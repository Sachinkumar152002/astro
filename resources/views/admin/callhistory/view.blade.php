
@extends('layouts.admin')
@section('content')
<h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
<div class="clearfix"></div>

<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
      <table class="table table-striped">

          <tr><td>From number</td><td>:</td><td>{{$item->from_number}}</td></tr>
          <tr><td>To number</td><td>:</td><td>{{$item->to_number}}</td></tr>
          <tr><td>Call duration in sec</td><td>:</td><td>{{$item->call_duration_in_sec}}</td></tr>
          <tr><td>Call charges</td><td>:</td><td>{{$item->call_charges}}</td></tr>
          <tr><td>Total charges</td><td>:</td><td>{{$item->total_charges}}</td></tr>
          <tr><td>Start time</td><td>:</td><td>{{$item->start_time}}</td></tr>
          <tr><td>End time</td><td>:</td><td>{{$item->end_time}}</td></tr>
          <tr><td>Call Sid</td><td>:</td><td>{{$item->callSid}}</td></tr>
          <tr><td>Call Url</td><td>:</td><td>{{$item->callUri}}</td></tr>
          <tr><td>Call status</td><td>:</td><td>{{$item->callStatus}}</td></tr>
          {{-- <tr><td>Astrologer status</td><td>:</td><td>{{$item->astrologer_status}}</td></tr>
          <tr><td>Status description</td><td>:</td><td>{{$item->status_description}}</td></tr>
          <tr><td>Minutes consumed</td><td>:</td><td>{{$item->minutes_consumed}}</td></tr>
          <tr><td>First response</td><td>:</td><td>{{$item->first_response}}</td></tr>
          <tr><td>CDR detail</td><td>:</td><td>{{$item->cdr_detail}}</td></tr>
          <tr><td>System call status</td><td>:</td><td>{{$item->system_call_status}}</td></tr>
          <tr><td>Max minute</td><td>:</td><td>{{$item->max_minute}}</td></tr> --}}
          <tr><td>Call start time</td><td>:</td><td>{{$item->call_start_time}}</td></tr>
          <tr><td>Call end time</td><td>:</td><td>{{$item->call_end_time}}</td></tr>
          <tr><td>Call hangup time</td><td>:</td><td>{{$item->call_hangup_time}}</td></tr>
          <tr>
      <td>View Calls Detail</td>
      <td>:</td>
      <td>
        <a href="{{ url('astroadmin/fetch-call-details/' . $item->callSid) }}">
          view
        </a>
      </td>
    </tr>


      </table>
  </div>
</div>
<div class="clearfix"></div>
<div class="text-center">
  <a href="{{route('admin.'.$nav.'.index')}}" class="btn btn-info">Back</a>
</div>
@endsection

@push('scripts')

@endpush
