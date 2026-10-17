<?php

namespace App\Http\Controllers\Astrologer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Jobs\{UserNotificationJob};
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;
use App\{CartItem};
use App\Model\{Astrologer, UserNotification};
use Session;
use DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\{UserNotificationNotification};
use App\Services\{ExotelService};

class LoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:astrologer', ['except' => ['logout']]);
    }

    public function loginForm(Request $request){
      if(auth()->guard('astrologer')->check()){
        $astrologer = auth()->guard('astrologer')->user();
        return $astrologer->is_verified ? redirect()->route('astrologer.dashboard') : redirect()->route('astrologer.pending');
      }
      $countries = DB::table('master_countries')->where("id", 101)->get();
      $hideUserLoginPopup = true;
      return view(themeView().'astrologers.auth.login',compact("countries", "hideUserLoginPopup"));
    }

    public function login(Request $request)
    {
      // Validate the form data
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required|min:6'
      ]);
      //dd($request);

      // Attempt to log the user in
      if (Auth::guard('astrologer')->attempt(['email' => $request->email, 'password' => $request->password])){
        $astrologer = Auth::guard('astrologer')->user();
        $astrologer->video = 1;
        $astrologer->chat = 1;
        $astrologer->call = 1;
        $astrologer->save();
        if (!$astrologer->is_verified) {
          return redirect()->route('astrologer.pending');
        }
        return redirect()->intended(route('astrologer.dashboard'));
      }

      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function getOTP(Request $request){
      $this->validate($request, [
        'phone_code' => 'required|max:3',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:astrologers,phone'
      ],[
        "phone.exists" => "Phone number is not registered with us. Please create new account"
      ]);
      $phone = $request->phone;
      $astrologer = Astrologer::where("phone", $phone)->first();
      if(!$astrologer){
        $errors = [
          "phone" => ["Your account is suspended. Please contact our support to reactivate."]
        ];
        return response()->json(["errors" => $errors], 403);
      }
      if($astrologer->status == 0 || $astrologer->is_delete_requested == 1){
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
      $astrologer->last_otp=$otp;
      $astrologer->is_otp_verified=0;
      $astrologer->save();
      return response()->json(['message' => 'OTP sent successfully'], 200);
    }

    public function verifyOtp(Request $request){
      $this->validate($request, [
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|exists:astrologers,phone,is_delete_requested,0,deleted_at,NULL',
        'otp' => 'required|digits:6'
      ]);
      $phone = $request->phone;
      $otp = $request->otp;
      $astrologer = Astrologer::where('phone', $phone)->first();
      if($astrologer){
        if($astrologer->status == 0 || $astrologer->is_delete_requested == 1){
          $errors = [
            "phone" => ["Your account is suspended. Please contact our support to reactivate."]
          ];
          return response()->json(["errors" => $errors], 403);
        }
        if($astrologer->last_otp == $otp){
          $astrologer->is_otp_verified = 1;
          $astrologer->last_logined = date('Y-m-d H:i:s');
          $astrologer->save();
          Auth::guard('astrologer')->login($astrologer);
          if($astrologer->is_verified){
            $astrologer->video = $astrologer->video ? 1 : 0;
            $astrologer->chat = $astrologer->chat ? 1 : 0;
            $astrologer->call = $astrologer->call ? 1 : 0;
          }else{
            $astrologer->video = 0;
            $astrologer->chat = 0;
            $astrologer->call = 0;
          }
          $astrologer->save();
          UserNotificationJob::dispatch($astrologer,null,"user","astrologer-online");
          $redirectTo = $astrologer->is_verified ? route('astrologer.dashboard') : route('astrologer.pending');
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

    public function logout(){
      $astrologer = Auth::guard('astrologer')->user();
      if(!$astrologer){
        return redirect()->route('home');
      }
      $astrologer->video = 2;
      $astrologer->chat = 2;
      $astrologer->call = 2;
      $astrologer->save();
      Session::pull('cart_user_id');
      Session::pull('cart_user_type');
      Auth::guard('astrologer')->logout();
      return redirect()->route('home');
    }

    //google auth
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function userNotification($astrologer, $notify_to, $type){
      $userNotification = UserNotification::where('astrologer_id', $astrologer->id)
      ->where('notify_to', $notify_to)
      ->where('type', $type)
      ->where('is_sent', 0)
      ->get();
      $userNotification->map(function ($item){
        UserNotificationJob::dispatch($item, $item->user_email);
        // Notification::route('mail', $item->user_email)
        // ->notify(new UserNotificationNotification($item));
      });
    }
}
