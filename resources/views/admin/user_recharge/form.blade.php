@extends('layouts.admin')


@section('content')

<h1 class="text-primary float-left">User Recharge Details</h1>
<div class="clearfix"></div>

{!! Form::model($data, ['route' => 'admin.user_recharge.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($data)
{{ Form::hidden('id', old('id')) }}
@endif
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>

<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <table>
      <tbody>
        <tr>
          <th>Recharge Number :</th>
          <td>{{$data->my_txn_id}}</td>
        </tr>
        <tr>
          <th>Invoice Number :</th>
          @if($data->invoice)
          <td>
            <a href="{{route('admin.invoice.download', [$data->invoice->id])}}" class="text-primary" targe="_blank">
              {{$data->invoice->prefix.$data->invoice->number}}
            </a>
          </td>
          @else
          <td>-</td>
          @endif
        </tr>
        <tr>
          <th>Payment Id :</th>
          <td>{{$data->payment_id}}</td>
        </tr>
        <tr>
          <th>Payment Mode :</th>
          <td>{{$data->payment_method}}</td>
        </tr>
        <tr>
          <th>Payment Date & Time :</th>
          <td>{{ISTDatetimeFormat($data->payment_date_time)}}</td>
        </tr>
        <tr>
          <th>Payment Status :</th>
          <td>{{$data->payment_status}}</td>
        </tr>
        <tr>
          <th>User :</th>
          <td>{{$data->billing_full_name}}</td>
        </tr>
        <tr>
          <th>Email :</th>
          <td>{{$data->billing_email}}</td>
        </tr>
        <tr>
          <th>Phone :</th>
          <td>{{$data->billing_phone_number}}</td>
        </tr>
        <tr>
          <th>Address :</th>
          <td>{{$data->billing_address}}</td>
        </tr>
        <tr>
          <th>City :</th>
          <td>{{$data->billing_city}}, {{$data->billing_zipcode}}</td>
        </tr>
        <tr>
          <th>State :</th>
          <td>{{$data->billing_state}}</td>
        </tr>
        <tr>
          <th>Country :</th>
          <td>{{$data->billing_country}}</td>
        </tr>
        <tr>
          <th>Coupon Code :</th>
          <td>{{$data->coupon_code}}</td>
        </tr>
        <tr>
          <th>Sub Total :</th>
          <td>₹ {{$data->amount}}</td>
        </tr>
        {{--
        <tr>
          <th>Discount :</th>
          <td>- ₹ {{$data->discount}}</td>
        </tr>
        --}}
        <tr>
          <th>Coupon :</th>
          <td>- ₹ {{$data->coupon_amount}}</td>
        </tr>
        <tr>
          <th>Gst & Taxes :</th>
          <td>+ ₹ {{$data->cgst_amount+$data->sgst_amount+$data->igst_amount}}</td>
        </tr>
        @if($data->cgst_amount && $data->sgst_amount)
        <tr>
          <th>CGST :</th>
          <td>₹ {{$data->cgst_amount}}</td>
        </tr>
        <tr>
          <th>SGST :</th>
          <td>₹ {{$data->sgst_amount}}</td>
        </tr>
        @endif
        <tr>
          <th>Amount :</th>
          <td>₹ {{$data->total_amount}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
{{--
<div class="text-center">
  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
</div>
--}}

  {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>

@endpush
