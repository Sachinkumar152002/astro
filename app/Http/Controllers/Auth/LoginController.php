<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Exception;
use Session;
use Illuminate\Support\Str;
use App\CartItem;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
        $this->middleware('guest:astrologer')->except('logout');
    }

    public function userLogout()
    {
        Auth::guard('web')->logout();
        Session::pull('cart_user_id');
        Session::pull('cart_user_type');
        return redirect('/');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->with(["prompt" => "select_account"])->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $findUser = Socialite::driver('google')->stateless()->user();
            $name = $findUser->name;
            $googleId = $findUser->id;
            $email = $findUser->email?:'google'.$googleId.'@gmail.com';

            $user = User::where(function ($q) use($email, $googleId){
              $q->where('email', $email);
              $q->orWhere('google_id', $googleId);
            })->first();
            $redirectTo = route('home');
            if($user){
              if(!$user->google_id){
                $user->google_id = $googleId;
              }
              $user->save();
              Auth::guard('web')->login($user);
              $this->addCartItem($user);

              // Handle product referral code if present
              if(Session::has('product_referral_code')) {
                $referralCode = Session::get('product_referral_code');
                if(!$user->used_referral_code) {
                  $user->used_referral_code = $referralCode;
                  $user->save();
                }
              }

              if($user->phone){
                if(Session::has('redirect_from_login')){
                  $redirectTo = Session::get('redirect_from_login');
                  Session::pull('redirect_from_login');
                }
              }else{
                $redirectTo = route('verify.user.mobile');
              }
            }else{
                $wallet = fromSetting('welcome_bonus')?:0;
                $newUser = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id'=> $googleId,
                    'wallet' => $wallet,
                    'status'=> 1,
                    'api_token' => Str::random(70),
                    'register_from_ip' => request()->ip(),
                    'last_logined' => date('Y-m-d H:i:s'),
                ]);
                Auth::guard('web')->login($newUser);

                // Handle product referral code if present
                if(Session::has('product_referral_code')) {
                  $referralCode = Session::get('product_referral_code');
                  $newUser->used_referral_code = $referralCode;
                  $newUser->save();
                }

                $redirectTo = route('verify.user.mobile');
            }
            return redirect($redirectTo);
        } catch (Exception $e) {
          // return $e->getMessage();
          return redirect('redirect.to.google');
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->with(["prompt" => "select_account"])->stateless()->redirect();
    }

    public function handleFacebookCallback()
    {
      // dd('mukesh');
        try {
            $findUser = Socialite::driver('facebook')->stateless()->user();
            $name = $findUser->getName();
            $facebookId = $findUser->getId();
            $email = $findUser->getEmail()?:'facebook'.$facebookId.'@facebook.com';

            $user = User::where(function ($q) use($email, $facebookId){
              $q->where('email', $email);
              $q->orWhere('facebook_id', $facebookId);
            })->first();
            $redirectTo = route('home');
            if($user){
              if(!$user->facebook_id){
                $user->facebook_id = $facebookId;
              }
              $user->save();
              Auth::guard('web')->login($user);
              $this->addCartItem($user);

              // Handle product referral code if present
              if(Session::has('product_referral_code')) {
                $referralCode = Session::get('product_referral_code');
                if(!$user->used_referral_code) {
                  $user->used_referral_code = $referralCode;
                  $user->save();
                }
              }

              if($user->phone){
                if(Session::has('redirect_from_login')){
                  $redirectTo = Session::get('redirect_from_login');
                  Session::pull('redirect_from_login');
                }
              }else{
                $redirectTo = route('verify.user.mobile');
              }
            }else{
                $wallet = fromSetting('welcome_bonus')?:0;
                $newUser = User::create([
                    'name' => $name,
                    'email' => $email,
                    'facebook_id'=> $facebookId,
                    'status'=> 1,
                    'wallet' => $wallet,
                    'api_token' => Str::random(70),
                    'register_from_ip' => request()->ip(),
                    'last_logined' => date('Y-m-d H:i:s'),
                ]);
                Auth::guard('web')->login($newUser);

                // Handle product referral code if present
                if(Session::has('product_referral_code')) {
                  $referralCode = Session::get('product_referral_code');
                  $newUser->used_referral_code = $referralCode;
                  $newUser->save();
                }

                $redirectTo = route('verify.user.mobile');
            }
            return redirect($redirectTo);
        } catch (Exception $e) {
          return $e->getMessage();
          return redirect('redirect.to.facebook');
        }
    }

    public function redirectToApple()
    {
        return Socialite::driver('apple')->with(["prompt" => "select_account"])->stateless()->redirect();
    }

    public function handleAppleCallback()
    {
        try {
            $findUser = Socialite::driver('apple')->stateless()->user();
            $name = $findUser->getName();
            $email = $findUser->getEmail();
            $appleId = $findUser->getId();

            $user = User::where(function ($q) use($email, $appleId){
              $q->where('email', $email);
              $q->orWhere('apple_id', $appleId);
            })->first();
            $redirectTo = route('home');
            if($user){
              if(!$user->apple_id){
                $user->apple_id = $appleId;
              }
              $user->save();
              Auth::guard('web')->login($user);
              $this->addCartItem($user);
              if($user->phone){
                if(Session::has('redirect_from_login')){
                  $redirectTo = Session::get('redirect_from_login');
                  Session::pull('redirect_from_login');
                }
              }else{
                $redirectTo = route('verify.user.mobile');
              }
            }else{
                $wallet = fromSetting('welcome_bonus')?:0;
                $newUser = User::create([
                    'name' => $name,
                    'email' => $email,
                    'apple_id'=> $appleId,
                    'wallet' => $wallet,
                    'status'=> 1,
                    'api_token' => Str::random(70),
                    'register_from_ip' => request()->ip(),
                    'last_logined' => date('Y-m-d H:i:s'),
                ]);
                Auth::guard('web')->login($newUser);
                $redirectTo = route('verify.user.mobile');
            }
            return redirect($redirectTo);
        } catch (Exception $e) {
          return $e->getMessage();
          return redirect('redirect.to.apple');
        }
    }

    public function addCartItem($user){
      $user_id = Session::get('cart_user_id');
      $user_type = Session::get('cart_user_type');
      if($user_id && $user_type){
        $cartItems = CartItem::where('user_id', $user_id)->get();
        foreach ($cartItems as $key => $value) {
          CartItem::where('user_id', $user->id)->where('item_id', $value->item_id)->where('type', $value->type)->delete();
          $value->user_id = $user->id;
          $value->user_type = 'user';
          $value->save();
        }
      }
      Session::put('cart_user_id', $user->id);
      Session::put('cart_user_type', 'user');
      return true;
    }

    public function handleRedirectUlr(Request $request){
      Session::put('redirect_from_login', $request->redirectUrl);
      return response()->json(['msg'=>'save', 'url'=>Session::get('redirect_from_login')], 200);
    }

}
