<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use App\Jobs\SendSms;
use App\Model\{TempUser,VerifyUser, WalletTransition};
use App\Jobs\EmailVerification;
use Session;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    public function __construct(){
      $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request){
      $countries = DB::table('master_countries')->where("id", 101)->get();
      $user_id = 0;
      $user_type = 'guest';
      if(Session::has("cart_user_id")){
        $user_id = Session::get("cart_user_id");
      }
      $hideUserLoginPopup = true;
      $phone = $request->phone;
      $verifyUser = VerifyUser::where("phone", $phone)->where("phone_verified", 1)->first();
      return view('auth.register', compact('countries', "hideUserLoginPopup","phone","verifyUser"));
    }

    public function register(Request $request){
      $request->validate([
        'name' => 'required|max:50',
        'email' => 'required|email|max:100|unique:users,email,id,id',
        'phone_code' => 'required|max:3',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/|unique:users,phone,id,id',
      ]);

      // Validate referral code if provided
      // Check only in astrologers table and users table (not in settings tables)
      if($request->referral_code){
        $referralCode = trim($request->referral_code);

        // Check if it's an astrologer's referral code in astrologers table
        $referralAstrologer = \App\Model\Astrologer::where('referral_code', $referralCode)
          ->where('status', 1)
          ->where('is_verified', 1)
          ->first();

        // Check if it's a user's referral code in users table
        $referralUser = User::where('referral_code', $referralCode)
          ->where('status', 1)
          ->first();

        // At least one should exist and be active/verified
        if(!$referralAstrologer && !$referralUser){
          return response()->json([
            'errors' => ['referral_code' => ['You have not enter a valid code']]
          ], 422);
        }

        // If astrologer code exists but not active/verified, show specific error
        if($referralAstrologer && ($referralAstrologer->status != 1 || $referralAstrologer->is_verified != 1)){
          return response()->json([
            'errors' => ['referral_code' => ['This referral code is invalid. The astrologer account is not active or verified.']]
          ], 422);
        }

        // Check if same phone or email is already using this referral code
        $existingUser = User::where('referral_code', $referralCode)
          ->where(function($query) use ($request) {
            $query->where('phone', $request->phone)
                  ->orWhere('email', $request->email);
          })
          ->first();

        if($existingUser){
          return response()->json([
            'errors' => ['referral_code' => ['This referral code has already been used with this phone number or email']]
          ], 422);
        }
      }

      $user = new User();
      $user->name = $request->name;
      $user->email = $request->email;
      $user->phone_code = $request->phone_code;
      $user->phone = $request->phone;
      $user->status = 1;
      $user->is_verified = 1;
      $user->wallet = 0; // Initialize wallet to 0

      // Save the referral code used during registration (if provided)
      // Note: User's own referral_code will be auto-generated in boot() method
      if($request->referral_code){
        $user->used_referral_code = trim($request->referral_code);
      }
      // If no referral code provided, used_referral_code will be null (no rewards will be given)

      $user->save();

      // Process App Referral Reward if trigger is 'signup'
      if($user->used_referral_code) {
        \App\Services\ReferralService::processReferralReward($user, 'user', null);
      }

      EmailVerification::dispatch($user);

      $redirectTo = route("login");
      return response()->json(["success" => true, "message" => "Registration Successfully.", "redirectTo" => $redirectTo], 200);
    }
}
