<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\{Controller, PushNotificationController, NotificationController};
use Illuminate\Http\Request;
use DB;
use App\{Product, Order, OrderItem, CartItem, User};
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Api;
use App\Http\Controllers\InvoiceController;
use App\Model\{Course, LmsCourse, ShippingAddress, OrderStatus};
use App\Jobs\{OrderPlaceJob, OrderFailedJob, CoursePurchaseJob};
use App\Services\CustomerReferralRewardService;

class OrderController extends Controller
{

  public function paymentTypes(Request $request){
    $item_type = $request->item_type ?: 0;

    if($item_type == 0 && fromSetting('cod')){
      $data[] = [
        "name" => "Cash On Delivery",
        "value" => "cod"
      ];
    }

    $data[] = [
      "name" => "Pay Online",
      "value" => "now"
    ];

    return response()->json(["success" => true, "data" => $data], 200);
  }

  public function placeOrder(Request $request){
    $user = $request->user();
    $user_type = 'user';
    if(!$user){
      $errors["message"] = "Please login";
      return response()->json($errors, 402);
    }

    $billingName = $shippingName = $user->name;
    $billingEmail = $shippingEmail = $user->email;
    $billingPhone = $shippingPhone = $user->phone;
    $billingCountry = $shippingCountry = $user->country;
    $billingState = $shippingState = $user->state;
    $billingCity = $shippingCity = $user->city;
    $billingPincode = $shippingPincode = $user->pincode;
    $billingAddress = $shippingAddress = $user->address;

    $address_id = $request->address_id ?: 0;
    $shippingAddressData = ShippingAddress::find($address_id);
    if($shippingAddressData){
      $shippingName = $shippingAddressData->name;
      $shippingEmail = $shippingAddressData->email;
      $shippingPhone = $shippingAddressData->phone;
      $shippingCountry = $shippingAddressData->country;
      $shippingState = $shippingAddressData->state;
      $shippingCity = $shippingAddressData->city;
      $shippingPincode = $shippingAddressData->zipcode;
      $shippingAddress = $shippingAddressData->address;
    }

    $billing_address = $request->billing_address;
    if($billing_address == "different"){
      $request->validate([
        'billing_address_country' => 'required|gt:0',
        'billing_address_address' => 'required',
        'billing_address_pincode' => 'required|digits:6',
        'billing_address_city' => 'required|gt:0',
        'billing_address_state' => 'required|gt:0',
        "billing_address_phone" => "required|digits:10|regex:/^[1-9]\d{9}$/",
        "billing_address_email" => "required|email|max:100",
        "billing_address_full_name" => "required|max:100",
      ], [
        'billing_address_country' => 'The Billing Address Contry field is required.',
        'billing_address_state' => 'The Billing Address State field is required.',
        'billing_address_city' => 'The Billing Address City field is required.',
      ]);

      $billingName = $request->billing_address_full_name;
      $billingEmail = $request->billing_address_email;
      $billingPhone = $request->billing_address_phone;
      $billingPincode = $request->billing_address_pincode;
      $billingAddress = $request->billing_address_address;
      $billingCountryId = $request->billing_address_country;
      $billingStateId = $request->billing_address_state;
      $billingCityId = $request->billing_address_state;

      $country = DB::table("master_countries")->where("id", $billingCountryId)->first();
      $billingCountry = $country ? $country->name : $billingCountryId;

      $state = DB::table("master_states")->where("id", $billingStateId)->first();
      $billingState = $state ? $state->name : $billingStateId;

      $city = DB::table("master_cities")->where("id", $billingCityId)->first();
      $billingCity = $city ? $city->name : $billingCityId;
    }elseif($billing_address == "shipping"){
      $billingName = $shippingName;
      $billingEmail = $shippingEmail;
      $billingPhone = $shippingPhone;
      $billingCountry = $shippingCountry;
      $billingState = $shippingState;
      $billingCity = $shippingCity;
      $billingPincode = $shippingPincode;
      $billingAddress = $shippingAddress;
    }

    $item_type = $request->item_type ?: 0;

    $totalQuantity = 0;
    $saleTotalAmount = 0;
    $baseTotalAmount = 0;
    $discountAmount = 0;
    $shippingAmount = 0;
    $couponId = 0;
    $couponCode = $request->coupon_code ?: "";
    $couponApplyOn = "";
    $couponAmount = 0;
    $gstType = 'igst';
    $igstAmount = 0;
    $cgstAmount = 0;
    $sgstAmount = 0;
    $totalAmount = 0;

    if($couponCode){
      $newRequest = new Request([
        'callType'=>'not_ajax',
        'page' => 'order',
        'coupon_code' => $couponCode,
        'item_type' => $item_type,
        'user_id'=>$user->id,
      ]);
      $couponController = new CouponController();
      $couponData = $couponController->apply2($newRequest);
      $couponData = $couponData->getData();
      $couponApplyOn = $couponData->apply_on;
      $couponAmount = $couponData->discount;
      $couponCode = $couponData->code;
      $couponId = $couponData->id;
    }

    $order_id = $request->order_id ?: 0;

    $order = Order::find($order_id);
    if(!$order){
      $order = new Order();
    }
    $order->user_id = $user->id;
    $order->user_type = $user_type;
    $order->billing_full_name = $billingName;
    $order->billing_email = $billingEmail;
    $order->billing_phone_number = $billingPhone;
    $order->billing_country = $billingCountry;
    $order->billing_state = $billingState;
    $order->billing_city = $billingCity;
    $order->billing_zipcode = $billingPincode;
    $order->billing_address = $billingAddress;
    $order->full_name = $shippingName;
    $order->email = $shippingEmail;
    $order->phone_number = $shippingPhone;
    $order->country = $shippingCountry;
    $order->state = $shippingState;
    $order->city = $shippingCity;
    $order->zipcode = $shippingPincode;
    $order->address = $shippingAddress;
    $order->payment_mode = 'online';
    $order->status = 'pending';
    $order->order_status = 'pending';
    $order->payment_status = 'pending';
    $order->coupon = $couponCode;
    $order->coupon_id = $couponId;
    $order->coupon_amount = $couponAmount;
    $order->client_ip = $request->ip();
    $order->client_agent = $request->userAgent();
  $order->item_type = $item_type;
  $order->payment_date_time = date('Y-m-d H:i:s');
  $order->cod = 0;

  // Get ashirwad_box value from cart items formdata
  $cartItems = CartItem::where('user_id', $user->id)->where('item_type', $item_type)->get();
  $ashirwadBox = 0;
  foreach($cartItems as $cartItem) {
    if($cartItem->formdata) {
      $formdataArray = json_decode($cartItem->formdata, true);
      if(is_array($formdataArray)) {
        foreach($formdataArray as $formField) {
          if(isset($formField['name']) && $formField['name'] === 'ashirwad_box') {
            $ashirwadBox = isset($formField['value']) ? (int)$formField['value'] : 0;
            break 2; // Break both loops
          }
        }
      }
    }
  }
  $order->ashirwad_box = $ashirwadBox;

  $order->save();

  $orderId = $order->id;
    $cartItemTotalSaleAmount = $cartItems->sum(function($item) {
        return $item->price * $item->quantity;
    });

    $orderItemIds = [];
    //create order items
    foreach ($cartItems as $item){
      if($item->type == 'shop'){
        $product = $item->product;
        if($product && $product->status){
          $order->product_type = $product->type;

          $itemTotalSaleAmount = $item->quantity*$item->price;
          $itemTotalBaseAmount = $item->quantity*$item->unit_price;
          $totalQuantity += $item->quantity;
          $saleTotalAmount += $itemTotalSaleAmount;
          $baseTotalAmount += $itemTotalBaseAmount;
          $discountAmount = $item->unit_price-$item->price;
          $itemCouponAmount = 0;

          if($couponAmount > 0){
            if($couponApplyOn=='any' || $couponApplyOn=='product'){
              $itemCouponAmount = ($itemTotalSaleAmount/$cartItemTotalSaleAmount)*$couponAmount;
            }
          }

          $itemShippingAmount = 0;

          if($product->apply_shipping){
            $itemShippingAmount = calculateShippingAmount($product, $order->zipcode)*$item->quantity;
            $shippingAmount += $itemShippingAmount;
          }

          $orderItem = OrderItem::where("order_id", $order->id)
          ->where("product_id", $product->id)
          ->first();

          if(!$orderItem){
            $orderItem = new OrderItem();
          }

          $orderItem->user_id = $order->user_id;
          $orderItem->user_type = $order->user_type;
          $orderItem->order_id = $order->id;
          $orderItem->product_id = $product->id;
          $orderItem->sku = $product->sku;
          $orderItem->quantity = $item->quantity;
          $orderItem->price = $item->price;//sale_price only
          $orderItem->unit_price = $item->unit_price; //base_price only
          $orderItem->coupon_amount = $itemCouponAmount/$item->quantity; //single product coupon discount only
          $orderItem->discount_amount = $discountAmount; //single product discount only
          $orderItem->total_price = 0; //with gst and couopon
          $orderItem->net_total = 0;//single product with discount and coupon without gst
          $orderItem->shipping_amount = $itemShippingAmount;
          $orderItem->type = $item->type;

          $title = $product->title;
          $productAttributes = $product->attributes;
          $productAttributeArray = [];

          foreach($productAttributes as $key => $valueTwo){
            if($attribute = $valueTwo->attribute){
              $productAttributeArray[] = "$attribute->name: $valueTwo->attribute_value";
            }
          }

          if(count($productAttributeArray)){
            $title = implode(', ', $productAttributeArray);
            $title="$product->title ($title)";
          }

          $orderItem->name = $title;
          $orderItem->image = $product->image ? $product->image->image(200,200) : no_image();
          $orderItem->description = $product->specifications ? $product->specifications->short_description : '';
          $orderItem->hsn_code = 0;
          $options = [];

          if($apiUrl = $product->apiUrl){
            $options["api_url_id"] = $apiUrl->id;
            $options["api_url"] = $apiUrl->url;
            $options["api_url_report_code"] = $apiUrl->report_code;
          }

          $formdataArray = json_decode($item->formdata, true);

          if(count($formdataArray) > 0){
            $options["formdata"] = $formdataArray;
          }

          if($video_call_url = $product->video_call_url){
            $options["video_call_url"] = $video_call_url;
          }

          if($place = $product->place){
            $options["place"] = $place;
          }

          if($expire_at = $product->expire_at){
            $options["start_at"] = $expire_at;
          }

          $orderItem->options = json_encode($options);
          $orderItem->save();

          $igstPercentage = 0;
          $cgstPercentage = 0;
          $sgstPercentage = 0;
          $igst_amount = 0;
          $csgt_amount = 0;
          $sgst_amount = 0;
          $gstPercentage = 0;

          if($hnsCode = $product->hnsCode){
            $gstPercentage = $hnsCode->gst_percentage;
            $orderItem->hsn_code = $hnsCode->hsn_code;
          }

          $itemTotalSaleAmount = $itemTotalSaleAmount-$itemCouponAmount;

          //in state: divide
          if(strtolower($shippingState) == "haryana"){
            $gstType = 'cgst_sgst';
            $cgstPercentage = $sgstPercentage = $gstPercentage/2;
            $orderItem->tax_type = $gstType;
            $orderItem->cgst_percentage = $cgstPercentage;
            $csgt_amount = ($itemTotalSaleAmount)*($cgstPercentage/100);
            $cgstAmount += $csgt_amount;
            $orderItem->cgst_amount = $csgt_amount/$item->quantity;
            $orderItem->sgst_percentage = $sgstPercentage;
            $sgst_amount = ($itemTotalSaleAmount)*($sgstPercentage/100);
            $sgstAmount += $sgst_amount;
            $orderItem->sgst_amount = $sgst_amount/$item->quantity;
          }else{
            $gstType = 'igst';
            $igstPercentage = $gstPercentage;
            $orderItem->tax_type = $gstType;
            $orderItem->igst_percentage = $igstPercentage;
            $igst_amount = ($itemTotalSaleAmount)*($igstPercentage/100);
            $igstAmount += $igst_amount;
            $orderItem->igst_amount = $igst_amount/$item->quantity;
          }

          $orderItem->net_total = $item->price-$orderItem->coupon_amount;
          $orderItem->total_price = ($item->price*$item->quantity)-$itemCouponAmount+($igst_amount+$sgst_amount+$csgt_amount);
          $orderItem->save();
          $orderItemIds[] = $orderItem->id;
        }else{
          $item->delete();
        }
      }
    }

    OrderItem::where("order_id", $orderId)
    ->whereNotIn("id", $orderItemIds)
    ->delete();

    $orderPrefix = 'ORD-'.date('ym').'-';
    $orderNum = sprintf('%05d', $order->id);
    $prefix = "$orderPrefix$orderNum";

    $discountAmount = $baseTotalAmount-$saleTotalAmount;
    $payableAmount = $saleTotalAmount-$couponAmount;
    $gstAmount = $igstAmount+$sgstAmount+$cgstAmount;
    $payableAmount = $payableAmount+$gstAmount+$shippingAmount;

    $paymentRequest = [
        'receipt' => $prefix,
        'amount' => ceil($payableAmount*100),
        'currency' => 'INR'
    ];

    $order->order_number = $prefix;
    $order->total_quantity = $totalQuantity;
    $order->sub_total = $baseTotalAmount;
    $order->discount = $discountAmount;
    $order->discount_amount = $discountAmount;
    $order->coupon_amount = $couponAmount;
    $order->shipping_price = $shippingAmount;
    $order->igst = $igstAmount;
    $order->cgst = $cgstAmount;
    $order->sgst = $sgstAmount;
    $order->total_price = $payableAmount;
    $order->payable_amount = $payableAmount;
    $order->payment_request = $paymentRequest;

    $payment_option = $request->payment_option ?: "now";
    if($payment_option == "cod"){
      $paymentRequest['amount'] = 0;
      $order->cod = 1;
    }

    $order->save();

    $data = [];
    $data['baseTotalAmount'] = sprintf('%.2f',$baseTotalAmount);
    $data['discountAmount'] = sprintf('%.2f',$discountAmount);
    $data['couponAmount'] = sprintf('%.2f',$couponAmount);
    $data['gstAmount'] = sprintf('%.2f',$gstAmount);
    $data['shippingAmount'] = sprintf('%.2f',$shippingAmount);
    $data['payableAmount'] = sprintf('%.2f',$payableAmount);

    $orderr['order_id'] = $order->id;

    $api = '';
    $orderData = '';

    if($paymentRequest['amount'] > 0){
      $razorpayKey = fromSetting("razorpay_api_key");
      $razorpaySecretKey = fromSetting("razorpay_api_secret_key");

      $api = new Api($razorpayKey, $razorpaySecretKey);
      $orderData  = $api->order->create($paymentRequest); // Creates Razorpay order
      $orderDataId = $orderData ? $orderData['id'] : 0;
      $appName = config('app.name');

      $paymentRequestData = [
          "key" => $razorpayKey,
          "amount" => $paymentRequest['amount'],
          "currency" => "INR",
          "name"=> $appName,
          "library_name" => $appName,
          "image" => logo(),
          "description" => "Placing order Order_id : ".$orderId,
          "order_id" => $orderDataId,
          "callback_url" => route('api.user.verify.order.payment', [$order->id, 'razorpay']),
          "prefill"=>[
            "name"=> $billingName,
            "email"=> $billingEmail,
            "contact"=> $billingPhone,
          ],
          "theme"=>[
            "color"=>"#528FF0",
          ],
      ];

      $order->payment_request = $paymentRequest;
      $order->save();
      return response()->json([
        'success' => true,
        'message' => 'Please make payment',
        'status' => 'payment',
        'order' => $orderr,
        'data' => $paymentRequestData
      ], 200);
    }else{
      $order->status = 'success';
      $order->order_status = 'ordered';

      if($payment_option == "cod"){
        $order->payment_mode = 'cod';
        $order->payment_method = 'cod';
        $order->payment_response = 'cod';
      }else{
        $order->payment_mode = 'nothing';
        $order->payment_response = 'zero amount';
        $order->payment_status = 'success';
      }

      $order->save();

      if($order->item_type == 0){
        // real
        $orderStatus = new OrderStatus;
        $orderStatus->order_id = $order->id;
        $orderStatus->status = 'ordered';
        $orderStatus->save();
      }else{
        // virtual
        $status_array = ['ordered', 'shipped', 'in transit', 'dispatch', 'delivered'];
        foreach ($status_array as $key => $value) {
          $orderStatus = new OrderStatus;
          $orderStatus->order_id = $order->id;
          $orderStatus->status = $value;
          $orderStatus->save();
        }
        $order->order_status = 'delivered';
        $order->save();
      }

      $order_items = OrderItem::where('order_id', $orderId)->get();
      $product_types = ['', 'product', 'pooja', 'service'];
      $product_type = 'product';

      foreach($order_items as $key => $oI){

        if($oI->type == 'shop'){
          $product = $oI->product;
          $product_type = $product_types[$product->type];

          if($product && ($product->type == 1)){
            $product->avaibility -= $oI->quantity;
            $product->save();
          }
        }
      }

      $pdf_url = '';

      $mail_credential = [$order->email => $order->full_name];
      OrderPlaceJob::dispatch($mail_credential, $order, 'user', $product_type, $pdf_url);

      $mail_credential = [config("vrdcms.admin_email") => "Admin"];
      OrderPlaceJob::dispatch($mail_credential, $order, 'admin', $product_type, $pdf_url);

      $message = "Your Order Has Been Placed Successfully. Order Number is $order->order_number";
      if($order->item_type == 1){
        $message = "Order confirmed. Your service will be processed shortly. Order Number is $order->order_number";
      }

      // normal notification
      $notification['user_id'] = $user->id;
      $notification['astrologer_id'] = 0;
      $notification['message'] = $message;
      $notification['image_id'] = 0;
      $notification['link'] = route('user.purchase.item');
      $notification['app_link'] = "order_history";
      $notification['for'] = 1;
      $notification['on'] = 0;
      $notification['status'] = 1;
      $notificationController = new NotificationController();
      $notificationController->create($notification);

      // push notification
      $title="Order";
      $body=$notification['message'];
      $dataArray = [
        'type' => $notification['app_link']
      ];
      $pushNotificationController = new PushNotificationController();
      $pushNotificationController->sendNotification($user->fcm_token, $title, $body, $dataArray);

      CartItem::where('user_id', $user->id)->where('user_type', $user_type)->where('item_type', $item_type)->delete();

      return response()->json([
        'success'=>true,
        'message' => $message,
        'status' => 'placed',
        'order' => $orderr,
        'data' => $data
      ], 200);
    }
  }

  public function verifyOrderPayment(Request $request){
    $this->validate($request, [
      'payment_response' => 'required',
    ]);
    $payment_type = $request->payment_type;
    $order_id = $request->order_id;
    $payment_response = $request->payment_response;
    $success = true;
    $error = "Payment Failed!";
    $orderResponse = [];
    $order = Order::find($order_id);
    $user = User::find($order->user_id);
    $user_type = 'user';
    if($payment_type == 'razorpay'){
      if(empty($payment_response['paymentId']) === false){
        $razorpayKey = fromSetting("razorpay_api_key");
        $razorpaySecretKey = fromSetting("razorpay_api_secret_key");

        $api = new Api($razorpayKey, $razorpaySecretKey);
        try{
          $attributes=[
            'razorpay_order_id' => $payment_response['orderId'],
            'razorpay_payment_id' => $payment_response['paymentId'],
            'razorpay_signature' => $payment_response['signature']
          ];
          $api->utility->verifyPaymentSignature($attributes);
          $orderResponse = $api->payment->fetch($payment_response['paymentId']);
          $payment_response = array_merge($payment_response, $orderResponse->toArray());
          $order->status = 'success';
          $order->payment_status = 'success';
          $order->order_status = 'ordered';
          $order->payment_id = isset($payment_response['paymentId'])?$payment_response['paymentId']:'';
          $order->payment_method = isset($payment_response['method'])?$payment_response['method']:'Online';
          $order->payment_date_time = isset($payment_response['created_at'])?date('Y-m-d H:i:s', $payment_response['created_at']):date('Y-m-d H:i:s');
        }catch(SignatureVerificationError $e){
          $success = false;
          $error = 'Razorpay Error : ' . $e->getMessage();
          $order->status = 'failed';
          $order->payment_status = 'failed';
        }
      }
    }
    $order->payment_response = json_encode($payment_response);
    $order->save();
    if($success === true){
      $data['id'] = $order->id;
      $data['name'] = $order->full_name;
      $data['email'] = $order->email;
      $data['phone'] = $order->phone_number;
      $data['address'] = $order->address;
      $data['pincode'] = $order->zipcode;
      $data['city'] = $order->city;
      $data['state'] = $order->state;
      $data['country'] = $order->country;
      $data['billing_full_name'] = $order->billing_full_name;
      $data['billing_email'] = $order->billing_email;
      $data['billing_phone_number'] = $order->billing_phone_number;
      $data['billing_address'] = $order->billing_address;
      $data['billing_country'] = $order->billing_country;
      $data['billing_state'] = $order->billing_state;
      $data['billing_city'] = $order->billing_city;
      $data['billing_zipcode'] = $order->billing_zipcode;
      $data['total_quantity'] = 0;
      $data['amount'] = $order->sub_total; //base
      $data['subtotal_amount'] = $order->sub_total; //base
      $data['discount_amount'] = $order->discount_amount;
      $data['coupon_amount'] = $order->coupon_amount;
      $data['sgst'] = $order->sgst;
      $data['cgst'] = $order->cgst;
      $data['igst'] = $order->igst;
      $data['shipping_amount'] = $order->shipping_price;
      $data['total_amount'] = $order->total_price; //payable
      $data['order_id'] = $order->order_number;
      $data['payment_id'] = $order->payment_id;
      $data['payment_method'] = $order->payment_method;
      $data['payment_date_time'] = $order->payment_date_time;

      $item_data = [];
      $order_items = OrderItem::where('order_id', $order->id)->get();
      $product_types = ['', 'product', 'pooja', 'service'];
      $product_type = 'product';

      foreach($order_items as $key => $oI){

        if($oI->type == 'shop'){
          $product = $oI->product;
          $product_type = $product_types[$product->type];

          if($product && ($product->type == 1)){
            $product->avaibility -= $oI->quantity;
            $product->save();
          }
        }

        $data['total_quantity'] += $oI->quantity;

        $item_datas['name'] = $oI->name;
        $item_datas['description'] = $oI->description;
        $item_datas['quantity'] = $oI->quantity;
        $item_datas['amount'] = $oI->price; //sale
        $item_datas['base_amount'] = $oI->unit_price; //base
        $item_datas['discount_amount'] = $oI->discount_amount;
        $item_datas['coupon_amount'] = $oI->coupon_amount;
        $item_datas['tax_type'] = $oI->tax_type;
        $item_datas['igst_percentage'] = $oI->igst_percentage;
        $item_datas['igst_amount'] = $oI->igst_amount;
        $item_datas['cgst_percentage'] = $oI->cgst_percentage;
        $item_datas['cgst_amount'] = $oI->cgst_amount;
        $item_datas['sgst_percentage'] = $oI->sgst_percentage;
        $item_datas['sgst_amount'] = $oI->sgst_amount;
        $item_datas['total_amount'] = $oI->total_price;
        $item_datas['shipping_amount'] = $oI->shipping_amount;
        $item_datas['hsn_code'] = $oI->hsn_code;
        $item_data[] = $item_datas;
      }

      $invoiceController = new InvoiceController();
      $pdf_url = '';

      if($order->item_type == 0){
        // real
        $orderStatus = new OrderStatus;
        $orderStatus->order_id = $order->id;
        $orderStatus->status = 'ordered';
        $orderStatus->save();
      }else{
        // virtual
        $status_array = ['ordered', 'shipped', 'in transit', 'dispatch', 'delivered'];
        foreach ($status_array as $key => $value){
          $orderStatus = new OrderStatus;
          $orderStatus->order_id = $order->id;
          $orderStatus->status = $value;
          $orderStatus->save();
        }
        $order->order_status = 'delivered';
        $order->save();
        $pdf_url = $invoiceController->index($user, $user_type, $data, 'order', $item_data);
      }

      $mail_credential = [$order->email => $order->full_name];
      OrderPlaceJob::dispatch($mail_credential, $order, 'user', $product_type, $pdf_url);

      $mail_credential = [config("vrdcms.admin_email") => "Admin"];
      OrderPlaceJob::dispatch($mail_credential, $order, 'admin', $product_type, $pdf_url);

      $message = "Your Order Has Been Placed Successfully. Order Number is $order->order_number";
      if($order->item_type == 1){
        $message = "Order confirmed. Your service will be processed shortly. Order Number is $order->order_number";
      }

      // normal notification
      $notification['user_id'] = $user->id;
      $notification['astrologer_id'] = 0;
      $notification['message'] = $message;
      $notification['image_id'] = 0;
      $notification['link'] = route('user.purchase.item');
      $notification['app_link'] = "order_history";
      $notification['for'] = 1;
      $notification['on'] = 0;
      $notification['status'] = 1;
      $notificationController = new NotificationController();
      $notificationController->create($notification);

      // push notification
      $title="Order";
      $body=$notification['message'];
      $dataArray = [
        'type' => $notification['app_link']
      ];
      $pushNotificationController = new PushNotificationController();
      $pushNotificationController->sendNotification($user->fcm_token, $title, $body, $dataArray);

      // Distribute referral rewards if user used a referral code
      // Reload order to get fresh user data
      $order = Order::with('user')->find($order->id);
      if($order && $order->user) {
        $orderTotal = $order->total_price ?? $order->sub_total ?? 0;
        CustomerReferralRewardService::distributeRewards(
          $order->user,
          $orderTotal,
          'Product Purchase/Puja',
          $order->id,
          'orders'
        );
      }

      CartItem::where('user_id', $order->user_id)->where('user_type', $order->user_type)->where('item_type', $order->item_type)->delete();

      // return response
      $data['order_number'] = $order->order_number;
      return response()->json([
        'success'=>true,
        'message'=> $message,
        'data'=>$data
      ], 200);
    } else {
      OrderFailedJob::dispatch($order);
      return response()->json(['success'=>false, 'message'=>'Payment unverified'], 402);
    }
  }

}
