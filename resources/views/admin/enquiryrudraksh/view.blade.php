
@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Details</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
  <a href="{{route('admin.'.$nav.'.index')}}">
    <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </a>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
      <table class="table table-striped">
          <tr><td>Name</td><td>: </td><td>{{$item->fullName}}</td></tr>
          <tr><td>Email</td><td>: </td><td>{{$item->email}}</td></tr>
          <tr><td>Phone</td><td>: </td><td>{{$item->mobile}}</td></tr>
          <tr><td>DOB</td><td>: </td><td>{{ \Carbon\Carbon::parse($item->dob)->format('d F Y') }}</td></tr>
          <tr><td>Birth Time</td><td>: </td><td>{{ \Carbon\Carbon::parse($item->birthTime)->format('h:i A') }}</td></tr>
          <tr><td>Birth Place</td><td>: </td><td>{{$item->birthPlace}}</td></tr>
          <tr><td>Amount</td><td>: </td><td>{{$item->amount}}</td></tr>
          <tr><td>Payment Status</td><td>: </td><td>{{$item->payment_status}}</td></tr>
          <tr><td>Message</td><td>: </td><td>{{$item->message}}</td></tr>
      </table>
  </div>
</div>
@endsection

@push('scripts')

@endpush
