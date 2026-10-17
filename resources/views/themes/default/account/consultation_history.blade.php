@extends(themeView().'layout')

 @section('title', 'Consultation Histories | '.config("app.name"))

 @section('content')
<section class="dashboad_section">
<div class="container" id="profile-app">
  <div class="row">
    @include(themeview().'account._sidebar')
    <div class="col-sm-9 mt-5 mt-md-0">
      <div class="row">
        @php
        $callConsultations = $appointments->where('package', 3);
        @endphp
       <div class="col-md-3 col-6 pe-md-3">
         <div class="card bg-white text-center text-white pt-3 mb-1">
           <i class="bi bi-telephone fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{count($callConsultations)}} Calls</p>
         </div>
       </div>
         @php
         $chatConsultations = $appointments->where('package', 2);
         @endphp
       <div class="col-md-3 col-6 pe-md-3">
         <div class="card bg text-center text-white pt-3 mb-1">
           <i class="bi bi-chat fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{count($chatConsultations)}} Chats</p>
         </div>
       </div>
         @php
          $videoConsultations = $appointments->where('package', 1);
         @endphp
       <div class="col-md-3 col-6 mt-3 mt-md-0">
         <div class="card bg text-center text-white pt-3 mb-1">
           <i class="bi bi-camera-video text-white fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{count($videoConsultations)}} Videos</p>
         </div>
       </div>

       <div class="col-md-3 col-6 mt-3 mt-md-0">
         <div class="card bg text-center text-white pt-3 mb-1">
           <i class="bi bi-chat-left-text fs-2 text-white"></i>
           <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2"> {{count($callConsultations)+count($videoConsultations)+count($chatConsultations)}} Total</p>
         </div>
       </div>
     </div>

      <ul class="nav nav-tabs dash-tab" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark active" id="call-tab" data-bs-toggle="tab" href="#call" role="tab" aria-controls="call" aria-selected="true">Call Consultation</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark" id="chat-tab" data-bs-toggle="tab" href="#chat" role="tab" aria-controls="chat" aria-selected="false" tabindex="-1">Chat Consultation</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark" id="video-tab" data-bs-toggle="tab" href="#video" role="tab" aria-controls="video" aria-selected="false" tabindex="-1">Video Consultation</a>
        </li>
      </ul>

      <div class="tab-content table_consultation" id="myTabContent">
        <div class="tab-pane fade active show" id="call" role="tabpanel" aria-labelledby="call-tab">
        <div class="card bg-light mb-3 mt-3">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered contable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Astrologer</th>
                    <th scope="col">Schedule Date & Time</th>
                    <th scope="col">Rate Per min</th>
                    <th scope="col">Duration</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($callConsultations as $key => $item)
                    @php
                      $duration = 0;
                      if($item->timeslot==5){
                        $userStartTime = strtotime($item->scheduled_date.' '.$item->user_start_time);
                        $astrologerStartTime = strtotime($item->scheduled_date.' '.$item->astrologer_start_time);
                        $userEndTime = strtotime($item->scheduled_date.' '.$item->user_end_time);
                        if($userStartTime > $astrologerStartTime){
                          $duration = ceil(($userEndTime - $userStartTime)/60);
                        }else{
                          $duration = ceil(($userEndTime - $astrologerStartTime)/60);
                        }
                      }else{
                        $duration = ceil((strtotime($item->scheduled_date.' '.$item->scheduled_end_time) - strtotime($item->scheduled_date.' '.$item->scheduled_time))/60);
                      }
                    @endphp
                    <tr>
                      <th scope="row">{{$key + 1}}</th>
                      <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                      <td>{{date('d-m-Y h:i A', strtotime($item->scheduled_date.' '.$item->scheduled_time))}}</td>
                      <td>₹ {{$item->actualprice}}</td>
                      @if($duration > 0)
                        <td>{{$duration}} min</td>
                      @else
                        <td>0 min</td>
                      @endif
                      <td>₹ {{$item->total_price}}</td>
                      <td>
                        @if(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                          @if($item->user_status == 'cancel')
                            <span class="text-secondary">cancelled</span>
                          @elseif($item->callHistory)
                            <a href="{{$item->callHistory->recordingUrl}}" target="_blank">{{ $item->total_price > 0 ? 'Completed' : 'On Call' }}</a>
                            @else
                            {{ $item->total_price > 0 ? 'Completed' : 'On Call' }}
                          @endif
                        @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_time))
                          <a class="text-primary text-decoration-underline" href="javascript:void(0)">On Call</a>
                        @else
                          <i class="bi bi-x-circle text-danger pointer" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                          <i class="bi bi-clock text-primary pointer" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-danger text-center">
                        <div class="no-data">
                          <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                          <h5 class="mt-3 text-dark"> No Call Consultation Found!</h5>
                          <p class="text-secondary"> Consultation bookings and history will be displayed here.</p>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
        </div>
        <div class="tab-pane fade" id="chat" role="tabpanel" aria-labelledby="chat-tab">
          <div class="card bg-light mb-3 mt-3">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered contable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Astrologer</th>
                      <th scope="col">Schedule Date & Time</th>
                      <th scope="col">Rate Per min</th>
                      <th scope="col">Duration</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($chatConsultations as $key => $item)
                      @php
                        $duration = 0;
                        if($item->timeslot==5){
                          $userStartTime = strtotime($item->scheduled_date.' '.$item->user_start_time);
                          $astrologerStartTime = strtotime($item->scheduled_date.' '.$item->astrologer_start_time);
                          $userEndTime = strtotime($item->scheduled_date.' '.$item->user_end_time);
                          if($userStartTime > $astrologerStartTime){
                            $duration = ceil(($userEndTime - $userStartTime)/60);
                          }else{
                            $duration = ceil(($userEndTime - $astrologerStartTime)/60);
                          }
                        }else{
                          $duration = ceil((strtotime($item->scheduled_date.' '.$item->scheduled_end_time) - strtotime($item->scheduled_date.' '.$item->scheduled_time))/60);
                        }
                      @endphp
                      <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                        <td>{{date('d-m-Y h:i A', strtotime($item->scheduled_date.' '.$item->scheduled_time))}}</td>
                        <td>₹ {{$item->actualprice}}</td>
                        @if($duration > 0)
                          <td>{{$duration}} min</td>
                        @else
                          <td>0 min</td>
                        @endif
                        <td>₹ {{$item->total_price}}</td>
                        <td>
                          @if(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                            @if($item->user_status == 'cancel')
                              <span class="text-secondary">cancelled</span>
                            @else
                              <a class="text-primary text-decoration-underline" href="{{route('consult.chat', [$item->id])}}">{{ $item->total_price > 0 ? 'Completed' : 'Join' }}</a>
                            @endif
                          @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_time))
                            <a class="text-primary text-decoration-underline" href="{{route('consult.chat', [$item->id])}}">Join</a>
                          @else
                            <i class="bi bi-x-circle text-danger pointer" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                            <i class="bi bi-clock text-primary pointer" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-danger text-center">
                          <div class="no-data">
                            <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                            <h5 class="mt-3 text-dark"> No Chat Consultation Found!</h5>
                            <p class="text-secondary"> Consultation bookings and history will be displayed here.</p>
                          </div>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
          <div class="card bg-light mb-3 mt-3">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered contable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Astrologer</th>
                      <th scope="col">Schedule Date & Time</th>
                      <th scope="col">Rate Per min</th>
                      <th scope="col">Duration</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($videoConsultations as $key => $item)
                      @php
                        $duration = 0;
                        if($item->timeslot==5){
                          $userStartTime = strtotime($item->scheduled_date.' '.$item->user_start_time);
                          $astrologerStartTime = strtotime($item->scheduled_date.' '.$item->astrologer_start_time);
                          $userEndTime = strtotime($item->scheduled_date.' '.$item->user_end_time);
                          if($userStartTime > $astrologerStartTime){
                            $duration = ceil(($userEndTime - $userStartTime)/60);
                          }else{
                            $duration = ceil(($userEndTime - $astrologerStartTime)/60);
                          }
                        }else{
                          $duration = ceil((strtotime($item->scheduled_date.' '.$item->scheduled_end_time) - strtotime($item->scheduled_date.' '.$item->scheduled_time))/60);
                        }
                      @endphp
                      <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                        <td>{{date('d-m-Y h:i A', strtotime($item->scheduled_date.' '.$item->scheduled_time))}}</td>
                        <td>₹ {{$item->actualprice}}</td>
                        @if($duration > 0)
                          <td>{{$duration}} min</td>
                        @else
                          <td>0 min</td>
                        @endif
                        <td>₹ {{$item->total_price}}</td>
                        <td>
                          @if(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                            @if($item->user_status == 'cancel')
                              <span class="text-secondary">cancelled</span>
                            @else
                              <span class="text-secondary">{{ $item->total_price > 0 ? 'Completed' : 'Join' }}</span>
                            @endif
                          @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_time))
                          <a class="text-primary text-decoration-underline" href="{{route('user.startvideocall', [$item->id])}}">Join</a>
                          @else
                            <i class="bi bi-x-circle text-danger pointer" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                            <i class="bi bi-clock text-primary pointer" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-danger text-center">
                          <div class="no-data">
                            <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                            <h5 class="mt-3 text-dark"> No Video Consultation Found!</h5>
                            <p class="text-secondary"> Consultation bookings and history will be displayed here.</p>
                          </div>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

  <div class="modal fade redeem-modal" id="consultation_request" tabindex="-1" aria-labelledby="consultation_request" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="consultation_request_type">Request</h5>
          <button type="button" class="btn-close" onclick="closeModel()"></button>
        </div>

    <div class="modal-body">
      <form class="" action="" method="post" onsubmit="return consultationRequest()">
        <input type="hidden" name="appointment_id" id="appointment_id" value="">
        <input type="hidden" name="request_type" id="request_type" value="">
        <div class="row">
          <div class="col-12">
            <label for="message">Message</label>
            <div class="form-group">
              <textarea name="message" id="message" rows="3" cols="50"></textarea>
            </div>
          </div>
        </div>
        <div class="validation text-danger fs-8 d-none" id="message-error">

        </div>
        <div class="form-group my-2">
          <button type="submit" class="btn btn-secondary px-4 py-2 profile-save-btn submit_btn" style="height: 100%;">Save</button>
        </div>
      </form>
    </div>

    </div>
  </div>
</div>
  @endsection
@push('scripts')
<script type="text/javascript">
  function consultationRequest(){
    $('.validation').addClass('d-none');
    let appointment_id = $('#appointment_id').val();
    let request_type = $('#request_type').val();
    let message = $('#message').val();
    $.ajax({
      url:@json(route('user.consultation.request')),
      method:'POST',
      data: {
        appointment_id:appointment_id,
        request_type:request_type,
        message:message,
      },
      headers:{
        "X-CSRF-TOKEN":@json(csrf_token())
      },
      success:function(response){
        Swal.fire({
            icon: response.success?'success':'error',
            title: response.message,
            confirmButtonText: 'Okay'
        });
        closeModel()
      },
      error:function(error){
        console.log(error)
        if(error.responseJSON && error.responseJSON.errors){
          for (var key in error.responseJSON.errors) {
            $('#'+key+'-error').removeClass('d-none').text(error.responseJSON.errors[key][0])
          }
        }else if(error.responseJSON){
          Swal.fire({
              icon: error.responseJSON.success?'success':'error',
              title: error.responseJSON.message,
              confirmButtonText: 'Okay'
          });
          closeModel()
        }
      }
    });
    return false;
  }
  function openModel(appointment_id, request_type){
    $('#consultation_request_type').html(request_type.toUpperCase()+' REQUEST FORM');
    $('#consultation_request').modal('show');
    $('#appointment_id').val(appointment_id);
    $('#request_type').val(request_type);
  }
  function closeModel(){
    $('#appointment_id').val('');
    $('#request_type').val('');
    $('#message').val('');
    $('#consultation_request').modal('hide');
  }
</script>
@endpush
