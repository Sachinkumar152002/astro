@extends('layouts.admin')


@section('content')

<h1 class="text-primary float-left">Order Details</h1>
<div class="clearfix"></div>

{!! Form::model($order, ['route' => 'admin.order.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($order)
{{ Form::hidden('id', old('id')) }}
@endif
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#order_detail">Order</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#item_detail">Items</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#update">Update</a></li>
</ul>

<div class="tab-content">
  <div id="order_detail" class="tab-pane fade in active show">
    <table>
      <tbody>
        <tr>
          <th>Order Number :</th>
          <td>{{$order->order_number}}</td>
        </tr>
        <tr>
          <th>Invoice Number :</th>
          @if($order->invoice)
          <td>{{$order->invoice->prefix.$order->invoice->number}}</td>
          @else
          <td>-</td>
          @endif
        </tr>
        <tr>
          <th>Payment Id :</th>
          <td>{{$order->payment_id}}</td>
        </tr>
        <tr>
          <th>Payment Mode :</th>
          <td>{{$order->payment_method}}</td>
        </tr>
        <tr>
          <th>Payment Date & Time :</th>
          <td>{{ISTDatetimeFormat($order->payment_date_time)}}</td>
        </tr>
        <tr>
          <th>Payment Status :</th>
          <td>{{$order->payment_status}}</td>
        </tr>
        <tr>
          <th>Order Status :</th>
          <td>{{$order->order_status}}</td>
        </tr>
        <tr>
          <th>User :</th>
          <td>{{$order->full_name}}</td>
        </tr>
        <tr>
          <th>Email :</th>
          <td>{{$order->email}}</td>
        </tr>
        <tr>
          <th>Phone :</th>
          <td>{{$order->phone_number}}</td>
        </tr>
        <tr>
          <th>Address :</th>
          <td>{{$order->address}}</td>
        </tr>
        <tr>
          <th>City :</th>
          <td>{{$order->city}}, {{$order->zipcode}}</td>
        </tr>
        <tr>
          <th>State :</th>
          <td>{{$order->state}}</td>
        </tr>
        <tr>
          <th>Country :</th>
          <td>{{$order->country}}</td>
        </tr>
        <tr>
          <th>Coupon :</th>
          <td>{{$order->coupon}}</td>
        </tr>
        <tr>
          <th>Sub Total :</th>
          <td>₹ {{$order->net_total}}</td>
        </tr>
        <tr>
          <th>Discount :</th>
          <td>- ₹ {{$order->discount}}</td>
        </tr>
        <tr>
          <th>Coupon :</th>
          <td>- ₹ {{$order->coupon_amount}}</td>
        </tr>
        <tr>
          <th>Gst & Taxes :</th>
          <td>+ ₹ {{$order->igst+$order->cgst+$order->sgst}}</td>
        </tr>
        @if($order->cgst && $order->sgst)
        <tr>
          <th>CGST :</th>
          <td>₹ {{$order->cgst}}</td>
        </tr>
        <tr>
          <th>SGST :</th>
          <td>₹ {{$order->sgst}}</td>
        </tr>
        @endif
        <tr>
          <th>Shipping :</th>
          <td>+ ₹ {{$order->shipping_price}}</td>
        </tr>
        <tr>
          <th>Amount :</th>
          <td>₹ {{$order->total_price}}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="item_detail" class="tab-pane">
    <table>
      <tbody>
        @foreach($order->orderItems as $key => $item)
        <tr>
          <th>Image :</th>
          <td><img src="{{$item->image}}"></td>
        </tr>
        <tr>
          <th>Item :</th>
          <td>{{$item->name}}</td>
        </tr>
        <tr>
          <th>Quantity :</th>
          <td>{{$item->quantity}}</td>
        </tr>
        <tr>
          <th>Amount :</th>
          <td>₹ {{$item->price}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div id="update" class="tab-pane">
    <div class="col-4">
      <div class="form-group">
          {!! Form::label('order_status', 'Order Status', ['class' => 'required']) !!}
          <select class="form-control nosearch" name="order_status">
            @foreach(orderStatus() as $key => $item)
              <option value="{{$item}}" {{$item == $order->order_status?'selected':''}}>{{$item}}</option>
            @endforeach
            <option value="pending" {{strtolower($order->order_status) == 'pending'?'selected':''}}>Pending</option>
            <option value="cancel" {{strtolower($order->order_status) == 'cancel'?'selected':''}}>Cancel</option>
          </select>
      </div>
    </div>
    <div class="form-group col-4">
      <span class="ml-2"><strong>Notify</strong></span>
        <input type="checkbox" class="toggle-check" id="notify" value="1" name="notify">
        <label class="mx-2 t-lable" for="notify"></label>
    </div>
  </div>
</div>
<div class="text-center">
  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
</div>

  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>

@endpush
