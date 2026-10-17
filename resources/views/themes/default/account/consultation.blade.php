@extends(themeView().'layout')
@section('title', 'Dashboard')
@section('keywords', '')
@section('description','')
@section('content')
@php
    use Carbon\Carbon;
@endphp

<section class="mt-3 mb-5">
  <div class="container">
  <div class="row justify-content-between">
    @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12">
      <div class="row my-3 recent">
        <div class="col-lg-12">
          <div class="row my-2 dashbord">
            @php
              $allAppointments = $user->appointments ?? collect();
              $userChatCount = $allAppointments->where('package', 2)->count();
              $userCallCount = $allAppointments->where('package', 3)->count();
              $userVideoCount = $allAppointments->where('package', 1)->count();
              $userTotalCount = $userChatCount + $userCallCount + $userVideoCount;
            @endphp
            <div class="col-md-3 col-6 pe-md-3 mb-2 mb-md-0">
              <a href="javascript:void(0)">
                <div class="card bg text-center text-white pt-3 mb-1">
                  <i class="bi bi-chat fs-2 text-black"></i>
                  <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{ $userChatCount }} Chats</p>
                </div>
              </a>
            </div>
            <div class="col-md-3 col-6 pe-md-3 mb-2 mb-md-0">
              <a href="javascript:void(0)">
                <div class="card bg-2 text-center text-white pt-3 mb-1">
                  <i class="bi bi-telephone fs-2 text-black"></i>
                  <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{ $userCallCount }} Calls</p>
                </div>
              </a>
            </div>
            <div class="col-md-3 col-6 mt-3 mt-md-0 mb-2 mb-md-0">
              <a href="javascript:void(0)">
                <div class="card bg-3 text-center text-white pt-3 mb-1">
                  <i class="bi bi-camera-video text-black fs-2 text"></i>
                  <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{ $userVideoCount }} Videos</p>
                </div>
              </a>
            </div>
            <div class="col-md-3 col-6 mt-3 mt-md-0 mb-2 mb-md-0">
              <a href="javascript:void(0)">
                <div class="card bg-4 text-center text-white pt-3 mb-1">
                  <i class="bi bi-chat-left-text fs-2 text-black"></i>
                  <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{ $userTotalCount }} Total</p>
                </div>
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <h4>
          Recent Consultation
          </h4>
        </div>
        <div class="col-lg-12">
          <ul class="nav nav-tabs " id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                  <a class="nav-link active text-dark" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                      aria-controls="profile" aria-selected="false">Video Consultation</a>
              </li>
              <li class="nav-item" role="presentation">
                  <a class="nav-link text-dark" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                      aria-controls="home" aria-selected="true">Chat Consultation</a>
              </li>
              <li class="nav-item" role="presentation">
                  <a class="nav-link text-dark" id="address-tab" data-bs-toggle="tab" href="#address" role="tab"
                      aria-controls="address" aria-selected="true">Call Consultation</a>
              </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="card bg-light mb-3 mt-4">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered contable">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col"><i class="bi bi-person"></i> Astrologer Name</th>
                          <th scope="col"><i class="bi bi-people"></i> User Name</th>
                          <th scope="col"> <i class="bi bi-calendar-day"></i> Schedule Date</th>
                          <th colspan="col"> <i class="bi bi-alarm"></i> Start Time</th>
                          <th colspan="col"> <i class="bi bi-personbi bi-alarm"></i> End Time</th>
                          <th scope="col"><i class="bi bi-check2-circle"></i>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($user->videoAppointmentsUpcoming as $key => $item)
                          @php
                            $meetingDateTime = Carbon::parse($item->meeting_date . ' ' . $item->end_time);
                            $now = Carbon::now();
                            $remainingTime = $meetingDateTime->diff($now);
                          @endphp
                          <tr>
                            <th scope="row">{{$key + 1}}</th>
                              <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                              <td>{{$item->user?$item->user->name:'User'}}</td>
                              <td>{{date('d-m-Y', strtotime($item->scheduled_date))}}</td>
                              <td>{{date('H:i:A', strtotime($item->scheduled_time))}}</td>
                              <td>{{date('H:i:A', strtotime($item->scheduled_end_time))}}</td>
                              <td>
                                @if($now->lt($meetingDateTime))
                                  <a class="text-primary text-decoration-underline" href="{{route('user.video.call', [$item->id])}}">
                                    ({{ $remainingTime->days }} days, {{ $remainingTime->h }} hours, {{ $remainingTime->i }} minutes remaining)
                                  </a>
                                @else
                                  <span class="text-secondary">Meeting Ended</span>
                                @endif
                              </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="7" class="text-danger text-center">No Video Consultation yet</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
              <div class="card bg-light mb-3 mt-4">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered contable">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col"><i class="bi bi-person"></i> Astrologer Name</th>
                          <th scope="col"><i class="bi bi-people"></i> User Name</th>
                          <th scope="col"> <i class="bi bi-calendar-day"></i> Schedule Date</th>
                          <th colspan="col"> <i class="bi bi-alarm"></i> Start Time</th>
                          <th colspan="col"> <i class="bi bi-personbi bi-alarm"></i> End Time</th>
                          <th scope="col"><i class="bi bi-check2-circle"></i>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($user->chatAppointmentsUpcoming as $key => $item)
                          @php
                            $meetingDateTime = Carbon::parse($item->meeting_date . ' ' . $item->end_time);
                            $now = Carbon::now();
                            $remainingTime = $meetingDateTime->diff($now);
                          @endphp
                          <tr>
                            <th scope="row">{{$key + 1}}</th>
                              <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                              <td>{{$item->user?$item->user->name:'User'}}</td>
                              <td>{{date('d-m-Y', strtotime($item->scheduled_date))}}</td>
                              <td>{{date('H:i:A', strtotime($item->scheduled_time))}}</td>
                              <td>{{date('H:i:A', strtotime($item->scheduled_end_time))}}</td>
                              <td>
                                @if($item->user_status == 'booked')
                                  <a class="text-primary text-decoration-underline" href="{{route('user.chat', [$item->id])}}">
                                    ({{ $remainingTime->days }} days, {{ $remainingTime->h }} hours, {{ $remainingTime->i }} minutes remaining)
                                  </a>
                                @else
                                  <span class="text-secondary">Meeting Ended</span>
                                @endif
                              </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="7" class="text-danger text-center">No Chat Consultation yet</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
              <div class="card bg-light mb-3 mt-4">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered contable">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col"><i class="bi bi-person"></i> Astrologer Name</th>
                          <th scope="col"><i class="bi bi-people"></i> User Name</th>
                          <th scope="col"> <i class="bi bi-calendar-day"></i> Schedule Date</th>
                          <th colspan="col"> <i class="bi bi-alarm"></i> Start Time</th>
                          <th colspan="col"> <i class="bi bi-personbi bi-alarm"></i> End Time</th>
                          <th scope="col"><i class="bi bi-check2-circle"></i>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($user->callAppointmentsUpcoming as $key => $item)
                          @php
                            $meetingDateTime = Carbon::parse($item->meeting_date . ' ' . $item->end_time);
                            $now = Carbon::now();
                            $remainingTime = $meetingDateTime->diff($now);
                          @endphp
                          <tr>
                            <th scope="row">{{$key + 1}}</th>
                              <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                              <td>{{$item->user?$item->user->name:'User'}}</td>
                              <td>{{date('d-m-Y', strtotime($item->scheduled_date))}}</td>
                              <td>{{date('H:i:A', strtotime($item->scheduled_time))}}</td>
                              <td>{{date('H:i:A', strtotime($item->scheduled_end_time))}}</td>
                              <td>
                                @if($item->user_status == 'booked')
                                  <a class="text-primary text-decoration-underline" href="javascript:void(0)" onclick="joinCall({{$item->callHistory?$item->callHistory->id:0}})">
                                    ({{ $remainingTime->days }} days, {{ $remainingTime->h }} hours, {{ $remainingTime->i }} minutes remaining)
                                  </a>
                                @else
                                  <span class="text-secondary">Meeting Ended</span>
                                @endif
                              </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="7" class="text-danger text-center">No Call Consultation yet</td>
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
  </div>
  </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function (){
  function joinCall(chat_id){
    chat_id = parseInt(chat_id);
    if(chat_id == 0){
      $.ajax({
          url: @json(route('click-to-call')),
          type: 'POST',
          data: {
            call_history_id:chat_id,
          },
          success: function(response) {
            console.log('Route hit successfully:', response);
          },
          error: function(xhr, status, error) {
            console.error('Error hitting the route:', error);
          }
      });
    }else{
      console.log('chat_id not found');
    }
    $.ajax({
        url: @json(route('click-to-call')),
        type: 'POST',
        data: {
          call_history_id:chat_id,
        },
        success: function(response) {
          console.log('Route hit successfully:', response);
        },
        error: function(xhr, status, error) {
          console.error('Error hitting the route:', error);
        }
    });
  }
})
</script>
@endpush
