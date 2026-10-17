<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Astrologer;
use App\temp_astrologer;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Jobs\{AstrologerVerifiedJob};

class TempAstroController extends Controller
{
    private $module = 'temp_astrologer';
    private $moduleName = 'Temp Astrologer';
    private $moduleNameSingle = 'tempAstrologer';
    private $moduleType = 'astrologer';

    public function __construct(){
      if(request()->route()){
        $this->module = 'temp_'.explode('/',request()->route()->getPrefix())[2];
        $this->moduleName = 'Temp '.ucfirst(explode('/',request()->route()->getPrefix())[2]);
        $this->moduleType = explode('/',request()->route()->getPrefix())[2];
      }
    }

    public function index(Request $request){
         $nav = $this->module;
         if(!adminCan('temporary-'.$this->moduleType.'-data','view')) return dd('No Permission');
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
        $results = temp_astrologer::where('status', 0)->where('type', $this->moduleType)->orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
        if($a_display_name = $request->a_display_name){
          $astrologer = Astrologer::where('display_name', 'like', "%$a_display_name%")->first();
          $astrologer_id = $astrologer ? $astrologer->id : 0;
          $results->where('astrologer_id', $astrologer_id);
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

        if($updated_at = $request->updated_at) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
        $results = $results->paginate(13);
        $nav = $this->module;
        $data = [
          'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'trashed'=>$request->trashed, 'nav'=>$nav])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
        ];
        return response()->json($data, 200);
     }


     public function form(Request $request){
        $id = $request->id;

        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$nav.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = temp_astrologer::findOrNew($id);
         $medias  = Media::where('type','astrologer')->get();
         $countries = DB::table('master_countries')->select('id', 'name')->orderBy('name','asc')->get();
         $languages = Language::orderBy('position', 'asc')->get();
         $services = Service::orderBy('name', 'asc')->get();
         $categories = Category::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
         $categories[0] = 'Select';
         ksort($categories);
         // return $categories;

         $timeTableTemp = $item->time_avaibility ? json_decode($item->time_avaibility, true) : [];
         $timeTable = [];
         foreach(weekDays() as $wd => $dayName) {
           if(count($timeTableTemp)){
             $itemm['week_day'] = $timeTableTemp[$wd][0]['week_day'];
             $itemm['to_time'] = isset($timeTableTemp[$wd][0]['to_time']) ? $timeTableTemp[$wd][0]['to_time'] : '';
             $itemm['from_time'] = isset($timeTableTemp[$wd][0]['from_time']) ? $timeTableTemp[$wd][0]['from_time'] : '';
             $timeTable[$wd] = $itemm;
             // array_push($timeTable, $itemm);
           }
         }
         $item->languages = $item->languages ? explode(',', $item->languages) : [];
         $item->expertise = $item->expertise ? explode(',', $item->expertise) : [];
         $astrologer = Astrologer::find($item->astrologer_id);

         return view('admin.'.$this->module.'.form', compact('categories', 'nav', 'breads', 'item', 'medias', 'moduleName', 'countries', 'languages', 'services', 'timeTable', 'astrologer'));
     }

     public function submit(Request $request) {
       // return $request->all();
        $id = $request->id;
         if(!$id && !adminCan('temporary-'.$this->moduleType.'-data', 'add')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
         if($id && !adminCan('temporary-'.$this->moduleType.'-data', 'update')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
        if($request->sp_from_date || $request->sp_to_date){
          $this->validate($request, [
            'sp_from_date'=>'required',
            'sp_to_date'=>'required',
          ]);
        }

        $astrologer = Astrologer::find($request->astrologer_id);
        $astrologer->chat_price = $request->chat_price ? $request->chat_price : $astrologer->chat_price;
        $astrologer->chat_special_price = $request->chat_special_price ? $request->chat_special_price : $astrologer->chat_special_price;
        $astrologer->call_price = $request->call_price ? $request->call_price : $astrologer->call_price;
        $astrologer->call_special_price = $request->call_special_price ? $request->call_special_price : $astrologer->call_special_price;
        $astrologer->price = $request->price ? $request->price : $astrologer->price;
        $astrologer->special_price = $request->special_price ? $request->special_price : $astrologer->special_price;
        $astrologer->sp_from_date = $request->sp_from_date ? $request->sp_from_date : $astrologer->sp_from_date;
        $astrologer->sp_to_date = $request->sp_to_date ? $request->sp_to_date : $astrologer->sp_to_date;
        $astrologer->save();

        $temp_astrologer = temp_astrologer::find($id);
        $temp_astrologer->status = 1;
        $temp_astrologer->is_publish = 1;
        $temp_astrologer->review_status = 'publish';
        $temp_astrologer->save();

        $redirectTo = route('admin.'.$this->module.'.edit', [$id]) ;
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan($this->module,'delete')) return dd('No Permission');
       if($id = $request->id) {
         $item = Astrologer::withTrashed()->find($id);
         if(is_null($item->deleted_at)){
           $item->delete();
         }
         else {
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

     public function restore(Request $request)
     {
        if($id = $request->id) {
          $item = Astrologer::withTrashed()->find($id);
          $item->restore();
        }
        return redirect()->route('admin.'.$this->moduleName.'.index');
     }

     public function duplicate(Request $request){
       if($id = $request->id) {
         $astrologer = Astrologer::find($id);
         $newastrologer = $astrologer->replicate();
         $newastrologer->slug = $astrologer->slug.'-'.$id;
         $newastrologer->display_name = $astrologer->display_name.' Cloned';
         $newastrologer->slug = $astrologer->slug.'-'.Str::random(5);
         $newastrologer->api_token = Str::random(80);
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

          return response()->json(['message'=>'Course Cloned successfully!'], 200);
       }
       return response()->json(['message'=>'Error in cloning data'], 401);
     }

     public function switch(Request $request){
       $id = $request->id;
       if(!$id){
         return redirect()->back();
       }else{
         $item = Astrologer::find($id);
         if(!$item){
           return redirect()->back();
         }
         if($item->type == 'Astrologer'){
           $item->type = 'Instructor';
           $item->save();
           return redirect()->route('admin.Astrologer.index');
         }else{
           $item->type = 'Astrologer';
           $item->save();
           return redirect()->route('admin.Instructor.index');
         }
       }
     }
}
