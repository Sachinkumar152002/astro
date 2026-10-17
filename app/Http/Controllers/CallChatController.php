<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\CallHistory;
use App\Model\Chat;
use App\Model\ChatLog;
use App\Jobs\GetLiveCalls;
use App\Model\LiveCall;
use App\Model\Astrologer;
use App\Jobs\CallHangUp;
use App\Jobs\McubeCallResponseJob;
use Carbon\Carbon;
use App\Model\AstrologerAppointment;
use App\Jobs\{FeedbackConsultationJob};
use App\Http\Controllers\Astrologer\LoginController;

class CallChatController extends Controller
{
    public function makecall(Request $request)
    {
        $id = $request->id;
        $user = auth()->guard('web')->user();

        $callHistory = CallHistory::where('call_id', $id)->where('user_id', $user->id)->first();
        if(!$callHistory) abort(404);

        return view(themeView().'account.call', compact('callHistory'));
    }

    public function callrequest(Request $request)
    {
        $id = decrypt($request->id);
        $call = CallHistory::find($id);
        $response = clicktocall($call->from_number, $call->to_number, $call->call_id, $call->max_minute*60);
        if(isset($response['callid']) && $response['callid']) {
            $call->service_call_id = $response['callid'];
            $call->astrologer_status = 'Answered';
            $call->save();
            return response()->json( ['message' => 'Astrologer Connected. Please pick your call in seconds', 'status'=>1] , 200);
        }
        $call->astrologer_status = 'Missed';
        $call->save();
        return response()->json( ['message' => 'Astrologer is not able to take your call. Please try later', 'status'=>0, 'response'=>$response] , 201);
    }

    public function checkLiveCall(Request $request)
    {
        return response()->json(['is_running'=>0]);
    }

    public function handleCallResponse(Request $request)
    {
        McubeCallResponseJob::dispatch($request->all());
    }

    public function makechat(Request $request){
      $consultation_id = $request->id;
      $user = auth()->guard('web')->user();
      $user_type = 'user';
      $appointment = AstrologerAppointment::where('id', $consultation_id)->where('package', 2)->where('user_id', $user->id)->first();
      if(!$appointment) abort(404);
      $chat = Chat::where('chat_key', $consultation_id)->first();
      if(!$chat) abort(404);
      $current_time = date('H:i:s');
      $scheduled_time = $appointment->scheduled_time;
      $scheduled_end_time = $appointment->scheduled_end_time;
      $chat_id = $chat->id;
      if(!$appointment->user_start_time){
        $appointment->user_status='joined';
        $appointment->user_start_time = date('H:i:s');
        $appointment->save();
      }
      $start_time = time()-strtotime($appointment->user_start_time);
      if($appointment->astrologer_start_time){
        $start_time = time()-strtotime($appointment->astrologer_start_time);
      }
      $end_time = Carbon::parse($appointment->scheduled_end_time);
      $hideFooter = true;
      $remind_at = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-6minutes');
      $astrologer = $appointment->astrologer;
      $count_down_end_time = null;
      if($appointment->count_down_end_time){
        $count_down_end_time = Carbon::parse($appointment->count_down_end_time);
      }
      return view(themeView().'account.chat', compact('appointment', 'chat','user_type', 'user', 'start_time', 'end_time', 'count_down_end_time', 'consultation_id', 'chat_id', 'hideFooter', 'remind_at', 'astrologer'));
    }

    public function chatrequest(Request $request){
      $id = $request->id;
      $timer = '';
      $chat = Chat::where('chat_key',$id)->first();
      $dataChat = Chat::where('chat_key',$id)->with(['getchatlog' => function($query) {
        $query->select('*', \DB::raw("DATE_FORMAT(created_at, '%h:%i %p') as chat_time"));
      }])->get();

      if($request->slug == 'init'){
        $chatlog = new ChatLog();
        if($request->message != ''){
          $chatlog->user_id = $chat->user_id;
          $chatlog->astrologer_id = $chat->astrologer_id;
          $chatlog->chat_id = $chat->id;
          $chatlog->message = $request->message;
          if($request->user_type == 'astrologer') {
            $chatlog->message_by = 'astrologer';
          } else {
            $chatlog->message_by = 'user';
          }
          $chatlog->save();
        }
      }
      if($request->slug == 'get') {
        $chat->remaining_time_in_seconds = $chat->remaining_time_in_seconds - 1;
        $chat->chat_duration_in_sec = $chat->chat_duration_in_sec + 1;
        $chat->save();

        if($request->user_type == 'user' && $chat->remaining_time_in_seconds <= 0) {
          $chat->end_time = date('H:i:s');
          if($chat->save()) {
            $astrologer = Astrologer::where('id', $chat->astrologer_id)->first();
            $astrologer->chat = 1;
            $astrologer->call = 1;
            $astrologer->save();
          }
        }

        if($request->user_type == 'astrologer') {
          $value = $chat->chat_duration_in_sec;
        } else {
          $value = $chat->remaining_time_in_seconds;
        }
        $dt = Carbon::now();
        $days = $dt->diffInDays($dt->copy()->addSeconds($value));
        $hours = $dt->diffInHours($dt->copy()->addSeconds($value)->subDays($days));
        $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($value)->subDays($days)->subHours($hours));
        $second = $dt->diffInSeconds($dt->copy()->addSeconds($value)->subDays($days)->subHours($hours)->subMinutes($minutes));

        if($hours < 10) {
          $hours = "0".$hours;
        }
        if($minutes < 10) {
          $minutes = "0".$minutes;
        }
        if($second < 10) {
          $second = "0".$second;
        }

        $timer = $hours.":".$minutes.":".$second;
      }
      if($request->slug == 'end') {
        $chat->end_time = date('H:i:s');
        if($chat->save()) {
          $astrologer = Astrologer::where('id', $chat->astrologer_id)->first();
          $astrologer->chat = 1;
          $astrologer->call = 1;
          $astrologer->save();
        }
      }
      if($request->slug == 'update'){
        if($request->user_type == 'astrologer') {
          $chat->astrologer_status = $request->status;
          $chat->astrologer_last_seen = date('Y-m-d H:i:s');
        } elseif($request->user_type == 'user') {
          $chat->user_status = $request->status;
          $chat->user_last_seen = date('Y-m-d H:i:s');
        }
        $chat->save();
      }
      return response()->json( ['message' => 'Chat Show', 'records' => $dataChat, 'timer' => $timer, 'status'=>1] , 200);
    }

    public function fetchChatLogs($chat_id){ // fetch chats for users
      $current_time = date('H:i:s');
      $chat = Chat::find($chat_id);
      if(!$chat) return response()->json(['message' => 'Chat not found.'], 404);
      $chat->user_last_seen = $current_time;
      $chat->save();
      $appointment = AstrologerAppointment::find($chat->chat_key);
      if(date('H:i:00') < $appointment->scheduled_end_time){
        $appointment->user_end_time = $current_time;
        $appointment->user_status = 'done';
      }
      $appointment->save();
      $user = auth()->guard('web')->user();
      if($chat->user_id != $user->id) return response()->json(['message' => 'Chat not found.'], 404);
      $thirty_seconds = date('H:i:s', strtotime($appointment->scheduled_end_time)-30);
      $two_minutes = date('H:i:s', strtotime($appointment->scheduled_end_time)-120);
      $chatLogs = ChatLog::where('chat_id', $chat_id)->orderBy('created_at', 'asc')->get();
      $total = $chatLogs->count();
      $data = view(themeView().'account.chats',['results'=>$chatLogs, 'appointment'=>$appointment])->render();
      $timeSlot = $appointment->timeslot;
      $user_time = strtotime($appointment->user_end_time);
      $astrologer_time = strtotime($appointment->astrologer_end_time);
      $user_astrologer_dif = abs($user_time - $astrologer_time);
      $is_astrologer_offline = false;
      $chat_end_time = date('H:i', strtotime('now + 15 seconds'));
      $appointment->count_down_end_time = $chat_end_time;
      $appointment->save();
      $chat_end_time = Carbon::parse($chat_end_time);
      if($user_astrologer_dif>5){
        if($user_time>$astrologer_time){
          $is_astrologer_offline = true;
        }
      }
      $astrologer = Astrologer::find($appointment->to_astrologer_id);
      if($timeSlot == 5){
        if($current_time > $appointment->max_time){
          $astrologer->chat = 1;
          $astrologer->save();
          FeedbackConsultationJob::dispatch($appointment);
          $appointment->feedback_mail_sent = 1;
          $appointment->save();
          return response()->json(['status'=>'done', 'message' => 'Time is Over', 'data'=>$data, 'total'=>$total], 200);
        }elseif($current_time > $appointment->scheduled_end_time){
          $astrologer->chat = 1;
          $astrologer->save();
          FeedbackConsultationJob::dispatch($appointment);
          $appointment->feedback_mail_sent = 1;
          $appointment->save();
          return response()->json(['status'=>'beforeMaxTime', 'message' => 'Time is over because of low balance', 'data'=>$data, 'total'=>$total], 200);
        }elseif($current_time > $thirty_seconds){
          if($appointment->max_time > $appointment->scheduled_end_time){
            $total_price = $appointment->actualprice*5;
            if($user->wallet >= $total_price){
              $user->wallet = $user->wallet-$total_price;
              $user->save();
              $bonus_time = date('H:i', strtotime($appointment->scheduled_date.' '.$appointment->scheduled_end_time)+300);
              $appointment->scheduled_end_time = $bonus_time;
              $appointment->total_price += $total_price;
              $appointment->is_notification_sent = 0;
              $appointment->save();
              $appointment->astrologer_amount = (($appointment->total_price - $appointment->astrologer_maintainance_charge)/100)*$appointment->astrologer_amount_ratio;
              $appointment->save();
            }
          }
        }elseif(!$appointment->is_notification_sent && $current_time > $two_minutes){
          if($appointment->max_time > $appointment->scheduled_end_time){
            $total_price = $appointment->actualprice*5;
            if($user->wallet < $total_price){
              $appointment->is_notification_sent = 1;
              $appointment->save();
              return response()->json(['status'=>'alert', 'message' => 'If you want to continue, Please recharge.', 'data'=>$data], 200);
            }
          }
        }
      }else{
        if($current_time > $appointment->scheduled_end_time){
          $astrologer->chat = 1;
          $astrologer->save();
          FeedbackConsultationJob::dispatch($appointment);
          $appointment->feedback_mail_sent = 1;
          $appointment->save();
          return response()->json(['status'=>'done', 'message' => 'Time is Over', 'data'=>$data, 'total'=>$total], 200);
        }
      }
      $now = Carbon::now();
      $endTime = Carbon::createFromTimestamp(strtotime($appointment->scheduled_end_time));
      $difference = $now->diff($endTime);
      $endTime = ($difference->h<10?'0'.$difference->h:$difference->h).':'.($difference->i<10?'0'.$difference->i:$difference->i).':'.($difference->s<10?'0'.$difference->s:$difference->s);

      return response()->json([
          'status'=>'running',
          'message'=>'chats',
          'data' => $data,
          'total'=>$total,
          'is_astrologer_offline'=>$is_astrologer_offline,
          'chat_end_time'=>$chat_end_time,
          'endTime'=>$endTime,
      ],200);
    }


      public function fetchChatLogsForAstro($chat_id){ //fetch chats for astrologers
        $current_time = date('H:i:s');
        $chat = Chat::find($chat_id);
        if(!$chat) return response()->json(['message' => 'Chat not found.'], 404);
        $chat->astrologer_last_seen = date('Y-m-d H:i:s');
        $chat->save();
        $appointment = AstrologerAppointment::find($chat->chat_key);
        if(date('H:i:00') < $appointment->scheduled_end_time){
          $appointment->astrologer_end_time = $current_time;
          $appointment->status = 'done';
        }
        $appointment->save();
        $user = auth()->guard('astrologer')->user();
        if($chat->astrologer_id != $user->id) return response()->json(['message' => 'Chat not found.'], 404);
        $chatLogs = ChatLog::where('chat_id', $chat_id)->orderBy('created_at', 'asc')->get();
        $total = $chatLogs->count();
        $data = view(themeView().'astrologers.chats',['results'=>$chatLogs, 'appointment'=>$appointment])->render();
        $user_time = strtotime($appointment->user_end_time);
        $astrologer_time = strtotime($appointment->astrologer_end_time);
        $user_astrologer_dif = abs($user_time - $astrologer_time);
        $is_user_offline = false;
        if($user_astrologer_dif>5){
          if($astrologer_time>$user_time){
            $is_user_offline = true;
          }
        }
        $astrologer = Astrologer::find($appointment->to_astrologer_id);
        if($current_time > $appointment->max_time){
          $astrologer->chat = 1;
          $astrologer->save();

          $loginController = new LoginController();
          $loginController->userNotification($astrologer, 'user', 'astrologer online');

          // $user->wallet += $appointment->astrologer_amount;
          // $user->save();
          return response()->json(['status'=>'done', 'message' => 'Time is Over', 'data'=>$data, 'total'=>$total], 200);
        }elseif($current_time > $appointment->scheduled_end_time){
          $astrologer->chat = 1;
          $astrologer->save();

          $loginController = new LoginController();
          $loginController->userNotification($astrologer, 'user', 'astrologer online');

          // $user->wallet += $appointment->astrologer_amount;
          // $user->save();
          return response()->json(['status'=>'done', 'message' => 'Time is Over', 'data'=>$data, 'total'=>$total], 200);
        }
        $now = Carbon::now();
        $endTime = Carbon::createFromTimestamp(strtotime($appointment->scheduled_end_time));
        $difference = $now->diff($endTime);
        $endTime = ($difference->h<10?'0'.$difference->h:$difference->h).':'.($difference->i<10?'0'.$difference->i:$difference->i).':'.($difference->s<10?'0'.$difference->s:$difference->s);
        return response()->json([
          'status'=>'running',
          'message'=>'chats',
          'data' => $data,
          'total'=>$total,
          'endTime'=>$endTime,
          'is_user_offline'=>$is_user_offline,
        ],200);
      }

    // Send a message
    public function sendMessage(Request $request)
    {
        $chatLog = ChatLog::create([
            'chat_id' => $request->chat_id,
            'user_id' => $request->user_id,
            'astrologer_id' => $request->astrologer_id,
            'message' => $request->message,
            'message_by' => $request->message_by,
        ]);

        return response()->json($chatLog);
    }
}
