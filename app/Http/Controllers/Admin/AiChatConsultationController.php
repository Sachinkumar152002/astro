<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AstrologerUserConsultacy;
use App\Model\Chat;
use App\Model\PayoutPackage;
use App\Model\Setting;
use App\Model\ConsultationCounter;
use App\Model\AstrologerTimeTable;
use App\User;
use App\Model\CallHistory;
use App\Model\Astrologer;
use App\Model\ConsultationRequest;
use App\Model\ConsultationRequestResponse;
use App\Jobs\{ConsultationCancelJob, ConstultatonRescheduleJob};
use Illuminate\Support\Facades\Notification;
use App\Notifications\ConsultationCancelNotification;
use App\Notifications\ConsultationRescheduleNotification;
use DB;
use App\Model\{AstrologerAppointment, AiAstrologerAppointment};

class AiChatConsultationController extends Controller
{
    private $module = 'aichatconsultation';
    private $moduleName = 'Ai Chat Consultation';
    private $moduleNameSingle = 'Ai Chat Consultation';
    private $userType = 'AI';

    public function __construct(){
      $this->userType = request()->type;
    }

    public function index(Request $request){
        $userType = $this->userType;
        if(!adminCan(strtolower($userType).'-chat-consultation','view')) return dd('No Permission');
        $nav = 'ai_chat_consultation';
        $breads = [
            ['name'=>'Ai Chat Consultation']
        ];
        $moduleName = $this->moduleName;
        return view('admin.aichatconsultation.list', compact('breads','nav','moduleName', 'userType'));
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
        $query = AiAstrologerAppointment::whereIn('astrologer_id', $astrologers_id)->orderBy('updated_at', 'desc');

        if($user_name = $request->user_name){
          $users = User::where('name', 'like', "%$user_name%")->pluck('id')->toArray();
          $query->whereIn('from_user_id', $users);
        }
        if($astrologer_name = $request->astrologer_name){
          $astrologer = Astrologer::where('display_name', 'like', "%$astrologer_name%")->pluck('id')->toArray();
          $query->whereIn('astrologer_id', $astrologer);
        }
        if($total_price_from = $request->total_price_from){
          $query->where('charges', '>=', $total_price_from);
        }
        if($total_price_to = $request->total_price_to){
          $query->where('charges', '<=', $total_price_to);
        }
        if($updated_at_from = $request->updated_at_from){
          $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
        }
        if($updated_at_to = $request->updated_at_to){
          $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
        }
        $results = $query->paginate(19);

        $data = [
            'rows' => view('admin.aichatconsultation.listrows', ['results' => $results, 'userType'=>$userType])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
        ];

        return response()->json($data, 200);
    }

    public function viewData(Request $request)
    {
        $userType = $this->userType;
       $id = $request->id;
       $nav = 'ai_chat_consultation';
       $moduleName = $this->moduleNameSingle;
       $breads = [
            ['name'=>'Ai Chat Consultation', 'url'=> route('admin.aiconsultation.chat.index', [$userType])],
            ['name'=>($id ? 'Edit': 'Add')]
       ];
        $data = AiAstrologerAppointment::find($id);
        $astrologer = $data->astrologer;
        $user = $data->user;
        $wallet_price = $user ? $user->wallet : 0;
        $wallet_price += $wallet_price + $data->charges;
        $data->timeslot = 5;
        // $consultation_counter = ConsultationCounter::where('appointment_id', $data->id)->first();
        $consultation_counter = null;
        $astrologers = DB::table('astrologers')->select('id', 'display_name', DB::raw("
        (CASE
            WHEN sp_from_date IS NOT NULL AND sp_from_date <= '".date('Y-m-d')."' AND sp_to_date IS NOT NULL AND sp_to_date >= '".date('Y-m-d')."' THEN chat_special_price
            ELSE chat_price
        END)*".$data->timeslot." AS applicable_price
        "))
        ->where('type', $astrologer?$astrologer->type:'astrologer')
        ->where('status', 1)
        ->where('is_verified', 1)
        ->where('deleted_at', null)
      ->whereRaw('1=1 having applicable_price <= ? and applicable_price>0', [$wallet_price])->pluck('display_name', 'id')->toArray();
      return view('admin.aichatconsultation.view', compact('nav', 'breads', 'data', 'moduleName', 'consultation_counter', 'astrologers', 'userType', 'astrologer', 'user'));
    }
}
