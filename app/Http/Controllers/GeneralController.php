<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Model\{VerifyUser};
use App\Services\{ExotelService};

class GeneralController extends Controller
{
  public function locations (Request $request){
    $request->validate([
      "table" => "required",
      "relation_table_id" => "required|gt:0",
    ]);
    $table = $request->table;
    $relationTableId = $request->relation_table_id;

    $locations = [];
    if($table == 'State'){
      $locations = DB::table("master_states")->where("country_id", $relationTableId)->orderBy('name', 'asc')->pluck("name","id")->toArray();
    }
    if($table == 'City'){
      $locations = DB::table("master_cities")->where("state_id", $relationTableId)->orderBy('name', 'asc')->pluck("name","id")->toArray();
    }
    return response()->json($locations, 200);
  }

  public function getPhoneOTP(Request $request){
    $page=$request->page?:"astrologer";
    if($page=="user"){
      $this->validate($request, [
        'phone_code' => 'required|max:3',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|unique:users,phone,id,id',
      ]);
    }else{
      $this->validate($request, [
        'phone_code' => 'required|max:3',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|unique:astrologers,phone,id,id',
      ]);
    }

    $phone = $request->phone;

    $verifyUser = VerifyUser::where("phone", $phone)->first();

    if(!$verifyUser){
      $verifyUser = new VerifyUser();
      $verifyUser->phone = $phone;
    }
    $phoneArray = config('vrdcms.otp_send_except_number');
    $otp = 654321;
    if(!in_array($phone, $phoneArray) && config('vrdcms.otp_send')){
      $otp = mt_rand(100000, 999999);
      $message = loginOtpMessage($otp);
      $exotelService=new ExotelService();
      $sendSmsResponse=$exotelService->sendSms("FROM",$phone,$message);
      if($sendSmsResponse==false){
          return response()->json(['errors' => ['mobile'=>['Failed to send OTP. Please try again later.']] ], 500);
      }
    }
    $verifyUser->phone_otp = $otp;
    $verifyUser->phone_verified = 0;
    $verifyUser->save();
    return response()->json(['message' => 'OTP sent successfully'], 200);
  }

  public function verifyPhoneOtp(Request $request){
    $this->validate($request, [
      'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:verify_users,phone',
      'otp' => 'required|digits:6'
    ],[
      "phone.exists" => "Invalid action"
    ]);

    $phone = $request->phone;
    $otp = $request->otp;

    $verifyUser = VerifyUser::where("phone", $phone)->first();

    if($verifyUser){
      if($verifyUser->phone_otp == $otp){
        $verifyUser->phone_verified = 1;
        $verifyUser->save();
        $page=$request->page?:"astrologer";
        if($page=="user"){
          $redirectTo = route('user.register', ["phone" => $phone]);
        }else{
          $redirectTo = route('astrologer.register', ["phone" => $phone]);
        }

        return response()->json(['message'=>'Mobile Number Verified Successfully.', 'redirectTo' => $redirectTo], 200);
      }
      $errors = [
        "phone" => ["OTP not matched."]
      ];
      return response()->json(["errors" => $errors], 403);
    }

    return response()->json(["errors" => $errors], 403);
  }
}
