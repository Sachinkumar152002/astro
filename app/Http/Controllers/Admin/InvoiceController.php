<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Invoice;
use App\Model\InvoiceItem;
use App\Model\Receipt;
use App\Model\ReceiptItem;
use App\Order;
use App\OrderItem;
use File;
use PDF;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
    if(!adminCan('invoice', 'view')) return dd('No Permission');
    $nav = 'invoice';
        $breads = [
            ['name'=>'Invoice']
        ];
        return view('admin.invoice.list', compact('nav','breads'));
    }

    public function all(Request $request){
      $query = Invoice::orderBy('updated_at','desc');
      if($invoice_number = $request->invoice_number){
        $query->whereRaw('CONCAT(prefix, number) like ?', ["%$invoice_number%"]);
      }
      if($relation_type = $request->relation_type){
       $query->where('relation_type', $relation_type);
      }
      if($billing_full_name = $request->billing_full_name){
        $query->where('billing_full_name', 'like', "%$billing_full_name%");
      }
      if($name = $request->name){
        $query->where('name', 'like', "%$name%");
      }
      if($total_amount_from = $request->total_amount_from){
        $query->where('total_amount', '>=', $total_amount_from);
      }
      if($total_amount_to = $request->total_amount_to){
        $query->where('total_amount', '<=', $total_amount_to);
      }
      if($updated_at_from = $request->updated_at_from){
        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }
      if($updated_at_to = $request->updated_at_to){
        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }
      $query = $query->paginate(10);

      $data = [
          'rows' => view('admin.invoice.listrows',['results'=>$query])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$query])->render(),
      ];
      return response()->json($data, 200);
    }

    public function invoice(Request $request){
      $id = $request->id;
      $invoice = Invoice::find($id);
      if($invoice){
        $path = public_path('invoice/');
        $fileName =  $invoice->prefix.$invoice->number.'.pdf' ;
        $pdfUrl = asset('invoice/'.$fileName);
        if(!File::exists(public_path('invoice/'.$fileName))) {
          if(!File::exists(public_path('invoice'))){
              File::makeDirectory(public_path('invoice'));
          }
          $invoice_items = InvoiceItem::where('invoice_id', $invoice->id)->get();
          $pdf = PDF::loadView(themeView().'invoice.index', compact('invoice', 'invoice_items'));
          $pdf->save($path.$fileName);
          $pdf = null;
        }
        $headers = array(
          'Content-Type: application/pdf',
        );
        $file = public_path('invoice/'.$fileName);
        return \Response::download($file, 'invoice-'.$invoice->prefix.$invoice->number.'.pdf', $headers);
        return redirect($pdfUrl);
      }
      return redirect()->back();
    }

    public function delete(Request $request){
    if(!adminCan('invoice', 'delete')) return dd('No Permission');
      if($id = $request->id) {
        $data = Invoice::find($order_id);
        $data->delete();
        return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function make($order_id, $type = 0){
      $order = Order::find($order_id);
      if($order->sub_total){
        $order_items = OrderItem::where('order_id', $order_id)->get();
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
        $invoice->relation_id = $order->id;
        $invoice->relation_type = 'order';
        $invoice->user_id = $order->user_id;
        $invoice->user_type = $order->user_type;
        $invoice->name = $order->full_name;
        $invoice->email = $order->email;
        $invoice->phone = $order->phone_number;
        $invoice->country = $order->country;
        $invoice->state = $order->state;
        $invoice->city = $order->city;
        $invoice->pincode = $order->zipcode;
        $invoice->address = $order->address;
        $invoice->billing_full_name = $order->billing_full_name;
        $invoice->billing_email = $order->billing_email;
        $invoice->billing_phone_number = $order->billing_phone_number;
        $invoice->billing_country = $order->billing_country;
        $invoice->billing_state = $order->billing_state;
        $invoice->billing_city = $order->billing_city;
        $invoice->billing_zipcode = $order->billing_zipcode;
        $invoice->billing_address = $order->billing_address;
        $invoice->description = 'order';
        $invoice->total_quantity = $order->total_quantity;
        $invoice->amount = $order->sub_total;
        $invoice->subtotal_amount = $order->sub_total;
        $invoice->discount_amount = $order->discount_amount;
        $invoice->coupon_amount = $order->coupon_amount;
        $invoice->sgst	 = $order->sgst;
        $invoice->cgst	 = $order->cgst;
        $invoice->igst	 = $order->igst;
        $invoice->shipping_amount = $order->shipping_price;
        $invoice->total_amount = $order->total_price;
        $invoice->payment_id = $order->payment_id;
        $invoice->payment_method = $order->payment_method;
        $invoice->payment_date_time = $order->payment_date_time;
        $invoice->save();
        $receipt = Receipt::where('relation_id', $order->id)->where('relation_type', 'order')->where('type', $type)->first();
        if($receipt){
          $invoice->receipt_id = $receipt->id;
          $invoice->save();
        }
        foreach($order_items as $key => $item){
          $invoice_item = new InvoiceItem();
          $invoice_item->invoice_id = $invoice->id;
          $invoice_item->invoice_prefix = $invoice->prefix;
          $invoice_item->invoice_number = $invoice->number;
          $invoice_item->relation_id = $invoice->relation_id;
          $invoice_item->relation_type = $invoice->relation_type;
          $invoice_item->user_id = $invoice->user_id;
          $invoice_item->user_type = $invoice->user_type;
          $invoice_item->item_name = $item->name;
          $invoice_item->item_description = $item->description;
          $invoice_item->quantity = $item->quantity;
          $invoice_item->amount = $item->price;
          $invoice_item->base_amount = $item->unit_price;
          $invoice_item->discount_amount = $item->discount_amount;
          $invoice_item->coupon_amount = $item->coupon_amount;
          $invoice_item->net_total = $item->net_total;
          $invoice_item->tax_type = $item->tax_type;
          $invoice_item->igst_percentage = $item->igst_percentage;
          $invoice_item->igst_amount = $item->igst_amount;
          $invoice_item->cgst_percentage = $item->cgst_percentage;
          $invoice_item->cgst_amount = $item->cgst_amount;
          $invoice_item->sgst_percentage = $item->sgst_percentage;
          $invoice_item->sgst_amount = $item->sgst_amount;
          $invoice_item->total_amount = $item->total_price;
          $invoice_item->shipping_amount = $item->shipping_amount;
          $invoice_item->hsn_code = $item->hsn_code;
          $invoice_item->save();
        }
      }
    }

    public function makeReturnInvoice($order_id, $items_id_array, $type, $include_shipping=false){
      $invoice = Invoice::where('prefix', 'like', '%RTN%')->orderBy('created_at', 'desc')->first();
      $prefix = 'RTN-'.date('ym').'-';
      $invoice_number = '00001';
      if($invoice){
        $invoice_number = $invoice->number;
        $invoice_number = (int) ltrim($invoice_number, '0');
        $invoice_number+=1;
        $invoice_number = sprintf("%05d", $invoice_number);
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
      $order_items = OrderItem::where('order_id', $order_id)->whereIn('id', $items_id_array)->get();
      $invoice = new Invoice();
      $invoice->prefix = $prefix;
      $invoice->number = $invoice_number;
      $invoice->relation_id = $order->id;
      $invoice->relation_type = 'order';
      $invoice->user_id = $order->user_id;
      $invoice->user_type = $order->user_type;
      $invoice->name = $order->full_name;
      $invoice->email = $order->email;
      $invoice->phone = $order->phone_number;
      $invoice->country = $order->country;
      $invoice->state = $order->state;
      $invoice->city = $order->city;
      $invoice->pincode = $order->zipcode;
      $invoice->address = $order->address;
      $invoice->billing_full_name = $order->billing_full_name;
      $invoice->billing_email = $order->billing_email;
      $invoice->billing_phone_number = $order->billing_phone_number;
      $invoice->billing_country = $order->billing_country;
      $invoice->billing_state = $order->billing_state;
      $invoice->billing_city = $order->billing_city;
      $invoice->billing_zipcode = $order->billing_zipcode;
      $invoice->billing_address = $order->billing_address;
      $invoice->description = 'order';
      $invoice->total_quantity = $order->total_quantity;
      $invoice->amount = $order->sub_total;
      $invoice->subtotal_amount = $order->sub_total;
      $invoice->discount_amount = $order->discount_amount;
      $invoice->coupon_amount = $order->coupon_amount;
      $invoice->sgst	 = $order->sgst;
      $invoice->cgst	 = $order->cgst;
      $invoice->igst	 = $order->igst;
      $invoice->shipping_amount = $order->shipping_price;
      $invoice->total_amount = $order->total_price;
      $invoice->payment_id = $order->payment_id;
      $invoice->payment_method = $order->payment_method;
      $invoice->payment_date_time = $order->payment_date_time;
      $invoice->save();
      $receipt = Receipt::where('relation_id', $order->id)->where('relation_type', 'order')->where('type', $type)->first();
      if($receipt){
        $invoice->receipt_id = $receipt->id;
        $invoice->save();
      }
      foreach($order_items as $key => $item){
        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_id = $invoice->id;
        $invoice_item->invoice_prefix = $invoice->prefix;
        $invoice_item->invoice_number = $invoice->number;
        $invoice_item->relation_id = $invoice->relation_id;
        $invoice_item->relation_type = $invoice->relation_type;
        $invoice_item->user_id = $invoice->user_id;
        $invoice_item->user_type = $invoice->user_type;
        $invoice_item->item_name = $item->name;
        $invoice_item->item_description = $item->description;
        $invoice_item->quantity = $item->quantity;
        $invoice_item->amount = $item->price;
        $invoice_item->base_amount = $item->unit_price;
        $invoice_item->discount_amount = $item->discount_amount;
        $invoice_item->coupon_amount = $item->coupon_amount;
        $invoice_item->net_total = $item->net_total;
        $invoice_item->tax_type = $item->tax_type;
        $invoice_item->igst_percentage = $item->igst_percentage;
        $invoice_item->igst_amount = $item->igst_amount;
        $invoice_item->cgst_percentage = $item->cgst_percentage;
        $invoice_item->cgst_amount = $item->cgst_amount;
        $invoice_item->sgst_percentage = $item->sgst_percentage;
        $invoice_item->sgst_amount = $item->sgst_amount;
        $invoice_item->total_amount = $item->total_price;
        $invoice_item->shipping_amount = $item->shipping_amount;
        $invoice_item->hsn_code = $item->hsn_code;
        $invoice_item->save();
        $totalQuantity += $item->quantity;
        $subTotalAmount += $item->unit_price*$item->quantity;
        $discountAmount += $item->discount_amount;
        $couponAmount += $item->coupon_amount;
        $igstAmount += $item->igst_amount;
        $cgstAmount += $item->cgst_amount;
        $sgstAmount += $item->sgst_amount;
        $shippingAmount += $include_shipping?$item->shipping_amount:0;
        $totalAmount += $item->total_price;
      }
      $invoice->total_quantity	 = $totalQuantity;
      $invoice->amount	 = $subTotalAmount;
      $invoice->subtotal_amount	 = $subTotalAmount;
      $invoice->discount_amount	 = $discountAmount;
      $invoice->coupon_amount	 = $couponAmount;
      $invoice->igst	 = $igstAmount;
      $invoice->cgst	 = $cgstAmount;
      $invoice->sgst	 = $sgstAmount;
      $invoice->shipping_amount	 = $shippingAmount;
      $invoice->total_amount	 = $totalAmount+$shippingAmount;
      $invoice->save();
      $invoice = Invoice::where('prefix', 'like', '%INV%')->where('relation_id', $order->id)->where('relation_type', 'order')->first();
      $invoice->status = 0;
      $invoice->save();
    }

    public function makeReceipt($order_id, $item_id_array, $type, $include_shipping){
      $receipt = null;
      $receipt_prefix = '';
      $receipt_number = '00001';
      if($type==0){//order
        $receipt = Receipt::where('receipt_prefix', 'like', '%ORD%')->orderBy('created_at', 'desc')->first();
        $receipt_prefix = 'ORD-'.date('ym').'-';
      }elseif($type==1){//cancel
        $receipt = Receipt::where('receipt_prefix', 'like', '%CNL%')->orderBy('created_at', 'desc')->first();
        $receipt_prefix = 'CNL-'.date('ym').'-';
      }elseif($type==2){//return
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
        $shippingAmount += $include_shipping?$item->shipping_amount:0;
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
      return [
        'receipt_id'=>$receipt->id,
        'pdfurl'=>'',
      ];

    }
}
