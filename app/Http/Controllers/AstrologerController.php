<?php

namespace App\Http\Controllers;

use App\Http\Controllers\{Controller, PushNotificationController, NotificationController};
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Model\{WalletTransition, Astrologer, Service, Language, CallHistory, PayoutPackage, TempUser, ConsultationCounter, Chat, Banner};
use App\Model\{AstrologerService, AstrologerOfflineReport, AstrologerAppointmentPackage, AstrologerAppointment, AstrologerReview, AstrologerTimeTable};
use App\Model\{Testimonial, VideoTestimonial,Course, Article, Vastu, NgSoftware, OnPage, AiAstrologerAppointment};
use App\{User, AstrologerUserConsultacy, LocationHistory, Product, Order};
use Carbon\Carbon;
use Auth;
use Session;
use DB;
use App\Model\{TemplateFaq};
use App\Services\SearchLocationService;
use Illuminate\Support\Facades\Mail;
use App\Model\Wishlist;
use App\Model\Setting;
use App\Model\{UserRechargeHistory,ApiLog};
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Api;
use App\Jobs\{UserWelcomeEmail, ConsultationJob, AstrologerConsultationJob, FeedbackConsultationJob};
use Illuminate\Support\Facades\Http;
use App\Services\{Meta};

class AstrologerController extends Controller
{

  protected $searchLocationService;

 public function __construct(SearchLocationService $searchLocationService)
 {
     $this->searchLocationService = $searchLocationService;
 }
  public function index(Request $request){
    $astrologersTypesArray = ["ai", "chatwithastrologers", "talktoastrologers"];
    $astrologerType = $request->type; // ai, chatwithastrologers and talktoastrologers
    $mode = $request->mode ?: "chat"; // chat and call
    if(!in_array($astrologerType, $astrologersTypesArray)){
      return abort(404);
    }
    $nav = $astrologerType;
    $appName = config("app.name");
    $metaTitle = "Ai Astrologers | $appName";
    if($astrologerType == "chatwithastrologers"){
      $metaTitle = "Chat With Astrologers | $appName";
      $astrologerType = "astrologer";
    }elseif($astrologerType == "talktoastrologers"){
      $metaTitle = "Talk To Astrologers | $appName";
      $astrologerType = "astrologer";
    }
    $today = date('Y-m-d');
    $banners = Banner::where('type','astrologers')
    ->where('status', 1)
    ->where(function($query) use ($today){
        $query->where('start_date', '<=', $today)
        ->orWhereNull('start_date');
    })
    ->where(function($query) use ($today){
        $query->where('end_date', '>=', $today)
        ->orWhereNull('end_date');
    })
    ->orderBy('order', 'asc')
    ->take(10)
    ->get();
    $path = $uri = $request->path();
    if($callchat = $request->callchat){
      $request->status = [$callchat];
    }
    $services = Service::get();
    $languages = Language::orderBy('position', 'asc')->get();
    $onPage = OnPage::where('table', 'template_faqs')->whereIn('page', [6, 7])->pluck('table_id')->toArray();
    $faqs = TemplateFaq::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(5)->get();
    $onPage = OnPage::where('table', 'testimonials')->where('page', [2, 6])->pluck('table_id')->toArray();
    $textTestimonials = Testimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
    $onPage = OnPage::where('table', 'video_testimonials')->where('page', [2, 6])->pluck('table_id')->toArray();
    $videoTestimonials = VideoTestimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
    $category_id = $request->category_id ?: 0;
    if($astrologerType == "ai"){
      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_ai");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "ai_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_ai", $user_type);
      }
    }
    $breads=[];
    $pageName = "";
    if($astrologerType=="ai"){
      $pageName = "Ai Astrologers";
      $breads=[
        ["name"=>"Ai Astrologers","url"=>url()->current()],
      ];
    }elseif($astrologerType=="chatwithastrologers"){
      $pageName = "Chat With Astrologers";
      $breads=[
        ["name"=>"Chat With Astrologers","url"=>url()->current()],
      ];
    }else{
      $pageName = "Talk To Astrologers";
      $breads=[
        ["name"=>"Talk To Astrologers","url"=>url()->current()],
      ];
    }
    return view(themeView().'astrologers.index', compact('nav', "breads","pageName",'metaTitle', "astrologerType", "mode", 'banners','services', 'languages', 'textTestimonials','videoTestimonials','faqs', "category_id"));
  }

   public function chatWithAstrologers(Request $request){
     $request->type = 'chatwithastrologers';
     $request->mode = "chat";

     $user = auth()->guard("web")->user();
     $user_type = "guest";
     $event = Session::get("view_content_chatwithastrologers");
     if($event == null || ($event == "guest" && $user != null)){
       $timestamp = time();
       $body["event_name"] = "ViewContent";
       $body["event_time"] = $timestamp;
       $body["event_id"] = "chatwithastrologers_{$timestamp}";
       $body["action_source"] = "website";
       $body["event_source_url"] = $request->fullUrl();
       $body["user_data"]["client_ip_address"] = $request->ip();
       $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
       $body["user_data"]["fn"] = sha256("guest");
       if($user){
         $body["user_data"]["em"] = sha256($user->email);
         $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
         $body["user_data"]["fn"] = sha256($user->name);
         $user_type = "user";
       }
       $metaService = new Meta();
       $metaService->sendEvent($body);
       Session::put("view_content_chatwithastrologers", $user_type);
     }
     return $this->index($request);
   }

   public function talkToAstrologers(Request $request){
     $request->type = 'talktoastrologers';
     $request->mode = "call";

     $user = auth()->guard("web")->user();
     $user_type = "guest";
     $event = Session::get("view_content_talktoastrologers");
     if($event == null || ($event == "guest" && $user != null)){
       $timestamp = time();
       $body["event_name"] = "ViewContent";
       $body["event_time"] = $timestamp;
       $body["event_id"] = "talktoastrologers_{$timestamp}";
       $body["action_source"] = "website";
       $body["event_source_url"] = $request->fullUrl();
       $body["user_data"]["client_ip_address"] = $request->ip();
       $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
       $body["user_data"]["fn"] = sha256("guest");
       if($user){
         $body["user_data"]["em"] = sha256($user->email);
         $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
         $body["user_data"]["fn"] = sha256($user->name);
         $user_type = "user";
       }
       $metaService = new Meta();
       $metaService->sendEvent($body);
       Session::put("view_content_talktoastrologers", $user_type);
     }
     return $this->index($request);
   }

   public function filterData(Request $request){
     $astrologerType = $request->type; // ai and astrologer
     $mode = $request->mode; // chat and call

     $query = Astrologer::where('status', 1)
     ->where('type', $astrologerType)
     ->where('is_verified',1)
     ->where('is_delete_requested',0);
     // ->orderBy('order', 'asc');

     if($mode == "chat"){
       $query->where(function($q) {
         $q->where('chat', '!=', 0);
       });
     }else{
       $query->where(function($q) {
         $q->where('call', '!=', 0)
         ->orWhere('video', '!=', 0);
       });
     }

     // Check for search input
     if($request->has('searchByName') && $request->searchByName){
       $query->where('display_name', 'LIKE', '%' . $request->searchByName . '%');
     }

     // Filter by categories (services)
     if($request->has('categories') && !empty($request->categories)){
       $astrologerIds = AstrologerService::whereIn('service_id', $request->categories)->pluck('astrologer_id')->toArray();
       $query->whereIn('id', $astrologerIds);
     }

     // Filter by experience
     if($request->has('experience') && !empty($request->experience)){
       $query->where(function ($query) use ($request){
         foreach ($request->experience as $experienceRange){
           if($experienceRange == '15+'){
             $query->orWhereRaw('YEAR(CURDATE()) - job_start_year >= ?', [15]);
           }else{
             $range = explode('-', $experienceRange);
             if(count($range) == 2){
               $minYears = (int)$range[0];
               $maxYears = (int)$range[1];
               $query->orWhereRaw('YEAR(CURDATE()) - job_start_year BETWEEN ? AND ?', [$minYears, $maxYears]);
             }
           }
         }
       });
     }

     // Filter by price
     if($request->has('price') && !empty($request->price)){
       $query->where(function ($query) use ($request){
         foreach($request->price as $price){
           if($price == '300+'){
             $query->orWhere('chat_price', '>', 300);
           }else{
             list($min, $max) = explode('-', $price);
             $query->orWhereBetween('chat_price', [(int)$min, (int)$max]);
           }
         }
       });
     }

     if($request->has('rating') && !empty($request->rating)){
       $ratings = $request->rating;
       $query->where(function($q) use ($ratings){
         // Handle the '4+' filter first
         if(in_array('4+', $ratings)){
           $q->orWhere('rating', '>=', 4);
         }
         // Remove '4+' from the ratings array since it's already handled
         $filteredRatings = array_diff($ratings, ['4+']);
         // Handle exact star ratings (2, 3, etc.)
         if(!empty($filteredRatings)){
           $q->orWhereIn('rating', $filteredRatings);
         }
       });
     }

     if($request->has('language') && !empty($request->language)){
       $query->whereHas('languages', function($query) use ($request) {
         $query->whereIn('language_id', $request->language);
       });
     }

    // $query->has('timetable');
    if($request->has('language') && !empty($request->language)){
      $query->whereHas('languages', function($query) use ($request){
        $query->whereIn('language_id', $request->language);
      });
    }
    if($request->has('genders') && $request->genders){
      $query->whereIn('gender', $request->genders);
    }
    if($request->sortBy){
      if($request->sortBy == 'whats_new'){
        $query->orderBy('created_at', 'DESC');
      }elseif($request->sortBy == 'price_low_to_high'){
        $query->orderByRaw('price_order ASC');
      }elseif($request->sortBy == 'price_high_to_low'){
        $query->orderByRaw('price_order DESC');
      }elseif($request->sortBy == 'a_to_z'){
        $query->orderBy('display_name', 'ASC');
      }
    }else{
      $query->orderByRaw("
        CASE
          WHEN `call` = 3 OR `chat` = 3 OR `video` = 3 THEN 2
          WHEN `call` = 1 OR `chat` = 1 OR `video` = 1 THEN 1
          WHEN `call` = 2 OR `chat` = 2 OR `video` = 2 THEN 3
          ELSE 4
        END ASC
      ");
      if($astrologerType == "ai"){
        $query->inRandomOrder();
      }
    }
    $selectPriceStatement = '*, IF(sp_from_date IS NOT NULL AND sp_from_date <= CURDATE() AND sp_to_date >= CURDATE(), call_special_price, call_price) as price_order';
    if($mode == "chat"){
      $selectPriceStatement = '*, IF(sp_from_date IS NOT NULL AND sp_from_date <= CURDATE() AND sp_to_date >= CURDATE(), chat_special_price, chat_price) as price_order';
    }
    $astrologers = $query->with('badge')->selectRaw($selectPriceStatement)->paginate(12);
    $data = '';
    $user = auth()->guard("web")->user();
    $user_id = $user ? $user->id : 0;
    $takeConsultation = false;

    if($mode == "chat" && $user_id){
      $userAstrologerAppointment = AstrologerAppointment::where("package", "2")->where("user_id", $user_id)->where("status", "done")->first();
      if($userAstrologerAppointment){
        $takeConsultation = true;
      }
      if($takeConsultation == false){
        $userAstrologerAppointment = AiAstrologerAppointment::where("user_id", $user_id)->where("paid", 1)->first();
        if($userAstrologerAppointment){
          $takeConsultation = true;
        }
      }
    }

    foreach ($astrologers as $astrologer){
      if($astrologer->type == "Astrologer"){
        if($mode == "chat"){
          $data .= view(themeView().'astrologers._chatwithastrologersitem', compact('astrologer', "user_id", "takeConsultation"))->render();
        }else{
          $data .= view(themeView().'astrologers._talktoastrologersitem', compact('astrologer', "user_id"))->render();
        }
        // $data .= view(themeView().'astrologers._item', compact('astrologer', "user_id"))->render();
      }else{
        $data .= view(themeView().'astrologers._aiitem', compact('astrologer', "user_id", "takeConsultation"))->render();
      }
    }
    if(!$data){
      $data = '<p>No match found </p>';
    }

    return response()->json([
      'html' => $data,
      'count' => $astrologers->total(),
      'hasMorePages' => $astrologers->hasMorePages(),
      'currentPage' => $astrologers->currentPage(),
    ], 200);
  }

  public function detail(Request $request){
    $astrologerType = $request->astrologerType ?: "Astrologer";
    $astrologerSlug = $request->slug;
    $astrologer = Astrologer::where("type", $astrologerType)
    ->where('is_verified', 1)
    ->where('status', 1)
    ->where('is_delete_requested', 0)
    ->where('slug', $astrologerSlug)
    ->with('badge')
    // ->has('timetable')
    ->first();
    if(!$astrologer){
      return abort(404);
    }
    $nav = $astrologerType;
    $type = '';
    if($request->type && $astrologer->chat == 1){
      $type = 'chat';
    }elseif($request->type && $astrologer->call == 1){
      $type = 'call';
    }elseif($request->type && $astrologer->video == 1){
      $type = 'video';
    }

    $user = auth()->guard("web")->user();
    $user_id = $user ? $user->id : 0;
    $takeConsultation = false;

    if($user_id){
      $userAstrologerAppointment = AstrologerAppointment::where("package", "2")->where("user_id", $user_id)->where("status", "done")->first();
      if($userAstrologerAppointment){
        $takeConsultation = true;
      }
      if($takeConsultation == false){
        $userAstrologerAppointment = AiAstrologerAppointment::where("user_id", $user_id)->where("paid", 1)->first();
        if($userAstrologerAppointment){
          $takeConsultation = true;
        }
      }
    }

    $takeBooking = 0;
    $bookNow = 0;
    $timetables = $astrologer->timetable;
    $takeBooking = $timetables->count() ? 1 : 0;
    if($astrologer->call == 1 || $astrologer->video == 1 || $astrologer->chat == 1){
      $bookNow = 1;
    }

    $waitingTime = "0:0 m";
    $currentTime = strtotime(date('Y-m-d H:i:s'));
    $diffInSeconds = 0;
    $currentAppointment = $astrologer->currentAppointment();
    $withInThirtyMinutes = $astrologer->withInThirtyMinutes();
    if($currentAppointment || $withInThirtyMinutes){
      $bookNow = 0;
      if(!$currentAppointment){
        $currentAppointment = $withInThirtyMinutes;
      }
      $scheduledTimestamp = strtotime($currentAppointment->scheduled_date . ' ' . $currentAppointment->max_time.'+1minutes');
      $diffInSeconds = $scheduledTimestamp-$currentTime;
      if($diffInSeconds > 0){
        $minutes = floor($diffInSeconds / 60);
        $seconds = $diffInSeconds % 60;
        $waitingTime = "{$minutes}:{$seconds} m";
      }
    }

    $weekDaysArray = [];
    foreach ($timetables as $timetable){
      $weekDaysArray[] = $timetable->week_day;
    }
    $astroreviews = AstrologerReview::where('astrologer_id', $astrologer->id)->where('status',1)->get();
    $reviews = $astroreviews;
    $total_avg = 0;
    foreach($astroreviews as $value){
      $total_avg += $value->review;
    }
    $average = count($astroreviews) > 0 ? $total_avg / count($astroreviews) : 0;
    $consultType =  null;
    $consultUrl =  null;
    $consultMessage =  null;
    $scheduleType =  null;
    if($request->has('consultType')){
      $consultType =  $request->get('consultType');
    }
    if($request->has('consultUrl')){
      $consultUrl =  $request->get('consultUrl');
    }
    if($request->has('consultMessage')){
      $consultMessage =  $request->get('consultMessage');
    }
    if($request->has('scheduleType')){
      $scheduleType =  $request->get('scheduleType');
    }
    $pageFaq=7;
    $pageTestimonial=2;
    if($astrologerType == 'AI'){
      $pageFaq=6;
      $pageTestimonial=6;
    }
    $onPage = OnPage::where('table', 'testimonials')->where('page', $pageTestimonial)->pluck('table_id')->toArray();
    $textTestimonials = Testimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
    $onPage = OnPage::where('table', 'video_testimonials')->where('page', $pageTestimonial)->pluck('table_id')->toArray();
    $videoTestimonials = VideoTestimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();

    $stars = ['0'];
    $stars[5] = AstrologerReview::where('astrologer_id', $astrologer->id)->where('status', 1)->where('review','>=', 5)->count();
    $stars[4] = AstrologerReview::where('astrologer_id', $astrologer->id)->where('status', 1)->where('review','=', 4)->count();
    $stars[3] = AstrologerReview::where('astrologer_id', $astrologer->id)->where('status', 1)->where('review','=', 3)->count();
    $stars[2] = AstrologerReview::where('astrologer_id', $astrologer->id)->where('status', 1)->where('review','=', 2)->count();
    $stars[1] = AstrologerReview::where('astrologer_id', $astrologer->id)->where('status', 1)->where('review','<=', 1)->count();
    $allReviews = array_sum($stars);
    $astrologerServicesIds = AstrologerService::where('astrologer_id', $astrologer->id)->pluck('service_id')->toArray();
    $serviceDocument = Service::whereIn('id', $astrologerServicesIds)->where('document_required', 1)->first();
    $loggedInAstrologer = auth()->guard("astrologer")->user();
    $astrologerReviews = AstrologerReview::where('astrologer_id', $astrologer->id)
    ->where('status', 1)
    ->orderBy('rating', 'DESC')
    ->paginate(5);
    if($astrologer->type == "Astrologer"){
      $onPage = OnPage::where('table', 'template_faqs')->whereIn('page', [0, 4])->pluck('table_id')->toArray();
      $faqs = TemplateFaq::whereIn('id', $onPage)
      ->where('status', 1)
      ->whereIn('category_id', $astrologerServicesIds)
      ->orderBy('order', 'asc')
      ->limit(5)
      ->get();

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_astrologer");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "astrologer_{$timestamp}";
        $body["action_source"] = "website";
        $body["event_source_url"] = $request->fullUrl();
        $body["user_data"]["client_ip_address"] = $request->ip();
        $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
        $body["user_data"]["fn"] = sha256("guest");
        if($user){
          $body["user_data"]["em"] = sha256($user->email);
          $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
          $body["user_data"]["fn"] = sha256($user->name);
          $user_type = "user";
        }
        $body["custom_data"]["content_id"] = $astrologer->id;
        $body["custom_data"]["content_name"] = "Detail Page";
        $body["custom_data"]["content_type"] = "Astrologer";
        $body["custom_data"]["currency"] = "RS";
        $todayDate = date("Y-m-d");
        if($astrologer->sp_from_date && ($astrologer->sp_from_date <= $todayDate) && ($astrologer->sp_to_date >= $todayDate)){
          $body["custom_data"]["value"] = $astrologer->chat_special_price;
        }else{
          $body["custom_data"]["value"] = $astrologer->chat_price;
        }
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_astrologer", $user_type);
      }
      $breads=[
        ["name"=>"Chat With Astrologers","url"=>route("astrologers.talktoastrologers")],
        ["name"=>"Astrologer","url"=>route("astrologers.publicview",[$astrologerType,$astrologer->slug])],
      ];
      // type can be stored as string in DB (VARCHAR)
      $topSellingProducts = Product::where('show_in_astrologer_profile', 1)
        ->whereIn('type', [1, 2, 3, '1', '2', '3'])
        ->orderByRaw('COALESCE(astrologer_profile_sort_order, 9999) ASC')
        ->orderBy('title', 'ASC')
        ->limit(12)
        ->get();
      return view(themeView().'astrologers.detail',
        compact("diffInSeconds", "waitingTime", 'nav', "breads",'astrologer', "astrologerType", "astrologerSlug", 'user', 'weekDaysArray', 'reviews', 'average', 'astroreviews', 'type', 'scheduleType', 'consultType', 'consultUrl', 'consultMessage', 'faqs', 'textTestimonials', 'videoTestimonials', 'takeBooking', 'bookNow', "waitingTime", 'stars', 'serviceDocument', "loggedInAstrologer",'astrologerReviews','allReviews', "takeConsultation", 'topSellingProducts')
      );
    }
    $onPage = OnPage::where('table', 'template_faqs')->whereIn('page', [0, 5])->pluck('table_id')->toArray();
    $faqs = TemplateFaq::whereIn('id', $onPage)
    ->where('status', 1)
    ->whereIn('category_id', $astrologerServicesIds)
    ->orderBy('order', 'asc')
    ->limit(5)
    ->get();

    $user = auth()->guard("web")->user();
    $user_type = "guest";
    $event = Session::get("view_content_ai");
    if($event == null || ($event == "guest" && $user != null)){
      $timestamp = time();
      $body["event_name"] = "ViewContent";
      $body["event_time"] = $timestamp;
      $body["event_id"] = "ai_{$timestamp}";
      $body["action_source"] = "website";
      $body["event_source_url"] = $request->fullUrl();
      $body["user_data"]["client_ip_address"] = $request->ip();
      $body["user_data"]["client_user_agent"] = $request->header('User-Agent');
      $body["user_data"]["fn"] = sha256("guest");
      if($user){
        $body["user_data"]["em"] = sha256($user->email);
        $body["user_data"]["phone"] = sha256(preg_replace('/\D+/', '', $user->phone));
        $body["user_data"]["fn"] = sha256($user->name);
        $user_type = "user";
      }
      $body["custom_data"]["content_id"] = $astrologer->id;
      $body["custom_data"]["content_name"] = "Detail Page";
      $body["custom_data"]["content_type"] = "Astrologer";
      $body["custom_data"]["currency"] = "RS";
      $todayDate = date("Y-m-d");
      if($astrologer->sp_from_date && ($astrologer->sp_from_date <= $todayDate) && ($astrologer->sp_to_date >= $todayDate)){
        $body["custom_data"]["value"] = $astrologer->chat_special_price;
      }else{
        $body["custom_data"]["value"] = $astrologer->chat_price;
      }
      $metaService = new Meta();
      $metaService->sendEvent($body);
      Session::put("view_content_ai", $user_type);
    }
    $breads=[
      ["name"=>"Ai Astrologers","url"=>route("astrologers.index",["ai"])],
      ["name"=>"Ai","url"=>route("astrologers.publicview",[$astrologerType,$astrologer->slug])],
    ];
    return view(themeView().'astrologers.aidetail',
      compact('nav', "breads",'astrologer', "astrologerType", "astrologerSlug", 'user', 'weekDaysArray', 'reviews', 'average', 'astroreviews', 'type', 'scheduleType', 'consultType', 'consultUrl', 'consultMessage', 'faqs', 'textTestimonials', 'videoTestimonials', 'takeBooking', 'bookNow', "waitingTime", 'stars', 'serviceDocument', "loggedInAstrologer",'astrologerReviews','allReviews', "takeConsultation")
    );
  }

    public function makeCallChatRequest(Request $request)
    {
        if(!auth()->guard('web')->check()) abort(404);
        $slug = $request->slug;
        $approve = $request->approve;
        $option = $request->option;
        $astrologer = Astrologer::where('status', 1)
        ->where('is_verified', 1)
        ->where('is_delete_requested', 0)
        ->where('slug', $request->slug)->first();
        if(!$astrologer) return abort(404);
        $user = auth()->guard('web')->user();

        if($user->wallet < $astrologer->per_minute_rate) return redirect()->route('user.recharge');

        if(!$approve){
            return view(themeView().'account.call-confirm', compact('astrologer', 'user', 'option'));
        }

        $newId = time();
        if($option=='call'){
            $minutes = round($user->wallet/((int)$astrologer->per_minute_rate?$astrologer->per_minute_rate:1));
            $newRecord = new CallHistory();
            $newRecord->user_id = $user->id;
            $newRecord->astrologer_id = $astrologer->id;
            $newRecord->from_number = $user->phone;
            $newRecord->to_number = $astrologer->phone;
            $newRecord->call_charges = $astrologer->per_minute_rate;
            $newRecord->call_id = $newId;
            $newRecord->max_minute = $minutes;
            $newRecord->save();
            return redirect()->route('user.'.$option, [$newId]);
        }

        if($option=='chat'){
            $second = round(round($user->wallet/((int)$astrologer->per_minute_rate?$astrologer->per_minute_rate:1))/60);
            $newRecord = new Chat();
            $newRecord->user_id = $user->id;
            $newRecord->astrologer_id = $astrologer->id;
            $newRecord->astrologer_status = 2;
            $newRecord->chat_key = $newId;
            $newRecord->per_minute_charge = $astrologer->per_minute_rate;
            $newRecord->max_time_in_seconds = $second;
            $newRecord->remaining_time_in_seconds = $second;
            $newRecord->user_name = $user->name;
            $newRecord->astrologer_name = $astrologer->firstname.' '.$astrologer->lastname;
            $newRecord->astrologer_image = $astrologer->image;
            $newRecord->save();
            return redirect()->route('user.chat.approval', [$newId]);
        }
    }

    public function verifyEmail(Request $request){
        $email = decrypt($request->email);
        if($astrologer = Astrologer::where('email', $email)->first()){
          if(is_null($astrologer->email_verified_at)){
            $astrologer->email_verified_at = now();
            $astrologer->save();
            UserWelcomeEmail::dispatch($astrologer);
          }
          if($user = User::where('astrologer_id', $astrologer->id)->first()){
            if(is_null($user->email_verified_at)){
              $user->email_verified_at = now();
              $user->save();
              // UserWelcomeEmail::dispatch($astrologer);
            }
          }
          Auth::guard('astrologer')->login($user);
          return redirect()->route('astrologer.dashboard');
        }
        return abort(404);
    }

    public function submitOfflineReport(Request $request) {
      $astrologer = Astrologer::where('status', 1)
      ->where('is_verified', 1)
      ->where('is_delete_requested', 0)
      ->where('slug', $request->slug)->first();
      if(!$astrologer) return abort(404);

      $this->validate($request, [
    'name' => 'required',
    'gender' => 'required|gt:0',
          'birth_time' => 'required',
          'birth_place' => 'required',
          'boy_name' => 'required_if:date_match,true',
          'boy_dob_dd' => 'required_if:date_match,true',
          'boy_dob_mm' => 'required_if:date_match,true',
          'boy_dob_yy' => 'required_if:date_match,true',
          'boy_birth_time' => 'required_if:date_match,true',
          'boy_birth_place' => 'required_if:date_match,true',
          'girl_name' => 'required_if:date_match,true',
          'girl_dob_dd' => 'required_if:date_match,true',
          'girl_dob_mm' => 'required_if:date_match,true',
          'girl_dob_yy' => 'required_if:date_match,true',
          'girl_birth_time' => 'required_if:date_match,true',
          'girl_birth_place' => 'required_if:date_match,true',
    ],
      [
        'gender.gt' => 'The gender is required',
        'boy_name.required_if' => 'The boy name is required',
        'boy_dob_dd.required_if' => 'The boy date is required',
        'boy_dob_mm.required_if' => 'The boy month is required',
        'boy_dob_yy.required_if' => 'The boy year is required',
        'boy_birth_time.required_if' => 'The boy birth time is required',
        'boy_birth_place.required_if' => 'The boy birth place is required',
        'girl_name.required_if' => 'The girl name is required',
        'girl_dob_dd.required_if' => 'The girl date is required',
        'girl_dob_mm.required_if' => 'The girl month is required',
        'girl_dob_yy.required_if' => 'The girl year is required',
        'girl_birth_time.required_if' => 'The girl birth time is required',
        'girl_birth_place.required_if' => 'The girl birth place is required',
      ]);

      $userid = auth()->guard('web')->user()->id;
      $user = User::find($userid);
      $astrologerOffline = new AstrologerOfflineReport();
      $amount = 0;
      $ser = $request->services;
      for($i = 0; $i < count($ser); $i++) {
           foreach(serviceArr() as $key => $service) {
           if($key == $ser[$i]) {
    $amount = $amount + $service['amount'];
    $services[] = $service['name'];
           }
         }
       }
      $astrologerOffline->user_id = $userid;
      $astrologerOffline->astrologer_id = $astrologer->id;
      $astrologerOffline->customer_name = $request->name;
      $astrologerOffline->customer_email = $request->email;
      $astrologerOffline->gender = $request->gender;
      $astrologerOffline->dob_dd = $request->dob_dd;
      $astrologerOffline->dob_mm = $request->dob_mm;
      $astrologerOffline->dob_yy = $request->dob_yyyy;
      $astrologerOffline->birth_time = $request->birth_time;
      $astrologerOffline->birth_place = $request->birth_place;
      $astrologerOffline->is_matchmaking = $request->date_match;
      $astrologerOffline->boy_name = $request->boy_name;
      $astrologerOffline->boy_dob_dd = $request->boy_dob_dd != '' ? $request->boy_dob_dd : 0;
      $astrologerOffline->boy_dob_mm = $request->boy_dob_mm != '' ? $request->boy_dob_mm : 0;
      $astrologerOffline->boy_dob_yy = $request->boy_dob_yy != '' ? $request->boy_dob_yy : 0;
      $astrologerOffline->boy_birth_time = $request->boy_birth_time;
      $astrologerOffline->boy_birth_place = $request->boy_birth_place;
      $astrologerOffline->girl_name = $request->girl_name;
      $astrologerOffline->girl_dob_dd = $request->girl_dob_dd != '' ? $request->girl_dob_dd : 0;
      $astrologerOffline->girl_dob_mm = $request->girl_dob_mm != '' ? $request->girl_dob_mm : 0;
      $astrologerOffline->girl_dob_yy = $request->girl_dob_yy != '' ? $request->girl_dob_yy : 0;
      $astrologerOffline->girl_birth_time = $request->girl_birth_time;
      $astrologerOffline->girl_birth_place = $request->girl_birth_place;
      $astrologerOffline->services_total_amount = $amount;
      $astrologerOffline->services = json_encode($services);
      $astrologerOffline->save();

      if($user->birth_time == '') {
        $user->birth_time = $request->birth_time;
        $user->save();
      }

      if($user->birth_place == '') {
        $user->birth_place = $request->birth_place;
        $user->save();
      }
      return response()->json(['message'=>'Offline Report submitted successfully'], 200);
    }

    public function schduleAppointment(Request $request)
    {
      // dd($request->all());
        $data = $request->all();
        //dd($data);
        $this->validate($request, [
    'ampm' => 'required',
    'date' => 'required',
    'hr' => 'required',
    'message' => 'required',
    'mm' => 'required',
            'package' => 'required',
            'timeslot' => 'required',
    ]);
        $package = AstrologerAppointmentPackage::find($request->package);
        $user = auth()->guard('web')->user();
        $astrologer = Astrologer::where('status', 1)
        ->where('is_verified', 1)
        ->where('is_delete_requested', 0)
        ->where('slug', $request->slug)->first();
        $newAppointment  = new AstrologerAppointment();
        $newAppointment->package_id = $package->id;
        $newAppointment->package= $package->name;
        $newAppointment->package_price = $package->price;
        $newAppointment->package_minutes =  $package->minutes;
        $newAppointment->call_to_date = $request->date;
        $newAppointment->call_to_time = date('H:i:00', strtotime($request->hr.':'.$request->mm.' '.$request->ampm));
        $newAppointment->appointment_time = $request->timeslot;
        $newAppointment->message = $request->message;
        $newAppointment->from_user_id = $user->id;
        $newAppointment->to_astrologer_id = $astrologer->id;
        $newAppointment->status = 0;
        $newAppointment->comments = '';
        $newAppointment->web_commission = 0;
        $newAppointment->commission_amount = 0;
        $newAppointment->astrologer_amount = 0;
        $newAppointment->call_history_id = 0;
        $newAppointment->call_status = 0;
        $newAppointment->save();

        return response()->json(['message'=>'Appointment submitted successfully'], 200);
    }

    public function review(Request $request) {
      $astrologer = Astrologer::where('status', 1)
      ->where('is_verified', 1)
      ->where('is_delete_requested', 0)
      ->where('slug', $request->slug)->first();
      if(!$astrologer) return abort(404);
      return view(themeView().'astrologers.reviewform', compact('astrologer'));
    }

    public function datachanges(Request $request) {
        $data = $request->all();
       //return $data;
        foreach ($data as $key => $value) {
            //echo "Key: " . $key . "<br>";
           // echo "Value: " . $value . "<br><br>";
           if ($key === 'formattedDate') {
            $dayMap = [
                'Monday'    => 1,
                'Tuesday'   => 2,
                'Wednesday' => 3,
                'Thursday'  => 4,
                'Friday'    => 5,
                'Saturday'  => 6,
                'Sunday'    => 0,
            ];

            $dateString = $value;
            $dateString = str_replace(['_', ','], [' ', ''], $dateString);
            // Extract the name of the day
            $dayName = explode(' ', $dateString)[0];

            // Map the day name to the corresponding week_day value
            $weekDayValue = $dayMap[$dayName];

            if ($weekDayValue !== null) {
                $records = AstrologerTimeTable::where('week_day', $weekDayValue)->get(['from_time', 'to_time']);

                if ($records->isNotEmpty()) {
                    foreach ($records as $key => $value) {
                       $startTime = $value->from_time;
                       $endTime = $value->to_time;
                       $intervals = [];
                       $current = strtotime($startTime);
                       $end = strtotime($endTime);


                       while ($current <= $end) {
                        $intervals[] = date('h:i A', $current);
                        $current = strtotime('+1 hour', $current);
                    }
                       return $intervals;
                    }
                } else {
                    return response()->json(['message' => 'No records found for ' . $dayName], 404);
                }
            } else {
                return response()->json(['message' => 'Invalid day name provided.'], 400);
            }
        }

            if($key === 'selectedTimeSlot'){
                $timeString = $value;
                $timeString = str_replace(['-', ','], [' ', ''], $timeString);
                $slottime = explode(' ', $timeString)[0];
               // echo $slottime;
            }
        }
    }

    public function callscheduled(Request $request){
      // status = booked, joined, done, cancel, reschedule, cancel
      // video = 1,chat = 2, call = 3
      // hide = 0, online = 1, offline = 2, busy = 3
      // now, later
      // AstrologerAppointment
      // if call -> CallHistory
      $this->validate($request, [
        'astrologer_id' => 'required|gt:0',
        'consultation_mode' => 'required|in:call,chat,video', // call, chat or video
        'consultation_type' => 'required|in:now,later', // now or later
        'time_slot' => 'required|in:5,15,30', // 5, 15 and 30
        'name' => 'required|string|max:255',
        // 'email' => 'required|email|max:255',
        // 'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
        'gender' => 'required',
        'birth_date' => 'required|before_or_equal:today',
        'birth_time' => 'required',
        'birth_place' => 'required|max:255',
      ]);
      // $astrologerServicesIds = AstrologerService::where('astrologer_id', $request->id)->pluck('service_id')->toArray();
      // $serviceDocument = Service::whereIn('id', $astrologerServicesIds)->where('document_required', 1)->first();
      // if($serviceDocument){
      //   $request->validate([
      //     'document' => 'required|file|mimes:jpg,jpeg,pdf|max:2048'
      //   ], [
      //     'document.required' => $serviceDocument->document_label.' field is required.',
      //     'document.file' => $serviceDocument->document_label.' field is required.',
      //     'document.mimes' => $serviceDocument->document_label.' field format is jpg, jpeg, pdf.',
      //     'document.max' => $serviceDocument->document_label.' field max is size is 2MB.',
      //   ]);
      // }

      $message = "";

      $astrologer_id = $request->astrologer_id;
      $astrologer = Astrologer::find($astrologer_id);
      if(!$astrologer){
        $message = "Invalid request.";
        return response()->json([
          'success' => false,
          "message" => $message
        ], 422);
      }

      $freeAppointment = 0;
      $astrologerName = $astrologer->display_name;

      $user = auth()->guard('web')->user();
      $user_type = 'user';
      $redirectTo = "";
      $status = "";
      $consultation_mode = $request->consultation_mode;

      if(!$user){
        Session::put('redirect_from_login', route('astrologers.publicview', [$astrologer->type, $astrologer->slug, "type" => $consultation_mode]));
        $message = "Please Login First.";
        $redirectTo = route('login');
        $status = "login";
        return response()->json([
          'success' => false,
          "message" => $message,
          'redirectTo' => $redirectTo,
          'status' => $status,
        ], 402);
      }elseif(!$user->phone){
        Session::put('redirect_from_login', route('astrologers.publicview', [$astrologer->type, $astrologer->slug, "type" => $consultation_mode]));
        Session::flash('message', "Please fill your phone number.");
        $redirectTo = route('user.profile', ['tab' => "change-phone-number"]);
        $status = "profile";
        return response()->json([
          'success' => false,
          'redirectTo' => $redirectTo,
          'status' => $status
        ], 422);
      }

      $updateUser = false;
      if(!$user->birth_date){
        $updateUser = true;
        $user->birth_date = $request->birth_date;
      }
       if(!$user->birth_time){
         $updateUser = true;
         $user->birth_time = $request->birth_time;
       }
       if(!$user->birth_place){
         $updateUser = true;
         $user->birth_place = $request->birth_place;
       }
       if($updateUser){
         $user->save();
       }
       if($consultation_mode == "chat"){
         $freeAppointment = $astrologer->first_chat_free;
         if($freeAppointment == 1){
           $userAstrologerAppointment = AstrologerAppointment::where("package", "2")->where("user_id", $user->id)->where("status", "done")->first();
           if($userAstrologerAppointment){
             $freeAppointment = 0;
           }
           if($freeAppointment == 1){
             $userAstrologerAppointment = AiAstrologerAppointment::where("user_id", $user->id)->where("paid", 1)->first();
             if($userAstrologerAppointment){
               $freeAppointment = 0;
             }
           }
         }
       }

       $current_date = date('Y-m-d');
       $current_time = date('H:i');
       $day = date('w');
       $consultation_type = $request->consultation_type;
       $time_slot = $request->time_slot;
       $package = 0;
       $per_minute_price = 0;
       $totalCharges = 0;
       // $server_charges = Setting::where('key', 'server')->first(); //rs.
       // $totalCharges += ($server_charges && $server_charges->value ? (double) $server_charges->value : 0);
       // $sms_charges = Setting::where('key', 'sms')->first(); //rs.
       // $totalCharges += ($sms_charges && $sms_charges->value ? (double) $sms_charges->value : 0);
       // $email_charges = Setting::where('key', 'email_charges')->first(); //rs.
       // $totalCharges += ($email_charges && $email_charges->value ? (double) $email_charges->value : 0);
       // $maintainance_charge = Setting::where('key', 'maintainance_charge')->first(); //rs.
       // $totalCharges += ($maintainance_charge && $maintainance_charge->value ? (double) $maintainance_charge->value : 0);
       // $call_charges = null;
       // $chat_charges = null;
       // $video_charges = null;

       if($consultation_mode == 'chat'){
         $package = 2;
         $per_minute_price = $astrologer->chat_price;
         if($astrologer->sp_from_date && $astrologer->sp_from_date <= $current_date && $astrologer->sp_to_date >= $current_date){
           $per_minute_price = $astrologer->chat_special_price;
         }
         // $chat_charges = Setting::where('key', 'chat_charges')->first(); //rs.
         // $totalCharges += ($chat_charges && $chat_charges->value ? (double) $chat_charges->value : 0);
       }elseif($consultation_mode == 'call'){
         $package = 3;
         $per_minute_price = $astrologer->call_price;
         if($astrologer->sp_from_date && $astrologer->sp_from_date <= $current_date && $astrologer->sp_to_date >= $current_date){
           $per_minute_price = $astrologer->call_special_price;
         }
         // $call_charges = Setting::where('key', 'call_charges')->first(); //rs.
         // $totalCharges += ($call_charges && $call_charges->value ? (double) $call_charges->value : 0);
       }elseif($consultation_mode == 'video'){
         $package = 1;
         $per_minute_price = $astrologer->price;
         if($astrologer->sp_from_date && $astrologer->sp_from_date <= $current_date && $astrologer->sp_to_date >= $current_date){
           $per_minute_price = $astrologer->special_price;
         }
         // $video_charges = Setting::where('key', 'video_charges')->first(); //rs.
         // $totalCharges += ($video_charges && $video_charges->value ? (double) $video_charges->value : 0);
       }

       $checkcallstatus= $astrologer->call; // 0:hide, 1:online ,2:offline ,3:busy
       $checkchatstatus= $astrologer->chat; // 0:hide, 1:online ,2:offline ,3:busy
       $checkvideostatus= $astrologer->video; // 0:hide, 1:online ,2:offline ,3:busy

       $total_price = (double) ($per_minute_price*$time_slot);

       // $payment_gateway = Setting::where('key', 'payment_gateway')->first(); //%
       // $payment_gateway = ($payment_gateway && $payment_gateway->value ? (double) $payment_gateway->value : 0);
       // if($payment_gateway){
       //  $totalCharges += ($total_price/100)*$payment_gateway;
       // }

       // if appointment is not free
       if($freeAppointment == 0){
         // Check wallet balance
         if($user->wallet < $total_price){
           Session::put('redirect_from_login', route('astrologers.publicview', [$astrologer->type, $astrologer->slug, "type" => $consultation_mode]));
           $redirectTo = route('user.recharge');
           $mode=$consultation_mode=='video'?$consultation_mode.' call':$consultation_mode;
           $message = "Minimum balance of {$time_slot} minutes (₹ {$total_price}) is required to start {$mode} with {$astrologer->display_name}";
           $status = "recharge";
           return response()->json([
             'success' => false,
             "message" => $message,
             "redirectTo" => $redirectTo,
             "status" => $status,
           ], 402);
         }
       }

       $booking_date = $current_date;
       $booking_time = $current_time;
       $max_end_time_seconds = strtotime($booking_date.' 23:50:00');
       $end_time_seconds = strtotime($booking_date.' '.$booking_time.'+30minutes');

       if($per_minute_price > 0){
         $end_time_seconds = strtotime($booking_date.' '.$booking_time.'+'.floor($user->wallet/$per_minute_price).'minutes');
         if($end_time_seconds > $max_end_time_seconds){
           $end_time_seconds = $max_end_time_seconds;
         }
       }

       $astrologerAppointment = AstrologerAppointment::where('astrologer_id', $astrologer_id)->where('scheduled_date', $current_date)->where('scheduled_time', '>',  $current_time)->first();

       if($astrologerAppointment && ($end_time_seconds > strtotime($astrologerAppointment->scheduled_time." -".$time_slot."minutes"))){
         $end_time_seconds = strtotime($booking_date.' '.$astrologerAppointment->scheduled_time." -".$time_slot."minutes");
       }elseif($astrologerTimeTable = AstrologerTimeTable::where('astrologer_id', $astrologer_id)->where('week_day', date('w'))->first()){
         $today_to_time = strtotime($astrologerTimeTable->to_time." -".$time_slot."minutes");
         if($today_to_time > time() && $end_time_seconds > $today_to_time){
           $end_time_seconds = $today_to_time;
         }
       }

       $end_time = date('H:i', $end_time_seconds);
       $max_time = date('H:i', strtotime($booking_date.' '.$end_time." +5minutes"));
       $scheduled_for = 0;

       if($consultation_type == 'now'){
         $scheduled_for = 0;
         if($checkcallstatus == 3 || $checkchatstatus == 3 || $checkvideostatus == 3){
           $message = "$astrologerName is bussy.";
           $status = "bussy";
           return response()->json([
             'success' => false,
             "message" => $message,
             "status" => $status
           ], 422);
         }
         if($consultation_mode == 'chat'){
           if($checkchatstatus == 0 || $checkchatstatus == 2){
             $message = "$astrologerName is offline in $consultation_mode mode.";
             $status = "offline";
             return response()->json([
               'success' => false,
               "message" => $message,
               "status" => $status
             ], 422);
           }
         }elseif($consultation_mode == 'call'){
           if($checkcallstatus == 0 || $checkcallstatus == 2){
             $message = "$astrologerName is offline in $consultation_mode mode.";
             $status = "offline";
             return response()->json([
               'success' => false,
               "message" => $message,
               "status" => $status
             ], 422);
           }
         }elseif($consultation_mode == 'video'){
           if($checkvideostatus == 0 || $checkvideostatus == 2) {
             $message = "$astrologerName is offline in $consultation_mode mode.";
             $status = "offline";
             return response()->json([
               'success' => false,
               "message" => $message,
               "status" => $status
             ], 422);
           }
         }
         if($astrologer->withInThirtyMinutes()){
           $message = "$astrologerName is bussy.";
           $status = "offline";
           return response()->json([
             'success' => false,
             "message" => $message,
             "status" => $status
           ], 422);
         }
         $end_time = date('H:i', strtotime($booking_date.' '.$booking_time." +1minutes"));
       }elseif($consultation_type == 'later'){
         $redirectTo = route("thank.you"); // redirct to thank you screen;
         $scheduled_for = 1;
         $booking_date = date('Y-m-d', strtotime($request->scheduled_date));
         $booking_time = date('H:i', strtotime($booking_date.' '.$request->scheduled_time_slot));
         $end_time = date('H:i', strtotime($booking_date.' '.$booking_time." +".$time_slot."minutes"));
         $max_time = date('H:i', strtotime($booking_date.' '.$end_time." +5minutes"));
       }

       if($astrologer->currentAppointment()){
         $message = "You are between in a consultation.";
         $status = "inconsultation";
         return response()->json([
           'success' => false,
           "message" => $message,
           "status" => $status,
         ], 422);
       }

       $max_time = date('H:i', strtotime($booking_date.' '.$max_time." -1minutes"));

       $existingAppointment = AstrologerAppointment::where('astrologer_id', $astrologer_id)
       ->where('scheduled_date', $booking_date)
       ->whereNotIn('status', ['pending', 'cancel'])
       ->where(function ($query) use($booking_time, $max_time) {
           $query->whereBetween('scheduled_time', [$booking_time, $max_time])
           ->orWhereBetween('max_time', [$booking_time, $max_time])
           ->where(function ($q) use($booking_time, $max_time) {
             $q->where('scheduled_time', '<', $booking_time)
             ->where('max_time', '>', $max_time);
           });
         })
       ->first();

       if($existingAppointment){
         $message = "Slot is already booked.";
         if($consultation_type == 'now'){
           $message = "$astrologerName is bussy.";
         }
         $status = "notavailable";
         return response()->json([
           'success' => false,
           "message" => $message,
           "status" => $status,
         ], 422);
       }

       $max_time = date('H:i', strtotime($booking_date.' '.$max_time." +1minutes"));

       $payoutPackage = PayoutPackage::find($astrologer->payout_package_id);
       if(!$payoutPackage){
         $message = "Astrologer is not verified.";
         $status = "invalidastrologer";
         return response()->json([
           'success' => false,
           "message" => $message,
           "status" => $status,
         ], 422);
       }

       // if appointment is free
       if($freeAppointment == 1){
         $end_time_seconds = strtotime($booking_date.' '.$end_time."+5minutes");
         if($end_time_seconds > $max_end_time_seconds){
           $end_time_seconds = $max_end_time_seconds;
         }
         $end_time = date('H:i', $end_time_seconds);
         $max_time = date('H:i', strtotime($booking_date.' '.$end_time." +5minutes"));
       }

       // Create an appointment
       $appointment = new AstrologerAppointment();
       $appointment->package = $package; // 1, 2, 3
       $appointment->scheduled_for = $scheduled_for; // 0, 1
       $appointment->timeslot = $time_slot; // 5, 15, 30
       $appointment->astrologer_id = $astrologer_id;
       $appointment->user_id = $user->id;
       $appointment->user_type = $user_type;
       $appointment->actualprice = $per_minute_price;
       $appointment->astrologer_amount_ratio = $payoutPackage->ratio;
       $appointment->total_price = 0;
       $appointment->astrologer_maintainance_charge = 0;
       $appointment->astrologer_amount = 0;
       $appointment->scheduled_date = $booking_date;
       $appointment->scheduled_time = $booking_time;
       $appointment->scheduled_end_time = $end_time;
       $appointment->max_time = $max_time;
       $appointment->name = $request->name;
       $appointment->email = $user->email;
       $appointment->mobile = $user->phone;
       $appointment->dateofbirth = $request->birth_date;
       $appointment->birthtime = $request->birth_time;
       $appointment->birthplace = $request->birth_place;
       $appointment->gender = '';

       if($gender = $request->gender){
         if($gender==1){
           $appointment->gender = 'male';
         }elseif($gender==2){
           $appointment->gender = 'female';
         }elseif($gender==3){
           $appointment->gender = 'other';
         }
       }

       $appointment->maritial_status = $request->maritial_status ?: '';
       $appointment->occupation = $request->occupation ?: '';
       $appointment->status = 'booked'; //booked, joined, done, pending, cancel
       $appointment->astrologer_status = 'booked';
       $appointment->user_status = 'booked';
       $appointment->service_type = $astrologer->type;
       $appointment->amount_job = 1;
       $appointment->_token = $request->userAgent();
       $appointment->comments = "Consultation booked successfully";
       $appointment->free = $freeAppointment;
       $appointment->save();

       $roomID = 'rAK'.$appointment->id.'u'.$user->id.'A'.$astrologer_id;
       $appointment->room_id = $roomID;
       $appointment->save();

       $message = "Consultation booked successfully";
       if($consultation_type=="now"){
         $message = "Connecting now to $astrologerName";
       }

       if($consultation_type == 'later'){
         $appointment->paid = 1;
         $appointment->total_price = $total_price;
         $appointment->astrologer_amount = (abs($total_price/100)*$payoutPackage->ratio);
         $appointment->save();

         $user->wallet -= $total_price;
         $user->save();

         // user wallet transition
         $walletTransition = new WalletTransition();
         $walletTransition->user_id = $user->id;
         $walletTransition->user_type = "user";
         $walletTransition->table_id = $appointment->id;
         $walletTransition->table_name = "astrologer_appointments";
         $walletTransition->action = 0; //debit
         $walletTransition->status = 1; //success
         $walletTransition->remark = "Booked Astrologer Consultation";
         $walletTransition->invoice = 0;
         $walletTransition->amount = $total_price;
         $walletTransition->save();
       }

       // if($package == 3){
       //   // call
       //   $callhistory = new CallHistory();
       //   $callhistory->user_id = $user->id;
       //   $callhistory->astrologer_id = $astrologer->id;
       //   // $callhistory->from_number  = '8929270713';
       //   $callhistory->from_number  = $astrologer->phone;
       //   $callhistory->to_number = $request->phone;
       //   $callhistory->start_time = $booking_time;
       //   $callhistory->end_time = $end_time;
       //   $callhistory->per_minute_charge = $per_minute_price;
       //   $callhistory->astrologer_amount = $total_price;
       //   $callhistory->call_hangup_time = $booking_time;
       //   $callhistory->is_appointment_call = $request->scheduled_for; // 0:now, 1:later
       //   $callhistory->appointment_id = $appointment->id;
       //   if($request->scheduled_for == 0){
       //     $callhistory->is_initiated = 1;
       //     $callhistory->initiated_time = 1;
       //   }
       //   $callhistory->save();
       //   if($request->scheduled_for==0){
       //     $newRequest = new Request([
       //       'call_history_id'=>$callhistory->id,
       //     ]);
       //     $exotelService = new ExotelService();
       //     $exotelController = new ExotelController($exotelService);
       //     $exotelController->clickToCall($newRequest);
       //   }
       // }

       // send consultation booked mail to user and admin
       ConsultationJob::dispatch($appointment);

       // send consultation booked mail to astrologer
       AstrologerConsultationJob::dispatch($appointment);

       if($consultation_type == 'later'){
         // user normal notification
         $notification['user_id'] = $user->id;
         $notification['astrologer_id'] = 0;
         $notification['message'] = 'Your consultation is booked successfully. Thank You!';
         $notification['image_id'] = 0;
         $notification['link'] = route('user.consultations');
         $notification['app_link'] = "consultation_history";
         $notification['for'] = 1;
         $notification['on'] = 0;
         $notification['status'] = 1;
         $notificationController = new NotificationController();
         $notificationController->create($notification);

         // user push notification
         $title="Consultation";
         $body=$notification['message'];
         $dataArray = [
           'type' => $notification['app_link']
         ];
         $pushNotificationController = new PushNotificationController();
         $pushNotificationController->sendNotification($user->fcm_token, $title, $body, $dataArray);
       }

       // astrologer normal notification
       $notification['user_id'] = 0;
       $notification['astrologer_id'] = $astrologer_id;
       $notification['message'] = 'A new consultation has been booked with you. Please check your schedule.';
       $notification['image_id'] = 0;
       $notification['link'] = route('astrologer.consultations');
       $notification['app_link'] = "consultation_history";
       $notification['for'] = 2;
       $notification['on'] = 0;
       $notification['status'] = 1;
       $notificationController = new NotificationController();
       $notificationController->create($notification);

       // astrologer push notification
       $title="Consultation";
       $body=$notification['message'];
       $dataArray = [
         'type' => $notification['app_link']
       ];
       $pushNotificationController = new PushNotificationController();
       $pushNotificationController->sendNotification($astrologer->fcm_token, $title, $body, $dataArray);

       $redirectTo = route("user.startchat", [$roomID]); // redirct to chat screen;
       if($consultation_mode == 'call'){
         $redirectTo = route("user.startphonecall", [$roomID]); // redirct to call screen;
       }elseif($consultation_mode == 'video'){
         $redirectTo = route("user.startvideocall", [$roomID]); // redirct to video screen;
       }
       $status = "booked";
       return response()->json([
         'success' => true,
         'message' => $message,
         'redirectTo' => $redirectTo,
         'status' => $status,
       ], 200);
     }

     public function bookAiAstrologerAppointment(Request $request){
       $request->validate([
        'astrologer_id' => 'required|gt:0',
          // 'consultation_mode' => 'required|in:call,chat,video', // call, chat or video
          // 'consultation_type' => 'required|in:now,later', // now or later
          // 'time_slot' => 'required|in:5,15,30', // 5, 15 and 30
          'name' => 'required|string|max:255',
          // 'email' => 'required|email|max:255',
          // 'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
          'gender' => 'required',
          'birth_date' => 'required|before_or_equal:today',
          'birth_time' => 'required',
          'birth_place' => 'required|max:255',
        ]);

        $message = "";

        $astrologer_id = $request->astrologer_id;
        $astrologer = Astrologer::find($astrologer_id);
        if(!$astrologer){
          $message = "Invalid request.";
          $status = "invalidrequest";
          return response()->json([
            'success' => false,
            "message" => $message,
            "status" => $status,
          ], 422);
        }

        $freeAppointment = 0;
        $astrologerName = $astrologer->display_name;

        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $redirectTo = "";
        $consultation_mode = $request->consultation_mode ?: 'chat';

        if(!$user){
          Session::put('redirect_from_login', route('astrologers.publicview', [$astrologer->type, $astrologer->slug, "type" => $consultation_mode]));
          $message = "Please Login First.";
          $redirectTo = route('login');
          $status = "login";
          return response()->json([
            'success' => false,
            "message" => $message,
            'redirectTo' => $redirectTo,
            'status' => $status,
          ], 402);
        }elseif(!$user->phone){
          Session::put('redirect_from_login', route('astrologers.publicview', [$astrologer->type, $astrologer->slug, "type" => $consultation_mode]));
          Session::flash('message', "Please fill your phone number.");
          $redirectTo = route('user.profile', ['tab' => "change-phone-number"]);
          $status = "profile";
          return response()->json([
            'success' => false,
            'redirectTo' => $redirectTo,
            'status' => $status,
          ], 422);
        }

        $updateUser = false;
        if(!$user->birth_date){
          $updateUser = true;
          $user->birth_date = $request->birth_date;
        }
        if(!$user->birth_time){
          $updateUser = true;
          $user->birth_time = $request->birth_time;
        }
        if(!$user->birth_place){
          $updateUser = true;
          $user->birth_place = $request->birth_place;
        }
        if($updateUser){
          $user->save();
        }

        if($consultation_mode == "chat"){
          $freeAppointment = $astrologer->first_chat_free;
          if($freeAppointment == 1){
            $userAstrologerAppointment = AstrologerAppointment::where("package", "2")->where("user_id", $user->id)->where("status", "done")->first();
            if($userAstrologerAppointment){
              $freeAppointment = 0;
            }
            if($freeAppointment == 1){
              $userAstrologerAppointment = AiAstrologerAppointment::where("user_id", $user->id)->where("paid", 1)->first();
              if($userAstrologerAppointment){
                $freeAppointment = 0;
              }
            }
          }
        }

        $currentAppointment = AiAstrologerAppointment::where('user_id', $user->id)->where('paid', 0)->first();

        if($currentAppointment){
          $message = "You're currently in a session with another astrologer. You can join a new one once the current session ends.";
          $status = "inconsultation";
          return response()->json([
            'success' => false,
            "message" => $message,
            "status" => $status,
          ], 402);
        }

        $formData['name']=$request->name;
        $formData['email']=$user->email;
        $formData['mobile']=$user->phone;
        $formData['birth_date']=$request->birth_date;
        $formData['birth_time']=$request->birth_time;
        $formData['birth_place']=$request->birth_place;
        $formData['gender']=$request->gender;
        $formData['maritial_status'] = $request->maritial_status ?: "";
        $formData['occupation'] = $request->occupation ?: "";

        $perMinutePrice = $astrologer->chat_price;

        if($astrologer->sp_from_date && $astrologer->sp_from_date <= date("Y-m-d") && $astrologer->sp_to_date >= date("Y-m-d")){
          $perMinutePrice = $astrologer->chat_special_price;
        }

        $timeSlot=5;
        $totalPrice = (double) $perMinutePrice*$timeSlot;

        // if appointment is not free
        if($freeAppointment == 0){
          // Check wallet balance
          if($user->wallet < $totalPrice){
            Session::put('redirect_from_login', route('astrologers.publicview', [$astrologer->type, $astrologer->slug, "type" => $consultation_mode]));
            $redirectTo = route('user.recharge');
            $mode=$consultation_mode=='video'?$consultation_mode.' call':$consultation_mode;
            $message = "Minimum balance of {$timeSlot} minutes (₹ {$totalPrice}) is required to start {$mode} with {$astrologer->display_name}";
            $status = "recharge";
            return response()->json([
              'success' => false,
              "message" => $message,
              "redirectTo" => $redirectTo,
              "status" => $status,
            ], 402);
          }
        }

        $startAt = $endAt = date('Y-m-d H:i:s');

        $aiAstrologerAppointment = new AiAstrologerAppointment();
        $aiAstrologerAppointment->mode=$consultation_mode;
        $aiAstrologerAppointment->astrologer_id=$astrologer_id;
        $aiAstrologerAppointment->user_id=$user->id;
        $aiAstrologerAppointment->form_data=json_encode($formData);
        $aiAstrologerAppointment->per_min_charge=$perMinutePrice;
        // $aiAstrologerAppointment->charges=0;
        $aiAstrologerAppointment->start_at=$startAt;
        $aiAstrologerAppointment->end_at=$endAt;
        $aiAstrologerAppointment->free=$freeAppointment;
        $aiAstrologerAppointment->save();

        // astrologer name
        $astrologerName=$astrologer->display_name;

        // return response
        $redirectTo = route("user.startaichat", [$aiAstrologerAppointment->id]); // redirct to chat screen;
        $message = "Connecting to the $astrologerName.";
        $status = "booked";
        return response()->json([
          'success' => true,
          'message' => $message,
          'redirectTo' => $redirectTo,
          'status' => $status,
        ], 200);
      }

public function sendNotification($user, $astrologer, $appointment, $appointment_mode, $link, $to){
  $notification = [];
  if($to == 'user'){
    $notification['user_id'] = $user->id;
    $notification['astrologer_id'] = 0;
    $notification['message'] = 'Thank You! Your '.$appointment_mode.' consultation is booked with '.$astrologer->display_name.'. Date & Time '.date('d-m-Y h:i a', strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time)).'.';
    $notification['for'] = 1;
  }else{
    $notification['user_id'] = 0;
    $notification['astrologer_id'] = $astrologer->id;
    $notification['message'] = 'Your '.$appointment_mode.' consultation is booked with '.$user->name.'. Date & Time '.date('d-m-Y h:i a', strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time)).'.';
    $notification['for'] = 2;
  }
  $notification['link'] = $link;
  $notificationController = new NotificationController();
  $notificationController->create($notification);
  return true;
}

    public function sendAppointmentEmails($user, $astrologer, $videoCallUrl){
        // Send email to user
        Mail::raw("Your appointment is scheduled. You can join the video call here: $videoCallUrl", function ($message) use ($user) {
            $message->to('testbyuser01@gmail.com')
                    ->subject('Your Appointment Scheduled');
        });

        // Send email to astrologer
        Mail::raw("A new appointment has been scheduled. You can join the video call here: $videoCallUrl", function ($message) use ($astrologer) {
            $message->to('testbyuser01@gmail.com')
                    ->subject('New Appointment Scheduled');
        });
    }


    public function date(Request $request){
      $timeIntervals15 = [];
      $timeIntervals30 = [];
      $astro_id = $request->astro_id;
      $date = $request->date;
      $month = $request->month;
      $year = $request->year;
      $currentDate = date('d');
      if(!$astro_id){
        return response()->json([
            'message' => 'Astrologer Required',
        ], 500);
      }
      $dayOfWeek = $request->dayOfWeek;
      $astrologer_data = Astrologer::find($astro_id);
      if(!$astrologer_data){
        return response()->json([
            'message' => 'Astrologer Required',
        ], 500);
      }
      $timeSlots = AstrologerTimeTable::where('astrologer_id', $astro_id)->where('week_day', $dayOfWeek)->select('from_time', 'to_time')->get();
      $formattedToSlots = $timeSlots->pluck('to_time')->map(function($time) {
          return date('h:i A', strtotime($time));
      });

      $formattedFromSlots = $timeSlots->pluck('from_time')->map(function($time) {
          return date('h:i A', strtotime($time));
      });
      $slot_time = $request->slot?:15;

      $formattedFromSlots->each(function ($fromTime, $key) use ($slot_time, $formattedToSlots, &$timeIntervals15, &$timeIntervals30, $date, $currentDate) {
          $toTime = $formattedToSlots[$key];
          $fromDateTime = new \DateTime($fromTime);
          $toDateTime = new \DateTime($toTime);
          $interval = $fromDateTime->diff($toDateTime);
          $totalMinutes = ($interval->h * 60) + $interval->i;
          for($minutes = 0; $minutes < $totalMinutes; $minutes += $slot_time){
              // Add the 15-minute interval to fromDateTime
              $timeSlot15 = clone $fromDateTime;
              $timeSlot15->add(new \DateInterval('PT' . $minutes . 'M'));
              if($date == $currentDate){
                if(strtotime('now') < strtotime($timeSlot15->format('h:i A'))){
                  $timeIntervals15[$timeSlot15->format('H:i:00')] = $timeSlot15->format('h:i A');
                }
              }else{
                $timeIntervals15[$timeSlot15->format('H:i:00')] = $timeSlot15->format('h:i A');
              }
          }
      });

      return response()->json([
          'timeIntervals15' => $timeIntervals15,
          'timeIntervals30' => $timeIntervals15
      ]);
    }
    public function paymentresponse(Request $request){
        return true;
    }

    public function search(Request $request){
        $title=$request->title;
        $search_result = LocationHistory::select('id','state')->where('place','LIKE',"$title%")->orderBy('place', 'ASC')->get();
        if($search_result->count()==0){
          $url = "https://api.vedicastroapi.com/v3-json/utilities/geo-search";
          $apiKey = fromSetting('astro_api_key');
          $url = "$url?api_key=$apiKey&city=$title";
          $response = Http::get($url);
          $statusCode=$response->status();
          $jsonResponse=$response->json();
          $apiLog=new ApiLog();
          $apiLog->table_name="location_histories";
          $apiLog->table_id=0;
          $apiLog->name="location_histories";
          $apiLog->request=$url;
          $apiLog->response=$response;
          $apiLog->api_status=0;
          $apiLog->save();
          if($jsonResponse["status"]===200){
            $data=$jsonResponse["response"];
            foreach($data as $key=>$item){
              $place=$item["name"];
              $country=$item["country_name"];
              $lh = LocationHistory::where('place',$place)->where('country',$country)->first();
              if(!$lh){
                $coordinates=$item["coordinates"];
                $lh = new LocationHistory();
                $lh->place = $place;
                $lh->state = $item["full_name"];
                $lh->country = $country;
                $lh->latDeg = $coordinates[0];
                $lh->longDeg = $coordinates[1];
                $lh->save();
              }
            }
            $apiLog->table_id=$lh?$lh->id:0;
            $apiLog->api_status=1;
            $apiLog->save();
            if(isset($jsonResponse["response"])){
              $data=$jsonResponse["response"];
            }
            $search_result = LocationHistory::select('id','state')->where('place','LIKE',"$title%")->orderBy('place', 'ASC')->get();
          }
        }
        return response()->json($search_result);
      }

      public function searchdata(Request $request){
        $data = $request->input('title');
        $astrologers = Astrologer::where('display_name', 'LIKE', "%$data%")->where('type', 'Astrologer')
        ->where('is_verified', 1)
        ->where('status', 1)
        ->where('is_delete_requested', 0)
        ->get()->map(function($astrologer){
          $image=$astrologer->image;
          return [
            'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
            'name' => $astrologer->display_name,
            'url' => route("astrologers.publicview", [$astrologer->type, $astrologer->slug])
          ];
        });
        $aiAstrologers = Astrologer::where('display_name', 'LIKE', "%$data%")->where('type', 'AI')
        ->where('is_verified', 1)
        ->where('status', 1)
        ->where('is_delete_requested', 0)
        ->get()->map(function($astrologer){
          $image=$astrologer->image;
          return [
            'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
            'name' => $astrologer->display_name,
            'url' => route("astrologers.publicview", [$astrologer->type, $astrologer->slug])
          ];
        });
        // $courses = Course::where('name', 'LIKE', "%$data%")->where('status', 1)->get()->map(function($course){
        //   return [
        //     'name' => $course->name,
        //     'url' => $app_url . 'courses/' . $course->slug
        //   ];
        // });

        $products = Product::where('title', 'LIKE', "%$data%")
        ->where('type', 1)
        ->where('status', 1)
        ->where('parent_id',0)
        ->get()
        ->map(function($product){
          $image=$product->image;
          $category = $product->category;
          if($category){
            return [
              'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
              'name' => $product->title,
              'url' => route("product.detail", ["Product", $category->slug, $product->slug])
            ];
          }
        });

        $poojas = Product::where('title', 'LIKE', "%$data%")
        ->where('type', 2)
        ->where('status', 1)
        ->where('parent_id', 0)
        ->get()
        ->map(function($product){
          $image=$product->image;
          $category = $product->category;
          if($category){
            return [
              'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
              'name' => $product->title,
              'url' => route("product.detail", ["Pooja", $category->slug, $product->slug])
            ];
          }
        });

        $services = Product::where('title', 'LIKE', "%$data%")
        ->where('type', 3)
        ->where('status', 1)
        ->where('parent_id',0)
        ->get()
        ->map(function($product){
          $image=$product->image;
          $category = $product->category;
          if($category){
            return [
              'image' => $image?$image->image():asset("assets/img/icons/vastu.png"),
              'name' => $product->title,
              'url' => route("product.detail", ["Service", $category->slug, $product->slug])
            ];
          }
        });

        // $blogs = Article::where('title', 'LIKE', "%$data%")->where('status', 1)->get()->map(function($blog){
        //   return [
        //     'name' => $blog->title,
        //     'url' => route('blog.post', [$blog->category->slug, $blog->slug]),
        //   ];
        // });

        // $vastus = Vastu::where('name', 'LIKE', "%$data%")->where('status', 1)->get()->map(function($vastu) use ($app_url) {
        //   return [
        //     'name' => $vastu->name,
        //     'url' => $vastu->type=='page'? url("$vastu->slug") : url("vastu-services/$vastu->slug"),
        //   ];
        // });

        // $cms_two = NgSoftware::where('title', 'LIKE', "%$data%")->get()->map(function($cms2) use ($app_url) {
        //   return [
        //     'name' => $cms2->title,
        //     'url' => url("$cms2->slug"),
        //   ];
        // });

        $result = [
          'astrologers' => $astrologers,
          'aiastrologers' => $aiAstrologers,
          // 'courses' => $courses,
          'products' => $products->filter()->values(),
          'poojas' => $poojas->filter()->values(),
          'services' => $services->filter()->values(),
          // 'blogs' => $blogs,
          // 'vastus' => $vastus,
          // 'cms_two' => $cms_two,
        ];
        return response()->json($result);
      }

    public function astrologerWishlists(Request $request){
      $nav = 'wishlist';
      $user = auth()->guard('astrologer')->user();
      $wishlists = Wishlist::where('user_id',$user->id)->where('user_type', 'astrologer')->get();
      return view(themeView().'astrologers.wishlist', compact('nav', 'user', 'wishlists'));
    }

    public function purchaseitem(Request $request){
    $user = auth()->guard('astrologer')->user();
      $orders = Order::where('user_id', $user->id)->where('user_type', 'astrologer')->get();
      return view(themeView() . 'astrologers.product', compact('orders', 'user'));
    }

    public function purchasecourses(Request $request){
    $user = auth()->guard('astrologer')->user();
      $orders = Order::where('user_id', $user->id)->where('user_type','astrologer')->get();
      return view(themeView().'astrologers.course', compact('orders','user'));
    }

}
