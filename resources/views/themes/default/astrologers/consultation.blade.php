@extends(themeView().'layout')

@section('title', 'Booking History')

@section('content')
@php
    use Carbon\Carbon;
@endphp

@include(themeView().'astrologers._partials.top')
 <div class="container top-space mb-3" id="profile-app">
  <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
  @if($user->is_verified==1)
    <div class="col-lg-9 col-md-12 col-12 mt-5 mt-lg-2">
      <div class="row">
        @php
        $callConsultations = $appointments->where('package', 3);
        @endphp
       <div class="col-md-3 col-6 pe-md-3">
         <div class="card bg-primary text-center text-white pt-3 mb-1">
           <i class="bi bi-telephone fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-white fw-bold min-hhh2">{{count($callConsultations)}} Calls</p>
         </div>
       </div>
         @php
         $chatConsultations = $appointments->where('package', 2);
         @endphp
       <div class="col-md-3 col-6 pe-md-3">
         <div class="card bg-primary text-center text-white pt-3 mb-1">
           <i class="bi bi-chat fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-white fw-bold min-hhh2">{{count($chatConsultations)}} Chats</p>
         </div>
       </div>
         @php
          $videoConsultations = $appointments->where('package', 1);
         @endphp
       <div class="col-md-3 col-6 mt-3 mt-md-0">
         <div class="card bg-primary text-center text-white pt-3 mb-1">
           <i class="bi bi-camera-video text-white fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-white fw-bold min-hhh2">{{count($videoConsultations)}} Videos</p>
         </div>
       </div>

       <div class="col-md-3 col-6 mt-3 mt-md-0">
         <div class="card bg-primary text-center text-white pt-3 mb-1">
           <i class="bi bi-chat-left-text fs-2 text-white"></i>
           <p class="text-uppercase mt-3 text-center text-white fw-bold min-hhh2"> {{count($callConsultations)+count($videoConsultations)+count($chatConsultations)}} Total</p>
         </div>
       </div>
     </div>


      <ul class="nav nav-tabs astro-account-tab" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark {{$tab == 'call' ? 'active' : ''}}" id="address-tab" data-bs-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="true">Call Consultation</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark {{$tab == 'chat' ? 'active' : ''}}" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false" tabindex="-1">Chat Consultation</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark {{$tab == 'video' ? 'active' : ''}}" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Video Consultation</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade {{$tab == 'call' ? 'active show' : ''}}" id="address" role="tabpanel" aria-labelledby="address-tab">
          <div class="card bg-light mb-3 mt-3">
            <div class="card-body pb-0">
              <div class="table-responsive">
                <table class="table table-bordered contable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th scope="col">User</th>
                      <th scope="col">Schedule Date & Time</th>
                      <th scope="col">Rate Per min</th>
                      <th scope="col">Duration</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $i = 0;
                    @endphp
                    @forelse($callConsultations as $key => $item)
                      @php
                        $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->scheduled_end_time);
                        $duration = ceil((strtotime($item->scheduled_date.' '.$item->scheduled_end_time) - strtotime($item->scheduled_date.' '.$item->scheduled_time))/60);
                        if($item->timeslot == 5){
                          $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->max_time.'-5minutes');
                          $userStartTime = strtotime($item->scheduled_date.' '.$item->user_start_time);
                          $astrologerStartTime = strtotime($item->scheduled_date.' '.$item->astrologer_start_time);
                          $userEndTime = strtotime($item->scheduled_date.' '.$item->user_end_time);
                          if($userStartTime > $astrologerStartTime){
                            $duration = ceil(($userEndTime - $userStartTime)/60);
                          }else{
                            $duration = ceil(($userEndTime - $astrologerStartTime)/60);
                          }
                        }
                        $i++;
                      @endphp
                      <tr>
                        <th>{{$i}}</th>
                        <td>{{$item->user?$item->user->name:'User'}}</td>
                        <td>{{date('d-m-Y h:i A', strtotime($item->scheduled_date.' '.$item->scheduled_time))}}</td>
                        <td>₹ {{$item->actualprice}}</td>
                        @if($duration > 0)
                          <td>{{$duration}} min</td>
                        @else
                          <td>0 min</td>
                        @endif
                        <td>₹ {{$item->total_price}}</td>
                        <td>
                          @if(time() > $scheduledEndTime)
                            @if($item->status == 'cancel')
                              <span class="text-secondary">canceled</span>
                            @else
                              <span class="text-secondary">Completed</span>
                            @endif
                          @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_time))
                            <a class="text-primary text-decoration-underline" href="javascript:void(0)">On Call</a>
                          @else
                            <i class="bi bi-x-circle text-danger pointer" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                            <i class="bi bi-clock text-primary pointer" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                            <span class="text-secondary">wait</span>
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
        <div class="tab-pane fade {{$tab == 'chat' ? 'active show' : ''}}" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="card bg-light mb-3 mt-3">
            <div class="card-body pb-0">
              <div class="table-responsive">
                <table class="table table-bordered contable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th scope="col">User</th>
                      <th scope="col">Schedule Date & Time</th>
                      <th scope="col">Rate Per min</th>
                      <th scope="col">Duration</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $i = 0;
                    @endphp
                    @forelse($chatConsultations as $key => $item)
                      @php
                        $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->scheduled_end_time);
                        $duration = ceil((strtotime($item->scheduled_date.' '.$item->scheduled_end_time) - strtotime($item->scheduled_date.' '.$item->scheduled_time))/60);
                        if($item->timeslot == 5){
                          $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->max_time.'-5minutes');
                          $userStartTime = strtotime($item->scheduled_date.' '.$item->user_start_time);
                          $astrologerStartTime = strtotime($item->scheduled_date.' '.$item->astrologer_start_time);
                          $userEndTime = strtotime($item->scheduled_date.' '.$item->user_end_time);
                          if($userStartTime > $astrologerStartTime){
                            $duration = ceil(($userEndTime - $userStartTime)/60);
                          }else{
                            $duration = ceil(($userEndTime - $astrologerStartTime)/60);
                          }
                        }
                        $i++;
                      @endphp
                      <tr>
                        <th>{{$i}}</th>
                        <td>{{$item->user?$item->user->name:'User'}}</td>
                        <td>{{date('d-m-Y h:i A', strtotime($item->scheduled_date.' '.$item->scheduled_time))}}</td>
                        <td>₹ {{$item->actualprice}}</td>
                        @if($duration > 0)
                          <td>{{$duration}} min</td>
                        @else
                          <td>0 min</td>
                        @endif
                        <td>₹ {{$item->total_price}}</td>
                        <td>
                          @if(time() > $scheduledEndTime)
                            @if($item->status == 'cancel')
                              <span class="text-secondary">canceled</span>
                            @else
                              <a class="text-primary text-decoration-underline" href="{{ $item->meeting_link }}">Completed</a>
                            @endif
                          @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_time))
                            <a class="text-primary text-decoration-underline" href="{{ $item->meeting_link }}">Join</a>
                          @else
                            <i class="bi bi-x-circle text-danger pointer" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                            <i class="bi bi-clock text-primary pointer" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                            <span class="text-secondary">wait</span>
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
          <div class="tab-pane fade {{$tab == 'video' ? 'active show' : ''}}" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <div class="card bg-light mb-3 mt-3">
            <div class="card-body pb-0">
              <div class="table-responsive">
                <table class="table table-bordered contable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th scope="col">User</th>
                      <th scope="col">Schedule Date & Time</th>
                      <th scope="col">Rate Per min</th>
                      <th scope="col">Duration</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $i = 0;
                    @endphp
                    @forelse($videoConsultations as $key => $item)
                      @php
                        $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->scheduled_end_time);
                        $duration = ceil((strtotime($item->scheduled_date.' '.$item->scheduled_end_time) - strtotime($item->scheduled_date.' '.$item->scheduled_time))/60);
                        if($item->timeslot == 5){
                          $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->max_time.'-5minutes');
                          $userStartTime = strtotime($item->scheduled_date.' '.$item->user_start_time);
                          $astrologerStartTime = strtotime($item->scheduled_date.' '.$item->astrologer_start_time);
                          $userEndTime = strtotime($item->scheduled_date.' '.$item->user_end_time);
                          if($userStartTime > $astrologerStartTime){
                            $duration = ceil(($userEndTime - $userStartTime)/60);
                          }else{
                            $duration = ceil(($userEndTime - $astrologerStartTime)/60);
                          }
                        }
                        $i++;
                      @endphp
                      <tr>
                        <th>{{$i}}</th>
                          <td>{{$item->user?$item->user->name:'User'}}</td>
                          <td>{{date('d-m-Y h:i A', strtotime($item->scheduled_date.' '.$item->scheduled_time))}}</td>
                          <td>₹ {{$item->actualprice}}</td>
                          @if($duration > 0)
                            <td>{{$duration}} min</td>
                          @else
                            <td>0 min</td>
                          @endif
                          <td>₹ {{$item->total_price}}</td>
                          <td>
                            @if(time() > $scheduledEndTime)
                              @if($item->status == 'cancel')
                                <span class="text-secondary">canceled</span>
                              @else
                                <span class="text-secondary">Completed</span>
                              @endif
                            @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_time))
                              <a class="text-primary text-decoration-underline" href="{{ $item->meeting_link }}">Join</a>
                            @else
                              <i class="bi bi-x-circle text-danger pointer" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                              <i class="bi bi-clock text-primary pointer" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                              <span class="text-secondary">wait</span>
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
    @else
      <div class="col-sm-9">
          Not Verified Yet.
      </div>
    @endif
  </div>
</div>

@endsection
