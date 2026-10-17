@extends(themeView().'layout') @section('title', 'Dashboard') @section('keywords', '') @section('description','') @section('content') <div class="container mt-3 mb-3" id="profile-app">
  <div class="row">
  @include(themeView().'astrologers._partials.sidebar')
    <div class="col-sm-9 mt-md-0 mt-5">
      <div class="row">
       <div class="col-md-3 col-6">
         <div class="card bg-primary text-center text-white pt-3 mb-1">
           <i class="bi bi-telephone fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-white fw-bold min-hhh">{{count($user->callAppointments)}} Total Phone Calls</p>
         </div>

       </div>
       <div class="col-md-3 col-6">
         <div class="card bg-primary text-center text-white pt-3 mb-1">
           <i class="bi bi-camera-video text-white fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-white fw-bold min-hhh">{{count($user->videoAppointments)}} Total Video Calls</p>
         </div>

       </div>
       <div class="col-md-3 col-6">
         <div class="card bg-primary text-center text-white pt-3 mb-1">
           <i class="bi bi-chat fs-2 text"></i>
           <p class="text-uppercase mt-3 text-center text-white fw-bold min-hhh">{{count($user->chatAppointments)}} Total Chats</p>
         </div>

       </div>

       <div class="col-md-3 col-6">
         <div class="card bg-warning text-center text-dark pt-3 mb-1">
           <i class="bi bi-chat-left-text fs-2 text-dark"></i>
           <p class="text-uppercase mt-3 text-center text-dark fw-bold min-hhh"> {{count($user->callAppointments)+count($user->videoAppointments)+count($user->chatAppointments)}} All Consultation</p>
         </div>

       </div>
     </div>


      <ul class="nav nav-tabs astro-account-tab" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Video Consultation</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false" tabindex="-1">Chat Consultation</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark" id="address-tab" data-bs-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="true">Call Consultation</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="card bg-light mb-3 mt-3">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered contable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <!-- <th scope="col"><i class="bi bi-person"></i> Astrologer</th> -->
                    <th scope="col"><i class="bi bi-people"></i> User</th>
                    <th scope="col"> <i class="bi bi-calendar-day"></i> Schedule Date</th>
                    <th colspan="col"> <i class="bi bi-alarm"></i> Start Time</th>
                    <th colspan="col"> <i class="bi bi-personbi bi-alarm"></i> End Time</th>
                    <th scope="col"><i class="bi bi-check2-circle"></i>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($user->videoAppointments as $key => $item)
                    @php
                      $meetingDateTime = \Carbon\Carbon::parse($item->scheduled_date . ' ' . $item->scheduled_end_time);
                      $now = \Carbon\Carbon::now();
                      $startTime = \Carbon\Carbon::createFromTimestamp(strtotime($item->scheduled_date . ' ' . $item->scheduled_time));
                      $remainingTime = $now->diff($startTime);
                    @endphp
                    <tr>
                      <th scope="row">{{$key + 1}}</th>
                      {{--
                        <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                        --}}
                        <td>{{$item->user?$item->user->name:'User'}}</td>
                        <td>{{date('d-m-Y', strtotime($item->scheduled_date))}}</td>
                        <td>{{date('h:i:A', strtotime($item->scheduled_time))}}</td>
                        <td>{{date('h:i:A', strtotime($item->scheduled_end_time))}}</td>
                        <td>
                          @if(time()<strtotime($item->scheduled_date.' '.$item->scheduled_time))
                            ({{ $remainingTime->days }} days, {{ $remainingTime->h }} hours, {{ $remainingTime->i }} minutes, seconds {{ $remainingTime->s }} remaining)
                          @elseif(time()>=strtotime($item->scheduled_date.' '.$item->scheduled_time) && time()<=strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                            <a class="text-primary text-decoration-underline" href="{{route('consult.video', [$item->id])}}">
                              {{'join'}}
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
          <div class="card bg-light mb-3 mt-3">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered contable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <!-- <th scope="col"><i class="bi bi-person"></i> Astrologer</th> -->
                      <th scope="col"><i class="bi bi-people"></i> User</th>
                      <th scope="col"> <i class="bi bi-calendar-day"></i> Schedule Date</th>
                      <th colspan="col"> <i class="bi bi-alarm"></i> Start Time</th>
                      <th colspan="col"> <i class="bi bi-personbi bi-alarm"></i> End Time</th>
                      <th scope="col"><i class="bi bi-check2-circle"></i>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($user->chatAppointments as $key => $item)
                      @php
                        $meetingDateTime = \Carbon\Carbon::parse($item->scheduled_date . ' ' . $item->scheduled_end_time);
                        $now = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::createFromTimestamp(strtotime($item->scheduled_date . ' ' . $item->scheduled_time));
                        $remainingTime = $now->diff($startTime);
                      @endphp
                      <tr>
                        <th scope="row">{{$key + 1}}</th>
                        {{--
                          <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                          --}}
                          <td>{{$item->user?$item->user->name:'User'}}</td>
                          <td>{{date('d-m-Y', strtotime($item->scheduled_date))}}</td>
                          <td>{{date('h:i:A', strtotime($item->scheduled_time))}}</td>
                          <td>{{date('h:i:A', strtotime($item->scheduled_end_time))}}</td>
                          <td>
                            @if(time()<strtotime($item->scheduled_date.' '.$item->scheduled_time))
                              ({{ $remainingTime->days }} days, {{ $remainingTime->h }} hours, {{ $remainingTime->i }} minutes, seconds {{ $remainingTime->s }} remaining)
                            @elseif(time()>=strtotime($item->scheduled_date.' '.$item->scheduled_time) && time()<=strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                              <a class="text-primary text-decoration-underline" href="{{route('consult.chat', [$item->id])}}">
                                {{'join'}}
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
          <div class="card bg-light mb-3 mt-3">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered contable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <!-- <th scope="col"><i class="bi bi-person"></i> Astrologer</th> -->
                      <th scope="col"><i class="bi bi-people"></i> User</th>
                      <th scope="col"> <i class="bi bi-calendar-day"></i> Schedule Date</th>
                      <th colspan="col"> <i class="bi bi-alarm"></i> Start Time</th>
                      <th colspan="col"> <i class="bi bi-personbi bi-alarm"></i> End Time</th>
                      <th scope="col"><i class="bi bi-check2-circle"></i>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($user->callAppointments as $key => $item)
                      @php
                        $meetingDateTime = \Carbon\Carbon::parse($item->scheduled_date . ' ' . $item->scheduled_end_time);
                        $now = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::createFromTimestamp(strtotime($item->scheduled_date . ' ' . $item->scheduled_time));
                        $remainingTime = $now->diff($startTime);
                      @endphp
                      <tr>
                        <th scope="row">{{$key + 1}}</th>
                        {{--
                          <td>{{$item->astrologer?$item->astrologer->display_name:'Astrologer'}}</td>
                          --}}
                          <td>{{$item->user?$item->user->name:'User'}}</td>
                          <td>{{date('d-m-Y', strtotime($item->scheduled_date))}}</td>
                          <td>{{date('h:i:A', strtotime($item->scheduled_time))}}</td>
                          <td>{{date('h:i:A', strtotime($item->scheduled_end_time))}}</td>
                          <td>
                            @if(time()<strtotime($item->scheduled_date.' '.$item->scheduled_time))
                              ({{ $remainingTime->days }} days, {{ $remainingTime->h }} hours, {{ $remainingTime->i }} minutes, seconds {{ $remainingTime->s }} remaining)
                            @elseif(time()>=strtotime($item->scheduled_date.' '.$item->scheduled_time) && time()<=strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                              <a class="text-primary text-decoration-underline" href="javascript:void(0)">
                                {{'join'}}
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
  </div> @endsection
