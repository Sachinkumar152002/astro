<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\Receipt;
use App\Model\ReceiptItem;
use App\Order;
use App\OrderItem;
use PDF;
use File;
use DB;

class InvoiceController extends Controller
{
    public function index($user, $user_type, $data, $data_type, $item_data, $type=0){
      if($data['amount']){
        $invoice = Invoice::where('prefix', 'like', '%INV%')->orderBy('created_at', 'desc')->first();
        $prefix = 'INV-'.date('ym').'-';
        $invoice_number = '00001';
        if($invoice){
          $invoice_number = $invoice->number;
          $invoice_number = (int) ltrim($invoice_number, '0');
          $invoice_number+=1;
          $invoice_number = sprintf("%05d", $invoice_number);
        }
        $invoice = new Invoice();
        $invoice->prefix = $prefix;
        $invoice->number = $invoice_number;
        $invoice->relation_id = $data['id'];
        $invoice->relation_type = $data_type;
        $invoice->user_id = $user->id;
        $invoice->user_type = $user_type;
        $invoice->name = $data['name'];
        $invoice->email = $data['email'];
        $invoice->phone = $data['phone'];
        $invoice->address = $data['address'];
        $invoice->pincode = $data['pincode'];
        $invoice->city = $data['city'];
        $invoice->state = $data['state'];
        $invoice->country = $data['country'];
        $invoice->billing_full_name = $data['billing_full_name'];
        $invoice->billing_email = $data['billing_email'];
        $invoice->billing_phone_number = $data['billing_phone_number'];
        $invoice->billing_address = $data['billing_address'];
        $invoice->billing_country = $data['billing_country'];
        $invoice->billing_state = $data['billing_state'];
        $invoice->billing_city = $data['billing_city'];
        $invoice->billing_zipcode = $data['billing_zipcode'];
        $invoice->description = $data_type;
        $invoice->total_quantity	 = $data['total_quantity'];
        $invoice->amount = $data['amount']; //sale
        $invoice->subtotal_amount = $data['subtotal_amount']; //base*quantity
        $invoice->discount_amount = $data['discount_amount'];
        $invoice->coupon_amount = $data['coupon_amount'];
        $invoice->sgst	 = $data['sgst'];
        $invoice->cgst	 = $data['cgst'];
        $invoice->igst	 = $data['igst'];
        $invoice->shipping_amount = $data['shipping_amount'];
        $invoice->total_amount = $data['total_amount']; //payable
        $invoice->payment_id = $data['payment_id'];
        $invoice->payment_method = $data['payment_method'];
        $invoice->payment_date_time = $data['payment_date_time'];
        $invoice->save();
        $receipt = Receipt::where('relation_id', $data['id'])->where('relation_type', 'order')->where('type', $type)->first();
        if($receipt){
          $invoice->receipt_id = $receipt->id;
          $invoice->save();
        }
        foreach($item_data as $key => $item){
          $invoice_item = new InvoiceItem();
          $invoice_item->invoice_id = $invoice->id;
          $invoice_item->invoice_prefix = $invoice->prefix;
          $invoice_item->invoice_number = $invoice->number;
          $invoice_item->relation_id = $invoice->relation_id;
          $invoice_item->relation_type = $invoice->relation_type;
          $invoice_item->user_id = $invoice->user_id;
          $invoice_item->user_type = $invoice->user_type;
          $invoice_item->item_name = $item['name'];
          $invoice_item->item_description = $item['description'];
          $invoice_item->quantity = $item['quantity'];
          $invoice_item->amount = $item['amount'];
          $invoice_item->base_amount = $item['base_amount'];
          $invoice_item->discount_amount = $item['discount_amount'];
          $invoice_item->coupon_amount = $item['coupon_amount'];
          $invoice_item->tax_type = $item['tax_type'];
          $invoice_item->igst_percentage = $item['igst_percentage'];
          $invoice_item->igst_amount = $item['igst_amount'];
          $invoice_item->cgst_percentage = $item['cgst_percentage'];
          $invoice_item->cgst_amount = $item['cgst_amount'];
          $invoice_item->sgst_percentage = $item['sgst_percentage'];
          $invoice_item->sgst_amount = $item['sgst_amount'];
          $invoice_item->total_amount = $item['total_amount'];
          $invoice_item->shipping_amount = $item['shipping_amount'];
          $invoice_item->hsn_code = $item['hsn_code'];
          $invoice_item->save();
          $invoice_item->net_total = $invoice_item->amount-$invoice_item->coupon_amount;
          $invoice_item->save();
        }
        $path = public_path('invoice/');
        $fileName =  $invoice->prefix.$invoice->number.'.'. 'pdf' ;
        $pdfurl = asset('invoice/'.$fileName);
        if(!File::exists(public_path('invoice/'.$fileName))) {
          if(!File::exists(public_path('invoice'))){
              File::makeDirectory(public_path('invoice'));
          }
          $invoice_items = InvoiceItem::where('invoice_id', $invoice->id)->get();
          $pdf = PDF::loadView(themeView().'invoice.index', compact('invoice', 'invoice_items'));
          $pdf->save($path.$fileName);
          $pdf = null;
        }
        return $pdfurl;
      }
    return "Zero amount has no invoice";

    }

    public function makeReceipt($order_id, $item_id_array, $type){
      $receipt = null;
      $receipt_prefix = '';
      $receipt_number = '00001';
      if($type==0){
        $receipt = Receipt::where('receipt_prefix', 'like', '%ORD%')->orderBy('created_at', 'desc')->first();
        $receipt_prefix = 'ORD-'.date('ym').'-';
      }elseif($type==1){
        $receipt = Receipt::where('receipt_prefix', 'like', '%CNL%')->orderBy('created_at', 'desc')->first();
        $receipt_prefix = 'CNL-'.date('ym').'-';
      }elseif($type==2){
        $receipt = Receipt::where('receipt_prefix', 'like', '%RTN%')->orderBy('created_at', 'desc')->first();
        $receipt_prefix = 'RTN-'.date('ym').'-';
      }
      if($receipt){
        $receipt_number = $receipt->receipt_number;
        $receipt_number = (int) ltrim($receipt_number, '0');
        $receipt_number+=1;
        $receipt_number = sprintf("%05d", $receipt_number);
      }
      $totalAmount = 0;
      $subTotalAmount = 0;
      $shippingAmount = 0;
      $discountAmount = 0;
      $couponAmount = 0;
      $totalQuantity = 0;
      $igstAmount = 0;
      $cgstAmount = 0;
      $sgstAmount = 0;
      $order = Order::find($order_id);
      $order_items = OrderItem::where('order_id', $order_id)->whereIn('id', $item_id_array)->get();
      $receipt = new Receipt();
      $receipt->receipt_prefix = $receipt_prefix;
      $receipt->receipt_number = $receipt_number;
      $receipt->relation_id = $order->id;
      $receipt->relation_type = 'order';
      $receipt->user_id = $order->user_id;
      $receipt->user_type = $order->user_type;
      $receipt->bill_to_name = $order->ship_to_name;
      $receipt->bill_to_email = $order->billing_email;
      $receipt->bill_to_phone = $order->billing_phone_number;
      $receipt->bill_to_address = $order->billing_address;
      $receipt->bill_to_city = $order->billing_city;
      $receipt->bill_to_state = $order->billing_state;
      $receipt->bill_to_country = $order->billing_country;
      $receipt->bill_to_zipcode = $order->billing_zipcode;
      $receipt->ship_to_name = $order->full_name;
      $receipt->ship_to_email = $order->email;
      $receipt->ship_to_phone = $order->phone_number;
      $receipt->ship_to_address = $order->address;
      $receipt->ship_to_city = $order->city;
      $receipt->ship_to_state = $order->state;
      $receipt->ship_to_country = $order->country;
      $receipt->ship_to_zipcode = $order->zipcode;
      $receipt->amount = $order->sub_total;
      $receipt->subtotal_amount = $order->sub_total;
      $receipt->coupon_amount = $order->coupon_amount;
      $receipt->discount_amount = $order->discount_amount;
      $receipt->shipping_amount = $order->shipping_price;
      $receipt->total_amount = $order->total_price;
      $receipt->total_quantity = $order->total_quantity;
      $receipt->igst_amount = $order->igst;
      $receipt->cgst_amount = $order->cgst;
      $receipt->sgst_amount = $order->sgst;
      $receipt->type = $type;
      $receipt->save();
      foreach($order_items as $key => $item){
        $receipt_item = new ReceiptItem();
        $receipt_item->receipt_id = $receipt->id;
        $receipt_item->receipt_prefix = $receipt->receipt_prefix;
        $receipt_item->receipt_number = $receipt->receipt_number;
        $receipt_item->user_id = $receipt->user_id;
        $receipt_item->user_type = $receipt->user_type;
        $receipt_item->item_name = $item->name;
        $receipt_item->item_description = $item->description;
        $receipt_item->quantity = $item->quantity;
        $receipt_item->amount = $item->price;
        $receipt_item->coupon_amount = $item->coupon_amount;
        $receipt_item->discount_amount = $item->discount_amount;
        $receipt_item->subtotal_amount = $item->unit_price*$item->quantity;
        $receipt_item->total_amount = $item->total_price;
        $receipt_item->tax_type = $item->tax_type;
        $receipt_item->igst_percentage = $item->igst_percentage;
        $receipt_item->igst_amount = $item->igst_amount;
        $receipt_item->cgst_percentage = $item->cgst_percentage;
        $receipt_item->cgst_amount = $item->cgst_amount;
        $receipt_item->sgst_percentage = $item->sgst_percentage;
        $receipt_item->sgst_amount = $item->sgst_amount;
        $receipt_item->hsn_code = $item->hsn_code;
        $receipt_item->shipping_amount = $item->shipping_amount;
        $receipt_item->save();
        $totalQuantity += $item->quantity;
        $subTotalAmount += $receipt_item->subtotal_amount;
        $discountAmount += $item->discount_amount;
        $couponAmount += $item->coupon_amount;
        $igstAmount += $item->igst_amount;
        $cgstAmount += $item->cgst_amount;
        $sgstAmount += $item->sgst_amount;
        $shippingAmount += $item->shipping_amount;
        $totalAmount += $receipt_item->total_amount;
      }

      $receipt->total_quantity = $totalQuantity;
      $receipt->igst_amount = $igstAmount;
      $receipt->cgst_amount = $cgstAmount;
      $receipt->sgst_amount = $sgstAmount;
      $receipt->amount = $subTotalAmount;
      $receipt->subtotal_amount = $subTotalAmount;
      $receipt->discount_amount = $discountAmount;
      $receipt->coupon_amount = $couponAmount;
      $receipt->shipping_amount = $shippingAmount;
      $receipt->total_amount = $totalAmount+$shippingAmount;
      $receipt->save();
      // $path = public_path('receipt/');
      // $fileName =  $receipt->receipt_prefix.$receipt->receipt_number.'.'. 'pdf' ;
      // $pdfurl = asset('receipt/'.$fileName);
      // if(!File::exists(public_path('receipt/'.$fileName))){
      //   if(!File::exists(public_path('receipt'))){
      //       File::makeDirectory(public_path('receipt'));
      //   }
      //   $items = ReceiptItem::where('receipt_id', $receipt->id)->get();
      //   $pdf = PDF::loadView(themeView().'invoice.index', compact('receipt', 'items'));
      //   $pdf->save($path.$fileName);
      //   $pdf = null;
      // }
      // return $pdfurl;
      return 'a';

    }
}
