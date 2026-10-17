
@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">

      <!-- <a class="btn btn-primary" href="{{route('admin.'.$nav.'.index')}}"><i class="bi bi-escape text-white"></i></a> -->
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
      <table class="table table-striped">
          <tr><td>Name</td><td>:</td><td>{{$item->name}}</td></tr>
          <tr><td>Phone</td><td>:</td><td>{{$item->phone}}</td></tr>
          <tr><td>Email</td><td>:</td><td>{{$item->email}}</td></tr>
          <tr><td>Status</td><td>:</td><td>{!! statusBadge($item->status) !!}</td></tr>
      </table>
  </div>
</div>
@endsection

@push('scripts')

@endpush
