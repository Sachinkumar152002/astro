<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;
use Session;
use DB;
use App\{User, CartItem};
use App\Model\{TempUser};
use App\Jobs\{UserWelcomeEmail};
use App\Services\{ExotelService};

class UserLoginController extends Controller {

  public function showLoginForm(){
    $user = auth()->guard("web")->user();
    if($user){
      return redirect()->route("home");
    }
    $countries = DB::table('master_countries')->where("id", 101)->get();
    $user_id = 0;
    $user_type = 'guest';
    if(Session::has("cart_user_id")){
      $user_id = Session::get("cart_user_id");
    }
    $hideUserLoginPopup = true;
    return view('auth.login', compact('countries', "hideUserLoginPopup"));
  }

  public function getOTP(Request $request){
    $this->validate($request, [
      'phone_code' => 'required|max:3',
      'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:users,phone'
    ],[
      "phone.exists" => "Phone number is not registered with us. Please create new account"
    ]);

    $phone = $request->phone;
    $user = User::where("phone", $phone)->first();

    if(!$user){
      $errors = [
        "phone" => ["Your account is suspended. Please contact our support to reactivate."]
      ];
      return response()->json(["errors" => $errors], 403);
    }

    if($user->status == 0){
      $errors = [
        "phone" => ["Your account is suspended. Please contact our support to reactivate."]
      ];
      return response()->json(["errors" => $errors], 403);
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
    $user->last_otp=$otp;
    $user->is_otp_verified=0;
    $user->save();
    return response()->json(['message' => 'OTP sent successfully'], 200);
  }

  public function verifyOtp(Request $request){
    $this->validate($request, [
      'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:users,phone,deleted_at,NULL',
      'otp' => 'required|digits:6'
    ],[
      "phone.exists" => "Phone number is not registered with us. Please create new account"
    ]);
    $phone = $request->phone;
    $otp = $request->otp;
    $user = User::where('phone', $phone)->first();
    if($user){
      if($user->status == 0){
        $errors = [
          "phone" => ["Your account is suspended. Please contact our support to reactivate."]
        ];
        return response()->json(["errors" => $errors], 403);
      }

      if($user->last_otp == $otp){
        $user->is_otp_verified = 1;
        $user->last_logined = date('Y-m-d H:i:s');
        $user->save();
        Auth::guard("web")->login($user);
        $user_id = Session::get('cart_user_id');
        $user_type = Session::get('cart_user_type');
        if($user_id && $user_type){
          $cartItems = CartItem::where('user_id', $user_id)->get();
          foreach($cartItems as $key => $value){
            CartItem::where('user_id', $user->id)->where('item_id', $value->item_id)->where('type', $value->type)->delete();
            $value->user_id = $user->id;
            $value->user_type = 'user';
            $value->save();
          }
        }
        Session::put('cart_user_id', $user->id);
        Session::put('cart_user_type', 'user');
        $redirectTo = route('user.dashboard');
        if(Session::has('redirect_from_login')){
          $redirectTo = Session::get('redirect_from_login');
          Session::pull('redirect_from_login');

          // If redirect URL contains checkout or product referral code, preserve it
          if(Session::has('product_referral_code')) {
            $referralCode = Session::get('product_referral_code');
            // Store in user's used_referral_code if not already set
            if(!$user->used_referral_code) {
              $user->used_referral_code = $referralCode;
              $user->save();
            }
          }
        }
        return response()->json(['message'=>'Logged In Successfully.', 'redirectTo' => $redirectTo], 200);
      }
      $errors = [
        "phone" => ["OTP not matched."]
      ];
      return response()->json(["errors" => $errors], 403);
    }
    $errors = [
      "phone" => ["Phone number is not registered with us. Please create new account."]
    ];
    return response()->json(["errors" => $errors], 403);
  }

  public function getOtpOnMobile(Request $request){
    // Validate the mobile number (should be 10 digits)
    $this->validate($request, [
      'country_id' => 'required|numeric|min:1',
      'mobile' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
    ]);
    // Find the user by mobile number
    $country = \DB::table('master_countries')->where('id', $request->country_id)->first();
    $user = User::where('phone', $request->mobile)->first();
    if ($user && $country) {
      if($user->phone_code != '+'.$country->phonecode){
        return response()->json(['errors'=>['mobile'=>['Mobile Not match with phone code.']]], 422);
      }
        // Check if the user is suspended
        if($user->status == 0){
            return response()->json(['message' => 'Your account is suspended. Please contact our support to reactivate.'], 403);
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
        $user->last_otp=$otp;
        $user->is_otp_verified=0;
        $user->save();
        return response()->json(['message' => 'OTP sent successfully'], 200);
    }
    return response()->json(['errors' => ['mobile'=>['Mobile is not registered with us. Please create a new account']] ], 403);
}

    public function verifyEmail(Request $request){
        $email = decrypt($request->email);
        if($user = User::where('email', $email)->first()){
          if(is_null($user->email_verified_at)){
            $user->email_verified_at = now();
            $user->save();
            UserWelcomeEmail::dispatch($user);
          }
          Auth::login($user);
          if($user_id = Session::get('cart_user_id')){
            Session::put('cart_user_id', $user->id);
            $cartItems = CartItem::where('user_id', $user_id)->update(['user_id'=>$user->id]);
          }
          return redirect()->route('user.dashboard');
        }
        return abort(404);
    }

    public function getOtpForVerify(Request $request){
      $this->validate($request, [
        "phone_code" => "required",
        "phone" => "required|numeric|digits:10|regex:/^[6-9]\d{9}$/|unique:users,phone,id,id",
      ]);
      $user = auth()->guard('web')->user();
      $phone = $request->phone;
      $user->phone_code = $request->phone_code;
      $user->temp_phone = $phone;
      $user->save();
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
      $user->last_otp=$otp;
      $user->is_otp_verified=0;
      $user->save();
      return response()->json(['message' => 'OTP sent successfully'], 200);
    }

    public function userVerifyOtp(Request $request){
      $this->validate($request, [
        "otp" => "required|digits:6",
      ]);
      $user = auth()->guard("web")->user();
      if($user->last_otp != $request->otp){
        return response()->json([
          "errors" => ["otp" => ["Otp not matched."]]
        ], 422);
      }
      $user->phone = $user->temp_phone;
      $user->is_otp_verified = 1;
      $user->is_verified = 1;
      $user->save();
      $redirectTo = route('user.profile', ["tab" => "my-profile"]);
      if(Session::has('redirect_from_login')){
        $redirectTo = Session::get('redirect_from_login');
        Session::pull('redirect_from_login');
      }
      return response()->json([
        'message'=>'Phone number changed Successfully.',
        'redirectTo'=> $redirectTo,
      ], 200);
    }
}
