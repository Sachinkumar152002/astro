<?php

namespace App\Http\Controllers;

use App\Http\Controllers\{Controller, PushNotificationController, NotificationController, GptController, ApiController};
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Model\Astrologer;
use App\Model\UserRechargeHistory;
use App\Model\ConsultationRequest;
use App\Model\ShippingAddress;
use App\Model\UserAdditionalInformation;
use App\Model\CourseExplore;
use App\Model\CourseExploreModule;
use App\Http\Controllers\Admin\MediaController;
use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\ConsultationCounter;
use App\Model\OrderRequest;
use App\Model\OrderRequestItem;
use App\Model\OrderRequestResponse;
use DB;
use Session;
use App\Jobs\SendSms;
use App\Jobs\SupportUserJob;
use App\Notifications\ConsultationCancelNotification;
use Carbon\Carbon;
use App\Jobs\{RechargeInvoiceMailJob, RechargeFailedJob, OrderRequestJob};
use App\Model\{CallHistory,Course, CourseModule, CourseModuleQuestion, AssignmentAnswer, AstrologerAppointment, Language, RechargePlan, Coupon, Chat, NewsletterSubscriber, Media, Wishlist, Support, SupportReplies, LmsCourse, OrderStatus};
use App\{Product,CartItem,Order,OrderItem,UserCourse,AstrologerUserConsultacy, User, CourseModuleContent};
use App\Model\{PujaAstrologerAssignment, AstrologerPujaMedia, PujaMahurat};
use Vimeo\Vimeo;
use PDF;
use File;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SupportNotification;
use App\Notifications\SupportAdminNotification;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Api;
use App\Http\Controllers\ProductController;
use App\Jobs\{ConsultationCancelJob, ConstultatonRescheduleJob};
use App\Model\Notification as NotificationModel;
use App\Model\{WalletTransition, ChatLog, AiAstrologerAppointment, AiAstrologerAppointmentChat, AstrologerService, Service, AstrologerReview,UserNotification, CustomerReferral};
use App\src\{RtcTokenBuilder2, RtmTokenBuilder2};
use App\{ProductReview};

class AccountController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $nav = 'dashboard';
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $not_in = ['pending', 'cancel'];
      $orders = Order::where('user_id', $user->id)->whereNotIn('status', $not_in)->orderBy('id', 'DESC')->limit(5)->get();
      $consultations = AstrologerAppointment::where('user_id', $user->id)->where('status', "done")->where("paid", 1)->orderBy('id', 'DESC')->limit(5)->get();
      $walletHistory = WalletTransition::where('user_id', $user->id)->orderBy('id', 'DESC')->limit(5)->get();
      $supports = Support::where('user_id', $user->id)->where("user_type", $user_type)->orderBy('id', 'DESC')->limit(5)->get();

      // Get user referral code data - check if user has referral code in users table
      // Also get CustomerReferral settings for reward amounts
      $userReferral = null;
      $referralSettings = null;
      if($user->referral_code) {
        // Get CustomerReferral settings (global settings)
        $referralSettings = CustomerReferral::where('status', 1)->first();
        // Create a userReferral object for view compatibility
        if($referralSettings) {
          $userReferral = (object)[
            'referral_code' => $user->referral_code,
            'user_reward' => $referralSettings->user_reward,
            'referrer_reward' => $referralSettings->referrer_reward
          ];
        } else {
          $userReferral = (object)[
            'referral_code' => $user->referral_code,
            'user_reward' => 0,
            'referrer_reward' => 0
          ];
        }
      }

      return view(themeView().'account.dashboard', compact('nav', 'user','orders', "consultations", "walletHistory", "supports", "userReferral", "referralSettings"));
    }

    public function profile(Request $request){
      $userNav = 'profile';
      $user = auth()->guard('web')->user()->load(['image', 'passbookImage']);
      $languages = Language::orderBy('name', 'asc')->get();
      $tabs = ["my-profile", "change-phone-number", "change-email-address"];
      $tab = $request->tab;
      if(!in_array($tab, $tabs)){
        $tab = "my-profile";
      }
      $states = [];
      $cities = [];
      $countries = DB::table('master_countries')->where("id", 101)->orderBy('name','asc')->get();
      if($country_id = $user->country_id){
        $states = DB::table('master_states')->where("country_id", $country_id)->orderBy('name','asc')->get();
        if($state_id = $user->state_id){
          $cities = DB::table('master_cities')->where("state_id", $state_id)->orderBy('name','asc')->get();
        }
      }
      return view(themeView().'account.profile', compact('userNav', 'user', 'countries', "states", "cities", 'languages', 'tab'));
    }

    public function saveProfile(Request $request){
      $tab = $request->tab ?: "my-profile";
      $user = auth()->guard("web")->user();
      $id = $user->id;
      if($tab == "my-profile"){
        $request->validate([
          "name" => "required",
          // "email" => "required|unique:users,email,id,$id,deleted_at,NULL",
          // "phonecode" => "required",
          // "phone" => "required|numeric|digits:10|regex:/^[6-9]\d{9}$/|unique:users,phone,id,$id,deleted_at,NULL",
          "gender" => "required|gt:0",
          "language_id" => "required|gt:0",
          "birth_date" => "required",
          "birth_time" => "required",
          "birth_place" => "required",
          "country_id" => "required|gt:0",
          "state_id" => "required|gt:0",
          "city_id" => "required|gt:0",
          "pincode" => "required|digits:6",
          "address" => "required",
        ],[
          "gender" => "The Gender field is required.",
          "language_id" => "The Language field is required.",
          "country_id" => "The Country field is required.",
          "state_id" => "The State field is required.",
          "city_id" => "The City field is required.",
        ]);

        if($request->hasFile('file')){
          $request->validate([
            "file" => "required|image|dimensions:max_width=4000,max_height=4000",
          ]);
        }

        $countryId = $request->country_id;
        $stateId = $request->state_id;
        $cityId = $request->city_id;

        $country = DB::table('master_countries')->where("id", $countryId)->first();
        $state = DB::table('master_states')->where("id", $stateId)->first();
        $city = DB::table('master_cities')->where("id", $cityId)->first();

        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->language_id = $request->language_id;
        $user->birth_date = $request->birth_date;
        $user->birth_time = $request->birth_time;
        $user->birth_place = $request->birth_place;
        $user->country_id = $country ? $country->id : $countryId;
        $user->country = $country ? $country->name : $countryId;
        $user->state_id = $state ? $state->id : $stateId;
        $user->state = $state ? $state->name : $stateId;
        $user->city_id = $city ? $city->id : $cityId;
        $user->city = $city ? $city->name : $cityId;
        $user->pincode = $request->pincode;
        $user->address = $request->address;
        if($request->hasFile('file')){
          $newRequest = new Request([
            'file'=>$request->file('file'),
            'type'=>'user',
          ]);
          $mediaController = new MediaController();
          $data = $mediaController->upload($newRequest);
          $response = $data->getData();
          $newMediaId = $response->media_id;
          $user->image_id = $response->media_id;
        }

        // Save bank details if user is influencer
        if($user->is_influencer == 1){
          if($request->has('account_number')){
            $user->account_number = $request->account_number;
          }
          if($request->has('ifsc_code')){
            $user->ifsc_code = $request->ifsc_code;
          }
          if($request->has('upi_id')){
            $user->upi_id = $request->upi_id;
          }
          if($request->hasFile('passbook_file')){
            $request->validate([
              "passbook_file" => "required|image|dimensions:max_width=4000,max_height=4000",
            ]);
            $newRequest = new Request([
              'file'=>$request->file('passbook_file'),
              'type'=>'user',
            ]);
            $mediaController = new MediaController();
            $data = $mediaController->upload($newRequest);
            $response = $data->getData();
            $user->passbook_image_id = $response->media_id;
          }
        }
        $user->save();
      }
      $redirectTo = route('user.profile', ["tab" => $tab]);
      if(Session::has('redirect_from_login')){
        $redirectTo = Session::get('redirect_from_login');
        Session::pull('redirect_from_login');
      }
      return response()->json(["success" => true, "message" => "Saved Successfully", "redirectTo" => $redirectTo], 200);
    }

    public function addresses(Request $request){
      $nav = 'userAddresses';
      $user = auth()->guard('web')->user();
      $user_type = 'user';
      $countries = null;
      $states = null;
      $cities = null;
      $country_id = $user->country_id;
      $state_id = $user->state_id;
      $countries = DB::table('master_countries')->get();
      $states = DB::table('master_states')->where('country_id', $country_id)->get();
      $cities = DB::table('master_cities')->where('state_id', $state_id)->get();
      $addresses = ShippingAddress::where('user_id', $user->id)->where('user_type', $user_type)->get();
      return view(themeView().'account.addresses', compact('nav', 'user', 'addresses', 'countries', 'states'));
    }

    public function addressesSave(Request $request){
      $user = auth()->guard('web')->user();
      $user_type = 'user';
      $this->validate($request, [
        'new_full_name'=>'required',
        'new_phone' => 'required|digits:10|regex:/^[1-9]\d{9}$/',
        'new_email' => 'required|email|max:255',
        'new_country_id' => 'required|gt:0',
        'new_state_id' => 'required|gt:0',
        'new_city_id' => 'required|gt:0',
        'new_zipcode' => 'required|digits:6',
        'new_address' => 'required',
      ], [
        'new_full_name.required'=>'Full Name field is required.',
        'new_phone.required'=>'Phone field is required.',
        'new_email.required'=>'Email field is required.',
        'new_country_id.required'=>'Country field is required.',
        'new_state_id.required'=>'State field is required.',
        'new_city_id.required'=>'City field is required.',
        'new_zipcode.required'=>'Zipcode field is required.',
        'new_address.required'=>'Address field is required.',
      ]);

      $id = $request->id;
      $country = \DB::table('master_countries')->where('id', $request->new_country_id)->first();
      $state = \DB::table('master_states')->where('id', $request->new_state_id)->first();
      $city = \DB::table('master_cities')->where('id', $request->new_city_id)->first();
      $shippingAddress = ShippingAddress::find($id);
      $message = 'Add Successfully.';

      if(!$shippingAddress){
        $message = 'Edit Successfully.';
        $shippingAddress = new ShippingAddress();
      }

      $shippingAddress->user_id = $user->id;
      $shippingAddress->user_type = $user_type;
      $shippingAddress->name = $request->new_full_name;
      $shippingAddress->phone = $request->new_phone;
      $shippingAddress->email = $request->new_email;
      $shippingAddress->country_id = $country?$country->id:$request->new_country_id;
      $shippingAddress->country = $country?$country->name:$request->new_country_id;
      $shippingAddress->state_id = $state?$state->id:$request->new_state_id;
      $shippingAddress->state = $state?$state->name:$request->new_state_id;
      $shippingAddress->city_id = $city?$city->id:$request->new_city_id;
      $shippingAddress->city = $city?$city->name:$request->new_city_id;
      $shippingAddress->address = $request->new_address;
      $shippingAddress->zipcode = $request->new_zipcode;
      $shippingAddress->save();

      $redirectTo = "";

      if($request->has("url")){
        $redirectTo = $request->url."?address_id=$shippingAddress->id";
      }

      return response()->json([
        'success'=>true,
        'message' => $message,
        "redirectTo" => $redirectTo
      ], 200);
    }

    public function addressesDelete(Request $request){
      $address_id = $request->address_id;
      $address = ShippingAddress::find($address_id);
      $address->delete();
      return redirect()->back();
    }

    public function getUserShippingAddress(Request $request){
      $user = auth()->guard('web')->user();
      if(!$user){
        return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
      }

      $user_type = 'user';
      $addresses = ShippingAddress::where('user_id', $user->id)->where('user_type', $user_type)->get();

      $addressData = $addresses->map(function($address){
        return [
          'id' => $address->id,
          'name' => $address->name,
          'address' => $address->address,
          'city' => $address->city,
          'state' => $address->state,
          'zipcode' => $address->zipcode,
          'country' => $address->country,
          'city_id' => $address->city_id,
          'state_id' => $address->state_id,
          'country_id' => $address->country_id
        ];
      })->toArray();

      return response()->json([
        'success' => true,
        'addresses' => $addressData
      ], 200);
    }

    public function recharge(Request $request){
      $nav = 'recharge';
      $user = auth()->guard('web')->user();
      if($user->country_id == 0 || $user->state_id == 0 || $user->city_id == 0){
        Session::flash("message", "Please fill you profile detail to recharge wallet.");
        Session::put("redirect_from_login", route("user.recharge"));
        return redirect()->route("user.profile", ["tab"=>"my-profile"]);
      }
      $option = $request->option;
      $ids = UserRechargeHistory::where('user_id', $user->id)->groupBy('recharge_plan_id')->pluck('recharge_plan_id')->toArray();
      $plans1 = RechargePlan::where('status', 1)->orderBy('amount', 'asc')->where('one_time', 0)->get();
      $plans2 = RechargePlan::where('status', 1)->orderBy('amount', 'asc')->where('one_time', 1)->whereNotIn('id', $ids)->get();
      $plans = $plans1->merge($plans2);
      $plans = $plans->sortBy('amount')->values();
      return view(themeView().'account.recharge', compact('nav', 'user', 'option', 'plans'));
    }

    public function makeRecharge(Request $request){
        $plan = $request->selectedplan;
        $code = $request->coupon_code;
        $uid = $request->uid;
        $today = date('Y-m-d');
        $currentDate = date('Y-m-d');
        $myPlan = RechargePlan::find($plan);
        $amount = $myPlan->amount;
        $discount = 0;
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $table = null;
        $couponAmount = 0;
        $couponCode = '';
        $couponId = 0;
        if($request->coupon_code){
          $newRequest = new Request([
            'callType'=>'not_ajax',
            'type'=>'recharge',
            'coupon_code'=>$request->coupon_code,
            'plan_id'=>$myPlan->id,
          ]);
          $productController = new ProductController();
          $couponData = $productController->applyCouponCode($newRequest);
          $couponData = $couponData->getData();
          $couponAmount = isset($couponData->discount)?$couponData->discount:0;
          $couponCode = isset($couponData->code)?$couponData->code:'';
          $couponId = isset($couponData->id)?$couponData->id:0;
        }
        $discount = abs($discount-$couponAmount);

        $table = UserRechargeHistory::where('my_txn_id', $uid)->first();
        if(!$table){
          $table = new UserRechargeHistory();
          $uid = str_random(20);
        }
        $table->user_id = $user->id;
        $table->user_type = $user_type;
        $table->recharge_plan_id = $myPlan->id;
        $table->amount = $myPlan->amount;
        $table->item_name = $myPlan->name;
        $table->item_description = 'Talk Amount - '.$myPlan->talk_amount.'<br> Loyality Points'.$myPlan->loyality_points;
        $table->payment_method = 'razorpay';
        $table->payment_txn_id = '';
        $table->payment_status = 'pending';
        $table->payment_request = '';
        $table->payment_response = '';
        $table->coupon_id = $couponId;
        $table->coupon_code = $couponCode;
        $table->coupon_amount = $couponAmount;
        $table->tax = 'GST';

        if(strtolower($user->state) == 'haryana'){
          $table->tax_type = 'cgst-sgst';
          $table->cgst = '9';
          $table->cgst_amount = ($amount-$discount)*0.09;
          $table->sgst = '9';
          $table->sgst_amount = ($amount-$discount)*0.09;
        }else{
          $table->tax_type = 'igst';
          $table->igst = '18';
          $table->igst_amount = ($amount-$discount)*0.18;
        }

        $table->coupon_code = '';
        $table->discount = $discount;
        $table->total_amount = ($amount-$discount) + ($amount-$discount)*0.18;
        $table->taktime_value = $myPlan->amount;
        if($myPlan->from_date && ($currentDate >= $myPlan->from_date) && ($currentDate <= $myPlan->to_date) ){
          $table->taktime_value = $myPlan->talk_amount;
        }
        // $table->loyality_points = $myPlan->loyality_points;
        $table->loyality_points = 0;
        $table->customer_name = $user->name;
        $table->customer_email = $user->email;
        $table->city = $user->city;
        $table->state = $user->state;
        $table->country = $user->country;
        $table->address = $user->address;
        $table->pincode = $user->pincode;
        $table->phone = $user->phone;
        $table->billing_full_name = $user->name;
        $table->billing_email = $user->email;
        $table->billing_phone_number = $user->phone;
        $table->billing_address = $user->address;
        $table->billing_country = $user->country;
        $table->billing_state = $user->state;
        $table->billing_city = $user->city;
        $table->billing_zipcode = $user->pincode;
        $table->hsn_code = fromSetting('recharge_hsn');
        if(!$table->invoice_id){
          $maxInvoiceId = UserRechargeHistory::max('invoice_id');
          $maxInvoiceId = $maxInvoiceId ? $maxInvoiceId+1 : '202100001';
          $table->invoice_id = $maxInvoiceId;
        }
        $table->save();
        $rechargePrefix = 'REC-'.date('ym').'-';
        $prefix = '';
        if($table->id<9){
          $prefix = $rechargePrefix.'0000'.$table->id;
        }elseif($table->id<99){
          $prefix = $rechargePrefix.'000'.$table->id;
        }elseif($table->id<999){
          $prefix = $rechargePrefix.'00'.$table->id;
        }elseif($table->id<9999){
          $prefix = $rechargePrefix.'0'.$table->id;
        }else{
          $prefix = $rechargePrefix.$table->id;
        }
        $table->my_txn_id = $prefix;
        $paymentRequest = [
          'receipt' => $prefix,
          'amount' => ceil($table->total_amount * 100),
          'currency' => 'INR'
        ];
        $table->total_amount = ($paymentRequest['amount']/100);
        $table->save();
        if($paymentRequest['amount']>0){
          $razorpayKey = fromSetting("razorpay_api_key");
          $razorpaySecretKey = fromSetting("razorpay_api_secret_key");

          $api = new Api($razorpayKey, $razorpaySecretKey);
          $rechargeData  = $api->order->create($paymentRequest); // Creates Razorpay order

          $data = [
            "key" => $razorpayKey,
            "amount" => $paymentRequest['amount'],
            "currency" => "INR",
            "name" => config("app.name"),
            "library_name" => config("app.name"),
            'description' =>$table->item_description,
            "image" => logo(),
            "order_id" => $rechargeData ? $rechargeData['id'] : 0,
            'prefill' => [
              'name' => $table->customer_name,
              'email' => $table->customer_email,
              'contact'=> $table->phone
            ]
          ];
          $data['callback_url'] = route('user.razorpay.response', [$table->id, $data['order_id']]);
          $table->payment_request = array_merge($paymentRequest, $data);
          $table->save();
          return response()->json([
            'uid' => $table->my_txn_id,
            'request' => $data
          ], 200);
        }else{
          $table->payment_id = '';
          $table->payment_method = 'nothing';
          $table->payment_date_time = date('Y-m-d H:i:s');
          $table->payment_status = 'success';
          $table->payment_response = '';
          $table->balance = $user->wallet+$table->taktime_value;
          $table->save();

          $user->wallet += $table->taktime_value;
          $user->save();

          // user wallet transition
          $walletTransition = new WalletTransition();
          $walletTransition->user_id = $user->id;
          $walletTransition->user_type = "user";
          $walletTransition->table_id = $table->id;
          $walletTransition->table_name = "user_recharge_histories";
          $walletTransition->action = 1; //credit
          $walletTransition->status = 1; //success
          $walletTransition->remark = "Recharge";
          $walletTransition->invoice = 0;
          $walletTransition->amount = $table->taktime_value;
          $walletTransition->save();

          $pdfUrl = '';
          RechargeInvoiceMailJob::dispatch($table, $pdfUrl);

          // normal notification
          $notification['user_id'] = $user->id;
          $notification['astrologer_id'] = 0;
          $notification['message'] = 'Thank You! Your recharge is successfully. of Rs. '.$table->total_amount.'.';
          $notification['image_id'] = 0;
          $notification['link'] = route('user.recharge-history');
          $notification['app_link'] = "wallet_history";
          $notification['for'] = 1;
          $notification['on'] = 0;
          $notification['status'] = 1;
          $notificationController = new NotificationController();
          $notificationController->create($notification);

          // push notification
          $title="Recharge";
          $body=$notification['message'];
          $dataArray = [
            'type' => $notification['app_link']
          ];
          $pushNotificationController = new PushNotificationController();
          $pushNotificationController->sendNotification($user->fcm_token, $title, $body, $dataArray);

          Session::flash('message', "<p>Recharge Successfully. Recharge Number is $table->my_txn_id.</p>");
          return response()->json([
            'uid' => $table->my_txn_id,
            "message" => "Recharge Successfully.",
            'redirectTo' => route('thank.you')
          ], 200);
        }
    }

    public function paymentResponse($user_recharge_id, $order_id, Request $request){
        $success = true;
        $error = "Payment Failed!";
        $txnId = session('paymentuid');
        $payment_response = $request->all();
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $table = UserRechargeHistory::find($user_recharge_id);

        if(empty($request->razorpay_payment_id) === false){
          $razorpayKey = fromSetting("razorpay_api_key");
          $razorpaySecretKey = fromSetting("razorpay_api_secret_key");

          $api = new Api($razorpayKey, $razorpaySecretKey);

            try {
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];
                $api->utility->verifyPaymentSignature($attributes);
                $orderResponse = $api->payment->fetch($request->razorpay_payment_id);
                $payment_response = array_merge($payment_response, $orderResponse->toArray());
                $table->payment_id = isset($payment_response['razorpay_payment_id'])?$payment_response['razorpay_payment_id']:'';
                $table->payment_method = isset($payment_response['method'])?$payment_response['method']:'';
                $table->payment_date_time = isset($payment_response['created_at'])?date('Y-m-d H:i:s', $payment_response['created_at']):date('Y-m-d H:i:s');
                $table->payment_status = 'success';
            }catch(SignatureVerificationError $e){
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
                $table->payment_status = 'failed';
            }
        }
        $table->payment_response = json_encode($payment_response);
        $table->balance = $user->wallet+$table->taktime_value;
        $table->save();
        if($success){
          $user->wallet += $table->taktime_value;
          $user->save();

          // user wallet transition
          $walletTransition = new WalletTransition();
          $walletTransition->user_id = $user->id;
          $walletTransition->user_type = "user";
          $walletTransition->table_id = $table->id;
          $walletTransition->table_name = "user_recharge_histories";
          $walletTransition->action = 1; //credit
          $walletTransition->status = 1; //success
          $walletTransition->remark = "Recharge";
          $walletTransition->invoice = 1;
          $walletTransition->amount = $table->taktime_value;
          $walletTransition->save();

          $invoiceController = new InvoiceController();
          $data['id'] = $table->id;
          $data['name'] = $table->customer_name;
          $data['email'] = $table->customer_email;
          $data['phone'] = $table->phone;
          $data['address'] = $table->address;
          $data['pincode'] = $table->pincode;
          $data['city'] = $table->city;
          $data['state'] = $table->state;
          $data['country'] = $table->country;
          $data['billing_full_name'] = $table->billing_full_name;
          $data['billing_email'] = $table->billing_email;
          $data['billing_phone_number'] = $table->billing_phone_number;
          $data['billing_address'] = $table->billing_address;
          $data['billing_country'] = $table->billing_country;
          $data['billing_state'] = $table->billing_state;
          $data['billing_city'] = $table->billing_city;
          $data['billing_zipcode'] = $table->billing_zipcode;
          $data['total_quantity'] = 1;
          $data['sgst'] = $table->sgst_amount;
          $data['cgst'] = $table->cgst_amount;
          $data['igst'] = $table->igst_amount;
          $data['subtotal_amount'] = $table->amount; //base
          $data['amount'] = $table->amount; //sale
          $data['total_amount'] = $table->total_amount; //payable
          $data['discount_amount'] = 0;
          $data['coupon_amount'] = $table->coupon_amount;
          $data['shipping_amount'] = 0;
          $data['order_id'] = $table->my_txn_id;
          $data['payment_id'] = $table->payment_id;
          $data['payment_method'] = $table->payment_method;
          $data['payment_date_time'] = $table->payment_date_time;

          $item_datas['name'] = 'Recharge';
          $item_datas['description'] = 'Recharge';
          $item_datas['quantity'] = 1;
          $item_datas['amount'] = $data['amount']; // sale
          $item_datas['base_amount'] = $data['amount']; //base
          $item_datas['total_amount'] = $table->total_amount;
          $item_datas['tax_type'] = $table->tax_type;
          $item_datas['igst_percentage'] = $table->igst;
          $item_datas['igst_amount'] = $table->igst_amount;
          $item_datas['cgst_percentage'] = $table->cgst;
          $item_datas['cgst_amount'] = $table->cgst_amount;
          $item_datas['sgst_percentage'] = $table->sgst;
          $item_datas['sgst_amount'] = $table->sgst_amount;
          $item_datas['hsn_code'] = $table->hsn_code;
          $item_datas['discount_amount'] = 0;
          $item_datas['coupon_amount'] = $table->coupon_amount;
          $item_datas['shipping_amount'] = 0;
          $item_data = [];
          array_push($item_data, $item_datas);
          $pdfUrl = $invoiceController->index($user, $user_type, $data, 'recharge', $item_data);

          RechargeInvoiceMailJob::dispatch($table, $pdfUrl);

          // normal notification
          $notification['user_id'] = $user->id;
          $notification['astrologer_id'] = 0;
          $notification['message'] = 'Thank You! Your recharge is successfully. of Rs. '.$table->total_amount.'.';
          $notification['image_id'] = 0;
          $notification['link'] = route('user.recharge-history');
          $notification['app_link'] = "wallet_history";
          $notification['for'] = 1;
          $notification['on'] = 0;
          $notification['status'] = 1;
          $notificationController = new NotificationController();
          $notificationController->create($notification);

          // push notification
          $title="Recharge";
          $body=$notification['message'];
          $dataArray = [
            'type' => $notification['app_link']
          ];
          $pushNotificationController = new PushNotificationController();
          $pushNotificationController->sendNotification($user->fcm_token, $title, $body, $dataArray);

          // $fields['campaign type'] = 'Recharge';
          // $body['fields'] = $fields;
          // sendDataToCrm($body);

          Session::flash('message', "<p>Recharge Successfully. Recharge Number is $table->my_txn_id.</p>");
          return redirect()->route('thank.you');
        }
        RechargeFailedJob::dispatch($table);
        return redirect()->route('home');
    }

    public function additionalInformation(Request $request){
      // $this->validate($request, [
      //   'qualification'=>'required',
      //   'institution'=>'required',
      //   'passing_year'=>'required',
      //   'marks'=>'required',
      // ]);
      $user = auth()->guard('web')->user();
      $information = UserAdditionalInformation::where('user_id', $user->id)->first();
      if(!$information){
        $information = new UserAdditionalInformation();
      }
      $information->user_id = $user->id;
      $information->qualification = $request->qualification;
      $information->institution = $request->institution;
      $information->passing_year = $request->passing_year;
      $information->division_grade = $request->division_grade?:'';
      $information->marks = $request->marks;
      $information->experience = $request->experience;
      if($request->hasFile('file')){
        $mediaController = new MediaController();
        $newRequest = new Request([
          'file'=>$request->file('file'),
          'type'=>'user',
        ]);
        $data = $mediaController->upload($newRequest);
        $response = $data->getData();
        $newMediaId = $response->media_id;
        $information->file = $newMediaId;
      }
      $information->save();
      return redirect()->route('user.profile')->with('message', 'Successfully updated!');
    }

    public function avatar(Request $request){
      $mediaController = new MediaController();
      $data = $mediaController->upload($request);
      $response = $data->getData();
      $newMediaId = $response->media_id;
      $profile = auth()->user();
      $profile->image_id = $newMediaId;
      $profile->save();
      return redirect()->route('user.dashboard')->with('success', 'Avatar updated successfully.');
    }

    public function rechargeHistory(Request $request)
    {
        $nav = 'recharge-history';
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $history = UserRechargeHistory::where('user_id', $user->id)->where('user_type', $user_type)->orderBy('updated_at', 'desc')->where('payment_status', 'success')->paginate(20);
        return view(themeView().'account.recharge-history', compact('nav', 'user', 'history'));
    }

    public function walletHistory(Request $request)
    {
        $nav = 'wallet-history';
        $user = auth()->guard('web')->user();
        $transitions = WalletTransition::where('user_id', $user->id)
        ->where('user_type', 'user')
        ->where('status', 1)
        ->orderBy('id', 'desc')
        ->paginate(15);
        return view(themeView().'account.walletHistory', compact('nav', 'user', 'transitions'));
    }

    public function consultations(Request $request){
      $nav = 'consultations';
      $user  = auth()->guard('web')->user();

      AstrologerAppointment::where('user_id', $user->id)
      ->where('scheduled_date', date('Y-m-d'))
      ->where('max_time', '>', date('H:i:00', strtotime('+5 minutes')))
      ->whereIn('status', ['cancel', 'failed'])
      ->update([
          'scheduled_end_time' => date('H:i:00'),
          'max_time' => date('H:i:00', strtotime('+5 minutes'))
      ]);

      $appointments = AstrologerAppointment::where('user_id', $user->id)
      ->whereNotIn('status', ['pending', 'failed'])
      ->orderByRaw("UNIX_TIMESTAMP(CONCAT(scheduled_date, ' ', scheduled_time)) DESC")
      ->get();
      $appointmentsId = $appointments->pluck("id")->toArray();
      $reviews = AstrologerReview::where("user_id", $user->id)->whereIn("appointment_id", $appointmentsId)->get();
      $tab = 'chat';

      if($request->has('tab')){
        $tab = $request->tab;
      }
      return view(themeView().'account.consultation.index',compact('user', 'appointments', 'tab', "reviews"));
    }

    public function suggestedRemedies(Request $request){
      $nav = 'suggested-remedies';
      $user = auth()->guard('web')->user();

      // Get suggested remedies for this user
      $suggestedRemedies = DB::table('astrologer_remedy_suggestions')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($suggestion) {
          $astrologer = Astrologer::find($suggestion->astrologer_id);
          $products = json_decode($suggestion->products, true) ?: [];
          $reports = json_decode($suggestion->reports, true) ?: [];
          $pujas = json_decode($suggestion->pujas ?? '[]', true) ?: [];

          $productDetails = [];
          $reportDetails = [];
          $pujaDetails = [];

          if(!empty($products)){
            $productDetails = Product::whereIn('id', $products)
              ->select('id', 'title', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
              ->get()
              ->map(function($product){
                $currentDate = date('Y-m-d');
                $price = $product->base_price ?? 0;

                // Check if sale price is valid and within date range
                if($product->sale_price && $product->sale_price > 0){
                  if($product->sale_price_start_date && $product->sale_price_end_date){
                    if($currentDate >= $product->sale_price_start_date && $currentDate <= $product->sale_price_end_date){
                      $price = $product->sale_price;
                    }
                  } elseif(!$product->sale_price_start_date && !$product->sale_price_end_date){
                    $price = $product->sale_price;
                  }
                }

                return [
                  'id' => $product->id,
                  'title' => $product->title,
                  'price' => $price
                ];
              });
          }

          if(!empty($reports)){
            $reportDetails = Product::whereIn('id', $reports)
              ->select('id', 'title', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
              ->get()
              ->map(function($report){
                $currentDate = date('Y-m-d');
                $price = $report->base_price ?? 0;

                // Check if sale price is valid and within date range
                if($report->sale_price && $report->sale_price > 0){
                  if($report->sale_price_start_date && $report->sale_price_end_date){
                    if($currentDate >= $report->sale_price_start_date && $currentDate <= $report->sale_price_end_date){
                      $price = $report->sale_price;
                    }
                  } elseif(!$report->sale_price_start_date && !$report->sale_price_end_date){
                    $price = $report->sale_price;
                  }
                }

                return [
                  'id' => $report->id,
                  'title' => $report->title,
                  'price' => $price
                ];
              });
          }

          if(!empty($pujas)){
            $pujaDetails = Product::whereIn('id', $pujas)
              ->select('id', 'title', 'base_price', 'sale_price', 'sale_price_start_date', 'sale_price_end_date')
              ->get()
              ->map(function($puja){
                $currentDate = date('Y-m-d');
                $price = $puja->base_price ?? 0;

                // Check if sale price is valid and within date range
                if($puja->sale_price && $puja->sale_price > 0){
                  if($puja->sale_price_start_date && $puja->sale_price_end_date){
                    if($currentDate >= $puja->sale_price_start_date && $currentDate <= $puja->sale_price_end_date){
                      $price = $puja->sale_price;
                    }
                  } elseif(!$puja->sale_price_start_date && !$puja->sale_price_end_date){
                    $price = $puja->sale_price;
                  }
                }

                return [
                  'id' => $puja->id,
                  'title' => $puja->title,
                  'price' => $price
                ];
              });
          }

          return [
            'id' => $suggestion->id,
            'astrologer_name' => $astrologer ? ($astrologer->display_name ?? $astrologer->firstname ?? 'Astrologer') : 'Astrologer',
            'created_at' => $suggestion->created_at,
            'products' => $productDetails,
            'reports' => $reportDetails,
            'pujas' => $pujaDetails,
            'referral_code' => $suggestion->referral_code ?? null,
            'is_used' => $suggestion->is_used ?? 0
          ];
        });

      return view(themeView().'account.suggested-remedies.index', compact('nav', 'user', 'suggestedRemedies'));
    }

    public function startchat(Request $request){
      $room_id = $request->room_id;
      $user = auth()->guard('web')->user();

      $appointment = AstrologerAppointment::where('package', 2)
      ->where('room_id', $room_id)
      ->where('user_id', $user->id)
      ->whereNotIn('status', ['pending', 'cancel'])
      ->first();

      if(!$appointment) abort(404);

      $consultation_id = $appointment->id;

      $current_time = date('H:i:s');
      $scheduled_time = $appointment->scheduled_time;
      $scheduled_end_time = $appointment->scheduled_end_time;

      if(!$appointment->user_start_time){
        $appointment->user_status='joined';
        $appointment->user_start_time = date('H:i:s');
        $appointment->save();
      }

      $start_time = strtotime("now")-strtotime($appointment->user_start_time);

      if($appointment->astrologer_start_time){
        $start_time = strtotime("now")-strtotime($appointment->astrologer_start_time);
      }

      $end_time = Carbon::parse($appointment->scheduled_end_time);
      $hideFooter = true;
      $talk_time_in_seconds = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-330seconds')-strtotime("now");
      $astrologer = $appointment->astrologer;

      return view(themeView().'account.consultation.chat', compact('appointment', 'user', 'room_id', 'start_time', 'end_time', 'hideFooter', 'talk_time_in_seconds', 'astrologer', "consultation_id"));
    }

    public function canChat(Request $request){
      $this->validate($request, [
        'room_id'=>'required'
      ]);

      $room_id = $request->room_id;
      $user = auth()->guard('web')->user();

      $appointment = AstrologerAppointment::where('package', 2)
      ->where('room_id', $room_id)
      ->where('user_id', $user->id)
      ->whereNotIn('status', ['pending', 'cancel'])
      ->first();

      if(!$appointment){
        return response()->json([
          'success'=>false,
          'message' => 'Consultation not found.'
        ], 404);
      }

      $user_detail['name'] = $user->name;
      $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
      $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
      $current_time = time();

      if(!$appointment->astrologer_start_time){
        $count_down = ($starting_time+180) - time();
        return response()->json([
          'success'=>true,
          'message' => 'Please wait for astrologer',
          'status' => 'wait',
          'user_detail'=>$user_detail,
          'count_down'=>$count_down
        ], 200);
      }

      if($starting_time > $current_time){
        $difference = $starting_time - $current_time;
        $hours = floor($difference / 3600);
        $minutes = floor(($difference % 3600) / 60);
        $message = "Consultation starts in $hours hours and $minutes minutes.";
        return response()->json([
          'success'=>true,
          'message' => $message,
          'status' => 'not now',
          'user_detail'=>$user_detail
        ], 200);
      }elseif($ending_time < $current_time){
        return response()->json([
          'success'=>true,
          'message' => 'Time is over',
          'status' => 'done',
          'user_detail'=>$user_detail
        ], 200);
      }

      return response()->json([
        'success'=>true,
        'message' => '',
        'status' => 'start',
        'user_detail'=>$user_detail
      ], 200);
    }

    public function fetchChatLogs(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required',
      ]);

      $consultation_id = $request->consultation_id;
      $user = auth()->guard('web')->user();

      $appointment = AstrologerAppointment::find($consultation_id);
      $chatLogs = ChatLog::where('chat_id', $consultation_id)->get();
      $chatLogsCount = $chatLogs->count();

      $data = view(themeView().'account.consultation.chats', ['results'=>$chatLogs, 'appointment'=>$appointment])->render();

      $status = 'done';

      $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
      $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
      $current_time = time();

      if($starting_time <= $current_time && $ending_time > $current_time){
        $appointment->user_end_time = date('H:i:s', $current_time);
        $appointment->user_status = 'done';

        if($appointment->status != "cancel"){
          $appointment->status = 'done';
        }

        $status = 'continue';

        if($request->status == 'leave'){
          $appointment->scheduled_end_time = date('H:i');
          $appointment->max_time = date('H:i', strtotime('+5minutes'));
          $status = 'done';
        }elseif($request->status == 'cancel'){
          $appointment->user_status = 'cancel';
          $appointment->status = 'cancel';
          $status = 'cancel';
        }
      }

      $appointment->save();

      return response()->json([
        'success'=>true,
        'message' => 'chats',
        'status' => $status,
        'data'=>$data,
        'chatLogsCount'=>$chatLogsCount
      ], 200);
    }

    public function maskIllegalString($string){
      $email_patterns = [
        '/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+(?:\.[A-Z]{2,})?/i',
        '/[A-Z0-9._%+\-]+@[A-Z0-9.\-]*[A-Z0-9][\s\.]?[A-Z]{2,}/i',
        '/[A-Z0-9._%+\-]+\s*(?:\(|\[)?\s*(?:@|at|\bat\b|\(at\)|\[at\])\s*(?:\)|\])?\s*[A-Z0-9.\-]+\s*(?:\(|\[)?\s*(?:\.|dot|\bdot\b|\(dot\)|\[dot\])\s*(?:\)|\])?\s*[A-Z]{2,}/i',
        '/[A-Z0-9._%+\-]+\s+(?:at)\s+[A-Z0-9.\-]+/i'
      ];
      $phone_re = '/(?:\+?\s*[\d\-\.\(\)\s]{3,}\d)|(?:\b(?:[0-9]|zero|one|two|three|four|five|six|seven|eight|nine|oh|o|plus)\b(?:[\s\-\.\,\/\(\)]*(?:[0-9]|zero|one|two|three|four|five|six|seven|eight|nine|oh|o))*)/i';

      $has_illegal_text = false;

      $map = [
        'zero' => '0', 'one' => '1', 'two' => '2', 'three' => '3', 'four' => '4',
        'five' => '5', 'six' => '6', 'seven' => '7', 'eight' => '8', 'nine' => '9',
        'plus' => '+'
      ];

      // convert words → digits (case-insensitive)
      $string = str_ireplace(array_keys($map), array_values($map), $string);

      // remove spaces/dots/dashes (so “9 eight 7 six...” becomes “9876543210”)
      // $string = preg_replace('/[\s\.\-]+/', '', $string);

      $mask_length = function($s) {
          return str_repeat('*', mb_strlen($s, 'UTF-8'));
      };

      foreach ($email_patterns as $pattern) {
          $string = preg_replace_callback($pattern, function($m) use (&$has_illegal_text, $mask_length) {
              $has_illegal_text = true;
              return $mask_length($m[0]);
          }, $string);
      }

      $string = preg_replace_callback($phone_re, function($m) use (&$has_illegal_text){
          if(strlen(preg_replace('/\D/','',$m[0])) < 6) return $m[0];
          $has_illegal_text = true;
          return str_repeat('*', strlen($m[0]));
      }, $string);

      return ["has_illegal_text" => $has_illegal_text , "string" => $string];
    }

    public function sendMessage(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required|gt:0',
        'message'=>'required'
      ]);

      $consultation_id = $request->consultation_id;
      $message = $request->message;

      $user = auth()->guard('web')->user();

      $data = $this->maskIllegalString($message);

      $has_illegal_text = $data["has_illegal_text"];
      $message = $data["string"];

      $chatLog = new ChatLog();
      $chatLog->chat_id = $consultation_id;
      $chatLog->user_id = $user->id;
      $chatLog->astrologer_id = 0;
      $chatLog->message = $message;
      $chatLog->message_by = 'user';

      if($has_illegal_text){
        $astrologerAppointment = AstrologerAppointment::where("id", $consultation_id)->where("has_illegal_activity", 0)->first();
        if($astrologerAppointment){
          $astrologerAppointment->has_illegal_activity = 1;
          $astrologerAppointment->save();
        }
        $chatLog->has_illegal_text = 1;
        $chatLog->illegal_message = $request->message;
      }

      $chatLog->save();

      return response()->json([
        'success'=>true,
        'message' => ''
      ], 200);
    }

    public function startPhoneCall(Request $request){
      $room_id = $request->room_id;
      $user = auth()->guard('web')->user();

      $appointment = AstrologerAppointment::where('package', 3)
      ->where('room_id', $room_id)
      ->where('user_id', $user->id)
      ->first();

      if(!$appointment) abort(404);

      $consultation_id = $appointment->id;

      if(!$appointment->user_start_time){
        $appointment->user_status='joined';
        $appointment->status='joined';
        $appointment->user_start_time = date('H:i:s');
        $appointment->save();
      }

      $start_time = time()-strtotime($appointment->user_start_time);

      if($appointment->astrologer_start_time){
        $start_time = time()-strtotime($appointment->astrologer_start_time);
      }

      $end_time = Carbon::parse($appointment->scheduled_end_time);
      $hideFooter = true;
      $talk_time_in_seconds = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-330seconds')-strtotime("now");

      $astrologer = $appointment->astrologer;

      return view(themeView().'account.consultation.call', compact("user", "astrologer", "room_id", 'consultation_id', 'start_time', 'end_time', "talk_time_in_seconds", "hideFooter", "appointment"));
    }

    public function canPhoneCall(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required'
      ]);

      $consultation_id = $request->consultation_id;
      $user = auth()->guard('web')->user();

      $appointment = AstrologerAppointment::where('package', 3)
      ->where('id', $consultation_id)
      ->where('user_id', $user->id)
      ->first();

      if(!$appointment){
        return response()->json([
          'success'=>false,
          'message' =>
          'Consultation not found.'
        ], 404);
      }

      $astrologer = $appointment->astrologer;
      $astrologer_detail['name'] = $astrologer->display_name;

      $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
      $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
      $current_time = time();

      if(!$appointment->astrologer_start_time){
        $count_down = ($starting_time+180) - time();
        return response()->json([
          'success'=>true,
          'message' =>
          'Please wait for astrologer',
          'status' => 'wait',
          'count_down'=>$count_down
        ], 200);
      }

      $consultation_detail['start_time'] = '00:00';
      $remaining_seconds = $ending_time - $current_time;

      if($remaining_seconds > 0){
        $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
        $remaining_seconds = $remaining_seconds % 60;
        $consultation_detail['start_time'] = "$remaining_minutes:$remaining_seconds";
      }

      $consultation_detail['app_id'] = fromSetting("agora_app_id");
      $consultation_detail['app_certificate'] = fromSetting("agora_app_certificate");
      $consultation_detail['room_id'] = $appointment->room_id;
      $consultation_detail['uid'] = "@".$user->name.$user->id;
      $consultation_detail['user_id'] = $user->id;
      $consultation_detail['app_token'] = RtcTokenBuilder2::buildTokenWithUid($consultation_detail['app_id'], $consultation_detail["app_certificate"], $consultation_detail["room_id"], $consultation_detail["user_id"], 1, 86400);
      $consultation_detail['user_name'] = $user->name;
      $consultation_detail['logo_url'] = asset('assets/img/logo.png');
      unset($consultation_detail['app_certificate']);

      if($starting_time > $current_time){
        $difference = $starting_time - $current_time;
        $hours = floor($difference / 3600);
        $minutes = floor(($difference % 3600) / 60);
        $message = "Consultation starts in $hours hours and $minutes minutes.";
        return response()->json([
          'success'=>true,
          'message' => $message,
          'status' => 'not now',
          'astrologer_detail'=>$astrologer_detail,
          'consultation_detail'=>$consultation_detail
        ], 200);
      }elseif($ending_time < $current_time){
        return response()->json([
          'success'=>true,
          'message' => 'Time is over',
          'status' => 'done',
          'astrologer_detail'=>$astrologer_detail,
          'consultation_detail'=>$consultation_detail
        ], 200);
      }

      return response()->json([
        'success'=>true,
        'message' => '',
        'status' => 'start',
        'astrologer_detail'=>$astrologer_detail,
        'consultation_detail'=>$consultation_detail
      ], 200);
    }

    public function updatePhoneCallStatus(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required'
      ]);

      $user = auth()->guard('web')->user();
      $consultation_id = $request->consultation_id;

      $appointment = AstrologerAppointment::find($consultation_id);

      if(!$appointment){
        return response()->json([
          'success'=>false,
          'message' => 'Consultation not found.'
        ], 404);
      }

      $status = 'done';

      $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
      $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
      $current_time = time();

      if($starting_time <= $current_time && $ending_time > $current_time){
        $appointment->user_end_time = date('H:i:s', $current_time);
        $appointment->user_status = 'done';

        if($appointment->status != "cancel"){
          $appointment->status = 'done';
        }

        $status = 'continue';

        if($request->status == 'leave'){
          $appointment->scheduled_end_time = date('H:i');
          $appointment->max_time = date('H:i', strtotime('+5minutes'));
          $status = 'done';
        }elseif($request->status == 'cancel'){
          $appointment->user_status = 'cancel';
          $appointment->status = 'cancel';
          $status = 'cancel';
        }

        $appointment->save();
      }

      return response()->json([
        'success'=>true,
        'status'=>$status
      ], 200);
    }

    public function startVideoCall(Request $request){
      $room_id = $request->room_id;
      $user = auth()->guard('web')->user();

      $appointment = AstrologerAppointment::where('package', 1)
      ->where('room_id', $room_id)
      ->where('user_id', $user->id)
      ->first();

      if(!$appointment) abort(404);

      $consultation_id = $appointment->id;

      if(!$appointment->user_start_time){
        $appointment->user_status='joined';
        $appointment->status='joined';
        $appointment->user_start_time = date('H:i:s');
        $appointment->save();
      }

      $start_time = time()-strtotime($appointment->user_start_time);

      if($appointment->astrologer_start_time){
        $start_time = time()-strtotime($appointment->astrologer_start_time);
      }

      $end_time = Carbon::parse($appointment->scheduled_end_time);
      $hideFooter = true;
      $talk_time_in_seconds = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-330seconds')-strtotime("now");

      $astrologer = $appointment->astrologer;

      return view(themeView().'account.consultation.video', compact("user", "astrologer", "room_id", 'consultation_id', 'start_time', 'end_time', 'hideFooter', "talk_time_in_seconds", "appointment"));
    }

    public function canVideoCall(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required'
      ]);

      $consultation_id = $request->consultation_id;
      $user = auth()->guard('web')->user();

      $appointment = AstrologerAppointment::where('package', 1)
      ->where('id', $consultation_id)
      ->where('user_id', $user->id)
      ->first();

      if(!$appointment){
        return response()->json([
          'success'=>false,
          'message' => 'Consultation not found.'
        ], 404);
      }

      $astrologer = $appointment->astrologer;
      $astrologer_detail['name'] = $astrologer->display_name;

      $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
      $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
      $current_time = time();

      if(!$appointment->astrologer_start_time){
        $count_down = ($starting_time+180) - time();
        return response()->json([
          'success'=>true,
          'message' => 'Please wait for astrologer',
          'status' => 'wait',
          'count_down'=>$count_down
        ], 200);
      }

      $consultation_detail['start_time'] = '00:00';
      $remaining_seconds = $ending_time - $current_time;

      if($remaining_seconds > 0){
        $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
        $remaining_seconds = $remaining_seconds % 60;
        $consultation_detail['start_time'] = "$remaining_minutes:$remaining_seconds";
      }

      // $consultation_detail['app_id'] = (int) config('vrdcms.zego_app_id');
      // $consultation_detail['app_sign'] = config('vrdcms.zego_server_secret');
      // $consultation_detail['room_id'] = $astrologerUserConsultacy->meeting_link;
      $consultation_detail['app_id'] = fromSetting("agora_app_id");
      $consultation_detail['app_certificate'] = fromSetting("agora_app_certificate");
      $consultation_detail['room_id'] = $appointment->room_id;
      $consultation_detail['uid'] = "@".$user->firstname.$user->id;
      $consultation_detail['user_id'] = $user->id;
      $consultation_detail['app_token'] = RtcTokenBuilder2::buildTokenWithUid($consultation_detail['app_id'], $consultation_detail["app_certificate"], $consultation_detail["room_id"], $consultation_detail["user_id"], 1, 86400);
      $consultation_detail['user_name'] = $user->name;
      $consultation_detail['logo_url'] = asset('assets/img/logo.png');
      unset($consultation_detail['app_certificate']);

      if($starting_time > $current_time){
        $difference = $starting_time - $current_time;
        $hours = floor($difference / 3600);
        $minutes = floor(($difference % 3600) / 60);
        $message = "Consultation starts in $hours hours and $minutes minutes.";

        return response()->json([
          'success'=>true,
          'message' => $message,
          'status' => 'not now',
          'astrologer_detail'=>$astrologer_detail,
          'consultation_detail'=>$consultation_detail
        ], 200);
      }elseif($ending_time < $current_time){
        return response()->json([
          'success'=>true,
          'message' => 'Time is over',
          'status' => 'done',
          'astrologer_detail'=>$astrologer_detail,
          'consultation_detail'=>$consultation_detail
        ], 200);
      }

      return response()->json([
        'success'=>true,
        'message' => '',
        'status' => 'start',
        'astrologer_detail'=>$astrologer_detail,
        'consultation_detail'=>$consultation_detail
      ], 200);
    }

    public function updateVideoCallStatus(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required'
      ]);
      $consultation_id = $request->consultation_id;
      $user = auth()->guard('web')->user();

      $appointment = AstrologerAppointment::where('package', 1)
      ->where('id', $consultation_id)
      ->where('user_id', $user->id)
      ->first();

      if(!$appointment){
        return response()->json([
          'success'=>false,
          'message' => 'Consultation not found.'
        ], 404);
      }

      $status = 'done';

      $starting_time = strtotime($appointment->scheduled_date.' '.$appointment->scheduled_time);
      $ending_time = strtotime($appointment->scheduled_date.' '.$appointment->max_time.'-5minutes');
      $current_time = time();

      if($starting_time <= $current_time && $ending_time > $current_time){
        $appointment->user_end_time = date('H:i:s', $current_time);
        $appointment->user_status = 'done';

        if($appointment->status != "cancel"){
          $appointment->status = 'done';
        }

        $status = 'continue';

        if($request->status == 'leave'){
          $appointment->scheduled_end_time = date('H:i');
          $appointment->max_time = date('H:i', strtotime('+5minutes'));
          $status = 'done';
        }elseif($request->status == 'cancel'){
          $appointment->user_status = 'cancel';
          $appointment->status = 'cancel';
          $status = 'cancel';
        }

        $appointment->save();
      }

      return response()->json([
        'success'=>true,
        'status'=>$status
      ], 200);
    }

    public function aiConsultations(Request $request){
      $nav = 'aiConsultations';
      $user  = auth()->guard('web')->user();

      $appointments = AiAstrologerAppointment::where('user_id', $user->id)
      ->orderBy("id", 'DESC')
      ->paginate(10);
      $appointmentsId = $appointments->pluck("id")->toArray();
      $reviews = AstrologerReview::where("user_id", $user->id)->whereIn("appointment_id", $appointmentsId)->get();

      $tab = 'chat';
      if($request->has('tab')){
        $tab = $request->tab;
      }
      return view(themeView().'account.consultation.ai.index',compact('user', 'appointments', 'tab', "reviews"));
    }

    public function startaichat(Request $request){
      $consultation_id = $request->consultation_id;
      $user = auth()->guard('web')->user();

      if(!$user) abort(404);

      $appointment = AiAstrologerAppointment::where('mode', "chat")
      ->where('id', $consultation_id)
      ->where('user_id', $user->id)
      ->first();

      if(!$appointment) abort(404);

      $formData = json_decode($appointment->form_data, true);

      $appointment->name = $formData["name"];

      $start_time = time()-strtotime($appointment->start_at);
      $astrologer = $appointment->astrologer;
      $hideFooter = true;
      $userImage = $user->image ? $user->image->image(100, 100) : asset('assets/images/user-n.png');

      return view(themeView().'account.consultation.ai.chat', compact('appointment', 'user', "astrologer", 'hideFooter', "consultation_id", "start_time", "userImage"));
    }

    public function fetchAiChatLogs(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required',
      ]);

      $appointment_id = $request->consultation_id;

      $aiAstrologerAppointment = AiAstrologerAppointment::find($appointment_id);
      $astrologer = $aiAstrologerAppointment->astrologer;
      $user = $aiAstrologerAppointment->user;

      $chatLogs = AiAstrologerAppointmentChat::where('appointment_id', $appointment_id)->limit(PHP_INT_MAX)->offset(1)->get();

      if($chatLogs->count() == 0){
        $astrologer_id = $aiAstrologerAppointment->astrologer_id;

        $astrologer = $aiAstrologerAppointment->astrologer;
        $astrologerName = $astrologer->display_name;
        $serivcesId = AstrologerService::where('astrologer_id', $astrologer_id)->pluck('service_id')->toArray();
        $serivcesNameArray = Service::whereIn('id', $serivcesId)->pluck('name')->toArray();
        $serivcesNameString = implode(', ', $serivcesNameArray);
        $experience = date('Y') - $astrologer->job_start_year;
        // $experience = $experience ?: 1;
        $experience = 10;
        $message = "Your name is $astrologerName. You are an expert astrologer specializing in $serivcesNameString with over $experience years of experience. You provide highly accurate, personalized, and insightful astrology-based predictions, remedies, and guidance based on user-provided details such as name, date of birth, time of birth, and place of birth. Always respond in hindi or english language. Use a friendly, empathetic, and conversational tone while maintaining professionalism. If the user asks something unrelated to $serivcesNameString, politely guide them back to astrology-related topics.";

        $aiAstrologerAppointmentChat=new AiAstrologerAppointmentChat();
        $aiAstrologerAppointmentChat->appointment_id = $appointment_id;
        $aiAstrologerAppointmentChat->astrologer_id = $astrologer_id;
        $aiAstrologerAppointmentChat->user_id = $user->id;
        $aiAstrologerAppointmentChat->message = $message;
        $aiAstrologerAppointmentChat->message_by = "system";
        $aiAstrologerAppointmentChat->save();

        $formData = json_decode($aiAstrologerAppointment->form_data, true);

        $firstMessage['message_by'] = 'user';
        $firstMessage['message'] = "Hi, \nName: ".$formData["name"];
        if($gender = $formData["gender"]){
          $firstMessage['message'] .= " \nGender: ".$gender;
        }
        if($birth_date = $formData["birth_date"]){
          $firstMessage['message'] .= " \nBirth Date: ".date('d-m-Y', strtotime($birth_date));
        }
        if($birth_time = $formData["birth_time"]){
          $firstMessage['message'] .= " \nBirth Time: ".date('h:i a', strtotime($birth_time));
        }
        if($birth_place = $formData["birth_place"]){
          $firstMessage['message'] .= " \nBirth Place: ".$birth_place;
        }
        if($occupation = $formData["occupation"]){
          $firstMessage['message'] .= " \nOccupation: ".$occupation;
        }
        if($maritial_status = $formData["maritial_status"]){
          $firstMessage['message'] .= " \nMaritial Status: ".$maritial_status;
        }

        $aiAstrologerAppointmentChat=new AiAstrologerAppointmentChat();
        $aiAstrologerAppointmentChat->appointment_id = $appointment_id;
        $aiAstrologerAppointmentChat->astrologer_id = $astrologer_id;
        $aiAstrologerAppointmentChat->user_id = $user->id;
        $aiAstrologerAppointmentChat->message = $firstMessage['message'];
        $aiAstrologerAppointmentChat->message_by = $firstMessage['message_by'];
        $aiAstrologerAppointmentChat->save();

        $chatLogs = AiAstrologerAppointmentChat::where('appointment_id', $appointment_id)->limit(PHP_INT_MAX)->offset(1)->get();
      }

      $astrologerImage = $astrologer->image ? $astrologer->image->image(100, 100) : asset('assets/images/profile-n.png');
      $userImage = $user->image ? $user->image->image(100, 100) : asset('assets/images/user-n.png');

      $data = "";
      $themeView = themeView();

      foreach ($chatLogs as $key => $value) {
        $data .= view($themeView."account.consultation.ai.chatCard", ["row" => $value, "astrologerImage" => $astrologerImage, "userImage" => $userImage])
        ->render();
      }

      $status="completed";
      $talk_time_in_seconds=0;
      if($aiAstrologerAppointment->paid == 0){
        $status="continue";
        $now=strtotime("now");
        $start_at=strtotime($aiAstrologerAppointment->start_at);
        $passedTime=ceil($now-$start_at);
        $userWalletAmount=$user->wallet;
        $perMin=$aiAstrologerAppointment->per_min_charge;
        $talk_time_in_seconds=ceil(($userWalletAmount/$perMin)*60);
        $talk_time_in_seconds-=$passedTime;
        $talk_time_in_seconds -= 30;

        if($aiAstrologerAppointment->free == 1){
          $talk_time_in_seconds += 300;
        }

      }
      return response()->json([
        'success'=>true,
        'data'=>$data,
        'status'=>$status,
        'talk_time_in_seconds'=>$talk_time_in_seconds
      ], 200);
    }

    public function sendMessageToAi(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required|integer|gt:0',
        'message'=>'required|string|max:1000'
      ]);
      $consultation_id=$request->consultation_id;
      $message=$request->message;

      $aiAstrologerAppointment=AiAstrologerAppointment::find($consultation_id);
      $user_id=$aiAstrologerAppointment->user_id;
      $astrologer_id=$aiAstrologerAppointment->astrologer_id;

      $aiAstrologerAppointmentChat=new AiAstrologerAppointmentChat();
      $aiAstrologerAppointmentChat->appointment_id=$consultation_id;
      $aiAstrologerAppointmentChat->astrologer_id=$astrologer_id;
      $aiAstrologerAppointmentChat->user_id=$user_id;
      $aiAstrologerAppointmentChat->message=$message;
      $aiAstrologerAppointmentChat->message_by='user';
      $aiAstrologerAppointmentChat->save();

      // $gptResponse=GptResponse::where('prompt', 'like', "%$message%")->where("response", "!=", "")->first();
      $gptResponse=null;
      $response="";
      if($gptResponse){
        $response=$gptResponse?json_decode($gptResponse->response, true):"";
      }else{
        $newRequest=new Request([
          "prompt"=>$message,
          "user_id"=>$user_id,
          "consultation_id"=>$consultation_id
        ]);
        $gptController=new GptController();
        $response=$gptController->getResponse($newRequest);
        if($response){
          $response=$response->json();
        }
      }
      $response=isset($response["output"])?$response["output"][0]["content"][0]["text"]:"No Response";
      $response = preg_replace('/[#*`_~]+/', '', $response);
      $response = preg_replace('/\s+/', ' ', $response);
      $response = trim($response);

      $aiAstrologerAppointmentChat=new AiAstrologerAppointmentChat();
      $aiAstrologerAppointmentChat->appointment_id=$consultation_id;
      $aiAstrologerAppointmentChat->astrologer_id=$astrologer_id;
      $aiAstrologerAppointmentChat->user_id=$user_id;
      $aiAstrologerAppointmentChat->message=$response;
      $aiAstrologerAppointmentChat->message_by='astrologer';
      $aiAstrologerAppointmentChat->save();

      $astrologer = $aiAstrologerAppointment->astrologer;
      $astrologerImage = $astrologer->image ? $astrologer->image->image(100, 100) : asset('assets/images/profile-n.png');

      $response = view(
        themeView()."account.consultation.ai.chatCard",
        [
          "row" => $aiAstrologerAppointmentChat,
          "astrologerImage" => $astrologerImage,
          "userImage" => "" ]
        )->render();

      return response()->json(['success'=>true, 'message' => 'message sent.', 'response'=>$response], 200);
    }

    public function updateAiChatTime(Request $request){
      $this->validate($request, [
        'consultation_id'=>'required'
      ]);

      $appointment_id = $request->consultation_id;

      $aiAstrologerAppointment = AiAstrologerAppointment::find($appointment_id);

      $astrologer = $aiAstrologerAppointment->astrologer;
      $user = $aiAstrologerAppointment->user;

      $status = $request->status ?: "continue";
      $message = "Status Updated";
      $aiAstrologerAppointment->end_at=date('Y-m-d H:i:s');

      if($status == "end"){
          $startAt = strtotime($aiAstrologerAppointment->start_at);
          $endAt = strtotime($aiAstrologerAppointment->end_at);
          $totalSeconds = $endAt-$startAt;
          $totalMinutes = ceil($totalSeconds/60);
          $totalCharges = $aiAstrologerAppointment->per_min_charge*$totalMinutes;
          $aiAstrologerAppointment->charges = $totalCharges;
          $aiAstrologerAppointment->paid = 1;
          $aiAstrologerAppointment->remark = "End Manually";

          if($aiAstrologerAppointment->free == 1){
            $freeAppointmentPrice = $aiAstrologerAppointment->per_min_charge*5;

            if($freeAppointmentPrice < $totalCharges){
              $aiAstrologerAppointment->discount =  $freeAppointmentPrice;
            }else{
              $aiAstrologerAppointment->discount =  $totalCharges;
            }

            $totalCharges -= $freeAppointmentPrice;

            if($totalCharges < 0){
              $totalCharges = 0;
            }
          }

          // debit consultation amount from wallet
          $user->wallet -= $totalCharges;
          $user->save();

          // user wallet transition
          $walletTransition = new WalletTransition();
          $walletTransition->user_id = $user->id;
          $walletTransition->user_type = "user";
          $walletTransition->table_id = $aiAstrologerAppointment->id;
          $walletTransition->table_name = "ai_astrologer_appointments";
          $walletTransition->action = 0; // debit
          $walletTransition->status = 1; // success
          $walletTransition->remark = "Completed Ai Astrologer Consultation";
          $walletTransition->invoice = 0;
          $walletTransition->amount = $totalCharges;
          $walletTransition->save();
          $message = "Chat has been ended.";
      }
      $aiAstrologerAppointment->save();

      $status = $aiAstrologerAppointment->paid ? "completed" : "continue";

      return response()->json([
        'success'=>true,
        'message'=> $message,
        'status'=>$status
      ], 200);
    }

    public function astrologerReviewSubmit(Request $request){
      $request->validate([
        "appointment_id" => "required|gt:0",
        "astrologer_id" => "required|gt:0",
        'rating' => 'required|numeric|min:1',
        "comment" => "required",
      ], [
        "rating" => "The rating field is required",
        "comment" => "The review field is required",
      ]);

      $user = auth()->guard("web")->user();
      $review = AstrologerReview::where("appointment_id", $request->appointment_id)->where("astrologer_id", $request->astrologer_id)->where("user_id", $user->id)->first();

      if(!$review){
        $review = new AstrologerReview();
        $review->status = 0;
      }

      $review->appointment_id = $request->appointment_id;
      $review->astrologer_id = $request->astrologer_id;
      $review->user_id = $user->id;
      $review->rating = $request->rating;
      $review->comment = $request->comment;

      if($review->status == 0){
        $review->save();
      }

      return response()->json([
         'status' => 'success',
         'message' => 'Review submitted successfully.'
     ]);
   }

    public function newsletter(Request $request)
    {
        $nav = 'newsletter';
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $isSubscribed = NewsletterSubscriber::where('email',$user->email)->where('status',1)->count();
        // return $isSubscribed;
        return view(themeView().'account.newsletter', compact('nav', 'user', 'isSubscribed'));
    }

    public function subscribe(Request $request)
    {
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $subsribe = (int) $request->status;
        // return $request->status;
        // if($request->subscribe == 'subscribe'){
        //   $subsribe = 1;
        // }
        $newItem = NewsletterSubscriber::where('email', $user->email)->first();
        // $newItem = NewsletterSubscriber::where('phone', $user->phone)->where('email', $user->email)->first();
        if(!$newItem){
          $newItem = new NewsletterSubscriber();
        }
        $newItem->name = $user->name;
        $newItem->phone = $user->phone;
        $newItem->email = $user->email;
        $newItem->status = $subsribe;
        $newItem->save();
        $newItem->sendEmail();
        return redirect()->back()->with('message', $subsribe?'Successfully Subscribed!':'Unsubscribed!');
    }

    public function subscription(Request $request)
    {
        $nav = 'newsletter';
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        return view(themeView().'account.subscription', compact('nav', 'user'));
    }

    public function quizzes(Request $request)
    {
        $nav = 'quizzes';
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        return view(themeView().'account.quizzes', compact('nav', 'user'));
    }

    public function financial(Request $request)
    {
        $nav = 'financial';
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        return view(themeView().'account.financial', compact('nav', 'user'));
    }

    public function notification(Request $request)
    {
      $nav = 'notification';
       $user = auth()->guard('web')->user();
       $user_type = 'user';
       $for = [0, 1];
       if(!$user){
         return redirect()->route('home');
       }
       $userIdIn = [$user->id, 0];
       $query=NotificationModel::whereIn('user_id', $userIdIn)
       ->where('created_at', '>=', $user->created_at)
       ->whereIn('for', $for);
       $notifications = $query->orderBy('created_at', 'desc')
       ->paginate(10);
       $query->update(['view' => 1]);

       NotificationModel::whereIn('user_id', $userIdIn)
       ->where('created_at','>=', $user->created_at)
       ->whereIn('for', $for)
       ->update(['view' => 1]);

       $hideNotifications = true;
       return view(themeView().'account.notification', compact('nav', 'user', 'notifications', 'hideNotifications'));
    }

    public function rechargeHistoryInvoice(Request $request)
    {
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $invoice_id = $request->id;
        $invoice = UserRechargeHistory::where('user_id', $user->id)->where('id', $invoice_id)->first();
        if(!$invoice) return abort(404);
        $print = true;
        return view(themeView().'emails.invoice', compact('print', 'invoice'));
    }

    public function callHistoryPrint(Request $request)
    {
        $user = auth()->guard('web')->user();
        $user_type = 'user';
        $id = $request->id;
        $callHistory = CallHistory::where('user_id', $user->id)->where('user_status', 'Answered')
        ->where('system_call_status',1)->where('id',$id)->first();
        if(!$callHistory) return abort(404);
        $print = true;
        return view(themeView().'emails.callsuccess', compact( 'callHistory', 'print'));
    }

    public function invoice(Request $request){
      $type = $request->type; // recharge,order
      $id = $request->id;
      $invoice = Invoice::where('relation_id', $id)->where('relation_type', $type)->first();
      if($invoice){
        $path = public_path('invoice/');
        $fileName =  $invoice->prefix.$invoice->number.'.pdf' ;
        $pdfUrl = asset('invoice/'.$fileName);
        if(!File::exists(public_path('invoice/'.$fileName))){
          if(!File::exists(public_path('invoice'))){
              File::makeDirectory(public_path('invoice'));
          }
          $invoice_items = InvoiceItem::where('invoice_id', $invoice->id)->get();
          $pdf = PDF::loadView(themeView().'invoice.index', compact('invoice', 'invoice_items'));
          $pdf->save($path.$fileName);
          $pdf = null;
        }
        return redirect($pdfUrl);
      }
      return redirect()->back();
    }

  public function wishlists(Request $request){
    $nav = 'wishlist';
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $products = Wishlist::where('user_id', $user->id)
    ->where('user_type', 'user')
    ->where('type', 'shop')
    ->paginate(6);
    $page = $request->page ?: 1;
    if($page >= 2 && $products->count() == 0){
      return redirect()->route("user.wishlist", ["page" => $page-1]);
    }
    return view(themeView().'account.wishlist', compact('nav', 'user', 'products'));
  }

  public function purchaseitem(Request $request){
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    if(!$user) abort(404);
    // $not_in = ['pending', 'cancel'];
    $not_in = ['pending'];
    $orders = Order::with('orderItems')
    ->where('orders.user_id', $user->id)
    ->where('orders.user_type',$user_type)
    ->orderBy('orders.id', 'DESC')
    ->whereNotIn('orders.order_status', $not_in)
    ->paginate(10);

    // Get user referral code data - CustomerReferral is a settings table, not per-user
    // Get referral settings and create userReferral object from user's referral_code
    $referralSettings = CustomerReferral::where('status', 1)->first();
    $userReferral = null;
    if($user->referral_code && $referralSettings) {
      $userReferral = (object)[
        'referral_code' => $user->referral_code,
        'user_reward' => $referralSettings->referee_reward_amount ?? $referralSettings->user_reward ?? 0,
        'referrer_reward' => $referralSettings->referrer_reward_amount ?? $referralSettings->referrer_reward ?? 0
      ];
    }

    return view(themeView().'account.orders', compact('orders','user', 'userReferral'));
  }

  public function itemDetail(Request $request){
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    if(!$user) abort(404);
    $id = $request->order_id;
    $order = Order::find($id);
    $order_status_array = OrderStatus::where('order_id', $id)->orderBy('id', 'asc')->pluck('created_at', 'status')->toArray();
    $productReviews = ProductReview::where('order_id', $order->id)->where('review_by', $user->id)->get();
    // return $productReviews->where("product_id", 4)->first();

    $apiController = new ApiController();
    $pdf_url = $apiController->getResponse($order->id);

    // For one-to-one puja order: get astrologer-scheduled date/time and status (for Video Url / Join / Success message)
    $pujaScheduleDatetime = null;
    $pujaStatusCompleted = false;
    $firstOrderItem = $order->orderItems->first();
    if ($firstOrderItem && $firstOrderItem->product && ($firstOrderItem->product->is_one_to_one_puja ?? 0) == 1) {
      $assignment = PujaAstrologerAssignment::where('order_id', $order->id)
        ->where('order_item_id', $firstOrderItem->id)
        ->first();
      if ($assignment) {
        $pujaStatusCompleted = (strtolower($assignment->status ?? '') === 'completed');
        $mahurat = PujaMahurat::where('assignment_id', $assignment->id)->first();
        if ($mahurat && $mahurat->date && $mahurat->time) {
          $pujaScheduleDatetime = Carbon::parse($mahurat->date->format('Y-m-d') . ' ' . $mahurat->time, config('app.timezone', 'Asia/Kolkata'));
        }
      }
    }

    return view(themeView().'account.order_detail', compact('order','user', 'order_status_array', "productReviews", "pdf_url", "pujaScheduleDatetime", "pujaStatusCompleted"));
  }

  public function purchasecourses(Request $request){
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    // $orders = Order::where('user_id', $user->id)->where('user_type','user')->where('payment_status', 'Success')->where('status', 'Success')->get();
    $lms = LmsCourse::where('user_id', $user->id)->where('user_type','user')->where('expire_date', '>=', date('Y-m-d'))->where('status', 1)->orderBy('id', 'desc')->get();
    // return $orders;
    return view(themeView().'account.course', compact('lms','user'));
  }

  public function exploreCourse(Request $request){
    $showStatus = true;
    $nav = 'explore';
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course = Course::where('slug', str_slug($request->slug))->first();
     // if(!$course || $course->type == 1) abort(404);
     if(!$course) abort(404);
     $first = null;
     $module = null;
     $content = null;
     // if($course->modules && isset($course->modules[0])){
     //   $module = $course->modules[0];
     //   if(isset($module->content[0])){
     //     $content = $module->content[0];
     //     $first = $module->content[0]->videos;
     //   }
     // }
     // if(!$first){
     //   return view(themeView().'comingsoon.index');
     //   // abort(404);
     // }
     $modules = CourseModule::where('course_id', $course->id)->where('status', 1)->orderBy('order', 'asc')->get();
     $courseExploreModules = CourseExploreModule::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course->id)->where('passed', 1);
     $watchedModules = $courseExploreModules->count();
     $percentage = ($watchedModules/count($modules))*100;
     $percentage = (int)$percentage;
    return view(themeView().'account.explore.index', compact('nav', 'course', 'module','content', 'first', 'percentage', 'showStatus'));
  }

  public function canWatchModule(Request $request){
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    if(!$user) return response()->json(['success'=>false, 'message'=>'authenticate first.'],402);
    $course_id = $request->course_id;
    $module_id = $request->module_id;
    $modules = CourseModule::where('course_id', $course_id)->where('status', 1)->orderBy('order', 'asc')->get();
    $modulesIds = $request->modulesIds;
    $canWatch = false;
    $courseExploreModulesQuery = CourseExploreModule::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('passed', 1);
    $watchedModules = $courseExploreModulesQuery->count();
    $percentage = ($watchedModules/count($modules))*100;
    $percentage = (int)$percentage;
    $courseExploreModules = $courseExploreModulesQuery->whereIn('module_id', $modulesIds)->count();
    if(count($modulesIds) == $courseExploreModules){
      $canWatch = true;
    }
    return response()->json(['success'=>$canWatch, 'canWatch'=>$canWatch?'yes':'no', 'percentage'=>$percentage, 'count'=>$courseExploreModules]);
  }

  public function howMuchWatch(Request $request){
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    if(!$user) return response()->json(['success'=>false, 'message'=>'authenticate first.'],402);
    $course_id = $request->course_id;
    $module_id = $request->module_id;
    $content_id = $request->content_id;
    $video_id = $request->video_id;
    $duration = $request->duration?:0; //seconds
    $currentTime = $request->currentTime?:0; //seconds
    $courseExplore = CourseExplore::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->where('content_id', $content_id)->where('video_id', $video_id)->first();
    if(!$courseExplore){
      $courseExplore = new CourseExplore();
      $courseExplore->user_id = $user->id;
      $courseExplore->user_type = $user_type;
      $course = Course::find($course_id);
      $courseExplore->course_id = $course_id;
      $courseExplore->course_name = $course->name;
      $courseModule = CourseModule::find($module_id);
      $courseExplore->module_id = $module_id;
      $courseExplore->module_name = $courseModule->name;
      $courseModuleContent = CourseModuleContent::find($module_id);
      $courseExplore->content_id = $content_id;
      $courseExplore->content_name = $courseModuleContent?$courseModuleContent->title:'';
      $courseExplore->video_id = $video_id;
      $courseExplore->duration = $duration;
      $courseExplore->watched_duration = $currentTime;
    }
    $courseExplore->watched_duration = $courseExplore->watched_duration < $currentTime ? $currentTime : $courseExplore->watched_duration;
    $percentage = ($courseExplore->watched_duration/$courseExplore->duration)*100;
    $courseExplore->percentage = $percentage;
    $courseExplore->save();
    $totalModuelCotntent = CourseModuleContent::where('course_id', $course_id)->where('module_id', $module_id)->count();
    $totalModuleContentExplored = CourseExplore::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->where('percentage', '>=', 80)->count();
    $moduleDone = 'no';
    if($totalModuleContentExplored >= $totalModuelCotntent){
      CourseExploreModule::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->delete();
      $cEM = new CourseExploreModule();
      $cEM->user_id = $user->id;
      $cEM->user_type = $user_type;
      $cEM->course_id = $course_id;
      $cEM->module_id = $module_id;
      $cEM->passed = 1;
      $cEM->save();
      $moduleDone = 'yes';
    }
    return response()->json(['success'=>true, 'message'=>'done', 'moduleDone'=>$moduleDone], 200);
  }

  public function passedModule(Request $request){
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course_id = $request->course_id;
    $module_id = $request->module_id;
    $cEM = CourseExploreModule::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->first();
    if(!$cEM){
      $cEM = new CourseExploreModule();
      $cEM->user_id = $user->id;
      $cEM->user_type = $user_type;
      $cEM->course_id = $course_id;
      $cEM->module_id = $module_id;
    }
    $cEM->passed = 1;
    $cEM->save();
    return response()->json(['success'=>true, 'course_id'=>$course_id, 'module_id'=>$module_id], 200);
  }

  public function assignments(Request $request){
    $type = $request->type; //assignment , quizz
    $nav = $type;
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $course = Course::where('slug', $course_slug)->first();
    if(!$course) abort(404);
    $passed = 0;
    $failed = 0;
    $modulesArray = CourseModule::where('course_id', $course->id)->pluck('minus_marks', 'id')->toArray();
    foreach ($modulesArray as $module_id => $marks) {
      $totalQuestions = CourseModuleQuestion::where('module_id', $module_id)->where('show_in', $type)->count();
      if($totalQuestions){
        $answers = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('module_id', $module_id)->where('show_in', $type);
        $totalAttemptedQuestions = $answers->count();
        // return $totalAttemptedQuestions;
        // return $totalQuestions;
        if($totalAttemptedQuestions == $totalQuestions){
          $mrks = $answers->sum('marks');
          $passingMarks = (($totalQuestions*$marks)/100)*33;
          if($mrks >= $passingMarks){
            $passed++;
          }else{
            $failed++;
          }
        }
      }
    }
    return view(themeView().'account.explore.'.$type.'.index', compact('nav', 'user', 'course','passed', 'failed'));
  }

  public function assignmentQuestions(Request $request)
  {
    $type = $request->type; //assignment , quizz
    $nav = $type;
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $module_id = $request->module_id;
    $course = Course::where('slug', $course_slug)->first();
    if(!$course) return abort(404);
    $module = CourseModule::where('id', $module_id)->where('course_id', $course->id)->first();
    if(!$module) return abort(404);
    $attemptedQuestion = null;
    $next = false;
    if($type == 'quizz'){
      $currentTime = date('H:i:s');
      $attemptedQuestion = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course->id)->where('module_id', $module->id)->where('show_in', $type)->orderBy('id', 'desc')->first();
      if($attemptedQuestion && $attemptedQuestion->answer > 0){
        $next = true;
      }elseif($attemptedQuestion && ($attemptedQuestion->answer == 0) && ($attemptedQuestion->end_time < $currentTime)){
        $next = true;
      }
    }
    return view(themeView().'account.explore.'.$type.'.questions', compact('nav', 'user', 'course', 'module', 'attemptedQuestion', 'next'));
  }

  public function fetchAssignmentQuestion(Request $request){
    $type = $request->type; //assignment , quizz
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course_id = $request->course_slug;
    $module_id = $request->module_id;
    $question_id = $request->question_id;
    $module = CourseModule::find($module_id);
    if(!$module){
      return response()->json(['success'=>false, 'msg'=>'Question not found.'], 402);
    }
    $question = CourseModuleQuestion::where('id', $question_id)->where('course_id', $course_id)->where('module_id', $module_id)->where('show_in', $type)->first();
    if(!$question){
      return response()->json(['success'=>false, 'msg'=>'Question not found.'], 402);
    }
    $hasAnswer = false;
    if($type == 'assignment'){
      $answer = $question->userAssignmentAnswer;
    }elseif($type == 'quizz'){
      $answer = $question->quizzAnswer;
    }
    if($answer){
      $hasAnswer = true;
      $answer = $answer->answer;
    }
    $assignmentAnswer = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->where('question_id', $question_id)->where('show_in', $type)->first();
    if(!$assignmentAnswer){
      $assignmentAnswer = new AssignmentAnswer();
      $assignmentAnswer->user_id = $user->id;
      $assignmentAnswer->user_type = $user_type;
      $assignmentAnswer->course_id = $question->course_id;
      $assignmentAnswer->module_id = $question->module_id;
      $assignmentAnswer->content_id = $question->content_id;
      $assignmentAnswer->question_id = $question->id;
      $assignmentAnswer->show_in = $type;
      $startTime = date('H:i:s');
      $endTime = date('H:i:s', strtotime($startTime.'+'.$module->total_time.'minute'));
      // $endTime = date('H:i:s', strtotime($startTime.'+1minute'));
      $assignmentAnswer->start_time = $startTime;
      $assignmentAnswer->end_time = $endTime;
      $assignmentAnswer->save();
    }

    return response()->json(['success'=>$question?true:false, 'show_in'=>$type, 'question'=>$question, 'answer'=>$hasAnswer?$answer:false, 'startTime'=>$assignmentAnswer->start_time, 'endTime'=>Carbon::parse($assignmentAnswer->end_time)], 200);
  }
  public function submitAssignmentQuestion(Request $request){
    $this->validate($request,[
      'question_id'=>'required'
    ]);
    $type = $request->type; //assignment , quizz
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course_id = $request->course_slug;
    $module_id = $request->module_id;
    $choosed_option = $request->choosed_option < 0 ? null : $request->choosed_option;
    $question_id = $request->question_id;
    // return $question_id;
    if($choosed_option == null){
      return response()->json(['success'=>false, 'msg'=>'answer required'], 402);
    }
    $question = CourseModuleQuestion::where('id', $question_id)->where('course_id', $course_id)->where('module_id', $module_id)->where('show_in', $type)->first();
    if(!$question){
      return response()->json(['success'=>false, 'msg'=>'QuestionId required'], 402);
    }
    $assignmentAnswer = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module_id)->where('content_id', $question->content_id)->where('question_id', $question_id)->where('show_in', $type)->first();
    $moudlee = CourseModule::find($module_id);
    if(!$assignmentAnswer){
      $assignmentAnswer = new AssignmentAnswer();
      $assignmentAnswer->user_id = $user->id;
      $assignmentAnswer->user_type = $user_type;
      $assignmentAnswer->course_id = $course_id;
      $assignmentAnswer->module_id = $module_id;
      $assignmentAnswer->content_id = $question->content_id;
      $assignmentAnswer->question_id = $question_id;
    }
    $assignmentAnswer->answer = $choosed_option;
    $assignmentAnswer->show_in = $type;
    $assignmentAnswer->marks = 0;
    if($choosed_option == $question->answer){
      $assignmentAnswer->marks = $moudlee->minus_marks;
    }
    $assignmentAnswer->save();
    return response()->json(['success'=>true, 'msg'=>'answer submitted'], 200);
  }
  public function courseResult(Request $request){
    $type = $request->type; //assignment , quizz
    $nav = 'result';
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $module_id = $request->module_id;
    $course = Course::where('slug', $course_slug)->first();
    if(!$course) return abort(404);
    $module = CourseModule::where('id', $module_id)->where('course_id', $course->id)->first();
    if(!$module) return abort(404);
    return view(themeView().'account.explore.'.$type.'.result', compact('nav', 'user', 'course', 'module'));
  }

  public function resultWebView(Request $request){
    $nav = 'result';
    $show_only_result = true;
    $type = $request->type; //assignment , quizz
    $api_token = $request->api_token;
    $module_id = $request->module_id;
    $user = User::where('api_token', $api_token)->first();
    $user_type = 'user';
    $module = CourseModule::find($module_id);
    if(!$module) return abort(404);
    $course = Course::find($module->course_id);
    if(!$course) return abort(404);
    return view(themeView().'account.explore.'.$type.'.result', compact('nav', 'user', 'course', 'module', 'show_only_result'));
  }

  public function courseCertificate(Request $request){
    $nav = 'course certificate';
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $module_id = $request->module_id;
    $course = Course::where('slug', $course_slug)->first();
    if(!$course) return abort(404);
    $module = CourseModule::where('id', $module_id)->where('course_id', $course->id)->first();
    if(!$module) return abort(404);
    $order = OrderItem::where('product_id', $course->id)->where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'desc')->first();
    $path = public_path('certificate/user/'.$user->id.'/');
    $fileName = $course->slug.'.pdf';
    $pdfurl = asset('certificate/user/'.$user->id.'/'.$fileName);
    if(!File::exists($pdfurl)) {
      $path = public_path('certificate');
      if(!File::exists($path)){
          File::makeDirectory($path);
      }
      $path = public_path('certificate/user');
      if(!File::exists($path)){
          File::makeDirectory($path);
      }
      $path = public_path('certificate/user/'.$user->id);
      if(!File::exists($path)){
          File::makeDirectory($path);
      }
      $pdf = PDF::loadView(themeView().'account.explore.certificate', compact('nav', 'user', 'course', 'module', 'order'));
      $path = public_path('certificate/user/'.$user->id.'/');
      $pdf->save($path.$fileName);
      $pdf = null;
    }
    return redirect($pdfUrl);
    // return view(themeView().'account.explore.certificate', compact('nav', 'user', 'course', 'module', 'order'));
  }

  public function certificate(Request $request){
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $course = Course::where('slug', $course_slug)->first();
    if($course){
      $url = downloadCertificate($user, $user_type, $course);
      return redirect($url);
    }
    return redirect()->back();
  }

  public function certificateApp(Request $request){
    $course_slug = $request->course_slug;
    $api_token = $request->api_token;
    $user = User::where('api_token', $api_token)->first();
    $user_type = 'user';
    if(!$user){
      return abort(404);
    }
    $course = Course::where('slug', $course_slug)->first();
    if($course){
      $url = downloadCertificate($user, $user_type, $course);
      return redirect($url);
    }
    return abort(404);
  }

  public function meetings(Request $request)
  {
      $nav = 'meeting';
      $user = auth()->guard('web')->user();
      $user_type = 'user';
      $course_slug = $request->course_slug;
      $course = Course::where('slug', $course_slug)->first();
      if(!$course) return abort(404);
      return view(themeView().'account.explore.meetings', compact('nav', 'user', 'course'));
  }

  public function support(Request $request)
  {
      $nav = 'support';
      $user = auth()->guard('web')->user();
      $user_type = 'user';
      if(!$user->is_verified || !$user->status) abort(404);
      $tickets = Support::where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'DESC')->limit(8)->get();
      return view(themeView().'account.support', compact('nav', 'user', 'user_type', 'tickets'));
  }

  public function submitSupport(Request $request)
  {
    $this->validate($request, [
      'subject'=>'required',
      'type'=>'required',
      'message'=>'required',
    ],[
      'subject.required'=>'Subject is required',
      'type.required'=>'Type is required',
      'message.required'=>'Message is required',
    ]);
    $nav = 'support';
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $type = $request->type;
    $support = Support::where('user_id', $user->id)->where('user_type', $user_type)->where('type', $type)->where('status', 'open')->first();
    if($support){
      return response()->json(['success'=>false, 'errors'=>['type'=>['You already raised ticked for this type.']]], 422);
    }
    if($request->hasFile('file')){
      $path = public_path('support');
      if(!File::exists($path)){
        File::makeDirectory($path);
      }
      $path = public_path('support/user');
      if(!File::exists($path)){
        File::makeDirectory($path);
      }
      $path = public_path('support/user/'.$type);
      if(!File::exists($path)){
        File::makeDirectory($path);
      }
      $time = time();
      $fileName = $time.'.'.$request->file->getClientOriginalExtension();
      $request->file->move($path, $fileName);
      $filePath = 'support/user/'.$type.'/'.$fileName;
    }
    $support = new Support();
    $support->user_id = $user->id;
    $support->user_type = $user_type;
    $support->subject = $request->subject;
    $support->type = $type;
    $support->message = $request->message;
    $support->file = $request->hasFile('file')?$filePath:'';
    $support->status = 'open';
    $support->save();

    $ticket_number = 'REQ-'.date('ym');
    if($support->id <= 9){
      $ticket_number = 'REQ-'.date('ym').'0000'.$support->id;
    }elseif($support->id <= 99){
      $ticket_number = 'REQ-'.date('ym').'000'.$support->id;
    }elseif($support->id <= 999){
      $ticket_number = 'REQ-'.date('ym').'00'.$support->id;
    }elseif($support->id <= 9999){
      $ticket_number = 'REQ-'.date('ym').'0'.$support->id;
    }else{
      $ticket_number = 'REQ-'.date('ym').$support->id;
    }
    $support->ticket_number = $ticket_number;
    $support->save();

    $user = User::find($user->id);
    SupportUserJob::dispatch($user, $support);
    Notification::route('mail', [
      config("vrdcms.admin_email") => 'Customer Support', //admin
    ])->notify(new SupportAdminNotification($user, 'user', $support));
    $data = view(themeView().'account.ticket_list', compact('support'))->render();
    return response()->json(['success'=>true, 'message'=>'Your request has been submitted successfully. Please wait for support team response.', 'data'=>$data], 200);
  }
  public function fetchSupportReplies(Request $request){
    $support_id = $request->support_id;
    $support = Support::find($support_id);
    if(!$support){
      return response()->json(['success'=>false, 'message'=>'not found'], 200);
    }
    $user_type = 'user';

    $replies = SupportReplies::where('support_id', $support_id)->get();
    $data = view(themeView().'account.replies_list', compact('replies', 'support', 'user_type'))->render();
    return response()->json(['success'=>$replies?true:false, 'data'=>$data, 'status'=>$support?$support->status:'closed', '
    '=>$support?$support->message:''], 200);
  }
  public function supportReplySubmit(Request $request){
    $this->validate($request, [
      'support_reply'=>'required'
    ],[
      'support_reply.required'=>'Reply is required'
    ]);
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $support_id = $request->support_id;
    $support = Support::find($support_id);
    if(!$support){
      return response()->json(['success'=>false, 'message'=>'not found'], 200);
    }
    $reply = new SupportReplies();
    $reply->support_id = $support->id;
    $reply->reply = $request->support_reply;
    $reply->reply_by = $user->id;
    $reply->reply_type = 'user';
    $reply->save();
    $replies = SupportReplies::where('support_id', $support_id)->get();
    $data = view(themeView().'account.replies_list', compact('replies', 'support', 'user_type'))->render();
    return response()->json(['success'=>$replies?true:false, 'data'=>$data, 'status'=>$support?$support->status:'closed', 'message'=>$support?$support->message:'', 'support_id'=>$support_id], 200);
  }

  public function switch(Request $request){
    $user = auth()->guard('web')->user();
    $astrologer = Astrologer::find($user->astrologer_id);
    if($astrologer){
      Auth::guard('web')->logout();
      Auth::guard('astrologer')->login($astrologer);
      $astrologer->last_logined = date('Y-m-d H:i:s');
      $astrologer->video = 1;
      $astrologer->chat = 1;
      $astrologer->call = 1;
      $astrologer->save();
      return redirect()->route('astrologer.dashboard');
    }
    return redirect()->back();
  }

  public function consultationRequest(Request $request){
    $this->validate($request, [
      'request_type'=>'required',
      'appointment_id'=>'required',
      'message'=>'required',
    ]);
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $consultation_request = ConsultationRequest::where('appointment_id', $request->appointment_id)->where('status', 0)->first();
    if($consultation_request){
      return response()->json(['success'=>false, 'message'=>'Please Try again later'], 422);
    }
    $data = AstrologerAppointment::find($request->appointment_id);
    if(!$data){
      return response()->json(['success'=>false, 'message'=>'Appointment not found.'], 422);
    }
    if($request->request_type=='reschedule'){
      $check_counter = [$data->id, $data->appointment_id];
      $consultation_counter = ConsultationCounter::whereIn('appointment_id', $check_counter)->where('user_id', $user->id)->where('user_type', $user_type)->first();
      if($consultation_counter->counter==2){
        return response()->json(['success'=>false, 'message'=>'Reached maximum limit of reschedule.'], 422);
      }
    }
    $cancel_before = fromSetting('cancel_before')?:24;
    $cancel_before = $cancel_before*60*60;
    $scheduled = strtotime($data->scheduled_date.' '.$data->scheduled_time)-time();
    if($cancel_before >= $scheduled){
      return response()->json(['success'=>false, 'message'=>"Your scheduled consultation is about to begin. It won't be ".ucfirst($request->request_type) ], 422);
    }
    $consultation_request = new ConsultationRequest();
    $consultation_request->appointment_id = $request->appointment_id;
    $consultation_request->user_id = $user->id;
    $consultation_request->user_type = $user_type;
    $consultation_request->message = $request->message;
    $consultation_request->request_type = $request->request_type;
    $consultation_request->status = 0;
    $consultation_request->save();
    if($request->request_type=='cancel'){
      ConsultationCancelJob::dispatch('mehtachandansingh101@gmail.com', 'user', 'admin', $data, 'request', $request->message);
    }elseif($request->request_type=='reschedule'){
      ConstultatonRescheduleJob::dispatch('mehtachandansingh101@gmail.com', 'user', 'admin', $data, 'request', $request->message);
    }
    return response()->json(['success'=>true, 'message'=>'Requst Submit successfully'], 200);
  }

  public function orderRequest(Request $request){
    $this->validate($request, [
      'request_type'=>'required',
      'order_id'=>'required',
      'reason'=>'required',
    ]);
    $request_type = $request->request_type;
    if($request_type=='return'){
      $this->validate($request, [
        'items'=>'required|array',
      ]);
    }
    // dd('done');
    $user = auth()->guard('web')->user();
    $user_type = 'user';
    $order_request = OrderRequest::where('order_id', $request->order_id)->where('status', 0)->first();
    if($order_request){
      return response()->json(['success'=>false, 'message'=>'Please Try again later'], 422);
    }
    $order = Order::find($request->order_id);
    if(!$order){
      return response()->json(['success'=>false, 'message'=>'Order not found.'], 422);
    }
    $order_request = new OrderRequest();
    $order_request->order_id = $order->id;
    $order_request->user_id = $user->id;
    $order_request->request_type = $request->request_type;
    if($request->request_type=='cancel'){
      $order_request->reason = orderRequestReason($request->reason);
    }else{
      $order_request->reason = orderRequestReturnReason($request->reason);
    }
    $order_request->message = $request->message;
    $order_request->status = 0;
    $order_request->save();
    if($request->request_type=='return'){
      foreach ($request->items as $key => $value) {
        $order_request_item = new OrderRequestItem();
        $order_request_item->request_id = $order_request->id;
        $order_request_item->order_id = $order_request->order_id;
        $order_request_item->user_id = $order_request->user_id;
        $order_request_item->item_id = $value;
        $order_request_item->save();
      }
    }
    OrderRequestJob::dispatch(config("vrdcms.admin_email"), $order, $order_request, null, $request->request_type, 0);
    return response()->json(['success'=>true, 'message'=>'Request Submit successfully'], 200);
  }

  public function other(Request $request){
    $supports = Support::get();
    $supports->map(function($item){
      $ym = date('ym', strtotime($item->created_at));
      $ticket_number = 'REQ-'.$ym;
      if($item->id <= 9){
        $ticket_number = 'REQ-'.$ym.'0000'.$item->id;
      }elseif($item->id <= 99){
        $ticket_number = 'REQ-'.$ym.'000'.$item->id;
      }elseif($item->id <= 999){
        $ticket_number = 'REQ-'.$ym.'00'.$item->id;
      }elseif($item->id <= 9999){
        $ticket_number = 'REQ-'.$ym.'0'.$item->id;
      }else{
        $ticket_number = 'REQ-'.$ym.$item->id;
      }
      $item->ticket_number = $ticket_number;
      $item->save();
    });
    return 'done';
  }

  public function login(Request $request){
    $user_type = "user";
    $user_id = $request->user_id ? decrypt($request->user_id) : 0;
    $user = User::find($user_id);
    if($user==null){
      return redirect()->back();
    }
    Auth::guard('web')->logout();
    Auth::guard('astrologer')->logout();
    Auth::guard('web')->login($user);
    return $user;
  }
  public function userNotification(Request $request){
    $request->validate([
      "astrologer_id"=>"required|integer",
      "type"=>"required|in:astrologer-online,astrologer-live",
    ]);
    $user = auth()->guard("web")->user();
    $astrologer_id=$request->astrologer_id?:0;
    $astrologer = Astrologer::find($astrologer_id);
    $success = false;
    $message = "";
    if($user && $astrologer){
      $notify_to = "user";
      $type = $request->type;
      $description = "Astrologer : {$astrologer->display_name} is now online.";
      if($type=="astrologer-live"){
        $description = "Astrologer : {$astrologer->display_name} is now live.";
      }
      $userNotification = UserNotification::where('user_id', $user->id)
      ->where('astrologer_id', $astrologer->id)
      ->where('notify_to', $notify_to)
      ->where('type', $type)
      ->first();
      if($userNotification==null){
        $userNotification = new UserNotification();
        $userNotification->user_id = $user->id;
        $userNotification->astrologer_id = $astrologer->id;
        $userNotification->notify_to = $notify_to;
        $userNotification->type = $type;
      }
      $userNotification->user_email = $user->email;
      $userNotification->astrologer_email = $astrologer->email;
      $userNotification->description = $description;
      $userNotification->is_sent = 0;
      $userNotification->save();
      $success = true;
      $message = "Notify you when {$astrologer->display_name} online back.";
      if($type=="astrologer-live"){
        $message = "Notify you when {$astrologer->display_name} live.";
      }
    }
    return response()->json(["success" => $success, "message" => $message], 200);;
  }

  public function bookedPuja(Request $request)
  {
    $nav = 'bookedPuja';
    $user = auth()->guard('web')->user();

    // Get all booked one-to-one pujas for this user (excluding cancelled and expired)
    $oneToOneAssignments = PujaAstrologerAssignment::with(['product', 'astrologer', 'order', 'approvedMedia', 'mahurat'])
      ->where('user_id', $user->id)
      ->where('status', '!=', 'cancelled')
      ->whereHas('product', function($query) {
        $query->where('type', 2)
              ->where('is_one_to_one_puja', 1)
              ->where('status', 1);
      })
      ->get()
      ->filter(function($assignment) {
        // Check if puja is expired
        if($assignment->product && $assignment->product->expire_at) {
          $expireTime = strtotime($assignment->product->expire_at);
          $currentTime = time();
          if($expireTime < $currentTime) {
            return false; // Puja has expired
          }
        }
        return true;
      })
      ->sortByDesc(function($assignment) {
        return $assignment->created_at;
      })
      ->values();

    // Get all booked group pujas for this user (excluding cancelled and expired)
    $groupPujaOrders = \App\Order::where('user_id', $user->id)
      ->where('user_type', 'user')
      ->where('payment_status', 'Success')
      ->where('status', 'Success')
      ->whereNotNull('group_puja_reference')
      ->whereHas('orderItems', function($query) {
        $query->where('type', 'shop')
              ->whereHas('product', function($q) {
                $q->where('type', 2)
                  ->where(function($subQ) {
                    $subQ->where('is_one_to_one_puja', '!=', 1)
                         ->orWhereNull('is_one_to_one_puja');
                  })
                  ->where('status', 1);
              });
      })
      ->with(['orderItems.product'])
      ->orderBy('created_at', 'DESC')
      ->get()
      ->map(function($order) {
        // Get parent product if variation
        $firstOrderItem = $order->orderItems->first();
        $product = $firstOrderItem ? $firstOrderItem->product : null;
        $parentProduct = $product;
        if($product && $product->parent_id && $product->parent_id > 0) {
          $parentProduct = \App\Product::find($product->parent_id);
        }

        // Get astrologer_id from product
        $astrologerId = $parentProduct ? $parentProduct->pandit_id : null;

        // Check if approved media exists for this group puja
        $hasApprovedMedia = false;
        if($order->group_puja_reference && $parentProduct && $astrologerId) {
          $approvedMediaCount = \App\Model\AstrologerPujaMedia::where('group_puja_reference', $order->group_puja_reference)
            ->where('product_id', $parentProduct->id)
            ->where('astrologer_id', $astrologerId)
            ->where('is_approved', 1)
            ->count();
          $hasApprovedMedia = $approvedMediaCount > 0;
        }

        $order->parent_product = $parentProduct;
        $order->astrologer_id = $astrologerId;
        $order->has_approved_media = $hasApprovedMedia;

        return $order;
      })
      ->filter(function($order) {
        // Check if any product in order is expired
        if($order->parent_product && $order->parent_product->expire_at) {
          $expireTime = strtotime($order->parent_product->expire_at);
          $currentTime = time();
          if($expireTime < $currentTime) {
            return false; // Puja has expired
          }
        }
        return true;
      })
      ->values();

    return view(themeView().'account.booked-puja', compact('nav', 'user', 'oneToOneAssignments', 'groupPujaOrders'));
  }

  public function bookedPujaDetail(Request $request, $assignment_id)
  {
    $nav = 'bookedPuja';
    $user = auth()->guard('web')->user();

    // Get assignment with approved media only
    $assignment = PujaAstrologerAssignment::with(['product', 'astrologer', 'order', 'mahurat'])
      ->where('id', $assignment_id)
      ->where('user_id', $user->id)
      ->first();

    if(!$assignment) {
      return redirect()->route('user.booked-puja')->with('error', 'Puja not found');
    }

    // Get only approved images and videos (if display_in_user_profile is enabled)
    $images = collect();
    $videos = collect();

    if($assignment->display_in_user_profile) {
      $images = AstrologerPujaMedia::where('assignment_id', $assignment_id)
        ->where('type', 'image')
        ->where('is_approved', 1)
        ->with('media')
        ->orderBy('created_at', 'desc')
        ->get();

      $videos = AstrologerPujaMedia::where('assignment_id', $assignment_id)
        ->where('type', 'video')
        ->where('is_approved', 1)
        ->with('video')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    return view(themeView().'account.booked-puja-detail', compact('nav', 'user', 'assignment', 'images', 'videos'));
  }

  /**
   * For group puja: which single valid date applies to this order (user booked before that date's cutoff).
   * @param \App\Order $order
   * @param \App\Product $parentProduct
   * @return string|null Y-m-d or null
   */
  protected function getValidDateForGroupOrder($order, $parentProduct)
  {
    if (!$order || !$parentProduct) {
      return null;
    }
    $tz = config('app.timezone', 'Asia/Kolkata');
    $orderDt = $order->created_at ? Carbon::parse($order->created_at)->setTimezone($tz) : null;
    $orderTs = $orderDt ? $orderDt->timestamp : 0;

    $dates = $parentProduct->valid_till_dates;
    $timePart = $parentProduct->expire_at ? Carbon::parse($parentProduct->expire_at)->format('H:i:s') : '23:59:59';
    if (!empty($dates) && is_array($dates)) {
      $dates = array_values(array_filter(array_map('trim', $dates), function ($d) {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $d);
      }));
      sort($dates);
      foreach ($dates as $d) {
        $expiryTs = Carbon::parse($d . ' ' . $timePart, $tz)->timestamp;
        if ($orderTs <= $expiryTs) {
          return $d;
        }
      }
      return count($dates) > 0 ? end($dates) : null;
    }
    if ($parentProduct->expire_at) {
      return Carbon::parse($parentProduct->expire_at)->format('Y-m-d');
    }
    return null;
  }

  public function bookedGroupPujaDetail(Request $request)
  {
    // Log immediately to confirm method is called
    \Log::info('=== Group Puja Detail Method Called ===', [
      'url' => $request->fullUrl(),
      'method' => $request->method(),
      'all_params' => $request->all(),
      'query' => $request->query()
    ]);

    $nav = 'bookedPuja';
    $user = auth()->guard('web')->user();

    if(!$user) {
      \Log::error('Group Puja Detail - User not authenticated');
      return redirect()->route('user.login')->with('error', 'Please login to view puja details.');
    }

    // Get parameters from query string - try multiple methods
    $groupPujaReference = $request->query('group_puja_reference')
      ?: $request->input('group_puja_reference')
      ?: $request->get('group_puja_reference');
    $productId = $request->query('product_id')
      ?: $request->input('product_id')
      ?: $request->get('product_id');
    $orderId = $request->query('order_id')
      ?: $request->input('order_id')
      ?: $request->get('order_id');

    // Log for debugging
    \Log::info('Group Puja Detail Request - Parameters extracted', [
      'group_puja_reference' => $groupPujaReference,
      'product_id' => $productId,
      'order_id' => $orderId,
      'user_id' => $user->id,
      'all_params' => $request->all(),
      'query_params' => $request->query(),
      'url' => $request->fullUrl()
    ]);

    // Manual validation with better error messages
    if(!$groupPujaReference || !$productId || !$orderId) {
      \Log::error('Group Puja Detail - Missing parameters - REDIRECTING', [
        'group_puja_reference' => $groupPujaReference ?: 'EMPTY',
        'product_id' => $productId ?: 'EMPTY',
        'order_id' => $orderId ?: 'EMPTY',
        'all_request' => $request->all(),
        'query' => $request->query()
      ]);
      return redirect()->route('user.booked-puja')->with('error', 'Invalid parameters. Please try again.');
    }

    // Convert to integers
    $productId = (int)$productId;
    $orderId = (int)$orderId;

    // Verify order belongs to user - check step by step with detailed logging
    $order = \App\Order::where('id', $orderId)->first();

    \Log::info('Group Puja Detail - Order Check', [
      'order_id' => $orderId,
      'order_found' => $order ? 'yes' : 'no',
      'order_user_id' => $order ? $order->user_id : null,
      'current_user_id' => $user->id,
      'order_user_type' => $order ? $order->user_type : null,
      'order_group_ref' => $order ? $order->group_puja_reference : null,
      'requested_ref' => $groupPujaReference
    ]);

    if(!$order) {
      \Log::error('Group Puja Detail - Order not found by ID', [
        'order_id' => $orderId
      ]);
      return redirect()->route('user.booked-puja')->with('error', 'Order not found.');
    }

    // Check if order belongs to user - be more lenient with user_type
    if($order->user_id != $user->id) {
      \Log::error('Group Puja Detail - Order user ID mismatch', [
        'order_id' => $orderId,
        'order_user_id' => $order->user_id,
        'current_user_id' => $user->id
      ]);
      return redirect()->route('user.booked-puja')->with('error', 'You do not have permission to view this puja.');
    }

    // Check user_type - be very lenient, don't redirect
    if($order->user_type && $order->user_type != 'user' && $order->user_type != '') {
      \Log::warning('Group Puja Detail - Order user type is not "user" but continuing', [
        'order_id' => $orderId,
        'order_user_type' => $order->user_type
      ]);
      // Don't redirect - just log warning and continue
    }

    // Check group puja reference - be flexible, use order's if available
    if($order->group_puja_reference) {
      // Order has a reference - use it (even if it doesn't match request)
      $groupPujaReference = $order->group_puja_reference;
      \Log::info('Group Puja Detail - Using order reference', [
        'order_id' => $orderId,
        'order_reference' => $order->group_puja_reference,
        'original_requested' => $request->query('group_puja_reference')
      ]);
    } elseif($groupPujaReference) {
      // Order doesn't have reference but request has one - that's okay
      \Log::info('Group Puja Detail - Order has no reference, using requested reference', [
        'order_id' => $orderId,
        'requested_reference' => $groupPujaReference
      ]);
    } else {
      // No reference at all - log but continue
      \Log::warning('Group Puja Detail - No group puja reference found anywhere', [
        'order_id' => $orderId
      ]);
    }

    // Get product
    $product = \App\Product::find($productId);
    if(!$product) {
      \Log::error('Group Puja Detail - Product not found', ['product_id' => $productId]);
      return redirect()->route('user.booked-puja')->with('error', 'Product not found.');
    }

    // Get parent product if variation
    $parentProduct = $product;
    if($product->parent_id && $product->parent_id > 0) {
      $parentProduct = \App\Product::find($product->parent_id);
      if(!$parentProduct) {
        \Log::error('Group Puja Detail - Parent product not found', [
          'product_id' => $productId,
          'parent_id' => $product->parent_id
        ]);
        return redirect()->route('user.booked-puja')->with('error', 'Parent product not found.');
      }
    }

    // Get astrologer_id from product
    $astrologerId = $parentProduct ? $parentProduct->pandit_id : null;

    // Initialize empty collections
    $images = collect();
    $videos = collect();

    // Only fetch media if astrologer is assigned
    if($astrologerId) {
      // Get approved media for this group puja
      $images = AstrologerPujaMedia::where('group_puja_reference', $groupPujaReference)
        ->where('product_id', $parentProduct->id)
        ->where('astrologer_id', $astrologerId)
        ->where('type', 'image')
        ->where('is_approved', 1)
        ->with('media')
        ->orderBy('created_at', 'desc')
        ->get();

      $videos = AstrologerPujaMedia::where('group_puja_reference', $groupPujaReference)
        ->where('product_id', $parentProduct->id)
        ->where('astrologer_id', $astrologerId)
        ->where('type', 'video')
        ->where('is_approved', 1)
        ->with('video')
        ->orderBy('created_at', 'desc')
        ->get();
    } else {
      \Log::warning('Group Puja Detail - Astrologer not assigned', [
        'product_id' => $parentProduct->id,
        'pandit_id' => $parentProduct->pandit_id ?? 'null'
      ]);
    }

    // Get astrologer if available
    $astrologer = null;
    if($astrologerId) {
      $astrologer = \App\Model\Astrologer::find($astrologerId);
    }

    // Get mahurat if available (for group puja, mahurat might be stored differently)
    $mahurat = null;
    // Try to find mahurat from any assignment linked to this order
    $firstAssignment = PujaAstrologerAssignment::where('order_id', $orderId)
      ->where('product_id', $parentProduct->id)
      ->with('mahurat')
      ->first();
    if($firstAssignment && $firstAssignment->mahurat) {
      $mahurat = $firstAssignment->mahurat;
    }

    // Which single valid date applies to this user's booking (booked before that date's cutoff)
    $groupPujaScheduleDateDisplay = null;
    $validDateForOrder = $this->getValidDateForGroupOrder($order, $parentProduct);
    if ($validDateForOrder) {
      $timePart = $parentProduct->expire_at ? date('h:i A', strtotime($parentProduct->expire_at)) : null;
      $groupPujaScheduleDateDisplay = date('d M Y', strtotime($validDateForOrder)) . ($timePart ? ' ' . $timePart : '');
    }

    // Create a mock assignment object for compatibility with view
    $assignment = new \stdClass();
    $assignment->product = $parentProduct;
    $assignment->order = $order;
    $assignment->group_puja_reference = $groupPujaReference;
    $assignment->is_group_puja = true;
    $assignment->group_puja_schedule_date_display = $groupPujaScheduleDateDisplay; // single date for this user
    $assignment->created_at = $order->created_at ? ($order->created_at instanceof Carbon ? $order->created_at : Carbon::parse($order->created_at)) : null;
    $assignment->astrologer = $astrologer;
    $assignment->mahurat = $mahurat;
    $assignment->status = $order->status ?? 'assigned';
    $assignment->notes = null; // Group puja might not have notes in assignment
    $assignment->display_in_user_profile = ($images->count() > 0 || $videos->count() > 0) ? true : false;

    \Log::info('Group Puja Detail - Success - About to render view', [
      'images_count' => $images->count(),
      'videos_count' => $videos->count(),
      'view_path' => themeView().'account.booked-puja-detail',
      'assignment_product_id' => $assignment->product ? $assignment->product->id : null,
      'assignment_group_ref' => $assignment->group_puja_reference ?? null
    ]);

    try {
      $view = view(themeView().'account.booked-puja-detail', compact('nav', 'user', 'assignment', 'images', 'videos'));
      \Log::info('Group Puja Detail - View rendered successfully');
      return $view;
    } catch (\Exception $e) {
      \Log::error('Group Puja Detail - View Error', [
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
      ]);
      return redirect()->route('user.booked-puja')->with('error', 'Error loading puja details: ' . $e->getMessage());
    }
  }

  public function cancelBookedPuja(Request $request)
  {
    $user = auth()->guard('web')->user();

    $request->validate([
      'assignment_id' => 'required|exists:puja_astrologer_assignments,id',
      'reason' => 'required|string|max:1000'
    ]);

    $assignment = PujaAstrologerAssignment::where('id', $request->assignment_id)
      ->where('user_id', $user->id)
      ->with(['order', 'product', 'astrologer'])
      ->first();

    if(!$assignment) {
      return response()->json([
        'success' => false,
        'message' => 'Assignment not found or permission denied'
      ], 403);
    }

    // Check if within 24 hours (use order created_at as booking time)
    $bookingTime = $assignment->order && $assignment->order->created_at ? strtotime($assignment->order->created_at) : ($assignment->created_at ? strtotime($assignment->created_at) : time());
    $currentTime = time();
    $hoursSinceBooking = ($currentTime - $bookingTime) / 3600;

    if($hoursSinceBooking > 24) {
      return response()->json([
        'success' => false,
        'message' => 'Puja can only be cancelled within 24 hours of booking'
      ], 400);
    }

    // Check if already cancelled
    if($assignment->status == 'cancelled') {
      return response()->json([
        'success' => false,
        'message' => 'Puja is already cancelled'
      ], 400);
    }

    try {
      $assignment->status = 'cancelled';
      $assignment->notes = $request->reason;
      $assignment->save();

      // Send notification to astrologer
      if($assignment->astrologer && $assignment->product) {
        $pujaName = $assignment->product->title ?? 'Puja';
        $assignment->astrologer->notify(new \App\Notifications\PujaCancelledNotification($pujaName, $request->reason));
      }

      return response()->json([
        'success' => true,
        'message' => 'Puja cancelled successfully'
      ], 200);

    } catch (\Exception $e) {
      \Log::error('Error cancelling puja: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Error cancelling puja: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Show users who registered using this user's referral code
   */
  public function referralListing()
  {
    $nav = 'referral-listing';
    $user = auth()->guard('web')->user();

    // Get users who used this user's referral code
    $referredUsers = User::where('used_referral_code', $user->referral_code)
      ->where('status', 1)
      ->orderBy('created_at', 'DESC')
      ->get();

    return view(themeView().'account.referral-listing', compact('nav', 'user', 'referredUsers'));
  }

}
