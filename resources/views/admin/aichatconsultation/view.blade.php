
@extends('layouts.admin')
@section('content')
{!! Form::model($data, ['route' => ['admin.aiconsultation.chat.submit', [$userType]], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($data)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="">
  <div class="row">
    <div class="col-md-6">
      <h1 class="text-primary float-left">Ai Chat Consultation Detail</h1>
    </div>
    <!-- <div class="col-md-6 text-right mt-2">
       <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
    </div> -->
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  <li class="nav-item d-none"><a data-toggle="tab" class="nav-link" href="#status">Status</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
    <table class="table table-bordered mt-3">
      <tbody>
        @php
          $formData = json_decode($data->form_data, true);
        @endphp
        <tr>
          <th>{{$userType}}</th>
          @if($astrologer)
            <td><a href="{{ route('admin.Astrologer.edit', [$astrologer->id])  }}" target="_blank">{{ $astrologer->display_name }}</a></td>
          @else
            <td>Astrologer</td>
          @endif
        </tr>
        <tr>
          <th>User</th>
          @if($user)
            <td><a href="{{ route('admin.customer.edit', [$user->id])  }}" target="_blank">{{ $user->name }}</a></td>
          @else
            <td>User</td>
          @endif
        </tr>
        <tr>
          <th>Name</th>
          <td>{{$formData["name"]}}</td>
        </tr>
        @if($formData["email"])
          <tr>
            <th>Email</th>
            <td>{{ $formData["email"] }}</td>
          </tr>
        @endif
        @if($formData["mobile"])
          <tr>
            <th>Phone</th>
            <td>{{ $formData["mobile"] }}</td>
          </tr>
        @endif
        <tr>
          <th>Date of birth</th>
          <td>{{date('d/m/Y', strtotime($formData["birth_date"]))}}</td>
        </tr>
        @if($data->birthtime)
        <tr>
          <th>Time of birth</th>
          <td>{{date('h:i a', strtotime($formData["birth_date"].' '.$formData["birth_time"]))}}</td>
        </tr>
        @endif
        <tr>
          <th>Birth Place</th>
          <td>{{ $formData["birth_place"] }}</td>
        </tr>
        <tr>
          <th>Total Charges</th>
          @if($data->free)
           @php
             $totalPrice = $data->charges - $data->discount;
           @endphp
           <td>₹ {{ $totalPrice > 0 ? $totalPrice : 0 }} (free)</td>
          @else
            <td>₹ {{ $data->charges}}</td>
          @endif
        </tr>
        @php
        $startTimeInSeconds = strtotime($data->start_at);
        $endTimeInSeconds = strtotime($data->end_at);
        @endphp
        <tr>
          <th>Date</th>
          <td>{{date('d/m/Y', $startTimeInSeconds)}}</td>
        </tr>
        <tr>
          <th>Duration</th>
          <td>{{ ceil(($endTimeInSeconds-$startTimeInSeconds)/60) }} Minutes</td>
        </tr>
        <tr>
          <th>Start Time</th>
          <td>{{date('h:i:s a', $startTimeInSeconds)}}</td>
        </tr>
        <tr>
          <th>End Time</th>
          <td>{{date('h:i:s a', $endTimeInSeconds)}}</td>
        </tr>
        @if($data->document)
          <tr>
            <th>Document</th>
            <td><a href="{{ asset($data->document) }}" target="_blank"><button type="button" name="button" class="btn btn-primary">View Details</button> </a></td>
          </tr>
        @endif
        @if($data->chats->count())
          <tr>
            <th>Chats</th>
            <td onclick="chatLogs()" style="cursor:pointer"><button type="button" name="button" class="btn btn-primary">View Details</button> </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  <div id="status" class="tab-pane fade">
  @if(count(consultationRequests($data->id, '', 'astrologer'))>0)
    <div class="row">
      <div class="col-12"><h5>{{$userType}}</h5></div>
      @foreach(consultationRequests($data->id, '', 'astrologer') as $key => $item)
        <div class="col-10">
          <textarea disabled name="message" rows="3" cols="100">{{$item->message}}</textarea>
        </div>
        @if($item->status==1)
          <div class="col-2">Accepted ({{$item->request_type}})</div>
        @elseif($item->status==2)
          <div class="col-2">Rejected ({{$item->request_type}})</div>
        @else
          <div class="col-2">Request ({{$item->request_type}})</div>
        @endif
        @if($item->consultation_request_responses)
          @if($item->consultation_request_responses->user_message)
            <div class="col-5">
              <label>To User</label>
              <div class="form-group">
                <textarea disabled rows="3" cols="50">{{$item->consultation_request_responses->user_message}}</textarea>
              </div>
            </div>
          @endif
          @if($item->consultation_request_responses->astrologer_message)
            <div class="col-5">
              <label>To {{$userType}}</label>
              <div class="form-group">
                <textarea disabled rows="3" cols="50">{{$item->consultation_request_responses->astrologer_message}}</textarea>
              </div>
            </div>
          @endif
        @else
        <div class="col-5">
          <label>To User</label>
          <div class="form-group">
            <textarea name="admin_message_user" rows="3" cols="50"></textarea>
          </div>
        </div>
        <div class="col-5">
          <label>To {{$userType}}</label>
          <div class="form-group">
            <textarea name="admin_message_astrologer" rows="3" cols="50"></textarea>
          </div>
        </div>
        @endif
        @if($item->status == 0)
          <input type="hidden" name="request_id" value="{{$item->id}}">
        @endif
      @endforeach
    </div>
  @endif
  @if(count(consultationRequests($data->id, '', 'user'))>0)
    <div class="row">
      <div class="col-12"><h5>User</h5></div>
      @foreach(consultationRequests($data->id, '', 'user') as $key => $item)
        <div class="col-10">
          <textarea disabled name="message" rows="3" cols="100">{{$item->message}}</textarea>
        </div>
        @if($item->status==1)
          <div class="col-2">Accepted ({{$item->request_type}})</div>
        @elseif($item->status==2)
          <div class="col-2">Rejected ({{$item->request_type}})</div>
        @else
          <div class="col-2">Request ({{$item->request_type}})</div>
        @endif
        @if($item->consultation_request_responses)
          @if($item->consultation_request_responses->user_message)
            <div class="col-5">
              <label>To User</label>
              <div class="form-group">
                <textarea disabled rows="3" cols="50">{{$item->consultation_request_responses->user_message}}</textarea>
              </div>
            </div>
          @endif
          @if($item->consultation_request_responses->astrologer_message)
            <div class="col-5">
              <label>To {{$userType}}</label>
              <div class="form-group">
                <textarea disabled rows="3" cols="50">{{$item->consultation_request_responses->astrologer_message}}</textarea>
              </div>
            </div>
          @endif
        @else
        <div class="col-5">
          <label>To User</label>
          <div class="form-group">
            <textarea name="admin_message_user" rows="3" cols="50"></textarea>
          </div>
        </div>
        <div class="col-5">
          <label>To {{$userType}}</label>
          <div class="form-group">
            <textarea name="admin_message_astrologer" rows="3" cols="50"></textarea>
          </div>
        </div>
        @endif
        @if($item->status == 0)
          <input type="hidden" name="request_id" value="{{$item->id}}">
        @endif
      @endforeach
    </div>
  @endif
  <div class="row">
    <div class="col-3">
      <div class="form-group">
          {!! Form::label('user_status', 'Status', ['class' => 'required']) !!}
          <select class="form-control nosearch" name="user_status" onchange="changeStatus(this)">
            <option value="">Select</option>
            <option value="reject" >Reject</option>
            <option value="cancel" {{strtolower($data->user_status) == 'cancel'?'selected':''}}>Cancel</option>
            <option value="reschedule">Reschedule</option>
          </select>
      </div>
    </div>
    {{--
    <div class="col-3">
      <span class="ml-2"><strong>User Notify</strong></span>
        <input type="checkbox" class="form-group toggle-check" id="user_notify" value="1" name="user_notify">
        <label class="mx-2 t-lable mt-3" for="user_notify"></label>
    </div>
    <div class="col-3">
      <span class="ml-2"><strong>{{$userType}} Notify</strong></span>
        <input type="checkbox" class="form-group toggle-check" id="astrologer_notify" value="1" name="astrologer_notify">
        <label class="mx-2 t-lable mt-3" for="astrologer_notify"></label>
    </div>
    --}}
    <div class="col-3 d-none" id="refund-div">
      <span class="ml-2"><strong>Refund</strong></span>
        <input type="checkbox" class="form-group toggle-check" id="refund" value="1" name="refund">
        <label class="mx-2 t-lable mt-3" for="refund"></label>
    </div>
  </div>
  <div class="row d-none" id="reschedule-div">
    <div class="col-4">
      <div class="form-group">
          {!! Form::label('astrologer_id', $userType, ['class' => 'required']) !!}
          <select class="form-control nosearch" id="astrologer_id" name="astrologer_id" onchange="getAvailableDate(this)">
            <option value="">Select</option>
            @foreach($astrologers as $key => $item)
            <option value="{{$key}}">{{$item}}</option>
            @endforeach
          </select>
      </div>
    </div>
    <div class="col-4">
      <div class="form-group">
          {!! Form::label('schedule_date', 'Date', ['class' => 'required']) !!}
          <select class="form-control nosearch" id="schedule_date" name="schedule_date" onchange="getTimeSlot(this)">
            <option value="">Select</option>
          </select>
      </div>
    </div>
    <div class="col-4">
      <div class="form-group">
          {!! Form::label('schedule_time', 'Time', ['class' => 'required']) !!}
          <select class="form-control nosearch" id="schedule_time" name="schedule_time">
            <option value="">Select</option>
          </select>
      </div>
    </div>
  </div>
  </div>
</div>


<!-- modal start -->
<!-- track return consignment -->
<div class="modal fade" id="chatLogsModal" tabindex="-1" role="dialog" aria-labelledby="chatLogsModal1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="chatLogsModal1">Tracking</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @php
          $chats = $data->chats;
          $astrologerImage = $astrologer->image ? $astrologer->image->image(100, 100) : asset('assets/images/profile-n.png');
          $userImage = $user->image ? $user->image->image(100, 100) : asset('assets/images/user-n.png');
        @endphp
        @if($chats->count() > 0)
          @foreach($chats as $key => $value)
            @if($value->message_by== 'user')
              <div class="col-12 mt-4">
                <div class="row">
                  <div class="col-md-6">
                    <div class="m-1 p-1 bg-primary text-white rounded d-flex fixed-height-box">
                      <div class="">
                        <div class="ch-imag-box i'mher">
                          <img src="{{$userImage}}" class="img-fluid astro-img rounded-circle ch-imag ch-im-2" alt="User-A">
                        </div>
                      </div>
                      <div class="mx-2">
                        <p> <strong class="text-white">User</strong></p>
                        {!!nl2br($value->message)!!}
                      </div>
                    </div>
                    <div class="">
                      <small class="text-black">
                        <em>{{ date('d-m-Y h:i A', strtotime($value->created_at)) }}</em>
                      </small>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6"></div>
            @elseif($value->message_by== 'astrologer')
              <div class="col-12 mt-4">
                <div class="row">
                  <div class="col-4">  </div>
                  <div class="col-8">
                    <div class="m-1 p-1 bg-success text-white rounded d-flex align-items-start position-relative fixed-height-box">
                      <div class="">
                        <div class="ch-imag-box i'mhere">
                          <img src="{{ $astrologerImage }}" class="img-fluid astro-img rounded-circle ch-imag ch-im-2" alt="User-A">
                        </div>
                      </div>
                      <div class="mx-2 flex-grow-1 w-100">
                        <p> <strong class="text-white">Astrologer</strong></p>
                        {!!nl2br($value->message)!!}
                      </div>
                    </div>
                    <div class="">
                      <small class="text-black">
                        <em>{{ date('d-m-Y h:i A', strtotime($value->created_at)) }}</em>
                      </small>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        @endif
      </div>
    </div>
  </div>
</div>
<!-- modal end -->


{!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
  function changeStatus(that){
    let v = $(that).find(':selected').val();
      $('#reschedule-div').addClass('d-none')
      $('#refund-div').addClass('d-none')
    if(v=='reschedule'){
      $('#reschedule-div').removeClass('d-none')
    }else if(v=='cancel'){
      $('#refund-div').removeClass('d-none')
    }
  }
  function getAvailableDate(that){
    let url = @json(route('admin.consultation.date'));
    let astrologer_id = $(that).find(':selected').val();
    $.ajax({
      url:url,
      method:"POST",
      data:{astrologer_id:astrologer_id},
      headers:{
        'X-CSRF-TOKEN':@json(csrf_token())
      },
      success:function(response){
        $('#schedule_date').html('')
        let data = '<option value="">Select</option>';
        for (var key in response.data) {
          data += `<option value="${key}">${response.data[key]}</option>`;
        }
        // response.data.forEach(function(value, index){
        //   data += `<option value="${value}">${value}</option>`;
        // });
        $('#schedule_date').html(data);
      },
      error:function(error){
        console.log(error);
      }
    });
  }
  function getTimeSlot(that){
    let url = @json(route('admin.consultation.time'));
    let date = $(that).find(':selected').val();
    let astrologer_id = $('#astrologer_id').val();
    console.log(astrologer_id)
    $.ajax({
      url:url,
      method:"POST",
      data:{
        astrologer_id:astrologer_id,
        date:date,
        slot:@json($data->timeslot)
      },
      headers:{
        'X-CSRF-TOKEN':@json(csrf_token())
      },
      success:function(response){
        console.log(response.data);
        $('#schedule_time').html('')
        let data = '<option value="">Select</option>';
        for (var key in response.data) {
          data += `<option value="${key}">${response.data[key]}</option>`;
        }
        $('#schedule_time').html(data);
      },
      error:function(error){
        console.log(error);
      }
    });
  }

  function chatLogs(){
    $('#chatLogsModal').modal('show')
  }
</script>
@endpush
