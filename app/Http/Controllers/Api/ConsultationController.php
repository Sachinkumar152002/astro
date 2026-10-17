<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\{Controller, GptController};
use Illuminate\Http\Request;
use App\Services\SearchLocationService;
use App\Model\{Astrologer, AstrologerAppointment, Chat, ChatLog, AiAstrologerAppointment, AiAstrologerAppointmentChat, GptResponse, AstrologerService, Service, WalletTransition, AstrologerReview};
use App\{User, AstrologerUserConsultacy, LocationHistory};
use App\Jobs\{FeedbackConsultationJob};
use App\src\{RtcTokenBuilder2, RtmTokenBuilder2};

class ConsultationController extends Controller
{
  protected $searchLocationService;

  public function __construct(SearchLocationService $searchLocationService){
     $this->searchLocationService = $searchLocationService;
  }

  public function index(Request $request){
    $this->validate($request, [
      'type'=>'required|in:video,call,chat'
    ]);
    $type = $request->type;
    $package = 1;
    if($type == 'chat'){
      $package = 2;
    }else if($type == 'call'){
      $package = 3;
    }
    $user = $request->user();
    $user_type = 'user';
    $results = AstrologerAppointment::where('user_id', $user->id)->whereNotIn('status', ['pending', 'failed'])->get();
    $data['total_count'] = $results->count();
    $data['video_count'] = $results->where('package', 1)->count();
    $data['chat_count'] = $results->where('package', 2)->count();
    $data['call_count'] = $results->where('package', 3)->count();
    $results = AstrologerAppointment::where('user_id', $user->id)->where('package', $package)->whereNotIn('status', ['pending', 'failed'])->orderBy('id', 'desc')->get();
    $appointmentsId = $results->pluck("id")->toArray();
    $reviews = AstrologerReview::where("user_id", $user->id)->whereIn("appointment_id", $appointmentsId)->get();

    $data['list'] = $results->map(function ($item) use($package, $reviews){
      $astrologer = $item->astrologer;
      $start_time = strtotime($item->scheduled_date.' '.$item->scheduled_time);
      $end_time = strtotime($item->scheduled_date.' '.$item->scheduled_end_time);

      $d['free']=$item->free ? true : false;
      $d['consultation_id']=$item->id;
      $d['astrologer_id']=$item->astrologer_id;
      $d['name']=$astrologer->display_name;
      $d['date_time']=date('d-m-Y h:i a', $start_time);
      $d['charges']=$item->actualprice;

      $duration = 0;

      if($item->timeslot==5){
        $userStartTime = strtotime($item->scheduled_date.' '.$item->user_start_time);
        $astrologerStartTime = strtotime($item->scheduled_date.' '.$item->astrologer_start_time);
        $userEndTime = strtotime($item->scheduled_date.' '.$item->user_end_time);
        if($userStartTime > $astrologerStartTime){
          $duration = ceil(($userEndTime - $userStartTime)/60);
        }else{
          $duration = ceil(($userEndTime - $astrologerStartTime)/60);
        }
      }else{
        $duration = ceil((strtotime($item->scheduled_date.' '.$item->scheduled_end_time) - strtotime($item->scheduled_date.' '.$item->scheduled_time))/60);
      }

      $d['duration']=$duration > 0 ? $duration : 0;
      $d['amount']=$item->total_price-$item->discount;

      if(time() >= $end_time){
        $d['status'] = 1;
        $d['action']= 'Join';
        if($package == 3){
          $d['action']= 'On Call';
        }
        if($item->status == 'cancel'){
          $d['action']= 'Cancelled';
          $d['status'] = 0;
        }elseif($item->total_price > 0){
          $d['action']= 'Completed';
        }
        if($package == 1  || $package == 3){
          $d['status'] = 0;
        }
      }elseif(time() >= $start_time){
        $d['status'] = 1;
        $d['action']= 'Join';
      }else{
        $d['status'] = 0;
        $remaining =  $start_time - time();
        $remaining_hours = floor($remaining / 3600);
        $remaining_minutes = floor(($remaining % 3600) / 60);
        $remaining_seconds = floor($remaining % 60);
        $d['action'] = "wait: $remaining_hours:$remaining_minutes:$remaining_seconds m";
      }

      $d['review'] = false;

      $reviewsAll = $reviews;
      $review = $reviewsAll->where("appointment_id", $item->id)->first();

      if($item->status == "done" && $review == null){
        $d['review'] = true;
      }

      return $d;
    });
    return response()->json(["success"=>true, "data"=>$data], 200);
  }

  public function aiConsultatins(Request $request){
    $user = $request->user();
    $user_type = 'user';
    $results = AiAstrologerAppointment::where('user_id', $user->id)->where('paid', 1)->get();
    $data['total_count'] = $results->count();

    $appointmentsId = $results->pluck("id")->toArray();
    $reviews = AstrologerReview::where("user_id", $user->id)->whereIn("appointment_id", $appointmentsId)->get();

    $data['list'] = $results->map(function ($item) use($reviews){
      $astrologer = $item->astrologer;
      // $formData = json_decode($item->form_data, true);
      $startTime = strtotime($item->start_at);
      $endTime = strtotime($item->end_at);

      $d['free']=$item->free ? true : false;
      $d['consultation_id']=$item->id;
      $d['astrologer_id']=$item->astrologer_id;
      $d['name']=$astrologer->display_name;
      $d['date_time']=date('d-m-Y h:i a', $startTime);
      $d['charges']=$item->per_min_charge;
      $d['duration'] = ceil(($endTime - $startTime)/60) ?: 0;
      $d['amount']=$item->charges-$item->discount;
      $d['status'] = 1;
      $d['action']= 'Completed';

      $d['review'] = [
        "can" => false,
        "review" => "",
        "rating" => 0,
        "status" => 0,
      ];

      $d['review'] = false;

      $reviewsAll = $reviews;
      $review = $reviewsAll->where("appointment_id", $item->id)->first();

      if($item->paid == 1 && $review == null){
        $d['review'] = true;
      }

      return $d;
    });
    return response()->json(["success"=>true, "data"=>$data], 200);
  }

  public function canChat(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);

    $consultation_id = $request->consultation_id;
    $user = $request->user();

    $appointment = AstrologerAppointment::where('package', 2)
    ->where('id', $consultation_id)
    ->where('user_id', $user->id)
    ->whereNotIn('status', ['pending', 'cancel'])
    ->first();

    if(!$appointment){
      return response()->json([
        'success'=>false,
        'message' => 'Consultation not found.'
      ], 404);
    }

    if(!$appointment->user_start_time){
      $appointment->user_status='joined';
      $appointment->status='joined';
      $appointment->user_start_time = date('H:i:s');
      $appointment->save();
    }

    $astrologer = $appointment->astrologer;

    $astrologer_detail['id'] = $astrologer->id;
    $astrologer_detail['slug'] = $astrologer->slug;
    $astrologer_detail['name'] = $astrologer->display_name;
    $astrologer_detail['type'] = 'astrologer';

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();
    $consultation_detail['start_time'] = '00:00';
    $consultation_detail['recharge_popup_count_down'] = 0;
    $count_down = '00:00';

    if(!$appointment->astrologer_start_time){
      $count_down = ($starting_time+180) - time();
      $count_down_minutes = floor(($count_down % 3600) / 60);
      $count_down_seconds = $count_down % 60;
      $count_down = "$count_down_minutes:$count_down_minutes";
      return response()->json(['success'=>true, 'message' => 'Please wait for astrologer', 'status' => 'wait', 'chat_id'=>0, 'astrologer_detail' => $astrologer_detail, 'consultation_detail' => $consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }

    $remaining_seconds = $ending_time - $current_time;

    if($appointment->timeslot > 5 && $remaining_seconds > 0){
      $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
      $remaining_seconds = $remaining_seconds % 60;
      $consultation_detail['start_time'] = "$remaining_minutes:$remaining_seconds";
    }else{
      $start_time = time() - strtotime($appointment->user_start_time);
      if($appointment->astrologer_start_time){
        $start_time = time() - strtotime($appointment->astrologer_start_time);
      }
      $start_minutes = floor(($start_time % 3600) / 60);
      $start_seconds = $start_time % 60;
      $consultation_detail['start_time'] = "$start_minutes:$start_seconds";
      $consultation_detail['recharge_popup_count_down'] = time()-strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-6minutes');

      if($consultation_detail['recharge_popup_count_down'] < 0){
        $consultation_detail['recharge_popup_count_down'] = 0;
      }
    }

    if($starting_time > $current_time){
      $difference = $starting_time - $current_time;
      $hours = floor($difference / 3600);
      $minutes = floor(($difference % 3600) / 60);
      $message = "Wait: $hours:$minutes hrs";
      return response()->json(['success'=>true, 'message' => $message, 'status' => 'not now', 'chat_id'=>0, 'astrologer_detail' => $astrologer_detail, 'consultation_detail'=>$consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }elseif($ending_time < $current_time){
      return response()->json(['success'=>true, 'message' => 'Chat has ended', 'status' => 'done', 'chat_id'=>0, 'astrologer_detail' => $astrologer_detail, 'consultation_detail'=>$consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }
    return response()->json(['success'=>true, 'message' => '', 'status' => 'start', 'chat_id'=>0, 'astrologer_detail' => $astrologer_detail, 'consultation_detail'=>$consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
  }

  public function fetchChatLogs(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required',
    ]);

    $user =  $request->user();
    $user_type =  'user';
    $current_time = date('H:i:s');
    $consultation_id = $request->consultation_id;
    $chat_id = $request->chat_id;
    $appointment = AstrologerAppointment::find($consultation_id);
    $chatLogs = ChatLog::where('chat_id', $consultation_id)->get();
    $chatLogsCount = $chatLogs->count();

    $firstMessage['message_by'] = 'user';
    $firstMessage['message'] = "Hi, \nName: ".$user->name;
    if($appointment->gender){
      $firstMessage['message'] .= " \nGender: ".$appointment->gender;
    }
    if($appointment->dateofbirth){
      $firstMessage['message'] .= " \nBirth Date: ".date('d-m-Y', strtotime($appointment->dateofbirth));
    }
    if($appointment->birthtime){
      $firstMessage['message'] .= " \nBirth Time: ".date('h:i a', strtotime($appointment->birthtime));
    }
    if($appointment->birthplace){
      $firstMessage['message'] .= " \nBirth Place: ".$appointment->birthplace;
    }
    if($appointment->occupation){
      $firstMessage['message'] .= " \nOccupation: ".$appointment->occupation;
    }
    if($appointment->maritial_status){
      $firstMessage['message'] .= " \nMaritial Status: ".$appointment->maritial_status;
    }
    $firstMessage['created_at']=date('h:i a', strtotime($appointment->created_at));

    $secondMessage['message_by'] = 'user';
    $secondMessage['message']='This is an automated message to confirmed that chat has started';
    $secondMessage['created_at']=date('h:i a', strtotime($appointment->created_at));

    $systmeGenrateMessages = [$firstMessage, $secondMessage];


    $data = $chatLogs->map(function($item){
      $d['message_by']=$item->message_by=='user'?'user':'astrologer';
      $d['message']=$item->message;
      $d['created_at']=date('h:i a', strtotime($item->created_at));
      return $d;
    })->toArray();

    $data = array_merge($systmeGenrateMessages, $data);

    $consultation_detail['show_lead_popup'] = $appointment->lead_mail_sent ? false : true;
    $consultation_detail['show_lead_popup'] = false;

    $status = 'done';
    $message = '';

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if($starting_time <= $current_time && $ending_time > $current_time){
      $appointment->user_end_time = date('H:i:s', $current_time);
      $appointment->user_status = 'done';

      if($appointment->status != "cancel"){
        $appointment->status = 'done';
      }

      $status = 'continue';

      if($request->status == 'leave'){
        $appointment->appointment_time = date('H:i:s');

        if($appointment->timeslot == 5){
          $appointment->scheduled_end_time = date('H:i');
          $appointment->max_time = date('H:i', strtotime('+5minutes'));
        }

        if($appointment->feedback_mail_sent == 0){
          FeedbackConsultationJob::dispatch($appointment);
          $appointment->feedback_mail_sent = 1;
        }

        // if($appointment->lead_mail_sent == 0){
        //   $astrologer = $appointment->astrologer;
        //   EnquiryJob::dispatch($appointment, 'send_lead_confirmation', $user->name, $user->email, 'user', $user, $astrologer);
        //   $appointment->lead_mail_sent = 1;
        // }

        $status = 'done';
        $message = 'chat has ended';
      }elseif($request->status == 'cancel'){
        $appointment->user_status = 'cancel';
        $appointment->status = 'cancel';
        $status = 'cancel';
        $message = 'chat has canceled';
      }

      $appointment->save();
    }

    return response()->json(['success'=>true, 'message' => $message, 'status' => $status, 'data'=>$data, 'chatLogsCount'=>$chatLogsCount, 'consultation_detail' => $consultation_detail], 200);
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
    $user = $request->user();

    $data = $this->maskIllegalString($message);

    $has_illegal_text = $data["has_illegal_text"];
    $message = $data["string"];

    $chatLog = new ChatLog();
    $chatLog->chat_id = $consultation_id;
    $chatLog->user_id = $user->id;
    $chatLog->astrologer_id = 0;
    $chatLog->message = $message;
    $chatLog->message_by = 'user';

    if($has_illegal_text){
      $astrologerAppointment = AstrologerAppointment::where("id", $consultation_id)->where("has_illegal_activity", 0)->first();
      if($astrologerAppointment){
        $astrologerAppointment->has_illegal_activity = 1;
        $astrologerAppointment->save();
      }
      $chatLog->has_illegal_text = 1;
      $chatLog->illegal_message = $request->message;
    }

    $chatLog->save();

    return response()->json(['success'=>true, 'message' => 'sent'], 200);
  }

  public function canPhoneCall(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);
    $user = $request->user();
    $consultation_id = $request->consultation_id;

    $appointment = AstrologerAppointment::where('package', 3)
    ->where('id', $consultation_id)
    ->where('user_id', $user->id)
    ->first();

    if(!$appointment){
      return response()->json([
        'success'=>false,
        'message' =>
        'Consultation not found.'
      ], 404);
    }

    if(!$appointment->user_start_time){
      $appointment->user_status='joined';
      $appointment->status='joined';
      $appointment->user_start_time = date('H:i:s');
      $appointment->save();
    }

    $astrologer = $appointment->astrologer;
    $astrologer_detail['name'] = $astrologer->display_name;

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_end_time);
    $current_time = time();

    $consultation_detail['id'] = $appointment->id;
    $consultation_detail['start_time'] = '00:00';
    $consultation_detail['recharge_popup_count_down'] = 0;
    $count_down = '00:00';

    if($appointment->timeslot == 5){
      $consultation_detail['recharge_popup_count_down'] = time() - strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-6minutes');
      if($consultation_detail['recharge_popup_count_down'] < 0){
        $consultation_detail['recharge_popup_count_down'] = 0;
      }
    }

    $remaining_seconds = $ending_time - $current_time;
    if($remaining_seconds > 0){
      $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
      $remaining_seconds = $remaining_seconds % 60;
      $consultation_detail['start_time'] = "$remaining_minutes:$remaining_seconds";
    }
    // $consultation_detail['app_id'] = (int) config('vrdcms.zego_app_id');
    // $consultation_detail['app_sign'] = config('vrdcms.zego_app_sign');
    // $consultation_detail['call_id'] = $astrologerUserConsultacy->meeting_link;

    $consultation_detail['app_id'] = fromSetting("agora_app_id");
    $consultation_detail['app_certificate'] = fromSetting("agora_app_certificate");
    $consultation_detail['call_id'] = $appointment->room_id;
    $consultation_detail['user_id'] = $user->id;
    $consultation_detail['user_name'] = $user->name;
    $consultation_detail['uid'] = "@".$user->name.$user->id;
    $consultation_detail['app_sign'] = RtcTokenBuilder2::buildTokenWithUid($consultation_detail['app_id'], $consultation_detail["app_certificate"], $consultation_detail["call_id"], $consultation_detail["user_id"], 1, 86400);
    $consultation_detail['logo_url'] = asset('assets/img/logo.png');
    unset($consultation_detail['app_certificate']);

    if(!$appointment->astrologer_start_time){
      $count_down = ($starting_time+180) - time();
      $count_down_minutes = floor(($count_down % 3600) / 60);
      $count_down_seconds = $count_down % 60;
      $count_down = "$count_down_minutes:$count_down_minutes";
      return response()->json(['success'=>true, 'message' => 'Please wait for astrologer', 'status' => 'wait', 'astrologer_detail' => $astrologer_detail, 'consultation_detail' => $consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }

    if($starting_time > $current_time){
      $difference = $starting_time - $current_time;
      $hours = floor($difference / 3600);
      $minutes = floor(($difference % 3600) / 60);
      $message = "Wait: $hours:$minutes hrs";
      return response()->json(['success'=>true, 'message' => $message, 'status' => 'not now', 'astrologer_detail' => $astrologer_detail, 'consultation_detail' => $consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }elseif($ending_time < $current_time){
      return response()->json(['success'=>true, 'message' => 'Video has ended', 'status' => 'done', 'astrologer_detail'=>$astrologer_detail, 'consultation_detail'=>$consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }
    return response()->json(['success'=>true, 'message' => '', 'status' => 'start', 'astrologer_detail'=>$astrologer_detail, 'consultation_detail'=>$consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
  }

  public function updatePhoneCallStatus(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);
    $user = $request->user();
    $consultation_id = $request->consultation_id;
    $appointment = AstrologerAppointment::where('id', $consultation_id)->where('user_id', $user->id)->where('package', 3)->first();
    if(!$appointment){
      return response()->json(['success'=>false, 'message' => 'Consultation not found.'], 404);
    }

    $consultation_detail['show_lead_popup'] = $appointment->lead_mail_sent ? false : true;
    $consultation_detail['show_lead_popup'] = false;

    $status = 'done';
    $message = '';

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if($starting_time <= $current_time && $ending_time > $current_time){
      $appointment->user_end_time = date('H:i:s', $current_time);
      $appointment->user_status = 'done';
      if($appointment->status != "cancel"){
        $appointment->status = 'done';
      }
      $status = 'continue';

      if($request->status == 'leave'){
        if($appointment->timeslot == 5){
          $appointment->scheduled_end_time = date('H:i');
          $appointment->max_time = date('H:i', strtotime('+5minutes'));
        }
        if($appointment->feedback_mail_sent == 0){
          FeedbackConsultationJob::dispatch($appointment);
          $appointment->feedback_mail_sent = 1;
        }
        // if($appointment->lead_mail_sent == 0){
        //   $astrologer = $appointment->astrologer;
        //   EnquiryJob::dispatch($appointment, 'send_lead_confirmation', $user->name, $user->email, 'user', $user, $astrologer);
        //   $appointment->lead_mail_sent = 1;
        // }
        $status = 'done';
        $message = 'Video has ended';
      }elseif($request->status == 'cancel'){
        $appointment->user_status = 'cancel';
        $appointment->status = 'cancel';
        $status = 'cancel';
        $message = 'Video has canceled';
      }
      $appointment->save();
    }

    return response()->json(['success'=>true, 'message'=>$message, 'status'=>$status, 'consultation_detail' => $consultation_detail], 200);
  }

  public function canVideoCall(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);
    $user = $request->user();
    $consultation_id = $request->consultation_id;

    $appointment = AstrologerAppointment::where('package', 1)
    ->where('id', $consultation_id)
    ->where('user_id', $user->id)
    ->first();

    if(!$appointment){
      return response()->json([
        'success'=>false,
        'message' =>
        'Consultation not found.'
      ], 404);
    }

    if(!$appointment->user_start_time){
      $appointment->user_status='joined';
      $appointment->status='joined';
      $appointment->user_start_time = date('H:i:s');
      $appointment->save();
    }

    $astrologer = $appointment->astrologer;
    $astrologer_detail['name'] = $astrologer->display_name;

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_end_time);
    $current_time = time();

    $consultation_detail['id'] = $appointment->id;
    $consultation_detail['start_time'] = '00:00';
    $consultation_detail['recharge_popup_count_down'] = 0;
    $count_down = '00:00';

    if($appointment->timeslot == 5){
      $consultation_detail['recharge_popup_count_down'] = time() - strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-6minutes');
      if($consultation_detail['recharge_popup_count_down'] < 0){
        $consultation_detail['recharge_popup_count_down'] = 0;
      }
    }

    $remaining_seconds = $ending_time - $current_time;
    if($remaining_seconds > 0){
      $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
      $remaining_seconds = $remaining_seconds % 60;
      $consultation_detail['start_time'] = "$remaining_minutes:$remaining_seconds";
    }
    // $consultation_detail['app_id'] = (int) config('vrdcms.zego_app_id');
    // $consultation_detail['app_sign'] = config('vrdcms.zego_app_sign');
    // $consultation_detail['call_id'] = $astrologerUserConsultacy->meeting_link;

    $consultation_detail['app_id'] = fromSetting("agora_app_id");
    $consultation_detail['app_certificate'] = fromSetting("agora_app_certificate");
    $consultation_detail['call_id'] = $appointment->room_id;
    $consultation_detail['user_id'] = $user->id;
    $consultation_detail['user_name'] = $user->name;
    $consultation_detail['uid'] = "@".$user->name.$user->id;
    $consultation_detail['app_sign'] = RtcTokenBuilder2::buildTokenWithUid($consultation_detail['app_id'], $consultation_detail["app_certificate"], $consultation_detail["call_id"], $consultation_detail["user_id"], 1, 86400);
    $consultation_detail['logo_url'] = asset('assets/img/logo.png');
    unset($consultation_detail['app_certificate']);

    if(!$appointment->astrologer_start_time){
      $count_down = ($starting_time+180) - time();
      $count_down_minutes = floor(($count_down % 3600) / 60);
      $count_down_seconds = $count_down % 60;
      $count_down = "$count_down_minutes:$count_down_minutes";
      return response()->json(['success'=>true, 'message' => 'Please wait for astrologer', 'status' => 'wait', 'astrologer_detail' => $astrologer_detail, 'consultation_detail' => $consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }

    if($starting_time > $current_time){
      $difference = $starting_time - $current_time;
      $hours = floor($difference / 3600);
      $minutes = floor(($difference % 3600) / 60);
      $message = "Wait: $hours:$minutes hrs";
      return response()->json(['success'=>true, 'message' => $message, 'status' => 'not now', 'astrologer_detail' => $astrologer_detail, 'consultation_detail' => $consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }elseif($ending_time < $current_time){
      return response()->json(['success'=>true, 'message' => 'Video has ended', 'status' => 'done', 'astrologer_detail'=>$astrologer_detail, 'consultation_detail'=>$consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
    }
    return response()->json(['success'=>true, 'message' => '', 'status' => 'start', 'astrologer_detail'=>$astrologer_detail, 'consultation_detail'=>$consultation_detail, 'count_down' => $count_down, 'when' => $appointment->scheduled_for ? 'later' : 'now'], 200);
  }

  public function updateVideoCallStatus(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);
    $user = $request->user();
    $consultation_id = $request->consultation_id;
    $appointment = AstrologerAppointment::where('id', $consultation_id)->where('user_id', $user->id)->where('package', 1)->first();
    if(!$appointment){
      return response()->json(['success'=>false, 'message' => 'Consultation not found.'], 404);
    }

    $consultation_detail['show_lead_popup'] = $appointment->lead_mail_sent ? false : true;
    $consultation_detail['show_lead_popup'] = false;

    $status = 'done';
    $message = '';

    $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
    $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
    $current_time = time();

    if($starting_time <= $current_time && $ending_time > $current_time){
      $appointment->user_end_time = date('H:i:s', $current_time);
      $appointment->user_status = 'done';
      if($appointment->status != "cancel"){
        $appointment->status = 'done';
      }
      $status = 'continue';

      if($request->status == 'leave'){
        if($appointment->timeslot == 5){
          $appointment->scheduled_end_time = date('H:i');
          $appointment->max_time = date('H:i', strtotime('+5minutes'));
        }
        if($appointment->feedback_mail_sent == 0){
          FeedbackConsultationJob::dispatch($appointment);
          $appointment->feedback_mail_sent = 1;
        }
        // if($appointment->lead_mail_sent == 0){
        //   $astrologer = $appointment->astrologer;
        //   EnquiryJob::dispatch($appointment, 'send_lead_confirmation', $user->name, $user->email, 'user', $user, $astrologer);
        //   $appointment->lead_mail_sent = 1;
        // }
        $status = 'done';
        $message = 'Video has ended';
      }elseif($request->status == 'cancel'){
        $appointment->user_status = 'cancel';
        $appointment->status = 'cancel';
        $status = 'cancel';
        $message = 'Video has canceled';
      }
      $appointment->save();
    }
    $status = 'continue';

    return response()->json(['success'=>true, 'message'=>$message, 'status'=>$status, 'consultation_detail' => $consultation_detail], 200);
  }

  public function search(Request $request){
    $title = $request->title;
    $search_result = LocationHistory::where('place', 'LIKE', '%' . $title . '%')->get();
    if($search_result->isEmpty()){
      $locationData = $this->searchLocationService->searchLocation($title);
      foreach($locationData as $location){
        LocationHistory::create([
          'latLonId'     => $location['latLonID'] ?? null,
          'place'        => $location['place'] ?? null,
          'state'        => $location['state'] ?? null,
          'country'      => $location['country'] ?? null,
          'latDeg'       => $location['latDeg'] ?? null,
          'latMin'       => $location['latMin'] ?? null,
          'latSec'       => $location['latSec'] ?? null,
          'ns'           => $location['ns'] ?? null,
          'longDeg'      => $location['longDeg'] ?? null,
          'longMin'      => $location['longMin'] ?? null,
          'longSec'      => $location['longSec'] ?? null,
          'ew'           => $location['ew'] ?? null,
          'timeZoneHour' => $location['timeZoneHour'] ?? null,
          'timeZoneMin'  => $location['timeZoneMin'] ?? null,
          'dstV'         => $location['dstV'] ?? null,
        ]);
      }
      $search_result = LocationHistory::where('place', 'LIKE', '%' . $title . '%')->get();
    }
    return response()->json($search_result);
  }

  public function fetchAiChatLogs(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required',
    ]);
    $appointment_id = $request->consultation_id;
    $user =  $request->user();

    $aiAstrologerAppointment = AiAstrologerAppointment::find($appointment_id);
    $chatLogs = AiAstrologerAppointmentChat::where('appointment_id', $appointment_id)->limit(PHP_INT_MAX)->offset(1)->get();

    if($chatLogs->count() == 0){
      $astrologer_id = $aiAstrologerAppointment->astrologer_id;

      $astrologer = $aiAstrologerAppointment->astrologer;
      $astrologerName = $astrologer->display_name;
      $serivcesId = AstrologerService::where('astrologer_id', $astrologer_id)->pluck('service_id')->toArray();
      $serivcesNameArray = Service::whereIn('id', $serivcesId)->pluck('name')->toArray();
      $serivcesNameString = implode(', ', $serivcesNameArray);
      $experience = date('Y') - $astrologer->job_start_year;
      // $experience = $experience ?: 1;
      $experience = 10;
      $message = "Your name is $astrologerName. You are an expert astrologer specializing in $serivcesNameString with over $experience years of experience. You provide highly accurate, personalized, and insightful astrology-based predictions, remedies, and guidance based on user-provided details such as name, date of birth, time of birth, and place of birth. Always respond in hindi or english language. Use a friendly, empathetic, and conversational tone while maintaining professionalism. If the user asks something unrelated to $serivcesNameString, politely guide them back to astrology-related topics.";

      $aiAstrologerAppointmentChat=new AiAstrologerAppointmentChat();
      $aiAstrologerAppointmentChat->appointment_id = $appointment_id;
      $aiAstrologerAppointmentChat->astrologer_id = $astrologer_id;
      $aiAstrologerAppointmentChat->user_id = $user->id;
      $aiAstrologerAppointmentChat->message = $message;
      $aiAstrologerAppointmentChat->message_by = "system";
      $aiAstrologerAppointmentChat->save();

      $formData = json_decode($aiAstrologerAppointment->form_data, true);

      $firstMessage['message_by'] = 'user';
      $firstMessage['message'] = "Hi, \nName: ".$formData["name"];
      if($gender = $formData["gender"]){
        $firstMessage['message'] .= " \nGender: ".$gender;
      }
      if($birth_date = $formData["birth_date"]){
        $firstMessage['message'] .= " \nBirth Date: ".date('d-m-Y', strtotime($birth_date));
      }
      if($birth_time = $formData["birth_time"]){
        $firstMessage['message'] .= " \nBirth Time: ".date('h:i a', strtotime($birth_time));
      }
      if($birth_place = $formData["birth_place"]){
        $firstMessage['message'] .= " \nBirth Place: ".$birth_place;
      }
      if($occupation = $formData["occupation"]){
        $firstMessage['message'] .= " \nOccupation: ".$occupation;
      }
      if($maritial_status = $formData["maritial_status"]){
        $firstMessage['message'] .= " \nMaritial Status: ".$maritial_status;
      }

      $aiAstrologerAppointmentChat=new AiAstrologerAppointmentChat();
      $aiAstrologerAppointmentChat->appointment_id = $appointment_id;
      $aiAstrologerAppointmentChat->astrologer_id = $astrologer_id;
      $aiAstrologerAppointmentChat->user_id = $user->id;
      $aiAstrologerAppointmentChat->message = $firstMessage['message'];
      $aiAstrologerAppointmentChat->message_by = $firstMessage['message_by'];
      $aiAstrologerAppointmentChat->save();

      $chatLogs = AiAstrologerAppointmentChat::where('appointment_id', $appointment_id)->limit(PHP_INT_MAX)->offset(1)->get();
    }

    $data = $chatLogs->map(function($item){
      $d['message_by']= $item->message_by == 'user' ? 'user' : 'astrologer';
      $d['message']=$item->message;
      $d['created_at']=date('h:i a', strtotime($item->created_at));
      return $d;
    })->toArray();

    // $data = array_merge($systmeGenrateMessages, $data);

    $status="completed";
    $start_at = strtotime("now")-strtotime($aiAstrologerAppointment->start_at);
    $end_at=0;
    if($aiAstrologerAppointment->paid==0){
      $status="continue";
      $now=strtotime("now");
      $userWalletAmount=$user->wallet;
      $perMin=$aiAstrologerAppointment->per_min_charge;
      $end_at=ceil(($userWalletAmount/$perMin)*60);
      $end_at-=$start_at;
      $end_at-=70;
    }
    return response()->json([
      'success'=>true,
      'data'=>$data,
      'status'=>$status,
      'start_at'=>$start_at,
      'end_at'=>$end_at
    ], 200);
  }

  public function sendMessageToAi(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required|integer|gt:0',
      'message'=>'required|string|max:1000'
    ]);
    $consultation_id=$request->consultation_id;
    $message=$request->message;

    $aiAstrologerAppointment=AiAstrologerAppointment::find($consultation_id);
    $user_id=$aiAstrologerAppointment->user_id;
    $astrologer_id=$aiAstrologerAppointment->astrologer_id;

    $aiAstrologerAppointmentChat=new AiAstrologerAppointmentChat();
    $aiAstrologerAppointmentChat->appointment_id=$consultation_id;
    $aiAstrologerAppointmentChat->astrologer_id=$astrologer_id;
    $aiAstrologerAppointmentChat->user_id=$user_id;
    $aiAstrologerAppointmentChat->message=$message;
    $aiAstrologerAppointmentChat->message_by='user';
    $aiAstrologerAppointmentChat->save();

    // $gptResponse=GptResponse::where('prompt', 'like', "%$message%")->where("response", "!=", "")->first();
    $gptResponse=null;
    $response="";
    if($gptResponse){
      $response=$gptResponse?json_decode($gptResponse->response, true):"";
    }else{
      $newRequest=new Request([
        "prompt"=>$message,
        "user_id"=>$user_id,
        "consultation_id"=>$consultation_id
      ]);
      $gptController=new GptController();
      $response=$gptController->getResponse($newRequest);
      if($response){
        $response=$response->json();
      }
    }
    $response=isset($response["output"])?$response["output"][0]["content"][0]["text"]:"No Response";
    $response = preg_replace('/[#*`_~]+/', '', $response);
    $response = preg_replace('/\s+/', ' ', $response);
    $response = trim($response);

    $aiAstrologerAppointmentChat=new AiAstrologerAppointmentChat();
    $aiAstrologerAppointmentChat->appointment_id=$consultation_id;
    $aiAstrologerAppointmentChat->astrologer_id=$astrologer_id;
    $aiAstrologerAppointmentChat->user_id=$user_id;
    $aiAstrologerAppointmentChat->message=$response;
    $aiAstrologerAppointmentChat->message_by='astrologer';
    $aiAstrologerAppointmentChat->save();

    $d['message_by']='astrologer';
    $d['message']=$response;
    $d['created_at']=date('h:i a', strtotime($aiAstrologerAppointmentChat->created_at));

    return response()->json(['success'=>true, 'message' => 'message sent.', 'response'=>$d], 200);
  }

  public function updateAiChatTime(Request $request){
    $this->validate($request, [
      'consultation_id'=>'required'
    ]);
    $user = $request->user();
    $status = $request->status ?: "continue";
    $message = "Status Updated";
    $appointment_id = $request->consultation_id;
    $aiAstrologerAppointment = AiAstrologerAppointment::find($appointment_id);
    $aiAstrologerAppointment->end_at=date('Y-m-d H:i:s');

    if($status == "end"){
        $startAt = strtotime($aiAstrologerAppointment->start_at);
        $endAt = strtotime($aiAstrologerAppointment->end_at);
        $totalSeconds = $endAt-$startAt;
        $totalMinutes = ceil($totalSeconds/60);
        $totalCharges = $aiAstrologerAppointment->per_min_charge*$totalMinutes;
        $aiAstrologerAppointment->charges = $totalCharges;
        $aiAstrologerAppointment->paid = 1;
        $aiAstrologerAppointment->remark = "End Manually";

        if($aiAstrologerAppointment->free == 1){
          $freeAppointmentPrice = $aiAstrologerAppointment->per_min_charge*5;

          if($freeAppointmentPrice < $totalCharges){
            $aiAstrologerAppointment->discount =  $freeAppointmentPrice;
          }else{
            $aiAstrologerAppointment->discount =  $totalCharges;
          }

          $totalCharges -= $freeAppointmentPrice;

          if($totalCharges < 0){
            $totalCharges = 0;
          }
        }

        // debit consultation amount from wallet
        $user->wallet -= $totalCharges;
        $user->save();

        // user wallet transition
        $walletTransition = new WalletTransition();
        $walletTransition->user_id = $user->id;
        $walletTransition->user_type = "user";
        $walletTransition->table_id = $aiAstrologerAppointment->id;
        $walletTransition->table_name = "ai_astrologer_appointments";
        $walletTransition->action = 0; // debit
        $walletTransition->status = 1; // success
        $walletTransition->remark = "Completed Ai Astrologer Consultation";
        $walletTransition->invoice = 0;
        $walletTransition->amount = $totalCharges;
        $walletTransition->save();
        $message = "Chat has been ended.";
    }
    $aiAstrologerAppointment->save();

    $status = $aiAstrologerAppointment->paid ? "completed" : "continue";

    return response()->json(['success'=>true, 'message'=> $message, 'status'=>$status], 200);
  }

}
