<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{Astrologer, Service};

class InstructorController extends Controller
{

    public function list(Request $request){
        $astrologers = Astrologer::where('status', 1)
        ->where('is_verified',1)
        ->where(function($q) {
          $q->orWhere('call', '!=', 0);
          $q->orWhere('chat', '!=', 0);
          $q->orWhere('video', '!=', 0);
        })->where('type', 'Instructor');
        if($search = $request->search) {
            $astrologers->where('display_name', 'like', "%$search%");
        }
        $status = $request->status;
        if($status && count($status)>0){
            foreach($status as $st){
                $astrologers->where($st, 1);
            }
        }
        $services = $request->services;
        if($services && count($services)>0) {
            $astrologers->wherehas('services', function($sq)use($services){
                $sq->whereIn('service_id', $services);
            });
        }
        $languages = $request->languages;
        if($languages && count($languages)>0) {
            $astrologers->wherehas('languages', function($sq)use($languages){
                $sq->whereIn('language_id', $languages);
            });
        }
        $experience = $request->experience;
        if($experience && count($experience)>0){
            $todayYear = date('Y');
            $astrologers->where(function($query)use($experience, $todayYear){
                foreach($experience as $y){
                    list($s, $e) = explode('-', $y);
                    $query->orWhereBetween('job_start_year',[$todayYear-$e, $todayYear-$s]);
                }
            });
        }
        $price = $request->price;
        if($price && count($price)>0){
            $astrologers->where(function($query)use($price){
                foreach($price as $y) {
                    list($s, $e) = explode('-', $y);
                    $query->orWhereBetween('price',[$s, $e]);
                }
            });
        }
        $rating = $request->rating;
        $astrologers = $astrologers->paginate(5);
        $astrologersData = [];
        foreach ($astrologers as $key => $astrologer) {
            if($astrologer->chat == 0 && $astrologer->call == 0 && $astrologer->video == 0){
              // astrologer choose to be hidden;
            }else{
              $status = 'Offline';
              if($astrologer->chat == 3 || $astrologer->call == 3 || $astrologer->video == 3){
                $status = 'Busy';
              }elseif($astrologer->chat == 1 || $astrologer->call == 1 || $astrologer->video == 1){
                $status = 'Online';
              }
              $video_status = 'Hide';
              if($astrologer->video == 1){
                $video_status = 'Online';
              }elseif($astrologer->video == 2){
                $video_status = 'Offline';
              }elseif($astrologer->video == 3){
                $video_status = 'Busy';
              }
              $call_status = 'Hide';
              if($astrologer->call == 1){
                $call_status = 'Online';
              }elseif($astrologer->call == 2){
                $call_status = 'Offline';
              }elseif($astrologer->call == 3){
                $call_status = 'Busy';
              }
              $chat_status = 'Hide';
              if($astrologer->chat == 1){
                $chat_status = 'Online';
              }elseif($astrologer->chat == 2){
                $chat_status = 'Offline';
              }elseif($astrologer->chat == 3){
                $chat_status = 'Busy';
              }
                $item = [
                    'astrologer_id'=>$astrologer->id,
                    'status'=>$status,
                    'chat_status'=>$chat_status,
                    'call_status'=>$call_status,
                    'video_status'=>$video_status,
                    'slug'=> $astrologer->slug,
                    'public_view'=> route('astrologers.publicview',$astrologer->slug),
                    'image' => $astrologer->image?$astrologer->image->image(100):no_image(),
                    'call' => $astrologer->call,
                    'chat' => $astrologer->chat,
                    'video'=>$astrologer->video,
                    'name'=> $astrologer->display_name,
                    'language'=> $astrologer->my_language,
                    'expertise'=> $astrologer->my_expertise,
                    'experience' => $astrologer->year_exp,
                    'is_offer' => $astrologer->offer_price,
                    'chat_price' => $astrologer->chat_price,
                    'chat_special_price' =>$astrologer->chat_special_price,
                    'price' => $astrologer->call_price,
                    'special_price' => $astrologer->call_special_price,
                    'video_price'=>$astrologer->price,
                    'video_special_price'=>$astrologer->special_price,
                    'rating' => $astrologer->rating,
                    'per_minute'=> $astrologer->per_minute_rate
                ];
                array_push($astrologersData, $item);
              }
        }
        // $astrologers = $astrologers->map(function($astrologer){
        //   if($astrologer->chat != 0 && $astrologer->call != 0 && $astrologer->video != 0){
        //   // if(true){
        //     $status = 'Offline';
        //     if($astrologer->chat == 3 || $astrologer->call == 3 || $astrologer->video == 3){
        //       $status = 'Busy';
        //     }elseif($astrologer->chat == 1 || $astrologer->call == 1 || $astrologer->video == 1){
        //       $status = 'Online';
        //     }
        //     $video_status = 'Hide';
        //     if($astrologer->video == 1){
        //       $video_status = 'Online';
        //     }elseif($astrologer->video == 2){
        //       $video_status = 'Offline';
        //     }elseif($astrologer->video == 3){
        //       $video_status = 'Busy';
        //     }
        //     $call_status = 'Hide';
        //     if($astrologer->call == 1){
        //       $call_status = 'Online';
        //     }elseif($astrologer->call == 2){
        //       $call_status = 'Offline';
        //     }elseif($astrologer->call == 3){
        //       $call_status = 'Busy';
        //     }
        //     $chat_status = 'Hide';
        //     if($astrologer->chat == 1){
        //       $chat_status = 'Online';
        //     }elseif($astrologer->chat == 2){
        //       $chat_status = 'Offline';
        //     }elseif($astrologer->chat == 3){
        //       $chat_status = 'Busy';
        //     }
        //       $item = [
        //           'astrologer_id'=>$astrologer->id,
        //           'status'=>$status,
        //           'chat_status'=>$chat_status,
        //           'call_status'=>$call_status,
        //           'video_status'=>$video_status,
        //           'slug'=> $astrologer->slug,
        //           'public_view'=> route('astrologers.publicview',$astrologer->slug),
        //           'image' => $astrologer->image?$astrologer->image->image(100):no_image(),
        //           'call' => $astrologer->call,
        //           'chat' => $astrologer->chat,
        //           'video'=>$astrologer->video,
        //           'name'=> $astrologer->display_name,
        //           'language'=> $astrologer->my_language,
        //           'expertise'=> $astrologer->my_expertise,
        //           'experience' => $astrologer->year_exp,
        //           'is_offer' => $astrologer->offer_price,
        //           'chat_price' => $astrologer->chat_price,
        //           'chat_special_price' =>$astrologer->chat_special_price,
        //           'price' => $astrologer->call_price,
        //           'special_price' => $astrologer->call_special_price,
        //           'video_price'=>$astrologer->price,
        //           'video_special_price'=>$astrologer->special_price,
        //           'rating' => $astrologer->rating,
        //           'per_minute'=> $astrologer->per_minute_rate
        //       ];
        //       return $item;
        //   }else{
        //     return [];
        //   }
        // });
        return $astrologersData;
    }
}
