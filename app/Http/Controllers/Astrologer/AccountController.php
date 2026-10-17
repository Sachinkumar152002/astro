<?php

namespace App\Http\Controllers\Astrologer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Astrologer;
use App\Model\PujaAstrologerAssignment;
use App\User;
use App\Model\Withdraw;
use App\Model\Media;
use App\Model\Language;
use App\Model\ConsultationRequest;
use App\Model\ConsultationCounter;
use App\Model\Service;
use App\Model\AstrologerLanguage;
use App\Model\AstrologerService;
use App\Model\AstrologerTimeTable;
use App\Model\AstrologerAppointment;
use App\Model\RechargePlan;
use App\Model\Coupon;
use App\Model\UserRechargeHistory;
use App\Model\Wishlist;
use App\Model\NewsletterSubscriber;
use App\Model\Support;
use App\Model\SupportReplies;
use App\Model\Setting;
use App\Model\Chat;
use App\{Product,CartItem,Order,OrderItem,UserCourse,AstrologerUserConsultacy,temp_astrologer};
use App\Model\ChatLog;
use Auth;
use DB;
use PDF;
use File;
use Image;
use Illuminate\Support\Str;
use App\Model\CallHistory;
use Carbon\Carbon;
use App\Http\Controllers\Admin\MediaController;
use App\Jobs\SendSms;
use App\Jobs\RechargeInvoiceMailJob;
use App\Jobs\SupportUserJob;
use App\Jobs\ConsultationCancelJob;
use App\Jobs\{ConstultatonRescheduleJob, WithdrawNotificationJob,UserNotificationJob};
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SupportNotification;
use App\Notifications\SupportAdminNotification;
use App\Notifications\AccountDeleteRequestAdminNotification;
use App\Notifications\EmailWithCustomTextNotification;
use App\Model\Course;
use App\Model\CourseModule;
use App\Model\CourseExplore;
use App\Model\CourseExploreModule;
use App\CourseModuleContent;
use App\Model\CourseModuleQuestion;
use App\Model\AssignmentAnswer;
use App\Model\{Advertisement,AstrologerLive, AstrologerLiveUser, WalletTransition, SendGift, Gift,KundaliEnquiry,Cms, AstrologerReferral};
use App\Http\Controllers\ProductController;
use App\Model\Notification as NotificationModel;
use App\src\{RtcTokenBuilder2, RtmTokenBuilder2};

class AccountController extends Controller {

  /**
   * Shown when astrologer is logged in but application is not yet approved by admin.
   * Until approved, they only see this page (middleware redirects dashboard etc. here).
   */
  public function applicationPending(){
    $astrologer = auth()->guard('astrologer')->user();
    if ($astrologer->is_verified) {
      return redirect()->route('astrologer.dashboard');
    }
    $applicationId = $astrologer->uuid;
    $hideHeaderNavigation = true;
    return view(themeView().'astrologers.auth.application-pending', compact('applicationId', 'hideHeaderNavigation'));
  }

  public function index(){
      $nav = 'dashboard';
      $astrologer = auth()->guard('astrologer')->user();
      $user_type = 'astrologer';

      if($astrologer->chat > 0){
        $astrologer->chat = 1;
      }

      if($astrologer->call > 0){
        $astrologer->call = 1;
      }

      if($astrologer->video > 0){
        $astrologer->video = 1;
      }

      $astrologer->save();

      $chatConsultations = AstrologerAppointment::where("package", 2)->where('astrologer_id', $astrologer->id)->where('status', 'done')->orderBy("id", "DESC")->get();
      $callConsultations = AstrologerAppointment::where("package", 3)->where('astrologer_id', $astrologer->id)->where('status', 'done')->orderBy("id", "DESC")->get();
      $videoConsultations = AstrologerAppointment::where("package", 1)->where('astrologer_id', $astrologer->id)->where('status', 'done')->orderBy("id", "DESC")->get();
      $giftsId = SendGift::where('astrologer_id', $astrologer->id)->pluck('gift_id')->toArray();
      $gifts = Gift::whereIn("id", $giftsId)->orderBy("id", "DESC")->get();
      $withdrawAmount = Withdraw::where('user_id', $astrologer->id)->where('user_type', $user_type)->where('status', 'done')->sum('amount');
      $walletTransitions = WalletTransition::where('user_id', $astrologer->id)->where('user_type', $user_type)->where('status', 1)->orderBy("id", "DESC")->get();
      $supports = Support::where('user_id', $astrologer->id)->where("user_type", $user_type)->orderBy('id', 'DESC')->limit(5)->get();

      if($astrologer->chat || $astrologer->call || $astrologer->video){
        $loginController = new LoginController();
        $loginController->userNotification($astrologer, 'user', 'astrologer online');
      }

      $astrologerLive = AstrologerLive::where('astrologer_id', $astrologer->id)->where("status", 'scheduled')->orderBy('start_at', 'DESC')->first();
      $hideHeaderNavigation = true;
      $hideFooter = true;
      $policies = Cms::where('status',1)->where('slug','astrologer-policies')->get();

      // Get astrologer referral settings (single record with global settings)
      $referralSettings = AstrologerReferral::where('status', 1)->first();

      return view(themeView().'astrologers.account.dashboard', compact("nav", "astrologer", "chatConsultations", "callConsultations", "videoConsultations", "gifts", "withdrawAmount", "walletTransitions", "hideHeaderNavigation", "hideFooter", "supports",'astrologerLive','policies', 'referralSettings'));
    }

    public function profile(Request $request)
    {
      $nav = 'profile';
      $astrologer = auth()->guard('astrologer')->user();
      $tabs = ["my-profile", "address-details", "skills", "languages", "prices", "bank-details", "documents", "change-phone-number", "change-email-address"];
      $tab = $request->tab;

      if(!in_array($tab, $tabs)){
        $tab = "my-profile";
      }

      $countries = [];
      $states = [];
      $cities = [];

      if($tab == "address-details"){
        $countries = DB::table('master_countries')->where("id", 101)->orderBy('name','asc')->get();

        if($country_id = $astrologer->country_id){
          $states = DB::table('master_states')->where("country_id", $country_id)->orderBy('name','asc')->get();

          if($state_id = $astrologer->state_id){
            $cities = DB::table('master_cities')->where("state_id", $state_id)->orderBy('name','asc')->get();
          }
        }
      }

      $astrologerLanguages = [];

      if($tab == "languages"){
        $astrologerLanguages = AstrologerLanguage::where("astrologer_id", $astrologer->id)->pluck("language_id")->toArray();
      }

      $astrologerServices = [];

      if($tab == "skills"){
        $astrologerServices = AstrologerService::where("astrologer_id", $astrologer->id)->pluck("service_id")->toArray();
      }

      $tempAstrologer = null;

      if($tab == "prices"){
        $tempAstrologer = temp_astrologer::where("astrologer_id", $astrologer->id)->where("status", 0)->where("is_publish", 0)->first();
      }

      $languages = Language::orderBy('position', 'asc')->get();
      $services = Service::orderBy('name', 'asc')->get();

      $hideHeaderNavigation = true;
      $hideFooter = true;
      return view(themeView().'astrologers.account.profile', compact("hideHeaderNavigation", 'astrologer', "tab", 'countries', "states", "cities", 'languages', "services", "hideFooter", "astrologerLanguages", "astrologerServices", "tempAstrologer"));
    }

    public function status(Request $request)
    {
      $nav = 'calls';
      $astrologer = auth()->guard('astrologer')->user();
      $day = daysIndex(strtolower(date('l')));
      $current_time = date('H:i:s');
      $availability = AstrologerTimeTable::where('astrologer_id', $astrologer->id)->where('week_day', $day)->where('from_time', '<=', $current_time)->where('to_time', '>=', $current_time)->first();
      $id = $astrologer->id;
      $history = CallHistory::where('astrologer_id', $id)->where('user_status', 'Answered')->where('system_call_status',1);
      $totalCharge = $history->sum('astrologer_amount');
      $history = $history->paginate(20);

      $hideHeaderNavigation = true;
      $hideFooter = true;

      return view(themeView().'astrologers.account.status', compact("hideHeaderNavigation", "hideFooter", 'totalCharge', 'nav', 'history', 'astrologer', 'availability'));
    }

    public function chat(Request $request)
    {
      $nav = 'chat';
      return view(themeView().'astrologers.account.chat');
    }

    public function earnings(Request $request)
    {
      $nav = 'earnings';
      $astrologer = auth()->guard('astrologer')->user();
      $day = daysIndex(strtolower(date('l')));
      $current_time = date('H:i:s');

      $totalVideoAmount = WalletTransition::where('user_id', $astrologer->id)
      ->where('user_type', 'astrologer')
      ->where('table_name', 'astrologer_appointments')
      ->where('status', 1)
      ->where('action', 1)
      ->whereHas('appointments', function($query){
        $query->where('package', 1);
      })
      ->sum('amount');

      $totalChatAmount = WalletTransition::where('user_id', $astrologer->id)
      ->where('user_type', 'astrologer')
      ->where('table_name', 'astrologer_appointments')
      ->where('status', 1)
      ->where('action', 1)
      ->whereHas('appointments', function($query){
        $query->where('package', 2);
      })
      ->sum('amount');

      $totalCallAmount = WalletTransition::where('user_id', $astrologer->id)
      ->where('user_type', 'astrologer')
      ->where('table_name', 'astrologer_appointments')
      ->where('status', 1)
      ->where('action', 1)
      ->whereHas('appointments', function($query){
        $query->where('package', 3);
      })
      ->sum('amount');

      $totalAmount = $astrologer->wallet;

      $withdraws = Withdraw::where('user_id', $astrologer->id)->where('user_type', 'astrologer')->get();

      $withdrawAmount = $withdraws->where('status', 'done')->sum('amount');

      $minumum_withraw_amount = Setting::where('key', 'minumum_withraw_amount')->first();
      $minumum_withraw_amount = ($minumum_withraw_amount && $minumum_withraw_amount->value ? (double) $minumum_withraw_amount->value : 0);

      $maximum_withraw_amount = Setting::where('key', 'maximum_withraw_amount')->first();
      $maximum_withraw_amount = ($maximum_withraw_amount && $maximum_withraw_amount->value ? (double) $maximum_withraw_amount->value : 0);

      $hold_withraw_amount = Setting::where('key', 'hold_withraw_amount')->first();
      $hold_withraw_amount = ($hold_withraw_amount && $hold_withraw_amount->value ? (double) $hold_withraw_amount->value : 0);

      $availableAmount = $totalAmount - $hold_withraw_amount;
      if($availableAmount < 0){
        $availableAmount = 0;
      }

      $transitions = WalletTransition::where('user_id', $astrologer->id)
      ->where('user_type', 'astrologer')
      ->where('status', 1)
      ->orderBy('id', 'desc')
      ->paginate(15);

      $hideHeaderNavigation = true;
      $hideFooter = true;

      return view(themeView().'astrologers.account.earnings',compact("hideHeaderNavigation", "hideFooter", 'astrologer', 'totalVideoAmount', 'totalChatAmount', 'totalCallAmount', 'totalAmount', 'withdrawAmount', 'availableAmount', 'minumum_withraw_amount','maximum_withraw_amount', 'hold_withraw_amount', 'transitions'));
    }

    public function withdraws(Request $request)
    {
      $nav = 'withdraws';
      $user = auth()->guard('astrologer')->user();
      $user_type = 'astrologer';
      $withdrawHistories = Withdraw::where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'desc')->get();

      $hideHeaderNavigation = true;
      $hideFooter = true;

      return view(themeView().'astrologers.account.withdraw_history', compact("hideHeaderNavigation", "hideFooter", 'withdrawHistories') );
    }

    public function submitWithrawRequest(Request $request) {
      $this->validate($request, [
        'requested_amount'=>'required|numeric|gt:0',
      ],[
        'requested_amount.required' => 'Amount is Required.',
        'requested_amount.numeric' => 'Amount Must be in numbers.',
        'requested_amount.gt' => 'Amount Must greater than zero.',
      ]);

      $requested_amount = (double) $request->requested_amount;

      $user = auth()->guard('astrologer')->user();
      $user_type = 'astrologer';

      $pendingRequests = Withdraw::where('user_id', $user->id)->where('user_type', $user_type)->where('status', 'pending')->count();

      if($pendingRequests){
        return response()->json(['success'=>false, 'message'=>'You already have pending withdraw requests.'], 402);
      }

      $minimumAmount = Setting::where('key', 'minumum_withraw_amount')->first();
      $minimumAmount = ($minimumAmount && $minimumAmount->value ? (double) $minimumAmount->value : 0);
      if($minimumAmount > $requested_amount){
        return response()->json(['success'=>false, 'message'=>'Amount must be ₹'.$minimumAmount.' or greater  than ₹'.$minimumAmount], 402);
      }

      $maximumAmount = Setting::where('key', 'maximum_withraw_amount')->first();
      $maximumAmount = ($maximumAmount && $maximumAmount->value ? (double) $maximumAmount->value : 0);
      if($maximumAmount < $requested_amount){
        return response()->json(['success'=>false, 'message'=>'Amount must be ₹'.$maximumAmount.' or lesser  than ₹'.$maximumAmount], 402);
      }

      $totalAmount = $user->wallet;

      $withdrawedAmount = Withdraw::where('user_id', $user->id)->where('user_type', $user_type)->where('status', 'done')->sum('amount');

      $holdAmount = Setting::where('key', 'hold_withraw_amount')->first();
      $holdAmount = ($holdAmount && $holdAmount->value ? (double) $holdAmount->value : 0);

      $availableAmount = $totalAmount - $withdrawedAmount - $holdAmount;
      if($availableAmount < $requested_amount){
        return response()->json(['success'=>false, 'message'=>"You don't have enough balance to process this request."], 402);
      }

      $withdraw = new Withdraw();
      $withdraw->user_id = $user->id;
      $withdraw->user_type = $user_type;
      $withdraw->amount = $requested_amount;
      $withdraw->status = 'pending';
      $withdraw->save();

      $user->wallet -= $requested_amount;
      $user->save();

      WithdrawNotificationJob::dispatch($withdraw, $user->display_name, $user->email, null, $user, 'astrologer', 'pending');
      WithdrawNotificationJob::dispatch($withdraw, 'Admin', config('vrdcms.admin_email'), null, $user, 'admin', 'pending');
      return response()->json(['success'=>true, 'message'=>'Your request have been submitted. please wait 2-3 working days.'], 200);
    }

    public function profileSave(Request $request){
      $astrologer = auth()->guard('astrologer')->user();
      $astrologer_id = $astrologer->id;
      $request->validate([
        "tab" => "required|in:my-profile,address-details,languages,skills,prices,bank-details,documents"
      ]);
      $tab = $request->tab;
      $message = "Saved Successfully";

      if($tab == "my-profile"){
        $request->validate([
          "first_name" => "required|max:255",
          "last_name" => "required|max:255",
          "gender" => "required|gt:0",
          "birth_date" => "required|date",
          "job_start_year" => "required",
          "qualification" => "required|max:255",
          "working_with_other_app" => "required|in:0,1",
          "about" => "required",
          "instagram" => "nullable|url|max:255",
          "facebook" => "nullable|url|max:255",
          "youtube" => "nullable|url|max:255",
        ],[
          "gender" => "The Gender field is required.",
        ]);

        if($request->hasFile('image')){
          $request->validate([
            "image" => "required|image|mimes:jpg,jpeg,png,webp,gif|max:5120",
          ]);
          $mediaController = new \App\Http\Controllers\MediaController();
          $newRequest = new Request([
            'file' => $request->file('image'),
            'type' => 'astrologer',
          ]);
          $data = $mediaController->upload($newRequest);
          $response = $data->getData();
          $astrologer->image_id = $response->media_id;
        }

        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $full_name = "$first_name $last_name";
        $uuid = $astrologer->uuid;
        $jobStartYear = date("Y", strtotime("now -".(int)$request->job_start_year." years"));

        $astrologer->firstname = $first_name;
        $astrologer->lastname = $last_name;
        $astrologer->display_name = $full_name;
        $astrologer->slug = str_slug("$full_name $uuid");
        $astrologer->gender = $request->gender;
        $astrologer->dob = $request->birth_date;
        $astrologer->job_start_year = $jobStartYear;
        $astrologer->qualification = $request->qualification;
        $astrologer->working_with_other_app = (int) $request->working_with_other_app;
        $astrologer->about = $request->about;
        $astrologer->instagram = $request->instagram ?: null;
        $astrologer->facebook = $request->facebook ?: null;
        $astrologer->youtube = $request->youtube ?: null;
        $astrologer->save();

      }elseif($tab == "address-details"){
        $request->validate([
          "address" => "required",
          "landmark" => "required",
          "country_id" => "required|gt:0",
          "state_id" => "required|gt:0",
          "city_id" => "required|gt:0",
          "pincode" => "required|digits:6",
        ],[
          "country_id" => "The Country field is required.",
          "state_id" => "The State field is required.",
          "city_id" => "The City field is required.",
        ]);

        $astrologer->address = $request->address;
        $astrologer->area = $request->area ?: null;
        $astrologer->landmark = $request->landmark;
        $astrologer->country_id = $request->country_id;
        $astrologer->state_id = $request->state_id;
        $astrologer->city_id = $request->city_id;
        $astrologer->pincode = $request->pincode;
        $astrologer->save();
      }elseif($tab == "languages"){
        $request->validate([
          "languages" => "required|array",
        ],[
          "languages" => "The Languages field is required.",
        ]);

        $languages = $request->languages;
        foreach($languages as $key => $value){
          if(AstrologerLanguage::where("astrologer_id", $astrologer_id)->where("language_id", $value)->count() == 0){
            $astrologerLanguage = new AstrologerLanguage();
            $astrologerLanguage->astrologer_id = $astrologer_id;
            $astrologerLanguage->language_id = $value;
            $astrologerLanguage->save();
          }
        }
        AstrologerLanguage::where("astrologer_id", $astrologer_id)->whereNotIn("language_id", $languages)->delete();
      }elseif($tab == "skills"){
        $request->validate([
          "services" => "required|array",
          "any_healing" => "required|in:0,1",
        ],[
          "services" => "The Skills field is required.",
          "any_healing" => "Please specify if you offer any healing.",
        ]);

        $astrologer->any_healing = (int) $request->any_healing;
        $astrologer->save();

        $services = $request->services;
        foreach($services as $key => $value){
          if(AstrologerService::where("astrologer_id", $astrologer_id)->where("service_id", $value)->count() == 0){
            $astrologerLanguage = new AstrologerService();
            $astrologerLanguage->astrologer_id = $astrologer_id;
            $astrologerLanguage->service_id = $value;
            $astrologerLanguage->save();
          }
        }
        AstrologerService::where("astrologer_id", $astrologer_id)->whereNotIn("service_id", $services)->delete();
      }elseif($tab == "prices"){
        $request->validate([
          "chat_price" => "required|gt:0",
          "call_price" => "required|gt:0",
          "video_price" => "required|gt:0",
        ]);

        $special_price_from_date = $request->filled('special_price_from_date') ? $request->special_price_from_date : null;
        $special_price_to_date = $request->filled('special_price_to_date') ? $request->special_price_to_date : null;
        $chat_special_price = $request->filled('chat_special_price') && (float) $request->chat_special_price > 0 ? $request->chat_special_price : null;
        $call_special_price = $request->filled('call_special_price') && (float) $request->call_special_price > 0 ? $request->call_special_price : null;
        $video_special_price = $request->filled('video_special_price') && (float) $request->video_special_price > 0 ? $request->video_special_price : null;

        if($special_price_from_date || $special_price_to_date || $chat_special_price !== null || $call_special_price !== null || $video_special_price !== null){
          $request->validate([
            "special_price_from_date" => "required",
            "special_price_to_date" => "required",
            "chat_special_price" => "required|gt:0",
            "call_special_price" => "required|gt:0",
            "video_special_price" => "required|gt:0",
          ]);
        }

        $chat_price = $request->chat_price;
        $call_price = $request->call_price;
        $video_price = $request->video_price;

        $temp_astrologer = temp_astrologer::where("astrologer_id", $astrologer_id)->where("status", 0)->where("is_publish", 0)->first();
        if(!$temp_astrologer){
          $temp_astrologer = new temp_astrologer();
        }

        $temp_astrologer->astrologer_id = $astrologer_id;
        $temp_astrologer->chat_price = $chat_price;
        $temp_astrologer->chat_special_price = $chat_special_price;
        $temp_astrologer->call_price = $call_price;
        $temp_astrologer->call_special_price = $call_special_price;
        $temp_astrologer->price = $video_price;
        $temp_astrologer->special_price = $video_special_price;
        $temp_astrologer->sp_from_date = $special_price_from_date;
        $temp_astrologer->sp_to_date = $special_price_to_date;
        $temp_astrologer->status = 0;
        $temp_astrologer->is_publish = 0;
        $temp_astrologer->save();

        $message = "Saved Successfully. Please wait for approval.";
      }elseif($tab == "bank-details"){
        $request->validate([
          "bank_name" => "required|max:255",
          "ifsc_code" => "required|max:20",
          "account_holder_name" => "required|max:255",
          "account_number" => "required|max:50",
          "confirm_account_number" => "required|same:account_number",
        ],[
          "confirm_account_number.same" => "Account number and confirm account number must match.",
        ]);

        $astrologer->bank_name = $request->bank_name;
        $astrologer->bank_ifsc_code = $request->ifsc_code;
        $astrologer->bank_city = $request->bank_city ?: null;
        $astrologer->bank_state = $request->bank_state ?: null;
        $astrologer->bank_upi_id = $request->upi_id ?: null;
        $astrologer->bank_account_name = $request->account_holder_name;
        $astrologer->bank_account_number = $request->account_number;
        $astrologer->save();
      }elseif($tab == "documents"){
        $request->validate([
          "aadhaar_number" => "required|digits:12",
          "pan_card" => "required|string|size:10|regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/",
        ],[
          "aadhaar_number.required" => "Aadhaar number is required.",
          "aadhaar_number.digits" => "Aadhaar number must be 12 digits.",
          "pan_card.required" => "PAN Card number is required.",
          "pan_card.regex" => "Please enter a valid PAN number (e.g. ABCDE1234F).",
        ]);

        $astrologer->aadhaar_number = $request->aadhaar_number;
        $astrologer->pan_card = strtoupper($request->pan_card);

        $mediaControllerPdf = new \App\Http\Controllers\MediaController();

        if($request->hasFile('aadhaar_front')){
          $request->validate(["aadhaar_front" => "file|mimes:jpg,jpeg,png,webp,pdf|max:5120"]);
          $newRequest = new Request(['file' => $request->file('aadhaar_front'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaControllerPdf->upload($newRequest);
          $astrologer->aadhaar_front_id = $data->getData()->media_id;
          $astrologer->address_proof = $data->getData()->media_id;
        }
        if($request->hasFile('aadhaar_back')){
          $request->validate(["aadhaar_back" => "file|mimes:jpg,jpeg,png,webp,pdf|max:5120"]);
          $newRequest = new Request(['file' => $request->file('aadhaar_back'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaControllerPdf->upload($newRequest);
          $astrologer->aadhaar_back_id = $data->getData()->media_id;
        }
        if($request->hasFile('pancard')){
          $request->validate(["pancard" => "file|mimes:jpg,jpeg,png,webp,pdf|max:5120"]);
          $newRequest = new Request(['file' => $request->file('pancard'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaControllerPdf->upload($newRequest);
          $astrologer->pan_id = $data->getData()->media_id;
        }
        if($request->hasFile('cancel_cheque')){
          $request->validate(["cancel_cheque" => "file|mimes:jpg,jpeg,png,webp,pdf|max:5120"]);
          $newRequest = new Request(['file' => $request->file('cancel_cheque'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaControllerPdf->upload($newRequest);
          $astrologer->cancel_cheque_id = $data->getData()->media_id;
        }
        if($request->hasFile('astrology_certificate')){
          $request->validate(["astrology_certificate" => "file|mimes:jpg,jpeg,png,webp,pdf|max:5120"]);
          $newRequest = new Request(['file' => $request->file('astrology_certificate'), 'type' => 'astrologer', 'allow_pdf' => true]);
          $data = $mediaControllerPdf->upload($newRequest);
          $astrologer->astrology_certificate_id = $data->getData()->media_id;
        }
        $astrologer->save();
      }
      $redirectTo = route('astrologer.profile', ["tab" => $tab]);
      if($_token=$request->_token){
        $redirectTo = route('astrologer.profile', ["tab" => $tab,'_token'=>$_token]);
      }
      return response()->json(["success" => true, "message" => $message, "redirectTo" => $redirectTo], 200);
    }

public function recharge(Request $request){
    $nav = 'recharge';
    $user = auth()->guard('astrologer')->user();
    $option = $request->option;
    $plans = RechargePlan::where('status', 1)->orderBy('amount', 'asc')->get();
    return view(themeView().'astrologers.account.recharge', compact('nav', 'user', 'option', 'plans'));
}


public function couponApply(Request $request)
{
    $code = $request->code;
    $plan = $request->plan;

    $today = date('Y-m-d');

    $coupon = Coupon::where('status', 1)->where('code', $code)
    ->where(function($from_date) use ($today){
        $from_date->where('from_date', '<=', $today)->orWhereNull('from_date')->orWhere('from_date', '1970-01-01')->orWhere('from_date', '0000-00-00');
    })
    ->where(function($to_date) use ($today){
        $to_date->where('to_date', '>=', $today)->orWhereNull('to_date')->orWhere('to_date', '1970-01-01')->orWhere('to_date', '0000-00-00');
    })->first();

    $conditions = $coupon->conditions ? json_decode($coupon->conditions, true) : [];

    if(isset($conditions['recharge_plan']) && count($conditions['recharge_plan'])>0 && !in_array($plan, $conditions['recharge_plan'])) $coupon = null;

    $request->session()->put('coupon_code', $code);
    if($coupon){
        return response()->json(['fixed_perc'=>$coupon->fixed_perc, 'discount'=>$coupon->amount, 'message'=>'Coupon Applied', 'status'=>1], 200);
    }
    return response()->json(['fixed_perc'=>0, 'discount'=>0, 'message'=>'Invalide Applied', 'status'=>0], 200);
}

  public function makeRecharge(Request $request){
    $plan = $request->selectedplan;
    $code = $request->coupon_code;
    $uid = $request->uid;
    $today = date('Y-m-d');
    // $coupon = Coupon::where('status', 1)->where('code', $code)
    // ->where(function($from_date) use ($today){
    //     $from_date->where('from_date', '<=', $today)->orWhereNull('from_date')->orWhere('from_date', '1970-01-01')->orWhere('from_date', '0000-00-00');
    // })
    // ->where(function($to_date) use ($today){
    //     $to_date->where('to_date', '>=', $today)->orWhereNull('to_date')->orWhere('to_date', '1970-01-01')->orWhere('to_date', '0000-00-00');
    // })->first();
    $currentDate = date('Y-m-d');
    $myPlan = RechargePlan::find($plan);
    if($myPlan->from_date){
      if( ($currentDate < $myPlan->from_date) || ($currentDate > $myPlan->to_date) ){
        return response()->json(['success'=>false, 'message'=>'Invalid coupon.'], 402);
      }
    }
    $amount = $myPlan->special_amount>0?$myPlan->special_amount:$myPlan->amount;
    $discount = 0;
    // if($coupon){
    //   if($coupon->fixed_perc){
    //       $discount = ($amount*$coupon->amount)/100;
    //   }elseif($amount>=$coupon->amount){
    //       $discount = $amount - $coupon->amount;
    //   }elseif($amount<=$coupon->amount){
    //       $discount = $amount;
    //   }
    // }
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $table = null;
    $couponAmount = 0;
    $couponCode = '';
    $couponId = 0;
    if($request->coupon_code){
      $newRequest = new Request([
        'callType'=>'not_ajax',
        'type'=>'recharge',
        'coupon_code'=>$request->coupon_code,
        'plan_id'=>$myPlan->id,
      ]);
      $productController = new ProductController();
      $couponData = $productController->applyCouponCode($newRequest);
      $couponData = $couponData->getData();
      $couponAmount = isset($couponData->discount)?$couponData->discount:0;
      $couponCode = isset($couponData->code)?$couponData->code:'';
      $couponId = isset($couponData->id)?$couponData->id:0;
    }
    $discount = $discount>$couponAmount?$discount-$couponAmount:$couponAmount-$discount;

    if($uid){
        $table = UserRechargeHistory::where('my_txn_id', $uid)->first();
    }
    if(!$table){
        $table = new UserRechargeHistory();
        $uid = str_random(20);
    }
    $table->user_id = $user->id;
    $table->user_type = $user_type;
    $table->amount = $myPlan->amount;
    $table->item_name = $myPlan->name;
    $table->item_description = 'Talk Amount - '.$myPlan->talk_amount.'<br> Loyality Points'.$myPlan->loyality_points;
    $table->payment_method = 'razorpay';
    $table->payment_txn_id = '';
    $table->payment_status = 'pending';
    $table->payment_request = '';
    $table->payment_response = '';
    $table->coupon_id = $couponId;
    $table->coupon_code = $couponCode;
    $table->coupon_amount = $couponAmount;
    $table->tax = 'GST';
    $country = \DB::table('master_countries')->where('id', $user->country_id)->first();
    $state = \DB::table('master_states')->where('id', $user->state_id)->first();
    $city = \DB::table('master_cities')->where('id', $user->city_id)->first();

    if(strtolower($state->name) == 'uttar pradesh'){
        $table->tax_type = 'cgst-sgst';
        $table->cgst = '9';
        $table->cgst_amount = ($amount-$discount)*0.09;
        $table->sgst = '9';
        $table->sgst_amount = ($amount-$discount)*0.09;
    }else {
        $table->tax_type = 'igst';
        $table->igst = '18';
        $table->igst_amount = ($amount-$discount)*0.18;
    }
    $table->coupon_code = '';
    $table->discount = $discount;
    $table->total_amount = ($amount-$discount) + ($amount-$discount)*0.18;
    $table->taktime_value = $myPlan->amount;
    if($myPlan->talk_amount){
      if($myPlan->from_date){
        if(strtotime($myPlan->from_date) <= strtotime('now') && strtotime($myPlan->to_date) >= strtotime('now')){
          $table->taktime_value = $myPlan->talk_amount;
        }
      }else{
        $table->taktime_value = $myPlan->talk_amount;
      }
    }
    // $table->loyality_points = $myPlan->loyality_points;
    $table->loyality_points = 0;
    $table->customer_name = $user->display_name;
    $table->customer_email = $user->email;
    $table->address = $user->address;
    $table->city = $city ? $city->name : '';
    $table->state = $state ? $state->name : '';
    $table->country = $country ? $country->name : '';
    $table->pincode = $user->pincode;
    $table->phone = $user->phone;
    if(!$table->invoice_id){
        $maxInvoiceId = UserRechargeHistory::max('invoice_id');
        $maxInvoiceId = $maxInvoiceId ? $maxInvoiceId+1 : '202100001';
        $table->invoice_id = $maxInvoiceId;
    }
    $rechargePrefix = 'RICHIRAGG';
    $prefix = $table->id<9?$rechargePrefix.'0'.strval($table->id):$rechargePrefix.strval($table->id);
    $paymentRequest = [
        'receipt' => $prefix,
        'amount' => ceil($table->total_amount * 100),
        'currency' => 'INR'
    ];
    $table->total_amount = ($paymentRequest['amount']/100);
    $table->save();
    $api = '';
    $rechargeData = '';
    $api = new Api(config('vrdcms.razorpay_key'), config('vrdcms.razorpay_secret_key'));
    $rechargeData  = $api->order->create($paymentRequest); // Creates Razorpay order

    $data = [
        "key" => config('vrdcms.razorpay_key'),
        "amount" => $paymentRequest['amount'],
        "currency" => "INR",
        "name" => config('app.name'),
        "library_name" => config('app.name'),
        'description' =>$table->item_description,
        "image" => asset('assets/images/logo.png'),
        "order_id" => $rechargeData ? $rechargeData['id'] : 0,
        'prefill' => [
            'name' => $table->customer_name,
            'email' => $table->customer_email,
            'contact'=> $table->phone
        ]
    ];
    $table->save();
    $data['callback_url'] = route('user.razorpay.response', ['user_recharge_id' =>$table->id, 'order_id'=>$data['order_id']]);
    $table->my_txn_id = $prefix;
    $table->payment_request = array_merge($paymentRequest, $data);
    $table->save();
    return response()->json([
        'uid' => $table->my_txn_id,
        'request' => $data,
        'myPlan' => $myPlan,
    ], 200);
}

public function paymentResponse($user_recharge_id, $order_id, Request $request){
    $success = true;
    $error = "Payment Failed!";
    $txnId = session('paymentuid');
    $payment_response = $request->all();
    $user = auth()->guard('astrologer')->user();
    $table = UserRechargeHistory::find($user_recharge_id);

    if (empty($request->razorpay_payment_id) === false) {
      $api = new Api(config('vrdcms.razorpay_key'), config('vrdcms.razorpay_secret_key'));
        try{
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);
            $orderResponse = $api->payment->fetch($request->razorpay_payment_id);
            $payment_response = array_merge($payment_response, $orderResponse->toArray());
            $table->payment_status = 'success';
        }catch(SignatureVerificationError $e){
            $success = false;
            $payment_response = 'Razorpay Error : ' . $e->getMessage();
            $table->payment_status = 'failed';
        }
    }
    $table->payment_response = json_encode($payment_response);
    // $table->balance = $user->wallet+$table->taktime_value;
    $table->save();
    if($success){
      if($table->taktime_value){
        $user->wallet += $table->taktime_value;
      }else{
        $user->wallet += $table->amount;
      }
      $user->save();
      $message = sprintf(config('vrdcms.mobile_recharge'), $table->taktime_value, $user->wallet);
      SendSms::dispatch($user->phone, $message, 'mobile_recharge');
      RechargeInvoiceMailJob::dispatch($table);
      $user->successfullyRecharge($message);
      return redirect()->route('astrologer.recharge', ['thankyou']);
    }
    return redirect()->route('astrologer.recharge', ['failed']);
}

public function rechargeHistory(Request $request)
{
    $nav = 'recharge-history';
    $user = auth()->guard('astrologer')->user();
    $history = UserRechargeHistory::where('user_id', $user->id)->where('user_type', 'astrologer')->orderBy('updated_at', 'desc')->where('payment_status', 'success')->paginate(20);
    // return $history;
    return view(themeView().'astrologers.account.recharge_history', compact('nav', 'user', 'history'));
}

public function rechargeHistoryInvoice(Request $request)
{
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $invoice_id = $request->id;
    $invoice = UserRechargeHistory::where('user_id', $user->id)->where('user_type', $user_type)->where('id', $invoice_id)->first();
    if(!$invoice) return abort(404);
    $print = true;
    return view(themeView().'emails.invoice', compact('print', 'invoice'));
}

  public function wishlists(Request $request){
    //live = 1
    //online = 3
    //real =1
    //service =3
    $nav = 'wishlist';
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $courses = Wishlist::where('user_id',$user->id)->where('user_type', $user_type)->where('type', 'course')->get();
    $products = Wishlist::where('user_id',$user->id)->where('user_type', $user_type)->where('type', 'shop')->get();
    $wishlists = Wishlist::where('user_id',$user->id)->where('user_type', $user_type)->get();
    return view(themeView().'astrologers.account.wishlist', compact('nav', 'user', 'wishlists', 'courses', 'products'));
  }

  public function purchaseitem(Request $request){
    $user = auth()->guard('astrologer')->user();
    $orders = Order::where('user_id', $user->id)->where('user_type','astrologer')->orderBy('id', 'DESC')->get();

    // Get global astrologer referral settings (for reward amounts)
    // Referral code is now stored in astrologers table, not in astrologer_referrals table
    $referralSettings = AstrologerReferral::where('status', 1)->first();

    return view(themeView().'astrologers.account.orders', compact('orders','user', 'referralSettings'));
  }

  public function itemDetail(Request $request){
    $user = auth()->guard('astrologer')->user();
    if(!$user) abort(404);
    $id = $request->order_id;
    $order = Order::find($id);
    if(!$order) abort(404);
    return view(themeView().'astrologers.account.order_detail', compact('order','user'));
  }

  public function newsletterr(Request $request)
  {
      $nav = 'newsletter';
      $user = auth()->guard('astrologer')->user();
      $isSubscribed = NewsletterSubscriber::where('email',$user->email)->where('status',1)->count();

      $hideHeaderNavigation = true;
      $hideFooter = true;
      return view(themeView().'astrologers.account.newsletter', compact('nav', 'user', 'isSubscribed', "hideHeaderNavigation", "hideFooter"));
  }

  public function subscribe(Request $request)
  {
      $user = auth()->guard('astrologer')->user();
      $subsribe = (int) $request->status;
      // return $request->status;
      // if($request->subscribe == 'subscribe'){
      //   $subsribe = 1;
      // }
      $newItem = NewsletterSubscriber::where('email', $user->email)->first();
      // $newItem = NewsletterSubscriber::where('phone', $user->phone)->where('email', $user->email)->first();
      if(!$newItem){
        $newItem = new NewsletterSubscriber();
      }
      $newItem->name = $user->name;
      $newItem->phone = $user->phone;
      $newItem->email = $user->email;
      $newItem->status = $subsribe;
      $newItem->save();
      $newItem->sendEmail();
      return redirect()->back()->with('message', $subsribe?'Successfully Subscribed!':'Unsubscribed!');
  }

  public function ads(Request $request)
  {
    $nav = 'ads';
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $ads = Advertisement::where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'DESC')->get();
    $inactive_ads = $ads->where('status', 0);
    $active_ads = $ads->where('status', 1);

    $hideHeaderNavigation = true;
    $hideFooter = true;

    return view(themeView().'astrologers.account.ad', compact("hideHeaderNavigation", "hideFooter", 'nav', 'user', 'user_type', 'inactive_ads', 'active_ads'));
  }

  public function submitAd(Request $request)
  {
    $this->validate($request, [
      'title'=>'required',
      'description'=>'required',
      'image_id'=>'required',
    ],[
      'title.required'=>'Title is required',
      'description.required'=>'Description is required',
      'image_id.required'=>'Image is required',
    ]);
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $type = $request->type;
    $ad = new Advertisement();
    $ad->user_id = $user->id;
    $ad->user_type = $user_type;
    $ad->title = $request->title;
    $ad->content = $request->description;
    $ad->link = $request->link?:'';
    $ad->page = 'product';
    $ad->type = 'paid';
    $ad->status = 0;
    $ad->save();
    if($request->hasFile('image_id')){
      $mediaController = new MediaController();
      $newRequest  = new Request([
        'file' => $request->file('image_id'),
        'type'=>'astrologer_ad',
      ]);
      $data = $mediaController->upload($newRequest);
      $response = $data->getData();
      $ad->image_id = $response->media_id;
      $ad->order = $ad->id;
      $ad->save();
    }
    return response()->json(['success'=>true, 'message'=>'Your request has been submitted successfully.', 'data'=>$data], 200);
  }

  public function support(Request $request)
  {
      $nav = 'support';
      $user = auth()->guard('astrologer')->user();
      $user_type = 'astrologer';
      $tickets = Support::where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'DESC')->limit(8)->get();

      $hideHeaderNavigation = true;
      $hideFooter = true;

      return view(themeView().'astrologers.account.support', compact("hideHeaderNavigation", "hideFooter", 'nav', 'user', 'user_type', 'tickets'));
  }

  public function submitSupport(Request $request)
  {
    $this->validate($request, [
      'subject'=>'required',
      'type'=>'required',
      'message'=>'required',
    ],[
      'subject.required'=>'Subject is required',
      'type.required'=>'Type is required',
      'message.required'=>'Message is required',
    ]);
    $nav = 'support';
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $type = $request->type;
    $support = Support::where('user_id', $user->id)->where('user_type', $user_type)->where('type', $type)->where('status', 'open')->first();
    if($support){
      return response()->json(['success'=>false, 'errors'=>['type'=>['You already raised ticked for this type.']]], 422);
    }
    if($request->hasFile('file')){
      $path = public_path('support');
      if(!File::exists($path)){
        File::makeDirectory($path);
      }
      $path = public_path('support/astrologer');
      if(!File::exists($path)){
        File::makeDirectory($path);
      }
      $path = public_path('support/astrologer/'.$type);
      if(!File::exists($path)){
        File::makeDirectory($path);
      }
      $time = time();
      $fileName = $time.'.'.$request->file->getClientOriginalExtension();
      $request->file->move($path, $fileName);
      $filePath = 'support/astrologer/'.$type.'/'.$fileName;
    }
    $support = new Support();
    $support->user_id = $user->id;
    $support->user_type = $user_type;
    $support->subject = $request->subject;
    $support->type = $type;
    $support->message = $request->message;
    $support->file = $request->hasFile('file')?$filePath:'';
    $support->status = 'open';
    $support->save();

    $ticket_number = 'REQ-'.date('ym');
    if($support->id <= 9){
      $ticket_number = 'REQ-'.date('ym').'0000'.$support->id;
    }elseif($support->id <= 99){
      $ticket_number = 'REQ-'.date('ym').'000'.$support->id;
    }elseif($support->id <= 999){
      $ticket_number = 'REQ-'.date('ym').'00'.$support->id;
    }elseif($support->id <= 9999){
      $ticket_number = 'REQ-'.date('ym').'0'.$support->id;
    }else{
      $ticket_number = 'REQ-'.date('ym').$support->id;
    }
    $support->ticket_number = $ticket_number;
    $support->save();

    $user = Astrologer::find($user->id);
    SupportUserJob::dispatch($user, $support);
    Notification::route('mail', [
      config("vrdcms.admin_email") => 'Customer Support', //admin
    ])->notify(new SupportAdminNotification($user, 'astrologer', $support));
    $data = view(themeView().'account.ticket_list', compact('support'))->render();
    return response()->json(['success'=>true, 'message'=>'Your request has been submitted successfully. Please wait for support team response.', 'data'=>$data], 200);
  }

  public function fetchSupportReplies(Request $request){
    $support_id = $request->support_id;
    $support = Support::find($support_id);
    if(!$support){
      return response()->json(['success'=>false, 'message'=>'not found'], 200);
    }
    $user_type = 'astrologer';
    $replies = SupportReplies::where('support_id', $support_id)->get();
    $data = view(themeView().'account.replies_list', compact('replies', 'support', 'user_type'))->render();
    return response()->json(['success'=>$replies?true:false, 'data'=>$data, 'status'=>$support?$support->status:'closed', 'message'=>$support?$support->message:''], 200);
  }

  public function supportReplySubmit(Request $request){
    $this->validate($request, [
      'support_reply'=>'required'
    ],[
      'support_reply.required'=>'Reply is required'
    ]);
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $support_id = $request->support_id;
    $support = Support::find($support_id);
    if(!$support){
      return response()->json(['success'=>false, 'message'=>'not found'], 200);
    }
    $reply = new SupportReplies();
    $reply->support_id = $support->id;
    $reply->reply = $request->support_reply;
    $reply->reply_by = $user->id;
    $reply->reply_type = 'astrologer';
    $reply->save();
    $replies = SupportReplies::where('support_id', $support_id)->get();
    $data = view(themeView().'account.replies_list', compact('replies', 'support', 'user_type'))->render();
    return response()->json(['success'=>$replies?true:false, 'data'=>$data, 'status'=>$support?$support->status:'closed', 'message'=>$support?$support->message:'', 'support_id'=>$support_id], 200);
  }

  public function purchasecourses(Request $request){
    $user = auth()->guard('astrologer')->user();
    $orders = Order::where('user_id', $user->id)->where('user_type','astrologer')->where('payment_status', 'Success')->where('status', 'Success')->get();
    // return $orders;
    return view(themeView().'astrologers.account.course', compact('orders','user'));
  }

  public function exploreCourse(Request $request){
    $showStatus = true;
    $nav = 'explore';
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
     $course = Course::where('slug', str_slug($request->slug))->first();
     if(!$course || $course->type == 1) abort(404);
     $first = null;
     $module = null;
     $content = null;
     if($course->modules && isset($course->modules[0])){
       $module = $course->modules[0];
       if(isset($module->content[0])){
         $content = $module->content[0];
         $first = $module->content[0]->videos;
       }
     }
     if(!$first) abort(404);
     $modules = CourseModule::where('course_id', $course->id)->where('status', 1)->orderBy('order', 'asc')->get();
     $courseExploreModules = CourseExploreModule::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course->id)->where('passed', 1);
     $watchedModules = $courseExploreModules->count();
     $percentage = ($watchedModules/count($modules))*100;
     $percentage = (int)$percentage;
    return view(themeView().'astrologers.account.explore.index', compact('nav', 'course', 'module','content', 'first', 'percentage', 'showStatus'));
  }

  public function canWatchModule(Request $request){
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    if(!$user) return response()->json(['success'=>false, 'message'=>'authenticate first.'],402);
    $course_id = $request->course_id;
    $module_id = $request->module_id;
    $modules = CourseModule::where('course_id', $course_id)->where('status', 1)->orderBy('order', 'asc')->get();
    $modulesIds = $request->modulesIds;
    $canWatch = false;
    $courseExploreModulesQuery = CourseExploreModule::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('passed', 1);
    $courseExploreModules = $courseExploreModulesQuery->whereIn('module_id', $modulesIds)->count();
    if(count($modulesIds) == $courseExploreModules){
      $canWatch = true;
    }
    $watchedModules = $courseExploreModulesQuery->count();
    $percentage = ($watchedModules/count($modules))*100;
    $percentage = (int)$percentage;
    return response()->json(['success'=>$canWatch, 'canWatch'=>$canWatch?'yes':'no', 'percentage'=>$percentage, 'count'=>$courseExploreModules]);
  }

  public function howMuchWatch(Request $request){
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    if(!$user) return response()->json(['success'=>false, 'message'=>'authenticate first.'],402);
    $course_id = $request->course_id;
    $module_id = $request->module_id;
    $content_id = $request->content_id;
    $video_id = $request->video_id;
    $duration = $request->duration?:0; //seconds
    $currentTime = $request->currentTime?:0; //seconds
    $courseExplore = CourseExplore::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->where('content_id', $content_id)->where('video_id', $video_id)->first();
    if(!$courseExplore){
      $courseExplore = new CourseExplore();
      $courseExplore->user_id = $user->id;
      $courseExplore->user_type = $user_type;
      $course = Course::find($course_id);
      $courseExplore->course_id = $course_id;
      $courseExplore->course_name = $course->name;
      $courseModule = CourseModule::find($module_id);
      $courseExplore->module_id = $module_id;
      $courseExplore->module_name = $courseModule->name;
      $courseModuleContent = CourseModuleContent::find($module_id);
      $courseExplore->content_id = $content_id;
      $courseExplore->content_name = $courseModuleContent->title;
      $courseExplore->video_id = $video_id;
      $courseExplore->duration = $duration;
      $courseExplore->watched_duration = $currentTime;
    }
    $courseExplore->watched_duration = $courseExplore->watched_duration < $currentTime ? $currentTime : $courseExplore->watched_duration;
    $percentage = ($courseExplore->watched_duration/$courseExplore->duration)*100;
    $courseExplore->percentage = $percentage;
    $courseExplore->save();
    $totalModuelCotntent = CourseModuleContent::where('course_id', $course_id)->where('module_id', $module_id)->count();
    $totalModuleContentExplored = CourseExplore::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->where('percentage', '>=', 80)->count();
    $moduleDone = 'no';
    if($totalModuleContentExplored >= $totalModuelCotntent){
      CourseExploreModule::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->delete();
      $cEM = new CourseExploreModule();
      $cEM->user_id = $user->id;
      $cEM->user_type = $user_type;
      $cEM->course_id = $course_id;
      $cEM->module_id = $module_id;
      $cEM->passed = 1;
      $cEM->save();
      $moduleDone = 'yes';
    }
    return response()->json(['success'=>true, 'message'=>'done', 'moduleDone'=>$moduleDone], 200);
  }

  public function passedModule(Request $request){
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $course_id = $request->course_id;
    $module_id = $request->module_id;
    $cEM = CourseExploreModule::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->first();
    if(!$cEM){
      $cEM = new CourseExploreModule();
      $cEM->user_id = $user->id;
      $cEM->user_type = $user_type;
      $cEM->course_id = $course_id;
      $cEM->module_id = $module_id;
    }
    $cEM->passed = 1;
    $cEM->save();
    return response()->json(['success'=>true, 'course_id'=>$course_id, 'module_id'=>$module_id], 200);
  }

  public function assignments(Request $request)
  {
    $type = $request->type; //assignment , quizz
    $nav = $type;
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $course_slug = $request->course_slug;
    $course = Course::where('slug', $course_slug)->first();
    if(!$course) abort(404);
    $passed = 0;
    $failed = 0;
    $modulesArray = CourseModule::where('course_id', $course->id)->pluck('minus_marks', 'id')->toArray();
    foreach ($modulesArray as $module_id => $marks) {
      $answers = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('module_id', $module_id)->where('show_in', $type);
      $totalAttemptedQuestions = $answers->count();
      $totalQuestions = CourseModuleQuestion::where('module_id', $module_id)->where('show_in', $type)->count();
      // return $totalAttemptedQuestions;
      // return $totalQuestions;
      if($totalAttemptedQuestions == $totalQuestions){
        $mrks = $answers->sum('marks');
        $passingMarks = (($totalQuestions*$marks)/100)*33;
        if($mrks >= $passingMarks){
          $passed++;
        }else{
          $failed++;
        }
      }
    }
    // return $passed;
    return view(themeView().'astrologers.account.explore.'.$type.'.index', compact('nav', 'user', 'course','passed', 'failed'));
  }

  public function assignmentQuestions(Request $request)
  {
    $type = $request->type; //assignment , quizz
    $nav = $type;
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $course_slug = $request->course_slug;
    $module_id = $request->module_id;
    $course = Course::where('slug', $course_slug)->first();
    if(!$course) return abort(404);
    $module = CourseModule::where('id', $module_id)->where('course_id', $course->id)->first();
    if(!$module) return abort(404);
    $attemptedQuestion = null;
    $next = false;
    if($type == 'quizz'){
      $currentTime = date('H:i:s');
      $attemptedQuestion = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course->id)->where('module_id', $module->id)->where('show_in', $type)->orderBy('id', 'desc')->first();
      if($attemptedQuestion && $attemptedQuestion->answer > 0){
        $next = true;
      }elseif($attemptedQuestion && ($attemptedQuestion->answer == 0) && ($attemptedQuestion->end_time < $currentTime)){
        $next = true;
      }
    }
    return view(themeView().'astrologers.account.explore.'.$type.'.questions', compact('nav', 'user', 'course', 'module', 'attemptedQuestion', 'next'));
  }

  public function fetchAssignmentQuestion(Request $request){
    $type = $request->type; //assignment , quizz
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $course_id = $request->course_slug;
    $module_id = $request->module_id;
    $question_id = $request->question_id;
    $module = CourseModule::find($module_id);
    if(!$module){
      return response()->json(['success'=>false, 'msg'=>'Question not found.'], 402);
    }
    $question = CourseModuleQuestion::where('id', $question_id)->where('course_id', $course_id)->where('module_id', $module_id)->where('show_in', $type)->first();
    if(!$question){
      return response()->json(['success'=>false, 'msg'=>'Question not found.'], 402);
    }
    $hasAnswer = false;
    if($type == 'assignment'){
      $answer = $question->userAssignmentAnswer;
    }elseif($type == 'quizz'){
      $answer = $question->quizzAnswer;
    }
    if($answer){
      $hasAnswer = true;
      $answer = $answer->answer;
    }
    $assignmentAnswer = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->where('question_id', $question_id)->where('show_in', $type)->first();
    if(!$assignmentAnswer){
      $assignmentAnswer = new AssignmentAnswer();
      $assignmentAnswer->user_id = $user->id;
      $assignmentAnswer->user_type = $user_type;
      $assignmentAnswer->course_id = $question->course_id;
      $assignmentAnswer->module_id = $question->module_id;
      $assignmentAnswer->content_id = $question->content_id;
      $assignmentAnswer->question_id = $question->id;
      $assignmentAnswer->show_in = $type;
      $startTime = date('H:i:s');
      $endTime = date('H:i:s', strtotime($startTime.'+'.$module->total_time.'minute'));
      // $endTime = date('H:i:s', strtotime($startTime.'+1minute'));
      $assignmentAnswer->start_time = $startTime;
      $assignmentAnswer->end_time = $endTime;
      $assignmentAnswer->save();
    }

    return response()->json(['success'=>$question?true:false, 'show_in'=>$type, 'question'=>$question, 'answer'=>$hasAnswer?$answer:false, 'startTime'=>$assignmentAnswer->start_time, 'endTime'=>Carbon::parse($assignmentAnswer->end_time)], 200);
  }
  public function submitAssignmentQuestion(Request $request){
    $this->validate($request,[
      'question_id'=>'required'
    ]);
    $type = $request->type; //assignment , quizz
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $course_id = $request->course_slug;
    $module_id = $request->module_id;
    $module = CourseModule::find($module_id);
    $choosed_option = $request->choosed_option < 0 ? null : $request->choosed_option;
    $question_id = $request->question_id;
    // return $question_id;
    if($choosed_option == null){
      return response()->json(['success'=>false, 'msg'=>'answer required'], 402);
    }
    $question = CourseModuleQuestion::where('id', $question_id)->where('course_id', $course_id)->where('module_id', $module_id)->where('show_in', $type)->first();
    if(!$question){
      return response()->json(['success'=>false, 'msg'=>'QuestionId required'], 402);
    }
    $assignmentAnswer = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->where('content_id', $question->content_id)->where('question_id', $question_id)->where('show_in', $type)->first();
    if(!$assignmentAnswer){
      $assignmentAnswer = new AssignmentAnswer();
      $assignmentAnswer->user_id = $user->id;
      $assignmentAnswer->user_type = $user_type;
      $assignmentAnswer->course_id = $course_id;
      $assignmentAnswer->module_id = $module_id;
      $assignmentAnswer->content_id = $question->content_id;
      $assignmentAnswer->question_id = $question_id;
    }
    $assignmentAnswer->answer = $choosed_option;
    $assignmentAnswer->marks = 0;
    if($question->answer == (int)$choosed_option){
      $assignmentAnswer->marks = $module->minus_marks;
    }
    $assignmentAnswer->show_in = $type;
    $assignmentAnswer->save();
    return response()->json(['success'=>true, 'msg'=>'answer submitted'], 200);
  }
  public function courseResult(Request $request){
    $type = $request->type; //assignment , quizz
    $nav = 'result';
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $course_slug = $request->course_slug;
    $module_id = $request->module_id;
    $course = Course::where('slug', $course_slug)->first();
    if(!$course) return abort(404);
    $module = CourseModule::where('id', $module_id)->where('course_id', $course->id)->first();
    if(!$module) return abort(404);
    return view(themeView().'astrologers.account.explore.'.$type.'.result', compact('nav', 'user', 'course', 'module'));
  }

  public function courseCertificate(Request $request){
    $nav = 'course certificate';
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $course_slug = $request->course_slug;
    $module_id = $request->module_id;
    $course = Course::where('slug', $course_slug)->first();
    if(!$course) return abort(404);
    $module = CourseModule::where('id', $module_id)->where('course_id', $course->id)->first();
    if(!$module) return abort(404);
    $order = OrderItem::where('product_id', $course->id)->where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'desc')->first();
    $pdf = PDF::loadView(themeView().'astrologers.account.explore.certificate', compact('nav', 'user', 'course', 'module', 'order'));
    // return $pdf->download($course->slug.'.pdf');
    return view(themeView().'astrologers.account.explore.certificate', compact('nav', 'user', 'course', 'module', 'order'));
  }

  public function meetings(Request $request)
  {
      $nav = 'meeting';
      $user = auth()->guard('astrologer')->user();
      $user_type = 'astrologer';
      $course_slug = $request->course_slug;
      $course = Course::where('slug', $course_slug)->first();
      if(!$course) return abort(404);
      return view(themeView().'astrologers.account.explore.meetings', compact('nav', 'user', 'course'));
  }




    public function upload(Request $request)
    {
      if($request->file_type=='image_id'){
          $this->validate($request, [
            'file' => 'required|image|dimensions:max_width=4000,max_height=4000|max:2048'
          ]);
          $mediaPath = public_path('media');
          if(!File::exists($mediaPath)) {
            File::makeDirectory($mediaPath, 0777, true);
          }
          $tempupload = public_path('media/tempupload');
          if(!File::exists($tempupload)) {
              File::makeDirectory($tempupload, 0777, true);
          }
          $time = time();
          $imageName = $time.'.'.$request->file->getClientOriginalExtension();
          $request->file->move(public_path('media/tempupload'), $imageName);
          $image = Image::make(public_path('media/tempupload/').$imageName);
          $attributes['H']      = $image->height();
          $attributes['W']      = $image->height();
          $attributes['file']   = $imageName;
          $media = new Media();
          $media->file = $imageName;
          $media->title = $request->file->getClientOriginalName();
          $media->caption = '';
          $media->type = 'astrologer';
          $media->attributes = json_encode($attributes);
          $media->sizes = json_encode([$attributes['W'].'-'.$attributes['H']]);
          $media->save();
          $path = public_path('media/'.$media->id);
          if(!File::exists($path)) {
              File::makeDirectory($path, 0755, true);
          }
          $image->save($path.'/'.$imageName);
          $media->image(200,200);
          unlink(public_path('media/tempupload/').$imageName);

          $data['path']  = $media->image(200,200);
          $data['value']  = $media->id;

          return response()->json($data, 200);
      }elseif($request->file_type=='video'){
        $videoFile = $request->file('file') ?: $request->file('video_file') ?: $request->file('video');
        if (!$videoFile && !empty($request->allFiles())) {
          $files = $request->allFiles();
          $videoFile = reset($files);
        }
        if (!$videoFile) {
          $contentLength = (int) $request->header('Content-Length');
          if ($contentLength > 5 * 1024 * 1024) {
            return response()->json([
              'value' => null,
              'message' => 'File too large or PHP limit reached. Increase upload_max_filesize and post_max_size in php.ini (e.g. 20M) and try again.'
            ], 422);
          }
          return response()->json([
            'value' => null,
            'message' => 'No file uploaded. Please select a video file and ensure it is under 10MB.'
          ], 422);
        }
        if (!$videoFile->isValid()) {
          $code = $videoFile->getError();
          $msg = ($code === UPLOAD_ERR_INI_SIZE) ? 'File exceeds server limit. Increase upload_max_filesize in php.ini.' : 'Upload failed. Please try again.';
          return response()->json(['value' => null, 'message' => $msg], 422);
        }
        $allowedExt = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
        $ext = strtolower($videoFile->getClientOriginalExtension());
        if (!in_array($ext, $allowedExt)) {
          return response()->json(['value' => null, 'message' => 'Allowed video formats: ' . implode(', ', $allowedExt)], 422);
        }
        if (($videoFile->getSize() / 1024) > 10240) {
          return response()->json(['value' => null, 'message' => 'Upload 10 MB or less Video'], 422);
        }
        $mediaPath = public_path('media');
        if(!File::exists($mediaPath)) {
            File::makeDirectory($mediaPath, 0777, true);
        }
        $tempupload = public_path('media/tempupload');
        if(!File::exists($tempupload)) {
            File::makeDirectory($tempupload, 0777, true);
        }
        $time = time();
        $videoName = $time.'.'.$ext;
        $videoFile->move(public_path('media/tempupload'), $videoName);

        // For video, we'll store it differently - just move to media folder
        $media = new Media();
        $media->file = $videoName;
        $media->title = $videoFile->getClientOriginalName();
        $media->caption = '';
        $media->type = 'astrologer';
        $attributes['H'] = 0;
        $attributes['W'] = 0;
        $attributes['file'] = $videoName;
        $media->attributes = json_encode($attributes);
        $media->sizes = json_encode(['0-0']);
        $media->save();

        $path = public_path('media/'.$media->id);
        if(!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Move video file to media folder
        rename(public_path('media/tempupload/').$videoName, $path.'/'.$videoName);

        $data['path'] = asset('media/'.$media->id.'/'.$videoName);
        $data['value'] = $media->id;

        return response()->json($data, 200);
      }else{
        $this->validate($request, [
            'file' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx,odt|max:5120'
        ]);
        $filePath = public_path('uploads/astrologers');
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0755, true);
        }
        $time = time();
        $fileName = $time.'.'.$request->file->getClientOriginalExtension();
        $fileDestination = $filePath;
        if(!File::exists($fileDestination)) {
            File::makeDirectory($fileDestination, 0755, true);
        }

        $request->file->move($fileDestination, $fileName);
        $filetype = $request->file->getClientOriginalExtension();

        return response()->json([
                'value' =>  $fileName,
                'path'  =>  asset('uploads/astrologers/'.$fileName),
        ], 200);
      }
    }
    public function updateStatus(Request $request){
      $field = $request->field;
      $status = $request->status;
      $statusArray = [
        'online' => 1,
        'offline' => 2,
        'busy' => 3,
        'hide' => 0,
      ];
      if(isset($statusArray[$status])){
        $astrologer = auth()->guard('astrologer')->user();
        $astrologer->{$field} = $statusArray[$status];
        $astrologer->save();
        if($status == 'online'){
          $loginController = new LoginController();
          $loginController->userNotification($astrologer, 'user', 'astrologer online');
        }
      }
      // return back();
      return response()->json(['success'=>true, 'message'=>'Updated Successfully', 'status'=> $astrologer->{$field}], 200);
    }

    public function checkchat(Request $request) {
      $astrologer = auth()->guard('astrologer')->user();
      $id = $astrologer->id;
      $chat = Chat::where('astrologer_id', $id)
                  ->where('astrologer_status', 2)
                  ->whereNull('end_time')
                  ->first();

      if ($chat) {
          return response()->json(['status' => 1, 'chat' => $chat], 200);
      } else {
          return response()->json(['status' => 0, 'message' => 'No active chat found'], 200);
      }
  }

  public function currentAppointment(Request $request){
    $astrologer = auth()->guard('astrologer')->user();
    if($currentAppointment = $astrologer->checkCurrentAppointment([1, 2, 3])){
      $type = 'video';
      $room_id = $currentAppointment->room_id;
      $_token=$request->_token??'';
      $redirectTo = route("astrologer.startvideocall", [$room_id]);
      if($_token){
        $redirectTo = route("astrologer.startvideocall", [$room_id,'_token'=>$_token]);
      }
      if($currentAppointment->package==2){
        $type = 'chat';
        $redirectTo = route("astrologer.startchat", [$room_id]);
        if($_token){
          $redirectTo = route("astrologer.startchat", [$room_id,'_token'=>$_token]);
        }
      }elseif($currentAppointment->package==3){
        $type = 'phone';
        $redirectTo = route("astrologer.startphonecall", [$room_id]);
        if($_token){
          $redirectTo = route("astrologer.startphonecall", [$room_id,'_token'=>$_token]);
        }
      }
      $currentAppointment->is_notification_sent = 1;
      $currentAppointment->save();
      return response()->json([
        'success'=> true,
        'message' => "$currentAppointment->name is waiting to consult with you via $type. Kindly accept and start the consultation.",
        'redirectTo'=>$redirectTo,
      ], 200);
    }
    return response()->json(['success'=>false, 'message' => 'appointment not found.'], 404);
  }

  public function consultations(Request $request){
    $user  = auth()->guard('astrologer')->user();
    if($user->chat > 0){
      $user->chat = 1;
    }
    if($user->call > 0){
      $user->call = 1;
    }
    if($user->video > 0){
      $user->video = 1;
    }
    $user->save();

    AstrologerAppointment::where('astrologer_id', $user->id)
    ->where('scheduled_date', date('Y-m-d'))
    ->where('max_time', '>', date('H:i:00', strtotime('+5 minutes')))
    ->whereIn('status', ['cancel', 'failed'])
    ->update([
        'scheduled_end_time' => date('H:i:00'),
        'max_time' => date('H:i:00', strtotime('+5 minutes'))
    ]);

    $appointments = AstrologerAppointment::where('astrologer_id', $user->id)
    ->whereNotIn('status', ['pending', 'failed'])
    ->orderByRaw("UNIX_TIMESTAMP(CONCAT(scheduled_date, ' ', scheduled_time)) DESC")
    ->get();

    $tab = 'chat';

    if($request->has('tab')){
      $tab = $request->tab;
    }

    // Get suggested remedies for this astrologer
    $suggestedRemedies = DB::table('astrologer_remedy_suggestions')
      ->where('astrologer_id', $user->id)
      ->orderBy('created_at', 'desc')
      ->get()
      ->map(function($suggestion) {
        $user = User::find($suggestion->user_id);
        $products = json_decode($suggestion->products, true) ?: [];
        $reports = json_decode($suggestion->reports, true) ?: [];
        $pujas = json_decode($suggestion->pujas ?? '[]', true) ?: [];

        $productDetails = [];
        $reportDetails = [];
        $pujaDetails = [];

        if(!empty($products)){
          $productDetails = Product::whereIn('id', $products)
            ->select('id', 'title', 'slug', 'type', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
            ->get()
            ->map(function($product){
              $currentDate = date('Y-m-d');
              $price = $product->base_price ?? 0;

              // Check if sale price is valid and within date range
              if($product->sale_price && $product->sale_price > 0){
                if($product->sale_price_start_date && $product->sale_price_end_date){
                  if($currentDate >= $product->sale_price_start_date && $currentDate <= $product->sale_price_end_date){
                    $price = $product->sale_price;
                  }
                } elseif(!$product->sale_price_start_date && !$product->sale_price_end_date){
                  // If no date range, use sale_price if it exists
                  $price = $product->sale_price;
                }
              }

              return [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'type' => $product->type,
                'price' => $price
              ];
            });
        }

        if(!empty($reports)){
          $reportDetails = Product::whereIn('id', $reports)
            ->select('id', 'title', 'slug', 'type', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
            ->get()
            ->map(function($report){
              $currentDate = date('Y-m-d');
              $price = $report->base_price ?? 0;

              // Check if sale price is valid and within date range
              if($report->sale_price && $report->sale_price > 0){
                if($report->sale_price_start_date && $report->sale_price_end_date){
                  if($currentDate >= $report->sale_price_start_date && $currentDate <= $report->sale_price_end_date){
                    $price = $report->sale_price;
                  }
                } elseif(!$report->sale_price_start_date && !$report->sale_price_end_date){
                  // If no date range, use sale_price if it exists
                  $price = $report->sale_price;
                }
              }

              return [
                'id' => $report->id,
                'title' => $report->title,
                'slug' => $report->slug,
                'type' => $report->type,
                'price' => $price
              ];
            });
        }

        if(!empty($pujas)){
          $pujaDetails = Product::whereIn('id', $pujas)
            ->select('id', 'title', 'slug', 'type', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
            ->get()
            ->map(function($puja){
              $currentDate = date('Y-m-d');
              $price = $puja->base_price ?? 0;

              // Check if sale price is valid and within date range
              if($puja->sale_price && $puja->sale_price > 0){
                if($puja->sale_price_start_date && $puja->sale_price_end_date){
                  if($currentDate >= $puja->sale_price_start_date && $currentDate <= $puja->sale_price_end_date){
                    $price = $puja->sale_price;
                  }
                } elseif(!$puja->sale_price_start_date && !$puja->sale_price_end_date){
                  // If no date range, use sale_price if it exists
                  $price = $puja->sale_price;
                }
              }

              return [
                'id' => $puja->id,
                'title' => $puja->title,
                'slug' => $puja->slug,
                'type' => $puja->type,
                'price' => $price
              ];
            });
        }

        return [
          'id' => $suggestion->id,
          'user_id' => $suggestion->user_id,
          'user_name' => $user ? ($user->name ?? $user->firstname ?? 'User #' . $suggestion->user_id) : 'User #' . $suggestion->user_id,
          'appointment_id' => $suggestion->appointment_id,
          'products' => $productDetails,
          'reports' => $reportDetails,
          'pujas' => $pujaDetails,
          'referral_code' => $suggestion->referral_code,
          'created_at' => $suggestion->created_at,
          'is_used' => $suggestion->is_used
        ];
      });

    $hideHeaderNavigation = true;
    $hideFooter = true;

    return view(themeView().'astrologers.account.consultation.index',compact("hideHeaderNavigation", "hideFooter", 'user', 'appointments', 'tab', 'suggestedRemedies'));
  }

  public function startchat(Request $request) {
      $room_id = $request->room_id;
      $astrologer = auth()->guard('astrologer')->user();

      $appointment = AstrologerAppointment::where('package', 2)
      ->where('room_id', $room_id)
      ->where('astrologer_id', $astrologer->id)
      ->whereNotIn('status', ['pending', 'cancel'])
      ->first();

      if(!$appointment) abort(404);

      $consultation_id = $appointment->id;

      $current_time = date('H:i:s');
      $scheduled_time = $appointment->scheduled_time;
      $scheduled_end_time = $appointment->scheduled_end_time;

      if(!$appointment->astrologer_start_time){
        $appointment->astrologer_status='joined';
        $appointment->status='joined';
        $appointment->astrologer_start_time = date('H:i:s');
        $appointment->save();
      }

      $start_time = time()-strtotime($appointment->astrologer_start_time);
      $end_time = Carbon::parse($appointment->scheduled_end_time);
      $stopCurrentAppointmentInterval = true;
      $user = $appointment->user;
      $hideHeaderNavigation = true;
      $hideFooter = true;

      return view(themeView().'astrologers.account.consultation.chat', compact("hideHeaderNavigation", 'appointment', 'astrologer', 'user', 'start_time', 'end_time', 'room_id', 'hideFooter', 'stopCurrentAppointmentInterval', "consultation_id"));
  }

  public function canChat(Request $request){
    $this->validate($request, [
      'room_id'=>'required'
    ]);

    $astrologer = auth()->guard('astrologer')->user();
    $room_id = $request->room_id;

    $appointment = AstrologerAppointment::where('package', 2)
    ->where('room_id', $room_id)
    ->where('astrologer_id', $astrologer->id)
    ->whereNotIn('status', ['pending', 'cancel'])
    ->first();

    if(!$appointment){
      return response()->json([
        'success'=>false,
        'message' => 'Consultation not found.'
      ], 404);
    }

    $user = $appointment->user;
    $user_detail['name'] = $user->name;
    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if(!$appointment->user_start_time){
      $count_down = ($starting_time+180) - time();
      return response()->json([
        'success'=>true,
        'message' => 'Please wait for user',
        'status' => 'wait',
        'user_detail'=>$user_detail,
        'count_down'=>$count_down
      ], 200);
    }

    if($starting_time > $current_time){
      $difference = $starting_time - $current_time;
      $hours = floor($difference / 3600);
      $minutes = floor(($difference % 3600) / 60);
      $message = "Consultation starts in $hours hours and $minutes minutes.";
      return response()->json([
        'success'=>true,
        'message' => $message,
        'status' => 'not now',
        'user_detail'=>$user_detail
      ], 200);
    }elseif($ending_time < $current_time){
      return response()->json([
        'success'=>true,
        'message' => 'Time is over',
        'status' => 'done',
        'user_detail'=>$user_detail
      ], 200);
    }

    $astrologer->chat = 3;
    $astrologer->save();

    return response()->json([
      'success'=>true,
      'message' => '',
      'status' => 'start',
      'user_detail'=>$user_detail
    ], 200);
  }

  public function fetchChatLogs(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required',
    ]);

    $astrologer = auth()->guard('astrologer')->user();
    $consultation_id = $request->consultation_id;

    $appointment = AstrologerAppointment::find($consultation_id);
    $chatLogs = ChatLog::where('chat_id', $consultation_id)->get();
    $chatLogsCount = $chatLogs->count();

    $data = view(themeView().'astrologers.account.consultation.chats',['results'=>$chatLogs, 'appointment'=>$appointment])->render();

    $status = 'done';

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if($starting_time <= $current_time && $ending_time > $current_time){
      $appointment->astrologer_end_time = date('H:i:s', $current_time);
      $appointment->astrologer_status = 'done';

      if($appointment->status != "cancel"){
        $appointment->status = 'done';
      }

      $status = 'continue';

      if($request->status == 'leave'){
        $appointment->scheduled_end_time = date('H:i');
        $appointment->max_time = date('H:i', strtotime('+5minutes'));
        $status = 'done';
        $astrologer->chat = 1;
      }elseif($request->status == 'cancel'){
        $appointment->astrologer_status = 'cancel';
        $appointment->status = 'cancel';
        $status = 'cancel';
        $astrologer->chat = 1;
      }
    }else{
      $astrologer->chat = 1;
    }
    $appointment->save();

    return response()->json([
      'success'=>true,
      'message' => '',
      'status' => $status,
      'data'=>$data,
      'chatLogsCount'=>$chatLogsCount
    ], 200);
  }



  public function maskIllegalString($string){
    $email_patterns = [
      '/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+(?:\.[A-Z]{2,})?/i',
      '/[A-Z0-9._%+\-]+@[A-Z0-9.\-]*[A-Z0-9][\s\.]?[A-Z]{2,}/i',
      '/[A-Z0-9._%+\-]+\s*(?:\(|\[)?\s*(?:@|at|\bat\b|\(at\)|\[at\])\s*(?:\)|\])?\s*[A-Z0-9.\-]+\s*(?:\(|\[)?\s*(?:\.|dot|\bdot\b|\(dot\)|\[dot\])\s*(?:\)|\])?\s*[A-Z]{2,}/i',
      '/[A-Z0-9._%+\-]+\s+(?:at)\s+[A-Z0-9.\-]+/i'
    ];
    $phone_re = '/(?:\+?\s*[\d\-\.\(\)\s]{3,}\d)|(?:\b(?:[0-9]|zero|one|two|three|four|five|six|seven|eight|nine|oh|o|plus)\b(?:[\s\-\.\,\/\(\)]*(?:[0-9]|zero|one|two|three|four|five|six|seven|eight|nine|oh|o))*)/i';

    $has_illegal_text = false;

    $map = [
      'zero' => '0', 'one' => '1', 'two' => '2', 'three' => '3', 'four' => '4',
      'five' => '5', 'six' => '6', 'seven' => '7', 'eight' => '8', 'nine' => '9',
      'plus' => '+'
    ];

    // convert words → digits (case-insensitive)
    $string = str_ireplace(array_keys($map), array_values($map), $string);

    // remove spaces/dots/dashes (so “9 eight 7 six...” becomes “9876543210”)
    // $string = preg_replace('/[\s\.\-]+/', '', $string);

    $mask_length = function($s) {
        return str_repeat('*', mb_strlen($s, 'UTF-8'));
    };

    foreach ($email_patterns as $pattern) {
        $string = preg_replace_callback($pattern, function($m) use (&$has_illegal_text, $mask_length) {
            $has_illegal_text = true;
            return $mask_length($m[0]);
        }, $string);
    }

    $string = preg_replace_callback($phone_re, function($m) use (&$has_illegal_text){
        if(strlen(preg_replace('/\D/','',$m[0])) < 6) return $m[0];
        $has_illegal_text = true;
        return str_repeat('*', strlen($m[0]));
    }, $string);

    return ["has_illegal_text" => $has_illegal_text , "string" => $string];
  }

  public function sendMessage(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required|gt:0',
      'message'=>'required'
    ]);

    $consultation_id = $request->consultation_id;
    $message = $request->message;

    $astrologer = auth()->guard('astrologer')->user();

    $data = $this->maskIllegalString($message);

    $has_illegal_text = $data["has_illegal_text"];
    $message = $data["string"];

    $chatLog = new ChatLog();
    $chatLog->chat_id = $consultation_id;
    $chatLog->user_id = 0;
    $chatLog->astrologer_id = $astrologer->id;
    $chatLog->message = $message;
    $chatLog->message_by = 'astrologer';

    if($has_illegal_text){
      $chatLog->has_illegal_text = 1;
      $chatLog->illegal_message = $request->message;
    }

    $chatLog->save();

    return response()->json([
      'success'=>true,
      'message' => 'sent'
    ], 200);
  }

  public function startPhoneCall(Request $request){
    $room_id = $request->room_id;
    $astrologer = auth()->guard('astrologer')->user();

    $appointment = AstrologerAppointment::where('package', 3)
    ->where('room_id', $room_id)
    ->where('astrologer_id', $astrologer->id)
    ->first();

    if(!$appointment) abort(404);

    $consultation_id = $appointment->id;

    if(!$appointment->astrologer_start_time){
      $appointment->astrologer_status='joined';
      $appointment->status='joined';
      $appointment->astrologer_start_time = date('H:i:s');
      $appointment->save();
    }

    $start_time = time()-strtotime($appointment->astrologer_start_time);
    $end_time = Carbon::parse($appointment->scheduled_end_time);
    $stopCurrentAppointmentInterval = true;
    $user = $appointment->user;
    $hideHeaderNavigation = true;
    $hideFooter = true;

    return view(
      themeView().'astrologers.account.consultation.call',
      compact(
        "hideHeaderNavigation",
        'hideFooter',
        "astrologer",
        'consultation_id',
        "room_id",
        'start_time',
        'end_time',
        'stopCurrentAppointmentInterval',
        'user',
        'appointment',
      )
    );
  }

  public function canPhoneCall(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);

    $consultation_id = $request->consultation_id;
    $astrologer = auth()->guard('astrologer')->user();

    $appointment = AstrologerAppointment::where('package', 3)
    ->where('id', $consultation_id)
    ->where('astrologer_id', $astrologer->id)
    ->first();

    if(!$appointment){
      return response()->json([
        'success'=>false,
        'message' => 'Consultation not found.'
      ], 404);
    }

    $astrologer = $appointment->astrologer;
    $astrologer_detail['name'] = $astrologer->display_name;

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if(!$appointment->user_start_time){
      $count_down = ($starting_time+180) - time();
      return response()->json([
        'success'=>true,
        'message' => 'Please wait for user',
        'status' => 'wait',
        'count_down'=>$count_down
      ], 200);
    }

    $consultation_detail['start_time'] = '00:00';
    $remaining_seconds = $ending_time - $current_time;

    if($remaining_seconds > 0){
      $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
      $remaining_seconds = $remaining_seconds % 60;
      $consultation_detail['start_time'] = "$remaining_minutes:$remaining_seconds";
    }

    $consultation_detail['app_id'] = fromSetting("agora_app_id");
    $consultation_detail['app_certificate'] = fromSetting("agora_app_certificate");
    $consultation_detail['room_id'] = $appointment->room_id;
    $consultation_detail['uid'] = "@".$astrologer->firstname.$astrologer->id;
    $consultation_detail['user_id'] = $astrologer->id;
    $consultation_detail['app_token'] = RtcTokenBuilder2::buildTokenWithUid($consultation_detail['app_id'], $consultation_detail["app_certificate"], $consultation_detail["room_id"], $consultation_detail["user_id"], 1, 86400);
    $consultation_detail['user_name'] = $astrologer->display_name;
    $consultation_detail['logo_url'] = asset('assets/img/logo.png');
    unset($consultation_detail['app_certificate']);

    if($starting_time > $current_time){
      $difference = $starting_time - $current_time;
      $hours = floor($difference / 3600);
      $minutes = floor(($difference % 3600) / 60);
      $message = "Consultation starts in $hours hours and $minutes minutes.";

      return response()->json([
        'success'=>true,
        'message' => $message,
        'status' => 'not now',
        'astrologer_detail'=>$astrologer_detail,
        'consultation_detail'=>$consultation_detail
      ], 200);
    }elseif($ending_time < $current_time){
      return response()->json([
        'success'=>true,
        'message' => 'Time is over',
        'status' => 'done',
        'astrologer_detail'=>$astrologer_detail,
        'consultation_detail'=>$consultation_detail
      ], 200);
    }

    $astrologer->call = 3;
    $astrologer->save();

    return response()->json([
      'success'=>true,
      'message' => '',
      'status' => 'start',
      'astrologer_detail'=>$astrologer_detail,
      'consultation_detail'=>$consultation_detail
    ], 200);
  }

  public function updatePhoneCallStatus(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);

    $consultation_id = $request->consultation_id;
    $astrologer = auth()->guard('astrologer')->user();

    $appointment = AstrologerAppointment::find($consultation_id);

    if(!$appointment){
      return response()->json([
        'success'=>false,
        'message' => 'Consultation not found.'
      ], 404);
    }

    $status = 'done';

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if($starting_time <= $current_time && $ending_time > $current_time){
      $appointment->astrologer_end_time = date('H:i:s', $current_time);
      $appointment->astrologer_status = 'done';

      if($appointment->status != "cancel"){
        $appointment->status = 'done';
      }

      $status = 'continue';

      if($request->status == 'leave'){
        $appointment->scheduled_end_time = date('H:i');
        $appointment->max_time = date('H:i', strtotime('+5minutes'));
        $status = 'done';
        $astrologer->call = 1;
      }elseif($request->status == 'cancel'){
        $appointment->astrologer_status = 'cancel';
        $appointment->status = 'cancel';
        $status = 'cancel';
        $astrologer->call = 1;
      }

      $appointment->save();
    }else{
      $astrologer->call = 1;
    }

    return response()->json([
      'success'=>true,
      'status'=>$status
    ], 200);
  }

  public function startVideoCall(Request $request){
    $room_id = $request->room_id;
    $astrologer = auth()->guard('astrologer')->user();

    $appointment = AstrologerAppointment::where('package', 1)
    ->where('room_id', $room_id)
    ->where('astrologer_id', $astrologer->id)
    ->first();

    if(!$appointment) abort(404);

    $consultation_id = $appointment->id;

    if(!$appointment->astrologer_start_time){
      $appointment->astrologer_status='joined';
      $appointment->status='joined';
      $appointment->astrologer_start_time = date('H:i:s');
      $appointment->save();
    }
    $start_time = time()-strtotime($appointment->astrologer_start_time);
    $end_time = Carbon::parse($appointment->scheduled_end_time);
    $stopCurrentAppointmentInterval = true;
    $user = $appointment->user;
    $hideHeaderNavigation = true;
    $hideFooter = true;

    return view(themeView().'astrologers.account.consultation.video', compact("hideHeaderNavigation", "user", "astrologer", "room_id", 'consultation_id', 'start_time', 'end_time', 'hideFooter', 'stopCurrentAppointmentInterval','appointment'));
  }

  public function canVideoCall(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);

    $consultation_id = $request->consultation_id;
    $astrologer = auth()->guard('astrologer')->user();

    $appointment = AstrologerAppointment::where('package', 1)
    ->where('id', $consultation_id)
    ->where('astrologer_id', $astrologer->id)
    ->first();

    if(!$appointment){
      return response()->json([
        'success'=>false,
        'message' => 'Consultation not found.'
      ], 404);
    }

    $astrologer = $appointment->astrologer;
    $astrologer_detail['name'] = $astrologer->display_name;

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if(!$appointment->user_start_time){
      $count_down = ($starting_time+180) - time();
      return response()->json(['success'=>true, 'message' => 'Please wait for user', 'status' => 'wait', 'count_down'=>$count_down], 200);
    }

    $consultation_detail['start_time'] = '00:00';
    $remaining_seconds = $ending_time - $current_time;

    if($remaining_seconds > 0){
      $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
      $remaining_seconds = $remaining_seconds % 60;
      $consultation_detail['start_time'] = "$remaining_minutes:$remaining_seconds";
    }

    // $consultation_detail['app_id'] = (int) config('vrdcms.zego_app_id');
    // $consultation_detail['app_sign'] = config('vrdcms.zego_server_secret');
    // $consultation_detail['room_id'] = $astrologerUserConsultacy->meeting_link;
    $consultation_detail['app_id'] = fromSetting("agora_app_id");
    $consultation_detail['app_certificate'] = fromSetting("agora_app_certificate");
    $consultation_detail['room_id'] = $appointment->room_id;
    $consultation_detail['uid'] = "@".$astrologer->firstname.$astrologer->id;
    $consultation_detail['user_id'] = $astrologer->id;
    $consultation_detail['app_token'] = RtcTokenBuilder2::buildTokenWithUid($consultation_detail['app_id'], $consultation_detail["app_certificate"], $consultation_detail["room_id"], $consultation_detail["user_id"], 1, 86400);
    $consultation_detail['user_name'] = $astrologer->display_name;
    $consultation_detail['logo_url'] = asset('assets/img/logo.png');
    unset($consultation_detail['app_certificate']);

    if($starting_time > $current_time){
      $difference = $starting_time - $current_time;
      $hours = floor($difference / 3600);
      $minutes = floor(($difference % 3600) / 60);
      $message = "Consultation starts in $hours hours and $minutes minutes.";

      return response()->json([
        'success'=>true,
        'message' => $message,
        'status' => 'not now',
        'astrologer_detail'=>$astrologer_detail,
        'consultation_detail'=>$consultation_detail
      ], 200);
    }elseif($ending_time < $current_time){
      return response()->json([
        'success'=>true,
        'message' => 'Time is over',
        'status' => 'done',
        'astrologer_detail'=>$astrologer_detail,
        'consultation_detail'=>$consultation_detail
      ], 200);
    }

    $astrologer->video = 3;
    $astrologer->save();

    return response()->json([
      'success'=>true,
      'message' => '',
      'status' => 'start',
      'astrologer_detail'=>$astrologer_detail,
      'consultation_detail'=>$consultation_detail
    ], 200);
  }

  public function updateVideoCallStatus(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);

    $consultation_id = $request->consultation_id;
    $astrologer = auth()->guard('astrologer')->user();

    $appointment = AstrologerAppointment::where('package', 1)
    ->where('id', $consultation_id)
    ->where('astrologer_id', $astrologer->id)
    ->first();

    if(!$appointment){
      return response()->json([
        'success'=>false,
        'message' => 'Consultation not found.'
      ], 404);
    }

    $status = 'done';

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if($starting_time <= $current_time && $ending_time > $current_time){
      $appointment->astrologer_end_time = date('H:i:s', $current_time);
      $appointment->astrologer_status = 'done';

      if($appointment->status != "cancel"){
        $appointment->status = 'done';
      }

      $status = 'continue';

      if($request->status == 'leave'){
        $appointment->scheduled_end_time = date('H:i');
        $appointment->max_time = date('H:i', strtotime('+5minutes'));
        $status = 'done';
        $astrologer->video = 1;
      }elseif($request->status == 'cancel'){
        $appointment->astrologer_status = 'cancel';
        $appointment->status = 'cancel';
        $status = 'cancel';
        $astrologer->video = 1;
      }

      $appointment->save();
    }else{
      $astrologer->video = 1;
    }

    return response()->json([
      'success'=>true,
      'status'=>$status
    ], 200);
  }

  public function liveIndex(Request $request){
    $astrologer = auth()->guard('astrologer')->user();
    $hideHeaderNavigation=true;
    $hideFooter=true;
    $astrologerLives=AstrologerLive::where("astrologer_id",$astrologer->id)->where(function($query){
      $query->where("status","scheduled")
      ->orWhere("status","started");
    })->paginate(20);
    return view(themeView().'astrologers.account.live.index', compact("hideHeaderNavigation", 'hideFooter','astrologer',"astrologerLives"));
  }
  public function scheduleLiveStream(Request $request){
    $request->validate([
      "astrologer_id"=>"required|integer|gt:0",
      "start_date"=>"required|date_format:Y-m-d",
      "start_time"=>"required",
      "end_date"=>"required|date_format:Y-m-d",
      "end_time"=>"required",
    ]);
    $astrologer_id=$request->astrologer_id;
    $start_date=$request->start_date;
    $start_time=$request->start_time;
    $start_at=date("Y-m-d H:i:00",strtotime("{$start_date} {$start_time}"));
    $end_date=$request->end_date;
    $end_time=$request->end_time;
    $end_at=date("Y-m-d H:i:00",strtotime("{$end_date} {$end_time}"));
    $astrologerLive=AstrologerLive::where("astrologer_id",$astrologer_id)
    ->where("status", "!=", "cancelled")
    ->where(function($query) use($start_at,$end_at){
      $query->whereBetween("start_at",[$start_at,$end_at])
      ->orWhereBetween("end_at",[$start_at,$end_at])
      ->orWhere(function($querySecond) use($start_at,$end_at){
        $querySecond->where("start_at","<",$start_at)
        ->where("end_at",">",$start_at);
      })
      ->orWhere(function($querySecond) use($start_at,$end_at){
        $querySecond->where("start_at","<",$end_at)
        ->where("end_at",">",$end_at);
      });
    })->first();
    if($astrologerLive){
      return response()->json(["success"=>false,"message"=>"This Slot is already booked."],422);
    }
    $astrologer=Astrologer::find($astrologer_id);
    $astrologerLive=new AstrologerLive();
    $astrologerLive->astrologer_id=$astrologer->id;
    $astrologerLive->astrologer_name=$astrologer->display_name;
    $astrologerLive->logo=asset('assets/img/logo.png');
    $astrologerLive->app_id="";
    $astrologerLive->app_secret="";
    $astrologerLive->app_sign="";
    $astrologerLive->room_id=Str::random(10);
    $astrologerLive->request=json_encode([]);
    $astrologerLive->status="scheduled";
    $astrologerLive->start_at=$start_at;
    $astrologerLive->end_at=$end_at;
    $astrologerLive->save();
    return response()->json(["success"=>true,"message"=>"Booked Successfully."],200);
  }
  public function liveVideo(Request $request){
    $astrologer = auth()->guard('astrologer')->user();
    $astrologerLiveId=$request->live_id?decrypt($request->live_id):0;
    $astrologerLive=AstrologerLive::where("id",$astrologerLiveId)
    ->where("astrologer_id",$astrologer->id)
    ->where("start_at","<=",date('Y-m-d H:i:s',strtotime("now+1minute")))
    ->where("end_at",">=",date('Y-m-d H:i:s',strtotime("now")))
    ->where(function($query){
      $query->whereNull("leaved_at")
      ->orWhere("leaved_at","<=",date('Y-m-d H:i:s',strtotime("now-10seconds")));
    })
    ->first();
    if($astrologerLive==null){
      return abort(404);
    }
    $data['astrologer_id'] = $astrologerLive->astrologer_id;
    $data['astrologer_name'] = $astrologerLive->astrologer_name;
    $data['logo'] = $astrologerLive->logo;
    $data['app_id'] = fromSetting("agora_app_id");
    $data['app_certificate'] = fromSetting("agora_app_certificate");
    $data['room_id'] = $astrologerLive->room_id;
    $data['uid'] = "@".$astrologer->firstname.$astrologer->id;
    $data['app_secret'] = RtcTokenBuilder2::buildTokenWithUid($data['app_id'], $data["app_certificate"], $data["room_id"], $data["uid"], 1, 86400);
    $data['rtm_token'] = RtmTokenBuilder2::buildToken($data['app_id'], $data["app_certificate"], $data["uid"], 86400);
    $data['app_sign'] = "";
    unset($data['app_certificate']);
    $astrologerLive->app_id=$data['app_id'];
    $astrologerLive->room_id=$data['room_id'];
    $astrologerLive->app_secret=$data['app_secret'];
    // $astrologerLive->app_sign="";
    $astrologerLive->request=json_encode($data);
    if($astrologerLive->joined_at==null){
      $astrologerLive->status="started";
      $astrologerLive->joined_at=date('Y-m-d H:i:s');
    }
    $astrologerLive->save();

    $hideHeaderNavigation=true;
    $hideFooter=true;
    UserNotificationJob::dispatch($astrologer,null,"user","astrologer-live");
    $liveStreamEndsIn=strtotime($astrologerLive->end_at)-strtotime("now");
    return view(themeView().'astrologers.account.live.video', compact("hideHeaderNavigation", 'astrologer', 'astrologerLive', 'data', 'hideFooter',"liveStreamEndsIn"));
  }
  public function liveStreamUpdate(Request $request){
    $astrologer_live_id=$request->astrologer_live_id;
    $status=$request->status;
    $astrologerLive=AstrologerLive::find($astrologer_live_id);
    $astrologerLive->status=$status;
    $astrologerLive->leaved_at=date('Y-m-d H:i:s');
    $astrologerLive->save();
    $audience["total"]=AstrologerLiveUser::where("astrologer_live_id", $astrologer_live_id)->where("end_at", '>', date("Y-m-d H:i:s", strtotime("now-10seconds")))->count();
    $data["status"]="continue";
    $currentTimestamp=strtotime("now-5seconds");
    $endAtTimestamp=strtotime($astrologerLive->end_at);
    if($endAtTimestamp <= $currentTimestamp){
      $data["status"]="end";
    }
    return response()->json(['success'=>true, 'message'=>'Status Updated', 'audience'=>$audience,"data"=>$data], 200);
  }

  public function assignment(Request $request){
    $user = auth()->guard('astrologer')->user();
    $orders = Order::where('user_id', $user->id)->where('user_type', 'astrologer')->get();
    return view(themeView() . 'astrologers.assignments', compact('orders', 'user'));
  }
  public function meeting(Request $request){
    $user = auth()->guard('astrologer')->user();
    $orders = Order::where('user_id', $user->id)->where('user_type', 'astrologer')->get();
    return view(themeView() . 'astrologers.meeting', compact('orders', 'user'));
  }
  public function quizzes(Request $request){
    $user = auth()->guard('astrologer')->user();
    $orders = Order::where('user_id', $user->id)->where('user_type', 'astrologer')->get();
    return view(themeView() . 'astrologers.quizzes', compact('orders', 'user'));
  }

  public function financial(Request $request){
    $user = auth()->guard('astrologer')->user();
    $orders = Order::where('user_id', $user->id)->where('user_type', 'astrologer')->get();
    return view(themeView() . 'astrologers.financial', compact('orders', 'user'));
  }

  public function question(Request $request){
    $user = auth()->guard('astrologer')->user();
    $orders = Order::where('user_id', $user->id)->where('user_type', 'astrologer')->get();
    return view(themeView() . 'astrologers.question', compact('orders', 'user'));
  }

  public function newsletter(Request $request){
    $user = auth()->guard('astrologer')->user();
    $orders = Order::where('user_id', $user->id)->where('user_type', 'astrologer')->get();
    return view(themeView() . 'astrologers.newsletter', compact('orders', 'user'));
  }

  // public function support(Request $request){
  //   $user = auth()->guard('astrologer')->user();
  //   $orders = Order::where('user_id', $user->id)->where('user_type', 'astrologer')->get();
  //   return view(themeView() . 'astrologers.support', compact('orders', 'user'));
  // }

  public function notification(Request $request){
    $nav = 'notification';
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $for = [0, 2];
    if(!$user){
      return redirect()->route('home');
    }
    $userIdIn = [$user->id, 0];

    $notifications = NotificationModel::whereIn('user_id', $userIdIn)
    ->where('created_at','>=', $user->created_at)
    ->whereIn('for', $for)
    ->orderBy('id', 'desc')
    ->paginate(10);

    NotificationModel::whereIn('user_id', $userIdIn)
    ->where('created_at','>=', $user->created_at)
    ->whereIn('for', $for)
    ->update(['view' => 1]);

    $hideNotifications = true;
    $hideHeaderNavigation = true;
    $hideFooter = true;
    return view(themeView() . 'astrologers.notification', compact("hideHeaderNavigation", "hideFooter", 'nav', 'notifications', 'hideNotifications'));
  }

  public function updateLoginTime(Request $request){
    $astrologer = auth()->guard('astrologer')->user();
    $astrologer->last_logined=date('Y-m-d H:i:s');
    $astrologer->save();
    return response()->json(['success'=>true, 'message'=>'Login time update']);
  }

  public function switch(Request $request){
    $astrologer = auth()->guard('astrologer')->user();
    $astrologer = Astrologer::find($astrologer->id);
    if($astrologer->userr){
      $astrologer->video = 2;
      $astrologer->chat = 2;
      $astrologer->call = 2;
      $astrologer->save();
      Auth::guard('astrologer')->logout();
      $user = User::find($astrologer->userr->id);
      Auth::guard('web')->login($user);
      $user->last_logined = date('Y-m-d H:i:s');
      $user->save();
      return redirect()->route('user.dashboard');
    }
    return redirect()->back();
  }

  public function consultationRequest(Request $request){
    $this->validate($request, [
      'request_type'=>'required',
      'appointment_id'=>'required',
      'message'=>'required',
    ]);
    $user = auth()->guard('astrologer')->user();
    $user_type = 'astrologer';
    $consultation_request = ConsultationRequest::where('appointment_id', $request->appointment_id)->where('status', 0)->first();
    if($consultation_request){
      return response()->json(['success'=>false, 'message'=>'Please Try again later'], 422);
    }
    $data = AstrologerAppointment::find($request->appointment_id);
    if(!$data){
      return response()->json(['success'=>false, 'message'=>'Appointment not found.'], 422);
    }
    if($request->request_type=='reschedule'){
      $check_counter = [$data->id, $data->appointment_id];
      $consultation_counter = ConsultationCounter::whereIn('appointment_id', $check_counter)->where('user_id', $user->id)->where('user_type', $user_type)->first();
      if($consultation_counter->counter==2){
        return response()->json(['success'=>false, 'message'=>'Reached maximum limit of reschedule.'], 422);
      }
    }
    $cancel_before = fromSetting('cancel_before')?:24;
    $cancel_before = $cancel_before*60*60;
    $scheduled = strtotime($data->scheduled_date.' '.$data->scheduled_time)-time();
    if($cancel_before >= $scheduled){
      return response()->json(['success'=>false, 'message'=>"Your scheduled consultation is about to begin. It won't be ".ucfirst($request->request_type) ], 422);
    }
    $consultation_request = new ConsultationRequest();
    $consultation_request->appointment_id = $request->appointment_id;
    $consultation_request->user_id = $user->id;
    $consultation_request->user_type = $user_type;
    $consultation_request->message = $request->message;
    $consultation_request->request_type = $request->request_type;
    $consultation_request->status = 0;
    $consultation_request->save();
    if($request->request_type=='cancel'){
      ConsultationCancelJob::dispatch('mehtachandansingh101@gmail.com', 'astrologer', 'admin', $data, 'request', $request->message);
    }elseif($request->request_type=='reschedule'){
      ConstultatonRescheduleJob::dispatch('mehtachandansingh101@gmail.com', 'astrologer', 'admin', $data, 'request', $request->message);
    }
    return response()->json(['success'=>true, 'message'=>'Requst Submit successfully'], 500);
  }

  public function getProductsAndReports(Request $request){
    try {
      // Get products (type = 1)
      $products = Product::where('status', 1)
        ->where('parent_id', 0)
        ->where('type', 1)
        ->where('avaibility', '>', 0)
        ->select('id', 'title', 'base_price', 'sale_price')
        ->orderBy('title', 'asc')
        ->get()
        ->map(function($product){
          return [
            'id' => $product->id,
            'title' => $product->title,
            'base_price' => $product->base_price,
            'sale_price' => $product->sale_price
          ];
        });

      // Get reports (type = 3)
      $reports = Product::where('status', 1)
        ->where('parent_id', 0)
        ->where('type', 3)
        ->select('id', 'title', 'base_price', 'sale_price')
        ->orderBy('title', 'asc')
        ->get()
        ->map(function($report){
          return [
            'id' => $report->id,
            'title' => $report->title,
            'base_price' => $report->base_price,
            'sale_price' => $report->sale_price
          ];
        });

      // Get pujas (type = 2)
      $pujas = Product::where('status', 1)
        ->where('parent_id', 0)
        ->where('type', 2)
        ->select('id', 'title', 'base_price', 'sale_price')
        ->orderBy('title', 'asc')
        ->get()
        ->map(function($puja){
          return [
            'id' => $puja->id,
            'title' => $puja->title,
            'base_price' => $puja->base_price,
            'sale_price' => $puja->sale_price
          ];
        });

      return response()->json([
        'success' => true,
        'products' => $products,
        'reports' => $reports,
        'pujas' => $pujas
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to load products, reports and pujas'
      ], 500);
    }
  }

  public function suggestRemedies(Request $request){
    $this->validate($request, [
      'appointment_id' => 'required|exists:astrologer_appointments,id',
      'user_id' => 'required|exists:users,id',
      'products' => 'nullable|array',
      'reports' => 'nullable|array',
      'pujas' => 'nullable|array',
    ]);

    $astrologer = auth()->guard('astrologer')->user();
    $appointment = AstrologerAppointment::find($request->appointment_id);

    if(!$appointment || $appointment->astrologer_id != $astrologer->id){
      return response()->json([
        'success' => false,
        'message' => 'Invalid appointment'
      ], 422);
    }

    $products = $request->products ?: [];
    $reports = $request->reports ?: [];
    $pujas = $request->pujas ?: [];

    if(empty($products) && empty($reports) && empty($pujas)){
      return response()->json([
        'success' => false,
        'errors' => ['products' => ['Please select at least one product, report or puja']]
      ], 422);
    }

    // Get astrologer referral code from astrologers table (not from astrologer_referrals table)
    $referralCode = $astrologer->referral_code;

    // Create a unique token for this suggestion
    $token = Str::random(32);

    // Store suggestion data in session or database (using database for persistence)
    DB::table('astrologer_remedy_suggestions')->insert([
      'astrologer_id' => $astrologer->id,
      'user_id' => $request->user_id,
      'appointment_id' => $request->appointment_id,
      'products' => json_encode($products),
      'reports' => json_encode($reports),
      'pujas' => json_encode($pujas),
      'referral_code' => $referralCode,
      'token' => $token,
      'created_at' => now(),
      'updated_at' => now()
    ]);

    // Create checkout link with referral code as query parameter
    $checkoutLink = route('remedies.checkout', ['token' => $token]);
    if($referralCode){
      $checkoutLink .= '?ref=' . urlencode($referralCode);
    }

    // Send notification to user
    $user = User::find($request->user_id);
    if($user){
      // Save in-app notification
      $notification = new NotificationModel();
      $notification->user_id = $user->id;
      $notification->astrologer_id = 0;
      $notification->message = 'Suggested remedies from ' . $astrologer->display_name;
      $notification->link = $checkoutLink;
      $notification->app_link = 'remedies_checkout';
      $notification->for = 1; // user
      $notification->on = 0;
      $notification->status = 1;
      $notification->save();

      // Send email notification
      $emailSubject = 'Suggested Remedies from ' . $astrologer->display_name;
      $emailMessage = 'Dear ' . $user->name . ",\n\n" .
        $astrologer->display_name . ' has suggested some remedies for you. ' .
        'Click on the link below to view and purchase the suggested remedies:' . "\n\n" .
        $checkoutLink . "\n\n" .
        'Thank you!';

      try {
        $user->notify(new EmailWithCustomTextNotification($emailSubject, $emailMessage));
      } catch (\Exception $e) {
        \Log::error('Failed to send email notification for suggested remedies: ' . $e->getMessage());
      }

      // Send push notification if FCM token exists
      if($user->fcm_token){
        $pushNotificationController = new \App\Http\Controllers\PushNotificationController();
        $pushNotificationController->sendNotification(
          $user->fcm_token,
          'Suggested Remedies',
          'Suggested remedies from ' . $astrologer->display_name,
          ['type' => 'remedies_checkout', 'link' => $checkoutLink]
        );
      }
    }

    return response()->json([
      'success' => true,
      'message' => 'Remedies suggested successfully. User will be notified.'
    ], 200);
  }

  public function deletion(Request $request){
    $hideHeaderNavigation = true;
    $hideFooter = true;
    return  view(themeView().'astrologers.account.deletion', compact("hideHeaderNavigation", "hideFooter"));
  }

  public function deletionConfirm(Request $request){
      $user = auth()->guard('astrologer')->user();
      $user->is_delete_requested = 1;
      $user->delete_request_on = now();
      $user->save();

      Notification::route('mail', [
        config("vrdcms.admin_email") => 'Customer Support',
      ])->notify(new AccountDeleteRequestAdminNotification($user));
      $user->deleteAccountMail();
      $appName = config("app.name");
    return  redirect()->route('astrologer.dashboard')->with('message', "Account Delete request to $appName Team, Will update you soon on mail.");
  }

  public function puja(){
    $nav = 'puja';
    $astrologer = auth()->guard('astrologer')->user();
    $hideHeaderNavigation = true;
    $hideFooter = true;

    // Get one-to-one pujas assigned to this astrologer (with pivot timestamps)
    $oneToOnePujas = Product::where('type', 2)
      ->where('is_one_to_one_puja', 1)
      ->where('status', 1)
      ->whereHas('astrologers', function($query) use ($astrologer) {
        $query->where('astrologers.id', $astrologer->id);
      })
      ->with(['astrologers' => function($query) use ($astrologer) {
        $query->where('astrologers.id', $astrologer->id);
      }])
      ->orderBy('id', 'DESC')
      ->get();

    // Get group pujas assigned to this astrologer using pandit_id column
    // Only show main pujas (parent_id = 0 or null), not variations
    $groupPujaParentIds = Product::where('type', 2)
      ->where(function($query) {
        $query->where('is_one_to_one_puja', '!=', 1)
              ->orWhereNull('is_one_to_one_puja');
      })
      ->where('status', 1)
      ->where('pandit_id', $astrologer->id)
      ->where(function($query) {
        $query->where('parent_id', 0)
              ->orWhereNull('parent_id');
      })
      ->pluck('id')
      ->toArray();

    // Also get parent IDs if variations are assigned
    $variationParentIds = Product::where('type', 2)
      ->where(function($query) {
        $query->where('is_one_to_one_puja', '!=', 1)
              ->orWhereNull('is_one_to_one_puja');
      })
      ->where('status', 1)
      ->where('pandit_id', $astrologer->id)
      ->where('parent_id', '>', 0)
      ->pluck('parent_id')
      ->unique()
      ->toArray();

    // Merge and get unique parent IDs
    $allParentIds = array_unique(array_merge($groupPujaParentIds, $variationParentIds));

    // Get only parent products
    $groupPujas = Product::whereIn('id', $allParentIds)
      ->where(function($query) {
        $query->where('parent_id', 0)
              ->orWhereNull('parent_id');
      })
      ->with(['astrologers' => function($query) use ($astrologer) {
        $query->where('astrologers.id', $astrologer->id);
      }])
      ->orderBy('id', 'DESC')
      ->get();

    return view(themeView().'astrologers.account.puja', compact('nav', 'astrologer', 'oneToOnePujas', 'groupPujas', 'hideHeaderNavigation', 'hideFooter'));
  }

  public function bookedPujaDetail(Request $request, $id)
  {
    $nav = 'booked-puja';
    $astrologer = auth()->guard('astrologer')->user();
    $hideHeaderNavigation = true;
    $hideFooter = true;

    // Get assignment for this astrologer
    $assignment = \App\Model\PujaAstrologerAssignment::with(['product', 'astrologer', 'order.user', 'orderItem', 'mahurat'])
      ->where('id', $id)
      ->where('astrologer_id', $astrologer->id)
      ->first();

    if(!$assignment) {
      return redirect()->route('astrologer.booked.puja')->with('error', 'Puja not found');
    }

    // Get all media (not just approved) for astrologer view
    $images = \App\Model\AstrologerPujaMedia::where('assignment_id', $id)
      ->where('type', 'image')
      ->with('media')
      ->orderBy('created_at', 'desc')
      ->get();

    $videos = \App\Model\AstrologerPujaMedia::where('assignment_id', $id)
      ->where('type', 'video')
      ->with('video')
      ->orderBy('created_at', 'desc')
      ->get();

    return view(themeView().'astrologers.account.booked-puja-detail', compact('nav', 'astrologer', 'assignment', 'images', 'videos', 'hideHeaderNavigation', 'hideFooter'));
  }

  public function bookedPuja(){
    $nav = 'booked-puja';
    $astrologer = auth()->guard('astrologer')->user();
    $hideHeaderNavigation = true;
    $hideFooter = true;

    // Get only assigned pujas (booked pujas) for this astrologer
    // First get all assignments with status 'assigned'
    $allAssignments = \App\Model\PujaAstrologerAssignment::where('astrologer_id', $astrologer->id)
      ->where('status', 'assigned')
      ->with(['product', 'order.user', 'orderItem', 'mahurat'])
      ->withCount([
        'media as total_images_count' => function($query) {
          $query->where('type', 'image');
        },
        'media as total_videos_count' => function($query) {
          $query->where('type', 'video');
        }
      ])
      ->get();

    // Filter to only show one-to-one pujas that are active and not expired
    $oneToOneBookedPujas = $allAssignments->filter(function($assignment) {
      if(!$assignment->product) {
        return false;
      }
      $product = $assignment->product;

      // Check if puja is expired
      if($product->expire_at) {
        $expireTime = strtotime($product->expire_at);
        $currentTime = time();
        if($expireTime < $currentTime) {
          return false; // Puja has expired
        }
      }

      return $product->type == 2
             && $product->is_one_to_one_puja == 1
             && $product->status == 1;
    })->values(); // Reindex the collection

    // Sort by assigned_at or created_at
    $oneToOneBookedPujas = $oneToOneBookedPujas->sortByDesc(function($assignment) {
      return $assignment->assigned_at ?? $assignment->created_at;
    })->values();

    // Get group pujas - only show pujas that are actually booked (have successful orders)
    // Only show main pujas (parent_id = 0 or null), not variations

    // First get parent product IDs that have successful orders
    $parentProductIdsWithOrders = \App\OrderItem::where('type', 'shop')
      ->whereHas('order', function($q) {
        $q->where('payment_status', 'Success')
          ->where('status', 'Success');
      })
      ->whereHas('product', function($query) use ($astrologer) {
        $query->where('type', 2)
              ->where(function($q) {
                $q->where('is_one_to_one_puja', '!=', 1)
                  ->orWhereNull('is_one_to_one_puja');
              })
              ->where('status', 1)
              ->where('pandit_id', $astrologer->id);
      })
      ->get()
      ->map(function($orderItem) {
        $product = $orderItem->product;
        // If variation, return parent_id, else return product_id
        if($product && $product->parent_id && $product->parent_id > 0){
          return $product->parent_id;
        }
        return $product ? $product->id : null;
      })
      ->filter()
      ->unique()
      ->toArray();

    // Load parent products; then split by (product, valid_date) so each batch is a separate listing
    $parentProducts = \App\Product::whereIn('id', $parentProductIdsWithOrders)
      ->where(function($query) {
        $query->where('parent_id', 0)
              ->orWhereNull('parent_id');
      })
      ->orderBy('id', 'DESC')
      ->get();

    $groupPujaProducts = collect();
    $tz = config('app.timezone', 'Asia/Kolkata');

    foreach ($parentProducts as $puja) {
      $variationIds = \App\Product::where('parent_id', $puja->id)
        ->where('status', 1)
        ->pluck('id')
        ->toArray();
      $allProductIds = array_merge([$puja->id], $variationIds);

      $allOrderItems = \App\OrderItem::whereIn('product_id', $allProductIds)
        ->where('type', 'shop')
        ->whereHas('order', function($q) {
          $q->where('payment_status', 'Success')
            ->where('status', 'Success');
        })
        ->with('order')
        ->orderBy('id', 'desc')
        ->get();

      // Group by effective valid date (stored at booking or computed for old orders)
      $itemsByValidDate = [];
      foreach ($allOrderItems as $item) {
        $order = $item->order;
        $validDate = $this->getValidDateForGroupOrderAstrologer($order, $puja);
        if (!$validDate) {
          $validDate = 'unknown';
        }
        if (!isset($itemsByValidDate[$validDate])) {
          $itemsByValidDate[$validDate] = collect();
        }
        $itemsByValidDate[$validDate]->push($item);
      }

      $videoCallUrl = $puja->video_call_url;
      if (empty($videoCallUrl)) {
        $videoCallUrl = \App\Product::where('parent_id', $puja->id)
          ->whereNotNull('video_call_url')
          ->where('video_call_url', '!=', '')
          ->value('video_call_url');
      }

      foreach ($itemsByValidDate as $batchValidDate => $orderItems) {
        $orderItems = $orderItems instanceof \Illuminate\Support\Collection ? $orderItems : collect($orderItems);
        if ($orderItems->isEmpty()) {
          continue;
        }

        $timePart = $puja->expire_at ? Carbon::parse($puja->expire_at)->format('H:i:s') : '23:59:59';
        $displayValidTillDatetime = null;
        if ($batchValidDate !== 'unknown') {
          $displayValidTillDatetime = Carbon::parse($batchValidDate . ' ' . $timePart, $tz);
        } elseif ($puja->expire_at) {
          $displayValidTillDatetime = Carbon::parse($puja->expire_at, $tz);
        }

        $familyReferences = $orderItems->pluck('order.group_puja_reference')->filter()->unique()->values();
        $hasFamilies = $familyReferences->count() > 0;

        $upload_media_allowed_after = null;
        $show_upload_media_button = false;
        if ($hasFamilies && $displayValidTillDatetime) {
          $upload_media_allowed_after = $displayValidTillDatetime->copy()->addHours(2);
          $show_upload_media_button = Carbon::now($tz)->gte($upload_media_allowed_after);
        } elseif ($hasFamilies && $puja->expire_at) {
          $expiryDt = Carbon::parse($puja->expire_at, $tz);
          $upload_media_allowed_after = $expiryDt->copy()->addHours(2);
          $show_upload_media_button = Carbon::now($tz)->gte($upload_media_allowed_after);
        } elseif ($hasFamilies) {
          $show_upload_media_button = true;
        }

        $show_join_button = false;
        if (!empty($videoCallUrl) && $displayValidTillDatetime) {
          $joinStart = $displayValidTillDatetime->copy()->subMinutes(15);
          $joinEnd = $displayValidTillDatetime->copy()->addHours(2);
          $show_join_button = Carbon::now($tz)->between($joinStart, $joinEnd);
        } elseif (!empty($videoCallUrl) && $puja->expire_at) {
          $expiryDt = Carbon::parse($puja->expire_at, $tz);
          $joinStart = $expiryDt->copy()->subMinutes(15);
          $joinEnd = $expiryDt->copy()->addHours(2);
          $show_join_button = Carbon::now($tz)->between($joinStart, $joinEnd);
        }

        $batch = (object)[
          'id' => $puja->id,
          'title' => $puja->title,
          'image' => $puja->image,
          'video_call_url' => $videoCallUrl,
          'expire_at' => $puja->expire_at,
          'orderItems' => $orderItems,
          'has_families' => $hasFamilies,
          'upload_media_allowed_after' => $upload_media_allowed_after,
          'show_upload_media_button' => $show_upload_media_button,
          'show_join_button' => $show_join_button,
          'display_valid_till_datetime' => $displayValidTillDatetime,
        ];
        $groupPujaProducts->push($batch);
      }
    }

    // Only show batches that have at least one booking (admin-updated date with no bookings must not show)
    $groupPujaProducts = $groupPujaProducts->filter(function($batch) {
      return $batch->orderItems->count() > 0;
    })->values();

    // Get media for media manager (astrologer type)
    $medias = \App\Model\Media::where('type', 'astrologer')
      ->whereNull('deleted_at')
      ->orderBy('id', 'desc')
      ->limit(50)
      ->get();

    return view(themeView().'astrologers.account.booked-puja', compact('nav', 'astrologer', 'oneToOneBookedPujas', 'groupPujaProducts', 'hideHeaderNavigation', 'hideFooter', 'medias'));
  }

  /**
   * For group puja: which valid date does this order belong to?
   * Uses snapshot stored at booking (group_puja_valid_date) when set; else computes from product dates.
   */
  private function getValidDateForGroupOrderAstrologer($order, $parentProduct)
  {
    if (!$order || !$parentProduct) {
      return null;
    }
    // Use date stored at booking so admin updates to product valid_till_dates don't change behavior
    if (!empty($order->group_puja_valid_date)) {
      return $order->group_puja_valid_date;
    }
    $tz = config('app.timezone', 'Asia/Kolkata');
    $orderDt = $order->created_at ? Carbon::parse($order->created_at)->setTimezone($tz) : null;
    $orderTs = $orderDt ? $orderDt->timestamp : 0;
    $dates = $parentProduct->valid_till_dates;
    $timePart = $parentProduct->expire_at ? Carbon::parse($parentProduct->expire_at)->format('H:i:s') : '23:59:59';
    if (!empty($dates) && is_array($dates)) {
      $dates = array_values(array_filter(array_map('trim', $dates), function ($d) {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $d);
      }));
      sort($dates);
      foreach ($dates as $d) {
        $expiryTs = Carbon::parse($d . ' ' . $timePart, $tz)->timestamp;
        if ($orderTs <= $expiryTs) {
          return $d;
        }
      }
      return count($dates) > 0 ? end($dates) : null;
    }
    if ($parentProduct->expire_at) {
      return Carbon::parse($parentProduct->expire_at)->format('Y-m-d');
    }
    return null;
  }

  public function getGroupPujaFamilies(Request $request){
    $productId = $request->product_id;
    $astrologer = auth()->guard('astrologer')->user();

    if(!$productId){
      return response()->json([
        'success' => false,
        'message' => 'Product ID is required'
      ], 422);
    }

    // Get product and its variations
    $product = \App\Product::find($productId);
    if(!$product){
      return response()->json([
        'success' => false,
        'message' => 'Product not found'
      ], 404);
    }

    // Get parent product if this is a variation
    $parentProduct = $product;
    if($product->parent_id && $product->parent_id > 0){
      $parentProduct = \App\Product::find($product->parent_id);
    }

    // Get all variation IDs
    $variationIds = \App\Product::where('parent_id', $parentProduct->id)
      ->where('status', 1)
      ->pluck('id')
      ->toArray();
    $allProductIds = array_merge([$parentProduct->id], $variationIds);

    // Get all successful orders for this puja assigned to this astrologer
    $orders = \App\Order::whereHas('orderItems', function($query) use ($allProductIds) {
        $query->whereIn('product_id', $allProductIds)
              ->where('type', 'shop');
      })
      ->where('payment_status', 'Success')
      ->where('status', 'Success')
      ->whereNotNull('group_puja_reference')
      ->orderBy('group_puja_reference', 'asc')
      ->get();

    // Families that already have photo or video uploaded by this astrologer – exclude from selection
    $uploadedFamilies = \App\Model\AstrologerPujaMedia::whereIn('product_id', $allProductIds)
      ->where('astrologer_id', $astrologer->id)
      ->whereNotNull('group_puja_reference')
      ->pluck('group_puja_reference')
      ->map(function ($ref) { return trim((string) $ref); })
      ->filter()
      ->unique()
      ->values()
      ->toArray();

    $families = [];
    foreach($orders as $order){
      $familyRef = $order->group_puja_reference;
      $familyRefTrimmed = trim((string) $familyRef);

      // Skip if this family already has any photo or video uploaded
      if (in_array($familyRefTrimmed, $uploadedFamilies)) {
        continue;
      }

      // Get person names from order items formdata
      $orderItems = \App\OrderItem::where('order_id', $order->id)
        ->whereIn('product_id', $allProductIds)
        ->where('type', 'shop')
        ->get();

      $persons = [];
      foreach($orderItems as $orderItem){
        if($orderItem->options){
          $options = json_decode($orderItem->options, true);
          if(isset($options['formdata']) && is_array($options['formdata'])){
            foreach($options['formdata'] as $formField){
              // Skip gotra and ashirwad_box fields
              $itemName = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
              $isGotraField = ($itemName == 'gotra' || $itemName == 'one_gotra' ||
                              (isset($formField['label']) && stripos(strtolower(trim($formField['label'])), 'gotra') !== false));
              $isAshirwadBox = ($itemName == 'ashirwad_box');

              if($isGotraField || $isAshirwadBox){
                continue;
              }

              // Look for name fields - check both name and label
              $isNameField = false;
              if(isset($formField['name'])){
                $fieldName = strtolower($formField['name']);
                // Check if it's a name field (contains 'name' but not 'gotra')
                if(strpos($fieldName, 'name') !== false && strpos($fieldName, 'gotra') === false){
                  $isNameField = true;
                }
              }

              // Also check label
              if(!$isNameField && isset($formField['label'])){
                $fieldLabel = strtolower($formField['label']);
                // Check if label contains 'name' (like "Name", "P1 Name", "P2 Name", "Boy Name", "Girl Name")
                if(strpos($fieldLabel, 'name') !== false && strpos($fieldLabel, 'gotra') === false){
                  $isNameField = true;
                }
              }

              // If it's a name field and has a value, add it
              if($isNameField && isset($formField['value']) && !empty(trim($formField['value']))){
                $nameValue = trim($formField['value']);
                // Skip if it's "I do not know my gotra" or similar
                if(strpos(strtolower($nameValue), 'do not know') === false){
                  // Get label for display (replace P1/P2 with Boy/Girl like order detail page)
                  $displayLabel = isset($formField['label']) ? str_replace(["P1", "P2"], ["Boy", "Girl"], $formField['label']) : 'Name';
                  $persons[] = [
                    'name' => $nameValue,
                    'label' => $displayLabel
                  ];
                }
              }
            }
          }
        }
      }

      // Remove duplicates based on name
      $uniquePersons = [];
      $seenNames = [];
      foreach($persons as $person){
        $personName = is_array($person) ? $person['name'] : $person;
        if(!in_array($personName, $seenNames)){
          $seenNames[] = $personName;
          $uniquePersons[] = is_array($person) ? $person : ['name' => $person, 'label' => 'Name'];
        }
      }
      $persons = $uniquePersons;

      // If no names found in formdata, use order name or a default
      if(empty($persons)){
        $persons[] = [
          'name' => $order->full_name ?: 'Family Member',
          'label' => 'Name'
        ];
      }

      $families[] = [
        'reference' => $familyRef,
        'persons' => $persons, // Already deduplicated above
        'order_id' => $order->id,
        'order_date' => $order->created_at->format('d M Y')
      ];
    }

    return response()->json([
      'success' => true,
      'families' => $families
    ], 200);
  }

  public function saveGroupPujaMedia(Request $request){
    $astrologer = auth()->guard('astrologer')->user();

    $request->validate([
      'product_id' => 'required|exists:products,id',
      'family_references' => 'required|array|min:1',
      'family_references.*' => 'required|string',
      'photo_ids' => 'nullable|array',
      'photo_ids.*' => 'nullable|integer|exists:media,id',
      'video_id' => 'nullable|integer|exists:media,id'
    ]);

    // Verify product belongs to this astrologer
    $product = \App\Product::find($request->product_id);
    if(!$product){
      return response()->json([
        'success' => false,
        'message' => 'Product not found'
      ], 404);
    }

    // Get parent product if variation
    $parentProduct = $product;
    if($product->parent_id && $product->parent_id > 0){
      $parentProduct = \App\Product::find($product->parent_id);
    }

    // Verify astrologer assignment
    if($parentProduct->pandit_id != $astrologer->id){
      return response()->json([
        'success' => false,
        'message' => 'Invalid product or permission denied'
      ], 403);
    }

    try {
      // Save media for each selected family
      foreach($request->family_references as $familyRef){
        // Get order for this family reference
        $order = \App\Order::where('group_puja_reference', $familyRef)
          ->whereHas('orderItems', function($query) use ($parentProduct) {
            $variationIds = \App\Product::where('parent_id', $parentProduct->id)
              ->where('status', 1)
              ->pluck('id')
              ->toArray();
            $allProductIds = array_merge([$parentProduct->id], $variationIds);
            $query->whereIn('product_id', $allProductIds)
                  ->where('type', 'shop');
          })
          ->where('payment_status', 'Success')
          ->where('status', 'Success')
          ->first();

        if(!$order){
          continue; // Skip if order not found
        }

        // Find or create assignment for this group puja order so assignment_id is set in astrologer_puja_media
        $variationIds = \App\Product::where('parent_id', $parentProduct->id)->where('status', 1)->pluck('id')->toArray();
        $allProductIds = array_merge([$parentProduct->id], $variationIds);
        $orderItem = \App\OrderItem::where('order_id', $order->id)
          ->whereIn('product_id', $allProductIds)
          ->where('type', 'shop')
          ->first();
        $assignment = null;
        if ($orderItem) {
          // Only set user_id if it exists in users table (avoids FK constraint when user was deleted or order has invalid user_id)
          $userId = null;
          if (!empty($order->user_id) && \App\User::where('id', $order->user_id)->exists()) {
            $userId = $order->user_id;
          }
          $assignment = PujaAstrologerAssignment::firstOrCreate(
            [
              'order_id' => $order->id,
              'order_item_id' => $orderItem->id,
              'product_id' => $orderItem->product_id,
              'astrologer_id' => $astrologer->id,
            ],
            [
              'user_id' => $userId,
              'status' => 'assigned',
              'assigned_at' => now(),
            ]
          );
        }
        $assignmentId = $assignment ? $assignment->id : null;

        // Delete existing media for this family and product
        \App\Model\AstrologerPujaMedia::where('product_id', $parentProduct->id)
          ->where('astrologer_id', $astrologer->id)
          ->where('group_puja_reference', $familyRef)
          ->delete();

        // Save photos (limit 5 per family)
        if($request->photo_ids && count($request->photo_ids) > 0) {
          $familyImageCount = \App\Model\AstrologerPujaMedia::where('product_id', $parentProduct->id)
            ->where('astrologer_id', $astrologer->id)
            ->where('group_puja_reference', $familyRef)
            ->where('type', 'image')
            ->count();

          $newImageCount = count(array_filter($request->photo_ids));
          if($familyImageCount + $newImageCount > 5) {
            return response()->json([
              'success' => false,
              'message' => 'Maximum 5 images allowed per family. Please delete some images first.'
            ], 422);
          }

          foreach($request->photo_ids as $photoId) {
            if($photoId) {
              $pujaMedia = new \App\Model\AstrologerPujaMedia();
              $pujaMedia->assignment_id = $assignmentId;
              $pujaMedia->astrologer_id = $astrologer->id;
              $pujaMedia->product_id = $parentProduct->id;
              $pujaMedia->order_id = $order->id;
              $pujaMedia->group_puja_reference = $familyRef;
              $pujaMedia->media_id = $photoId;
              $pujaMedia->type = 'image';
              $pujaMedia->is_approved = 0; // Requires admin approval
              $pujaMedia->save();
            }
          }
        }

        // Save video (one video per family)
        if($request->video_id) {
          // Delete existing video for this family
          \App\Model\AstrologerPujaMedia::where('product_id', $parentProduct->id)
            ->where('astrologer_id', $astrologer->id)
            ->where('group_puja_reference', $familyRef)
            ->where('type', 'video')
            ->delete();

          $pujaMedia = new \App\Model\AstrologerPujaMedia();
          $pujaMedia->assignment_id = $assignmentId;
          $pujaMedia->astrologer_id = $astrologer->id;
          $pujaMedia->product_id = $parentProduct->id;
          $pujaMedia->order_id = $order->id;
          $pujaMedia->group_puja_reference = $familyRef;
          $pujaMedia->video_id = $request->video_id;
          $pujaMedia->media_id = $request->video_id;
          $pujaMedia->type = 'video';
          $pujaMedia->is_approved = 0; // Requires admin approval
          $pujaMedia->save();
        }
      }

      return response()->json([
        'success' => true,
        'message' => 'Media uploaded successfully'
      ], 200);

    } catch (\Exception $e) {
      \Log::error('Error saving group puja media: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error saving media: ' . $e->getMessage()
      ], 500);
    }
  }

  public function getPujaMedia(Request $request){
    $assignmentId = $request->assignment_id;
    $astrologer = auth()->guard('astrologer')->user();

    // Get all media for this assignment
    $photos = \App\Model\AstrologerPujaMedia::where('assignment_id', $assignmentId)
      ->where('type', 'image')
      ->where('astrologer_id', $astrologer->id)
      ->with('media')
      ->get()
      ->map(function($item) {
        return [
          'id' => $item->id,
          'media_id' => $item->media_id,
          'image_url' => $item->media ? $item->media->image(200, 200) : ''
        ];
      });

    $video = \App\Model\AstrologerPujaMedia::where('assignment_id', $assignmentId)
      ->where('type', 'video')
      ->where('astrologer_id', $astrologer->id)
      ->with('video')
      ->first();

    $videoData = null;
    if($video && $video->video_id) {
      $videoMedia = \App\Model\Media::find($video->video_id);
      $videoData = [
        'id' => $video->id,
        'video_id' => $video->video_id,
        'video_url' => $videoMedia ? $videoMedia->originalPath : ''
      ];
    }

    return response()->json([
      'success' => true,
      'media' => [
        'photos' => $photos,
        'video' => $videoData
      ]
    ], 200);
  }

  public function savePujaMedia(Request $request){
    $astrologer = auth()->guard('astrologer')->user();

    $request->validate([
      'assignment_id' => 'required|exists:puja_astrologer_assignments,id',
      'product_id' => 'required|exists:products,id',
      'order_id' => 'required|exists:orders,id',
      'photo_ids' => 'nullable|array',
      'photo_ids.*' => 'nullable|integer|exists:media,id',
      'video_id' => 'nullable|integer|exists:media,id'
    ]);

    // Verify assignment belongs to this astrologer
    $assignment = \App\Model\PujaAstrologerAssignment::where('id', $request->assignment_id)
      ->where('astrologer_id', $astrologer->id)
      ->first();

    if(!$assignment) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid assignment or permission denied'
      ], 403);
    }

    try {
      // Check image limit (maximum 5 images)
      $photoIds = $request->photo_ids ? array_filter($request->photo_ids) : [];
      if(count($photoIds) > 5) {
        return response()->json([
          'success' => false,
          'message' => 'Maximum 5 images allowed per puja.'
        ], 422);
      }

      // Delete existing media for this assignment
      \App\Model\AstrologerPujaMedia::where('assignment_id', $request->assignment_id)
        ->where('astrologer_id', $astrologer->id)
        ->delete();

      // Save photos
      if(count($photoIds) > 0) {
        foreach($photoIds as $photoId) {
          if($photoId) {
            $pujaMedia = new \App\Model\AstrologerPujaMedia();
            $pujaMedia->assignment_id = $request->assignment_id;
            $pujaMedia->astrologer_id = $astrologer->id;
            $pujaMedia->product_id = $request->product_id;
            $pujaMedia->order_id = $request->order_id;
            $pujaMedia->media_id = $photoId;
            $pujaMedia->type = 'image';
            $pujaMedia->save();
          }
        }
      }

      // Save video (only one video per assignment)
      if($request->video_id) {
        // Delete existing video if any
        \App\Model\AstrologerPujaMedia::where('assignment_id', $request->assignment_id)
          ->where('astrologer_id', $astrologer->id)
          ->where('type', 'video')
          ->delete();

        $pujaMedia = new \App\Model\AstrologerPujaMedia();
        $pujaMedia->assignment_id = $request->assignment_id;
        $pujaMedia->astrologer_id = $astrologer->id;
        $pujaMedia->product_id = $request->product_id;
        $pujaMedia->order_id = $request->order_id;
        $pujaMedia->video_id = $request->video_id;
        $pujaMedia->media_id = $request->video_id; // Also set media_id for video
        $pujaMedia->type = 'video';
        $pujaMedia->save();
      }

      return response()->json([
        'success' => true,
        'message' => 'Media uploaded successfully'
      ], 200);

    } catch (\Exception $e) {
      \Log::error('Error saving puja media: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error saving media: ' . $e->getMessage()
      ], 500);
    }
  }

  public function uploadPujaMedia(Request $request)
  {
    $astrologer = auth()->guard('astrologer')->user();

    $request->validate([
      'assignment_id' => 'required|exists:puja_astrologer_assignments,id',
      'media_id' => 'required|integer|exists:media,id',
      'media_type' => 'required|in:image,video'
    ]);

    // Verify assignment belongs to this astrologer
    $assignment = \App\Model\PujaAstrologerAssignment::where('id', $request->assignment_id)
      ->where('astrologer_id', $astrologer->id)
      ->first();

    if(!$assignment) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid assignment or permission denied'
      ], 403);
    }

    try {
      $mediaType = $request->media_type;

      // Check limits based on type
      if($mediaType === 'image') {
        $imageCount = \App\Model\AstrologerPujaMedia::where('assignment_id', $request->assignment_id)
          ->where('type', 'image')
          ->count();
        if($imageCount >= 5) {
          return response()->json([
            'success' => false,
            'message' => 'Maximum 5 images allowed per puja. Please delete an image first.'
          ], 422);
        }
      } else if($mediaType === 'video') {
        // Check if video already exists
        $existingVideo = \App\Model\AstrologerPujaMedia::where('assignment_id', $request->assignment_id)
          ->where('type', 'video')
          ->first();
        if($existingVideo) {
          return response()->json([
            'success' => false,
            'message' => 'A video already exists for this puja. Please delete it first to upload a new one.'
          ], 422);
        }
      }

      // Create puja media record
      $pujaMedia = new \App\Model\AstrologerPujaMedia();
      $pujaMedia->assignment_id = $request->assignment_id;
      $pujaMedia->astrologer_id = $astrologer->id;
      $pujaMedia->product_id = $assignment->product_id;
      $pujaMedia->order_id = $assignment->order_id;
      $pujaMedia->media_id = $request->media_id;
      $pujaMedia->type = $mediaType;

      if($mediaType === 'video') {
        $pujaMedia->video_id = $request->media_id;
      }
      $pujaMedia->is_approved = 0; // Both photo and video require admin approval

      $pujaMedia->save();

      return response()->json([
        'success' => true,
        'message' => ucfirst($mediaType) . ' uploaded successfully'
      ], 200);

    } catch (\Exception $e) {
      \Log::error('Error uploading puja media: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error uploading media: ' . $e->getMessage()
      ], 500);
    }
  }

  public function getPujaMahurat(Request $request){
    $assignmentId = $request->assignment_id;
    $astrologer = auth()->guard('astrologer')->user();

    // Verify assignment belongs to this astrologer
    $assignment = \App\Model\PujaAstrologerAssignment::where('id', $assignmentId)
      ->where('astrologer_id', $astrologer->id)
      ->first();

    if(!$assignment) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid assignment or permission denied'
      ], 403);
    }

    // Get mahurat for this assignment
    $mahurat = \App\Model\PujaMahurat::where('assignment_id', $assignmentId)->first();

    $mahuratData = null;
    if($mahurat) {
      $mahuratData = [
        'id' => $mahurat->id,
        'date' => $mahurat->date ? $mahurat->date->format('Y-m-d') : null,
        'time' => $mahurat->time ? date('H:i', strtotime($mahurat->time)) : null
      ];
    }

    return response()->json([
      'success' => true,
      'mahurat' => $mahuratData
    ], 200);
  }

  public function savePujaMahurat(Request $request){
    $astrologer = auth()->guard('astrologer')->user();

    $request->validate([
      'assignment_id' => 'required|exists:puja_astrologer_assignments,id',
      'date' => 'required|date',
      'time' => 'required|date_format:H:i'
    ]);

    // Verify assignment belongs to this astrologer
    $assignment = \App\Model\PujaAstrologerAssignment::where('id', $request->assignment_id)
      ->where('astrologer_id', $astrologer->id)
      ->with(['order.user', 'product', 'astrologer'])
      ->first();

    if(!$assignment) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid assignment or permission denied'
      ], 403);
    }

    try {
      // Find existing mahurat or create new one
      $mahurat = \App\Model\PujaMahurat::where('assignment_id', $request->assignment_id)->first();

      if(!$mahurat) {
        $mahurat = new \App\Model\PujaMahurat();
        $mahurat->assignment_id = $request->assignment_id;
      }

      $mahurat->date = $request->date;
      $mahurat->time = $request->time;
      $mahurat->save();

      // Send notification to user who booked the puja
      if($assignment->order && $assignment->order->user) {
        $user = $assignment->order->user;
        $product = $assignment->product;
        $pujaName = $product ? ($product->title ?? 'Puja') : 'Puja';
        $userName = $user->name ?? 'User';
        $astrologerName = $assignment->astrologer ? trim(($assignment->astrologer->firstname ?? '') . ' ' . ($assignment->astrologer->lastname ?? '')) : 'Astrologer';
        $videoCallUrl = $product && !empty($product->video_call_url) ? $product->video_call_url : null;

        $user->notify(new \App\Notifications\PujaMahuratSetNotification($userName, $astrologerName, $pujaName, $request->date, $request->time, $videoCallUrl));
      }

      return response()->json([
        'success' => true,
        'message' => 'Mahurat saved successfully'
      ], 200);

    } catch (\Exception $e) {
      \Log::error('Error saving puja mahurat: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error saving mahurat: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Show users and astrologers who registered using this astrologer's referral code
   */
  public function referralListing()
  {
    $nav = 'referral-listing';
    $astrologer = auth()->guard('astrologer')->user();

    // Get users who used this astrologer's referral code
    $referredUsers = User::where('used_referral_code', $astrologer->referral_code)
      ->where('status', 1)
      ->orderBy('created_at', 'DESC')
      ->get();

    // Get astrologers who used this astrologer's referral code
    $referredAstrologers = Astrologer::where('used_referral_code', $astrologer->referral_code)
      ->where('status', 1)
      ->orderBy('created_at', 'DESC')
      ->get();

    $hideHeaderNavigation = true;
    $hideFooter = true;

    return view(themeView().'astrologers.account.referral-listing', compact('nav', 'astrologer', 'referredUsers', 'referredAstrologers', 'hideHeaderNavigation', 'hideFooter'));
  }

  public function uploadPujaImage(Request $request)
  {
    $astrologer = auth()->guard('astrologer')->user();

    $request->validate([
      'assignment_id' => 'required|exists:puja_astrologer_assignments,id',
      'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120'
    ], [
      'file.required' => 'Please select an image file',
      'file.image' => 'The uploaded file must be an image',
      'file.mimes' => 'Allowed image formats: JPEG, JPG, PNG, GIF, WEBP',
      'file.max' => 'Maximum image size is 5MB'
    ]);

    // Verify assignment belongs to this astrologer
    $assignment = \App\Model\PujaAstrologerAssignment::where('id', $request->assignment_id)
      ->where('astrologer_id', $astrologer->id)
      ->first();

    if(!$assignment) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid assignment or permission denied'
      ], 403);
    }

    // Check image limit for one-to-one puja
    $imageCount = \App\Model\AstrologerPujaMedia::where('assignment_id', $request->assignment_id)
      ->where('type', 'image')
      ->count();
    if($imageCount >= 5) {
      return response()->json([
        'success' => false,
        'message' => 'Maximum 5 images allowed per puja'
      ], 422);
    }

    $file = $request->file('file');

    if(!$file || !$file->isValid()) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid file. Please select a valid image file.'
      ], 422);
    }

    try {
      // Ensure type for media (MediaController expects it)
      $request->merge(['type' => 'astrologer']);
      // Upload image using MediaController
      $mediaController = new \App\Http\Controllers\Admin\MediaController();
      $uploadResponse = $mediaController->upload($request);

      if($uploadResponse->getStatusCode() !== 200) {
        $responseData = json_decode($uploadResponse->getContent(), true);
        return response()->json([
          'success' => false,
          'message' => $responseData['message'] ?? 'Error uploading image'
        ], 422);
      }

      $responseData = json_decode($uploadResponse->getContent(), true);
      $mediaId = $responseData['media_id'] ?? $responseData['id'] ?? null;

      if(!$mediaId) {
        return response()->json([
          'success' => false,
          'message' => 'Error uploading image. Media ID not found.'
        ], 500);
      }

      // Create puja media record
      $pujaMedia = new \App\Model\AstrologerPujaMedia();
      $pujaMedia->assignment_id = $request->assignment_id;
      $pujaMedia->astrologer_id = $astrologer->id;
      $pujaMedia->product_id = $assignment->product_id;
      $pujaMedia->order_id = $assignment->order_id;
      $pujaMedia->type = 'image';
      $pujaMedia->media_id = $mediaId;
      $pujaMedia->is_approved = 0; // Requires admin approval
      $pujaMedia->save();

      return response()->json([
        'success' => true,
        'message' => 'Image uploaded successfully',
        'media' => [
          'id' => $pujaMedia->id,
          'type' => $pujaMedia->type,
          'media_id' => $pujaMedia->media_id,
          'is_approved' => $pujaMedia->is_approved
        ]
      ], 200);
    } catch (\Exception $e) {
      \Log::error('Error uploading puja image: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error uploading image: ' . $e->getMessage()
      ], 500);
    }
  }

  public function uploadPujaVideo(Request $request)
  {
    $astrologer = auth()->guard('astrologer')->user();

    // Check if this is a URL upload
    if($request->has('video_url') && $request->video_url) {
      return $this->uploadVideoFromUrl($request);
    }

    $request->validate([
      'assignment_id' => 'required|exists:puja_astrologer_assignments,id'
    ]);

    // Verify assignment belongs to this astrologer
    $assignment = \App\Model\PujaAstrologerAssignment::where('id', $request->assignment_id)
      ->where('astrologer_id', $astrologer->id)
      ->first();

    if(!$assignment) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid assignment or permission denied'
      ], 403);
    }

    // Get file from any key (file, video_file, video) – in case PHP limits truncate request
    $file = $request->file('file') ?: $request->file('video_file') ?: $request->file('video');
    if (!$file && !empty($request->allFiles())) {
      $files = $request->allFiles();
      $file = reset($files);
    }

    if(!$file) {
      $contentLength = (int) $request->header('Content-Length');
      if ($contentLength > 5 * 1024 * 1024) {
        return response()->json([
          'success' => false,
          'message' => 'File too large or PHP limit reached. Increase upload_max_filesize and post_max_size in php.ini (e.g. 20M) and try again.'
        ], 422);
      }
      return response()->json([
        'success' => false,
        'message' => 'No file uploaded. Please select a video file and ensure it is under 10MB.'
      ], 422);
    }

    if(!$file->isValid()) {
      $code = $file->getError();
      $msg = $code === UPLOAD_ERR_INI_SIZE ? 'File exceeds server limit. Increase upload_max_filesize in php.ini.' : 'Upload failed. Please try again.';
      return response()->json(['success' => false, 'message' => $msg], 422);
    }

    if (($file->getSize() / 1024) > 10240) {
      return response()->json([
        'success' => false,
        'message' => 'Upload 10 MB or less Video'
      ], 422);
    }

    // Validate file extension
    $allowedExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
    $fileExtension = strtolower($file->getClientOriginalExtension());

    if(!in_array($fileExtension, $allowedExtensions)) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid file type. Allowed formats: ' . implode(', ', $allowedExtensions)
      ], 422);
    }

    // Check file size (10MB = 10240 KB)
    $maxSizeKB = 10240;
    $fileSizeKB = $file->getSize() / 1024;

    if($fileSizeKB > $maxSizeKB) {
      return response()->json([
        'success' => false,
        'message' => 'Upload 10 MB or less Video'
      ], 422);
    }

    try {
      // Create onetoonepuja directory in public if it doesn't exist
      $videoPath = public_path('onetoonepuja');
      if(!File::exists($videoPath)) {
        File::makeDirectory($videoPath, 0755, true);
      }

      // Generate unique filename
      $time = time();
      $originalName = $file->getClientOriginalName();
      $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
      $fileName = $time . '_' . $sanitizedName . '.' . $fileExtension;

      // Move uploaded file
      $file->move($videoPath, $fileName);

      // Create media record
      $attributes = [
        'H' => 0,
        'W' => 0,
        'file' => $fileName
      ];

      $media = new Media();
      $media->file = $fileName;
      $media->title = $file->getClientOriginalName();
      $media->caption = '';
      $media->type = 'astrologer';
      $media->folder_id = 0;
      $media->attributes = json_encode($attributes);
      $media->sizes = json_encode(['0-0']);
      $media->save();

      // Create puja media record
      $pujaMedia = new \App\Model\AstrologerPujaMedia();
      $pujaMedia->assignment_id = $request->assignment_id;
      $pujaMedia->astrologer_id = $astrologer->id;
      $pujaMedia->product_id = $assignment->product_id;
      $pujaMedia->order_id = $assignment->order_id;
      $pujaMedia->type = 'video';
      $pujaMedia->video_id = $media->id;
      $pujaMedia->media_id = $media->id;
      $pujaMedia->is_approved = 0; // Videos require admin approval
      $pujaMedia->save();

      return response()->json([
        'success' => true,
        'media_id' => $media->id,
        'file_path' => asset('onetoonepuja/' . $fileName),
        'message' => 'Video uploaded successfully. It will be reviewed by admin.'
      ], 200);

    } catch (\Exception $e) {
      \Log::error('Error uploading video: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'File failed to upload. Please try again.'
      ], 500);
    }
  }

  private function uploadVideoFromUrl(Request $request)
  {
    try {
      $astrologer = auth()->guard('astrologer')->user();

      $request->validate([
        'video_url' => 'required|url|max:500',
        'assignment_id' => 'required|exists:puja_astrologer_assignments,id'
      ], [
        'video_url.required' => 'Please enter a video URL',
        'video_url.url' => 'Please enter a valid URL',
        'video_url.max' => 'URL is too long'
      ]);

      // Verify assignment belongs to this astrologer
      $assignment = \App\Model\PujaAstrologerAssignment::where('id', $request->assignment_id)
        ->where('astrologer_id', $astrologer->id)
        ->first();

      if(!$assignment) {
        return response()->json([
          'success' => false,
          'message' => 'Invalid assignment or permission denied'
        ], 403);
      }

      $videoUrl = $request->video_url;

      // Validate video file extension in URL
      $allowedExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
      $urlPath = parse_url($videoUrl, PHP_URL_PATH);
      $urlExtension = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));

      // Remove query parameters from extension
      $urlExtension = explode('?', $urlExtension)[0];

      if(!in_array($urlExtension, $allowedExtensions)) {
        \Log::warning('Video URL extension not in allowed list', [
          'url' => $videoUrl,
          'extension' => $urlExtension
        ]);
      }

      // Create media record with URL
      $attributes = [
        'H' => 0,
        'W' => 0,
        'file' => $videoUrl,
        'is_url' => true
      ];

      $media = new Media();
      $media->file = $videoUrl;
      $media->title = 'Video from URL';
      $media->caption = '';
      $media->type = 'astrologer';
      $media->folder_id = 0;
      $media->attributes = json_encode($attributes);
      $media->sizes = json_encode(['0-0']);
      $media->save();

      // Create puja media record
      $pujaMedia = new \App\Model\AstrologerPujaMedia();
      $pujaMedia->assignment_id = $request->assignment_id;
      $pujaMedia->astrologer_id = $astrologer->id;
      $pujaMedia->product_id = $assignment->product_id;
      $pujaMedia->order_id = $assignment->order_id;
      $pujaMedia->type = 'video';
      $pujaMedia->video_id = $media->id;
      $pujaMedia->media_id = $media->id;
      $pujaMedia->is_approved = 0; // Videos require admin approval
      $pujaMedia->save();

      return response()->json([
        'success' => true,
        'media_id' => $media->id,
        'file_path' => $videoUrl,
        'message' => 'Video URL added successfully. It will be reviewed by admin.'
      ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      \Log::error('Error adding video URL: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Failed to add video URL. Please try again.'
      ], 500);
    }
  }

  public function deletePujaMedia(Request $request)
  {
    $astrologer = auth()->guard('astrologer')->user();

    $request->validate([
      'media_id' => 'required|exists:astrologer_puja_media,id'
    ]);

    $media = \App\Model\AstrologerPujaMedia::where('id', $request->media_id)
      ->where('astrologer_id', $astrologer->id)
      ->first();

    if(!$media) {
      return response()->json([
        'success' => false,
        'message' => 'Media not found or permission denied'
      ], 404);
    }

    try {
      $media->delete();
      return response()->json([
        'success' => true,
        'message' => 'Media deleted successfully'
      ], 200);
    } catch (\Exception $e) {
      \Log::error('Error deleting puja media: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error deleting media'
      ], 500);
    }
  }

  public function pujaEarnings(Request $request)
  {
    $nav = 'puja-earnings';
    $astrologer = auth()->guard('astrologer')->user();

    $pujaEarnings = WalletTransition::where('user_id', $astrologer->id)
      ->where('user_type', 'astrologer')
      ->where('table_name', 'puja_astrologer_assignments')
      ->where('action', 1)
      ->where('status', 1)
      ->with(['pujaAssignment.orderItem.product', 'pujaAssignment.orderItem.order', 'pujaAssignment.product'])
      ->orderBy('created_at', 'desc')
      ->get();

    $hideHeaderNavigation = true;
    $hideFooter = true;

    return view(themeView().'astrologers.account.puja-earnings', compact('nav', 'astrologer', 'pujaEarnings', 'hideHeaderNavigation', 'hideFooter'));
  }

  public function pujaEarningsPdf(Request $request)
  {
    $astrologer = auth()->guard('astrologer')->user();

    $pujaEarnings = WalletTransition::where('user_id', $astrologer->id)
      ->where('user_type', 'astrologer')
      ->where('table_name', 'puja_astrologer_assignments')
      ->where('action', 1)
      ->where('status', 1)
      ->with(['pujaAssignment.orderItem.product', 'pujaAssignment.orderItem.order', 'pujaAssignment.product'])
      ->orderBy('created_at', 'desc')
      ->get();

    $totalEarnings = $pujaEarnings->sum('amount');

    $pdf = PDF::loadView(themeView().'astrologers.account.puja-earnings-pdf', compact('astrologer', 'pujaEarnings', 'totalEarnings'));

    $filename = 'puja-earnings-' . $astrologer->id . '-' . date('Y-m-d') . '.pdf';

    return $pdf->download($filename);
  }

}
