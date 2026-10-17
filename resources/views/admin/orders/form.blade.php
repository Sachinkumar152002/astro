@extends('layouts.admin')


@section('content')
{!! Form::model($order, ['route' => 'admin.order.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($order)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <h1 class="text-primary float-left">Order Details</h1>
    </div>
    <div class="col-md-6 text-right mt-2">
       <!-- <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button> -->
       <!-- <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button> -->
    </div>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#order_detail">Information</a></li>

    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#update">Status</a></li>
    @if($order->status != 'pending')
      @if($order->product_type == 1)
        <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#shippment">Shippment</a></li>
      @endif
    @if(count($order->cancelRequests))
      <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#cancel_request">Cancel Request</a></li>
    @endif
    @if(count($order->returnRequests))
      <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#return_request">Return Request</a></li>
    @endif
  @endif
</ul>

<div class="tab-content">
  <div id="order_detail" class="tab-pane fade in active show px-0">
    <div class="container px-0 mx-0">
      <div class="row">
        <div class="col-md-4">
         <div class="invoice-box px-2 py-1">

           @if($order->invoice)
           <h4 class="mb-0">Invoice</h4>
            <p class="mb-0"> <a href="{{route('admin.invoice.download', [$order->invoice->id])}}" class="text-primary" targe="_blank">
               {{$order->invoice->prefix.$order->invoice->number}}
             </a>
           </p>
             @if($order->item_type == 0)
             <h4 class="mb-0">Slip</h4>
              <p class="mb-0">
                <a href="{{route('admin.order.downloadslip', [$order->invoice->id])}}" class="text-primary" targe="_blank">
                 Download
               </a>
             </p>
             @endif
           @else
           <h4 class="mb-0">Order #:</h4>
           <p class="mb-0"> <a class="text-primary">{{$order->order_number}}</a> </p>
           @endif
         </div>
        </div>
        <div class="col-md-4">
         <div class="invoice-box px-2 py-1">
           <h4 class="mb-0">Customer</h4>
           <p class="mb-0">{{$order->full_name}}</p>
         </div>
        </div>
        <div class="col-md-4">
        <div class="invoice-box  px-2 py-1">
          <h4 class="mb-0">Date Added</h4>
          <p class="mb-0">{{ISTDatetimeFormat($order->payment_date_time)}}</p>
        </div>
        </div>
        @if(strtolower($order->order_status) == "pending")
          <div class="col-md-1 col-4 mt-3">
            <button type="button" onclick="copyCheckoutUrl(this)" class="btn btn-primary text-white" data-checkout-url="{{ route('guest.redirecttocheckout', ['user_id' => encrypt($order->user_id), 'order_id' => encrypt($order->id)]) }}"><i class="far fa-copy"></i> Checkout Url</button>
          </div>
        @endif
      </div>
      @php
        $orderItems = $order->orderItems;
        $fristOrderItem = $orderItems->first();
      @endphp
      <table class="table table-bordered mt-3">
        <tr>
          <th>Sr No.</th>
          <th>Quantity</th>
          <th>Product</th>
          <th>Price</th>
          <th>Discount</th>
          <th>Coupon Discount</th>
          <th>Net Amount</th>
          <th>Tax (%)</th>
          <th>Tax (₹)</th>
          <th>Total</th>
        </tr>
          @foreach($orderItems as $key => $item)
        <tr>
          <td>{{$key+1}}</td>
          <td>{{$item->quantity}}</td>
          <td>{{$item->name}}</td>
          <td>{{sprintf('%.2f', $item->unit_price)}}</td>
          <td>{{sprintf('%.2f', $item->discount_amount)}}</td>
          <td>{{sprintf('%.2f', $item->coupon_amount)}}</td>
          <td>{{sprintf('%.2f', $item->net_total)}}</td>
          <td>
            @if($item->tax_type=='igst')
            IGST ({{$item->igst_amount>0?$item->igst_percentage:0}} %)
            @else
            CGST ({{$item->cgst_amount>0?$item->cgst_percentage:0}} %)
            <br>
            SGST ({{$item->sgst_amount>0?$item->sgst_percentage:0}} %)
            @endif
          </td>
          <td>
            @if($item->tax_type=='igst')
            ₹ {{sprintf('%.2f', $item->igst_amount)}}
            @else
            ₹ {{sprintf('%.2f', $item->cgst_amount)}}
            <br>
            ₹ {{sprintf('%.2f', $item->sgst_amount)}}
            @endif
          </td>
          <td>₹ {{sprintf('%.2f', $item->total_price)}}</td>
        </tr>
          @endforeach
          <tr>
            <td colspan="5">
              <h3 class="fw-bold">Billing To</h3>
            <p>
              {{$order->billing_full_name}}
              <br>
              {{$order->billing_email}}
              <br>
              {{$order->billing_phone_number}}
              <br>
              {{$order->billing_address }}, {{$order->billing_city}}, {{$order->billing_state}}-{{$order->billing_country}} ({{$order->billing_zipcode}})
            </p>
          </td>
          @if($order->item_type == 0)
            <td colspan="5">
              <h3 class="fw-bold">Shipping To</h3>
              <p>
                {{$order->full_name}}
                <br>
                {{$order->email}}
                <br>
                {{$order->phone_number}}
                <br>
                {{$order->address }}, {{$order->city}}, {{$order->state}}-{{$order->country}} ({{$order->zipcode}})
              </p>
            </td>
          @endif
          @php
            $optinsArray = json_decode($fristOrderItem->options, true);
          @endphp
          @if(count($optinsArray) > 0)
            <td colspan="5">
              <h3 class="fw-bold">Detail</h3>
              @if( isset($optinsArray["formdata"]) )
                @php
                  $formDataArray = $optinsArray["formdata"];
                @endphp
                @foreach($formDataArray as $index => $item)
                  @if(isset($item["value"]))
                    <p class="mb-0">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $item["label"]) }}: {{ $item["value"] }}</p>
                  @endif
                @endforeach
              @endif
              @if( isset($optinsArray["place"]) )
                <p class="mb-0">Place: {{ $optinsArray["place"] }}</p>
              @endif
              @php
                $startAtSeconds = strtotime("now");
                $isOneToOnePuja = false;
                if($fristOrderItem && $fristOrderItem->product) {
                  $isOneToOnePuja = ($fristOrderItem->product->is_one_to_one_puja == 1);
                }
              @endphp
              @if( isset($optinsArray["start_at"]) && !$isOneToOnePuja )
                @php
                  $startAtSeconds = strtotime($optinsArray["start_at"]);
                  $isPujaComplete = $startAtSeconds < strtotime("now");
                @endphp
                <p class="mb-0">Start at: {{ date("D, d M Y h:i a", $startAtSeconds) }}</p>
              @elseif( isset($optinsArray["start_at"]) && $isOneToOnePuja )
                @php
                  $startAtSeconds = strtotime($optinsArray["start_at"]);
                  $isPujaComplete = $startAtSeconds < strtotime("now");
                @endphp
              @else
                @php
                  $isPujaComplete = false;
                  $startAtSeconds = strtotime("now");
                @endphp
              @endif
              @if( isset($optinsArray["start_at"]) )
                @if($isPujaComplete)
                  <p class="mb-0 pt-3"><strong>Status:</strong> <span class="text-success">Success</span></p>
                @elseif( isset($optinsArray["video_call_url"]) && $isOneToOnePuja)
                  <p class="mb-0 k_bold pt-3 d-flex align-items-center flex-wrap gap-2">
                    <span><strong>Video Url :</strong></span>
                    @if(date("Y-m-d", $startAtSeconds) < date("Y-m-d"))
                      <span class="text-muted">not available</span>
                    @elseif($startAtSeconds-(15*60) <= strtotime("now"))
                      <a href="{{ $optinsArray['video_call_url'] }}" target="_blank" rel="noopener noreferrer" class="btn text-white text-center d-inline-flex align-items-center justify-content-center" style="background-color: #215082; border-radius: 6px; padding: 6px 16px; text-decoration: none; border: none;">Join</a>
                    @else
                      <span class="text-muted">Link will be available before 15 mins from start time.</span>
                    @endif
                  </p>
                @endif
              @elseif( isset($optinsArray["video_call_url"]) && $isOneToOnePuja)
                <p class="mb-0 k_bold pt-3 d-flex align-items-center flex-wrap gap-2">
                  <span><strong>Video Url :</strong></span>
                  <a href="{{ $optinsArray['video_call_url'] }}" target="_blank" rel="noopener noreferrer" class="btn text-white text-center d-inline-flex align-items-center justify-content-center" style="background-color: #215082; border-radius: 6px; padding: 6px 16px; text-decoration: none; border: none;">Join</a>
                </p>
              @endif
              @if($pdf_url)
                <p class="mb-0 k_bold mt-3">
                  PDF : <a href="{{ $pdf_url }}" target="_blank"> Download</a>
                </p>
              @endif
            </td>
            @endif
          </tr>
          <tr>
            <td colspan="5">
              @if($order->item_type == 0)
                Shipping
                <p>+ ₹ {{ $order->shipping_price }}</p>
              @endif
            </td>
            <td colspan="5">Payment Method
               <p>{{$order->payment_method}}</p>
            </td>
          </tr>
          <tr>
            <th class="text-right" colspan="8">Sub Total</th>
            <td class="text-right" colspan="2">₹ {{sprintf('%.2f', $order->sub_total)}}</td>
          </tr>
          <tr>
            <th class="text-right" colspan="8">Discount</th>
            <td class="text-right" colspan="2">- ₹ {{sprintf('%.2f', $order->discount_amount)}}</td>
          </tr>
          <tr>
            <th class="text-right" colspan="8">Coupon</th>
            <td class="text-right" colspan="2">- ₹ {{sprintf('%.2f', $order->coupon_amount)}}</td>
          </tr>
          <tr>
            <th class="text-right" colspan="8">Gst & Taxes </th>
            <td class="text-right" colspan="2">+ ₹ {{sprintf('%.2f', $order->igst+$order->cgst+$order->sgst)}}</td>
          </tr>
          @if($order->cgst>0 && $order->sgst>0)
          <tr>
            <th class="text-right" colspan="8">	CGST</th>
            <td class="text-right" colspan="2">₹ {{sprintf('%.2f', $order->cgst)}}</td>
          </tr>
          <tr>
            <th class="text-right" colspan="4">	SGST</th>
            <td class="text-right" colspan="2">₹ {{sprintf('%.2f', $order->sgst)}}</td>
          </tr>
          @endif
          <tr>
            <th class="text-right" colspan="8">	Shipping</th>
            <td class="text-right" colspan="2">+ ₹ {{sprintf('%.2f', $order->shipping_price)}}</td>
          </tr>
          <tr>
            <th class="text-right" colspan="8">	Total</th>
            <td class="text-right" colspan="2">₹ {{sprintf('%.2f', $order->total_price)}}</td>
          </tr>

      </table>


    </div>
  </div>
  <div id="update" class="tab-pane px-0">
    <div class="row">
      <div class="col-md-2 col-4">
        <div class="form-group">
            {!! Form::label('order_status', 'Order Status', ['class' => 'required']) !!}
            <select class="form-control nosearch" name="order_status" onchange="handleOrderStatus(this)">
              @foreach(orderStatus() as $key => $item)
                <option value="{{$key}}" {{$key == $order->order_status?'selected':''}}>{{$item}}</option>
              @endforeach
              <option value="cancel" {{strtolower($order->order_status) == 'cancel'?'selected':''}}>Cancel</option>
              @if(strtolower($order->order_status) == "pending")
                <option value="pending" {{strtolower($order->order_status) == 'pending'?'selected':''}}>Pending</option>
              @endif
            </select>
        </div>
      </div>
      <div class="form-group col-md-1 col-4">
        <span class="ml-2"><strong>Notify</strong></span>
          <input type="checkbox" class="toggle-check" id="notify" value="1" name="notify">
          <label class="mx-2 t-lable mt-3" for="notify"></label>
      </div>
      <div class="col-md-1 col-4">
        <div class="form-group">
            <label style="visibility: hidden">Submit</label>
            <button type="submit" class="form-control btn btn-primary text-white">Submit</button>
        </div>
      </div>
      <div class="col-12"></div>
      <div class="col-md-2 col-4">
        <div class="form-group">
            {!! Form::label('payment_status', 'Payment Status', ['class' => 'required']) !!}
            <select class="form-control nosearch" id="payment_status" name="payment_status">
              <option value="">Select</option>
              @if($order->payment_status == "pending")
                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="cod" {{ $order->payment_status == 'cod' ? 'selected' : '' }}>COD</option>
                <option value="success" {{ $order->payment_status == 'success' ? 'selected' : '' }}>Success</option>
              @endif

              @if($order->payment_status == "cod")
                <option value="cod" {{ $order->payment_status == 'cod' ? 'selected' : '' }}>COD</option>
                <option value="success" {{ $order->payment_status == 'success' ? 'selected' : '' }}>Success</option>
              @endif

              @if($order->payment_status == "success")
                <option value="success" {{ $order->payment_status == 'success' ? 'selected' : '' }}>Success</option>
              @endif
            </select>
        </div>
      </div>
      <div class="col-md-1 col-4">
        <div class="form-group">
            <label style="visibility: hidden">Submit</label>
            <button type="button" onclick="handlePaymentStatus()" class="form-control btn btn-primary text-white">Submit</button>
        </div>
      </div>
    </div>
  </div>
  @if($order->product_type == 1)
  <div id="shippment" class="tab-pane px-0">
    <div class="row">
      <div class="col-2 d-none">
        <div class="form-group">
            {!! Form::label('zipcode', 'Pincode', ['class' => 'required']) !!}
            <input type="text" readonly class="form-control nosearch" value="{{$order->zipcode}}" name="zipcode" id="zipcode">
        </div>
      </div>
      <div class="col-1 d-none">
        <div class="form-group">
          <label style="visibility: hidden">check</label>
          <input type="button" id="check-serviceablility-btn" onclick="checkPincodeServiceability()" class="form-control btn btn-primary" value="Check" name="button"/>
        </div>
      </div>
      <div class="col-9">
      </div>
      <div class="col-12 mb-3 py-2">
          <div style="border-bottom:2px solid #ccc;" class="py-2">
              @if($order && $order->consignment)
                @if($order->consignment->delivery_type == 'normal')
                  <h4 class="p-0 m-0">AWB Number: {{$order->consignment->awb_number}}</h4>
                  @if($order->consignment->courier_company_name)
                    <p class="p-0 m-0">Courier: {{$order->consignment->courier_company_name}}</p>
                  @endif
                @else
                  <h4 class="p-0 m-0">Way Bill: {{$order->consignment->waybill}}</h4>
                @endif
              @else
                <h4 class="p-0 m-0">Create Shippment</h4>
              @endif
          </div>
      </div>

      @if($order->consignment)
      <!-- Show Delivery Type (Disabled) -->
      @php
        $deliveryType = $order->consignment->delivery_type ?? 'shiprocket';
      @endphp
      <div class="col-12 mb-3">
        <div class="form-group">
          {!! Form::label('delivery_type', 'Delivery Type', ['class'=>'required']) !!}
          <select class="form-control nosearch" name="delivery_type" id="delivery_type" disabled>
            <option value="shiprocket" {{$deliveryType == 'shiprocket' ? 'selected' : ''}}>Shiprocket Delivery</option>
            <option value="normal" {{$deliveryType == 'normal' ? 'selected' : ''}}>Normal Delivery</option>
          </select>
        </div>
      </div>

      <!-- Show Data in Table Format -->
      <div class="col-12">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Field</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
            @if($deliveryType == 'shiprocket')
              <tr>
                <td><strong>Weight (gm)</strong></td>
                <td>{{$order->consignment->weight ?? 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>Length (cm)</strong></td>
                <td>{{$order->consignment->shipment_length ?? 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>Width (cm)</strong></td>
                <td>{{$order->consignment->shipment_width ?? 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>Height (cm)</strong></td>
                <td>{{$order->consignment->shipment_height ?? 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>Pickup Date</strong></td>
                <td>{{$order->consignment->pickup_date ? date('d-m-Y', strtotime($order->consignment->pickup_date)) : 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>Pickup Time</strong></td>
                <td>{{$order->consignment->pickup_time ?? 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>Shipping Mode</strong></td>
                <td>{{ucfirst($order->consignment->shipping_mode ?? 'N/A')}}</td>
              </tr>
              <tr>
                <td><strong>Fragile</strong></td>
                <td>{{$order->consignment->fragile ? 'Yes' : 'No'}}</td>
              </tr>
              <tr>
                <td><strong>Waybill</strong></td>
                <td>{{$order->consignment->waybill ?? 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>Status</strong></td>
                <td>
                  @if($order->consignment->status == 'success')
                    <span class="badge badge-success">{{ucfirst($order->consignment->status ?? 'N/A')}}</span>
                  @else
                    <span class="badge badge-danger">{{ucfirst($order->consignment->status ?? 'N/A')}}</span>
                  @endif
                </td>
              </tr>
            @else
              <tr>
                <td><strong>Courier Company Name</strong></td>
                <td>{{$order->consignment->courier_company_name ?? 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>AWB Number</strong></td>
                <td>{{$order->consignment->awb_number ?? 'N/A'}}</td>
              </tr>
              <tr>
                <td><strong>Tracking URL</strong></td>
                <td>
                  @if($order->consignment->tracking_url)
                    <a href="{{$order->consignment->tracking_url}}" target="_blank">{{$order->consignment->tracking_url}}</a>
                  @else
                    N/A
                  @endif
                </td>
              </tr>
              <tr>
                <td><strong>Status</strong></td>
                <td><span class="badge badge-success">{{ucfirst($order->consignment->status ?? 'N/A')}}</span></td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <!-- Track Button -->
      <div class="col-12 mt-3">
        @php
          $canTrack = false;
          if($order->consignment->delivery_type == 'shiprocket' || !$order->consignment->delivery_type) {
            // For Shiprocket, check if AWB is available
            $canTrack = $order->consignment->status == 'success' && ($order->consignment->awb_number || preg_match('/^[A-Z]{2}\d{9}[A-Z]{2}$/', $order->consignment->waybill));
          } else {
            // For Normal delivery, check if tracking URL is available
            $canTrack = $order->consignment->status == 'success' && !empty($order->consignment->tracking_url);
          }
        @endphp
        <button type="button" onclick="trackStatus()" id="track-status-btn" class="btn btn-primary" {{$canTrack ? '' : 'disabled'}}>
          Track Shipment
          @if(!$canTrack && ($order->consignment->delivery_type == 'shiprocket' || !$order->consignment->delivery_type))
            <small class="d-block">(Waiting for AWB assignment)</small>
          @endif
        </button>
      </div>
      @else
      <!-- Form Fields for Creating New Shipment -->
      <div class="col-12">
        <div class="form-group">
          {!! Form::label('delivery_type', 'Delivery Type', ['class'=>'required']) !!}
          <select class="form-control nosearch" name="delivery_type" id="delivery_type" onchange="toggleDeliveryFields()">
            <option value="shiprocket">Shiprocket Delivery</option>
            <option value="normal">Normal Delivery</option>
          </select>
        </div>
      </div>
      <div class="col-3 shiprocket-fields">
        <div class="form-group">
          {!! Form::label('weight', 'Weight (gm)', ['class'=>'required']) !!}
          {!! Form::number('weight', ($order->consignment ? $order->consignment->weight : '150'), ['class'=>'form-control', 'placeholder'=>'in gm', 'id'=>'weight']) !!}
        </div>
      </div>
      <div class="col-3 shiprocket-fields">
        <div class="form-group">
          {!! Form::label('shipment_length', 'Length (cm)', ['class'=>'required']) !!}
          {!! Form::number('shipment_length', ($order->consignment ? $order->consignment->shipment_length : '6'), ['class'=>'form-control', 'placeholder'=>'in cm', 'id'=>'shipment_length']) !!}
        </div>
      </div>
      <div class="col-3 shiprocket-fields">
        <div class="form-group">
          {!! Form::label('shipment_width', 'Width (cm)', ['class'=>'required']) !!}
          {!! Form::number('shipment_width', ($order->consignment ? $order->consignment->shipment_width : '5'), ['class'=>'form-control', 'placeholder'=>'in cm', 'id'=>'shipment_width']) !!}
        </div>
      </div>
      <div class="col-3 shiprocket-fields">
        <div class="form-group">
          {!! Form::label('shipment_height', 'Height (cm)', ['class'=>'required']) !!}
          {!! Form::number('shipment_height', ($order->consignment ? $order->consignment->shipment_height : '5'), ['class'=>'form-control', 'placeholder'=>'in cm', 'id'=>'shipment_height']) !!}
        </div>
      </div>
      <div class="col-3 shiprocket-fields">
        <div class="form-group">
          {!! Form::label('pickup_date', 'Pickup Date', ['class'=>'required']) !!}
          @php
            $defaultPickupDate = $order->consignment ? $order->consignment->pickup_date : date('Y-m-d', strtotime('+1 day'));
          @endphp
          {!! Form::date('pickup_date', $defaultPickupDate, ['class'=>'form-control', 'id'=>'pickup_date', 'min'=>date('Y-m-d')]) !!}
        </div>
      </div>
      <div class="col-3 shiprocket-fields">
        <div class="form-group">
          {!! Form::label('pickup_time', 'Pickup Time', ['class'=>'required']) !!}
          @php
            $defaultPickupTime = $order->consignment ? $order->consignment->pickup_time : date('H:i');
          @endphp
          {!! Form::time('pickup_time', $defaultPickupTime, ['class'=>'form-control', 'id'=>'pickup_time']) !!}
        </div>
      </div>
      <div class="col-3 shiprocket-fields">
        <div class="form-group">
            {!! Form::label('shipping_mode', 'Shipping Mode', ['class' => 'required']) !!}
            <select class="form-control nosearch" name="shipping_mode" id="shipping_mode">
              <option {{$order->consignment && $order->consignment->shipping_mode == 'express' ? 'selected' : ''}} value="express">Express</option>
              <option {{$order->consignment && $order->consignment->shipping_mode == 'air' ? 'selected' : ''}} value="air">Air</option>
            </select>
        </div>
      </div>
      <div class="col-3 shiprocket-fields">
        <div class="form-group">
            {!! Form::label('fragile', 'Fragile', ['class' => 'required']) !!}
            <select class="form-control nosearch" name="fragile" id="fragile">
              <option {{$order->consignment && $order->consignment->fragile ? '' : 'selected'}} value="0">No</option>
              <option {{$order->consignment && $order->consignment->fragile ? 'selected' : ''}} value="1">Yes</option>
            </select>
        </div>
      </div>
      <!-- Normal Delivery Fields -->
      <div class="col-4 normal-fields d-none">
        <div class="form-group">
          {!! Form::label('courier_company_name', 'Courier Company Name', ['class'=>'required']) !!}
          {!! Form::text('courier_company_name', ($order->consignment ? $order->consignment->courier_company_name : ''), ['class'=>'form-control', 'placeholder'=>'Enter courier company name', 'id'=>'courier_company_name']) !!}
        </div>
      </div>
      <div class="col-4 normal-fields d-none">
        <div class="form-group">
          {!! Form::label('awb_number', 'AWB Number', ['class'=>'required']) !!}
          {!! Form::text('awb_number', ($order->consignment ? $order->consignment->awb_number : ''), ['class'=>'form-control', 'placeholder'=>'Enter AWB number', 'id'=>'awb_number']) !!}
        </div>
      </div>
      <div class="col-4 normal-fields d-none">
        <div class="form-group">
          {!! Form::label('tracking_url', 'Tracking URL', ['class'=>'required']) !!}
          {!! Form::url('tracking_url', ($order->consignment ? $order->consignment->tracking_url : ''), ['class'=>'form-control', 'placeholder'=>'Enter tracking URL', 'id'=>'tracking_url']) !!}
        </div>
      </div>
      @endif

      @if($order && $order->consignment)
        @if(!$order->consignment->pickup_id)
          <div class="col-2 d-none">
            <div class="form-group">
                <label style="visibility: hidden">Pickup Request</label>
                <button type="button" onclick="rePickupRequestModal()" id="pickup-request-btnn" class="form-control btn btn-primary">Pickup Request Error</button>
            </div>
          </div>
        @endif
        <div class="col-2 d-none">
          <div class="form-group">
              <label style="visibility: hidden">Cancel</label>
              <button {{$order->consignment?'':'disabled'}} type="button" onclick="cancelShippment('{{$order->consignment->id}}')" id="cancel-shippment-btn" class="form-control btn btn-primary">Cancel</button>
          </div>
        </div>
      @else
        <div class="col-2">
          <div class="form-group">
              <label style="visibility: hidden">Create</label>
              <button {{$order->consignment?'disabled':''}} type="button" onclick="createShippment()" id="create-shippment-btn" class="form-control btn btn-primary">Create</button>
          </div>
        </div>
      @endif
    </div>
  </div>
  @endif
  <div id="cancel_request" class="tab-pane px-0">
    <div class="row">
      @foreach($order->cancelRequests as $index => $request)
        @if($request->status && $request->response)
          <div class="col-4">
            <div class="form-group">
                {!! Form::label('', ucfirst($request->request_type).': '.$request->reason, ['class' => '']) !!}
                <textarea name="name" readonly class="form-control" id="{{$request->request_type.$index}}" rows="3" cols="40">{{$request->message}}</textarea>
            </div>
          </div>
          <div class="col-4">
            <div class="form-group">
                {!! Form::label('', 'Admin', ['class' => '']) !!}
                <textarea name="name" readonly class="form-control" id="admin{{$index}}" rows="3" cols="40">{{$request->response->message}}</textarea>
            </div>
          </div>
          @if($request->status==1)
            <div class="col-2">
              <div class="form-group">
                  <label style="visibility: hidden">Accepted</label>
                  <button type="button" disabled class="form-control btn btn-primary">Accepted</button>
              </div>
            </div>
            <div class="col-2"></div>
          @else
            <div class="col-2">
              <div class="form-group">
                  <label style="visibility: hidden">Rejected</label>
                  <button type="button" disabled class="form-control btn btn-primary">Rejected</button>
              </div>
            </div>
            <div class="col-2"></div>
          @endif
        @else
          <div class="col-8">
            <div class="form-group">
                {!! Form::label('', ucfirst($request->request_type).': '.$request->reason, ['class' => '']) !!}
                <textarea name="name" readonly class="form-control" id="{{$request->request_type.$index}}" rows="3" cols="80">{{$request->message}}</textarea>
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
                <label style="visibility: hidden">accept</label>
                <button type="button" onclick="requestResponse('{{$request->id}}', '1', 'admin_cancel_message')" class="form-control btn btn-primary">Accept</button>
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
                <label style="visibility: hidden">reject</label>
                <button type="button" onclick="requestResponse('{{$request->id}}', '2', 'admin_cancel_message')" class="form-control btn btn-primary">Reject</button>
            </div>
          </div>
        @endif
      @endforeach
      <div class="col-8 admin_cancel_message d-none">
        <div class="form-group">
            {!! Form::label('admin_cancel_message', 'Admin Message', ['class' => '']) !!}
            <textarea name="admin_cancel_message" class="form-control" id="admin_cancel_message" rows="3" cols="80"></textarea>
        </div>
      </div>
      <div class="col-4 admin_cancel_message d-none"></div>
      <div class="col-2 admin_cancel_message d-none">
        <div class="form-group">
            <label style="visibility: hidden">submit</label>
            <button type="button" onclick="submitRequestResponse('admin_cancel_message')" class="submit-response-btn form-control btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>
  <div id="return_request" class="tab-pane px-0">
    <div class="row">
      @foreach($order->returnRequests as $index => $request)
        @if($request->status && $request->response)
          <div class="col-6">
            <div class="form-group">
                {!! Form::label('', ucfirst($request->request_type).': '.$request->reason, ['class' => '']) !!}
                <textarea name="name" readonly class="form-control" id="{{$request->request_type.$index}}" rows="3" cols="40">{{$request->message}}</textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
                @php
                $totalAmount = 0;
                @endphp
                {!! Form::label('', 'Items', ['class' => '']) !!}
                @foreach($request->orderRequestItems as $i => $item)
                  @if($item->item)
                    @php
                    $totalAmount += $item->item->total_price;
                    @endphp
                    <div class="">{{$i+1}}. Name: {{$item->item->name}}- Qty: {{$item->item->quantity}}- Total: {{$item->item->total_price}}</div>
                  @endif
                @endforeach
                <div class="">Total: {{$totalAmount}}</div>
            </div>
          </div>
          <div class="col-8">
            <div class="form-group">
                {!! Form::label('', 'Admin', ['class' => '']) !!}
                <textarea name="name" readonly class="form-control" id="admin{{$index}}" rows="3" cols="40">{{$request->response->message}}</textarea>
            </div>
          </div>
          @if($request->status==1)
            <div class="col-2">
              <div class="form-group">
                  <label style="visibility: hidden">Accepted</label>
                  <button type="button" disabled class="form-control btn btn-primary">Accepted</button>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group mt-4">
                <span class="ml-2"><strong>Received</strong></span>
                  <input type="checkbox" class="toggle-check" id="received" onchange="orderReturnReceived('{{$request->id}}')" {{$order && $order->received ? 'checked' : ''}} value="1" name="received">
                  <label class="mx-2 t-lable" for="received"></label>
              </div>
            </div>
          @else
            <div class="col-2">
              <div class="form-group">
                  <label style="visibility: hidden">Rejected</label>
                  <button type="button" disabled class="form-control btn btn-primary">Rejected</button>
              </div>
            </div>
            <div class="col-2"></div>
          @endif
        @else
          <div class="col-6">
            <div class="form-group">
                {!! Form::label('', ucfirst($request->request_type).': '.$request->reason, ['class' => '']) !!}
                <textarea name="name" readonly class="form-control" id="{{$request->request_type.$index}}" rows="3" cols="40">{{$request->message}}</textarea>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
                @php
                $totalAmount = 0;
                @endphp
                {!! Form::label('', 'Items', ['class' => '']) !!}
                @foreach($request->orderRequestItems as $i => $item)
                  @if($item->item)
                    @php
                    $totalAmount += $item->item->total_price;
                    @endphp
                    <div class="">{{$i+1}}. Name: {{$item->item->name}}- Qty: {{$item->item->quantity}}- Total: {{$item->item->total_price}}</div>
                  @endif
                @endforeach
                <div class="">Total: {{sprintf('%.2f', $totalAmount)}}</div>
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
                <label style="visibility: hidden">accept</label>
                <button type="button" onclick="requestResponse('{{$request->id}}', '1', 'admin_return_message')" class="form-control btn btn-primary">Accept</button>
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
                <label style="visibility: hidden">reject</label>
                <button type="button" onclick="requestResponse('{{$request->id}}', '2', 'admin_return_message')" class="form-control btn btn-primary">Reject</button>
            </div>
          </div>
          <div class="col-8"></div>
        @endif
      @endforeach
      @php
       $returnConsignment=false;
       if($order->returnConsignment){
         $returnConsignment=true;
       }
      @endphp
      <div class="col-8 admin_return_message {{$returnConsignment?'':'d-none'}}">
        <div class="form-group">
            {!! Form::label('admin_message', 'Admin Message', ['class' => '']) !!}
            <textarea name="admin_return_message" class="form-control" id="admin_return_message" rows="3" cols="80"></textarea>
        </div>
      </div>
      <div class="col-2 admin_return_message {{$returnConsignment?'':'d-none'}}">
        <div class="form-group return_pickup_time {{$returnConsignment?'':'d-none'}}">
          {!! Form::label('return_pickup_time', 'Pickup Time', ['class'=>'required']) !!}
          {!! Form::time('return_pickup_time', ($returnConsignment ? $order->returnConsignment->pickup_time : ''), ['class'=>'form-control', 'id'=>'return_pickup_time']) !!}
        </div>
      </div>
      <div class="col-2 admin_return_message {{$returnConsignment?'':'d-none'}}">
        <div class="form-group return_pickup_date {{$returnConsignment?'':'d-none'}}">
          {!! Form::label('return_pickup_date', 'Pickup Date', ['class'=>'required']) !!}
          {!! Form::date('return_pickup_date', ($returnConsignment ? $order->returnConsignment->pickup_date : '') , ['class'=>'form-control', 'id'=>'return_pickup_date', 'min'=>date('Y-m-d')]) !!}
        </div>
      </div>
      @if($returnConsignment)
        <div class="col-2">
            <div class="form-group">
                <label style="visibility: hidden">Track</label>
                <button type="button" onclick="trackReturnStatus()" id="track-return-status-btn" class="form-control btn btn-primary">Track</button>
            </div>
        </div>
      @else
      <div class="col-2 admin_return_message d-none">
        <div class="form-group">
            <label style="visibility: hidden">submit</label>
            <button type="button" onclick="submitRequestResponse('admin_return_message')" class="submit-response-btn form-control btn btn-primary">Submit</button>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>

<!-- Tracking Side Panel -->
<div id="trackingSidePanel" class="tracking-side-panel">
  <div class="tracking-panel-overlay" onclick="closeTrackingPanel()"></div>
  <div class="tracking-panel-content">
    <div class="tracking-panel-header">
      <div>
        <h2 class="tracking-panel-title">Order Details</h2>
        <p class="tracking-panel-subtitle" id="tracking-order-number">Tracking and action history for <span id="tracking-order-id"></span></p>
      </div>
      <button type="button" class="tracking-panel-close" onclick="closeTrackingPanel()">
        <span>&times;</span>
      </button>
    </div>

    <div class="tracking-panel-body">
      <div class="order-info-section">
        <div class="order-info-item">
          <span class="order-info-label">Order Number:</span>
          <span class="order-info-value" id="tracking-order-num"></span>
        </div>
        <div class="order-info-item">
          <span class="order-info-label">Invoice Number:</span>
          <span class="order-info-value" id="tracking-invoice-num"></span>
        </div>
        <div class="order-info-item">
          <span class="order-info-label">Amount:</span>
          <span class="order-info-value" id="tracking-amount"></span>
        </div>
        <div class="order-info-item">
          <span class="order-info-label">Customer:</span>
          <span class="order-info-value" id="tracking-customer"></span>
        </div>
        <div class="order-info-item">
          <span class="order-info-label">Payment:</span>
          <span class="order-info-badge payment-success" id="tracking-payment"></span>
        </div>
        <div class="order-info-item">
          <span class="order-info-label">Status:</span>
          <span class="order-info-badge status-delivered" id="tracking-status-badge"></span>
        </div>
      </div>

      <div class="action-history-section">
        <h3 class="action-history-title">Action History</h3>
        <div id="tracking-timeline" class="tracking-timeline">
          <!-- Timeline items will be dynamically inserted here -->
        </div>
      </div>
    </div>
  </div>
</div>

@if($order->consignment)
<div class="modal fade" id="pickupRequestErrorModal" tabindex="-1" role="dialog" aria-labelledby="pickupRequestErrorModal1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pickupRequestErrorModal1">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!! $order->consignment->pickup_api_response !!}
      </div>
      <div class="modal-footer">
        <button type="button" onclick="rePickupRequest('{{$order->consignment->id}}')" id="pickup-request-btn" class="form-control btn btn-primary">Request For Pickup</button>
      </div>
    </div>
  </div>
</div>
@endif
<!-- modal end -->

<!-- modal start -->
<div class="modal fade" id="trackReturnConsignmentModal" tabindex="-1" role="dialog" aria-labelledby="trackReturnConsignmentModal1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="trackReturnConsignmentModal1">Tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if($order->returnConsignment)
          @foreach($order->returnConsignment->trackConsignment as $key => $item)
            <div class="row">
              <div class="col-6">Status: {{$item->status}}</div>
              <div class="col-6">Date: {{date('d-m-Y h:i a', strtotime($item->date.' '.$item->time))}}</div>
              <div class="col-6">Location: {{$item->status_location}}</div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
</div>
<!-- modal end -->

  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
  function handleOrderStatus(that){
    let v = $(that).find(':selected').val();
    if(v=='cancel'){
      $('#cancel_message_div').removeClass('d-none')
    }
  }

  function checkPincodeServiceability(){
    let pincode = $('#zipcode').val();
    $('#check-serviceablility-btn').attr('disabled', true);
    let url = @json(route('admin.pincode.serviceabilty'));
    $.ajax({
      url:url,
      method:"POST",
      data:{
        pincode:pincode
      },
      headers:{
        'X-CSRF-TOKEN':@json(csrf_token())
      },
      success:function(response){
        toastr.success(response.message)
      },
      error:function(error){
        error = error.responseJSON.errors;
        for (var key in error) {
          toastr.error(error[key][0]);
        }
      },
    });
    $('#check-serviceablility-btn').attr('disabled', false);
  }

  function toggleDeliveryFields(){
    var deliveryType = $('#delivery_type').val();
    if(deliveryType == 'shiprocket'){
      $('.shiprocket-fields').show();
      $('.normal-fields').addClass('d-none');
    } else {
      $('.shiprocket-fields').hide();
      $('.normal-fields').removeClass('d-none');
    }
  }

  function createShippment(){
    $('#create-shippment-btn').attr('disabled', true);
    let url = @json(route('admin.create.shippment'));
    var deliveryType = $('#delivery_type').val() || 'shiprocket';

    var data = {
      order_id:@json($order->id),
      delivery_type: deliveryType,
    };

    if(deliveryType == 'shiprocket'){
      data.weight = $('#weight').val();
      data.shipment_length = $('#shipment_length').val();
      data.shipment_width = $('#shipment_width').val();
      data.shipment_height = $('#shipment_height').val();
      data.pickup_time = $('#pickup_time').val();
      data.pickup_date = $('#pickup_date').val();
      data.shipping_mode = $('#shipping_mode').val();
      data.fragile = $('#fragile').val();
      data.return_pickup_time = $('#return_pickup_time').val();
      data.return_pickup_date = $('#return_pickup_date').val();
    } else {
      data.courier_company_name = $('#courier_company_name').val();
      data.awb_number = $('#awb_number').val();
      data.tracking_url = $('#tracking_url').val();
    }

    $.ajax({
      url:url,
      method:"POST",
      data:data,
      headers:{
        'X-CSRF-TOKEN':@json(csrf_token())
      },
      success:function(response){
        toastr.success(response.message);
        window.location.reload();
      },
      error:function(error){
      $('#create-shippment-btn').attr('disabled', false);
        error = error.responseJSON.errors;
        for (var key in error) {
          toastr.error(error[key][0]);
        }
      },
    });
  }

  function trackStatus(){
    @if($order->consignment)
      const consignmentId = {{ $order->consignment->id }};
      openTrackingPanel();
      fetchTrackingData(consignmentId);
    @else
      toastr.error('No tracking information available');
    @endif
  }

  function openTrackingPanel(){
    // Set order info
    $('#tracking-order-id').text('{{ $order->order_number }}');
    $('#tracking-order-num').text('{{ $order->order_number }}');
    @if($order->invoice)
      $('#tracking-invoice-num').text('{{ $order->invoice->prefix.$order->invoice->number }}');
    @else
      $('#tracking-invoice-num').text('N/A');
    @endif
    $('#tracking-amount').text('₹{{ sprintf("%.2f", $order->total_price) }}');
    $('#tracking-customer').text('{{ $order->full_name }}');
    $('#tracking-payment').text('{{ ucfirst($order->payment_status) }}');
    $('#tracking-status-badge').text('{{ ucfirst($order->order_status) }}');

    $('#trackingSidePanel').addClass('active');
    $('body').css('overflow', 'hidden');
  }

  function closeTrackingPanel(){
    $('#trackingSidePanel').removeClass('active');
    $('body').css('overflow', 'auto');
  }

  function fetchTrackingData(consignmentId){
    $('#tracking-timeline').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin"></i> Loading tracking data...</div>');

    let url = @json(route('admin.track.order'));
    $.ajax({
      url: url,
      method: "POST",
      data: {
        consignment_id: consignmentId
      },
      headers: {
        'X-CSRF-TOKEN': @json(csrf_token())
      },
      success: function(response){
        if(response && response.success !== false){
          if(response.data){
            // Handle Shiprocket API response structure
            if(response.data.tracking_data && response.data.tracking_data.track_status){
              displayTrackingTimeline(response.data.tracking_data);
            } else if(response.data.track_status){
              // Alternative response structure
              displayTrackingTimeline({ track_status: response.data.track_status });
            } else if(response.tracking_data && response.tracking_data.track_status){
              displayTrackingTimeline(response.tracking_data);
            } else if(response.track_status){
              displayTrackingTimeline({ track_status: response.track_status });
            } else {
              // Normal delivery - show tracking URL
              if(response.tracking_url){
                $('#tracking-timeline').html('<div class="text-center p-4"><p>Tracking URL:</p><p><a href="' + response.tracking_url + '" target="_blank" class="btn btn-primary">View Tracking</a></p></div>');
              } else {
                $('#tracking-timeline').html('<div class="text-center p-4 text-muted">No tracking data available</div>');
              }
            }
          } else if(response.track_status){
            displayTrackingTimeline({ track_status: response.track_status });
          } else if(response.tracking_url){
            // Normal delivery with tracking URL
            $('#tracking-timeline').html('<div class="text-center p-4"><p>Tracking URL:</p><p><a href="' + response.tracking_url + '" target="_blank" class="btn btn-primary">View Tracking</a></p></div>');
          } else {
            $('#tracking-timeline').html('<div class="text-center p-4 text-muted">No tracking data available</div>');
          }
        } else {
          $('#tracking-timeline').html('<div class="text-center p-4 text-danger">' + (response.message || 'No tracking data available') + '</div>');
          if(response.message){
            toastr.warning(response.message);
          }
        }
      },
      error: function(error){
        $('#tracking-timeline').html('<div class="text-center p-4 text-danger">Error loading tracking data</div>');
        if(error.responseJSON && error.responseJSON.message){
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error('Failed to load tracking data');
        }
      }
    });
  }

  function displayTrackingTimeline(trackingData){
    let timelineHtml = '';

    if(trackingData && trackingData.track_status && trackingData.track_status.length > 0){
      const statuses = trackingData.track_status;
      const statusMap = {
        'Delivered': { icon: 'check-circle', color: 'green', bgColor: '#d4edda' },
        'Out for Delivery': { icon: 'truck', color: 'blue', bgColor: '#cfe2ff' },
        'In Transit': { icon: 'truck', color: 'blue', bgColor: '#cfe2ff' },
        'Picked Up': { icon: 'box', color: 'purple', bgColor: '#e2d9f3' },
        'Shipment Created': { icon: 'file-alt', color: 'grey', bgColor: '#e9ecef' },
        'Order Placed': { icon: 'shopping-cart', color: 'grey', bgColor: '#e9ecef' }
      };

      statuses.forEach((status, index) => {
        const statusText = status.status || status.current_status || '';
        const statusInfo = statusMap[statusText] || { icon: 'circle', color: 'grey', bgColor: '#e9ecef' };
        const isLast = index === statuses.length - 1;

        const dateTime = status.status_date_time || status.datetime || '';
        const location = status.location || status.current_location || '';
        const courier = status.courier_name || status.courier || '';

        timelineHtml += `
          <div class="timeline-item ${isLast ? 'active' : ''}">
            <div class="timeline-marker timeline-marker-${statusInfo.color}">
              <i class="fas fa-${statusInfo.icon}"></i>
            </div>
            <div class="timeline-content">
              <div class="timeline-card">
                <h4 class="timeline-title">${statusText}</h4>
                <p class="timeline-description">${getStatusDescription(statusText)}</p>
                <div class="timeline-details">
                  ${dateTime ? `<div class="timeline-detail-item"><i class="far fa-clock"></i> ${formatDateTime(dateTime)}</div>` : ''}
                  ${location ? `<div class="timeline-detail-item"><i class="fas fa-map-marker-alt"></i> ${location}</div>` : ''}
                  ${courier ? `<div class="timeline-detail-item"><i class="fas fa-user"></i> ${courier}</div>` : ''}
                </div>
              </div>
            </div>
          </div>
        `;
      });
    } else {
      timelineHtml = '<div class="text-center p-4 text-muted">No tracking updates available</div>';
    }

    $('#tracking-timeline').html(timelineHtml);
  }

  function getStatusDescription(status){
    const descriptions = {
      'Delivered': 'Package delivered successfully to customer',
      'Out for Delivery': 'Package is out for delivery',
      'In Transit': 'Package is in transit',
      'Picked Up': 'Package picked up from warehouse',
      'Shipment Created': 'Shipment label generated and order ready for pickup',
      'Order Placed': 'Order has been placed'
    };
    return descriptions[status] || 'Status update';
  }

  function formatDateTime(dateTimeStr){
    if(!dateTimeStr) return '';
    const date = new Date(dateTimeStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const ampm = date.getHours() >= 12 ? 'PM' : 'AM';
    const displayHours = date.getHours() % 12 || 12;
    return `${day}/${month}/${year} ${displayHours}:${minutes} ${ampm}`;
  }

  // Close panel on ESC key
  $(document).keydown(function(e) {
    if (e.key === "Escape" && $('#trackingSidePanel').hasClass('active')) {
      closeTrackingPanel();
    }
  });
  function trackReturnStatus(){
    $('#trackReturnConsignmentModal').modal('show')
  }

  function cancelShippment(consignment_id){
    $('#cancel-shippment-btn').attr('disabled', true);
    let url = @json(route('admin.cancel.shippment'));
    $.ajax({
      url:url,
      method:"POST",
      data:{
        consignment_id:consignment_id,
      },
      headers:{
        'X-CSRF-TOKEN':@json(csrf_token())
      },
      success:function(response){
        toastr.success(response.message);
        window.location.reload();
      },
      error:function(error){
      $('#cancel-shippment-btn').attr('disabled', false);
        error = error.responseJSON.errors;
        for (var key in error) {
          toastr.error(error[key][0]);
        }
      },
    });
  }

  var request_id = 0;
  var request_status = 0;

  function requestResponse(id, status, _id){
    request_id = id;
    request_status = status;
    $('.'+_id).addClass('d-none');
    $('.return_pickup_time').addClass('d-none')
    $('.return_pickup_date').addClass('d-none')
    $('#'+_id).val('');
    $('.'+_id).removeClass('d-none');
    if(status=='1'){
      $('.return_pickup_time').removeClass('d-none')
      $('.return_pickup_date').removeClass('d-none')
    }
  }

  function submitRequestResponse(message_textarea_id){
    $('.submit-response-btn').attr('disabled', true);
    let url = @json(route('admin.order.request.response.submit'));
    $.ajax({
      url:url,
      method:"POST",
      data:{
        request_id:request_id,
        request_status:request_status,
        message:$('#'+message_textarea_id).val(),
        pickup_time:$('#return_pickup_time').val(),
        pickup_date:$('#return_pickup_date').val(),
      },
      headers:{
        'X-CSRF-TOKEN':@json(csrf_token())
      },
      success:function(response){
        toastr.success(response.message);
        window.location.reload();
      },
      error:function(error){
        $('.submit-response-btn').attr('disabled', false);
        error = error.responseJSON.errors;
        for(var key in error){
          toastr.error(error[key][0]);
        }
      },
    });
  }

  function orderReturnReceived(request_id){
    let url = @json(route('admin.order.return.received'));
    $.ajax({
      url:url,
      method:"POST",
      data:{
        request_id:request_id,
      },
      headers:{
        'X-CSRF-TOKEN':@json(csrf_token())
      },
      success:function(response){
        toastr.success(response.message);
        window.location.reload();
      },
      error:function(error){
        error = error.responseJSON.errors;
        for(var key in error){
          toastr.error(error[key][0]);
        }
        if(error['received']){
          $('#received').prop('checked', true);
        }
      },
    });
  }

  function rePickupRequest(consignment_id){
    let url = @json(route('admin.order.repickuprequest'));
    $.ajax({
      url:url,
      method:"POST",
      data:{
        consignment_id:consignment_id,
        pickup_date:$('#pickup_date').val(),
        pickup_time:$('#pickup_time').val()
      },
      headers:{
        'X-CSRF-TOKEN':@json(csrf_token())
      },
      success:function(response){
        toastr.success(response.message);
        setTimeout(function(){
          window.location.reload();
        }, 2000);
      },
      error:function(error){
        if(error.responseJSON.errors){
          error = error.responseJSON.errors;
          for(var key in error){
            toastr.error(error[key][0]);
          }
        }else if(error.responseJSON.message){
          error = error.responseJSON.message;
          toastr.error(error);
        }
        setTimeout(function(){
          window.location.reload();
        }, 2000);
      }
    });
  }

  function handlePaymentStatus(){
    $.ajax({
      url:"{{ route('admin.order.paymentstatus') }}",
      method:"POST",
      data:{
        order_id:"{{ $order->id }}",
        payment_status:$('#payment_status').val()
      },
      headers:{
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      success:function(response){
        toastr.success(response.message);
        setTimeout(function(){
          window.location.reload();
        }, 2000);
      },
      error:function(error){
        let errors = error.responseJSON.errors;
        if(errors){
          for(var key in errors){
            toastr.error(errors[key][0]);
          }
        }else{
          let message = error.responseJSON.message;
          if(message){
            toastr.error(message);
          }
        }
      }
    });
  }

  function rePickupRequestModal(){
    $('#pickupRequestErrorModal').modal('show')
  }

  function copyCheckoutUrl(that){
    navigator.clipboard.writeText($(that).attr("data-checkout-url"));
    toastr.success("copied");
  }
</script>

<style>
  /* Tracking Side Panel Styles */
  .tracking-side-panel {
    position: fixed;
    top: 0;
    right: -100%;
    width: 100%;
    max-width: 600px;
    height: 100vh;
    z-index: 9999;
    transition: right 0.3s ease-in-out;
  }

  .tracking-side-panel.active {
    right: 0;
  }

  .tracking-panel-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9998;
    display: none;
  }

  .tracking-side-panel.active .tracking-panel-overlay {
    display: block;
  }

  .tracking-panel-content {
    position: relative;
    background: #ffffff;
    height: 100%;
    overflow-y: auto;
    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
    z-index: 9999;
  }

  .tracking-panel-header {
    padding: 24px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    background: #ffffff;
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .tracking-panel-title {
    font-size: 24px;
    font-weight: 700;
    color: #000000;
    margin: 0 0 4px 0;
  }

  .tracking-panel-subtitle {
    font-size: 14px;
    color: #6c757d;
    margin: 0;
  }

  .tracking-panel-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #6c757d;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
  }

  .tracking-panel-close:hover {
    color: #000000;
  }

  .tracking-panel-body {
    padding: 24px;
  }

  .order-info-section {
    margin-bottom: 32px;
  }

  .order-info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .order-info-item:last-child {
    border-bottom: none;
  }

  .order-info-label {
    font-weight: 500;
    color: #6c757d;
  }

  .order-info-value {
    font-weight: 600;
    color: #000000;
  }

  .order-info-badge {
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #ffffff;
  }

  .payment-success {
    background-color: #0d6efd;
  }

  .status-delivered {
    background-color: #198754;
  }

  .action-history-section {
    margin-top: 32px;
  }

  .action-history-title {
    font-size: 20px;
    font-weight: 700;
    color: #000000;
    margin-bottom: 24px;
  }

  .tracking-timeline {
    position: relative;
    padding-left: 40px;
  }

  .tracking-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
  }

  .timeline-item {
    position: relative;
    margin-bottom: 24px;
  }

  .timeline-item:last-child {
    margin-bottom: 0;
  }

  .timeline-marker {
    position: absolute;
    left: -25px;
    top: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 14px;
    z-index: 2;
  }

  .timeline-marker-green {
    background-color: #198754;
  }

  .timeline-marker-blue {
    background-color: #0d6efd;
  }

  .timeline-marker-purple {
    background-color: #6f42c1;
  }

  .timeline-marker-grey {
    background-color: #6c757d;
  }

  .timeline-content {
    margin-left: 0;
  }

  .timeline-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
    border: 1px solid #e0e0e0;
  }

  .timeline-title {
    font-size: 16px;
    font-weight: 700;
    color: #000000;
    margin: 0 0 8px 0;
  }

  .timeline-description {
    font-size: 14px;
    color: #6c757d;
    margin: 0 0 12px 0;
  }

  .timeline-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .timeline-detail-item {
    font-size: 13px;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .timeline-detail-item i {
    width: 16px;
    color: #6c757d;
  }

  @media (max-width: 768px) {
    .tracking-side-panel {
      max-width: 100%;
    }
  }
</style>
@endpush
