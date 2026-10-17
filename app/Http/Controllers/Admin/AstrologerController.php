<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Astrologer;
use App\Model\Category;
use App\Model\Media;
use App\Model\Language;
use App\Model\Service;
use App\Model\CourseAlot;
use App\Model\AstrologerLanguage;
use App\Model\PayoutPackage;
use App\Model\AstrologerService;
use App\Model\AstrologerTimeTable;
use App\Model\Course;
use DB;
use App\Jobs\SendSms;
use App\Model\AstrologerAppointmentPackage;
use App\Model\AstrologerReview;
use App\Model\Badge;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Jobs\{AstrologerVerifiedJob};
use App\{temp_astrologer, Product};

class AstrologerController extends Controller
{
    private $module = 'astrologers';
    private $moduleKey = 'astrologer';
    private $moduleName = 'Astrologers';
    private $moduleNameSingle = 'Astrologer';
    private $astrologerType = 'Astrologer';

    public function __construct(){
        if(request()->url()){
          if(str_contains(url()->current(), 'Astrologer')){
            $this->moduleName = "Astrologer";
            $this->moduleNameSingle = "Astrologer";
            $this->astrologerType = "Astrologer";
            $this->moduleKey = 'astrologer';
          }elseif(str_contains(url()->current(), 'AI')){
            $this->moduleName = "AI Astrologer";
            $this->moduleNameSingle = "AI Astrologer";
            $this->astrologerType = "AI";
            $this->moduleKey = 'ai';
          }
        }

    }

    public function index(Request $request){
      $moduleKey = $this->moduleKey;
      $moduleName = $this->moduleName;
      $module = $this->module;
      $astrologerType = $this->astrologerType;
      if(!adminCan($moduleKey,'view')) return dd('No Permission');
      $nav = "";
      if($astrologerType == "Astrologer"){
        $nav = "Astrologer";
      }elseif($astrologerType == "AI"){
        $nav = "AI";
      }
      $breads = [
        ['name'=>$moduleName]
      ];
      return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
    }

    /**
     * Dashboard "Active Astrologers" — same listing UI/filters as main Astrologer list (scoped via active_dashboard_scope).
     */
    public function activeList(Request $request)
    {
      if (! adminCan('astrologer', 'view')) {
        return dd('No Permission');
      }
      $nav = 'Astrologer';
      $moduleName = 'Active Astrologers';
      $permissionModuleName = 'Astrologer';
      $activeDashboardScope = true;

      return view('admin.astrologers.list', compact('nav', 'moduleName', 'permissionModuleName', 'activeDashboardScope'));
    }

    /**
     * Dashboard "Active AI Astrologers" — same listing UI/filters as main AI list (scoped via active_ai_dashboard_scope).
     */
    public function activeAiList(Request $request)
    {
      if (! adminCan('ai', 'view')) {
        return dd('No Permission');
      }
      $nav = 'AI';
      $moduleName = 'Active AI Astrologers';
      $permissionModuleName = 'AI Astrologer';
      $activeAiDashboardScope = true;

      return view('admin.astrologers.list', compact('nav', 'moduleName', 'permissionModuleName', 'activeAiDashboardScope'));
    }

    /**
     * Dashboard "New Astrologer Requests" — type Astrologer, new = 1 (same table UI as Active Astrologers).
     */
    public function newRequestsList(Request $request)
    {
      if (! adminCan('astrologer', 'view')) {
        return dd('No Permission');
      }
      $nav = 'Astrologer';
      $moduleName = 'New Astrologer Requests';
      $permissionModuleName = 'Astrologer';
      $newRequestsScope = true;

      return view('admin.astrologers.list', compact('nav', 'moduleName', 'permissionModuleName', 'newRequestsScope'));
    }

     public function all(Request $request)
     {
       $module = $this->module;
       $moduleName = $this->moduleName;
       $astrologerType = $this->astrologerType;
       $moduleKey = $this->moduleKey;
        $results = Astrologer::whereType($astrologerType)->orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
        if ($astrologerType === 'Astrologer' && $request->boolean('active_dashboard_scope')) {
          $results->where('status', 1)
            ->where('is_verified', 1)
            ->where('is_delete_requested', 0)
            ->whereHas('appointments');
        }
        if ($astrologerType === 'AI' && $request->boolean('active_ai_dashboard_scope')) {
          $results->where('status', 1)
            ->where('is_verified', 1)
            ->where('is_delete_requested', 0)
            ->whereHas('aiAppointments', function ($q) {
              $q->where('paid', 1);
            });
        }
        if ($astrologerType === 'Astrologer' && $request->boolean('new_requests_scope')) {
          $results->where('new', 1);
        }
        if($uuid = $request->uuid) {
            $results->where('uuid', 'like', "%$uuid%");
        }
        if($display_name = $request->display_name) {
            $results->where('display_name', 'like', "%$display_name%");
        }
        if($email = $request->email) {
            $results->where('email', 'like', "%$email%");
        }
        if($phone = $request->phone) {
            $results->where('phone', 'like', "%$phone%");
        }
        if(isset($request->status) && is_numeric($request->status)) {
            $status = $request->status;
            $results->where('status', $status);
        }
        if(isset($request->is_verified) && is_numeric($request->is_verified)) {
            $is_verified = $request->is_verified;
            $results->where('is_verified', $is_verified);
        }
        if($trashed = $request->trashed){
          $results->onlyTrashed();
        }
        if($available_mode = $request->available_mode) {
          if(is_numeric($request->available_status)) {
            $results->where($available_mode, $request->available_status);
          }
        }elseif(is_numeric($request->available_status)) {
            $available_status = $request->available_status;
            $results->where(function($q) use($available_status) {
              if($available_status){
                $q->where('chat', $available_status)->orWhere('call', $available_status)->orWhere('video', $available_status);
              }else {
              $q->where('chat', $available_status)->where('call', $available_status)->where('video', $available_status);
              }
            });
        }
        if($updated_at = $request->updated_at) {
          $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
        $results = $results->paginate(13);
        $data = [
          'rows' => view('admin.'.$module.'.listrows', [
            'results' => $results,
            'astrologerType' => $astrologerType,
            'trashed' => $request->trashed,
            'moduleKey' => $moduleKey,
            'activeDashboardScope' => $request->boolean('active_dashboard_scope'),
            'activeAiDashboardScope' => $request->boolean('active_ai_dashboard_scope'),
            'newRequestsScope' => $request->boolean('new_requests_scope'),
          ])->render(),
          'pagination' => view('admin._partials.pagination', ['results'=>$results])->render(),
          'total'=>$results->total()
        ];
        return response()->json($data, 200);
     }

     public function form(Request $request){
        $module = $this->module;
        $moduleName = $this->moduleName;
        $astrologerType = $this->astrologerType;
        $nav = "";
        if($astrologerType == "Astrologer"){
          $nav = "addAstrologer";
        }elseif($astrologerType == "AI"){
          $nav = "addAI";
        }
        $id = $request->id;
        $breads = [
             ['name'=>$moduleName, 'url'=> route('admin.'.$astrologerType.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = Astrologer::findOrNew($id);
         $medias  = Media::where('type','astrologer')->get();
         $countries = DB::table('master_countries')->select('id', 'name')->orderBy('name','asc')->get();
         $languages = Language::orderBy('position', 'asc')->get();
         $services = Service::orderBy('name', 'asc')->get();
         $categories = Category::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
         $categories[0] = 'Select';
         ksort($categories);
         // return $categories;


         $timeTable = $item->timetable?$item->timetable->groupBy('week_day')->toArray():[];
         foreach(weekDays() as $wd=>$dayName) {
             if(!isset($timeTable[$wd])) {
               $timeTable[$wd] = [[
                   'week_day'=> $wd,
                   'from_date' => '',
                   'to_date' => ''
               ]];
             }
         }
         $payoutPackages = PayoutPackage::pluck('name', 'id')->toArray();
         $payoutPackages[0] = 'Select';
         ksort($payoutPackages);
         $courses = Course::where('status', 1)->pluck('name', 'id')->toArray();
         $courses[0] = 'Select';
         ksort($courses);
         $badges = Badge::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
         $badges[0] = 'Select';
         ksort($badges);
         $temp_astrologers = temp_astrologer::where('astrologer_id', $item->id)->where('status', 1)->get();

         // Fetch pujas for pandit assignment
         $oneToOnePujas = Product::where('type', 2)
                                  ->where('is_one_to_one_puja', 1)
                                  ->where('status', 1)
                                  ->where('parent_id', 0)
                                  ->orderBy('title', 'asc')
                                  ->get();

         // Group pujas: only main (non-variation) products
         $groupPujas = Product::where('type', 2)
                              ->where('parent_id', 0)
                              ->where(function($q) {
                                  $q->where('is_one_to_one_puja', 0)
                                    ->orWhereNull('is_one_to_one_puja');
                              })
                              ->where('status', 1)
                              ->orderBy('title', 'asc')
                              ->get();

         // Get selected pujas for this astrologer, normalize variations to main parent puja
         $selectedPujas = [];
         if($item->id) {
             $rawPujaIds = DB::table('puja_astrologers')
                               ->where('astrologer_id', $item->id)
                               ->where('status', 1)
                               ->pluck('product_id')
                               ->toArray();

             foreach($rawPujaIds as $pid) {
                 $product = Product::find($pid);
                 if(!$product) continue;
                 $mainId = $product->parent_id && $product->parent_id > 0 ? $product->parent_id : $product->id;
                 $selectedPujas[] = $mainId;
             }

             $selectedPujas = array_values(array_unique($selectedPujas));
         }

         return view('admin.'.$module.'.form', compact('categories', 'nav', 'breads', 'item', 'medias', 'payoutPackages', 'moduleName', 'countries', 'languages', 'services', 'timeTable', 'courses', 'astrologerType', "temp_astrologers", 'badges', 'oneToOnePujas', 'groupPujas', 'selectedPujas'));
     }

     public function submit(Request $request) {
       // dd($request->all());
        $id = $request->id;
        $module = $this->module;
        $moduleKey = $this->moduleKey;
        $moduleName = $this->moduleName;
        $moduleNameSingle = $this->moduleNameSingle;
        $astrologerType = $this->astrologerType;
        if($id && !adminCan($moduleKey, 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if(!$id && !adminCan($moduleKey, 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

        $validations = [];
        $isVerified = 1;
        if($astrologerType == 'Astrologer'){
          $validations=[
            'slug' => 'max:255|unique:astrologers,slug,'.$id.',id,deleted_at,NULL,type,'.$astrologerType,
            'firstname'    => 'required',
            'lastname'     => 'required',
            'email'        => 'required|email|unique:astrologers,email,'.$id.',id,deleted_at,NULL',
            'phone'        => 'required|min:10|max:10|unique:astrologers,phone,'.$id.',id,deleted_at,NULL',
            'price'        => 'required|numeric|regex:/^\d+(\.\d+)?$/',
            'chat_price'   => 'required|numeric|regex:/^\d+(\.\d+)?$/',
            'call_price'   => 'required|numeric|regex:/^\d+(\.\d+)?$/',
            'languages'    => 'required',
            'services'     => 'required',
            "pan_card"     => "nullable|min:10|max:10",
            'payout_package_id'     => 'required|numeric|gt:0',
            'gender'     => 'required|numeric|gt:0',
            'job_start_year'     => 'required',
          ];
          $isVerified = $request->is_verified;
        }else{
          $validations=[
            'slug' => 'max:255|unique:astrologers,slug,'.$id.',id,deleted_at,NULL,type,'.$astrologerType,
            'firstname'    => 'required',
            'lastname'     => 'required',
            'chat_price'   => 'required|numeric|regex:/^\d+(\.\d+)?$/',
            'languages'    => 'required',
            'services'     => 'required',
            'gender'     => 'required|numeric|gt:0',
            'job_start_year' => 'required',
          ];
        }

        $request->validate($validations,[
          "payout_package_id.required"=>"The Payout Package field is required",
          "payout_package_id.gt"=>"The Payout Package field is required",
          "gender.required"=>"The Gender field is required",
          "gender.gt"=>"The Gender field is required",
          "job_start_year.required"=>"The Start Year field is required",
        ]);

        if(($request->special_price > 0) || ($request->chat_special_price > 0) || ($request->call_special_price > 0)){
    $this->validate($request, [
             'sp_from_date' 		=> 'required|date',
             'sp_to_date' 		=> 'required|date|after:sp_from_date',
    ],[
             'sp_from_date.required' => 'Special price from date is required',
             'sp_to_date.required' => 'Special price to date is required',
    ]);
        }

        if($request->sp_from_date|| $request->sp_to_date){
          if($astrologerType == 'Astrologer'){
            $validations=[
              'special_price' 		=> 'required|numeric|gt:0|regex:/^\d+(\.\d+)?$/',
              'price'         => 'gt:special_price',
              'chat_special_price' 		=> 'required|numeric|gt:0|regex:/^\d+(\.\d+)?$/',
              'chat_price'         => 'gt:chat_special_price',
              'call_special_price' 		=> 'required|numeric|gt:0|regex:/^\d+(\.\d+)?$/',
              'call_price'         => 'gt:call_special_price',
    ];
          }else{
            $validations=[
              'chat_price'         => 'gt:chat_special_price',
          'chat_special_price' 		=> 'required|numeric|regex:/^\d+(\.\d+)?$/',
    ];
          }
    $request->validate($validations, [
             'special_price.required' => 'Video Special price is required',
             'chat_special_price.required' => 'Chat Special price is required',
             'call_special_price.required' => 'Call Special price is required',
    ]);
        }

        $country = DB::table('master_countries')->where('id', $request->country_id)->first();

        $item = Astrologer::findOrNew($id);
        if(is_null($item->id)){
            $item->register_from_ip  = request()->ip();
            // Ensure referral_code is null for new astrologers so boot() method can auto-generate it
            $item->referral_code = null;
        }
        $lastIsVerified = $item->is_verified;

        // Handle used_referral_code if provided (the referral code that this astrologer used during registration)
        if($request->has('used_referral_code') && $request->used_referral_code){
            $item->used_referral_code = trim($request->used_referral_code);
        }
        $item->payout_package_id        = $request->payout_package_id?:0;
        $item->last_status              = is_null($item->status) ? 0 : $item->status;
        $item->image_id 		            = $request->image_id?:0;
        $item->type                     = $astrologerType;
        $item->firstname                = $request->firstname;
        $item->lastname                 = $request->lastname;
        $item->display_name             = $request->firstname.' '.$request->lastname;
        $item->phone_code               = $country?$country->phonecode:'+91';
        $item->country_code             = $country?$country->sortname:'IN';
        $item->about                    = $request->about;
        $item->experience               = $request->experience;
        $item->education                = $request->education;
        $item->expertise                = $request->expertise;
        $item->price                    = $request->price?:0;
        $item->special_price            = $request->special_price?:0;
        $item->chat_price               = $request->chat_price?:0;
        $item->chat_special_price       = $request->chat_special_price?:0;
        $item->call_price               = $request->call_price?:0;
        $item->call_special_price       = $request->call_special_price?:0;
        $item->sp_from_date             = $request->sp_from_date;
        $item->rating                   = $request->rating?:0;
        $item->blue_tick                = $request->blue_tick?:0;
        $item->sp_to_date               = $request->sp_to_date;
        $item->job_start_year           = $request->job_start_year ?: date('Y');
        $item->address                  = $request->address;
        $item->city_id                  = $request->city_id;
        $item->state_id                 = $request->state_id;
        $item->country_id               = $request->country_id?:101;
        $item->pincode                  = $request->pincode;
        $item->chat                     = $request->chat;
        $item->call                     = $request->call;
        $item->video                    = $request->video;
        $item->status                   = $request->status;
        $item->is_verified              = $isVerified;
        $item->is_otp_verified          = $request->is_otp_verified?:0;
        $item->address_proof            = $request->address_proof;
        $item->pan_id                   = $request->pan_id;
        $item->id_proof                 = $request->id_proof;
        $item->signature                = $request->signature?:0;
        $item->bank_name                = $request->bank_name;
        $item->bank_account_name        = $request->bank_account_name;
        $item->bank_account_number      = $request->bank_account_number;
        $item->bank_account_number_verify= $request->bank_account_number_verify;
        $item->bank_ifsc_code           = $request->bank_ifsc_code;
        $item->bank_upi_id              = $request->bank_upi_id;
        $item->bank_branch              = $request->bank_branch;
        $item->bank_branch              = $request->bank_branch;
        $item->dob                      = $request->dob?date('Y-m-d', strtotime($request->dob)):'';
        $item->dob_yyyy                 = $request->dob?date('Y', strtotime($request->dob)):'';
        $item->dob_mm                   = $request->dob?date('m', strtotime($request->dob)):'';
        $item->dob_dd                   = $request->dob?date('d', strtotime($request->dob)):'';
        // $item->birth_time               = $request->birth_time?date('H:i:s', strtotime($request->birth_time)):'';
        // $item->birth_place              = $request->birth_place?:'';
        $item->commission               = $request->commission?:'';
        $item->pan_card                 = $request->pan_card;
        $item->commission_type          = $request->commission_type?:0;
        $item->gender 		              = $request->gender?:0;
        $item->blog_category_id 		    = $request->blog_category_id?:0;
        $item->meta_title= $request->meta_title;
        $item->meta_keywords= $request->meta_keywords;
        $item->meta_description= $request->meta_description;
        $item->can_go_live 		    = $request->can_go_live?:0;
        $item->first_chat_free = $request->first_chat_free ?: 0;
        $item->is_pandit = $request->is_pandit ? 1 : 0;
        $item->badge_id = $request->badge_id ?: null;
        $item->new = 0;
        $item->save();
        $item->email = $request->email?:strtolower(str_replace(" ", "", $item->display_name)).'@gmail.com';
        $item->phone = $request->phone?:str_pad($item->id, 10, '1', STR_PAD_LEFT);
        $item->order = $request->order?:$item->id;
        $item->slug = $request->slug ? str_slug($request->slug) : str_slug($item->display_name);
        $item->save();

        if(isset($request->languages)) {
            foreach($request->languages as $lang_id){
              if(!AstrologerLanguage::where(['astrologer_id'=>$item->id, 'language_id'=>$lang_id])->first()){
                $userLang =  new AstrologerLanguage();
                $userLang->astrologer_id = $item->id;
                $userLang->language_id = $lang_id;
                $userLang->save();
              }
            }
            AstrologerLanguage::where('astrologer_id', $item->id)->whereNotIn('language_id', $request->languages)->delete();
        }
        if(isset($request->services)){
            foreach($request->services as $service_id){
              if(!AstrologerService::where('astrologer_id', $item->id)->where('service_id', $service_id)->first()){
                $userService =  new AstrologerService();
                $userService->astrologer_id = $item->id;
                $userService->service_id = $service_id;
                $userService->save();
              }
            }
            AstrologerService::where('astrologer_id', $item->id)->whereNotIn('service_id', $request->services)->delete();
        }
        // dd($request->timings[1]['week_day']);

        AstrologerTimeTable::where('astrologer_id', $item->id)->delete();
        if(isset($request->days)) {
            // $timings = json_decode($request->timings, true);
            $timings = $request->timings;
            foreach($request->days as $day){
              if(isset($timings[$day])){
                // dd($timings[$day]);
                $newTime = new AstrologerTimeTable();
                $newTime->astrologer_id = $item->id;
                $newTime->week_day = $timings[$day]['week_day'];
                $newTime->from_time = $timings[$day]['from_time'];
                $newTime->to_time = $timings[$day]['to_time'];
                $newTime->save();
              }
            }
        }

        if(isset($request->packages)){
          $packages = json_decode($request->packages);
          $oldIds = collect($packages)->pluck('id');
          AstrologerAppointmentPackage::where('astrologer_id', $item->id)->whereNotIn('id', $oldIds)->delete();
          foreach($packages as $order=>$row){
            $package  = $row->id ? AstrologerAppointmentPackage::find($row->id) : new AstrologerAppointmentPackage();
            $package->name = $row->name;
            $package->price = $row->price;
            $package->minutes = $row->minutes;
            $package->status = $row->status;
            $package->astrologer_id = $item->id;
            $package->save();
          }
        }

        // Handle puja assignments (only if is_pandit is checked)
        if($item->is_pandit == 1) {
          $selectedPujas = [];
          if($request->one_to_one_pujas) {
            $selectedPujas = array_merge($selectedPujas, $request->one_to_one_pujas);
          }
          if($request->group_pujas) {
            $selectedPujas = array_merge($selectedPujas, $request->group_pujas);
          }

          // Remove duplicates
          $selectedPujas = array_unique($selectedPujas);

          // Delete existing puja assignments for this astrologer
          DB::table('puja_astrologers')->where('astrologer_id', $item->id)->delete();

          // Insert new puja assignments including their variations
          $priority = 0;
          $insertedProductIds = [];
          foreach($selectedPujas as $pujaId) {
            $product = Product::find($pujaId);
            if(!$product) {
              continue;
            }

            // Always work with main puja (parent) id
            $mainProductId = $product->parent_id && $product->parent_id > 0 ? $product->parent_id : $product->id;

            // Insert main puja assignment if not already inserted
            if(!in_array($mainProductId, $insertedProductIds)) {
              DB::table('puja_astrologers')->insert([
                'astrologer_id' => $item->id,
                'product_id' => $mainProductId,
                'priority' => $priority++,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
              ]);
              $insertedProductIds[] = $mainProductId;

              // Sync pandit assignment on group puja products (type = 2, non one-to-one)
              $mainProduct = Product::find($mainProductId);
              if($mainProduct && $mainProduct->type == 2 && (empty($mainProduct->is_one_to_one_puja) || $mainProduct->is_one_to_one_puja == 0)) {
                $mainProduct->pandit_id = $item->id;
                $mainProduct->pandit_name = $item->display_name ?: ($item->firstname . ' ' . $item->lastname);
                $mainProduct->save();
              }
            }

            // Find and assign all variation products for this main puja
            $variationProducts = Product::where('parent_id', $mainProductId)->get();
            foreach($variationProducts as $variation) {
              if(in_array($variation->id, $insertedProductIds)) {
                continue;
              }
              DB::table('puja_astrologers')->insert([
                'astrologer_id' => $item->id,
                'product_id' => $variation->id,
                'priority' => $priority++,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
              ]);
              $insertedProductIds[] = $variation->id;

              // Also sync pandit assignment on variation products (group puja)
              if($variation->type == 2 && (empty($variation->is_one_to_one_puja) || $variation->is_one_to_one_puja == 0)) {
                $variation->pandit_id = $item->id;
                $variation->pandit_name = $item->display_name ?: ($item->firstname . ' ' . $item->lastname);
                $variation->save();
              }
            }
          }
        } else {
          // If is_pandit is unchecked, remove all puja assignments
          DB::table('puja_astrologers')->where('astrologer_id', $item->id)->delete();
        }

        if($item->is_verified && !$lastIsVerified){
            $item->sendApprovedNotification();
        }
        $redirectTo = route('admin.'.$astrologerType.'.edit', [$item->id])  ;
        return response()->json(['message'=>$this->moduleName.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request){
       $moduleKey = $this->moduleKey;
       $moduleName = $this->moduleName;
       $module = $this->module;
       $astrologerType = $this->astrologerType;
       $id = $request->id;
       if($id && !adminCan($moduleKey, 'delete')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }

       if($id){
         $item = Astrologer::withTrashed()->find($id);
         if(is_null($item->deleted_at)){
           $item->status = 0;
           $item->is_verified = 0;
           $item->delete();
           if($item->is_delete_requested && $item->delete_request_on){
             $item->deleteAccountMailConfirmation();
           }
         }else{
           AstrologerTimeTable::where('astrologer_id', $id)->delete();
           AstrologerService::where('astrologer_id', $id)->delete();
           AstrologerLanguage::where('astrologer_id', $id)->delete();
           AstrologerAppointmentPackage::where('astrologer_id', $id)->delete();
           $item->forceDelete();
         }
         return response()->json(['message'=>'Deleted successfully!'], 200);
       }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function restore(Request $request){
        if($id = $request->id) {
          $item = Astrologer::withTrashed()->find($id);
          $item->restore();
        }
        return redirect()->route('admin.'.$this->astrologerType.'.index');
     }

     public function duplicate(Request $request){
       $module = $this->module;
       $moduleKey = $this->moduleKey;
       $moduleName = $this->moduleName;
       $astrologerType = $this->astrologerType;
       $id = $request->id;
       if($id && !adminCan($moduleKey, 'add')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       if($id){
         $astrologer = Astrologer::find($id);
         $newastrologer = $astrologer->replicate();
         $newastrologer->slug = $astrologer->slug.'-'.$id;
         $newastrologer->display_name = $astrologer->display_name.' Cloned';
         $newastrologer->slug = $astrologer->slug.'-'.Str::random(5);
         $newastrologer->api_token = Str::random(80);
         $newastrologer->email='';
         $newastrologer->phone='';
         $newastrologer->is_verified=0;
         $newastrologer->status=0;
         $newastrologer->save();

         $astrologerTimeTable = AstrologerTimeTable::where('astrologer_id', $id)->get();
         foreach($astrologerTimeTable as $cd){
           $ncd = $cd->replicate();
           $ncd->astrologer_id = $newastrologer->id;
           $ncd->save();
         }
         $astrologerService = AstrologerService::where('astrologer_id', $id)->get();
         foreach($astrologerService as $cb){
           $ncb = $cb->replicate();
           $ncb->astrologer_id = $newastrologer->id;
           $ncb->save();
         }

         $astrologerLanguage = AstrologerLanguage::where('astrologer_id', $id)->get();
         foreach($astrologerLanguage as $s){
           $ns = $s->replicate();
           $ns->astrologer_id = $newastrologer->id;
           $ns->save();
         }

         $astrologerAppointmentPackage = AstrologerAppointmentPackage::where('astrologer_id', $id)->get();
         foreach($astrologerAppointmentPackage as $s){
           $ns = $s->replicate();
           $ns->astrologer_id = $newastrologer->id;
           $ns->save();
         }

          return response()->json(['message'=>'Cloned successfully!'], 200);
       }
       return response()->json(['message'=>'Error in cloning data'], 401);
     }

     public function switch(Request $request){
       $module = $this->module;
       $moduleKey = $this->moduleKey;
       $moduleName = $this->moduleName;
       $astrologerType = $this->astrologerType;
       $id = $request->id;
       if(!$id){
         return redirect()->back();
       }else{
         $item = Astrologer::find($id);
         if(!$item){
           return redirect()->back();
         }
         if($item->type == 'Astrologer'){
           $item->type = 'AI';
           $item->save();
           return redirect()->route('admin.Astrologer.index');
         }else{
           $item->type = 'Astrologer';
           $item->save();
           return redirect()->route('admin.AI.index');
         }
       }
     }

     public function astrologerReview(Request $request){
       $module = "astrologerreview";
       $moduleKey = "";
       $moduleName = $this->moduleName;
       $astrologerType = $this->astrologerType;
       $nav = "";
       $status = $request->status ?: "";
       if($astrologerType == "Astrologer"){
         $moduleKey = "astrologer-review";
         $nav = "review";
         if($status == "inactive"){
           $nav = "pendingreview";
           $status = 0;
         }
       }elseif($astrologerType == "AI"){
         $moduleKey = "ai-review";
         $nav = "aireview";
         if($status == "inactive"){
           $nav = "aipendingReview";
           $status = 0;
         }
       }
       if(!adminCan($moduleKey, 'view')) return dd('No Permission');
       $breads = [
         ['name' => $moduleName, 'url'=> route("admin.$astrologerType.index")],
         ['name' => 'Review', 'url'=> route('admin.astrologer.review', $astrologerType)],
       ];
       return view('admin.astrologerreview.list', compact('nav', 'breads', 'astrologerType', 'status'));
     }

     public function reviewAll(Request $request){
       $module = $this->module;
       $moduleKey = "";
       $moduleName = $this->moduleName;
       $astrologerType = $this->astrologerType;
       if($astrologerType == "Astrologer"){
         $moduleKey = "astrologer-review";
       }elseif($astrologerType == "AI"){
         $moduleKey = "ai-review";
       }
       $reviews = AstrologerReview::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc')
       ->whereHas('astrologerInstructor', function ($query) use($astrologerType){
         $query->where('type', $astrologerType);
       });
       if($astrologer_id = $request->astrologer_id){
         $astrologers = Astrologer::where('display_name', 'like', "%$astrologer_id%")->pluck('id')->toArray();
         $reviews->whereIn('astrologer_id', $astrologers);
       }
        if($user_id = $request->user_id) {
          $users = User::where('name', 'like', "%$user_id%")->pluck('id')->toArray();
          $reviews->whereIn('user_id  ', $users);
        }
        if($review = $request->review) {
            $reviews->where('comment', 'like', "%$review%");
        }
        if($rating_from = $request->rating_from) {
            $reviews->where('review', '>=', (int)$rating_from);
        }
       if($rating_to = $request->rating_to) {
           $reviews->where('review', '<=', (int)$rating_to);
       }
       if(isset($request->status) && is_numeric($request->status)) {
         $reviews->where('status', $request->status);
       }

       if($updated_at_from = $request->updated_at_from) {
           $reviews->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to) {
           $reviews->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
       $reviews = $reviews->paginate(10);
       $data = [
           'rows' => view('admin.astrologerreview.listrows', ['results'=>$reviews, "astrologerType" => $astrologerType, 'moduleKey' => $moduleKey])->render(),
           'pagination' => view('admin._partials.pagination', ['results'=>$reviews])->render(),
       ];
       return response()->json($data, 200);
     }

     public function reviewForm(Request $request){
      $module = $this->module;
      $moduleKey = "";
      $moduleName = $this->moduleName;
      $astrologerType = $this->astrologerType;
      $nav = "";
      if($astrologerType == "Astrologer"){
        $nav = "addreview";
        $moduleKey = "astrologer-review";
      }elseif($astrologerType == "AI"){
        $nav = "aiaddreview";
        $moduleKey = "ai-review";
      }
      $id = $request->id;
      $breads = [
        ['name' => $moduleName, 'url'=> route("admin.$astrologerType.index")],
        ['name' => 'Review', 'url'=> route('admin.astrologer.review', $astrologerType)],
        ['name'=>($id ? 'Edit': 'Add')]
      ];
      $review = AstrologerReview::find($id);
      return view('admin.astrologerreview.form', compact('nav', 'breads', 'review', 'astrologerType'));
    }

     public function formSubmit(Request $request){
      $id = $request->id ?: 0;
      $module = $this->module;
      $moduleKey = "";
      $moduleName = $this->moduleName;
      $astrologerType = $this->astrologerType;
      $nav = "";
      if($astrologerType == "Astrologer"){
        $nav = "addreview";
        $moduleKey = "astrologer-review";
      }elseif($astrologerType == "AI"){
        $nav = "aiaddreview";
        $moduleKey = "ai-review";
      }
      if($id && !adminCan($moduleKey, 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if(!$id && !adminCan($moduleKey, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }

      $validatedData = $request->validate([
        'comments' => 'required|string',
        'status' => 'required',
      ]);

      $review = AstrologerReview::find($id);
      if($review){
        $review->comment = $request->comments;
        $review->status = $request->status;
        $review->save();
        return response()->json(['success' => true, 'message' => 'Review Submitted Successfully!']);
      }
      return response()->json(['success' => false, 'message' => 'Review not found.'], 404);
  }

  public function exportCsv(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $astrologerType = $this->astrologerType;
    $moduleKey = $this->moduleKey;
    $results = Astrologer::whereType($astrologerType)->orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
    if ($astrologerType === 'Astrologer' && $request->boolean('active_dashboard_scope')) {
      $results->where('status', 1)
        ->where('is_verified', 1)
        ->where('is_delete_requested', 0)
        ->whereHas('appointments');
    }
    if ($astrologerType === 'AI' && $request->boolean('active_ai_dashboard_scope')) {
      $results->where('status', 1)
        ->where('is_verified', 1)
        ->where('is_delete_requested', 0)
        ->whereHas('aiAppointments', function ($q) {
          $q->where('paid', 1);
        });
    }
    if ($astrologerType === 'Astrologer' && $request->boolean('new_requests_scope')) {
      $results->where('new', 1);
    }
    if($uuid = $request->uuid) {
      $results->where('uuid', 'like', "%$uuid%");
    }
    if($display_name = $request->display_name) {
      $results->where('display_name', 'like', "%$display_name%");
    }
    if($email = $request->email) {
      $results->where('email', 'like', "%$email%");
    }
    if($phone = $request->phone) {
      $results->where('phone', 'like', "%$phone%");
    }
    if(isset($request->status) && is_numeric($request->status)) {
      $status = $request->status;
      $results->where('status', $status);
    }
    if(isset($request->is_verified) && is_numeric($request->is_verified)) {
      $is_verified = $request->is_verified;
      $results->where('is_verified', $is_verified);
    }
    if($trashed = $request->trashed){
      $results->onlyTrashed();
    }
    if($available_mode = $request->available_mode) {
      if(is_numeric($request->available_status)) {
        $results->where($available_mode, $request->available_status);
      }
    }elseif(is_numeric($request->available_status)) {
      $available_status = $request->available_status;
      $results->where(function($q) use($available_status) {
        if($available_status){
          $q->where('chat', $available_status)->orWhere('call', $available_status)->orWhere('video', $available_status);
        }else {
        $q->where('chat', $available_status)->where('call', $available_status)->where('video', $available_status);
        }
      });
    }
    if($updated_at = $request->updated_at) {
      $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
    }

    $results = $results->get();

    $genders = ["Select", "Male", "Female", "Other"];

    $filePath = public_path('csvs/astrologers/list.csv');

    $handle = fopen($filePath, 'w');

    if($astrologerType == "AI"){
      fputcsv($handle, ["UUID", "Type", "Name", "Gender", "Status", "Created at"]);
      foreach ($results as $index => $item){
        fputcsv($handle, [
          $item->uuid,
          $item->type,
          $item->display_name,
          $genders[$item->gender],
          $item->status ? "Active" : "Inactive",
          $item->created_at
        ]);
      }
    }else{
      fputcsv($handle, ["UUID", "Type", "Name", "Email", "Phone", "Gender", "Status", "Verified", "Created at"]);
      foreach ($results as $index => $item){
        fputcsv($handle, [
          $item->uuid,
          $item->type,
          $item->display_name,
          $item->email,
          $item->phone,
          $genders[$item->gender],
          $item->status ? "Active" : "Inactive",
          $item->is_verified ? "Yes" : "No",
          $item->created_at
        ]);
      }
    }

    fclose($handle);

    $message = "Data Export Successfully";
    $redirectTo = url("csvs/astrologers/list.csv");

    return response()->json([
      'success'=>true,
      'message'=> $message,
      'redirectTo'=> $redirectTo,
    ], 200);
  }

  /**
   * Get Astrologer Suggestions for Autocomplete
   */
  public function getAstrologerSuggestions(Request $request)
  {
    $query = $request->get('q', '');
    $astrologerType = $this->astrologerType;

    if(strlen($query) < 2) {
      return response()->json([]);
    }

    $astrologers = Astrologer::whereType($astrologerType)
      ->where(function($q) use ($query) {
        $q->where('firstname', 'like', '%' . $query . '%')
          ->orWhere('lastname', 'like', '%' . $query . '%')
          ->orWhere('display_name', 'like', '%' . $query . '%');
      })
      ->select('id', 'firstname', 'lastname', 'display_name')
      ->limit(10)
      ->get()
      ->map(function($astrologer) {
        return [
          'id' => $astrologer->id,
          'name' => $astrologer->display_name ?: ($astrologer->firstname . ' ' . $astrologer->lastname),
          'display_name' => $astrologer->display_name,
          'firstname' => $astrologer->firstname,
          'lastname' => $astrologer->lastname
        ];
      });

        return response()->json($astrologers);
  }

 }
