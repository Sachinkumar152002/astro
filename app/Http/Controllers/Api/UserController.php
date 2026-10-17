<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\{Controller};
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use App\Jobs\{EmailVerification, UserRegistrationJob};
use App\{CartItem, User};
use App\Services\{ExotelService};

class UserController extends Controller{
  public function register(Request $request){
    $request->validate([
      'name' => 'required|max:50',
      'email' => 'required|email|max:100|unique:users,email,id,id',
      'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|unique:users,phone,id,id',
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone_code = '91';
    $user->phone = $request->phone;
    $user->status = 1;
    $user->is_verified = 1;
    $user->api_token = Str::random(70);
    $user->save();

    EmailVerification::dispatch($user);

    return response()->json([
      'success'=>true,
      'message'=>'Registration successfully'
    ], 200);
  }

  public function getOTP(Request $request){
    $this->validate($request, [
      'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:users,phone'
    ],[
      "phone.exists" => "Phone number is not registered with us. Please create new account"
    ]);

    $phone = $request->phone;
    $user = User::where('phone', $phone)->first();

    if(!$user){
      return response()->json([
        'success'=>false,
        'message'=>'Phone number is not registered with us. Please create new account'
      ], 422);
    }

    if($user->status == 0){
      return response()->json([
        'success'=>false,
        'message'=>'Your account is suspended. Please contact our support to reactivate.'
      ], 422);
    }
    $phoneArray = config('vrdcms.otp_send_except_number');
    $otp = 654321;
    if(!in_array($user->phone, $phoneArray) && config('vrdcms.otp_send')){
      $otp = mt_rand(100000, 999999);
      $message = loginOtpMessage($otp);
      $exotelService=new ExotelService();
      $sendSmsResponse=$exotelService->sendSms("FROM",$user->phone,$message);
      if($sendSmsResponse==false){
          return response()->json(['errors' => ['mobile'=>['Failed to send OTP. Please try again later.']] ], 500);
      }
    }
    $user->last_otp = $otp;
    $user->is_otp_verified = 0;
    $user->save();

    return response()->json([
      'success'=>true,
      'message'=>'Otp send successfully.'
    ], 200);

  }

  public function verifyOtp(Request $request){
    $this->validate($request, [
      'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:users,phone,deleted_at,NULL',
      'otp'   => 'required|digits:6|numeric',
    ],[
      "phone.exists" => "Phone number is not registered with us. Please create new account"
    ]);

    $user_type  = 'user';
    $phone      = $request->phone;
    $fcm_token= $request->fcm_token?:'';
    $user = User::where('phone', $phone)->first();

    if(!$user){
      return response()->json([
        'success'=>false,
        'message'=>'Phone number is not registered with us. Please create new account'
      ], 422);
    }

    if($user->status == 0){
      return response()->json([
        'success'=>false,
        'message'=>'Your account is suspended. Please contact our support to reactivate.'
      ], 422);
    }

    if($user->last_otp == $request->otp){
      $token = Str::random(70);
      $user->is_otp_verified = 1;
      $user->last_logined = date('Y-m-d H:i:s');
      $user->api_token = $token;
      $user->fcm_token = $fcm_token;
      $user->save();
      $data['token'] = $token;

      if($api_token = $request->api_token){
        $cartItems = CartItem::where('user_id', $api_token)
        ->where('user_type', 'guest')
        ->get();

        foreach($cartItems as $key => $item){
          CartItem::where('user_id', $user->id)
          ->where('user_type', $user_type)
          ->where('item_id', $item->item_id)
          ->where('type', $item->type)
          ->delete();

          $item->user_id = $user->id;
          $item->user_type = $user_type;
          $item->save();
        }
      }

      return response()->json(['success'=>true, 'message'=>'Otp matched successfully', 'data'=>$data], 200);
    }
    return response()->json(['success'=>false, 'message'=>'Otp not matched'], 422);
  }
}
