
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
          <tr><td>Name</td><td>: </td><td>{{$item->name}}</td></tr>
          <tr><td>Email</td><td>: </td><td>{{$item->email}}</td></tr>
          <tr><td>Phone</td><td>: </td><td>{{$item->phone}}</td></tr>
          @if($item->language)
            <tr><td>Language</td><td>: </td><td>{{$item->language}}</td></tr>
          @endif
          @if($item->service_type)
            <tr><td>Service</td><td>: </td><td>{{$item->service_type}}</td></tr>
          @endif
          @if($item->message)
            <tr><td>Message</td><td>: </td><td>{{$item->message}}</td></tr>
          @endif
          @if($item->country)
            <tr><td>Country</td><td>: </td><td>{{$item->country}}</td></tr>
          @endif
          @if($item->state)
            <tr><td>State</td><td>: </td><td>{{$item->state}}</td></tr>
          @endif
          @if($item->city)
            <tr><td>City</td><td>: </td><td>{{$item->city}}</td></tr>
          @endif
          @if($item->pincode)
            <tr><td>Pincode</td><td>: </td><td>{{$item->pincode}}</td></tr>
          @endif
      </table>
  </div>
</div>
@endsection

@push('scripts')

@endpush
