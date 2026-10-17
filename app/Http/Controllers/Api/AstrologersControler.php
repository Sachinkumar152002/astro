<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\{Controller, PushNotificationController, NotificationController};
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Model\{Astrologer,Service};
use App\Model\Language;
use App\Model\CallHistory;
use App\Model\Chat;
use App\Model\AstrologerOfflineReport;
use App\Model\AstrologerAppointmentPackage;
use App\Model\AstrologerAppointment;
use App\Model\AstrologerReview;
use App\Model\AstrologerTimeTable;
use App\Model\Testimonial;
use App\Model\AstrologerService;
use App\Model\AstrologerLanguage;
use App\AstrologerUserConsultacy;
use Carbon\Carbon;
use App\User;
use App\Model\TempUser;
use App\Model\Setting;
use App\Model\PayoutPackage;
use App\Model\{WalletTransition, AstrologerLive,SendGift, Gift, AiAstrologerAppointment};
use App\Jobs\{ConsultationJob, AstrologerConsultationJob};
use App\Services\ExotelService;
use App\Http\Controllers\ExotelController;

class AstrologersControler extends Controller
{
    public function types(Request $request){
      $data = [ 'Astrologer'];
      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function experiences(Request $request){
      $data = [
        ["name"=>"0-5", "value"=>"0-5"],
        ["name"=>"5-10", "value"=>"5-10"],
        ["name"=>"15+", "value"=>"15+"],
      ];
      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function prices(Request $request){
      $data = [
        ["name"=>"0-100", "value"=>"0-100"],
        ["name"=>"100-200", "value"=>"100-200"],
        ["name"=>"200-300", "value"=>"200-300"],
        ["name"=>"300+", "value"=>"300+"],
      ];
      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function ratings(Request $request){
      $data = [
        ["name"=>"4+", "value"=>"4+"],
        ["name"=>"3", "value"=>"3"],
        ["name"=>"2", "value"=>"2"]
      ];
      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function services(Request $request){
      $limit = $request->limit?:0;
      $query = Service::where('id', '!=', 0);
      if($limit){
        $query->limit($limit);
      }
      $results = $query->get();
      $data = $results->map(function ($item){
        return [
          'value' => $item->id,
          'icon' => $item->icon?$item->icon->image(50, 50):no_image(),
          'slug' => $item->slug,
          'name' => $item->name,
        ];
      });
      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function languages(Request $request){
      $data = Language::all()->map(function ($item){
        return [
          'name' => $item->name,
          'value' => $item->id,
        ];
      });
      return response()->json(["success"=>true, "data"=>$data], 200);
    }

    public function sortBy(Request $request){
      $sortByData = [
        [
          'label'=>"A To Z",
          'value'=>'a_to_z',
        ],
        [
          'label'=>"What's New",
          'value'=>'whats_new',
        ],
        [
          'label'=>'Price Low To High',
          'value'=>'price_low_to_high',
        ],
        [
          'label'=>'Price High To Low',
          'value'=>'price_high_to_low',
        ],
      ];
       return response()->json([
         'success' => true,
          'data' => $sortByData,
       ], 200);
    }

    public function list(Request $request){
      $astrologerType = $request->astroologerType ?: "Astrologer";
      $mode = $request->mode ?: "chat"; // chat and call
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

      if($request->has('except') && $request->except){
        $query->where('slug', '!=', $request->except);
      }

      // Filter by name
      if($request->has('name') && $request->name){
        $query->where('display_name', 'LIKE', "%$request->name%");
      }

      // Filter by city
      if($request->has('city') && $request->city){
        $query->where('city', 'LIKE', "%$request->city%");
      }

      // Filter by services
      if($request->has('services') && count($request->services)){
        $astrologerIds = AstrologerService::whereIn('service_id', $request->services)->pluck('astrologer_id');
        $query->whereIn('id', $astrologerIds);
      }

      // Filter by experience
      if($request->has('experiences') && count($request->experiences)){
        $query->where(function ($query) use ($request){
          foreach ($request->experiences as $experienceRange){
            if($experienceRange == '15+'){
              $query->orWhereRaw('YEAR(CURDATE()) - job_start_year >= ?', [15]);
            }else{
              $range = explode('-', $experienceRange);
              if (count($range) == 2){
                $minYears = (int)$range[0];
                $maxYears = (int)$range[1];
                $query->orWhereRaw('YEAR(CURDATE()) - job_start_year BETWEEN ? AND ?', [$minYears, $maxYears]);
              }
            }
          }
        });
      }

      // Filter by price
      if($request->has('prices') && count($request->prices)){
        $query->where(function ($query) use ($request){
          foreach ($request->prices as $price){
            if($price == '300+'){
              $query->orWhere('call_price', '>', 300);
            }else{
              list($min, $max) = explode('-', $price);
              $query->orWhereBetween('call_price', [(int)$min, (int)$max]);
            }
          }
        });
      }

      // Filter by rating
      if($request->has('ratings') && count($request->ratings)){
        $ratings = $request->ratings;
        $query->where(function($q) use ($ratings){
          if(in_array('4+', $ratings)){
            $q->orWhere('rating', '>=', 4);
          }
          $filteredRatings = array_diff($ratings, ['4+']);
          if(!empty($filteredRatings)){
            $q->orWhereIn('rating', $filteredRatings);
          }
        });
      }

      // Filter by language
      if($request->has('languages') && count($request->languages)){
        $astrologerIds = AstrologerLanguage::whereIn('language_id', $request->languages)->pluck('astrologer_id');
        $query->whereIn('id', $astrologerIds);
      }

      if($request->has('sort_by') && $request->sort_by){
        if($request->sort_by == 'whats_new'){
          $query->orderBy('created_at', 'DESC');
        }elseif($request->sort_by == 'price_low_to_high'){
          $query->orderByRaw('price_order ASC');
        }elseif($request->sort_by == 'price_high_to_low'){
          $query->orderByRaw('price_order DESC');
        }elseif($request->sort_by == 'a_to_z'){
          $query->orderBy('display_name', 'ASC');
        }elseif($request->sort_by == 'status'){
          $query->orderByRaw("
            CASE
              WHEN `call` = 3 OR `chat` = 3 OR `video` = 3 THEN 2
              WHEN `call` = 1 OR `chat` = 1 OR `video` = 1 THEN 1
              WHEN `call` = 2 OR `chat` = 2 OR `video` = 2 THEN 3
              ELSE 4
            END ASC
          ");
        }
      }else{
        $query->orderByRaw('(SELECT `order` FROM payout_packages WHERE payout_packages.id = astrologers.payout_package_id) ASC');
      }

      $astrologers = $query->selectRaw('*, IF(sp_from_date IS NOT NULL AND sp_from_date <= CURDATE() AND sp_to_date >= CURDATE(), call_special_price, call_price) as price_order')->paginate(12);
      // $astrologers = $query->paginate(12);
      $data = [
        'list'=>[],
        'count' => 0,
        'per_page' => 0,
        'has_more_pages' => false,
        'current_page' => 0,
        'previous_page_url' => null,
        'next_page_url' => null,
        'last_page_url' => null,
      ];
      $user = User::where('api_token', $request->api_token)->first();
      $user_id = $user ? $user->id : 0;
      $checCurrentkAiAstrologerAppointment = $user_id && $astrologerType = "AI" ? true : false;
      $currentAppointmentsArray = [];
      foreach ($astrologers as $astrologer){
        $takeBooking = 0;
        $bookNow = 0;
        $timetables = $astrologer->timetable;
        if($timetables->count()){
          $takeBooking = 1;
        }
        if($astrologer->call==1 || $astrologer->video==1 || $astrologer->chat==1){
          $bookNow = 1;
        }
        $waitingTime = "";
        $currentTime = strtotime(date('Y-m-d H:i:s'));
        $consultation_id = 0;
        if($checCurrentkAiAstrologerAppointment){
          $aiAstrologerAppointment = AiAstrologerAppointment::where('astrologer_id', $astrologer->id)->where('user_id', $user_id)->where('paid', 0)->first();
          $consultation_id = $aiAstrologerAppointment ? $aiAstrologerAppointment->id : 0;
        }
        $item['id'] = $astrologer->id;
        $item['price_order'] = $astrologer->price_order;
        $item['blue_tick'] = $astrologer->blue_tick?true:false;
        $item['type'] = strtolower($astrologer->type);
        $item['image'] = $astrologer->image ? $astrologer->image->image(100, 100) : no_image();
        $item['rating'] = $astrologer->rating ? floatval(sprintf('%.1f', $astrologer->rating)) : 1.1;
        $item['slug'] = $astrologer->slug;
        // $item['name'] = $astrologer->display_name;
        $item['name'] = $astrologer->display_name;
        $status = 'offline';
        if($astrologer->chat == 3 || $astrologer->call == 3 || $astrologer->video == 3){
          $status = 'bussy';
        }elseif($astrologer->chat == 1 || $astrologer->call == 1 || $astrologer->video == 1){
          $status = 'online';
        }
        $video_status = 'hide';
        if($astrologer->video == 1){
          $video_status = 'online';
        }elseif($astrologer->video == 2){
          $video_status = 'offline';
        }elseif($astrologer->video == 3){
          $video_status = 'bussy';
        }
        $call_status = 'hide';
        if($astrologer->call == 1){
          $call_status = 'online';
        }elseif($astrologer->call == 2){
          $call_status = 'offline';
        }elseif($astrologer->call == 3){
          $call_status = 'bussy';
        }
        $chat_status = 'hide';
        if($astrologer->chat == 1){
          $chat_status = 'online';
        }elseif($astrologer->chat == 2){
          $chat_status = 'offline';
        }elseif($astrologer->chat == 3){
          $chat_status = 'bussy';
        }
        $currentAppointment = $astrologer->currentAppointment();
        $withInThirtyMinutes = $astrologer->withInThirtyMinutes();
        if($currentAppointment || $withInThirtyMinutes){
        // if($currentAppointment){
          $bookNow = 0;
          // $currentAppointmentsArray[] = ["astrologer"=> $astrologer->id, "currentAppointment" => $currentAppointment->id];
          if(!$currentAppointment){
            $currentAppointment = $withInThirtyMinutes;
          }
          $scheduledTimestamp = strtotime($currentAppointment->scheduled_date . ' ' . $currentAppointment->max_time.'+1minutes');
          $diffInSeconds = $scheduledTimestamp-$currentTime;
          if($diffInSeconds > 0){
            $minutes = floor($diffInSeconds / 60);
            $seconds = $diffInSeconds % 60;
            $waitingTime = "{$minutes}:{$seconds} m";
            $status = 'bussy';
            $video_status = 'bussy';
            $call_status = 'bussy';
            $chat_status = 'bussy';
          }
        }
        $item['status'] = $status;
        $item['chat_status'] = $chat_status;
        $item['call_status'] = $call_status;
        $item['video_status'] = $video_status;
        $item['consultation'] = [
          "id" => $consultation_id,
          'now' => $bookNow ? true : false,
          // 'later' => $takeBooking ? true : false,
          'later' => false,
          'wait' => $waitingTime,
        ];
        $item['languages'] = [];
        foreach($astrologer->languages as $key => $language){
          if($language = $language->language){
            array_push($item['languages'], $language->name);
          }
        }
        $item['services'] = [];
        foreach($astrologer->services as $key => $service){
          if($service = $service->service){
            array_push($item['services'], $service->name);
          }
        }
        $item['experience'] = (int) $astrologer->job_start_year ? date('Y') - $astrologer->job_start_year : 0;
        $item['chat_price'] = (float) $astrologer->chat_price;
        $item['call_price'] = (float) $astrologer->call_price;
        $item['video_price'] = (float) $astrologer->price;
        $item['chat_special_price'] = (double) 0.00;
        $item['call_special_price'] = (float) 0.00;
        $item['video_special_price'] = (float) 0.00;
        if($astrologer->chat_special_price > 0 && $astrologer->sp_from_date && (date('Y-m-d')>=$astrologer->sp_from_date) && (date('Y-m-d')<=$astrologer->sp_to_date)){
          $item['chat_special_price'] = (float) $astrologer->chat_special_price;
        }
        if($astrologer->call_special_price > 0 && $astrologer->sp_from_date && (date('Y-m-d')>=$astrologer->sp_from_date) && (date('Y-m-d')<=$astrologer->sp_to_date)){
          $item['call_special_price'] = (float) $astrologer->call_special_price;
        }
        if($astrologer->special_price > 0 && $astrologer->sp_from_date && (date('Y-m-d')>=$astrologer->sp_from_date) && (date('Y-m-d')<=$astrologer->sp_to_date)){
          $item['video_special_price'] = (float) $astrologer->special_price;
        }
        array_push($data['list'], $item);
      }
      $data['count'] = $astrologers->total();
      $data['per_page'] = $astrologers->perPage();
      $data['has_more_pages'] = $astrologers->hasMorePages();
      $data['current_page'] = $astrologers->currentPage();
      $data['previous_page_url'] = $astrologers->previousPageUrl();
      $data['next_page_url'] = $astrologers->nextPageUrl();
      $data['last_page_url'] = $astrologers->url($astrologers->lastPage());
      return response()->json([
        'success' => true,
        'data' => $data,
        // 'currentAppointmentsArray' => $currentAppointmentsArray,
      ], 200);
    }

    public function detail(Request $request){
      $this->validate($request, [
        'slug'=>'required',
      ]);
      $astrologer = Astrologer::where('status', 1)->where('is_verified', 1)->where('slug', $request->slug)->first();
      $takeBooking = 0;
      $bookNow = 0;
      $data = [];
      if($astrologer){
        $timetables = $astrologer->timetable;
        if($timetables->count()){
          $takeBooking = 1;
        }
        if($astrologer->call==1 || $astrologer->video==1 || $astrologer->chat==1){
          $bookNow = 1;
        }
        $waitingTime = "";
        $currentTime = strtotime(date('Y-m-d H:i:s'));
        $data = [];
        if(!$astrologer){
          return response()->json(['success'=>true, 'data'=>$data], 402);
        }
        $status = 'offline';
        if($astrologer->chat == 3 || $astrologer->call == 3 || $astrologer->video == 3){
          $status = 'bussy';
        }elseif($astrologer->chat == 1 || $astrologer->call == 1 || $astrologer->video == 1){
          $status = 'online';
        }
        $video_status = 'hide';
        if($astrologer->video == 1){
          $video_status = 'online';
        }elseif($astrologer->video == 2){
          $video_status = 'offline';
        }elseif($astrologer->video == 3){
          $video_status = 'bussy';
        }
        $call_status = 'hide';
        if($astrologer->call == 1){
          $call_status = 'online';
        }elseif($astrologer->call == 2){
          $call_status = 'offline';
        }elseif($astrologer->call == 3){
          $call_status = 'bussy';
        }
        $chat_status = 'hide';
        if($astrologer->chat == 1){
          $chat_status = 'online';
        }elseif($astrologer->chat == 2){
          $chat_status = 'offline';
        }elseif($astrologer->chat == 3){
          $chat_status = 'bussy';
        }
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
            $waitingTime = "{$minutes}:{$seconds}";
            $status = 'bussy';
            $video_status = 'bussy';
            $call_status = 'bussy';
            $chat_status = 'bussy';
          }
        }
        $reviews = AstrologerReview::where('astrologer_id',$astrologer->id)->where('status', 1)->orderBy('review', 'DESC')->select('review')->get();
        $rating = 0;
        if(count($reviews)){
          $rating = floatval(sprintf('%.1f', $reviews[0]->review));
        }
        if($rating == 0){
          $rating = floatval(sprintf('%.1f', $astrologer->rating));
        }
        $reviews_count = $reviews->count();
        $astro['id'] = $astrologer->id;
        $astro['blue_tick'] = $astrologer->blue_tick?true:false;
        $astro['image'] = $astrologer->image ? $astrologer->image->image(100, 100) : no_image();
        $astro['rating'] = $rating;
        $astro['reviews_count'] = $reviews_count;
        $astro['rating_overview'] = [
          'one' => $reviews->where('review','<=', 1)->count(),
          'two' => $reviews->where('review', 2)->count(),
          'three' => $reviews->where('review', 3)->count(),
          'four' => $reviews->where('review', 4)->count(),
          'five' => $reviews->where('review','>=', 5)->count()
        ];
        $astro['slug'] = $astrologer->slug;
        $astro['uuid'] = $astrologer->uuid;
        $astro['last_login'] = date('d M Y h:i A',strtotime($astrologer->last_logined));
        $astro['name'] = $astrologer->display_name;
        $astro['status'] = $status;
        $astro['chat_status'] = $chat_status;
        $astro['call_status'] = $call_status;
        $astro['video_status'] = $video_status;
        $chatConsultations = AstrologerAppointment::where("package", 2)->where('astrologer_id', $astrologer->id)->where('status', 'done')->orderBy("id", "DESC")->get();
        $callConsultations = AstrologerAppointment::where("package", 3)->where('astrologer_id', $astrologer->id)->where('status', 'done')->orderBy("id", "DESC")->get();
        $videoConsultations = AstrologerAppointment::where("package", 1)->where('astrologer_id', $astrologer->id)->where('status', 'done')->orderBy("id", "DESC")->get();
        $giftsId = SendGift::where('astrologer_id', $astrologer->id)->pluck('gift_id')->toArray();
        $gifts = Gift::whereIn("id", $giftsId)->orderBy("id", "DESC")->get();
        $todayAmountChatConsultations = $chatConsultations->where('created_at','=',date("Y-m-d"))->sum("astrologer_amount");
        $todayAmountCallConsultations = $callConsultations->where('created_at','=',date("Y-m-d"))->sum("astrologer_amount");
        $todayAmountVideoConsultations = $videoConsultations->where('created_at','=',date("Y-m-d"))->sum("astrologer_amount");
        $todayAmountGifts = $gifts->where('created_at','=',date("Y-m-d"))->sum("price");
        $todayAmount = $todayAmountChatConsultations + $todayAmountCallConsultations + $todayAmountVideoConsultations + $todayAmountGifts;
        $monthAmountChatConsultations = $chatConsultations
        ->filter(function($item) {
            return $item->created_at->month == date("m") && $item->created_at->year == date("Y");
        })
        ->sum("astrologer_amount");
        $monthAmountCallConsultations = $callConsultations
        ->filter(function($item) {
            return $item->created_at->month == date("m") && $item->created_at->year == date("Y");
        })
        ->sum("astrologer_amount");
        $monthAmountVideoConsultations = $videoConsultations
        ->filter(function($item) {
            return $item->created_at->month == date("m") && $item->created_at->year == date("Y");
        })
        ->sum("astrologer_amount");
        $monthAmountGifts = $gifts
        ->filter(function($item) {
            return $item->created_at->month == date("m") && $item->created_at->year == date("Y");
        })
        ->sum("price");
        $monthAmount = $monthAmountChatConsultations + $monthAmountCallConsultations + $monthAmountVideoConsultations + $monthAmountGifts;

        $totalAmountChatConsultations = $chatConsultations->sum("astrologer_amount");
        $totalAmountCallConsultations = $callConsultations->sum("astrologer_amount");
        $totalAmountVideoConsultations = $videoConsultations->sum("astrologer_amount");
        $totalAmountGifts = $gifts->sum("price");
        $totalAmount = $totalAmountChatConsultations + $totalAmountCallConsultations + $totalAmountVideoConsultations + $totalAmountGifts;
        $astro['todayAmount'] = sprintf("%.2f", $todayAmount);
        $astro['monthly_amount'] = sprintf("%.2f", $monthAmountGifts);
        $astro['life_time'] = sprintf("%.2f", $totalAmount);
        $astro['wallet_balance'] = sprintf("%.2f", $astrologer->wallet);
        $totalTimeChatConsultations = $chatConsultations
        ->sum(function($item){
          $user_start_time = strtotime($item->user_start_time);
          $user_end_time = strtotime($item->user_end_time);
          $astrologer_start_time = strtotime($item->astrologer_start_time);
          $astrologer_end_time = strtotime($item->astrologer_end_time);
          $start_time = $astrologer_start_time>$user_start_time?$astrologer_start_time:$user_start_time;
          $end_time = $astrologer_end_time<$user_end_time?$astrologer_end_time:$user_end_time;
          $time=$end_time-$start_time;
          return ceil($time/60);
        });
        $totalTimeCallConsultations = $callConsultations
        ->sum(function($item){
          $user_start_time = strtotime($item->user_start_time);
          $user_end_time = strtotime($item->user_end_time);
          $astrologer_start_time = strtotime($item->astrologer_start_time);
          $astrologer_end_time = strtotime($item->astrologer_end_time);
          $start_time = $astrologer_start_time>$user_start_time?$astrologer_start_time:$user_start_time;
          $end_time = $astrologer_end_time<$user_end_time?$astrologer_end_time:$user_end_time;
          $time=$end_time-$start_time;
          return ceil($time/60);
        });
        $totalTimeVideoConsultations = $videoConsultations
        ->sum(function($item){
          $user_start_time = strtotime($item->user_start_time);
          $user_end_time = strtotime($item->user_end_time);
          $astrologer_start_time = strtotime($item->astrologer_start_time);
          $astrologer_end_time = strtotime($item->astrologer_end_time);
          $start_time = $astrologer_start_time>$user_start_time?$astrologer_start_time:$user_start_time;
          $end_time = $astrologer_end_time<$user_end_time?$astrologer_end_time:$user_end_time;
          $time=$end_time-$start_time;
          return ceil($time/60);
        });
        $astro['chat_time'] = $totalTimeChatConsultations;
        $astro['call_time'] = $totalTimeCallConsultations;
        $astro['video_time'] = $totalTimeVideoConsultations;
        $astrologerLive = AstrologerLive::where('astrologer_id', $astrologer->id)->where("status", 'scheduled')->orderBy('start_at', 'DESC')->first();
        if($astrologerLive){
          $astro['astro_live'] = 'true';
          $astro['schedule_start_at'] = $astrologerLive->start_at;
          $astro['schedule_end_at'] = $astrologerLive->end_at;
        }else{
          $astro['astro_live'] = 'false';
        }
        $astro['consultation'] = [
          'now' => $bookNow ? true : false,
          'later' => $takeBooking ? true : false,
          'later' => false,
          'wait' => $waitingTime,
        ];
        $astro['languages'] = [];
        foreach($astrologer->languages as $key => $language){
          if($language = $language->language){
            array_push($astro['languages'], $language->name);
          }
        }
        $astro['services'] = [];
        // foreach($astrologer->services as $key => $service){
        //   if($service = $service->service){
        //     array_push($astro['services'], $service->name);
        //   }
        // }
        foreach($astrologer->services as $key => $service){
          if($service = $service->service){
            $s = [
              'value' => $service->id,
              'icon' => $service->icon?$service->icon->image(50, 50):no_image(),
              'slug' => $service->slug,
              'name' => $service->name,
            ];
            array_push($astro['services'], $s);
          }
        }
        $astro['experience'] = (int) $astrologer->job_start_year ? date('Y') - $astrologer->job_start_year : 0;
        $astro['chat_price'] = (float) $astrologer->chat_price;
        $astro['call_price'] = (float) $astrologer->call_price;
        $astro['video_price'] = (float) $astrologer->price;
        $astro['chat_special_price'] = (double) 0.00;
        $astro['call_special_price'] = (float) 0.00;
        $astro['video_special_price'] = (float) 0.00;
        if($astrologer->chat_special_price > 0 && $astrologer->sp_from_date && (date('Y-m-d')>=$astrologer->sp_from_date) && (date('Y-m-d')<=$astrologer->sp_to_date)){
          $astro['chat_special_price'] = (float) $astrologer->chat_special_price;
        }
        if($astrologer->call_special_price > 0 && $astrologer->sp_from_date && (date('Y-m-d')>=$astrologer->sp_from_date) && (date('Y-m-d')<=$astrologer->sp_to_date)){
          $astro['call_special_price'] = (float) $astrologer->call_special_price;
        }
        if($astrologer->special_price > 0 && $astrologer->sp_from_date && (date('Y-m-d')>=$astrologer->sp_from_date) && (date('Y-m-d')<=$astrologer->sp_to_date)){
          $astro['video_special_price'] = (float) $astrologer->special_price;
        }
        $astro['about'] = $astrologer->about;
        $astro['experience_data'] = $astrologer->experience;
        $astro['education'] = $astrologer->education;
        $astro['expertise_data'] = $astrologer->expertise;
        $data['detail'] = $astro;
      }
      return response()->json(['success'=>true, 'data'=>$data], 200);
    }

    public function updateStatus(Request $request)
    {
      $this->validate($request, [
        'slug'=>'required',
        'field'  => 'required|string',
        'status' => 'required|string'
      ]);
        $field = $request->field;
        $status = $request->status;
        $statusArray = [
            'online'  => 1,
            'offline' => 2,
            'busy'    => 3,
            'hide'    => 0,
        ];
        if (!isset($statusArray[$status])) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 422);
        }
        $astrologer = Astrologer::where('status', 1)->where('is_verified', 1)->where('slug', $request->slug)->first();
        if (!$astrologer) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        $astrologer->{$field} = $statusArray[$status];
        $astrologer->save();
        // if ($status == 'online') {
        //     $loginController = new LoginController();
        //     $loginController->userNotification($astrologer, 'user', 'astrologer online');
        // }
        return response()->json(['success' => true, 'message' => 'Updated Successfully','status'  => $astrologer->{$field}], 200);
    }


    public function review(Request $request){
      $request->validate([
        "id" => "required|gt:0"
      ]);
      $id = $request->id;
      $astrologer = Astrologer::find($id);
      $data = [];
      if($astrologer){
        $reviews = AstrologerReview::where('astrologer_id', $astrologer->id)->where('status', 1)->orderBy('review', 'DESC')->get();
        $data = $reviews->map(function($value, $key){
          $item["review"] = $value->comment;
          $item["rating"] = (int) $value->review>0?$value->review:1;
          $item["date"] = date('d-m-Y', strtotime($value->created_at));
          $user = $value->user;
          if($user){
            $image = $user->image;
            $item["user_name"] = $user->name;
            $item["user_image_url"] = $image ? $image->image(200,200) : no_image();
          }else{
            $item["user_name"] = $user->name;
            $item["user_image_url"] = no_image();
          }
          return $item;
        });
      }
      return response()->json(['success'=>count($data) ? true : false, 'data'=>$data], 200);
    }

    public function scheduledLaterDate(Request $request){
      $this->validate($request, [
        'id'=>'required'
      ]);
      $id = $request->id;
      $astrologer = Astrologer::find($id);
      date_default_timezone_set('Asia/Kolkata');
      $weekDays = AstrologerTimeTable::where('astrologer_id', $astrologer->id)->groupBy('week_day')->orderBy('week_day', 'asc')->pluck('week_day')->toArray();
      $weekDays2 = [];
      for($i = 0; $i <= 30; $i++){
        $date = date('Y-m-d', strtotime("+{$i} days"));
        $dayOfWeek = (int) date('w', strtotime($date));
        if(in_array($dayOfWeek, $weekDays)){
            $weekDays2[] = $date;
        }
      }
      return response()->json([
        'success' => true,
        'data' => $weekDays2
      ]);
    }

    public function scheduledLaterTime(Request $request){
      $this->validate($request, [
        'id'=>'required',
        'time_slot'=>'required',
        'date'=>'required'
      ]);
      $id = $request->id;
      $time_slot = $request->time_slot;
      $date = $request->date;
      $dayOfWeek = date('w', strtotime($date));
      $timeSlots = AstrologerTimeTable::where('astrologer_id', $id)->where('week_day', $dayOfWeek)->select('from_time', 'to_time')->get();

      $formattedFromSlots = $timeSlots->pluck('from_time')->map(function($time) {
          return date('h:i A', strtotime($time));
      });

      $formattedToSlots = $timeSlots->pluck('to_time')->map(function($time) {
          return date('h:i A', strtotime($time));
      });

      $timeIntervals = [];
      $currentTime = new \DateTime();
      $now = time();
      if(date('Y-m-d') == date('Y-m-d', strtotime($date))){
        $now = strtotime('now+45minutes');
      }
      foreach ($formattedFromSlots as $key => $value){
        if(isset($formattedToSlots[$key])){
          $fromTime = strtotime($date.' '.$value);
          $toTime = strtotime($date.' '.$formattedToSlots[$key]);
          $interval = $fromTime;
          $add = 35*60;
          if($time_slot == 15){
            $add = 20*60;
          }
          for($interval = $fromTime; $interval <= $toTime; $interval+=$add){
            if($interval > $now){
              $timeIntervals[]=date('h:i A', $interval);
            }
          }
        }
      }
      return response()->json([
        'success'=>true,
        'timeIntervals' => $timeIntervals
      ]);
    }

        public function callscheduled(Request $request){
          // status = booked, joined, done, cancel, reschedule, cancel
          // video = 1,chat = 2, call = 3
          // hide = 0, online = 1, offline = 2, busy = 3
          // now, later
          // AstrologerAppointment
          // if call -> CallHistory
         $this->validate($request, [
             'id' => 'required',
             'mode' => 'required|in:call,chat,video', // chat, call and video
             'when' => 'required|in:now,later', // now and later
             'name' => 'required', // user name
             // 'email' => 'required|email', // user email
             // 'mobile' => 'required|digits:10', // user mobile
             'birth_date' => 'required', // user mobile
             'birth_time' => 'required', // user mobile
             'birth_place' => 'required', // user mobile
         ]);

         if($request->when == 'later'){
           $this->validate($request, [
              'time_slot' => 'required|in:15,30', // 15 and 30
              'scheduled_date' => 'required',
              'scheduled_time_slot' => 'required',
           ],[
               'scheduled_date.required' => 'The Schedule Date is Required',
               'scheduled_time_slot.required' => 'The Schedule Time is Required',
           ]);
         }

         $message = "";

         $astrologer_id = $request->id;
         $astrologer = Astrologer::find($astrologer_id);

         if(!$astrologer){
           $message = "Invalid request.";
           return response()->json([
             'success' => false,
             'status'=>'not found',
             "message" => $message
           ], 422);
         }

         $freeAppointment = 0;
         $astrologerName = $astrologer->display_name;

         $user = $request->user();
         $user_type = 'user';
         $consultation_mode = $request->mode;

         if(!$user){
           $message = "Please Login First.";
           return response()->json([
             'success' => false,
             'status'=>'login',
             "message" => $message
           ], 402);
         }elseif(!$user->phone){
           $message = "Please fill your phone number.";
           return response()->json([
             'success' => false,
             'status' => 'fill detail',
             "message" => $message
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
          $consultation_type = $request->when;
          $time_slot = $request->time_slot ?: 5;
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

          $checkcallstatus = $astrologer->call; // 0:hide, 1:online ,2:offline ,3:busy
          $checkchatstatus = $astrologer->chat; // 0:hide, 1:online ,2:offline ,3:busy
          $checkvideostatus = $astrologer->video; // 0:hide, 1:online ,2:offline ,3:busy

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
              $mode=$consultation_mode=='video'?$consultation_mode.' call':$consultation_mode;
              $message = "Minimum balance of {$time_slot} minutes (₹ {$total_price}) is required to start {$mode} with {$astrologer->display_name}";
              return response()->json([
                'success' => false,
                'status' => 'recharge',
                "message" => $message
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
              return response()->json([
                'success' => false,
                "message" => $message
              ], 422);
            }
            if($consultation_mode == 'chat'){
              if($checkchatstatus == 0 || $checkchatstatus == 2){
                $message = "$astrologerName is offline in $consultation_mode mode.";
                return response()->json([
                  'success' => false,
                  "message" => $message
                ], 422);
              }
            }elseif($consultation_mode == 'call'){
              if($checkcallstatus == 0 || $checkcallstatus == 2){
                $message = "$astrologerName is offline in $consultation_mode mode.";
                return response()->json([
                  'success' => false,
                  "message" => $message
                ], 422);
              }
            }elseif($consultation_mode == 'video'){
              if($checkvideostatus == 0 || $checkvideostatus == 2) {
                $message = "$astrologerName is offline in $consultation_mode mode.";
                return response()->json([
                  'success' => false,
                  "message" => $message
                ], 422);
              }
            }
            if($astrologer->withInThirtyMinutes()){
              $message = "$astrologerName is bussy.";
              return response()->json([
                'success' => false,
                "message" => $message
              ], 422);
            }
            $end_time = date('H:i', strtotime($booking_date.' '.$booking_time." +1minutes"));
          }elseif($consultation_type == 'later'){
            $scheduled_for = 1;
            $booking_date = date('Y-m-d', strtotime($request->scheduled_date));
            $booking_time = date('H:i', strtotime($booking_date.' '.$request->scheduled_time_slot));
            $end_time = date('H:i', strtotime($booking_date.' '.$booking_time." +".$time_slot."minutes"));
            $max_time = date('H:i', strtotime($booking_date.' '.$end_time." +5minutes"));
          }

          if($astrologer->currentAppointment()){
            $message = "You are between in a consultation.";
            return response()->json([
              'success' => false,
              'status' => 'bussy',
              "message" => $message
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
            return response()->json([
              'success' => false,
              'status'=>'booked',
              "message" => $message
            ], 422);
          }

          $max_time = date('H:i', strtotime($booking_date.' '.$max_time." +1minutes"));

          $payoutPackage = PayoutPackage::find($astrologer->payout_package_id);
          if(!$payoutPackage){
            $message = "Astrologer is not verified.";
            return response()->json([
              'success' => false,
              'status'=>'not verfiried',
              "message" => $message
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
            $notification['link'] = route('user.consultation.history');
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

          return response()->json([
            'success' => true,
            'message' => $message,
            'when' => $consultation_type,
            'mode' => $consultation_mode,
            'consultation_id' => $appointment->id,
            'status'=>'booked'
          ], 200);
      }

      public function liveAstrologers(Request $request){
        $results=AstrologerLive::where(function($query){
          $query->where('status','started')
          ->orWhere("status","scheduled");
        })
        ->orderBy("status","DESC")
        ->orderBy("end_at")
        ->get();
        $data=$results->map(function($item){
          $astrologer=$item->astrologer;
          $image=$astrologer->image;
          return [
            'live_id'=>$item->id,
            'astrologer_id'=>$item->astrologer_id,
            'astrologer_image'=>$image?$image->image():no_image(),
            'name'=>$item->astrologer_name,
            'mode'=>$item->mode,
            'status'=>$item->status,
            'start_at'=>$item->start_at,
            'end_at'=>$item->end_at,
          ];
        });
        return response()->json(['success'=>true, 'data' => $data], 200);
      }

      public function bookAiAstrologerAppointment(Request $request){
       $request->validate([
           'astrologer_id' => 'required',
           // 'mode' => 'required|in:call,chat,video', // chat, call and video
           // 'when' => 'required|in:now,later', // now and later
           'name' => 'required', // user name
           // 'email' => 'required|email', // user email
           // 'mobile' => 'required|digits:10', // user mobile
           'birth_date' => 'required', // user mobile
           'birth_time' => 'required', // user mobile
           'birth_place' => 'required', // user mobile
       ]);

       $message = "";

       $astrologer_id = $request->astrologer_id;
       $astrologer = Astrologer::find($astrologer_id);

       if(!$astrologer){
         $message = "Invalid request.";
         return response()->json([
           'success' => false,
           'status'=>'not found',
           "message" => $message
         ], 422);
       }

       $freeAppointment = 0;
       $astrologerName = $astrologer->display_name;

       $user = $request->user();
       $user_type = 'user';
       $consultation_mode = $request->mode ?: "chat";

       if(!$user){
         $message = "Please Login First.";
         return response()->json([
           'success' => false,
           'status'=>'login',
           "message" => $message
         ], 402);
       }elseif(!$user->phone){
         $message = "Please fill your phone number.";
         return response()->json([
           'success' => false,
           'status' => 'fill detail',
           "message" => $message
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
          return response()->json([
            'success' => false,
            'status' => 'retry',
            "message" => $message
          ], 402);
        }

        $formData['name']=$request->name;
        $formData['email']=$user->email;
        $formData['mobile']=$user->phone;
        $formData['birth_date']=$request->birth_date;
        $formData['birth_time']=$request->birth_time;
        $formData['birth_place']=$request->birth_place;
        $formData['gender']="";

        if($gender = $request->gender){
          if($gender==1){
            $formData['gender'] = 'male';
          }elseif($gender==2){
            $formData['gender'] = 'female';
          }elseif($gender==3){
            $formData['gender'] = 'other';
          }
        }

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
            $mode=$consultation_mode=='video'?$consultation_mode.' call':$consultation_mode;
            $message = "Minimum balance of {$timeSlot} minutes (₹ {$totalPrice}) is required to start {$mode} with {$astrologer->display_name}";
            return response()->json([
              'success' => false,
              'status' => 'recharge',
              "message" => $message
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

        $message = "Connecting to the $astrologerName.";

       // return response
       return response()->json([
         'success'=>true,
         'message' => $message,
         'consultation_id'=>$aiAstrologerAppointment->id,
         'status'=>'booked'
       ], 200);
    }

}
