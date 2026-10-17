<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\{Controller, PushNotificationController, NotificationController};
use Illuminate\Http\{Request, UploadedFile};
use App\Model\{RechargePlan, Course, LmsCourse, CourseModule, CourseModuleQuestion, AssignmentAnswer, CourseExplore, CourseExploreModule, ShippingAddress, UserRechargeHistory, WalletTransition, OrderStatus, Astrologer, AstrologerLive, AstrologerLiveUser, Gift, SendGift, AstrologerLiveUserChat, OrderRequest, OrderRequestItem, OrderRequestResponse};
use App\Model\{UserNotification};
use App\{Order, OrderItem, User, ProductReview};
use DB;
use App\Http\Controllers\{MediaController, InvoiceController};
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Api;
use App\Jobs\{RechargeInvoiceMailJob, PaymentVerifiedJob, EnquiryJob, RechargeFailedJob, OrderRequestJob};
use App\src\{RtcTokenBuilder2, RtmTokenBuilder2};

class AccountController extends Controller{

  public function userData(Request $request){
    $user = $request->user();
    $data['id'] = $user->id;
    $data['name'] = $user->name;
    $data['email'] = $user->email;
    $data['phone'] = $user->phone;
    $data['country_id'] = $user->country_id ?: 0;
    $data['country'] = $user->country ?: '';
    $data['state_id'] = $user->state_id ?: 0;
    $data['state'] = $user->state ?: '';
    $data['city_id'] = $user->city_id ?: 0;
    $data['city'] = $user->city ?: '';
    $data['pincode'] = $user->pincode ?: '';
    $data['address'] = $user->address ?: '';
    $data['gender_id'] = $user->gender ?: 0;
    $data['gender'] = 'Male';
    if($user->gender == 2){
      $data['gender'] = 'Female';
    }elseif($user->gender == 3){
      $data['gender'] = 'Other';
    }
    $data['image'] = $user->image ? $user->image->image(200, 200) : no_image();
    $data['wallet'] = $user->wallet ?: 0;
    $data['last_logined'] = date('d F Y h:i a', strtotime($user->last_logined));
    return response()->json(['success' => true, 'data' => $data], 200);
  }

//   public function save(Request $request)
public function save(Request $request){
$this->validate($request, [
'name'=>'required',
// 'phone'=>'required|digits:10',
// 'email'=>'required|email',
'gender_id'=>'required|gt:0',
'country_id'=>'required|gt:0',
'state_id'=>'required|gt:0',
'city_id'=>'required|gt:0',
'pincode'=>'required|digits:6',
'address'=>'required',
], [
'gender_id.required'=>'Gender is required',
'gender_id.gt'=>'Gender is required',
'country_id.required'=>'Country is required',
'country_id.gt'=>'Country is required',
'state_id.required'=>'State is required',
'state_id.gt'=>'State is required',
'city_id.required'=>'City is required',
'city_id.gt'=>'City is required',
]);
$country = DB::table('master_countries')->where('id', $request->country_id)->first();
$state = DB::table('master_states')->where('id', $request->state_id)->first();
$city = DB::table('master_cities')->where('id', $request->city_id)->first();

$user = $request->user();

$user->name = $request->name;
// $user->phone = $request->phone;
// $user->email = $request->email;

$user->country_id = $country ? $country->id : $request->country_id;
$user->country = $country ? $country->name : '';
$user->state_id = $state ? $state->id : $request->state_id;
$user->state = $state ? $state->name : '';
$user->city_id = $city ? $city->id : $request->city_id;
$user->city = $city ? $city->name : '';
$user->pincode = $request->pincode;
$user->address = $request->address;
$user->gender = $request->gender_id;

if($request->has('image') && $request->image){

   $time = time();
   $base64String = $request->image;

   // Remove Base64 image prefix if present
   if(preg_match('/^data:image\/([a-zA-Z0-9.+-]+);base64,/', $base64String, $matches)){
       $base64String = substr($base64String, strpos($base64String, ',') + 1);
   }

   // Decode Base64 image
   $binaryData = base64_decode($base64String, true);

   if($binaryData === false || empty($binaryData)){
       return response()->json([
           'success' => false,
           'message' => 'Invalid Base64 image data'
       ], 422);
   }

   // Verify actual image data
   $imageInfo = @getimagesizefromstring($binaryData);

   if($imageInfo === false){
       return response()->json([
           'success' => false,
           'message' => 'Decoded data is not a valid image'
       ], 422);
   }

   // Get actual image extension and MIME type
   $mimeTypes = [
       'image/jpeg' => 'jpg',
       'image/png' => 'png',
       'image/gif' => 'gif',
       'image/webp' => 'webp',
   ];

   $mimeType = $imageInfo['mime'];

   if(!isset($mimeTypes[$mimeType])){
       return response()->json([
           'success' => false,
           'message' => 'Unsupported image format'
       ], 422);
   }

   $extension = $mimeTypes[$mimeType];

   $tempDirectory = storage_path('app/temp');

   if(!is_dir($tempDirectory)){
       mkdir($tempDirectory, 0755, true);
   }

   $filePath = $tempDirectory.'/'.$time.'.'.$extension;

   file_put_contents($filePath, $binaryData);

   try {
       $file = new UploadedFile(
           $filePath,
           $time.'.'.$extension,
           $mimeType,
           null,
           true
       );

       $mediaController = new MediaController();

       $newRequest = new Request();

       $newRequest->files->set('file', $file);
       $newRequest->request->set('type', 'user');

       $data = $mediaController->upload($newRequest);

       $response = $data->getData();

       if(isset($response->media_id)){
           $user->image_id = $response->media_id;
       } else {
           return response()->json([
               'success' => false,
               'message' => 'Image upload failed'
           ], 422);
       }

   } finally {
       if(file_exists($filePath)){
           unlink($filePath);
       }
   }
}

$user->save();

$data = [];
$data['id'] = $user->id;
$data['name'] = $user->name;
$data['email'] = $user->email;
$data['phone'] = $user->phone;
$data['country_id'] = $user->country_id ?: 0;
$data['country'] = $user->country ?: '';
$data['state_id'] = $user->state_id ?: 0;
$data['state'] = $user->state ?: '';
$data['city_id'] = $user->city_id ?: 0;
$data['city'] = $user->city ?: '';
$data['pincode'] = $user->pincode ?: '';
$data['address'] = $user->address ?: '';
$data['gender_id'] = $user->gender ?: 0;
$data['gender'] = 'Male';

if($user->gender == 2){
  $data['gender'] = 'Female';
}elseif($user->gender == 3){
  $data['gender'] = 'Other';
}

$data['image'] = $user->image ? $user->image->image(200, 200) : no_image();
$data['wallet'] = $user->wallet ?: 0;
$data['last_logined'] = date('d F Y h:i a', strtotime($user->last_logined));

return response()->json(['success' => true, 'message' => 'Update Successfully', 'data' => $data], 200);

}


  public function addresses(Request $request){
    $user = $request->user();
    $user_type = 'user';
    $addresses = ShippingAddress::where('user_id', $user->id)->where('user_type', $user_type)->get();
    $data = $addresses->map(function($value){
      $d['address_id'] = $value->id;
      $d['name'] = $value->name;
      $d['email'] = $value->email;
      $d['phone'] = $value->phone;
      $d['country'] = $value->country;
      $d['country_id'] = $value->country_id;
      $d['state'] = $value->state;
      $d['state_id'] = $value->state_id;
      $d['city'] = $value->city;
      $d['city_id'] = $value->city_id;
      $d['zipcode'] = $value->zipcode;
      $d['address'] = $value->address;
      return $d;
    });
    $message = 'Addresses list.';
    return response()->json(['success'=>true, 'message' => $message, 'data'=>$data], 200);
  }

  public function addressesSave(Request $request){
    $user = $request->user();
    $user_type = 'user';
    $this->validate($request, [
      'id'=>'required',
      'name'=>'required',
      'email' => 'required|email|max:255',
      'phone' => 'required|digits:10|regex:/^[1-9]\d{9}$/',
      'country_id' => 'required|gt:0',
      'state_id' => 'required|gt:0',
      'city_id' => 'required|gt:0',
      'zipcode' => 'required|digits:6',
      'address' => 'required',
    ], [
      'name.required'=>'Full Name field is required.',
      'email.required'=>'Email field is required.',
      'phone.required'=>'Phone field is required.',
      'country_id.required'=>'Country field is required.',
      'state_id.required'=>'State field is required.',
      'city_id.required'=>'City field is required.',
      'zipcode.required'=>'Zipcode field is required.',
      'address.required'=>'Address field is required.',
    ]);

    $id = $request->id;
    $country = DB::table('master_countries')->where('id', $request->country_id)->first();
    $state = DB::table('master_states')->where('id', $request->state_id)->first();
    $city = DB::table('master_cities')->where('id', $request->city_id)->first();
    $shippingAddress = ShippingAddress::find($id);
    $message = 'Edit Successfully.';
    if(!$shippingAddress){
      $message = 'Add Successfully.';
      $shippingAddress = new ShippingAddress();
    }
    $shippingAddress->user_id = $user->id;
    $shippingAddress->user_type = $user_type;
    $shippingAddress->name = $request->name;
    $shippingAddress->phone = $request->phone;
    $shippingAddress->email = $request->email;
    $shippingAddress->country_id = $country ? $country->id : $request->country_id;
    $shippingAddress->country = $country ? $country->name : '';
    $shippingAddress->state_id = $state ? $state->id : $request->state_id;
    $shippingAddress->state = $state ? $state->name : '';
    $shippingAddress->city_id = $city ? $city->id : $request->city_id;
    $shippingAddress->city = $city ? $city->name : '';
    $shippingAddress->address = $request->address;
    $shippingAddress->zipcode = $request->zipcode;
    $shippingAddress->save();
    return response()->json(['success'=>true, 'message' => $message], 200);
  }

  public function addressesDelete(Request $request){
    $user = $request->user();
    $user_type = 'user';
    $this->validate($request, [
      'id'=>'required|gt:0',
    ]);
    $id = $request->id;
    $address = ShippingAddress::find($id);
    $address->delete();
    $message = 'Deleted Successfully.';
    return response()->json(['success'=>true, 'message' => $message], 200);
  }

//   public function allPackagesData(Request $request){

//     $user = $request->user();
//     $ids = UserRechargeHistory::where('user_id', $user->id)->groupBy('recharge_plan_id')->pluck('recharge_plan_id')->toArray();
//     $plans1 = RechargePlan::where('status', 1)->orderBy('amount', 'asc')->where('one_time', 0)->get();
//     $plans2 = RechargePlan::where('status', 1)->orderBy('amount', 'asc')->where('one_time', 1)->whereNotIn('id', $ids)->get();
//     $plans = $plans1->merge($plans2);
//     $plans = $plans->sortBy('amount')->values();
//     $data = $plans->map(function($plan){
//       $d['id'] = $plan->id;
//       $d['name'] = $plan->name;
//       $d['amount'] = $plan->amount;
//       $d['extra'] = 0;
//       $d['get_amount'] = $plan->amount;
//       if($plan->talk_amount > 0 && $plan->from_date <= date('Y-m-d') && $plan->to_date >= date('Y-m-d')){
//         $d['extra'] = (($plan->talk_amount-$plan->amount)/$plan->amount)*100;
//         $d['get_amount'] = $plan->talk_amount;
//       }
//       $d['gst_percentage'] = 18;
//       $d['gst_amount'] = number_format(($plan->amount*0.18), 2);
//       $d['gst_amount'] = round($d['gst_amount'], 1);
//       $d['total'] = number_format(($plan->amount+($plan->amount*0.18)), 2);
//       return $d;
//     });
//     return response()->json(['success'=>true, 'data'=>$data], 200);
//   }
public function allPackagesData(Request $request)
{
    $user = $request->user();

    $purchasedPlanIds = UserRechargeHistory::where('user_id', $user->id)
        ->whereNotNull('recharge_plan_id')
        ->distinct()
        ->pluck('recharge_plan_id');

    $plans = RechargePlan::where('status', 1)
        ->where(function ($query) use ($purchasedPlanIds) {
            $query->where('one_time', 0)
                ->orWhere(function ($query) use ($purchasedPlanIds) {
                    $query->where('one_time', 1)
                        ->whereNotIn('id', $purchasedPlanIds);
                });
        })
        ->orderBy('amount', 'asc')
        ->get();

    $today = now()->toDateString();

    $data = $plans->map(function ($plan) use ($today) {

        $amount = (float) $plan->amount;
        $getAmount = $amount;
        $extra = 0;

        // Promotional / bonus amount
        if (
            (float) $plan->talk_amount > 0 &&
            $plan->from_date <= $today &&
            $plan->to_date >= $today
        ) {
            $talkAmount = (float) $plan->talk_amount;

            $extra = (($talkAmount - $amount) / $amount) * 100;
            $getAmount = $talkAmount;
        }

        // GST
        $gstPercentage = 18;
        $gstAmount = round($amount * ($gstPercentage / 100), 2);
        $total = round($amount + $gstAmount, 2);

        return [
            'id' => $plan->id,
            'name' => $plan->name,
            'amount' => $amount,
            'extra' => round($extra, 2),
            'get_amount' => $getAmount,
            'gst_percentage' => $gstPercentage,
            'gst_amount' => number_format($gstAmount, 2, '.', ''),
            'total' => number_format($total, 2, '.', ''),
        ];
    })->values();

    return response()->json([
        'success' => true,
        'data' => $data,
    ], 200);
}

  public function makeRecharge(Request $request){
    $this->validate($request, [
      'plan_id'=>'required|gt:0'
    ]);
    $plan_id = $request->plan_id;
    $plan = RechargePlan::find($plan_id);
    $amount = $plan->amount;
    $currentDate = date('Y-m-d');
    $discount = 0;
    $user = $request->user();
    $user_type = 'user';
    $couponCode = $request->coupon_code?:'';
    $couponAmount = 0;
    $couponId = 0;
    $couponApplyOn = '';
    if($couponCode){
      $newRequest = new Request([
        'callType'=>'not_ajax',
        'page'=>'recharge',
        'coupon_code'=>$couponCode,
        'plan_id'=>$plan_id,
        'user_id'=>$user->id,
      ]);
      // $newRequest->headers->set('Authorization', 'Bearer '.$user->api_token);
      $couponController = new CouponController();
      $couponData = $couponController->apply2($newRequest);
      $couponData = $couponData->getData();
      $couponAmount = $couponData->discountAmount?:0;
      $coupon = $couponData->coupon?:null;
      $couponApplyOn = $coupon?$coupon->apply_on:'';
      $couponCode = $coupon?$coupon->code:'';
      $couponId = $coupon?$coupon->id:0;
    }
    $discount = abs($couponAmount-$discount);
    $table = new UserRechargeHistory();
    $uid = str_random(20);
    $table->user_id = $user->id;
    $table->user_type = $user_type;
    $table->recharge_plan_id = $plan->id;
    $table->amount = $amount;
    $table->item_name = $plan->name;
    $table->item_description = 'Talk Amount - '.$plan->talk_amount;
    $table->payment_method = 'razorpay';
    $table->payment_txn_id = '';
    $table->payment_status = 'pending';
    $table->payment_request = '';
    $table->payment_response = '';
    $table->coupon_id = $couponId;
    $table->coupon_code = $couponCode;
    $table->coupon_amount = $couponAmount;
    $table->tax = 'GST';
    $country = DB::table('master_countries')->where('id', $user->country_id)->first();
    $state = DB::table('master_states')->where('id', $user->state_id)->first();
    $city = DB::table('master_cities')->where('id', $user->city_id)->first();
    $amount = $amount-$discount;

    if($state && (strtolower($state->name) == 'gujarat')){
      $table->tax_type = 'cgst-sgst';
      $table->cgst = '9';
      $table->cgst_amount = ($amount)*0.09;
      $table->sgst = '9';
      $table->sgst_amount = ($amount)*0.09;
    }else{
      $table->tax_type = 'igst';
      $table->igst = '18';
      $table->igst_amount = ($amount)*0.18;
    }
    $table->coupon_code = $couponCode;
    $table->discount = $discount;
    $table->total_amount = $amount+($amount*0.18);
    $table->taktime_value = $plan->amount;
    if($plan->talk_amount > 0 && $plan->from_date <= $currentDate  && $plan->to_date >= $currentDate){
      $table->taktime_value = $plan->talk_amount;
    }
    $table->loyality_points = 0;
    $table->customer_name = $user->name;
    $table->customer_email = $user->email;
    $table->phone = $user->phone;
    $table->address = $user->address ?: '';
    $table->city = $city ? $city->name : '';
    $table->state = $state ? $state->name : '';
    $table->country = $country ? $country->name : '';
    $table->pincode = $user->pincode ?: '';
    $table->billing_full_name = $user->name;
    $table->billing_email = $user->email;
    $table->billing_phone_number = $user->phone;
    $table->billing_address = $user->address ?: '';
    $table->billing_city = $city ? $city->name : '';
    $table->billing_state = $state ? $state->name : '';
    $table->billing_country = $country ? $country->name : '';
    $table->billing_zipcode = $user->pincode ?: '';
    $table->hsn_code = fromSetting('recharge_hsn');
    $maxInvoiceId = UserRechargeHistory::max('invoice_id');
    $maxInvoiceId = $maxInvoiceId ? $maxInvoiceId+1 : '202500001';
    $table->invoice_id = $maxInvoiceId;
    $table->save();
    $rechargePrefix = 'REC-ADV-'.date('Ym').'-';
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
        'amount' => ceil($table->total_amount*100),
        'currency' => 'INR'
    ];
    if($paymentRequest['amount']>0){
      $razorpay_api_key = fromSetting("razorpay_api_key");
      $razorpay_api_secret_key = fromSetting("razorpay_api_secret_key");
      $api = new Api($razorpay_api_key, $razorpay_api_secret_key);
      $rechargeData  = $api->order->create($paymentRequest);
      $paymentRequestData = [
          "key" => $razorpay_api_key,
          "amount" => $paymentRequest['amount'],
          "currency" => "INR",
          "name"=> config('app.name'),
          "library_name" => config('app.name'),
          "image" => asset('assets/img/logo.png'),
          'description' =>$table->item_description,
          "order_id" => $rechargeData ? $rechargeData['id'] : 0,
          "callback_url" => route('api.user.verify.recharge.payment', [$table->id, 'razorpay']),
          "prefill"=>[
            "name"=>$user->name,
            "email"=>$user->email,
            "contact"=>$user->phone,
          ],
          "theme"=>[
            "color"=>"#528FF0",
          ],
      ];
      $table->payment_request = array_merge($paymentRequest, $paymentRequestData);
      $table->save();
      return response()->json(['success'=>true, 'message'=> 'Please make payment', 'status'=>'payment', 'data'=>$paymentRequestData], 200);
    }else{
      $table->payment_id = '';
      $table->payment_response = 'zero amount';
      $table->payment_status = 'success';
      $table->payment_method = 'nothing';
      $table->payment_date_time = date('Y-m-d H:i:s');
      $table->balance = $user->wallet+$table->taktime_value;
      $table->save();

      // add amount to user wallet
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

      $data['recharge_number'] = $table->my_txn_id;
      $message = 'Recharge Successfully. Recharge Number is '.$table->my_txn_id;
      return response()->json(['success'=>true, 'message'=> $message, 'status'=>'placed', 'data'=>$data ], 200);
    }
  }

  public function paymentResponse(Request $request){
    $this->validate($request, [
      'payment_response' => 'required',
    ]);
    $payment_type = $request->payment_type;
    $user_recharge_id = $request->user_recharge_id;
    $payment_response = $request->payment_response;
    $success = true;
    $error = "Payment Failed!";
    $rechargeResponse = [];
    $table = UserRechargeHistory::find($user_recharge_id);
    $user = User::find($table->user_id);
    $user_type = 'user';
    if($payment_type == 'razorpay'){
      if(empty($payment_response['paymentId']) === false){
        $razorpay_api_key = fromSetting("razorpay_api_key");
        $razorpay_api_secret_key = fromSetting("razorpay_api_secret_key");
        $api = new Api($razorpay_api_key, $razorpay_api_secret_key);
        try{
          $attributes=[
            'razorpay_order_id' => $payment_response['orderId'],
            'razorpay_payment_id' => $payment_response['paymentId'],
            'razorpay_signature' => $payment_response['signature']
          ];
          $api->utility->verifyPaymentSignature($attributes);
          $rechargeResponse = $api->payment->fetch($payment_response['paymentId']);
          $payment_response = array_merge($payment_response, $rechargeResponse->toArray());
          $table->payment_status = 'success';
          $table->payment_id = isset($payment_response['paymentId'])?$payment_response['paymentId']:'';
          $table->payment_method = isset($payment_response['method'])?$payment_response['method']:'Online';
          $table->payment_date_time = isset($payment_response['created_at'])?date('Y-m-d H:i:s', $payment_response['created_at']):date('Y-m-d H:i:s');
        }catch(SignatureVerificationError $e){
          $success = false;
          $error = 'Razorpay Error : ' . $e->getMessage();
          $table->payment_status = 'failed';
        }
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
      $invoiceController = new InvoiceController();
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

      $wallet['amount'] = $user->wallet;
      $data['recharge_number'] = $table->my_txn_id;
      $message = 'Recharge Successfully. Recharge Number is '.$table->my_txn_id;
      return response()->json(['success'=>true, 'message'=> $message, 'wallet' => $wallet, 'data' => $data], 200);
    }
    RechargeFailedJob::dispatch($table);
    return response()->json(['success'=>false, 'message'=>'Payment unverified'], 402);
  }

  public function rechargeHistory(Request $request){
    $user = $request->user();
    $user_type = 'user';
    $per_page = 10;
    $page = $request->page?:1;
    $results = UserRechargeHistory::where('user_id', $user->id)->where('user_type', $user_type)->orderBy('id', 'desc')->where('payment_status', 'success')->paginate($per_page);
    $data['list']=[];
    $data['count']=0;
    $data['per_page']=0;
    $data['has_more_pages']=false;
    $data['current_page']=$page;
    $data['previous_page_url']='';
    $data['next_page_url']='';
    $data['last_page_url']='';

    $data['list'] = $results->map(function($value, $index) use($user){
      $d['id'] = $value->id;
      $d['recharge_id'] = $value->my_txn_id;
      $d['date_time'] = date('d-m-Y h:i a', strtotime($value->created_at));
      $d['package'] = $value->item_name;
      $d['mode'] = $value->payment_method;
      $d['debit'] = $value->total_amount;
      $d['credit'] = $value->taktime_value;
      if(invoice('recharge', $value->id)){
        $d['invoice'] = [
          'has' => true,
          'id' => $value->id,
          'page'=>'recharge',
          'url'=>route('user.invoice', ['type' => 'recharge', 'id' => $value->id])
        ];
      }else{
        $d['invoice'] = [
          'has' => false,
          'id' => 0,
          'page'=> '',
          'url'=>''
        ];
      }
      return $d;
    });

    if($results->total()){
      $data['count'] = $results->total();
      $data['per_page'] = $results->perPage();
      $data['has_more_pages'] = $results->hasMorePages();
      $data['current_page'] = $results->currentPage();
      $data['previous_page_url'] = $results->previousPageUrl();
      $data['next_page_url'] = $results->nextPageUrl();
      $data['last_page_url'] = $results->url($results->lastPage());
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function orders(Request $request){
    $user = $request->user();
    $user_type = 'user';
    $data = [];
    $not_in = ['pending'];
    $orders = Order::where('user_id', $user->id)->where('user_type',$user_type)->orderBy('id', 'DESC')->whereNotIn('order_status', $not_in)->get();
    foreach ($orders as $key => $value){
      $item['order_id'] = $value->id;
      $item['order_number'] = $value->order_number;
      $item['date'] = date('d-m-Y', strtotime($value->created_at));
      $item['total_price'] = $value->total_price;
      $item['status'] = $value->order_status == "delivered" ? "Completed" : ucwords($value->order_status);
      $item['show_shipping_address'] = $value->item_type == 0 ? true : false;

      $orderItem = OrderItem::where('order_id', $value->id)->orderBy('id', 'asc')->first();
      $item['item'] = [];
      $product = $orderItem ? $orderItem->product : null;
      if($product){
        $item['item']['parent_id'] = $product->parent_id ?: $product->id;
        $item['item']['item_id'] = $product->id;
        $item['item']['slug'] = $product->slug;
        $item['item']['image'] = $orderItem->image;
        $item['item']['name'] = $product->title;
        $item['item']['price'] = $orderItem->price;
        if($orderItem->quantity > 1){
          $item['item']['price'] = $orderItem->price.' x '.$orderItem->quantity;
        }
      }
      array_push($data, $item);
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function orderItems(Request $request){
    $this->validate($request, [
      'order_id'=>'required|gt:0',
    ]);
    $order_id = $request->order_id;
    $user = $request->user();
    $user_type = 'user';
    $order = Order::find($order_id);
    $data = [
      'order_id'=>$order->id,
      'date'=>date('d-m-Y', strtotime($order->created_at)),
      'order_number'=>$order->order_number,
      // 'status'=>$order->order_status == "delivered" ? "completed" : $order->order_status,
      'status'=>$order->order_status,
      'payment_method'=>$order->payment_method,
      'cancel'=>[],
      'return'=>[],
      'billing_address'=>[
        'name'=>$order->billing_full_name,
        'address'=>$order->billing_address,
        'country'=>$order->billing_country,
        'state'=>$order->billing_state,
        'city'=>$order->billing_city,
        'zipcode'=>$order->billing_zipcode,
      ],
      'shipping_address'=>[
        'name'=>$order->full_name,
        'address'=>$order->address,
        'country'=>$order->country,
        'state'=>$order->state,
        'city'=>$order->city,
        'zipcode'=>$order->zipcode,
      ],
      'sub_total'=> sprintf('%.2f', $order->sub_total - $order->discount_amount),
      // 'discount'=> sprintf('%.2f', $order->discount_amount+$order->coupon_amount),
      'discount'=> sprintf('%.2f', $order->coupon_amount),
      'gst_taxes'=> sprintf('%.2f', $order->igst + $order->cgst + $order->sgst),
      'shipping_price'=> $order->shipping_price,
      'total_price'=> $order->total_price,
      'can_review'=> $order->order_status == 'delivered' ? true : false,
      'invoice'=>[],
      'track'=>[],
      'items'=>[],
    ];
    $orderRequestStatus = ["requested", "accepted", "rejected"];
    $orderCancelRequests = OrderRequest::where("order_id", $order_id)->where("request_type", "cancel")->first();
    $data["cancel"]["can"] = false;
    if($orderCancelRequests){
      $data["cancel"]["request"] = true;
      $data["cancel"]["reason"] = $orderCancelRequests->reason ?: "";
      $data["cancel"]["message"] = $orderCancelRequests->message ?: "";
      $data["cancel"]["status"] = $orderRequestStatus[$orderCancelRequests->status];
    }else{
      $data["cancel"]["request"] = false;
      $data["cancel"]["reason"] = "";
      $data["cancel"]["message"] = "";
      $data["cancel"]["status"] = "";
    }
    if($order->order_status == "ordered" && $data["cancel"]["request"] == false){
      $data["cancel"]["can"] = true;
    }

    $orderReturnRequests = OrderRequest::where("order_id", $order_id)->where("request_type", "return")->first();
    $data["return"]["can"] = false;
    if($orderReturnRequests){
      $data["return"]["request"] = true;
      $data["return"]["reason"] = $orderReturnRequests->reason ?: "";
      $data["return"]["message"] = $orderReturnRequests->message ?: "";
      $data["return"]["status"] = $orderRequestStatus[$orderReturnRequests->status];
    }else{
      $data["return"]["request"] = false;
      $data["return"]["reason"] = "";
      $data["return"]["message"] = "";
      $data["return"]["status"] = "";
    }
    if($data["return"]["request"]==true){
      $data["cancel"]["request"] = false;
    }

    $delivery_date = time()+(7*24*60*60);
    if($order->delivery_date){
      $delivery_date = strtotime($order->delivery_date)+(7*24*60*60);
    }

    if($order->order_status == "delivered" && time() < $delivery_date && $data["return"]["request"] == false){
      $data["return"]["can"] = true;
    }
    if(invoice('order', $order->id)){
      $data['invoice'] = [
        'has' => true,
        'id' => $order->id,
        'page'=>'order',
        'url'=>route('user.invoice', ['type' => 'order', 'id' => $order->id])
      ];
    }else{
      $data['invoice'] = [
        'has' => false,
        'id' => 0,
        'page'=> '',
        'url'=>''
      ];
    }
    $orderStatus = OrderStatus::where('order_id', $order_id)->get();
    foreach ($orderStatus as $index => $itemm){
      $data["track"][] = [
        "status" => $itemm->status,
        "created_at" => date("d-m-Y h:i a", strtotime($itemm->created_at)),
      ];
    }

    $orderItems = OrderItem::where('order_id', $order_id)->get();
    $formdata=[];

    $productsId = $orderItems->pluck("product_id")->toArray();
    $productReviews = ProductReview::where('order_id', $order->id)->where('review_by', $user->id)->whereIn("product_id", $productsId)->get();

    foreach ($orderItems as $key => $value){
      $product = $value ? $value->product : null;
      if($product){
        $item['item']['parent_id'] = $product->parent_id ?: $product->id;
        $item['item']['item_id'] = $product->id;
        $item['item']['slug'] = $product->slug;
        $item['item']['image'] = $value->image;
        $item['item']['name'] = $product->title;
        $item['item']['price'] = $value->price;
        if($value->quantity > 1){
          $item['item']['price'] = $value->price.' x '.$value->quantity;
        }
        $item['item']['review'] = false;

        $productReviewsAll = $productReviews;
        $review = $productReviewsAll->where("product_id", $product->id)->first();

        if($order->order_status == "delivered" && $review == null){
          $item['item']['review'] = true;
        }
      }
      if($value->options && count($formdata)==0){
        $formdata=json_decode($value->options,true);
      }
      array_push($data['items'], $item);
    }
    $data['formdata']=$formdata;
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function orderCancelRequestReasons(Request $request){
    $data = [];
    foreach (orderRequestReason() as $key => $value){
      $data[] = [
        "name" => $value,
        "value" => $key
      ];
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function orderReturnRequestReasons(Request $request){
    $data = [];
    foreach (orderRequestReturnReason() as $key => $value){
      $data[] = [
        "name" => $value,
        "value" => $key
      ];
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function orderRequest(Request $request){
    $request->validate([
      'request_type'=>'required|in:cancel,return',
      'order_id'=>'required|exists:orders,id',
      'reason'=>'required|gt:0',
    ]);
    $request_type = $request->request_type;
    if($request_type=='return'){
      $this->validate($request, [
        'item_ids'=>'required|array',
      ]);
    }
    $reason = "";
    if($request_type=='cancel'){
      $reason = orderRequestReason($request->reason);
    }else{
      $reason = orderRequestReturnReason($request->reason);
    }
    if(is_array($reason) || $reason == ""){
      return response()->json(['success'=>false, 'message'=>'Invalid reason.'], 422);
    }
    if($reason=='Other'){
      $this->validate($request, [
        'message'=>'required',
      ]);
    }
    $order_id = $request->order_id;
    $user = $request->user();
    $user_type = 'user';
    $order_request = OrderRequest::where('order_id', $order_id)->where("request_type", $request_type)->first();
    if($order_request){
      return response()->json(['success'=>false, 'message'=>'Please Try again later.'], 422);
    }
    $order = Order::where("user_id", $user->id)->where("id", $order_id)->first();
    if(!$order){
      return response()->json(['success'=>false, 'message'=>'Order not found.'], 422);
    }
    $order_request = new OrderRequest();
    $order_request->order_id = $order_id;
    $order_request->user_id = $user->id;
    $order_request->request_type = $request_type;
    $order_request->reason = $reason;
    $order_request->message = $request->message;
    $order_request->status = 0;
    $order_request->save();
    if($request_type=='return'){
      foreach ($request->item_ids as $key => $value){
        $order_request_item = new OrderRequestItem();
        $order_request_item->request_id = $order_request->id;
        $order_request_item->order_id = $order_id;
        $order_request_item->user_id = $user->id;
        $order_request_item->item_id = $value;
        $order_request_item->save();
      }
    }
    OrderRequestJob::dispatch(config("vrdcms.admin_email"), $order, $order_request, null, $request_type, 0);
    return response()->json(['success'=>true, 'message'=>'Request Submit successfully'], 200);
  }

  public function joinLiveAstrologer(Request $request){
    $user = $request->user();
    $request->validate([
      "live_id"=>"required|integer|exists:astrologer_lives,id"
    ]);
    $astrologer_live_id = $request->live_id;
    $astrologerLive = Astrologerlive::find($astrologer_live_id);
    $astrologer = Astrologer::find($astrologerLive->astrologer_id);

    $data['user_id'] = $user->id;
    $data['user_name'] = $user->name;
    $data['logo'] = $astrologerLive->logo;
    $data['mode'] = $astrologerLive->mode;
    $data['role'] = "audience";
    $data['app_id'] = $astrologerLive->app_id;
    $data['app_certificate'] = fromSetting("agora_app_certificate");
    $data['room_id'] = $astrologerLive->room_id;
    $data['uid'] = "@".str_replace(" ", "", $user->name).$user->id;
    $data['app_secret'] = $astrologerLive->app_secret;
    $data['app_secret'] = RtcTokenBuilder2::buildTokenWithUid($data['app_id'], $data["app_certificate"], $data["room_id"], $data["user_id"], 2, 86400);
    $data['rtm_token'] = RtmTokenBuilder2::buildToken($data['app_id'], $data["app_certificate"], $data["user_id"], 86400);
    $data['app_sign'] = $astrologerLive->app_sign;
    unset($data['app_certificate']);

    $astrologerLiveUser=AstrologerLiveUser::where('astrologer_live_id', $astrologer_live_id)->first();
    if(!$astrologerLiveUser){
      $astrologerLiveUser=new AstrologerLiveUser();
      $astrologerLiveUser->astrologer_live_id=$astrologer_live_id;
      $astrologerLiveUser->user_id=$user->id;
      $astrologerLiveUser->user_name=$data['user_name'];
      $astrologerLiveUser->start_at=date('Y-m-d H:i:s');
      $astrologerLiveUser->end_at=date('Y-m-d H:i:s');
      $astrologerLiveUser->save();
    }
    $data['astrologer_live_user_id'] = $astrologerLiveUser->id;
    $response['credientials']=$data;

    $item['id'] = $astrologer->id;
    $item['name'] = $astrologer->display_name;
    $takeBooking = 0;
    $bookNow = 0;
    $waitingTime = "";
    $timetables = $astrologer->timetable;
    if($timetables->count()){
      $takeBooking = 1;
    }
    if($astrologer->call==1 || $astrologer->video==1 || $astrologer->chat==1){
      $bookNow = 1;
    }
    $status = 'offline';
    if($astrologer->chat == 3 || $astrologer->call == 3 || $astrologer->video == 3){
      $status = 'bussy';
    }elseif($astrologer->chat == 1 || $astrologer->call == 1 || $astrologer->video == 1){
      $status = 'online';
    }
    $video_status = 'hide';
    if($astrologer->video == 1){
      $video_status = 'online';
    }elseif($astrologer->video == 2){
      $video_status = 'offline';
    }elseif($astrologer->video == 3){
      $video_status = 'bussy';
    }
    $call_status = 'hide';
    if($astrologer->call == 1){
      $call_status = 'online';
    }elseif($astrologer->call == 2){
      $call_status = 'offline';
    }elseif($astrologer->call == 3){
      $call_status = 'bussy';
    }
    $chat_status = 'hide';
    if($astrologer->chat == 1){
      $chat_status = 'online';
    }elseif($astrologer->chat == 2){
      $chat_status = 'offline';
    }elseif($astrologer->chat == 3){
      $chat_status = 'bussy';
    }
    $currentAppointmennt=$astrologer->currentAppointment();
    $withInThirtyMinutes=$astrologer->withInThirtyMinutes();
    if($currentAppointmennt || $withInThirtyMinutes){
      $bookNow = 0;
      $currentAppointment = $withInThirtyMinutes;
      if($currentAppointmennt){
        $currentAppointment=$currentAppointmennt;
      }
      $scheduledTimestamp = strtotime($currentAppointment->scheduled_date . ' ' . $currentAppointment->max_time.'+1minutes');
      $diffInSeconds = $scheduledTimestamp-$currentTime;
      if($diffInSeconds > 0){
        $minutes = floor($diffInSeconds / 60);
        $seconds = $diffInSeconds % 60;
        $waitingTime = "{$minutes}:{$seconds} m";
        $status = 'bussy';
        $video_status = 'bussy';
        $call_status = 'bussy';
        $chat_status = 'bussy';
      }
    }
    $item['status'] = $status;
    $item['call_status'] = $call_status;
    $item['consultation'] = [
      'now' => $bookNow ? true : false,
      'later' => $takeBooking ? true : false,
      'later' => false,
      'wait' => $waitingTime,
    ];
    $response['astrologer']=$item;
    $data["ends_in"]=strtotime($astrologerLive->end_at)-strtotime("now");
    return response()->json(["success"=>true, "data"=>$response],200);
  }

  public function liveStreamUpdate(Request $request){
    $request->validate([
      "astrologer_live_user_id"=>"required|integer|exists:astrologer_live_users,id"
    ]);
    $astrologer_live_user_id=$request->astrologer_live_user_id;
    $status=$request->status;
    $astrologerLiveUser=AstrologerLiveUser::find($astrologer_live_user_id);
    $astrologerLiveUser->end_at=date('Y-m-d H:i:s');
    $astrologerLiveUser->save();
    $astrologerLive=AstrologerLive::find($astrologerLiveUser->astrologer_live_id);
    $data['audience']["total"]=AstrologerLiveUser::where("astrologer_live_id", $astrologer_live_id)->where("end_at", '>', date("Y-m-d H:i:s", strtotime("now-10seconds")))->count();
    $data['status']=$astrologerLive->status;
    return response()->json(['success'=>true, 'message'=>'Status Updated', 'data'=>$data], 200);
  }

  public function astrologerLiveUserChat(Request $request){
    $request->validate([
      "astrologer_live_user_id"=>"required|integer|exists:astrologer_live_users,id",
      "message"=>"required",
    ]);
    $astrologer_live_user_id=$request->astrologer_live_user_id;
    $message=$request->message;
    $status=$request->status;
    $astrologerLiveUser=AstrologerLiveUser::find($astrologer_live_user_id);
    $astrologerLive=AstrologerLive::find($astrologerLiveUser->astrologer_live_id);
    $astrologerLiveUserChat=new AstrologerLiveUserChat();
    $astrologerLiveUserChat->astrologer_live_id=$astrologerLiveUser->astrologer_live_id;
    $astrologerLiveUserChat->astrologer_live_user_id=$astrologerLiveUser->id;
    $astrologerLiveUserChat->astrologer_id=$astrologerLive->astrologer_id;
    $astrologerLiveUserChat->user_id=$astrologerLiveUser->user_id;
    $astrologerLiveUserChat->message=$message;
    $astrologerLiveUserChat->save();
    return response()->json(['success'=>true, 'message'=>'Message saved successfully',], 200);
  }

  public function lms(Request $request){
    $user = $request->user();
    $user_type = 'user';
    $lms = LmsCourse::where('user_id', $user->id)->where('user_type', $user_type)->where('status', 1)->where('expire_date', '>=', date('Y-m-d'))->get();
    $data = [];
    foreach ($lms as $key => $value){
      $course = $value->course;
      if($course && $course->status){
        $item['image'] = $course->image ? $course->image->image(200,200) : no_image();
        $item['name'] = $course->name;
        $item['slug'] = $course->slug;
        $item['price'] = number_format($value->amount, 2);
        $item['can_explore'] = count($course->modules) ? true : false;
      }
      array_push($data, $item);
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function exploreCourse(Request $request){
    $this->validate($request, [
      'course_slug'=>'required',
    ]);
    $showStatus = true;
    $user = $request->user();
    $user_type = 'user';
    $data = [];
    $course = Course::where('slug', $request->course_slug)->first();
    if(!$course){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    $first = null;
    $module = null;
    $content = null;
    if($course->modules && isset($course->modules[0])){
      $module = $course->modules[0];
      if(isset($module->content[0])){
        $content = $module->content[0];
        $first = $module->content[0]->videos;
      }
    }
    if(count($course->modules)==0){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    $data['name'] = $course->name;
    $data['modules'] = [
      'total'=> count($course->modules),
      // 'total_topics'=> $course->topics->count(),
      'chapters'=>[]
    ];
    foreach ($course->modules as $mKey => $value){
      $m['name'] = $value->name;
      $contents = $value->content;
      if(count($contents)){
        $m['total'] = count($contents);
        $m['contents'] = [
          // 'total'=> count($contents),
          'topics' => []
        ];
        foreach ($contents as $cKey => $content){
          $c['name'] = $content->title;
          $c['file_url'] = null;
          $file = $content->files;
          if($file){
            $c['file_url'] = asset($file->file);
          }
          $video = $content->videos;
          if($video){
            if($mKey == 0 && $cKey == 0){
              $data['first'] = [];
              $data['first']['video_type'] = $video->type;
              $data['first']['video_id'] = $video->video_id;
              $data['first']['video_hash'] = $video->video_hash;
            }
            $c['video_type'] = $video->type;
            $c['video_id'] = $video->video_id;
            $c['video_hash'] = $video->video_hash;
            if($video->type == 'vimeo'){
              if($mKey == 0 && $cKey == 0){
                $data['first']['video_url'] = 'https://player.vimeo.com/video/'.$video->video_id.'?h='.$video->video_hash;
              }
              $c['video_url'] = 'https://player.vimeo.com/video/'.$video->video_id.'?h='.$video->video_hash;
            }elseif($video->type == 'youtube'){
              if($mKey == 0 && $cKey == 0){
                $data['first']['video_url'] = 'https://www.youtube.com/embed/'.$video->video_id;
              }
              $c['video_url'] = 'https://www.youtube.com/embed/'.$video->video_id;
            }
          }
          array_push($m['contents']['topics'], $c);
        }
        array_push($data['modules']['chapters'], $m);
      }
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function assignments(Request $request){
    $this->validate($request, [
      'course_slug'=>'required',
    ]);
    $type = 'assignment';
    $user = $request->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $passed = 0;
    $failed = 0;
    $course_assignment = 0;
    $pending_review = 0;
    $data = [
      'passed'=>$passed,
      'failed'=>$failed,
      'course_assignment'=>$course_assignment,
      'pending_review'=>$pending_review,
      'list'=>[]
    ];
    $course = Course::where('slug', $course_slug)->first();
    if(!$course){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    $modules = CourseModule::where('course_id', $course->id)->get();
    foreach ($modules as $module_id => $module){
      $item['total_questions'] = CourseModuleQuestion::where('module_id', $module->id)->where('show_in', $type)->count();
      if($item['total_questions']){
        $answers = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('module_id', $module->id)->where('show_in', $type);
        $item['type'] = $type;
        $item['module_id'] = $module->id;
        $item['module_name'] = $module->name;
        $item['posted_date'] = date('d-m-Y', strtotime($module->created_at));
        $item['updated_date'] = date('d-m-Y', strtotime($module->updated_at));
        $item['result'] =  $answers->count() == $item['total_questions'] ? true : false;
        $item['result_url'] = $item['result'] ? route('result.webview', [$user->api_token, $module->id, 'assignment']) : ''; //define in web.php
        $item['attempted_questions'] = $answers->count();
        $item['marks'] = (int) $answers->sum('marks');
        $item['total_marks'] = $item['total_questions']*$module->minus_marks;
        $passingMarks = (($item['total_marks'])/100)*33;
        $item['passing_marks'] = $passingMarks;
        if($item['attempted_questions'] == $item['total_questions']){
          if($item['marks'] >= $item['passing_marks']){
            $passed++;
          }else{
            $failed++;
          }
        }
        array_push($data['list'], $item);
      }
      $item = [];
    }
    $data['passed']=$passed.' Passed';
    $data['failed']=$failed.' Failed';
    $data['course_assignment']=count($data['list']).' Course Assignments';
    $data['pending_review']=$pending_review.' Pending Review';
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function quizzes(Request $request){
    $this->validate($request, [
      'course_slug'=>'required',
    ]);
    $type = 'quizz';
    $user = $request->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $passed = 0;
    $failed = 0;
    $course_assignment = 0;
    $pending_review = 0;
    $data = [
      'passed'=>0,
      'failed'=>0,
      'course_assignment'=>$course_assignment,
      'pending_review'=>$pending_review,
      'list'=>[]
    ];
    $course = Course::where('slug', $course_slug)->first();
    if(!$course){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    $modules = CourseModule::where('course_id', $course->id)->get();
    foreach ($modules as $module_id => $module) {
      // $item['type'] = $type;
      $item['total_questions'] = CourseModuleQuestion::where('module_id', $module->id)->where('show_in', $type)->count();
      if($item['total_questions']){
        $answers = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('module_id', $module->id)->where('show_in', $type);
        $item['module_id'] = $module->id;
        $item['module_name'] = $module->name;
        $item['posted_date'] = date('d-m-Y', strtotime($module->created_at));
        $item['updated_date'] = date('d-m-Y', strtotime($module->updated_at));
        $item['result'] =  $answers->count() == $item['total_questions'] ? true : false;
        $item['result_url'] = $item['result'] ? route('result.webview', [$user->api_token, $module->id, 'quizz']) : ''; //define in web.php
        $item['attempted_questions'] = $answers->count();
        $item['marks'] = (int) $answers->sum('marks');
        if($item['attempted_questions'] == $item['total_questions']){
          $mrks = $answers->sum('marks');
          $passingMarks = (($item['total_questions']*$module->minus_marks)/100)*33;
          if($mrks >= $passingMarks){
            $passed++;
          }else{
            $failed++;
          }
        }
        array_push($data['list'], $item);
      }
    }
    $data['passed']=$passed.' Passed';
    $data['failed']=$failed.' Failed';
    $data['course_assignment']=count($data['list']).' Course Assignments';
    $data['pending_review']=$pending_review.' Pending Review';
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function firstQuestions(Request $request){
    $this->validate($request, [
      'module_id'=>'required',
      'type'=>'required', //assignment, quizz
    ]);
    $user = $request->user();
    $user_type = 'user';
    $type = $request->type;
    $module_id = $request->module_id;
    $question_id = 0;
    $question_ids = [];
    $question_id_index = 0;
    $data = [
      'name'=>'',
      'description'=>'',
      'question_id'=> 0,
      'total_question'=> 0,
      'mark_each'=> 0,
      'min_marks'=> 0,
      'total_marks'=> 0,
    ];
    $module = CourseModule::find($module_id);
    if(!$module){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    $q_ids = CourseModuleQuestion::where('module_id', $module_id)->where('show_in', $type)->orderBy('order', 'asc')->pluck('id')->toArray();
    if(count($q_ids)==0){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    if($type == 'assignment'){
      $question_id = $q_ids[0];
    }else{
      $aQ_ids = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('module_id', $module_id)->where('show_in', $type)->pluck('question_id')->toArray();
      foreach ($q_ids as $key => $id) {
        if(!in_array($id, $aQ_ids)){
          array_push($question_ids, $id);
        }
      }
      if(count($question_ids)){
        $question_id = $question_ids[0];
      }
    }
    $data['name'] = $module->name;
    $data['description'] = $module->details;
    $data['question_id'] = $question_id;
    $data['total_question'] = (int) count($q_ids);
    $data['mark_each'] = $module->minus_marks;
    $data['total_marks'] = $data['total_question']*$data['mark_each'];
    $data['min_marks'] = ceil(($data['total_marks']/100)*40);
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function fetchQuestion(Request $request){
    $this->validate($request, [
      'question_id'=>'required',
    ]);
    $user = $request->user();
    $user_type = 'user';
    $question_id = $request->question_id;
    $data = [
      'question'=> [],
      'given_answer'=> 0,
      'previous_question_id'=> 0,
      'current_question_id'=> (int) $question_id,
      'next_question_id'=> 0,
      'is_last'=>false
    ];
    $question = CourseModuleQuestion::find($question_id);
    if(!$question){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    $type = $question->show_in;
    $q_ids = CourseModuleQuestion::where('module_id', $question->module_id)->where('show_in', $question->show_in)->orderBy('order', 'asc')->pluck('id')->toArray();
    $question_id_index = array_search($question_id, $q_ids);
    if($question->show_in == 'quizz'){
      $aQ_ids = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('module_id', $question->module_id)->where('show_in', $type)->pluck('question_id')->toArray();
      $question_ids = [];
      foreach ($q_ids as $key => $id) {
        if(!in_array($id, $aQ_ids)){
          array_push($question_ids, $id);
        }
      }
      $question_id_index = array_search($question_id, $question_ids);
      $data['previous_question_id'] = (int) isset($question_ids[$question_id_index-1]) ? $question_ids[$question_id_index-1] : 0;
      $data['next_question_id'] = (int) isset($question_ids[$question_id_index+1]) ? $question_ids[$question_id_index+1] : 0;
      $data['is_last'] = $question_id == isset($question_ids[count($q_ids)-1]) && $question_id == $question_ids[count($q_ids)-1] ? true : false;
    }else{
      $data['previous_question_id'] = (int) isset($q_ids[$question_id_index-1]) ? $q_ids[$question_id_index-1] : 0;
      $data['next_question_id'] = (int) isset($q_ids[$question_id_index+1]) ? $q_ids[$question_id_index+1] : 0;
      $data['is_last'] = $question_id == isset($q_ids[count($q_ids)-1]) && $question_id == $q_ids[count($q_ids)-1] ? true : false;
    }
    $question_id_index = array_search($question_id, $q_ids);
    $data['question']['number'] = $question_id_index+1;
    $data['question']['question'] = $question->question;
    $data['question']['options'] = [];
    array_push($data['question']['options'], $question->option_a);
    array_push($data['question']['options'], $question->option_b);
    array_push($data['question']['options'], $question->option_c);
    array_push($data['question']['options'], $question->option_d);
    $attemptedQuestion = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('question_id', $question_id)->first();
    if($attemptedQuestion){
      $data['given_answer'] = $attemptedQuestion->answer;
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function submitQuestion(Request $request){
    $this->validate($request, [
      'question_id'=>'required',
      'answer'=>'required',
    ]);
    $user = $request->user();
    $user_type = 'user';
    $question_id = $request->question_id;
    $answer = $request->answer;
    $data = [
      'is_last'=> false,
    ];
    $question = CourseModuleQuestion::find($question_id);
    if(!$question){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    $type = $question->show_in;
    $assignmentAnswer = AssignmentAnswer::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $question->course_id)->where('module_id', $question->module_id)->where('content_id', $question->content_id)->where('question_id', $question->id)->where('show_in', $type)->first();
    $moudlee = CourseModule::find($question->module_id);
    if(!$assignmentAnswer){
      $assignmentAnswer = new AssignmentAnswer();
      $assignmentAnswer->user_id = $user->id;
      $assignmentAnswer->user_type = $user_type;
      $assignmentAnswer->course_id = $question->course_id;
      $assignmentAnswer->module_id = $question->module_id;
      $assignmentAnswer->content_id = $question->content_id;
      $assignmentAnswer->question_id = $question->id;
    }
    $assignmentAnswer->answer = $answer;
    $assignmentAnswer->show_in = $type;
    $assignmentAnswer->marks = 0;
    if($answer == $question->answer){
      $assignmentAnswer->marks = $moudlee->minus_marks;
    }
    $assignmentAnswer->save();
    $q_ids = CourseModuleQuestion::where('module_id', $question->module_id)->where('show_in', $type)->orderBy('order', 'asc')->pluck('id')->toArray();
    if(end($q_ids) == $question_id){
      $data['is_last'] = true;
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function resultWebView(Request $request){
    //in controller/AccountController.php
  }

  public function meetings(Request $request){
    $this->validate($request, [
      'course_slug'=>'required',
    ]);
    $user = $request->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $data = [
      'course_name'=>'',
      'instructor'=>'',
      'duration'=>'',
      'active_inactive'=>'',
      'list'=>[]
    ];
    $course = Course::where('slug', $course_slug)->first();
    if($course){
        $item['user_name'] = $user->name;
        $item['date'] = date('d-m-Y', strtotime($course->meet_date));
        $item['start_time'] = date('h:i a', strtotime($course->meet_start_time));
        $item['end_time'] = date('h:i a', strtotime($course->meet_end_time));
        $item['time_slot'] = $item['start_time'].' - '.$item['end_time'];
        $item['link'] = $course->meet_link;
        $item['platform'] = $course->meet_platform;
        $item['instructor'] = $course->instructor ? $course->instructor->display_name : 'Instructor';
        $item['status'] = false;
        if($course->meet_status && $course->meet_link &&  ($course->meet_type == 'permanent' || time() < strtotime($course->meet_date.' '.$course->meet_end_time) )){
          $item['status'] = true;
        }
        array_push($data['list'], $item);
        $data['course_name'] = $course->name;
        $data['instructor'] = $course->instructor ? $course->instructor->display_name : 'Instructor';
        $data['duration'] = abs(strtotime($course->meet_date.' '.$course->meet_start_time) - strtotime($course->meet_date.' '.$course->meet_end_time))/60;
        $data['duration'] = $data['duration'].' Minutes';
        $data['active_inactive'] = $item['status'] ? 'Active' : 'Inactive';
      return response()->json(['success'=>true, 'data'=>$data], 200);
    }
    return response()->json(['success'=>false, 'data'=>$data], 402);
  }

  public function certificate(Request $request){
    $this->validate($request, [
      'course_slug'=>'required'
    ]);
    $user = $request->user();
    $user_type = 'user';
    $course_slug = $request->course_slug;
    $data = [
      'url'=> route('home')
    ];
    $course = Course::where('slug', $course_slug)->first();
    if(!$course){
      return response()->json(['success'=>false, 'data'=>$data], 402);
    }
    $lms = LmsCourse::where('user_id', $user->id)->where('user_type', $user_type)->where('course_id', $course->id)->first();
    if($lms){
      $data['url'] = route('certificate.app', [$user->api_token, $course_slug]); //define in web.php
      return response()->json(['success'=>true, 'data'=>$data], 200);
    }
    return response()->json(['success'=>false, 'data'=>$data], 402);
  }

  public function sendGift(Request $request){
    $this->validate($request, [
      'gift_id'=>'required|integer|exists:gifts,id',
      'astrologer_id'=>'required|integer|exists:astrologers,id',
      'live_id'=>'required|integer|exists:astrologer_lives,id',
    ]);
    $gift_id = $request->gift_id;
    $astrologer_id = $request->astrologer_id;
    $astrologer_live_id = $request->live_id;
    $user = $request->user();
    $gift=Gift::find($gift_id);
    $astrologer=Astrologer::find($astrologer_id);
    if($gift->price > $user->wallet){
      return response()->json(["message"=>"You don't have enought balance. Please recharge", "errors"=>["recharge"=>["You don't have enought balance. Please recharge"]]], 422);
    }

    // send gift
    $sendGift=new SendGift();
    $sendGift->gift_id = $request->gift_id;
    $sendGift->user_id = $user->id;
    $sendGift->astrologer_id = $astrologer->id;
    $sendGift->astrologer_live_id = $astrologer_live_id;
    $sendGift->save();

    // deduct amount from user wallet
    $user->wallet -= $gift->price;
    $user->save();

    // user wallet transition
    $walletTransition = new WalletTransition();
    $walletTransition->user_id = $user->id;
    $walletTransition->user_type = "user";
    $walletTransition->table_id = $sendGift->id;
    $walletTransition->table_name = "send_gifts";
    $walletTransition->action = 0; //debit
    $walletTransition->status = 1; //success
    $walletTransition->remark = "Gift to $astrologer->display_name";
    $walletTransition->invoice = 0;
    $walletTransition->amount = $gift->price;
    $walletTransition->save();

    // add amount to astrologer wallet
    $astrologer->wallet += $gift->price;
    $astrologer->save();

    // astrologer wallet transition
    $walletTransition = new WalletTransition();
    $walletTransition->user_id = $astrologer->id;
    $walletTransition->user_type = "astrologer";
    $walletTransition->table_id = $sendGift->id;
    $walletTransition->table_name = "send_gifts";
    $walletTransition->action = 1; //credit
    $walletTransition->status = 1; //success
    $walletTransition->remark = "Gift from $user->name";
    $walletTransition->invoice = 0;
    $walletTransition->amount = $gift->price;
    $walletTransition->save();

    // return response
    return response()->json(['success'=>true, 'message'=>'Gift send successfully.', 'wallet'=>$user->wallet], 200);
  }

  public function walletTransitionHistory(Request $request){
    $user = $request->user();
    $userType = "user";
    $page = $request->page ?: 1;
    $limit = 10;

    $results = WalletTransition::where('user_id', $user->id)
    ->where('user_type', $userType)
    ->orderBy('created_at', 'desc')
    ->paginate($limit);

    $data['list'] = [];
    $data['count'] = 0;
    $data['per_page'] = 0;
    $data['has_more_pages'] = false;
    $data['current_page'] = $page;
    $data['previous_page_url'] = '';
    $data['next_page_url'] = '';
    $data['last_page_url'] = '';

    $data['list'] = $results->map(function($item){
      $d['transition_id'] = $item->id;
      $d['remark'] = $item->remark;
      $d['amount'] = (double) $item->amount;
      if($item->action == 1){
        $d['action'] = "credit";
      }else{
        $d['action'] = "debit";
      }
      if($item->status == 1){
        $d['status'] = "success";
      }else{
        $d['status'] = "failed";
      }
      $d['date_time'] = date('d-m-Y h:i a', strtotime($item->created_at));
      if($item->invoice == 1 && invoice('recharge', $item->table_id)){
        $d['invoice'] = route('user.invoice', ['type' => 'recharge', 'id' => $item->table_id]);
      }else{
        $d['invoice'] = "";
      }
      return $d;
    });

    if($results->total()){
      $data['count'] = $results->total();
      $data['per_page'] = $results->perPage();
      $data['has_more_pages'] = $results->hasMorePages();
      $data['current_page'] = $results->currentPage();
      $data['previous_page_url'] = $results->previousPageUrl();
      $data['next_page_url'] = $results->nextPageUrl();
      $data['last_page_url'] = $results->url($results->lastPage());
    }
    return response()->json(['success'=>true, 'data'=>$data], 200);
  }

  public function logout(Request $request){
    $user = $request->user();
    $user->api_token = "";
    $user->fcm_token = "";
    $user->save();
    return response()->json(['success'=>true, 'message'=>"Loged Out Successfully"], 402);
  }
  public function userNotification(Request $request){
    $request->validate([
      "astrologer_id"=>"required|integer",
      "type"=>"required|in:astrologer-online,astrologer-live",
    ]);
    $user = $request->user();
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

}
