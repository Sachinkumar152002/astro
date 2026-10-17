<?php

namespace App\Http\Controllers;

use App\Services\ExotelService;
use Illuminate\Http\Request;
use App\Model\Astrologer;
use App\Model\AstrologerAppointment;
use GuzzleHttp\Client;
use App\Model\CallHistory;
use App\Model\WalletTransition;
use App\User;
use App\Jobs\{FeedbackConsultationJob, EnquiryJob, ConsultationFailedJob};

class ExotelController extends Controller
{
    protected $exotelService;

    public function __construct(ExotelService $exotelService)
    {
        $this->exotelService = $exotelService;
    }
    public function clickToCall(Request $request){
      $current_time = date('H:i:s');
      $call_history_id = $request->call_history_id;
      $callHistory = CallHistory::find($call_history_id);
      if(!$callHistory) return response()->json(['success'=>false, 'message'=>'Call not found.'], 402);
      $counter = $callHistory->counter;
      $appointment = AstrologerAppointment::find($callHistory->appointment_id);
      if(!$appointment) return response()->json(['success'=>false, 'message'=>'Appointment not found.'], 402);
      $scheduled_time = $appointment->scheduled_time;
      if($current_time < $scheduled_time) return response()->json(['success'=>false, 'message'=>'Not yet.'], 402);
      $scheduled_end_time = $appointment->max_time;
      if($current_time > $scheduled_end_time) return response()->json(['success'=>false, 'message'=>'Time is over.'], 402);
      $user = User::find($appointment->user_id);
      if($user->id != $callHistory->user_id) return response()->json(['success'=>false, 'message'=>'Not Authorized.'], 402);
      $appointment->user_start_time = date('H:i:s');
      $appointment->astrologer_start_time = date('H:i:s');
      $appointment->save();
      $timeSlot = $appointment->timeslot;
      $actualprice = $appointment->actualprice;
      $timeOut = $timeSlot*60;
      if($timeSlot == 5){
        $totalMinute = floor((strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes')-time())/60);
        if($totalMinute > 240){
          $totalMinute = 230;
        }
        if($totalMinute > 0){
          for($i=$totalMinute; $i > 0; $i--){
            if($user->wallet >= $actualprice*$i){
              $timeOut = ($timeSlot+$i)*60;
              break;
            }
          }
        }
      }
      // $timeOut = 120;
      $callHistory->call_duration_in_sec = $timeOut;
      $callHistory->save();
      $from=$callHistory->from_number;
      $to=$callHistory->to_number;
      $response = $this->exotelService->clickToCall($from, $to, $timeOut, $appointment->id);
      if (isset($response['error']) && $response['error']) {
          $callHistory = CallHistory::find($call_history_id);
          $callHistory->callSid = 0;
          $callHistory->callStatus = 'cancel';
          $callHistory->call_start_time = date('H:i');
          $callHistory->call_end_time = date('H:i');
          // $callHistory->call_duration_in_sec = 0;
          $callHistory->total_charges = 0;
          $callHistory->recordingUrl = '';
          $callHistory->callUri = '';
          $callHistory->accountSid = config('vrdcms.exotel_sid');
          $callHistory->response = $response;
          $callHistory->save();
          $appointment->status = 'cancel';
          $appointment->user_status = 'cancel';
          $appointment->user_end_time = date('H:i:s');
          $appointment->astrologer_end_time = date('H:i:s');
          $appointment->save();
          // $astrologer = $appointment->astrologer;
          // ConsultationFailedJob::dispatch($appointment, $user->name, $user->email, 'user', $user, $astrologer);
          // ConsultationFailedJob::dispatch($appointment, $astrologer->display_name, $useastrologerr->email, 'astrologer', $user, $astrologer);
          // ConsultationFailedJob::dispatch($appointment, 'Admin', config('vrdcms.admin_email'), 'admin', $user, $astrologer);
          return response()->json([
              'success' => false,
              'message' => 'Failed to initiate the call',
              'error' => $response,
          ], 500);
      }elseif(isset($response['Call'])){
           $callData = $response['Call'];
           $duration = $callData['Duration'];
           $callHistory = CallHistory::find($call_history_id);
           // $callHistory->is_initiated = 1;
           $callHistory->initiated_time = date('Y-m-d H:i');
           $callHistory->callSid = $callData['Sid'];
           $callHistory->callStatus = 'joined';
           $callHistory->call_start_time = $callData['StartTime'];
           $callHistory->call_end_time = $callData['EndTime'];
           // $callHistory->call_duration_in_sec = $duration;
           $callHistory->total_charges = $callData['Price'];
           $callHistory->recordingUrl = $callData['RecordingUrl'];
           $callHistory->callUri = $callData['Uri'];
           $callHistory->accountSid = $callData['AccountSid'];
           // $callHistory->response = $response;
           $callHistory->save();
           $appointment->status = 'joined';
           $appointment->user_status = 'joined';
           $appointment->save();
       }
       $astrologer = Astrologer::find($appointment->astrologer_id);
       $astrologer->call = 3;
       $astrologer->save();
      return response()->json([
          'success' => true,
          'message' => 'Call initiated successfully',
          'data' => $response,
      ]);
    }

    public function statusCallback(Request $request){
      $appointment_id = $request->appointment_id;
      $response = $request->all();
      $appointment = AstrologerAppointment::find($appointment_id);
      $duration = 0;
      $userDuration = 0;
      if($appointment->try < 2){
        $appointment->try += 1;
      }
      $appointment->save();
      if(isset($response['Legs'])){
        $duration = (int) $response['Legs'][0]['OnCallDuration'];
        $userDuration = (int) $response['Legs'][1]['OnCallDuration'];
      }
      if(isset($response['StartTime']) && $response['StartTime']){
        $start_time = date('H:i:s', strtotime($response['StartTime']));
        $appointment->user_start_time = $start_time;
        $appointment->astrologer_start_time = $start_time;
      }
      $end_time = date('H:i:s');
      if(isset($response['EndTime']) && $response['EndTime']){
        $end_time = date('H:i:s', strtotime($response['EndTime']));
      }
      if($appointment->try == 2){
        $appointment->status = $duration > 0 ? 'done' : 'cancel';
        $appointment->astrologer_end_time = $duration > 0 ? $end_time : null;
        $appointment->user_status = $userDuration > 0 ? 'done' : 'cancel';
        $appointment->user_end_time = $userDuration > 0 ? $end_time : null;
      }else{
        $appointment->status = $duration > 0 ? 'done' : 'booked';
        $appointment->astrologer_end_time = $duration > 0 ? $end_time : null;
        $appointment->user_status = $userDuration > 0 ? 'done' : 'booked';
        $appointment->user_end_time = $userDuration > 0 ? $end_time : null;
      }
      $appointment->save();
      $callHistory = CallHistory::where('appointment_id', $appointment_id)->first();
      $callHistory->call_end_time = $end_time;
      $callHistory->call_hangup_time = $end_time;
      $callHistory->callStatus = $response['Status'];
      $callHistory->call_duration_in_sec = $duration;
      $callHistory->recordingUrl = isset($response['RecordingUrl']) ? $response['RecordingUrl'] : '';
      $callHistory->response = json_encode($request->all());
      $callHistory->save();
      $user = User::find($appointment->user_id);
      if($userDuration > 0){
        if($appointment->timeslot == 5){
          $charge = $appointment->actualprice*ceil($userDuration/60);

          $appointment->total_price = $charge;
          $appointment->paid = 1;
          $appointment->astrologer_amount = ($charge/100)*$appointment->astrologer_amount_ratio;
          $appointment->scheduled_end_time = $end_time;
          $appointment->save();
          $appointment->max_time = date('H:i', strtotime($appointment->scheduled_end_time.'+5minutes'));
          $appointment->save();

          $user->wallet -= $charge;
          $user->save();

          $walletTransition = WalletTransition::where('user_id', $user->id)->where('user_type', 'user')->where('table_id', $appointment->id)->where('table_name', 'astrologer_appointments')->where('action', 0)->where('status', 1)->first();
          if(!$walletTransition){
            $walletTransition = new WalletTransition();
            $walletTransition->user_id = $user->id;
            $walletTransition->user_type = "user";
            $walletTransition->table_id = $appointment->id;
            $walletTransition->table_name = "astrologer_appointments";
            $walletTransition->action = 0; //debit
            $walletTransition->status = 1;
          }
          $walletTransition->amount = $appointment->total_price;
          $walletTransition->save();
        }
      }elseif($appointment->try == 2){
        // $user->wallet += $appointment->total_price;
        // $user->save();
      }
      $astrologer = Astrologer::find($appointment->astrologer_id);
      $astrologer->call = 1;
      $astrologer->save();
      if($userDuration > 0){
        if($appointment->feedback_mail_sent==0){
          FeedbackConsultationJob::dispatch($appointment);
          $appointment->feedback_mail_sent = 1;
        }
        // if($appointment->lead_mail_sent == 0){
        //   $user = $appointment->user;
        //   $astrologer = $appointment->astrologer;
        //   EnquiryJob::dispatch($appointment, 'send_lead_confirmation', $user->name, $user->email, 'user', $user, $astrologer);
        //   $appointment->lead_mail_sent = 1;
        // }
        $appointment->astrologer_start_time = date('H:i:s', strtotime($appointment->scheduled_date.' '.$end_time.'-'.$duration.'seconds'));
        $appointment->user_start_time = date('H:i:s', strtotime($appointment->scheduled_date.' '.$end_time.'-'.$userDuration.'seconds'));
        $appointment->save();
      }elseif($appointment->try == 1){
        $newRequest = new Request([
          'call_history_id'=>$callHistory->id
        ]);
        $this->clickToCall($newRequest);
      }
    }

    public function fetchCallDetails($sid){
     // dd("hello");
       $client = new Client();
       // $url = "https://api.exotel.com/v1/Accounts/dixinfotech1/Calls/{$sid}.json";
       // $auth = base64_encode('dixinfotech1:5eb56b57235df8625885c36b3b5f86e129f06c90d0a2c311'); // Replace with your SID and Token
       $url = "https://api.exotel.com/v1/Accounts/{$sid}/Calls/{$sid}.json";
       $auth = base64_encode(env('EXOTEL_SID')).':'.env('EXOTEL_API_TOKEN'); // Replace with your SID and Token

       try {
           $response = $client->request('GET', $url, [
               'headers' => [
                   'Authorization' => 'Basic ' . $auth,
                   'Accept'        => 'application/json',
               ],
           ]);

           $responseBody = json_decode($response->getBody(), true);

           if (isset($responseBody['Call'])) {
               return response()->json([
                   'success' => true,
                   'data' => $responseBody['Call'],
               ]);
           }

           return response()->json([
               'success' => false,
               'message' => 'Call details not found',
           ], 404);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Failed to fetch call details',
               'error' => $e->getMessage(),
           ], 500);
       }
   }
  public function  previewpage(){
    return view(themeView().'calltest.index');
  }
}
