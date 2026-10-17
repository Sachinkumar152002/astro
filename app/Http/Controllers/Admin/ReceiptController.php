<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Receipt;
use App\Model\ReceiptItem;

class ReceiptController extends Controller
{
    public function index($type, $message, $invoice, $invoice_items){
      $receipt = null;
      $receipt_prefix = '';
      $receipt_type = 0;
      if($type == 'cancel'){
        $receipt = Receipt::where('receipt_prefix', 'like', '%CNL%')->orderBy('created_at', 'desc')->first();
        $receipt_prefix = 'CNL-'.date('ym').'-';
        $receipt_type = 0;
      }elseif($type == 'refund'){
        $receipt = Receipt::where('receipt_prefix', 'like', '%RFD%')->orderBy('created_at', 'desc')->first();
        $receipt_prefix = 'RFD-'.date('ym').'-';
        $receipt_type = 1;
      }
      $receipt_number = '00001';
      if($receipt){
        $receipt_number = $receipt->number;
        $receipt_number = (int) ltrim($receipt_number, '0');
        $receipt_number+=1;
        $receipt_number = sprintf("%05d", $receipt_number);
      }
      $receipt = new Receipt();
      $receipt->invoice_id = $invoice->id;
      $receipt->invoice_prefix = $invoice->prefix;
      $receipt->invoice_number = $invoice->number;
      $receipt->invoice_payment_id = $invoice->payment_id;
      $receipt->invoice_payment_method = $invoice->payment_method;
      $receipt->invoice_payment_date_time = $invoice->payment_date_time;
      $receipt->relation_id = $invoice->relation_id;
      $receipt->relation_type = $invoice->relation_type;
      $receipt->receipt_prefix = $receipt_prefix
      $receipt->receipt_number = $receipt_number;
      $receipt->gst = $invoice->gst;
      $receipt->pan = $invoice->pan;
      $receipt->bill_from_company_name = $invoice->bill_from_company_name;
      $receipt->bill_from_company_address = $invoice->bill_from_company_address;
      $receipt->ship_from_company_name = $invoice->ship_from_company_name;
      $receipt->ship_from_company_address = $invoice->ship_from_company_address;
      $receipt->nature_of_supply = $invoice->nature_of_supply;
      $receipt->user_id = $invoice->user_id;
      $receipt->user_type = $invoice->user_type;
      $receipt->bill_to_name = $invoice->billing_full_name;
      $receipt->bill_to_email = $invoice->billing_email;
      $receipt->bill_to_phone = $invoice->billing_phone_number;
      $receipt->bill_to_address = $invoice->billing_address;
      $receipt->bill_to_country = $invoice->billing_country;
      $receipt->bill_to_state = $invoice->billing_state;
      $receipt->bill_to_city = $invoice->billing_city;
      $receipt->bill_to_zipcode = $invoice->billing_zipcode;
      $receipt->ship_to_name = $invoice->name;
      $receipt->ship_to_email = $invoice->email;
      $receipt->ship_to_phone = $invoice->phone;
      $receipt->ship_to_address = $invoice->address;
      $receipt->ship_to_city = $invoice->city;
      $receipt->ship_to_state = $invoice->state;
      $receipt->ship_to_country = $invoice->country;
      $receipt->ship_to_zipcode = $invoice->pincode;
      $receipt->total_quantity = $invoice->total_quantity;
      $receipt->amount = $invoice->amount;
      $receipt->coupon_amount = $invoice->coupon_amount;
      $receipt->discount_amount = $invoice->discount_amount;
      $receipt->shipping_amount = $invoice->shipping_amount;
      $receipt->subtotal_amount = $invoice->subtotal_amount;
      $receipt->igst_amount = $invoice->igst;
      $receipt->cgst_amount = $invoice->cgst;
      $receipt->sgst_amount = $invoice->sgst;
      $receipt->total_amount = $invoice->total_amount;
      $receipt->type = $receipt_type;
      $receipt->message = $message;
      $receipt->save();
      foreach($invoice_items as $key => $item){
        $receipt_item = new ReceiptItem();
        $receipt_item->invoice_id = $invoice->id;
        $receipt_item->invoice_prefix = $invoice->prefix;
        $receipt_item->invoice_number = $invoice->number;
        $receipt_item->invoice_item_id = $item->id;
        $receipt_item->receipt_id = $receipt->id;
        $receipt_item->receipt_prefix = $receipt->receipt_prefix;
        $receipt_item->receipt_number = $receipt->receipt_number;
        $receipt_item->user_id = $receipt->user_id;
        $receipt_item->user_type = $receipt->user_type;
        $receipt_item->item_name = $item->item_name;
        $receipt_item->item_description = $item->item_description;
        $receipt_item->quantity = $item->quantity;
        $receipt_item->amount = $item->amount;
        $receipt_item->coupon_amount = $item->coupon_amount;
        $receipt_item->discount_amount = $item->discount_amount;
        $receipt_item->subtotal_amount = $item->total_amount-($item->coupon_amount+$item->discount_amount);
        $receipt_item->tax_type = $item->tax_type;
        $receipt_item->igst_percentage = $item->igst_percentage;
        $receipt_item->igst_amount = $item->igst_amount;
        $receipt_item->cgst_percentage = $item->cgst_percentage;
        $receipt_item->cgst_amount = $item->cgst_amount;
        $receipt_item->sgst_percentage = $item->sgst_percentage;
        $receipt_item->sgst_amount = $item->sgst_amount;
        $receipt_item->hsn_code = $item->hsn_code;
        $receipt_item->save();
        $receipt_item->total_amount = $receipt_item->subtotal_amount+$receipt_item->igst_amount+$receipt_item->cgst_amount+$receipt_item->sgst_amount;
        $receipt_item->save();
      }
      $path = public_path('receipt/'.$type.'/');
      $fileName =  $receipt->prefix.$receipt->number.'.'. 'pdf' ;
      $pdfurl = asset('receipt/'.$type.'/'.$fileName);
      $pdfurl = '';
      // if(!File::exists(public_path('receipt/'.$type.'/'.$fileName))){
      //   if(!File::exists(public_path('receipt'))){
      //       File::makeDirectory(public_path('receipt'));
      //   }
      //   if(!File::exists(public_path('receipt/'.$type))){
      //       File::makeDirectory(public_path('receipt/'.$type));
      //   }
      //   $receipt_items = ReceiptItem::where('receipt_id', $receipt->id)->get();
      //   $pdf = PDF::loadView(themeView().'receipt.index', compact('receipt', 'receipt_items'));
      //   $pdf->save($path.$fileName);
      //   $pdf = null;
      // }
      return $pdfurl;

    }
}
