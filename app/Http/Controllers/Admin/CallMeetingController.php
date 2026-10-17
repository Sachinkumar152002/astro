<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Astrologer;
use App\AstrologerUserConsultacy;
use App\Model\AstrologerAppointment;
use App\Model\PayoutPackage;
use App\Model\Setting;
use App\Model\ConsultationCounter;
use App\Model\AstrologerTimeTable;
use App\User;
use App\Model\CallHistory;
use App\Model\ConsultationRequest;
use App\Model\ConsultationRequestResponse;
use App\Jobs\{ConsultationCancelJob, ConstultatonRescheduleJob};
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConsultationCancelNotification;
use App\Notifications\ConsultationRescheduleNotification;
use DB;
class CallMeetingController extends Controller
{
    private $module = 'callmeeting';
    private $moduleName = 'Call Meeting';
    private $moduleNameSingle = 'Call Meeting ';
    private $userType = 'Astrologer';

    public function __construct(){
      $this->userType = request()->type;
    }

    public function index(Request $request){
        $userType = $this->userType;
        if(!adminCan(strtolower($userType).'-call-consultation','view')) return dd('No Permission');
        $nav = 'call_consultation';
        $breads = [
            ['name'=>'Call Consultation']
        ];
        $moduleName = $this->moduleName;
        return view('admin.callmeeting.list', compact('breads','nav','moduleName', 'userType'));
    }
    public function all(Request $request)
    {
      //schedule_for:
      //0=now
      //1=later
      //timeslot:-
      //now:-
      //5-15, 15, 30
      //later:-
      //15, 30
        $userType = $this->userType;
        $astrologers_id = Astrologer::where('type', $userType)->pluck('id')->toArray();
        $query = AstrologerAppointment::whereIn('astrologer_id', $astrologers_id)->where('package', 3)->orderBy('updated_at', 'desc');

        if($user_name = $request->user_name){
          $users = User::where('name', 'like', "%$user_name%")->pluck('id')->toArray();
          $query->whereIn('from_user_id', $users);
        }
        if($astrologer_name = $request->astrologer_name){
          $astrologer = Astrologer::where('display_name', 'like', "%$astrologer_name%")->pluck('id')->toArray();
          $query->whereIn('to_astrologer_id', $astrologer);
        }
        if($request->has('scheduled_for') && is_numeric($request->scheduled_for)){
          $query->where('scheduled_for', $request->scheduled_for);
        }
        if($timeslot = $request->timeslot){
          $query->where('timeslot', $timeslot);
        }
        if($total_price_from = $request->total_price_from){
          $query->where('total_price', '>=', $total_price_from);
        }
        if($total_price_to = $request->total_price_to){
          $query->where('total_price', '<=', $total_price_to);
        }
        if($status = $request->status){
          if($status == 'complete'){
            $complete = ['completed','done'];
            $query->whereIn('status', $complete);
          }elseif($status == 'booked'){
            $booked = ['booked'];
            $query->whereIn('status', $booked);
          }elseif($status == 'reschedule'){
            // $other = ['completed', 'done', 'booked', 'pending'];
            $query->where('reschedule', 1);
          }elseif($status == 'pending'){
            $pending = ['pending'];
            $query->whereIn('status', $pending);
          }elseif($status == 'other'){
            $other = ['completed', 'done', 'booked', 'pending'];
            $query->whereNotIn('status', $other);
          }
        }
        if($updated_at_from = $request->updated_at_from){
          $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
        }
        if($updated_at_to = $request->updated_at_to){
          $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
        }
        $results = $query->paginate(19);

        $data = [
            'rows' => view('admin.callmeeting.listrows', ['results' => $results, 'userType'=>$userType])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
        ];

        return response()->json($data, 200);
    }

    public function viewData(Request $request)
    {
      $userType = $this->userType;
       $id = $request->id;

       $nav = 'call_consultation';
       $moduleName = $this->moduleNameSingle;
       $breads = [
            ['name'=>'Call Consultation', 'url'=> route('admin.consultation.call.index', [$userType])],
            ['name'=>($id ? 'Edit': 'Add')]
       ];
        $data = AstrologerAppointment::find($id);
        if(!$data) return 'false';
        $user = User::find($data->user_id);
        $wallet_price = $user ? $user->wallet : 0;
        $wallet_price += $wallet_price + $data->total_price;
        $consultation_counter = ConsultationCounter::where('appointment_id', $data->id)->first();
        $astrologer = $data->astrologer;
        $user = $data->user;
        $astrologers = DB::table('astrologers')->select('id', 'display_name', DB::raw("
        (CASE
            WHEN sp_from_date IS NOT NULL AND sp_from_date <= '".date('Y-m-d')."' AND sp_to_date IS NOT NULL AND sp_to_date >= '".date('Y-m-d')."' THEN call_special_price
            ELSE call_price
        END)*".$data->timeslot." AS applicable_price
        "))
        ->where('type', $astrologer?$astrologer->type:'astrologer')
        ->where('status', 1)
        ->where('is_verified', 1)
        ->where('deleted_at', null)
        ->whereRaw('1=1 having applicable_price <= ? and applicable_price>0', [$wallet_price])->pluck('display_name', 'id')->toArray();
        return view('admin.callmeeting.view', compact('nav', 'breads', 'data', 'moduleName', 'consultation_counter', 'astrologers', 'userType', 'astrologer', 'user'));
    }

    public function submit(Request $request){
      //booked, cancel, complete, pending
      $userType = $this->userType;
      $admin = auth()->guard('admin')->user();
    $id = $request->id;
       if(!$id && !adminCan(strtolower($userType).'-call-consultation','add')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       if($id && !adminCan(strtolower($userType).'-call-consultation','update')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
    $this->validate($request, [
    'user_status' => 'required|not_in:Select',
    'request_id' => 'required',
        ],[
    'user_status.required' => 'Status is required',
          'request_id.required' => "You don't have any active request against this consultation",
    ]);
      if($request->user_status=='reschedule'){
    $this->validate($request, [
    'astrologer_id' => 'required',
    'schedule_date' => 'required',
    'schedule_time' => 'required',
        ],[
    'astrologer_id.required' => 'Astrologer is required',
    'schedule_date.required' => 'Date is required',
    'schedule_time.required' => 'Time is required',
    ]);
      }
      $astrologer_id = $request->astrologer_id;
      $data = AstrologerAppointment::find($id);
      if($data->user_status == 'pending' || $data->user_status == 'cancel'){
        return response()->json(['error' => 'Consultation already pending or canceled.'], 402);
      }
      if(strtotime('now') > strtotime($data->scheduled_date.' '.$data->scheduled_time)){
        return response()->json(['error' => 'Not possible.'], 402);
      }
      $consultation_request = ConsultationRequest::find($request->request_id);
      if(!$consultation_request){
        return response()->json(['error' => 'Request not found.'], 402);
      }
      if($request->user_status == 'reject'){
        $email = '';
        $consultation_request_responses = new ConsultationRequestResponse();
        if($consultation_request->user_type=='user'){
          $this->validate($request, [
              'admin_message_user' => 'required',
              ],[
              'admin_message_user.required' => "To user message is required",
          ]);
          $consultation_request_responses->user_message = $request->admin_message_user;
          $email= $data->user?$data->user->email:$data->email;
        }elseif($consultation_request->user_type=='astrologer'){
          $this->validate($request, [
              'admin_message_astrologer' => 'required',
              ],[
              'admin_message_astrologer.required' => "To astrologer message is required",
          ]);
          $consultation_request_responses->astrologer_message = $request->admin_message_astrologer;
          $email= $data->astrologer?$data->astrologer->email:'';
        }
        $consultation_request_responses->save();
        $consultation_request->status = 2;
        $consultation_request->save();
        $consultation_request_responses->appointment_id = $data->id;
        $consultation_request_responses->request_id = $request->request_id;
        $consultation_request_responses->admin_id = $admin->id;
        $consultation_request_responses->user_id = $consultation_request->user_id;
        $consultation_request_responses->user_type = $consultation_request->user_type;
        $consultation_request_responses->status = $consultation_request->status;
        $consultation_request_responses->save();
        if($consultation_request->request_type=='cancel'){
          ConsultationCancelJob::dispatch($email, 'admin', $consultation_request->user_type, $data, 'reject', $request->admin_message_user);
        }elseif($consultation_request->request_type=='reschedule'){
          ConstultatonRescheduleJob::dispatch($email, 'admin', $consultation_request->user_type, $data, 'reject', $request->admin_message_user);
        }
        return response()->json(['message' => 'Saved Successfully', 'redirectto' => route('admin.consultation.call.view', [$userType, $data->id])], 200);
      }
      $this->validate($request, [
          'admin_message_user' => 'required',
          'admin_message_astrologer' => 'required',
          ],[
          'admin_message_user.required' => "To user message is required",
          'admin_message_astrologer.required' => "To astrologer message is required",
      ]);
      $user = User::find($data->user_id);
      $astrologer = Astrologer::find($astrologer_id);
      if($request->user_status=='cancel' && $consultation_request->request_type=='cancel'){
        $cancel_before = fromSetting('cancel_before')?:24;
        $cancel_before = $cancel_before*60*60;
        $scheduled = strtotime($data->scheduled_date.' '.$data->scheduled_time)-time();
        // if($cancel_before <= $scheduled){
        if(true){
          $data->user_status = 'cancel';
          $data->status = 'cancel';
          $data->save();
          if($request->refund){
            $data->refund = 1;
            $data->save();
            $user->wallet += $data->total_price;
            $user->save();
          }
          $consultation_request->status = 1;
          $consultation_request->save();
          $consultation_request_responses = new ConsultationRequestResponse();
          $consultation_request_responses->appointment_id = $data->id;
          $consultation_request_responses->request_id = $request->request_id;
          $consultation_request_responses->admin_id = $admin->id;
          $consultation_request_responses->user_id = $consultation_request->user_id;
          $consultation_request_responses->user_type = $consultation_request->user_type;
          $consultation_request_responses->user_message = $request->admin_message_user;
          $consultation_request_responses->astrologer_message = $request->admin_message_astrologer;
          $consultation_request_responses->save();
          ConsultationCancelJob::dispatch($data->user?$data->user->email:$data->email, 'admin', 'user', $data, 'accept', $request->admin_message_user);
          if($data->astrologer){
            ConsultationCancelJob::dispatch($data->astrologer->email, 'admin', 'astrologer', $data, 'accept', $request->admin_message_astrologer);
          }
        }
        return response()->json(['message' => 'Saved Successfully', 'redirectto' => route('admin.consultation.call.view', [$userType, $data->id])], 200);
      }elseif($request->user_status=='reschedule' && $consultation_request->request_type=='reschedule'){
        $check_counter = [$data->id, $data->appointment_id];
          $user_id = $data->user_id;
          $user_type = 'user';
        if($consultation_request->user_type == 'astrologer'){
          $user_id = $data->astrologer_id;
          $user_type = 'astrologer';
        }
        $consultation_counter = ConsultationCounter::whereIn('appointment_id', $check_counter)->where('user_id', $user_id)->where('user_type', $user_type)->first();
        if($consultation_counter->counter==2){
          return response()->json(['errors' => ['error'=> ['Reached maximum limit to reschedule']]], 422);
        }
        $price = $astrologer->call_price;
        if(($astrologer->call_special_price > 0) && (date('Y-m-d') >= $astrologer->sp_from_date) && (date('Y-m-d') <= $astrologer->sp_to_date) ){
          $price = $astrologer->call_special_price;
        }
        $total_price = $price*$data->timeslot;
        $paid_price = $data->total_price;
        $wallet = $user->wallet;
        $wallet += $paid_price ;
        if($wallet < $total_price){
          return response()->json(['errors' => ['error'=> ["User don't have sufficient balance"]]], 422);
        }
        $scheduled_date = $request->schedule_date;
        $scheduled_time = date('H:i', strtotime($request->schedule_date.' '.$request->schedule_time.'+1minutes'));
        $scheduled_end_time = date('H:i', strtotime($request->schedule_date.' '.$request->schedule_time.'+'.$data->timeslot.'minutes'));
        $max_time = date('H:i', strtotime($request->schedule_date.' '.$request->schedule_time.'+'.($data->timeslot+5).'minutes'));
        $existingAppointment = AstrologerAppointment::where('scheduled_date', $scheduled_date)->where('astrologer_id', $astrologer_id)
        ->where(fn ($q) =>
          $q->whereNotIn('status', ['cancel', 'pending'])
        )->where(
           fn ($q) => $q->whereBetween('scheduled_time', [$scheduled_time, $max_time])
               ->orWhereBetween('scheduled_end_time', [$scheduled_time, $max_time])
               ->orWhere(
                   fn ($q) => $q->where('scheduled_time', '<', $scheduled_time)
                       ->where('scheduled_end_time', '>', $max_time)
               )
        )->exists();
        if($existingAppointment){
          return response()->json(['errors' =>['error'=> ['Slot already booked']]], 422);
        }
        $scheduled_time = date('H:i', strtotime($request->schedule_date.' '.$request->schedule_time));
        $payoutPackage = PayoutPackage::find($astrologer->payout_package_id);
        if(!$payoutPackage){
          return response()->json(['errors' =>['error'=> ['Astrologer Payout package not found']]], 422);
        }
        $totalCharges = 0;
        $server_charges = Setting::where('key', 'server')->first(); //rs.
        $totalCharges += ($server_charges && $server_charges->value ? $server_charges->value : 0);
        $sms_charges = Setting::where('key', 'sms')->first(); //rs.
        $totalCharges += ($sms_charges && $sms_charges->value ? $sms_charges->value : 0);
        $email_charges = Setting::where('key', 'email_charges')->first(); //rs.
        $totalCharges += ($email_charges && $email_charges->value ? $email_charges->value : 0);
        $maintainance_charge = Setting::where('key', 'maintainance_charge')->first(); //rs.
        $totalCharges += ($maintainance_charge && $maintainance_charge->value ? $maintainance_charge->value : 0);
        $call_charges = Setting::where('key', 'call_charges')->first(); //rs.
        $totalCharges += ($call_charges && $call_charges->value ? $call_charges->value : 0);
        $payment_gateway = Setting::where('key', 'payment_gateway')->first(); //%
        $payment_gateway = ($payment_gateway && $payment_gateway->value ? $payment_gateway->value : 0);
        if($payment_gateway){
          $totalCharges += ($total_price/100)*$payment_gateway;
        }
        if($total_price > $paid_price){
          $user->wallet -= ($total_price-$paid_price);
        }elseif($total_price < $paid_price){
          $user->wallet += ($paid_price-$total_price);
        }
        $user->save();
        $new_appointment = $data->replicate();
        $new_appointment->appointment_id = $data->appointment_id?:$data->id;
        $new_appointment->astrologer_id = $astrologer_id;
        $new_appointment->to_astrologer_id = $astrologer_id;
        $new_appointment->status = 'booked';
        $new_appointment->user_status = 'booked';
        $new_appointment->scheduled_date = $scheduled_date;
        $new_appointment->scheduled_time = $scheduled_time;
        $new_appointment->scheduled_end_time = $scheduled_end_time;
        $new_appointment->max_time = $max_time;
        $new_appointment->actualprice = $price;
        $new_appointment->total_price = $total_price;
        $new_appointment->astrologer_maintainance_charge = $totalCharges;
        $new_appointment->astrologer_amount_ratio = $payoutPackage->ratio;
        $new_appointment->astrologer_amount = (abs($total_price-$totalCharges)/100)*$payoutPackage->ratio;
        $new_appointment->user_end_time = date('H:i:s', strtotime('now'));
        $new_appointment->astrologer_end_time = date('H:i:s', strtotime('now'));
        $new_appointment->save();
        $consultation_counter->counter = $consultation_counter->counter+1;
        $consultation_counter->save();
        $consultation = new AstrologerUserConsultacy();
        $consultation->user_id = $new_appointment->user_id;
        $consultation->astrologer_appointment_id = $new_appointment->id;
        $consultation->astrologer_id = $astrologer_id;
        $consultation->meeting_link = '';
        $consultation->meeting_date = $new_appointment->schedule_date;
        $consultation->start_time = $new_appointment->schedule_time;
        $consultation->end_time = $new_appointment->schedule_end_time;
        $consultation->save();
        $chathistory = new CallHistory();
        $chathistory->user_id = $new_appointment->user_id;
        $chathistory->astrologer_id = $astrologer_id;
        $chathistory->from_number  = $astrologer->phone;
        $chathistory->to_number = $new_appointment->mobile;
        $chathistory->start_time = $new_appointment->schedule_time;
        $chathistory->end_time = $new_appointment->schedule_end_time;
        $chathistory->per_minute_charge = $new_appointment->actualprice;
        $chathistory->astrologer_amount = $new_appointment->astrologer_amount;
        $chathistory->call_hangup_time = $new_appointment->schedule_time;
        $chathistory->is_appointment_call = $new_appointment->scheduled_for; // 0:now, 1:later
        $chathistory->consultancy_id = $consultation->id;
        $chathistory->appointment_id = $new_appointment->id;
        $chathistory->initiated_time = 0;
        $chathistory->save();
        $data->user_status = 'cancel';
        $data->status = 'cancel';
        $data->reschedule = 1;
        $data->save();
        $consultation_request->status = 1;
        $consultation_request->save();
        $consultation_request_responses = new ConsultationRequestResponse();
        $consultation_request_responses->appointment_id = $data->id;
        $consultation_request_responses->request_id = $request->request_id;
        $consultation_request_responses->admin_id = $admin->id;
        $consultation_request_responses->user_id = $consultation_request->user_id;
        $consultation_request_responses->user_type = $consultation_request->user_type;
        $consultation_request_responses->user_message = $request->admin_message_user;
        $consultation_request_responses->astrologer_message = $request->admin_message_astrologer;
        $consultation_request_responses->save();
        ConstultatonRescheduleJob::dispatch($data->user?$data->user->email:$data->email, 'admin', 'user', $new_appointment, 'accept', $request->admin_message_user);
        if($data->astrologer){
          ConstultatonRescheduleJob::dispatch($data->astrologer->email, 'admin', 'astrologer', $new_appointment, 'accept', $request->admin_message_astrologer);
        }
        return response()->json(['message' => 'Saved Successfully', 'redirectto' => route('admin.consultation.call.view', [$userType, $data->id])], 200);
    }
    return response()->json(['errors' => ['error'=> ['Invalid Status']]], 422);
  }

    public function date(Request $request){
      $this->validate($request, [
        'astrologer_id'=>'required'
      ]);
      $astrologer = Astrologer::find($request->astrologer_id);
      $timetables = $astrologer->timetable;
      $weekDaysArray = [];
      foreach ($timetables as $timetable) {
        $weekDaysArray[] = $timetable->week_day;
      }

      $list=[];
      $month = date('m');
      $year = date('Y');
      $current_date = date('d');
      for($d=1; $d<=31; $d++){
        $time=mktime(0, 0, 0, $month, $d, $year);
        if (date('m', $time)==$month && in_array(date('w', $time), $weekDaysArray) && $current_date <= date('d', $time)){
          $list[date('Y-m-d', $time)]=date('d-m-Y', $time);
        }
      }
      return response()->json(['success'=>true, 'data'=>$list], 200);
    }

    public function time(Request $request){
      $this->validate($request, [
        'astrologer_id'=>'required',
        'date'=>'required',
      ]);
      $astro_id = $request->astrologer_id;
      $datee = explode('-', $request->date);
      $date = $datee[0];
      $month = $datee[1];
      $year = $datee[2];
      $currentDate = date('d');
      $dayOfWeek = date('w', strtotime($request->date));
      $astrologer_data = Astrologer::find($astro_id);
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
          for ($minutes = 0; $minutes < $totalMinutes; $minutes += $slot_time){
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
      return response()->json(['success'=>true, 'data'=>$timeIntervals15], 200);
    }
}
