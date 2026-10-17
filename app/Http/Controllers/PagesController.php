<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Cms;
use DB;
use App\Model\Banner;
use App\Model\ZodiacSign;
use App\Model\Festival;
use App\Model\Planet;
use App\Model\{Vastu,Course,Astrologer,ProductCategory, UserRechargeHistory, AstrologerAppointment, CallHistory, Panchang};
use App\Model\ContactEnquiry;
use App\Model\Article;
use App\Model\Testimonial;
use App\Model\ConsultationCounter;
use App\Model\VideoTestimonial;
use App\{CourseCategory,Instructor,Product};
use Session;
use App\Model\NewsletterSubscriber;
use App\Model\VastuProcedure;
use App\Model\NgSoftware;
use App\Model\DemoVideo;
use App\Model\TemplateFaq;
use App\Model\EnquiryCmsTwo;
use App\Model\EnquiryAstrology;
use App\Model\Tag;
use App\Model\OnPage;
use File;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Api;
use App\Jobs\SendSms;
use App\Jobs\{RechargeInvoiceMailJob, RechargeFailedJob, ConsultationJob, ConsultationFailedJob, AstrologerConsultationJob};
use Illuminate\Support\Facades\Notification;
use App\Notifications\EnquiryNotification;
use App\Http\Controllers\VastuController;
use Illuminate\Support\Facades\Cache;
use Phattarachai\LaravelMobileDetect\Agent;
use App\Model\Notification as NotificationModel;
use App\{User,LocationHistory, OrderItem};
use App\Model\{UserNotification,ApiLog,HoroscopeDaily,HoroscopeWeekly,HoroscopeYearly,HoroscopeMonthly,PlanetDetail,DivisionChart,AkvargaChart,Dasha,KpPlanet,Yoga,Dosh,KundaliEnquiry, KundaliMatchingEnquiry, KundaliMatching};
use Illuminate\Support\Facades\Http;
use App\Services\{Meta};


class PagesController extends Controller
{
    /**
     * Home Page
     */
    // new homepage
    public function testing(Request $request){
      $download_url="https://reports.divinestore.net/generate/118531760354476846892-15";
      $fileName = time().'.pdf';
      $filePath = public_path('pdfs/divine/1/'.$fileName);

      if(!file_exists($filePath)){
        $filePath = public_path('pdfs');
        if(!file_exists($filePath)){
            mkdir(public_path('pdfs'), 0755, true);
        }

        $filePath = public_path('pdfs/divine');
        if(!file_exists($filePath)){
            mkdir(public_path('pdfs/divine'), 0755, true);
        }

        $filePath = public_path('pdfs/divine/1');
        if(!file_exists($filePath)){
            mkdir(public_path('pdfs/divine/1'), 0755, true);
        }

        $filePath = public_path('pdfs/divine/1/'.$fileName);
        $ch = curl_init($download_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $pdfContent = curl_exec($ch);
        curl_close($ch);

        if ($pdfContent !== false) {
            file_put_contents($filePath, $pdfContent);
            return 'PDF downloaded successfully';
        }
        return 'done';
      }
    }

    public function index(Request $request)
    {
      if(auth()->guard('astrologer')->user()){
        return redirect()->route('astrologer.dashboard');
      }
      $today = date('Y-m-d');
      $nav="home";
      $banners = Cache::remember('home-banners', 60*60*24, function () use($today) {
        return Banner::where('status', 1)
        ->where('type','home')
        ->where(function($start_date) use ($today){
          $start_date->where('start_date', '<=', $today)
          ->orWhereNull('start_date')
          ->orWhere('start_date', '1970-01-01')
          ->orWhere('start_date', '0000-00-00');
        })
        ->where(function($end_date) use ($today){
          $end_date->where('end_date', '>=', $today)
          ->orWhereNull('end_date')
          ->orWhere('end_date', '1970-01-01')
          ->orWhere('end_date', '0000-00-00');
        })
        ->orderBy('order', 'asc')
        ->limit(10)
        ->get();
      });
      $limit = 10;
      // $aiAstrologers = Cache::remember('home-aiastrologers', 60*60*24, function () use($today, $limit) {
      //   return Astrologer::where('type', 'AI')
      //   ->where('status', 1)
      //   ->where('is_verified', 1)
      //   ->where('is_delete_requested',0)
      //   ->where(function($q) {
      //     $q->orWhere('call', '!=', 0);
      //     $q->orWhere('chat', '!=', 0);
      //     $q->orWhere('video', '!=', 0);
      //   })
      //   // ->has('timetable')
      //   // ->orderBy('order', 'asc')
      //   ->inRandomOrder()
      //   ->limit($limit)
      //   ->get();
      // });
      $aiAstrologers = Astrologer::where('type', 'AI')
      ->where('status', 1)
      ->where('is_verified', 1)
      ->where('is_delete_requested',0)
      ->where(function($q) {
        $q->orWhere('call', '!=', 0);
        $q->orWhere('chat', '!=', 0);
        $q->orWhere('video', '!=', 0);
      })
      ->with('badge')
      // ->has('timetable')
      // ->orderBy('order', 'asc')
      ->inRandomOrder()
      ->limit($limit)
      ->get();
      $astrologers = Cache::remember('home-astrologers', 60*60*24, function () use($today, $limit) {
        return Astrologer::where('type', 'Astrologer')
        ->where('status', 1)
        ->where('is_verified', 1)
        ->where('is_delete_requested',0)
        ->where(function($q) {
          $q->where('chat', '!=', 0);
          // $q->orWhere('call', '!=', 0);
          // $q->orWhere('chat', '!=', 0);
          // $q->orWhere('video', '!=', 0);
        })
        ->with('badge')
        // ->has('timetable')
        ->orderBy('order', 'asc')
        ->limit($limit)
        ->get();
      });
      $chatWithAstrologersId = $astrologers->pluck("id")->toArray();
      $talkAstrologers = Cache::remember('home-talk-to-astrologers', 60*60*24, function () use($today, $limit, $chatWithAstrologersId) {
        return Astrologer::where('type', 'Astrologer')
        ->whereNotIn('id', $chatWithAstrologersId)
        ->where('status', 1)
        ->where('is_verified', 1)
        ->where('is_delete_requested',0)
        ->where(function($q) {
          // $q->where('chat', '!=', 0);
          $q->orWhere('call', '!=', 0);
          // $q->orWhere('chat', '!=', 0);
          $q->orWhere('video', '!=', 0);
        })
        ->with('badge')
        // ->has('timetable')
        ->orderBy('order', 'asc')
        ->limit($limit)
        ->get();
      });
      $productCategories = Cache::remember('home-product-categories', 60*60*24, function () use($today, $limit) {
        $categriesId = Product::where('type', 1)
        ->where('status', 1)
        ->where('parent_id', 0)
        ->distinct("category_id")
        ->pluck("category_id")
        ->toArray();
        return ProductCategory::where("type", 1)
        ->where('status', 1)
        ->whereIn("id", $categriesId)
        ->orderBy('order','asc')
        ->limit($limit)
        ->get();
      });
      $serviceProducts = Cache::remember('home-service-products', 60*60*24, function () use($today, $limit) {
        return Product::where('type', 3)
        ->where('status', 1)
        ->where('parent_id', 0)
        ->orderBy('order', 'asc')
        ->limit($limit)
        ->get();
      });
      $poojaProducts = Cache::remember('home-pooja-products', 60*60*24, function () use($today, $limit) {
        return Product::where('type', 2)
        ->where('status', 1)
        ->where('parent_id', 0)
        ->where('expire_at', ">=", date("Y-m-d H:i:s"))
        ->orderBy('order', 'asc')
        ->limit($limit)
        ->get();
      });
      $articles = Cache::remember('home-blogs', 60*60*24, function () use($today) {
        return Article::where('status',1)->orderBy('id','DESC')->limit(4)->get();
      });
      $textTestimonials = Cache::remember('home-testimonials-text', 60*60*24, function () use($today) {
        return Testimonial::where('status', 1)->limit(8)->get();
      });
      $videoTestimonials = Cache::remember('home-testimonials-video', 60*60*24, function () use($today) {
        return VideoTestimonial::where('status', 1)->limit(8)->get();
      });

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_home");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "home_{$timestamp}";
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
        // $body["custom_data"]["content_ids"] = [""];
        // $body["custom_data"]["content_name"] = "";
        // $body["custom_data"]["content_type"] = "";
        // $body["custom_data"]["currency"] = "";
        // $body["custom_data"]["value"] = "0.00";
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_home", $user_type);
      }
      $breads=[];
      return view (themeView().'homepage.index', compact('nav',"breads",'talkAstrologers', 'banners', 'aiAstrologers', 'astrologers', 'productCategories', 'serviceProducts', 'poojaProducts', 'articles', 'textTestimonials', 'videoTestimonials'));
    }

    /**
     * Pages
     */
    public function pages(Request $request)
    {
        if(Vastu::where('status', 1)->where('slug', $request->slug)->first()){
          $vastuController = new VastuController();
          return $vastuController->index($request);
        }
        if($cms2 = NgSoftware::where('slug', $request->slug)->first()){
          if($cms2->page == 'astrology'){
            return $this->astrology($request);
          }elseif($cms2->page == 'software'){
            return $this->nakshtra($request);
          }
        }
        $pageTemplate = 'default';
        $slug = $request->slug;
        $page = Cms::where('slug', $slug)->where('status', 1)->first();
        if(!$page) abort(404);
        if($page) {
            $pageTemplate = $page->template;
        }
        $nav="default";
        $onPage = OnPage::where('table', 'template_faqs');
        $onPage1 = OnPage::where('table', 'testimonials');
        $onPage2 = OnPage::where('table', 'video_testimonials');
        if($slug == 'about-us'){
          $nav="aboutus";
          $onPage->where('page', 8);
          $onPage1->where('page', 8);
          $onPage2->where('page', 8);
        }else{
          $onPage->where('page', -1);
          $onPage1->where('page', -1);
          $onPage2->where('page', -1);
        }
        $onPage = $onPage->pluck('table_id')->toArray();
        $onPage1 = $onPage1->pluck('table_id')->toArray();
        $onPage2 = $onPage2->pluck('table_id')->toArray();
        $faqs = TemplateFaq::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(5)->get();
        $textTestimonials = Testimonial::whereIn('id', $onPage1)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
        $videoTestimonials = VideoTestimonial::whereIn('id', $onPage2)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();

        $breads[] = ['name' => $page->name,'is_last' => true, 'url'=>'#'];
        $procedures = VastuProcedure::where('type','astrology')->where('status',1)->get();

        $user = auth()->guard("web")->user();
        $user_type = "guest";
        $event = Session::get("view_content_{$slug}");
        if($event == null || ($event == "guest" && $user != null)){
          $timestamp = time();
          $body["event_name"] = "ViewContent";
          $body["event_time"] = $timestamp;
          $body["event_id"] = "{$slug}_{$timestamp}";
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
          Session::put("view_content_{$slug}", $user_type);
        }
        $breads[]=["name"=>"$page->name","url"=>route('pages', [$page->slug])];
        return view(themeView().'pages.'.$pageTemplate, compact('nav','page',"breads",'breads','procedures', 'faqs', 'textTestimonials', 'videoTestimonials'));
    }

    public function state(Request $request) {
        $country = $request->country_id;
        $state = DB::table('master_states')->where('country_id', $country)->pluck('name', 'id')->toArray();
        echo json_encode($state);
    }

    public function city(Request $request) {
        $state_id = $request->state_id;
        $city = DB::table('master_cities')->where('state_id', $state_id)->pluck('name', 'id')->toArray();;
        echo json_encode($city);
    }
    public function contactForm(Request $request){
      $nav = 'contactus';

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_{$nav}");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "{$nav}_{$timestamp}";
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
        Session::put("view_content_{$nav}", $user_type);
      }
      return view (themeView().'contactus.index', compact('nav'));
    }

    public function contact(Request $request)
    {
        $check = $request->checkb;
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
          ]);
          $newItem = new ContactEnquiry();
          $newItem->name = $request->name;
          $newItem->phone = $request->phone;
          $newItem->email = $request->email;
          $newItem->message = $request->message;
          $newItem->type = $request->type;
          $newItem->save();
          if($request->checkb){
            $news = new NewsletterSubscriber();
            $news->email = $request->email;
            $news->save();
            $news->sendEmail();
          }
        return response()->json(['message'=> 'Thankyou for contacting us!'],200);
    }



    public function newsletter(Request $request)
    {
        $breads[] = ['name' => 'Newsletter','is_last' => true, 'url'=>'#'];
        return view(themeView().'pages.newsletter', compact('breads'));
    }

    public function workWithUs(Request $request){
      $breads[] = ['name' => 'Work with us','is_last' => true, 'url'=>'#'];
      return view(themeView().'pages.workwithus', compact('breads'));
    }

    public function horoscopes(Request $request){
      $nav="horoscope";

      $today = date('Y-m-d');
      $banners = Banner::where('status', 1)
      ->where('type', "horoscope")
      ->where(function($start_date) use ($today){
        $start_date->where('start_date', '<=', $today)
        ->orWhereNull('start_date')
        ->orWhere('start_date', '1970-01-01')
        ->orWhere('start_date', '0000-00-00');
      })
      ->where(function($end_date) use ($today){
        $end_date->where('end_date', '>=', $today)
        ->orWhereNull('end_date')
        ->orWhere('end_date', '1970-01-01')
        ->orWhere('end_date', '0000-00-00');
      })
      ->orderBy('order', 'asc')
      ->limit(1)
      ->get();

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_horoscope");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "horoscope_{$timestamp}";
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
        Session::put("view_content_horoscope", $user_type);
      }
      $breads[]=["name"=>"Horoscope","url"=>route("horoscopes")];
      return view(themeView().'pages.horoscopes', compact("nav","breads", "banners"));
    }

    public function horoscopedetail(Request $request){
      $zodiac=$request->zodiac;

      $zodiac=zodiacSignNameHindiEnglish(strtolower($zodiac));
      $nav="horoscope";
      $zodiacSign=ZodiacSign::where("name",$zodiac)->first();
      if(!$zodiacSign) abort(404);
      $zodiac=$zodiacSign->id;
      $timeFrame=$request->timeFrame;
      $date=date("d/m/Y");
      $apiKey=fromSetting('astro_api_key');
      $date=date("d/m/Y", strtotime($date));
      $split=$request->split?:true;
      $type=$request->type?:"big";
      $lang=$request->lang?:"en";
      $horoscopeDate=date("d F Y");
      $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey";
      if($timeFrame=="today"){
        $date=date("d/m/Y");
        $horoscopeDate=date("d F Y");
        $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
        $date=date("Y-m-d");
        $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where('lang',$lang)->first();
      }elseif($timeFrame=="tomorrow"){
        $date=date("d/m/Y", strtotime('now+24hours'));
        $horoscopeDate=date("d F Y", strtotime('now+24hours'));
        $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
        $date=date("Y-m-d", strtotime('now+24hours'));
        $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where('lang',$lang)->first();
      }elseif($timeFrame=="yesterday"){
        $date=date("d/m/Y", strtotime('now-24hours'));
        $horoscopeDate=date("d F Y", strtotime('now-24hours'));
        $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
        $date=date("Y-m-d", strtotime('now-24hours'));
        $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where('lang',$lang)->first();
      }elseif($timeFrame=="weekly"){
        $week="thisweek";
        $horoscopeDate=date("d F Y", strtotime('monday this week'))." - ".date("d F Y", strtotime('sunday this week'));
        $fromDate=date("Y-m-d", strtotime('monday this week'));
        $toDate=date("Y-m-d", strtotime('sunday this week'));
        $url="https://api.vedicastroapi.com/v3-json/prediction/weekly-moon?api_key=$apiKey&week=$week&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
        $horoscope=HoroscopeWeekly::where("zodiac_sign_id",$zodiacSign->id)->where("from_date",$fromDate)->where("to_date",$toDate)->where('lang',$lang)->first();
      }elseif($timeFrame=="monthly"){
        $timeFrameUrl="monthly";
        $month = (int) date('m', strtotime('+1 month'));
        $year  = (int) date('Y', strtotime('+1 month'));
        $horoscopeDate = date("F Y", strtotime('+1 month'));
        $url = "https://api.vedicastroapi.com/v3-json/prediction/monthly?api_key=$apiKey&month=$month&year=$year&zodiac=$zodiac&lang=$lang";
        $horoscope=HoroscopeMonthly::where("zodiac_sign_id",$zodiacSign->id)->where("month_number",$month)->where('lang',$lang)->first();
      }elseif($timeFrame=="yearly"){
        $timeFrameUrl="yearly";
        $horoscopeDate=$year=date("Y");
        $url="https://api.vedicastroapi.com/v3-json/prediction/yearly?api_key=$apiKey&year=$year&zodiac=$zodiac&lang=$lang";
        $horoscope=HoroscopeYearly::where("zodiac_sign_id",$zodiacSign->id)->where("year",$year)->where('lang',$lang)->first();
      }else{
        abort(404);
      }
      $breads[] = ['name' => "$zodiac Horoscope",'is_last' => true, 'url'=>'#'];
      $data=[];
      if($horoscope){
        $jsonResponse=json_decode($horoscope->api_response,true);
        if(isset($jsonResponse["response"])){
          $data=$jsonResponse["response"];
        }
      }
      if(count($data)==0){
        $response=Http::withoutVerifying()->get($url);
        $statusCode=$response->status();
        $jsonResponse=$response->json();
        $apiLog=new ApiLog();
        $apiLog->table_name="";
        $apiLog->table_id=0;
        $apiLog->name="";
        $apiLog->request=$url;
        $apiLog->response=$response;
        $apiLog->api_status=0;
        $apiLog->save();
        if($jsonResponse["status"]===200){
          if(!$horoscope){
            if($timeFrame=="yearly"){
              $horoscope=new HoroscopeYearly();
              $horoscope->zodiac_sign_id=$zodiac;
              $horoscope->year=$year;
              $apiLog->table_name="horoscope_yearlies";
              $apiLog->name="horoscope_yearlies";
            }elseif($timeFrame=="monthly"){
              $horoscope=new HoroscopeMonthly();
              $horoscope->zodiac_sign_id=$zodiac;
              $apiLog->table_name="horoscope_monthlies";
              $apiLog->name="horoscope_monthlies";
            }elseif($timeFrame=="weekly"){
              $horoscope=new HoroscopeWeekly();
              $horoscope->zodiac_sign_id=$zodiac;
              $horoscope->from_date=$fromDate;
              $horoscope->to_date=$toDate;
              $apiLog->table_name="horoscope_weeklies";
              $apiLog->name="horoscope_weeklies";
            }else{
              $horoscope=new HoroscopeDaily();
              $horoscope->zodiac_sign_id=$zodiac;
              $horoscope->date=$date;
              $apiLog->table_name="horoscope_dailies";
              $apiLog->name="horoscope_dailies";
            }
          }
          $horoscope->lang=$lang;
          $horoscope->api_response=$response;
          $horoscope->save();
          $apiLog->table_id=$horoscope->id;
          $apiLog->api_status=1;
          $apiLog->save();
          if(isset($jsonResponse["response"])){
            $data=$jsonResponse["response"];
          }
        }
      }
      $viewFile=themeView().'pages.horoscopedetail';
      if($timeFrame=="yearly"){
        $dataa=$data;
        $data=[];
        $data["bot_response"]=$dataa["phase_1"];
        $data["bot_response"]["relationship"]["split_response"]=$dataa["phase_1"]["relationship"]["prediction"];
        $data["bot_response"]["health"]["split_response"]=$dataa["phase_1"]["health"]["prediction"];
        $data["bot_response"]["physique"]["split_response"]=$dataa["phase_1"]["physique"]["prediction"];
        $data["bot_response"]["career"]["split_response"]=$dataa["phase_1"]["career"]["prediction"];
        $data["bot_response"]["finances"]["split_response"]=$dataa["phase_1"]["finances"]["prediction"];
        $data["bot_response"]["family"]["split_response"]=$dataa["phase_1"]["family"]["prediction"];
        $data["bot_response"]["friends"]["split_response"]=$dataa["phase_1"]["friends"]["prediction"];
        $data["bot_response"]["total_score"]["split_response"]=$dataa["phase_1"]["status"]["prediction"];
      }

      $user = auth()->guard("web")->user();
      $user_type = "guest";
      $event = Session::get("view_content_horoscope_detail");
      if($event == null || ($event == "guest" && $user != null)){
        $timestamp = time();
        $body["event_name"] = "ViewContent";
        $body["event_time"] = $timestamp;
        $body["event_id"] = "horoscope_detail_{$timestamp}";
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
        $body["custom_data"]["content_id"] = $horoscope ? $horoscope->id : 0;
        $body["custom_data"]["content_name"] = $zodiacSign ? $zodiacSign->name : "0";
        $body["custom_data"]["content_type"] = "Panchang";
        $metaService = new Meta();
        $metaService->sendEvent($body);
        Session::put("view_content_horoscope_detail", $user_type);
      }
      $breads[]=["name"=>"Horoscope","url"=>route("horoscopes")];
      $breads[]=["name"=>"$zodiac Detail","url"=>route("horoscopedetail",[$zodiac,$timeFrame])];
      return view($viewFile, compact("nav","breads","zodiacSign","timeFrame","horoscopeDate","data"));
    }

    public function pooja(Request $request){
        $breads[] = ['name' => 'pooja','is_last' => true, 'url'=>'#'];
        return view(themeView().'pages.pooja', compact('breads'));
    }

    public function reports(Request $request){
        $breads[] = ['name' => 'reports','is_last' => true, 'url'=>'#'];
        return view(themeView().'pages.reports', compact('breads'));
    }

    public function aiastrologer(Request $request){
        $breads[] = ['name' => 'aiastrologer','is_last' => true, 'url'=>'#'];
        return view(themeView().'pages.aiastrologer', compact('breads'));
    }

    public function kundali(Request $request){
        $nav="kundali";

        $today = date('Y-m-d');
        $banners = Banner::where('status', 1)
        ->where('type', "kundali")
        ->where(function($start_date) use ($today){
          $start_date->where('start_date', '<=', $today)
          ->orWhereNull('start_date')
          ->orWhere('start_date', '1970-01-01')
          ->orWhere('start_date', '0000-00-00');
        })
        ->where(function($end_date) use ($today){
          $end_date->where('end_date', '>=', $today)
          ->orWhereNull('end_date')
          ->orWhere('end_date', '1970-01-01')
          ->orWhere('end_date', '0000-00-00');
        })
        ->orderBy('order', 'asc')
        ->limit(1)
        ->get();
        $errorToastr = Session::get("error-toastr");

        $user = auth()->guard("web")->user();
        $user_type = "guest";
        $event = Session::get("view_content_free_kundali");
        if($event == null || ($event == "guest" && $user != null)){
          $timestamp = time();
          $body["event_name"] = "ViewContent";
          $body["event_time"] = $timestamp;
          $body["event_id"] = "free_kundali_{$timestamp}";
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
          Session::put("view_content_free_kundali", $user_type);
        }
        $breads[]=["name"=>"Kundali","url"=>route("kundali")];
        return view(themeView().'pages.kundali.index', compact('nav','breads', "banners", "errorToastr"));
    }

    public function kundaliEnquirySubmit(Request $request){
      $request->validate([
        "name"=>"required",
        "gender"=>"required",
        "date"=>"required|date_format:Y-m-d",
        "time"=>"required",
        "city"=>"required",
      ]);
      $name=$request->name;
      $gender=$request->gender;
      $date=$request->date;
      $time=$request->time;
      $city=$request->city;
      $lang=$request->lang?:'en';
      $kundaliEnquiry=KundaliEnquiry::where("name",$name)->where("gender",$gender)->where("date",$date)->where("time",$time)->where("city",$city)->where('lang',$lang)->first();
      if(!$kundaliEnquiry){
        $lH=LocationHistory::where('state',$city)->first();
        $kundaliEnquiry=new KundaliEnquiry();
        $kundaliEnquiry->name=$name;
        $kundaliEnquiry->gender=$gender;
        $kundaliEnquiry->date=$date;
        $kundaliEnquiry->time=$time;
        $kundaliEnquiry->city=$city;
        $kundaliEnquiry->lat=$lH?$lH->latDeg:config('vrdcms.delhi_lat');
        $kundaliEnquiry->lon=$lH?$lH->longDeg:config('vrdcms.delhi_lon');
        $kundaliEnquiry->lang=$lang;
        $kundaliEnquiry->save();
      }
      return response()->json(["suceess"=>true,"message"=>"Please wait. Kundali is generating.","redirectTo"=>route("kundalidetail",[$kundaliEnquiry->id])]);
    }

    public function kundaliDetail(Request $request){
        $nav="kundali";
        $kundaliEnquiry=KundaliEnquiry::find($request->kundaliId);
        $place=$kundaliEnquiry->city;
        $data=[];
        if($kundaliEnquiry){
          $apiKey=fromSetting('astro_api_key');
          $dateSeconds=strtotime($kundaliEnquiry->date." ".$kundaliEnquiry->time);
          $date=date("Y-m-d",$dateSeconds);
          $time=date("H:i",$dateSeconds);
          $lat=$kundaliEnquiry->lat;
          $lon=$kundaliEnquiry->lon;
          $tz="5.5";
          $lang=$kundaliEnquiry->lang;
          $planetDetail=PlanetDetail::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
          $date=date("d/m/Y",$dateSeconds);
          if(!$planetDetail){
            $url="https://api.vedicastroapi.com/v3-json/horoscope/planet-details?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponseMain=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="planet_details";
            $apiLog->table_id=0;
            $apiLog->name="planet_details";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponseMain["status"]===200){
              $planetDetail=new PlanetDetail();
              $planetDetail->date=date("Y-m-d",$dateSeconds);
              $planetDetail->time=$time;
              $planetDetail->lat=$lat;
              $planetDetail->lon=$lon;
              $planetDetail->tz=$tz;
              $planetDetail->lang=$lang;
              $planetDetail->response=$response;
              $planetDetail->save();
              $apiLog->table_id=$planetDetail->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponseMain["response"])){
                $jsonResponse=$jsonResponseMain["response"];
              }
            }
            //extended detail
            $url="https://api.vedicastroapi.com/v3-json/extended-horoscope/extended-kundli-details?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponseExtended=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="planet_details";
            $apiLog->table_id=0;
            $apiLog->name="planet_details";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponseExtended["status"]===200){
              if(isset($jsonResponseExtended["response"])){
                $jsonResponseMain["response"]["extended"]=$jsonResponseExtended["response"];
              }
              $planetDetail->response=$jsonResponseMain;
              $planetDetail->save();
              $apiLog->table_id=$planetDetail->id;
              $apiLog->api_status=1;
              $apiLog->save();
            }
            $jsonResponse=$jsonResponseMain["response"];
          }else{
            $jsonResponse=json_decode($planetDetail->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
          $data=[
            "name"=>$kundaliEnquiry->name,
            "date"=>$date,
            "time"=>date("h:i A", $dateSeconds),
            "place"=>$place,
            "latitude"=>$lat,
            "longitude"=>$lon,
            "timezone"=>"GMT+5.5",
            "response"=>$jsonResponse
          ];
        }
        if(!is_array($data["response"])){
          Session::flash("error-toastr", "Some technical error occured. Please try again later.");
          return redirect()->route("kundali");
        }

        $user = auth()->guard("web")->user();
        $user_type = "guest";
        $event = Session::get("view_content_free_kundali_detail");
        if($event == null || ($event == "guest" && $user != null)){
          $timestamp = time();
          $body["event_name"] = "ViewContent";
          $body["event_time"] = $timestamp;
          $body["event_id"] = "free_kundali_detail_{$timestamp}";
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
          $body["custom_data"]["content_id"] = $kundaliEnquiry->id;
          $body["custom_data"]["content_name"] = $kundaliEnquiry->name;
          $body["custom_data"]["content_type"] = "Kundali";
          $metaService = new Meta();
          $metaService->sendEvent($body);
          Session::put("view_content_free_kundali_detail", $user_type);
        }
        $breads[]=["name"=>"Kundali","url"=>route("kundali")];
        $breads[]=["name"=>"Kundali Detail","url"=>route("kundalidetail",$kundaliEnquiry->id)];
        return view(themeView().'pages.kundali.detail', compact('nav','breads','data','planetDetail','kundaliEnquiry'));
    }

    public function kundaliChart(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $response="";
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $division=$request->div;
        $style="north";
        $divisionChart=DivisionChart::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("division",$division)->first();
        if(!$divisionChart){
          $date=date("d/m/Y",$dateSeconds);
          $url="https://api.vedicastroapi.com/v3-json/horoscope/chart-image?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang&div=$division&style=$style";
          $response=Http::withoutVerifying()->get($url);
          $apiLog=new ApiLog();
          $apiLog->table_name="division_charts";
          $apiLog->table_id=0;
          $apiLog->name="division_charts";
          $apiLog->request=$url;
          $apiLog->response=$response;
          $apiLog->api_status=0;
          $apiLog->save();
          if(!str_contains($response, 'status')){
            $divisionChart=new DivisionChart();
            $divisionChart->date=date("Y-m-d",$dateSeconds);
            $divisionChart->time=$time;
            $divisionChart->lat=$lat;
            $divisionChart->lon=$lon;
            $divisionChart->tz=$tz;
            $divisionChart->lang=$lang;
            $divisionChart->division=$division;
            $divisionChart->style="north";
            $divisionChart->response=$response;
            $divisionChart->save();
            $apiLog->table_id=$divisionChart->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }else{
          $response=$divisionChart->response;
        }
      }
      $svg="";
      if(!str_contains($response, 'status')){
        $svg=html_entity_decode($response); // Decode HTML entities
        $svg=str_replace('\n','',$svg); // Remove newlines
        $svg=str_replace('\"','"',$svg); // Unescape double quotes
        $svg=str_replace('\\','',$svg); // Remove backslashes
        $svg = preg_replace('/<svg /', '<svg viewBox="0 0 500 500" ', $svg);
        $svg = preg_replace('/width="[^"]*"/', 'width="400"', $svg);
        $svg = preg_replace('/height="[^"]*"/', 'height="400"', $svg);
      }
      return response()->json(["svg"=>$svg]);
    }

    public function kundaliMahadasha(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dasha=$request->dasha;
        $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
        if(!$dashaModel){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/dashas/maha-dasha?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="dashas";
            $apiLog->table_id=0;
            $apiLog->name="dashas";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dashaModel=new Dasha();
              $dashaModel->date=date("Y-m-d",$dateSeconds);
              $dashaModel->time=$time;
              $dashaModel->lat=$lat;
              $dashaModel->lon=$lon;
              $dashaModel->tz=$tz;
              $dashaModel->lang=$lang;
              $dashaModel->dasha=$dasha;
              $dashaModel->response=$response;
              $dashaModel->save();
              $apiLog->table_id=$dashaModel->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($dashaModel->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $mahadashaJson=[];
          if(isset($jsonResponse["mahadasha"])){
            $mahadasha=$jsonResponse["mahadasha"];
            $mahadasha_order=$jsonResponse["mahadasha_order"];
            $birthYear=date("Y",$dateSeconds);
            $birth=false;
            for($i=0;$i<count($mahadasha);$i++){
              $year=explode(" ",$mahadasha_order[$i]);
              $year=end($year);
              if($year>$birthYear){
                $mahadashaJson[]=[
                  "planet"=>$mahadasha[$i],
                  "start"=>$birth?$mahadasha_order[($i-1)]:"Birth",
                  "end"=>$mahadasha_order[$i],
                ];
                $birth=true;
              }
            }
          }
          return $mahadashaJson;
        }else{
          $html="";
          if(isset($jsonResponse["mahadasha"])){
            $mahadasha=$jsonResponse["mahadasha"];
            $mahadasha_order=$jsonResponse["mahadasha_order"];
            $html='<div class="table-responsive">';
            $html.='<table class="table table-bordered table-sm">';
            $html.='<thead class="bg-light">';
            $html.='<tr>';
            $html.='<th>Planet</th>';
            $html.='<th>Start Date</th>';
            $html.='<th>End Date</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $birthYear=date("Y",$dateSeconds);
            $birth=false;
            for($i=0;$i<count($mahadasha);$i++){
              $year=explode(" ",$mahadasha_order[$i]);
              $year=end($year);
              if($year>$birthYear){
                $html.='<tr>';
                $html.='<td>'.$mahadasha[$i].'</td>';
                $html.='<td>'.($birth?$mahadasha_order[($i-1)]:"Birth").'</td>';
                $html.='<td>'.$mahadasha_order[$i].'</td>';
                $html.='</tr>';
                $birth=true;
              }
            }
            $html.='</tbody>';
            $html.='</table>';
            $html.='</div>';
          }
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliAntardasha(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dasha=$request->dasha;
        $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
        if(!$dashaModel){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/dashas/antar-dasha?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="dashas";
            $apiLog->table_id=0;
            $apiLog->name="dashas";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dashaModel=new Dasha();
              $dashaModel->date=date("Y-m-d",$dateSeconds);
              $dashaModel->time=$time;
              $dashaModel->lat=$lat;
              $dashaModel->lon=$lon;
              $dashaModel->tz=$tz;
              $dashaModel->lang=$lang;
              $dashaModel->dasha=$dasha;
              $dashaModel->response=$response;
              $dashaModel->save();
              $apiLog->table_id=$dashaModel->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($dashaModel->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $antardashaJson=[];
          if(isset($jsonResponse["antardashas"])){
            $antardashas=$jsonResponse["antardashas"][0];
            $antardasha_order=$jsonResponse["antardasha_order"][0];
            $birth=false;
            $birthYear=date("Y",$dateSeconds);
            for($i=0;$i<count($antardashas);$i++){
              $year=explode(" ",$antardasha_order[$i]);
              $year=end($year);
              if($year>$birthYear){
                $antardashaJson[]=[
                  "planet"=>$antardashas[$i],
                  "start"=>$birth?$antardasha_order[($i-1)]:"Birth",
                  "end"=>$antardasha_order[$i],
                ];
                $birth=true;
              }
            }
          }
          return $antardashaJson;
        }else{
          $html="";
          if(isset($jsonResponse["antardashas"])){
            $antardashas=$jsonResponse["antardashas"][0];
            $antardasha_order=$jsonResponse["antardasha_order"][0];
            $html='<div class="table-responsive">';
            $html.='<table class="table table-bordered table-sm">';
            $html.='<thead class="bg-light">';
            $html.='<tr>';
            $html.='<th>Planet</th>';
            $html.='<th>Start Date</th>';
            $html.='<th>End Date</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $birth=false;
            $birthYear=date("Y",$dateSeconds);
            for($i=0;$i<count($antardashas);$i++){
              $year=explode(" ",$antardasha_order[$i]);
              $year=end($year);
              if($year>$birthYear){
                $html.='<tr>';
                $html.='<td>'.$antardashas[$i].'</td>';
                $html.='<td>'.($birth?$antardasha_order[($i-1)]:"Birth").'</td>';
                $html.='<td>'.$antardasha_order[$i].'</td>';
                $html.='</tr>';
                $birth=true;
              }
            }
            $html.='</tbody>';
            $html.='</table>';
            $html.='</div>';
          }
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliPratyantardasha(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dasha=$request->dasha;
        $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
        if(!$dashaModel){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/dashas/paryantar-dasha?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="dashas";
            $apiLog->table_id=0;
            $apiLog->name="dashas";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dashaModel=new Dasha();
              $dashaModel->date=date("Y-m-d",$dateSeconds);
              $dashaModel->time=$time;
              $dashaModel->lat=$lat;
              $dashaModel->lon=$lon;
              $dashaModel->tz=$tz;
              $dashaModel->lang=$lang;
              $dashaModel->dasha=$dasha;
              $dashaModel->response=$response;
              $dashaModel->save();
              $apiLog->table_id=$dashaModel->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($dashaModel->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $pratyantardashaJson=[];
          if(isset($jsonResponse["paryantardasha"])){
            $paryantardasha=$jsonResponse["paryantardasha"][1][0];
            $paryantardasha_order=$jsonResponse["paryantardasha_order"][1][0];
            $birth=false;
            $birthYear=date("Y",$dateSeconds);
            for($i=0;$i<count($paryantardasha);$i++){
              $year=explode(" ",$paryantardasha_order[$i]);
              $year=end($year);
              if($year>$birthYear){
                $pratyantardashaJson[]=[
                  "planet"=>$paryantardasha[$i],
                  "start"=>$birth?$paryantardasha_order[($i-1)]:"Birth",
                  "end"=>$paryantardasha_order[$i],
                ];
                $birth=true;
              }
            }
          }
          return $pratyantardashaJson;
        }else{
          $html="";
          if(isset($jsonResponse["paryantardasha"])){
            $paryantardasha=$jsonResponse["paryantardasha"][1][0];
            $paryantardasha_order=$jsonResponse["paryantardasha_order"][1][0];
            $html='<div class="table-responsive">';
            $html.='<table class="table table-bordered table-sm">';
            $html.='<thead class="bg-light">';
            $html.='<tr>';
            $html.='<th>Planet</th>';
            $html.='<th>Start Date</th>';
            $html.='<th>End Date</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $birth=false;
            $birthYear=date("Y",$dateSeconds);
            for($i=0;$i<count($paryantardasha);$i++){
              $year=explode(" ",$paryantardasha_order[$i]);
              $year=end($year);
              if($year>$birthYear){
                $html.='<tr>';
                $html.='<td>'.$paryantardasha[$i].'</td>';
                $html.='<td>'.($birth?$paryantardasha_order[($i-1)]:"Birth").'</td>';
                $html.='<td>'.$paryantardasha_order[$i].'</td>';
                $html.='</tr>';
                $birth=true;
              }
            }
            $html.='</tbody>';
            $html.='</table>';
            $html.='</div>';
          }
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliKpPlanet(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      $planetJsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $kpPlanet=KpPlanet::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
        if(!$kpPlanet){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/extended-horoscope/kp-planets?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="kp_planets";
            $apiLog->table_id=0;
            $apiLog->name="kp_planets";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $kpPlanet=new KpPlanet();
              $kpPlanet->date=date("Y-m-d",$dateSeconds);
              $kpPlanet->time=$time;
              $kpPlanet->lat=$lat;
              $kpPlanet->lon=$lon;
              $kpPlanet->tz=$tz;
              $kpPlanet->lang=$lang;
              $kpPlanet->response=$response;
              $kpPlanet->save();
              $apiLog->table_id=$kpPlanet->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($kpPlanet->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
          $planetJsonResponse=json_decode($planetDetail->response,true);
          $planetJsonResponse=$planetJsonResponse["response"];
        }
        if($request->return_type && $request->return_type="json"){
          $planetJson=[];
          for($i=0;$i<10;$i++){
            $planetItem=$planetJsonResponse[$i];
            $item=$jsonResponse[$i];
            $planetJson[]=[
              "planet"=>$planetItem["full_name"],
              "cups"=>$planetItem["house"],
              "zodiac"=>$planetItem["zodiac"],
              "zodiac_lord"=>$planetItem["zodiac_lord"],
              "nakshatra"=>$planetItem["nakshatra"],
              "nakshatra_lord"=>$planetItem["nakshatra_lord"],
              "sub_lord"=>$item["sub_lord"]
            ];
          }
          return $planetJson;
        }else{
          $html='<h5 class="mb-3">Planet Positions</h5>';
          $html.='<div class="table-responsive">';
          $html.='<table class="table table-bordered table-sm">';
          $html.='<thead class="bg-light">';
          $html.='<tr>';
          $html.='<th>Planet</th>';
          $html.='<th>Cups</th>';
          $html.='<th>Zodiac</th>';
          $html.='<th>Zodiac Lord</th>';
          $html.='<th>Start</th>';
          $html.='<th>Start Lord</th>';
          $html.='<th>Sub Lord</th>';
          $html.='</tr>';
          $html.='</thead>';
          $html.='<tbody>';
          for($i=0;$i<10;$i++){
            $planetItem=$planetJsonResponse[$i];
            $item=$jsonResponse[$i];
            $html.='<tr>';
            $html.='<td>'.$planetItem["full_name"].'</td>';
            $html.='<td>'.$planetItem["house"].'</td>';
            $html.='<td>'.$planetItem["zodiac"].'</td>';
            $html.='<td>'.$planetItem["zodiac_lord"].'</td>';
            $html.='<td>'.$planetItem["nakshatra"].'</td>';
            $html.='<td>'.$planetItem["nakshatra_lord"].'</td>';
            $html.='<td>'.$item["sub_lord"].'</td>';
            $html.='</tr>';
          }
          $html.='</tbody>';
          $html.='</table>';
          $html.='</div>';
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliAkvargaChart(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $response="";
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $planet=$request->planet;
        $style="north";
        $akvargaChart=AkvargaChart::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("planet",$planet)->first();
        if(!$akvargaChart){
          $date=date("d/m/Y",$dateSeconds);
          $url="https://api.vedicastroapi.com/v3-json/horoscope/ashtakvarga-chart-image?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang&planet=$planet&style=$style";
          $response=Http::withoutVerifying()->get($url);
          $apiLog=new ApiLog();
          $apiLog->table_name="akvarga_charts";
          $apiLog->table_id=0;
          $apiLog->name="akvarga_charts";
          $apiLog->request=$url;
          $apiLog->response=$response;
          $apiLog->api_status=0;
          $apiLog->save();
          if(!str_contains($response, 'status')){
            $akvargaChart=new AkvargaChart();
            $akvargaChart->date=date("Y-m-d",$dateSeconds);
            $akvargaChart->time=$time;
            $akvargaChart->lat=$lat;
            $akvargaChart->lon=$lon;
            $akvargaChart->tz=$tz;
            $akvargaChart->lang=$lang;
            $akvargaChart->planet=$planet;
            $akvargaChart->style="north";
            $akvargaChart->response=$response;
            $akvargaChart->save();
            $apiLog->table_id=$akvargaChart->id;
            $apiLog->api_status=1;
            $apiLog->save();
          }
        }else{
          $response=$akvargaChart->response;
        }
      }
      $svg="";
      if(!str_contains($response, 'status')){
        $svg=html_entity_decode($response); // Decode HTML entities
        $svg=str_replace('\n','',$svg); // Remove newlines
        $svg=str_replace('\"','"',$svg); // Unescape double quotes
        $svg=str_replace('\\','',$svg); // Remove backslashes
        $svg = preg_replace('/<svg /', '<svg viewBox="0 0 500 500" ', $svg);
        $svg = preg_replace('/width="[^"]*"/', 'width="400"', $svg);
        $svg = preg_replace('/height="[^"]*"/', 'height="400"', $svg);
      }
      return response()->json(["svg"=>$svg]);
    }

    public function kundaliDashaVimshottari(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dasha=$request->dasha;
        $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
        if(!$dashaModel){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/dashas/maha-dasha-predictions?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="dashas";
            $apiLog->table_id=0;
            $apiLog->name="dashas";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dashaModel=new Dasha();
              $dashaModel->date=date("Y-m-d",$dateSeconds);
              $dashaModel->time=$time;
              $dashaModel->lat=$lat;
              $dashaModel->lon=$lon;
              $dashaModel->tz=$tz;
              $dashaModel->lang=$lang;
              $dashaModel->dasha=$dasha;
              $dashaModel->response=$response;
              $dashaModel->save();
              $apiLog->table_id=$dashaModel->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($dashaModel->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $vimshottariJson=[];
          if(isset($jsonResponse["dashas"])){
            $dashas=$jsonResponse["dashas"];
            for($i=0;$i<count($dashas);$i++){
              $vimshottariJson[]=[
                "full_name"=>$dashas[$i]["dasha"]." Mahadasha",
                "start_end"=>$dashas[$i]["dasha_start_year"]." - ".$dashas[$i]["dasha_end_year"],
                "prediction"=>$dashas[$i]["prediction"],
                "planet_in_zodiac"=>$dashas[$i]["planet_in_zodiac"],
              ];
            }
          }
          return $vimshottariJson;
        }else{
          $html="";
          if(isset($jsonResponse["dashas"])){
            $dashas=$jsonResponse["dashas"];
            for($i=0;$i<count($dashas);$i++){
              $html.='<div class="p-3 border rounded bg-white mb-3">';
              $html.='<div class="d-flex justify-content-between">';
              $html.='<strong>'.$dashas[$i]["dasha"].' Mahadasha</strong>';
              $html.='<small class="text-muted">('.$dashas[$i]["dasha_start_year"].' - '.$dashas[$i]["dasha_end_year"].')</small>';
              $html.='</div>';
              $html.='<p class="mt-2 mb-1">'.$dashas[$i]["prediction"].'</p>';
              $html.='<p>'.$dashas[$i]["planet_in_zodiac"].'</p>';
              $html.='</div>';
            }
          }
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliDashaYogini(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dasha=$request->dasha;
        $dashaModel=Dasha::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where("dasha",$dasha)->first();
        if(!$dashaModel){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/dashas/yogini-dasha-main?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="dashas";
            $apiLog->table_id=0;
            $apiLog->name="dashas";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dashaModel=new Dasha();
              $dashaModel->date=date("Y-m-d",$dateSeconds);
              $dashaModel->time=$time;
              $dashaModel->lat=$lat;
              $dashaModel->lon=$lon;
              $dashaModel->tz=$tz;
              $dashaModel->lang=$lang;
              $dashaModel->dasha=$dasha;
              $dashaModel->response=$response;
              $dashaModel->save();
              $apiLog->table_id=$dashaModel->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($dashaModel->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $yoginiJson=[];
          if(isset($jsonResponse["dasha_list"])){
            $dasha_list=$jsonResponse["dasha_list"];
            $dasha_end_dates=$jsonResponse["dasha_end_dates"];
            $birth=false;
            $birthYear=date("Y",$dateSeconds);
            for($i=0;$i<count($dasha_list);$i++){
              $current=explode(" ",$dasha_end_dates[$i]);
              $year=$current[3];
              if($year>$birthYear){
                $previous="Birth";
                if($birth){
                  $previous=explode(" ",$dasha_end_dates[$i-1]);
                  $previous=($previous[0].' '.$previous[1].' '.$previous[2].' '.$previous[3]);
                }
                $yoginiJson[]=[
                  "planet"=>$dasha_list[$i],
                  "start"=>$previous,
                  "end"=>$current[0].' '.$current[1].' '.$current[2].' '.$current[3]
                ];
                $birth=true;
              }
            }
          }
          return $yoginiJson;
        }else{
          $html="";
          if(isset($jsonResponse["dasha_list"])){
            $dasha_list=$jsonResponse["dasha_list"];
            $dasha_end_dates=$jsonResponse["dasha_end_dates"];
            $html='<div class="table-responsive">';
            $html.='<table class="table table-bordered table-sm">';
            $html.='<thead class="bg-light">';
            $html.='<tr>';
            $html.='<th>Planet</th>';
            $html.='<th>Start Date</th>';
            $html.='<th>End Date</th>';
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $birth=false;
            $birthYear=date("Y",$dateSeconds);
            for($i=0;$i<count($dasha_list);$i++){
              $current=explode(" ",$dasha_end_dates[$i]);
              $year=$current[3];
              if($year>$birthYear){
                $previous="Birth";
                if($birth){
                  $previous=explode(" ",$dasha_end_dates[$i-1]);
                  $previous=($previous[0].' '.$previous[1].' '.$previous[2].' '.$previous[3]);
                }
                $html.='<tr>';
                $html.='<td>'.$dasha_list[$i].'</td>';
                $html.='<td>'.$previous.'</td>';
                $html.='<td>'.($current[0].' '.$current[1].' '.$current[2].' '.$current[3]).'</td>';
                $html.='</tr>';
                $birth=true;
              }
            }
            $html.='</tbody>';
            $html.='</table>';
            $html.='</div>';
          }
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliReportGeneral(Request $request){
      $zodiac=$request->zodiac;
      $zodiac=zodiacSignNameHindiEnglish($zodiac);
      $zodiacSign=ZodiacSign::where("name",$zodiac)->first();
      $zodiac=$zodiacSign->id;
      $timeFrame=$request->timeFrame;
      $date=date("d/m/Y");
      $apiKey=fromSetting('astro_api_key');
      $split=$request->split?:true;
      $type=$request->type?:"big";
      $lang=$request->lang?:"en";
      $horoscopeDate=date("d F Y");
      $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey";
      if($timeFrame=="today"){
        $date=date("d/m/Y");
        $horoscopeDate=date("d F Y");
        $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
        $date=date("Y-m-d");
        $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where('lang',$lang)->first();
      }elseif($timeFrame=="tomorrow"){
        $date=date("d/m/Y", strtotime('now+24hours'));
        $horoscopeDate=date("d F Y", strtotime('now+24hours'));
        $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
        $date=date("Y-m-d", strtotime('now+24hours'));
        $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where('lang',$lang)->first();
      }elseif($timeFrame=="yesterday"){
        $date=date("d/m/Y", strtotime('now-24hours'));
        $horoscopeDate=date("d F Y", strtotime('now-24hours'));
        $url="https://api.vedicastroapi.com/v3-json/prediction/daily-moon?api_key=$apiKey&date=$date&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
        $date=date("Y-m-d", strtotime('now-24hours'));
        $horoscope=HoroscopeDaily::where("zodiac_sign_id",$zodiacSign->id)->where("date",$date)->where('lang',$lang)->first();
      }elseif($timeFrame=="weekly"){
        $week="thisweek";
        $horoscopeDate=date("d F Y", strtotime('monday this week'))." - ".date("d F Y", strtotime('sunday this week'));
        $fromDate=date("Y-m-d", strtotime('monday this week'));
        $toDate=date("Y-m-d", strtotime('sunday this week'));
        $url="https://api.vedicastroapi.com/v3-json/prediction/weekly-moon?api_key=$apiKey&week=$week&zodiac=$zodiac&split=$split&type=$type&lang=$lang";
        $horoscope=HoroscopeWeekly::where("zodiac_sign_id",$zodiacSign->id)->where("from_date",$fromDate)->where("to_date",$toDate)->where('lang',$lang)->first();
      }elseif($timeFrame=="yearly"){
        $timeFrameUrl="yearly";
        $horoscopeDate=$year=date("Y");
        $url="https://api.vedicastroapi.com/v3-json/prediction/yearly?api_key=$apiKey&year=$year&zodiac=$zodiac&lang=$lang";
        $horoscope=HoroscopeYearly::where("zodiac_sign_id",$zodiacSign->id)->where("year",$year)->where('lang',$lang)->first();
      }
      $data=[];
      if($horoscope){
        $jsonResponse=json_decode($horoscope->api_response,true);
        if(isset($jsonResponse["response"])){
          $jsonResponse=$jsonResponse["response"];
        }
      }
      if(count($data)==0){
        $response=Http::withoutVerifying()->get($url);
        $statusCode=$response->status();
        $jsonResponse=$response->json();
        $apiLog=new ApiLog();
        $apiLog->table_name="horoscope_dailies";
        $apiLog->table_id=0;
        $apiLog->name="horoscope_dailies";
        $apiLog->request=$url;
        $apiLog->response=$response;
        $apiLog->api_status=0;
        $apiLog->save();
        if($jsonResponse["status"]===200){
          if(!$horoscope){
            if($timeFrame=="yearly"){
              $horoscope=new HoroscopeYearly();
              $horoscope->zodiac_sign_id=$zodiac;
              $horoscope->year=$year;
              $apiLog->table_name="horoscope_yearlies";
              $apiLog->name="horoscope_yearlies";
            }elseif($timeFrame=="weekly"){
              $horoscope=new HoroscopeWeekly();
              $horoscope->zodiac_sign_id=$zodiac;
              $horoscope->from_date=$fromDate;
              $horoscope->to_date=$toDate;
              $apiLog->table_name="horoscope_weeklies";
              $apiLog->name="horoscope_weeklies";
            }else{
              $horoscope=new HoroscopeDaily();
              $horoscope->zodiac_sign_id=$zodiac;
              $horoscope->date=$date;
            }
          }
          $horoscope->lang=$lang;
          $horoscope->api_response=$response;
          $horoscope->save();
          $apiLog->table_id=$horoscope->id;
          $apiLog->api_status=1;
          $apiLog->save();
          if(isset($jsonResponse["response"])){
            $jsonResponse=$jsonResponse["response"];
          }
        }
      }
      // return response()->json(["html"=>$jsonResponse]);
      if($timeFrame=="yearly"){
        $dataa=$jsonResponse;
        $data=[];
        $data["bot_response"]=$dataa["phase_1"];
        $data["bot_response"]["relationship"]["split_response"]=$dataa["phase_1"]["relationship"]["prediction"];
        $data["bot_response"]["health"]["split_response"]=$dataa["phase_1"]["health"]["prediction"];
        $data["bot_response"]["physique"]["split_response"]=$dataa["phase_1"]["physique"]["prediction"];
        $data["bot_response"]["career"]["split_response"]=$dataa["phase_1"]["career"]["prediction"];
        $data["bot_response"]["finances"]["split_response"]=$dataa["phase_1"]["finances"]["prediction"];
        $data["bot_response"]["family"]["split_response"]=$dataa["phase_1"]["family"]["prediction"];
        $data["bot_response"]["friends"]["split_response"]=$dataa["phase_1"]["friends"]["prediction"];
        $data["bot_response"]["total_score"]["split_response"]=$dataa["phase_1"]["status"]["prediction"];
      }
      if($request->return_type && $request->return_type="json"){
        $generalReportJson=[];
        if(isset($data["bot_response"])){
          $bot_response=$data["bot_response"];
          $generalReportJson[]=[
            "name"=>"Description",
            "prediction"=>$bot_response["prediction"]
          ];
          $generalReportJson[]=[
            "name"=>"Personality",
            "prediction"=>$bot_response["physique"]["split_response"]
          ];
          $generalReportJson[]=[
            "name"=>"Health",
            "prediction"=>$bot_response["health"]["split_response"]
          ];
          $generalReportJson[]=[
            "name"=>"Career",
            "prediction"=>$bot_response["career"]["split_response"]
          ];
          $generalReportJson[]=[
            "name"=>"Relationship",
            "prediction"=>$bot_response["relationship"]["split_response"]
          ];
          $generalReportJson[]=[
            "name"=>"Family",
            "prediction"=>$bot_response["family"]["split_response"]
          ];
        }
        return $generalReportJson;
      }else{
        $html="";
        if(isset($data["bot_response"])){
          $bot_response=$data["bot_response"];
          $html.='<div class="p-3 border rounded bg-white mb-3">';
          $html.='<h6 class="fw-bold">Description</h6>';
          $html.='<p>'.$bot_response["prediction"].'</p>';
          $html.='</div>';
          $html.='<div class="p-3 border rounded bg-white mb-3">';
          $html.='<h6 class="fw-bold">Personality</h6>';
          $html.='<p>'.$bot_response["physique"]["split_response"].'</p>';
          $html.='</div>';
          $html.='<div class="p-3 border rounded bg-white mb-3">';
          $html.='<h6 class="fw-bold">Health</h6>';
          $html.='<p>'.$bot_response["health"]["split_response"].'</p>';
          $html.='</div>';
          $html.='<div class="p-3 border rounded bg-white mb-3">';
          $html.='<h6 class="fw-bold">Career</h6>';
          $html.='<p>'.$bot_response["career"]["split_response"].'</p>';
          $html.='</div>';
          $html.='<div class="p-3 border rounded bg-white mb-3">';
          $html.='<h6 class="fw-bold">Relationship</h6>';
          $html.='<p>'.$bot_response["relationship"]["split_response"].'</p>';
          $html.='</div>';
          $html.='<div class="p-3 border rounded bg-white mb-3">';
          $html.='<h6 class="fw-bold">Family</h6>';
          $html.='<p>'.$bot_response["family"]["split_response"].'</p>';
          $html.='</div>';
        }
        return response()->json(["html"=>$html]);
      }
    }

    public function kundaliReportPlanetary(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $planet=Planet::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
        if(!$planet){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/horoscope/personal-characteristics?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="planets";
            $apiLog->table_id=0;
            $apiLog->name="planets";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $planet=new Planet();
              $planet->date=date("Y-m-d",$dateSeconds);
              $planet->time=$time;
              $planet->lat=$lat;
              $planet->lon=$lon;
              $planet->tz=$tz;
              $planet->lang=$lang;
              $planet->response=$response;
              $planet->save();
              $apiLog->table_id=$planet->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($planet->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $planetaryReportJson=[];
          foreach($jsonResponse as $key => $value){
            $planetaryReportJson[]=[
              "planet"=>$value["lord_of_zodiac"],
              "prediction"=>$value["personalised_prediction"]
            ];
          }
          return $planetaryReportJson;
        }else{
          $html="";
          foreach ($jsonResponse as $key => $value) {
            $html.='<div class="p-3 border rounded bg-white mb-3">';
            $html.='<h6 class="fw-bold">'.$value["lord_of_zodiac"].' Consideration</h6>';
            $html.='<p>'.$value["personalised_prediction"].'</p>';
            $html.='</div>';
          }
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliReportYoga(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $yoga=Yoga::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->first();
        if(!$yoga){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/extended-horoscope/yoga-list?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="yogas";
            $apiLog->table_id=0;
            $apiLog->name="yogas";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $yoga=new Yoga();
              $yoga->date=date("Y-m-d",$dateSeconds);
              $yoga->time=$time;
              $yoga->lat=$lat;
              $yoga->lon=$lon;
              $yoga->tz=$tz;
              $yoga->lang=$lang;
              $yoga->response=$response;
              $yoga->save();
              $apiLog->table_id=$yoga->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($yoga->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $yogaReportJson=[];
          foreach($jsonResponse["yogas_list"] as $key => $value){
            $yogaReportJson[]=[
              "name"=>$value["yoga"],
              "meaning"=>$value["meaning"]
            ];
          }
          return $yogaReportJson;
        }else{
          $html="";
          foreach ($jsonResponse["yogas_list"] as $key => $value) {
            $html.='<div class="p-3 border rounded bg-white mb-3">';
            $html.='<h6 class="fw-bold">'.$value["yoga"].'</h6>';
            $html.='<p>'.$value["meaning"].'</p>';
            $html.='</div>';
          }
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliReportManglik(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/dosha/manglik-dosh?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="doshes";
            $apiLog->table_id=0;
            $apiLog->name="doshes";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$response;
              $dosh->dosh=$request->id;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($dosh->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        preg_match('/([\d.]+)%/',$jsonResponse["bot_response"],$matches);
        $percentage=0;
        if(isset($matches[1])){
          $percentage=(float) $matches[1];
        }
        if($request->return_type && $request->return_type="json"){
          $manglikReportJson=[
            "full_name"=>"Manglik Analysis",
            "is_dosha_present"=>$percentage>50?true:false,
            "factors"=>[],
            "note"=>"This is a computer-generated result. Please consult an Astrologer to confirm & understand this in detail."
          ];
          foreach($jsonResponse["factors"] as $key => $value){
            $manglikReportJson["factors"][]=$value;
          }
          return $manglikReportJson;
        }else{
          $html='<h6 class="fw-bold">Manglik Analysis</h6>';
          $html.='<div class="p-3 border rounded bg-danger-subtle" style="border: 1px solid #dc3545;">';
          $html.='<div class="d-flex align-items-center mb-2">';
          $html.='<div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">'.($percentage>50?"Yes":"No").'</div>';
          // $html.='<div><strong>Rohit</strong></div>';
          $html.='</div>';
          foreach ($jsonResponse["factors"] as $key => $value){
            $html.='<p class="mb-1">'.$value.'</p>';
          }
          $html.='<small class="text-danger d-block mt-2">';
          $html.='[This is a computer-generated result. Please consult an Astrologer to confirm & understand this in detail.]';
          $html.='</small>';
          $html.='</div>';
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliReportKalsarpa(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/dosha/kaalsarp-dosh?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="doshes";
            $apiLog->table_id=0;
            $apiLog->name="doshes";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$response;
              $dosh->dosh=$request->id;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($dosh->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $kalsarpaReportJson=[
            "full_name"=>"Kalsarpa Analysis",
            "is_dosha_present"=>$jsonResponse["is_dosha_present"]?true:false,
            "remedies"=>[]
          ];
          foreach($jsonResponse["remedies"] as $key => $value){
            $kalsarpaReportJson["remedies"][]=$value;
          }
          return $kalsarpaReportJson;
        }else{
          $html='<h6 class="fw-bold">Kalsarpa Analysis</h6>';
          $html.='<div class="p-3 border rounded bg-danger-subtle" style="border: 1px solid #dc3545;">';
          $html.='<div class="d-flex align-items-center mb-2">';
          $html.='<div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">'.($jsonResponse["is_dosha_present"]?"Yes":"No").'</div>';
          // $html.='<div><strong>Rohit</strong></div>';
          $html.='</div>';
          foreach ($jsonResponse["remedies"] as $key => $value){
            $html.='<p class="mb-1">'.$value.'</p>';
          }
          $html.='</div>';
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliReportSadesati(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      $jsonResponseTable=[];
      $jsonResponseDetail=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/extended-horoscope/sade-sati-table?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="doshes";
            $apiLog->table_id=0;
            $apiLog->name="doshes";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$response;
              $dosh->dosh=$request->id;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponseTable=$jsonResponse["response"];
              }
            }
            $url="https://api.vedicastroapi.com/v3-json/extended-horoscope/current-sade-sati?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponseDetail=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="doshes";
            $apiLog->table_id=0;
            $apiLog->name="doshes";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponseDetail["status"]===200){
              if(isset($jsonResponseDetail["response"])){
                $jsonResponse["detail"]=$jsonResponseDetail["response"];
                $jsonResponseDetail=$jsonResponseDetail["response"];
              }
              $dosh->response=$jsonResponse;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();
            }
          }else{
            $jsonResponse=json_decode($dosh->response,true);
            $jsonResponseTable=$jsonResponse["response"];
            $jsonResponseDetail=$jsonResponse["detail"];
          }
        }
        if($request->return_type && $request->return_type="json"){
          $sadesatiReportJson=[
            "full_name"=>"Sadesati Analysis",
            "is_dosha_present"=>$jsonResponseDetail["shani_period_type"]?true:false,
            "description"=>$jsonResponseDetail["description"],
            "details"=>[],
            "remedies"=>[]
          ];
          $currentYear=date("Y");
          $afterTwentyYear=date("Y",strtotime("now+20years"));
          for($i=0;$i<count($jsonResponseTable);$i++){
            $item=$jsonResponseTable[$i];
            $startDateArray=explode(" ",$item["start_date"]);
            $startYear=end($startDateArray);
            if($startYear>=$currentYear && $startYear<=$afterTwentyYear){
              $sadesatiReportJson["details"][]=[
                "start"=>$item["start_date"],
                "end"=>$item["end_date"],
                "zodiac"=>$item["zodiac"],
                "type"=>$item["type"]
              ];
            }
          }
          foreach($jsonResponseDetail["remedies"] as $key => $value){
            $sadesatiReportJson["remedies"][]=$value;
          }
          return $sadesatiReportJson;
        }else{
          $html='<h6 class="fw-bold">Description</h6>';
          $html.='<div class="p-3 border rounded bg-danger-subtle mb-3" style="border: 1px solid #dc3545;">';
          $html.='<div class="d-flex align-items-center mb-2">';
          $html.='<div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">'.($jsonResponseDetail["shani_period_type"]?"Yes":"No").'</div>';
          $html.='</div>';
          $html.='<p class="mb-1">'.$jsonResponseDetail["description"].'</p>';
          $html.='</div>';

          $html.='<div class="table-responsive">';
          $html.='<table class="table table-bordered table-sm">';
          $html.='<thead class="bg-light">';
          $html.='<tr>';
          $html.='<th>Start</th>';
          $html.='<th>End</th>';
          $html.='<th>Sign Name</th>';
          $html.='<th>Type</th>';
          $html.='</tr>';
          $html.='</thead>';
          $html.='<tbody>';
          $currentYear=date("Y");
          $afterTwentyYear=date("Y",strtotime("now+20years"));
          for($i=0;$i<count($jsonResponseTable);$i++){
            $item=$jsonResponseTable[$i];
            $startDateArray=explode(" ",$item["start_date"]);
            $startYear=end($startDateArray);
            if($startYear>=$currentYear && $startYear<=$afterTwentyYear){
              $html.='<tr>';
              $html.='<td>'.$item["start_date"].'</td>';
              $html.='<td>'.$item["end_date"].'</td>';
              $html.='<td>'.$item["zodiac"].'</td>';
              $html.='<td>'.$item["type"].'</td>';
              $html.='</tr>';
            }
          }
          $html.='</tbody>';
          $html.='</table>';
          $html.='</div>';

          $html.='<h6 class="fw-bold">Remedies</h6>';
          $html.='<div class="p-3 border rounded bg-danger-subtle" style="border: 1px solid #dc3545;">';
          foreach ($jsonResponseDetail["remedies"] as $key => $value){
            $html.='<p class="mb-1">'.$value.'</p>';
          }
          $html.='</div>';
          return response()->json(["html"=>$html]);
        }
    }

    public function kundaliReportPitra(Request $request){
      $planetDetail=PlanetDetail::find($request->planet_detail_id);
      $jsonResponse=[];
      if($planetDetail){
        $apiKey=fromSetting('astro_api_key');
        $dateSeconds=strtotime($planetDetail->date." ".$planetDetail->time);
        $date=date("Y-m-d",$dateSeconds);
        $time=date("H:i",$dateSeconds);
        $lat=$planetDetail->lat;
        $lon=$planetDetail->lon;
        $tz=$planetDetail->tz;
        $lang=$planetDetail->lang;
        $dosh=Dosh::where("date",$date)->where("time",$time)->where("lat",$lat)->where("lon",$lon)->where("tz",$tz)->where("lang",$lang)->where('dosh', $request->id)->first();
        if(!$dosh){
            $date=date("d/m/Y",$dateSeconds);
            $url="https://api.vedicastroapi.com/v3-json/dosha/pitra-dosh?api_key=$apiKey&dob=$date&tob=$time&lat=$lat&lon=$lon&tz=$tz&lang=$lang";
            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponse=$response->json();
            $apiLog=new ApiLog();
            $apiLog->table_name="doshes";
            $apiLog->table_id=0;
            $apiLog->name="doshes";
            $apiLog->request=$url;
            $apiLog->response=$response;
            $apiLog->api_status=0;
            $apiLog->save();
            if($jsonResponse["status"]===200){
              $dosh=new Dosh();
              $dosh->date=date("Y-m-d",$dateSeconds);
              $dosh->time=$time;
              $dosh->lat=$lat;
              $dosh->lon=$lon;
              $dosh->tz=$tz;
              $dosh->lang=$lang;
              $dosh->response=$response;
              $dosh->dosh=$request->id;
              $dosh->save();
              $apiLog->table_id=$dosh->id;
              $apiLog->api_status=1;
              $apiLog->save();
              if(isset($jsonResponse["response"])){
                $jsonResponse=$jsonResponse["response"];
              }
            }
          }else{
            $jsonResponse=json_decode($dosh->response,true);
            $jsonResponse=$jsonResponse["response"];
          }
        }
        preg_match('/([\d.]+)%/',$jsonResponse["bot_response"],$matches);
        $percentage=0;
        if(isset($matches[1])){
          $percentage=(float) $matches[1];
        }
        $html='<h6 class="fw-bold">Pitra Analysis</h6>';
        $html.='<div class="p-3 border rounded bg-danger-subtle" style="border: 1px solid #dc3545;">';
        $html.='<div class="d-flex align-items-center mb-2">';
        $html.='<div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">'.($jsonResponse["is_dosha_present"]?"Yes":"No").'</div>';
        $html.='<div><strong>Pitra Dosha</strong></div>';
        $html.='</div>';
        foreach ($jsonResponse["remedies"] as $key => $value){
          $html.='<p class="mb-1">'.$value.'</p>';
        }
        $html.='</div>';
        return response()->json(["html"=>$html]);
    }

    public function kundalimatching(Request $request){
        $breads[] = ['name' => 'kundalimatching','is_last' => true, 'url'=>'#'];
        $nav = "kundalimatching";

        $today = date('Y-m-d');
        $banners = Banner::where('status', 1)
        ->where('type', "kundali_matching")
        ->where(function($start_date) use ($today){
          $start_date->where('start_date', '<=', $today)
          ->orWhereNull('start_date')
          ->orWhere('start_date', '1970-01-01')
          ->orWhere('start_date', '0000-00-00');
        })
        ->where(function($end_date) use ($today){
          $end_date->where('end_date', '>=', $today)
          ->orWhereNull('end_date')
          ->orWhere('end_date', '1970-01-01')
          ->orWhere('end_date', '0000-00-00');
        })
        ->orderBy('order', 'asc')
        ->limit(1)
        ->get();
        $errorToastr = Session::get("error-toastr");

        $user = auth()->guard("web")->user();
        $user_type = "guest";
        $event = Session::get("view_content_free_kundali_matching");
        if($event == null || ($event == "guest" && $user != null)){
          $timestamp = time();
          $body["event_name"] = "ViewContent";
          $body["event_time"] = $timestamp;
          $body["event_id"] = "free_kundali_matching_{$timestamp}";
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
          Session::put("view_content_free_kundali_matching", $user_type);
        }
        return view(themeView().'pages.kundalimatching.index', compact("nav", 'breads', "banners", "errorToastr"));
    }

    public function kundaliMatchingEnquiry(Request $request){
      $request->validate([
        "boy_name"=>"required",
        "boy_birth_date"=>"required|date_format:Y-m-d|before:today",
        "boy_birth_time"=>"required",
        "boy_birth_place"=>"required",
        "girl_name"=>"required",
        "girl_birth_date"=>"required|date_format:Y-m-d|before:today",
        "girl_birth_time"=>"required",
        "girl_birth_place"=>"required",
      ]);
      $boy_name = $request->boy_name;
      $boy_birth_date = $request->boy_birth_date;
      $boy_birth_time = $request->boy_birth_time;
      $boy_birth_place = $request->boy_birth_place;
      $girl_name = $request->girl_name;
      $girl_birth_date = $request->girl_birth_date;
      $girl_birth_time = $request->girl_birth_time;
      $girl_birth_place = $request->girl_birth_place;
      $lang = $request->lang?:'en';

      $kundaliMatchingEnquiry = KundaliMatchingEnquiry::where("boy_name", $boy_name)
      ->where("boy_birth_date", $boy_birth_date)
      ->where("boy_birth_time", $boy_birth_time)
      ->where("boy_birth_place", $boy_birth_place)
      ->where("girl_name", $girl_name)
      ->where("girl_birth_date", $girl_birth_date)
      ->where("girl_birth_time", $girl_birth_time)
      ->where("girl_birth_place", $girl_birth_place)
      ->where("lang", $lang)
      ->first();

      if(!$kundaliMatchingEnquiry){
        $kundaliMatchingEnquiry = new KundaliMatchingEnquiry();
        $kundaliMatchingEnquiry->boy_name = $boy_name;
        $kundaliMatchingEnquiry->boy_birth_date = $boy_birth_date;
        $kundaliMatchingEnquiry->boy_birth_time = $boy_birth_time;
        $kundaliMatchingEnquiry->boy_birth_place = $boy_birth_place;

        $lH = LocationHistory::where('state', $boy_birth_place)->first();

        $kundaliMatchingEnquiry->boy_lat = $lH?$lH->latDeg:config('vrdcms.delhi_lat');
        $kundaliMatchingEnquiry->boy_lon = $lH?$lH->longDeg:config('vrdcms.delhi_lon');
        $kundaliMatchingEnquiry->girl_name = $girl_name;
        $kundaliMatchingEnquiry->girl_birth_date = $girl_birth_date;
        $kundaliMatchingEnquiry->girl_birth_time = $girl_birth_time;
        $kundaliMatchingEnquiry->girl_birth_place = $girl_birth_place;

        $lH = LocationHistory::where('state', $girl_birth_place)->first();

        $kundaliMatchingEnquiry->girl_lat = $lH?$lH->latDeg:config('vrdcms.delhi_lat');
        $kundaliMatchingEnquiry->girl_lon = $lH?$lH->latDeg:config('vrdcms.delhi_lon');
        $kundaliMatchingEnquiry->lang = $lang;
        $kundaliMatchingEnquiry->save();
      }
      return response()->json(["suceess"=>true,"message"=>"Please wait. Kundali is generating.","redirectTo"=>route("kundalimatchingdetail", [$kundaliMatchingEnquiry->id])]);
    }

    public function kundaliMatchingDetail(Request $request){
        $breads[] = ['name' => 'Kundali Matching','is_last' => false, 'url'=> route('kundalimatching')];
        $breads[] = ['name' => 'detail','is_last' => true, 'url'=>'#'];
        $nav="kundalimatching";
        $today = date('Y-m-d');
      $page = Banner::where('status', 1)
      ->where('type', "kundali_matching")
      ->where(function($start_date) use ($today){
        $start_date->where('start_date', '<=', $today)
        ->orWhereNull('start_date')
        ->orWhere('start_date', '1970-01-01')
        ->orWhere('start_date', '0000-00-00');
      })
      ->where(function($end_date) use ($today){
        $end_date->where('end_date', '>=', $today)
        ->orWhereNull('end_date')
        ->orWhere('end_date', '1970-01-01')
        ->orWhere('end_date', '0000-00-00');
      })
      ->orderBy('order', 'asc')
      ->limit(1)
      ->get();
        $kundaliMatchingEnquiry = KundaliMatchingEnquiry::find($request->kundali_matching_enquiries_id);
        $data=[];
        $jsonResponse=null;

        if($kundaliMatchingEnquiry){
          $apiKey=fromSetting('astro_api_key');
          $boy_birth_date_time_seconds = strtotime($kundaliMatchingEnquiry->boy_birth_date." ".$kundaliMatchingEnquiry->boy_birth_time);
          $boy_birth_date = date("d/m/Y", $boy_birth_date_time_seconds);
          $boy_birth_time = date("H:i", $boy_birth_date_time_seconds);
          $boy_lat = $kundaliMatchingEnquiry->boy_lat;
          $boy_lon = $kundaliMatchingEnquiry->boy_lon;
          $girl_birth_date_time_seconds = strtotime($kundaliMatchingEnquiry->girl_birth_date." ".$kundaliMatchingEnquiry->girl_birth_time);
          $girl_birth_date = date("d/m/Y", $girl_birth_date_time_seconds);
          $girl_birth_time = date("H:i", $girl_birth_date_time_seconds);
          $girl_lat = $kundaliMatchingEnquiry->girl_lat;
          $girl_lon = $kundaliMatchingEnquiry->girl_lon;
          $tz = "5.5";
          $lang=$kundaliMatchingEnquiry->lang;
          $kundaliMatching = KundaliMatching::where("kundali_matching_enquiries_id", $kundaliMatchingEnquiry->id)->where("lang", $lang)->first();

          if(!$kundaliMatching){
            $url = "https://api.vedicastroapi.com/v3-json/matching/ashtakoot?api_key=$apiKey&boy_dob=$boy_birth_date&boy_tob=$boy_birth_time&boy_tz=$tz&boy_lat=$boy_lat&boy_lon=$boy_lon&girl_dob=$girl_birth_date&girl_tob=$girl_birth_time&girl_tz=$tz&girl_lat=$girl_lat&girl_lon=$girl_lon&lang=$lang";

            $response=Http::withoutVerifying()->get($url);
            $statusCode=$response->status();
            $jsonResponseMain=$response->json();

            $apiLog = new ApiLog();
            $apiLog->table_name = "kundali_matchings";
            $apiLog->table_id = 0;
            $apiLog->name = "kundali_matchings";
            $apiLog->request = $url;
            $apiLog->response = $response;
            $apiLog->api_status = 0;
            $apiLog->save();
            if($jsonResponseMain["status"] === 200){
              $kundaliMatching = new KundaliMatching();
              $kundaliMatching->kundali_matching_enquiries_id = $kundaliMatchingEnquiry->id;
              $kundaliMatching->lang = $lang;
              $kundaliMatching->response = $response;
              $kundaliMatching->save();

              $apiLog->table_id = $kundaliMatching->id;
              $apiLog->api_status = 1;
              $apiLog->save();

              if(isset($jsonResponseMain["response"])){
                $jsonResponse = $jsonResponseMain["response"];
              }
            }
          }else{
            $jsonResponse = json_decode($kundaliMatching->response, true);
            $jsonResponse = $jsonResponse["response"];
          }
        }else{
          return abort(404);
        }
        if(!is_array($jsonResponse)){
          Session::flash("error-toastr", "Some technical error occured. Please try again later.");
          return redirect()->route("kundalimatching");
        }

        $user = auth()->guard("web")->user();
        $user_type = "guest";
        $event = Session::get("view_content_free_kundali_matching_detail");
        if($event == null || ($event == "guest" && $user != null)){
          $timestamp = time();
          $body["event_name"] = "ViewContent";
          $body["event_time"] = $timestamp;
          $body["event_id"] = "free_kundali_matching_detail_{$timestamp}";
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
          $body["custom_data"]["content_id"] = $kundaliMatchingEnquiry->id;
          $body["custom_data"]["content_name"] = $kundaliMatchingEnquiry->boy_name." - ".$kundaliMatchingEnquiry->girl_name;
          $body["custom_data"]["content_type"] = "Kundali";
          $metaService = new Meta();
          $metaService->sendEvent($body);
          Session::put("view_content_free_kundali_matching_detail", $user_type);
        }
        return view(themeView().'pages.kundalimatching.detail', compact('nav','page', 'breads', 'jsonResponse', 'kundaliMatchingEnquiry'));
    }

    public function services(Request $request){
        $breads[] = ['name' => 'kundali','is_last' => true, 'url'=>'#'];
        $nav="service";
        return view(themeView().'pages.services', compact('nav','breads'));
    }

    public function panchang(Request $request){
        $nav="panchang";
        $data=[];
        $date=$request->date?:date("Y-m-d");
        $panchangeDate=strtotime($date);
        $apiKey=fromSetting('astro_api_key');
        $date=date("d/m/Y", strtotime($date));
        $lat=config('vrdcms.delhi_lat');
        $lon=config('vrdcms.delhi_lon');
        $city=$request->city??'';
        $lH=LocationHistory::where('state','like',"%$city%")->first();
        if($lH){
          $lat=$lH->latDeg;
          $lon=$lH->longDeg;
        }
        $lang=$request->lang?:"en";
        $panchang=Panchang::where("status",1)->where("date",$date)->first();
        if($panchang){
          if($lang=="hi"){
            $jsonResponse=json_decode($panchang->hindi,true);
          }elseif($panchang->english){
            $jsonResponse=json_decode($panchang->english,true);
          }
          if(isset($jsonResponse["response"])){
            $data=$jsonResponse["response"];
          }
        }
        if(count($data)==0){
          $url="https://api.vedicastroapi.com/v3-json/panchang/panchang?api_key=$apiKey&date=$date&lat=$lat&lon=$lon&tz=5.5&lang=$lang";
          $response=Http::withoutVerifying()->get($url);
          $statusCode=$response->status();
          $jsonResponse=$response->json();
          $apiLog=new ApiLog();
          $apiLog->table_name="panchangs";
          $apiLog->table_id=0;
          $apiLog->name="panchangs";
          $apiLog->request=$url;
          $apiLog->response=$response;
          $apiLog->api_status=0;
          $apiLog->save();
          if($jsonResponse["status"]===200){
            if(!$panchang){
              $panchang=new Panchang();
              $panchang->date=$date;
            }
            if($lang=="hi"){
              $panchang->hindi=$response;
            }else{
              $panchang->english=$response;
            }
            $panchang->status=1;
            $panchang->save();
            $apiLog->table_id=$panchang->id;
            $apiLog->api_status=1;
            $apiLog->save();
            if(isset($jsonResponse["response"])){
              $data=$jsonResponse["response"];
            }
          }
        }
        if (
            isset($data['advanced_details']['sun_rise']) &&
            isset($data['advanced_details']['sun_set'])
        ) {
            $sunrise = $data['advanced_details']['sun_rise'];
            $sunset  = $data['advanced_details']['sun_set'];
            $nextDate = date('Y-m-d', strtotime($request->date . ' +1 day'));
            $nextSunrise = date('H:i', strtotime($sunrise)); // usually same time next day
            $nextSunrise = $nextDate . ' ' . $nextSunrise;
            $dayHora = $this->generateDayHora($sunrise, $sunset, $request->date);
            $nightHora = $this->generateNightHora($sunset, $nextSunrise, $request->date);
            $dayChoghadiya   = $this->generateDayChoghadiya($sunrise, $sunset, $request->date);
            $nightChoghadiya = $this->generateNightChoghadiya($sunset, $nextSunrise, $request->date);
        }
        if($request->return_view && $request->return_view=="no"){
          return [
            "data" => $data,
            "dayHora" => $dayHora,
            "dayChoghadiya"=> $dayChoghadiya,
            "nightChoghadiya" => $nightChoghadiya
        ];
        }else{

          $today = date('Y-m-d');
          $banners = Banner::where('status', 1)
          ->where('type', "panchang")
          ->where(function($start_date) use ($today){
            $start_date->where('start_date', '<=', $today)
            ->orWhereNull('start_date')
            ->orWhere('start_date', '1970-01-01')
            ->orWhere('start_date', '0000-00-00');
          })
          ->where(function($end_date) use ($today){
            $end_date->where('end_date', '>=', $today)
            ->orWhereNull('end_date')
            ->orWhere('end_date', '1970-01-01')
            ->orWhere('end_date', '0000-00-00');
          })
          ->orderBy('order', 'asc')
          ->limit(1)
          ->get();

          $user = auth()->guard("web")->user();
          $user_type = "guest";
          $event = Session::get("view_content_panchang");
          if($event == null || ($event == "guest" && $user != null)){
            $timestamp = time();
            $body["event_name"] = "ViewContent";
            $body["event_time"] = $timestamp;
            $body["event_id"] = "panchang_{$timestamp}";
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
            $body["custom_data"]["content_id"] = $panchang ? $panchang->id : 0;
            $body["custom_data"]["content_name"] = $panchangeDate;
            $body["custom_data"]["content_type"] = "Panchang";
            $metaService = new Meta();
            $metaService->sendEvent($body);
            Session::put("view_content_panchang", $user_type);
          }
          $breads[]=["name"=>"Panchang","url"=>route("panchang")];
          return view(themeView().'pages.panchang', compact('nav','breads',"panchangeDate","data","dayHora","nightHora","dayChoghadiya","nightChoghadiya","lang", "banners",'lH'));
        }
        // return $data;
    }

    public function generateDayHora($sunrise, $sunset, $date){
        $planetOrder = ["Saturn","Jupiter","Mars","Sun","Venus","Mercury","Moon"];

        $dayPlanetMap = [
            "Sunday" => "Sun",
            "Monday" => "Moon",
            "Tuesday" => "Mars",
            "Wednesday" => "Mercury",
            "Thursday" => "Jupiter",
            "Friday" => "Venus",
            "Saturday" => "Saturn",
        ];

        $dayName = date("l", strtotime($date));
        $firstPlanet = $dayPlanetMap[$dayName];

        $startIndex = array_search($firstPlanet, $planetOrder);

        $sunriseTs = strtotime($sunrise);
        $sunsetTs = strtotime($sunset);

        $horaDuration = ($sunsetTs - $sunriseTs) / 12;

        $horaList = [];

        for($i=0; $i<12; $i++){
            $start = $sunriseTs + ($horaDuration * $i);
            $end   = $start + $horaDuration;

            $planet = $planetOrder[($startIndex + $i) % 7];

            $horaList[] = [
                "planet" => $planet,
                "time" => date("H:i", $start)." - ".date("H:i", $end)
            ];
        }

        return $horaList;
    }

    function generateNightHora($sunset, $nextSunrise, $date)
    {
        $planetOrder = ["Saturn","Jupiter","Mars","Sun","Venus","Mercury","Moon"];

        $dayPlanetMap = [
            "Sunday"    => "Sun",
            "Monday"    => "Moon",
            "Tuesday"   => "Mars",
            "Wednesday" => "Mercury",
            "Thursday"  => "Jupiter",
            "Friday"    => "Venus",
            "Saturday"  => "Saturn",
        ];

        // Day ke last Day Hora ka planet
        $dayName = date("l", strtotime($date));
        $dayStartIndex = array_search($dayPlanetMap[$dayName], $planetOrder);

        // Night ka first planet = day ke 12th hora ke baad wala
        $nightStartIndex = ($dayStartIndex + 12) % 7;

        $sunsetTs = strtotime($sunset);
        $nextSunriseTs = strtotime($nextSunrise);

        $horaDuration = ($nextSunriseTs - $sunsetTs) / 12;

        $horas = [];

        for ($i = 0; $i < 12; $i++) {
            $start = $sunsetTs + ($horaDuration * $i);
            $end   = $start + $horaDuration;

            $horas[] = [
                "planet" => $planetOrder[($nightStartIndex + $i) % 7],
                "time"   => date("H:i", $start) . " - " . date("H:i", $end)
            ];
        }

        return $horas;
    }

    // DaysChoghadiya
    function generateDayChoghadiya($sunrise, $sunset, $date){
    $dayName = date("l", strtotime($date));

    $map = [
      "Sunday"    => ["Udveg","Char","Labh","Amrit","Kaal","Shubh","Rog","Udveg"],
      "Monday"    => ["Amrit","Kaal","Shubh","Rog","Udveg","Char","Labh","Amrit"],
      "Tuesday"   => ["Rog","Udveg","Char","Labh","Amrit","Kaal","Shubh","Rog"],
      "Wednesday" => ["Labh","Amrit","Kaal","Shubh","Rog","Udveg","Char","Labh"],
      "Thursday"  => ["Shubh","Rog","Udveg","Char","Labh","Amrit","Kaal","Shubh"],
      "Friday"    => ["Char","Labh","Amrit","Kaal","Shubh","Rog","Udveg","Char"],
      "Saturday"  => ["Kaal","Shubh","Rog","Udveg","Char","Labh","Amrit","Kaal"],
    ];

    $start = strtotime($sunrise);
    $end   = strtotime($sunset);

    $slot = ($end - $start) / 8;

    $result = [];

    for($i=0; $i<8; $i++){
        $from = $start + ($slot * $i);
        $to   = $from + $slot;

        $result[] = [
            "name" => $map[$dayName][$i],
            "time" => date("h:i A", $from)." - ".date("h:i A", $to),
            "type" => $this->getChoghadiyaType($map[$dayName][$i])
        ];
    }
    return $result;
}

// NightChogadiya
function generateNightChoghadiya($sunset, $nextSunrise, $date){
    $dayName = date("l", strtotime($date));

    $map = [
      "Sunday"    => ["Shubh","Amrit","Char","Rog","Kaal","Labh","Udveg","Shubh"],
      "Monday"    => ["Char","Rog","Kaal","Labh","Udveg","Shubh","Amrit","Char"],
      "Tuesday"   => ["Labh","Udveg","Shubh","Amrit","Char","Rog","Kaal","Labh"],
      "Wednesday" => ["Udveg","Shubh","Amrit","Char","Rog","Kaal","Labh","Udveg"],
      "Thursday"  => ["Amrit","Char","Rog","Kaal","Labh","Udveg","Shubh","Amrit"],
      "Friday"    => ["Rog","Kaal","Labh","Udveg","Shubh","Amrit","Char","Rog"],
      "Saturday"  => ["Kaal","Labh","Udveg","Shubh","Amrit","Char","Rog","Kaal"],
     ];

    $start = strtotime($sunset);
    $end   = strtotime($nextSunrise);

    $slot = ($end - $start) / 8;

    $result = [];

    for($i=0; $i<8; $i++){
        $from = $start + ($slot * $i);
        $to   = $from + $slot;

        $result[] = [
            "name" => $map[$dayName][$i],
            "time" => date("h:i A", $from)." - ".date("h:i A", $to),
            "type" => $this->getChoghadiyaType($map[$dayName][$i])
        ];
    }
    return $result;
}

  function getChoghadiyaType($name){
      if(in_array($name, ["Amrit","Shubh","Labh"])) return "Auspicious";
      if(in_array($name, ["Rog","Kaal","Udveg"])) return "Inauspicious";
      return "Good";
  }


    public function newsletterSubscribe(Request $request)
    {
      $request->validate([
        'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
        ]);
        $previous = NewsletterSubscriber::where('email', $request->email)->first();
        if(!$previous){
            $newItem = new NewsletterSubscriber();
            $newItem->name = $request->name;
            $newItem->phone = $request->phone;
            $newItem->email = $request->email;
            $newItem->save();
            $newItem->sendEmail();
        }else {
            $previous->name = $request->name;
            $previous->status = 1;
            $previous->save();
            $previous->sendEmail();
        }
        return response()->json(['message'=> 'Successfully Subscribed!'],200);
    }

    public function aboutus(Request $request){
        return view(themeView().'aboutus.index');
    }


    public function nakshtra(Request $request){
      $data = NgSoftware::where('slug', $request->slug)->first();
      if(!$data) abort(404);
      $breads[] = ['name' => $data->title,'is_last' => true, 'url'=>'#'];
      $onPage = OnPage::where('table', 'template_faqs')->where('page', 3)->pluck('table_id')->toArray();
      $faqs = TemplateFaq::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(5)->get();
      $onPage = OnPage::where('table', 'testimonials')->where('page', 5)->pluck('table_id')->toArray();
      $textTestimonials = Testimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
      $onPage = OnPage::where('table', 'video_testimonials')->where('page', 5)->pluck('table_id')->toArray();
      $videoTestimonials = VideoTestimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
      $videos = DemoVideo::where('status', 1)->where('page_id', $data->id)->orderBy('order', 'ASC')->get();
      return view(themeView().'cms_two.nakshatra', compact('breads', 'data', 'faqs', 'textTestimonials', 'videoTestimonials', 'videos'));
    }

    public function astrology(Request $request){
      $data = NgSoftware::where('slug', $request->slug)->first();
      if(!$data) abort(404);
      $breads[] = ['name' => $data->title,'is_last' => true, 'url'=>'#'];
      $onPage = OnPage::where('table', 'template_faqs')->where('page', 5)->pluck('table_id')->toArray();
      $faqs = TemplateFaq::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(5)->get();
      $onPage = OnPage::where('table', 'testimonials')->where('page', 7)->pluck('table_id')->toArray();
      $textTestimonials = Testimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
      $onPage = OnPage::where('table', 'video_testimonials')->where('page', 7)->pluck('table_id')->toArray();
      $videoTestimonials = VideoTestimonial::whereIn('id', $onPage)->where('status', 1)->orderBy('order', 'asc')->limit(8)->get();
      $videos = DemoVideo::where('status', 1)->where('page_id', $data->id)->orderBy('order', 'ASC')->get();
      return view(themeView().'cms_two.astrology', compact('breads', 'data', 'faqs', 'textTestimonials', 'videoTestimonials', 'videos'));
    }

    public function submitEnquiry(Request $request){
      $validdation = [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
        // 'language' => 'required',
        // 'country' => 'required|numeric',
        // 'states' => 'required|numeric',
        // 'cities' => 'required|numeric',
        // 'pincode' => 'required|numeric',
        // 'service_type' => 'required',
    ];
      $request->validate($validdation);
      $user_id = 0;
      $user_type = 'guest';
      if(auth()->guard("web")->check()){
        $user_id = auth()->guard('web')->user()->id;
        $user_type = 'user';
      }elseif(auth()->guard("astrologer")->check()){
        $user_id = auth()->guard('astrologer')->user()->id;
        $user_type = 'astrologer';
      }
      $country = \DB::table('master_countries')->where('id', $request->country)->first();
      $state = \DB::table('master_states')->where('id', $request->states)->first();
      $city = \DB::table('master_cities')->where('id', $request->cities)->first();
      $enquiry = new EnquiryCmsTwo();
      $enquiry->user_id = $user_id;
      $enquiry->user_type = $user_type;
      $enquiry->name = $request->name;
      $enquiry->email = $request->email;
      $enquiry->phone = $request->phone;
      $enquiry->language = $request->language;
      $enquiry->country = $country && $country->name ? $country->name : $request->country;
      $enquiry->state = $state && $state->name ? $state->name : $request->states;
      $enquiry->city = $city && $city->name ? $city->name : $request->cities;
      $enquiry->pincode = $request->pincode?:'';
      $enquiry->service_type = $request->service_type?:'';
      $enquiry->message = $request->message;
      $enquiry->save();
      Notification::route('mail', [
        $enquiry->email => $enquiry->name, //user
        'customercare@iiag.co.in' => 'Customer Care', //admin
      ])->notify(new EnquiryNotification($enquiry, 'software'));

      $fields['campaign type'] = 'NG Software Enquiry';
      $fields['name'] = $enquiry->name;
      $fields['email'] = $enquiry->email;
      $fields['phone'] = $enquiry->phone;
      $fields['city'] = $enquiry->location;
      $body['fields'] = $fields;
      sendDataToCrm($body);

      return response()->json([
          'status' => 'success',
          'message' => 'Thank You For Contacting Us.'
      ]);
    }

    public function submitAstrologyEnquiry(Request $request){
      $validdation = [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
        // 'country' => 'required|numeric',
        // 'states' => 'required|numeric',
        // 'cities' => 'required|numeric',
        // 'pincode' => 'required|numeric',
        // 'services' => 'required',
        // 'service_type' => 'required',
        // 'file' => 'required',
        // 'dob_astrology' => 'required',
        // 'tob_astrology' => 'required',
    ];
      $request->validate($validdation);
      $user_id = 0;
      $user_type = '';
      if(auth()->guard("web")->check()){
        $user_id = auth()->guard('web')->user()->id;
        $user_type = 'user';
      }elseif(auth()->guard("astrologer")->check()){
        $user_id = auth()->guard('astrologer')->user()->id;
        $user_type = 'astrologer';
      }
      $country = \DB::table('master_countries')->where('id', $request->country)->first();
      $state = \DB::table('master_states')->where('id', $request->states)->first();
      $city = \DB::table('master_cities')->where('id', $request->cities)->first();
      $service = Vastu::find($request->services);
      $fileName = '';
      if($request->hasFile('file')){
        $filePath = public_path('enquiry');
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true);
        }
        $filePath = public_path('enquiry/astrology');
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true);
        }
        $time = time();
        $fileName = $time.'.'.$request->file->getClientOriginalExtension();
        $request->file->move($filePath, $fileName);
      }
      $enquiry = new EnquiryAstrology();
      $enquiry->user_id = $user_id;
      $enquiry->user_type = $user_type;
      $enquiry->name = $request->name;
      $enquiry->email = $request->email;
      $enquiry->phone = $request->phone;
      $enquiry->dob = date('Y-m-d', strtotime($request->dob_astrology));
      $enquiry->tob = $request->tob_astrology;
      $enquiry->birth_place = $request->birth_place;
      $enquiry->gender = $request->gender;
      // $enquiry->country = $country && $country->name ? $country->name : $request->country;
      // $enquiry->state = $state && $state->name ? $state->name : $request->states;
      // $enquiry->city = $city && $city->name ? $city->name : $request->cities;
      // $enquiry->pincode = $request->pincode?:'';
      $enquiry->service_type = $request->service_type?:'';
      $enquiry->services = implode(' ', explode('_', $request->services));
      $enquiry->file = $fileName ? '/enquiry/astrology/'.$fileName : '';
      $enquiry->message = $request->message;
      $enquiry->save();
      Notification::route('mail', [
        $enquiry->email => $enquiry->name, //user
        'customercare@iiag.co.in' => 'Customer Care', //admin
      ])->notify(new EnquiryNotification($enquiry, 'astrology'));

      $fields['campaign type'] = 'Astrology Enquiry';
      $fields['name'] = $enquiry->name;
      $fields['email'] = $enquiry->email;
      $fields['phone'] = $enquiry->phone;
      $fields['city'] = $enquiry->location;
      $body['fields'] = $fields;
      sendDataToCrm($body);

      return response()->json([
          'status' => 'success',
          'message' => 'Thank You For Contacting Us.'
      ]);
    }
  public function todaySpecial (Request $request){
    $breads = [
        ['name'=> 'Today Special', 'is_last'=>true]
    ];
    $today = date('Y-m-d');
    $banners = Banner::where('status', 1)
      ->where('type', 'today')
      ->where(function($start_date) use ($today){
        $start_date->where('start_date', '<=', $today)
        ->orWhereNull('start_date')
        ->orWhere('start_date', '1970-01-01')
        ->orWhere('start_date', '0000-00-00');
      })
      ->where(function($end_date) use ($today){
        $end_date->where('end_date', '>=', $today)
        ->orWhereNull('end_date')
        ->orWhere('end_date', '1970-01-01')
        ->orWhere('end_date', '0000-00-00');
      })->get();
    $thoughtoftheday = Panchang::where('status', 1)->where('date', date('Y-m-d'))->first();
    return view(themeView().'pages.todayspecial', compact('banners', 'breads', 'thoughtoftheday'));
  }

  public function verifyUrgentPayment($user_id, $user_type, $table_name, $table_id, $order_id, Request $request){
      $is_razorpay_payment_id = false;
      $payment_response = '';
      $success = true;
      $error = "Payment Failed!";
      $payment_response = $request->all();
      $user = null;
      if($user_type == 'user'){
        $user = auth()->guard('web')->user();
      }elseif($user_type == 'astrologer'){
        $user = auth()->guard('astrologer')->user();
      }

      if(empty($request->razorpay_payment_id) === false){
        $is_razorpay_payment_id = true;
        $api = new Api(fromSetting("razorpay_api_key"), fromSetting("razorpay_api_secret_key"));
        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];
            $api->utility->verifyPaymentSignature($attributes);
            $orderResponse = $api->payment->fetch($request->razorpay_payment_id);
            $payment_response = array_merge($payment_response, $orderResponse->toArray());
        }catch(SignatureVerificationError $e){
            $success = false;
            $payment_response = 'Razorpay Error : ' . $e->getMessage();
        }
      }
      if(!$request->razorpay_payment_id){
        return abort(404);
      }

      if($table_name == 'user_recharge_histories'){
        $table = UserRechargeHistory::find($table_id);
        $table->payment_status = $success?'success':'failed';
        $table->payment_id = isset($payment_response['razorpay_payment_id'])?$payment_response['razorpay_payment_id']:'';
        $table->payment_method = isset($payment_response['method'])?$payment_response['method']:'';
        $table->payment_date_time = isset($payment_response['created_at'])?date('Y-m-d H:i:s', $payment_response['created_at']):date('Y-m-d H:i:s');
        $table->payment_response = json_encode($payment_response);
        $table->balance = 0;
        $table->save();
        $appointment = AstrologerAppointment::find($table->appointment_id);
        if($success){
          $consultation_counter = new ConsultationCounter();
          $consultation_counter->user_id = $appointment->user_id;
          $consultation_counter->user_type = 'user';
          $consultation_counter->appointment_id = $appointment->id;
          $consultation_counter->counter = 0;
          $consultation_counter->save();
          $consultation_counter = new ConsultationCounter();
          $consultation_counter->user_id = $appointment->astrologer_id;
          $consultation_counter->user_type = 'astrologer';
          $consultation_counter->appointment_id = $appointment->id;
          $consultation_counter->counter = 0;
          $consultation_counter->save();
          $callHistory = CallHistory::where('appointment_id', $appointment->id)->first();
          $astrologer = Astrologer::find($appointment->to_astrologer_id);
          $appointment->status = 'booked';
          $appointment->user_status = 'booked';
          $appointment->save();
          $type = '';
          $url = '';
          $scheduleType = '';
          if($appointment->package == 1){
            $type = 'video';
            $url = url('consult/'.$appointment->id.'/video?roomID='.$appointment->id.$user->id.$appointment->to_astrologer_id);
            $link = '';
            $this->sendNotification($user, $astrologer, $appointment, 'video', $link, 'user');
            $this->sendNotification($user, $astrologer, $appointment, 'video', $link, 'astrologer');
          }elseif($appointment->package == 3){
            $type = 'call';
            $url = $callHistory->id;
            $link = '';
            $this->sendNotification($user, $astrologer, $appointment, 'call', $link, 'user');
            $this->sendNotification($user, $astrologer, $appointment, 'call', $link, 'astrologer');
          }elseif($appointment->package == 2){
            $type = 'chat';
            $url = url('consult/'.$appointment->id.'/chat');
            $link = '';
            $this->sendNotification($user, $astrologer, $appointment, 'chat', $link, 'user');
            $this->sendNotification($user, $astrologer, $appointment, 'chat', $link, 'astrologer');
          }
          if($appointment->scheduled_for == 0){
            $scheduleType = 'now';
          }elseif($appointment->scheduled_for == 0){
            $scheduleType = 'later';
          }
          $user->wallet += $table->taktime_value;
          $user->save();
          $user->wallet -= $appointment->total_price;
          $user->save();
          $message = sprintf(config('vrdcms.mobile_recharge'), $table->taktime_value, $user->wallet);
          // SendSms::dispatch($user->phone, $message, 'mobile_recharge');
          $invoiceController = new InvoiceController();
          $data['id'] = $table->id;
          $data['name'] = $table->customer_name;
          $data['email'] = $table->customer_email;
          $data['phone'] = $table->phone;
          $data['address'] = $table->address;
          $data['pincode'] = $table->pincode;
          $data['city'] = $table->city;
          $data['state'] = $table->state;
          $data['country'] = $table->country;
          $data['billing_full_name'] = $table->billing_full_name;
          $data['billing_email'] = $table->billing_email;
          $data['billing_phone_number'] = $table->billing_phone_number;
          $data['billing_address'] = $table->billing_address;
          $data['billing_country'] = $table->billing_country;
          $data['billing_state'] = $table->billing_state;
          $data['billing_city'] = $table->billing_city;
          $data['billing_zipcode'] = $table->billing_zipcode;
          $data['total_quantity'] = 1;
          $data['sgst'] = $table->sgst_amount;
          $data['cgst'] = $table->cgst_amount;
          $data['igst'] = $table->igst_amount;
          $data['subtotal_amount'] = $table->amount; //base
          $data['amount'] = $table->amount; //sale
          $data['total_amount'] = $table->total_amount; //payable
          $data['discount_amount'] = 0;
          $data['coupon_amount'] = $table->coupon_amount;
          $data['shipping_amount'] = 0;
          $data['order_id'] = $table->my_txn_id;
          $data['payment_id'] = $table->payment_id;
          $data['payment_method'] = $table->payment_method;
          $data['payment_date_time'] = $table->payment_date_time;

          $item_datas['name'] = 'Recharge';
          $item_datas['description'] = 'Recharge';
          $item_datas['quantity'] = 1;
          $item_datas['amount'] = $data['amount']; //sale
          $item_datas['base_amount'] = $data['amount']; //base
          $item_datas['total_amount'] = $table->total_amount;
          $item_datas['tax_type'] = $table->tax_type;
          $item_datas['igst_percentage'] = $table->igst;
          $item_datas['igst_amount'] = $table->igst_amount;
          $item_datas['cgst_percentage'] = $table->cgst;
          $item_datas['cgst_amount'] = $table->cgst_amount;
          $item_datas['sgst_percentage'] = $table->sgst;
          $item_datas['sgst_amount'] = $table->sgst_amount;
          $item_datas['hsn_code'] = $table->hsn_code;
          $item_datas['discount_amount'] = 0;
          $item_datas['coupon_amount'] = $table->coupon_amount;
          $item_datas['shipping_amount'] = 0;
          $item_data = [];
          array_push($item_data, $item_datas);
          $pdfUrl = $invoiceController->index($user, $user_type, $data, 'recharge', $item_data);
          RechargeInvoiceMailJob::dispatch($table, $pdfUrl);
          $notification['user_id'] = $user->id;
          $notification['astrologer_id'] = 0;
          $notification['message'] = 'Thank You! Your recharge is successfully. of Rs. '.$table->total_amount.'.';
          $notification['link'] = route('user.recharge-history');
          $notification['for'] = 1;
          $notificationController = new NotificationController();
          $notificationController->create($notification);

          $fields['campaign type'] = 'Recharge';
          $fields['name'] = $table->billing_full_name;
          $fields['email'] = $table->billing_email;
          $fields['phone'] = $table->billing_phone_number;
          $fields['city'] = $table->billing_location;
          $fields['description'] = $table->my_txn_id;
          $body['fields'] = $fields;
          sendDataToCrm($body);

          ConsultationJob::dispatch($appointment);
          AstrologerConsultationJob::dispatch($appointment);
          // $user->successfullyRecharge($message);
          $message = 'Appointment scheduled successfully';
          $routeUrl = route('astrologers.publicview', [$astrologer->slug]).'?scheduleType='.$scheduleType.'&consultType='.$type.'&consultUrl='.$url.'&consultMessage='.$message;
          return redirect($routeUrl);
        }
        RechargeFailedJob::dispatch($table);
        // ConsultationFailedJob::dispatch($appointment);
        return redirect()->route('astrologers.publicview').'?type=failed';
      }
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

  public function tags(Request $request){
    $tag = Tag::where('slug', $request->slug)->first();
    if(!$tag) abort();
    return view(themeView().'tag.index', compact('tag'));
  }

    public function thankYou(Request $request){
      $message = Session::get('message');
      return view(themeView().'pages.thank_you', compact('message'));
    }

  public function allNotification(Request $request){
    $user = auth()->guard('web')->user();
    $for = [0, 1];
    $user_type = 'user';
    if(!$user){
      $user = auth()->guard('astrologer')->user();
      $for = [0, 2];
      $user_type = 'astrologer';
    }
    if(!$user){
      return redirect()->route('home');
    }
    $userIdIn = [$user->id, 0];
    $notification = [];
    if($user_type=='user'){
      $notification = NotificationModel::whereIn('user_id', $userIdIn)->where('created_at','>=', $user->created_at)->whereIn('for', $for)->orderBy('created_at', 'desc')->get();
      DB::table('notifications')->whereIn('user_id', $userIdIn)->where('created_at','>=', $user->created_at)->whereIn('for', $for)->update(['view' => 1]);
    }else{
      $notification = NotificationModel::whereIn('astrologer_id', $userIdIn)->where('created_at','>=', $user->created_at)->whereIn('for', $for)->orderBy('created_at', 'desc')->get();
      DB::table('notifications')->whereIn('astrologer_id', $userIdIn)->where('created_at','>=', $user->created_at)->whereIn('for', $for)->update(['view' => 1]);
    }
    $hideNotifications = true;
    return view(themeView().'pages.notification', compact('notification', 'hideNotifications'));
  }

  public function redirectToNotificationLin(Request $request){
    $notification = NotificationModel::find($request->notification_id);
    $notification->view = 1;
    $notification->save();
    return redirect($notification->link);
  }

  public function UserNotification(Request $request){
    $this->validate($request, [
      'notify_to' => 'required|in:user,astrologer',
      'type' => 'required',
      'description' => 'required',
    ]);
    $user = User::find($request->user_id);
    $astrologer = Astrologer::find($request->astrologer_id);
    $userNotification = UserNotification::where('user_id', $request->user_id)
    ->where('astrologer_id', $request->astrologer_id)
    ->where('notify_to', $request->notify_to)
    ->where('type', $request->type)
    ->where('is_sent', 0)
    ->first();
    if(!$userNotification){
      $userNotification = new UserNotification();
      $userNotification->user_id = $user ? $user->id : 0;
      $userNotification->user_email = $user ? $user->email : '';
      $userNotification->astrologer_id = $astrologer ? $astrologer->id : 0;
      $userNotification->astrologer_email = $astrologer ? $astrologer->email : '';
      $userNotification->notify_to = $request->notify_to;
      $userNotification->type = $request->type;
      $userNotification->description = $request->description;
      $userNotification->save();
    }
    return response()->json(['message' => $request->message], 200);
  }

  public function feedback(){
    $breads[]=["name"=>"Feedback","url"=>url()->current()];
    $countries=DB::table('master_countries')->where("id", 101)->get();
    return view(themeView().'pages.feedback',compact("breads","countries"));
  }

  public function feedbackSubmit(Request $request){
    $request->validate([
      'name' => 'required|max:50',
      'email' => 'required|email',
      'mobile_number' => 'required|digits:10',
      'message' => 'required',
    ]);
    $feedback = new ContactEnquiry;
    $feedback->name = $request->name;
    $feedback->email = $request->email;
    $feedback->phone = $request->mobile_number;
    $feedback->message = $request->message;
    $feedback->type = $request->type;
    $feedback->save();
    return response()->json(['success' => 'Submitted Successfully'],200);
  }

}
