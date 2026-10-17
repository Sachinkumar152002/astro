
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.landing.page.submit', [$slug]], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{ucwords(str_replace('-', ' ', $slug))}} Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary d-none"><i class="bi bi-floppy"></i></button>
     <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  @php
  $data = json_decode($item->id, true);
  @endphp

  <div id="information_tab" class="tab-pane fade in active show">
    <table cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
          <tr><th>ID :</th><td>{{$item->id}}</td></tr>
          <tr><td>Name</td><td>: </td><td>{{$item->fullName}}</td></tr>
          <tr><td>Email</td><td>: </td><td>{{$item->email}}</td></tr>
          <tr><td>Phone</td><td>: </td><td>{{$item->mobile}}</td></tr>
          <tr><td>DOB</td><td>: </td><td>{{ \Carbon\Carbon::parse($item->dob)->format('d F Y') }}</td></tr>
          <tr><td>Birth Time</td><td>: </td><td>{{ \Carbon\Carbon::parse($item->birthTime)->format('h:i A') }}</td></tr>
          <tr><td>Birth Place</td><td>: </td><td>{{$item->birthPlace}}</td></tr>
          <tr><td>Amount</td><td>: </td><td>{{$item->amount}}</td></tr>
          <tr>
              <td>Payment Status</td>
              <td>:</td>
              <td>
                  @if($item->payment_status == 0)
                      Pending
                  @elseif($item->payment_status == 1)
                      Success
                  @elseif($item->payment_status == 2)
                      Failed
                  @else
                      Unknown
                  @endif
              </td>
          </tr>
          <tr><td>Message</td><td>: </td><td>{{$item->message}}</td></tr>
    </table>
  </div>
  {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
