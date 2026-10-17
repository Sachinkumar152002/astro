<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\{AstrologerAppointment, Astrologer};
use App\{AstrologerUserConsultacy};
use App\Jobs\{FeedbackConsultationJob};

class VideoCallController extends Controller
{
    public function index(Request $request){
      if(!$request->has('roomID')) return abort(404);
      $current_time = date('H:i:s');
      $id = $request->appointment_id;
      $appointment = AstrologerAppointment::where('id', $id)->where('package', 1)->first();
      if(!$appointment) return abort(404);
      $current_time = date('H:i:s');
      $scheduled_time = $appointment->scheduled_time;
      $scheduled_end_time = $appointment->scheduled_end_time;
      if(($current_time < $scheduled_time) || ($current_time > $scheduled_end_time)) return abort(404);
      $appointment->user_status='ready_to_join';
      $appointment->user_end_time=$current_time;
      $appointment->save();
      $consultancy = AstrologerUserConsultacy::where('astrologer_appointment_id', $id)->first();
      if(!$consultancy) return abort(404);
      $user_type = $appointment->user_type;
      $user =  null;
      if($appointment->user_type == 'user'){
        $user = auth()->guard('web')->user();
      }elseif($appointment->user_type == 'astrologer'){
        $user = auth()->guard('astrologer')->user();
      }
      if(!$user || ($user->id != $appointment->from_user_id)) return abort(404);
      $roomID = explode('roomID=', $consultancy->meeting_link)[1];
      if($request->roomID != $roomID) return abort(404);
      $scheduled_end_time = $appointment->scheduled_end_time;
      if($current_time > $scheduled_end_time) return abort(404);
      $user_name = $user->name;
      $random_number = rand(1000, 9999);
      $videoCallUrl = asset('account/'.$consultancy->meeting_link);
      $end_time = Carbon::parse($appointment->scheduled_end_time);
      $astrologer = Astrologer::find($appointment->to_astrologer_id);
      $astrologer->video = 3;
      $astrologer->save();
      return view(themeView().'videocall.index', compact('appointment', 'user_name', 'random_number', 'videoCallUrl', 'consultancy', 'roomID', 'user', 'end_time'));
    }

    public function updateStatus(Request $request){
      $id = $request->appointment_id;
      $appointment = AstrologerAppointment::find($id);
      if(!$appointment){
        return response()->json(['status'=>false, 'message' => 'Not Found'], 402);
      }
      $end_time = date('H:i:s', strtotime('now'));
      // $appointment->user_status = $request->status;
      $appointment->user_status = 'done';
      $appointment->user_end_time = $end_time;
      $appointment->save();
      if(date('H:i:00') > $appointment->scheduled_end_time){
        $astrologer = Astrologer::find($appointment->to_astrologer_id);
        $astrologer->video = 1;
        $astrologer->save();
        FeedbackConsultationJob::dispatch($appointment);
        $appointment->feedback_mail_sent = 1;
        $appointment->save();
      }
      return response()->json(['success'=>true,'msg'=>'status and time update'],200);
    }
}
