<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\OrderItem;
use App\Model\LmsCourse;
use App\Model\UserRechargeHistory;
use DB;
use App\{CartItem, User};
use App\Http\Controller\InvoiceController;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Api;
use App\Jobs\{OrderPlaceJob, OrderFailedJob, CoursePurchaseJob};
use App\Services\CustomerReferralRewardService;
use Session;

class VerifyPaymentController extends Controller
{
    public function razorpayOrder(Request $request){
      $id = $request->id;
      $success = true;
      $error = "Payment Failed!";
      $user = null;
      $order = Order::find($id);
      if(!$order){
        return redirect()->back();
      }
      $user = User::find($order->user_id);
      $user_type = 'user';
      $type = $user_type; // For compatibility with InvoiceController
      $orderResponse = [];
      $payment_response = $request->all();
      if (empty($request->razorpay_payment_id) === false) {
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
              $order->status = 'Success';
              $order->payment_status = 'Success';
              // $order->published = 1;
          } catch (SignatureVerificationError $e) {
              $success = false;
              $error = 'Razorpay Error : ' . $e->getMessage();
              $order->status = 'Failed';
              $order->payment_status = 'Failed';
          }
      }
      $order->payment_response = json_encode($payment_response);
      $order->save();

      // Refresh order to ensure latest data is available
      $order->refresh();

      if ($success === true) {
        $total_qnty = 1;
        $data['id'] = $order->id;
        $data['city'] = $order->city;
        $data['state'] = $order->state;
        $data['country'] = $order->country;
        $data['total_quantity'] = $total_qnty;
        $data['sgst'] = $order->sgst;
        $data['cgst'] = $order->cgst;
        $data['igst'] = $order->igst;
        $data['amount'] = $order->total_price;
        $data['total_amount'] = $order->net_total;
        $data['discount_amount'] = $order->discount;
        $data['order_id'] = $order->order_number;
        $order_items = OrderItem::where('order_id', $order->id)->get();
        $item_data = [];
        foreach($order_items as $key => $oI){
          if($oI->type == 'course'){
            $expire_date = date('Y-m-d', strtotime("now+".($oI->course?$oI->course->duration:0)." months"));
            $lms = new LmsCourse();
            $lms->user_id = $user->id;
            $lms->user_name = $user->display_name?:$user->name;
            $lms->user_email = $user->email;
            $lms->user_phone = $user->phone;
            $lms->user_type = $type;
            $lms->order_id = $oI->order_id;
            $lms->order_item_id = $oI->id;
            $lms->course_id = $oI->course?$oI->course->id:0;
            $lms->course_name = $oI->course?$oI->course->name:'';
            $lms->amount = $oI->price;
            $lms->expire_date = $expire_date;
            $lms->status = 1;
            $lms->save();
            CoursePurchaseJob::dispatch($lms);
          }

          $total_qnty += $oI->quantity;
          $item_datas['name'] = $oI->name;
          $item_datas['description'] = $oI->description;
          $item_datas['quantity'] = $oI->quantity;
          $item_datas['amount'] = $oI->price;
          $item_datas['total_amount'] = $oI->total_price;
          array_push($item_data, $item_datas);
        }
        $data['total_quantity'] = $total_qnty;
        $invoiceController = new InvoiceController();
        $pdfUrl = $invoiceController->index($user, $user_type, $data, 'order', $item_data);

        // Determine product type
        $product_types = ['', 'product', 'pooja', 'service'];
        $product_type = 'product';
        foreach($order_items as $oI) {
          if($oI->type == 'shop' && $oI->product) {
            $product_type = $product_types[$oI->product->type] ?? 'product';
            break;
          }
        }

        // Dispatch OrderPlaceJob with correct parameters
        $mail_credential = [$order->email => $order->full_name];
        OrderPlaceJob::dispatch($mail_credential, $order, 'user', $product_type, $pdfUrl);

        $mail_credential = [config("vrdcms.admin_email") => "Admin"];
        OrderPlaceJob::dispatch($mail_credential, $order, 'admin', $product_type, $pdfUrl);

        // Note: Referral rewards will be distributed in OrderPlaceJob
        // (when order status is 'success' and payment_status is 'success' or 'cod')

        CartItem::where('user_id', $user->id)->where('user_type', $type)->delete();

        // Mark remedies suggestion as used after successful payment
        $remediesSuggestionToken = Session::get('remedies_suggestion_token');
        if($remediesSuggestionToken){
          DB::table('astrologer_remedy_suggestions')
            ->where('token', $remediesSuggestionToken)
            ->where('is_used', 0)
            ->update(['is_used' => 1]);
          Session::forget('remedies_suggestion_token');
        }

        // Clear remedies referral code session after successful payment
        Session::forget('remedies_referral_code');

        // Clear product referral code session after successful payment
        Session::forget('product_referral_code');

        return redirect()->route('thank.you');
      } else {
        OrderFailedJob::dispatch($order);
        return redirect()->back();
      }
    }
}
