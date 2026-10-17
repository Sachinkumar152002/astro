@extends('layouts.admin')

@section('content')
  <div class="clearfix"></div>
  {!! Form::model($item, ['route' => ['admin.'.$module.'.submit'], 'method'=>'post' , 'onsubmit'=>"return childValidateAndSubmit(this)"]) !!}
    @if($item)
      {{ Form::hidden('id', old('id')) }}
    @endif
    <div class="row">
      <div class="col-md-6">
        <h1 class="text-primary float-left">{{ $moduleName }} Detail</h1>
      </div>
      <div class="col-md-6 text-right">
        @if($item && $item->start_at< date("Y-m-d H:i:s",strtotime("now+5minutes")))
        @else
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
          </div>
        @endif
      </div>
    </div>
    <div class="clearfix"></div>
    <ul class="nav nav-tabs">
      <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
    </ul>
    <div class="tab-content">
      <div id="information_tab" class="tab-pane fade in active show">
        <div class="row">
          <div class="col-md-6 form-group">
            <label for="astrologer_id" class="fs-14 fw-bold">Astrologer <span class="text-danger">*</span></label>
            <select class="form-control" name="astrologer_id" id="astrologer_id">
              <option value="">Select</option>
              @foreach($astrologers as $astrologer_id=>$astrologer_name)
                <option value="{{ $astrologer_id }}" {{ $item && $item->astrologer_id==$astrologer_id?"selected":"" }}>{{ $astrologer_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 form-group"></div>
          <div class="col-md-3 form-group">
            <label for="birth_date" class="fs-14 fw-bold">Start Date <span class="text-danger">*</span></label>
            <input type="date" id="start_date" class="form-control" name="start_date" min="{{ date('Y-m-d') }}" value="{{ $item?date('Y-m-d',strtotime($item->start_at)):'' }}">
          </div>
          <div class="col-md-3 form-group">
            <label for="time" class="fs-14 fw-bold">Start Time <span class="text-danger">*</span></label>
            <input class="form-control timepicker" type="time" name="start_time" id="start_time" value="{{ $item?date('H:i',strtotime($item->start_at)):'' }}">
          </div>
          <div class="col-md-3 form-group">
            <label for="birth_date" class="fs-14 fw-bold">End Date <span class="text-danger">*</span></label>
            <input type="date" id="end_date" class="form-control" name="end_date" min="{{ date('Y-m-d') }}" value="{{ $item?date('Y-m-d',strtotime($item->end_at)):'' }}">
          </div>
          <div class="col-md-3 form-group">
            <label for="time" class="fs-14 fw-bold">End Time <span class="text-danger">*</span></label>
            <input class="form-control timepicker" type="time" name="end_time" id="end_time" value="{{ $item?date('H:i',strtotime($item->end_at)):'' }}">
          </div>
          <div class="col-md-3 form-group">
            <label for="status" class="fs-14 fw-bold">Status <span class="text-danger">*</span></label>
            <select class="form-control nosearch" name="status" id="status">
              <option value="scheduled" {{ $item && $item->status=="scheduled"?"selected":"" }}>Scheduled</option>
              <option value="cancelled" {{ $item && $item->status=="cancelled"?"selected":"" }}>Cancelled</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  {!! Form::close() !!}
@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
  function childValidateAndSubmit(that){
    let startDate = $("#start_date").val();
    let startTime = $("#start_time").val();
    let endDate = $("#end_date").val();
    let endTime = $("#end_time").val();
    if(startDate && startTime && endDate && endTime){
      let currentTimestamp = new Date().getTime();
      let startTimeStamp = new Date(`${startDate} ${startTime}`).getTime();
      let endTimeStamp = new Date(`${endDate} ${endTime}`).getTime();
      if(startTimeStamp < currentTimestamp+(300*1000)){
        toastr.error("Start Time should be greater than 5 minutes from now.");
        return false;
      }
      if(endTimeStamp < startTimeStamp+(300*1000)){
        toastr.error("End Time should be greater than 5 minutes from start time.");
        return false;
      }
    }
    return validateAndSubmit(that);
  }
  function cancelScheduledLiveStream(url){
    $.ajax({
      url: url,
      method: "GET",
      success: function(response){
        // console.log(response);
        let message = response.message;
        if(message){
          toastr.success(message);
        }
        setTimeout(function(){
          window.location.reload();
        },1500);
      },
      error: function(error){
        // console.log(error);
        let errors = error.responseJSON.errors;
        let message = error.responseJSON.message;
        if(errors){
          for(var key in errors){
            toastr.error(errors[key][0]);
          }
        }else if(message){
          toastr.error(message);
        }
      }
    });
  }
</script>
@endpush
