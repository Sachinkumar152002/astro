@extends(themeView().'layout')

@section('title', 'Live')

@push("style")
  <link rel="stylesheet" href="{{ asset('assets/lib/material-timepicker/mdtimepicker.css') }}">
@endpush

@section('content')
  @php
    $_token=request()->has('_token')?request()->get("_token"):'';
  @endphp
  <section class="dashboad_section" style="background: #f2f2f2;">
   <div class="container top-space mb-3" id="profile-app">
     <div class="row">
       @include(themeView().'astrologers._partials.sidebar')
       @if($astrologer->is_verified==1)
        <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
          <div class="card border-n bg-light p-3 mt-3 mt-lg-0">
            <div class="table-responsive er-table">
              <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 dash-b-heading">Scheduled Live Streams</h4>
                <div class="d-flex mb-2">
                  <button type="button" class="btn btn-secondary" name="button" onclick="handleOpenScheduleLiveStreamModal()">Schedule</button>
                </div>
              </div>
              <div class="container my-4">
                <div class="row">
                  @forelse($astrologerLives as $key => $item)
                    <div class="col-md-4 col-sm-6 mb-4">
                      <div class="earning-card shadow-sm py-3 px-2 bg-light rounded earning_hieght">
                        <h6 class="mb-2 text-dark">{{ ucfirst($item->status) }}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <div class="">
                            <div class="">
                              <small class="small">Start at: {{ date('d-m-Y h:i a', strtotime($item->start_at)) }}</small>
                            </div>
                            <div class="">
                              <small class="small">End at: {{ date('d-m-Y h:i a', strtotime($item->end_at)) }}</small>
                            </div>
                          </div>
                          @if(strtotime($item->start_at) <= strtotime("now+1minute"))
                            @php
                              $redictToUrl=route('astrologer.live.video',['live_id'=>encrypt($item->id)]);
                              if($_token){
                                $redictToUrl=route('astrologer.live.video',['live_id'=>encrypt($item->id),"_token"=>$_token]);
                              }
                            @endphp
                            <a href="{{ $redictToUrl }}" class="btn btn-success px-2 py-1 text-white">Join</a>
                          @else
                            <button type="button" class="btn btn-success px-2 py-1 text-white" name="button">Wait</button>
                          @endif
                        </div>
                      </div>
                    </div>
                  @empty
                    <div class="col-12">
                      <div class="no-data text-center">
                        <h5 class="mt-3 text-dark">No Scheduled Streames Found!</h5>
                        <p class="text-secondary">Scheduled Streames will be displayed here.</p>
                      </div>
                    </div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
          <div class="mt-3">
            {!! $astrologerLives->links('vendor.pagination.bootstrap-5') !!}
          </div>
        </div>
       @else
        <div class="col-sm-9">
          <h4 class="h4">Profile Under review, come back later.</h4>
        </div>
       @endif
     </div>
   </div>
 </section>

 <!-- schedule live stream modal start -->
   <div class="modal fade mt-5 pt-5 live-m" id="scheduleLiveStreamModal" tabindex="-1" aria-labelledby="scheduleLiveStreamModalLabel" aria-hidden="true">
     <div class="modal-dialog mt-5">
       <div class="modal-content">
         <div class="modal-header bg-p">
           <h1 class="modal-title fs-5" id="scheduleLiveStreamModalLabel">Schedule Your Next Live Stream</h1>
           <button type="button" class="btn-close" id="scheduleLiveStreamModalCloseBtn" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
           @php
             $formSubmitUrl = route("astrologer.live.schedulelivestream");
           @endphp
           <form method="POST" action="{{ $formSubmitUrl }}" onsubmit="return handleScheduleLiveStream(this)" id="scheduleLiveStreamForm">
             <div class="schedule1 row">
               <input type="hidden" id="astrologer_id" name="astrologer_id" value="{{ $astrologer->id }}">
               <div class="col-md-6">
                 <label for="birth_date" class="fs-14 fw-bold">Start Date <span class="text-danger">*</span></label>
                 <input type="date" id="start_date" class="form-control" name="start_date" min="{{ date('Y-m-d') }}">
               </div>
               <div class="col-md-6">
                 <label for="time" class="fs-14 fw-bold">Start Time <span class="text-danger">*</span></label>
                 <i class="bi bi-clock" onclick="handleTimePickerIcon('#start_time')" style="position:relative;top:44%;left:44%"></i>
                 <input class="form-control timepicker" type="text" name="start_time" id="start_time">
               </div>
               <div class="col-md-6">
                 <label for="birth_date" class="fs-14 fw-bold">End Date <span class="text-danger">*</span></label>
                 <input type="date" id="end_date" class="form-control" name="end_date" min="{{ date('Y-m-d') }}">
               </div>
               <div class="col-md-6">
                 <label for="time" class="fs-14 fw-bold">End Time <span class="text-danger">*</span></label>
                 <i class="bi bi-clock" onclick="handleTimePickerIcon('#end_time')" style="position:relative;top:44%;left:48%"></i>
                 <input class="form-control timepicker" type="text" name="end_time" id="end_time">
               </div>
             </div>
             <div class="text-center">
               <button type="submit" class="btn btn-secondary">Schedule</button>
             </div>
           </form>
         </div>
       </div>
     </div>
   </div>
 <!-- schedule live stream modal end -->
@endsection
@push('scripts')
<script src="{{ asset('assets/lib/material-timepicker/mdtimepicker.js') }}"></script>
<script type="text/javascript">
  $('document').ready(function(){
    $('.timepicker').mdtimepicker({
      timeFormat:'hh:mm:ss',
      format:'h:mm tt',
      theme:'pink',
      clearBtn:true
    });
  });
  function handleOpenScheduleLiveStreamModal(){
    $("#scheduleLiveStreamForm")[0].reset();
    $("#scheduleLiveStreamModal").modal("show");
  }
  function handleTimePickerIcon(that){
    $(that).click();
  }
  function handleScheduleLiveStream(that){
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
    let _form = $(that);
    let url = _form.attr("action");
    let button = _form.find('button[type=submit]');
    button.addClass('disabled').prop('disabled', true).text('Scheduling...');
    formData=_form.serialize();
    console.log(formData);
    $.ajax({
      url: url,
      method: "POST",
      data: formData,
      headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      success: function(response){
        console.log(response);
        button.removeClass('disabled').prop('disabled', false).text('Schedule');
        let message = response.message;
        if(message){
          toastr.success(message);
        }
        setTimeout(function(){
          window.location.reload();
        },1500);
      },
      error: function(error){
        console.log(error);
        button.removeClass('disabled').prop('disabled', false).text('Schedule');
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
    return false;
  }
</script>

<script type="text/javascript">
  let _token='{{ $_token }}';
  if(_token!=""){
    $(".pagination a").on("click",function(e){
      e.preventDefault();
      let url = $(this).attr("href");
      url=`${url}&_token=${_token}`;
      window.location.href=url;
    });
  }
</script>

@endpush
