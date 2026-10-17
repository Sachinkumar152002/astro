@extends(themeView().'layout')

 @section('title', 'Consultation Histories | '.config("app.name"))

@section('content')
<section class="dashboad_section">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeview().'account._sidebar')
      <div class="col-lg-9 col-12 mt-md-0 mt-5 all_pages pad-ap3">

        <div class="container mt-2 mb-2 px-1">
         <div class="row g-3 same-menu-row">

             <!-- Item -->

            @php
              $allAppointments = $user->appointments ?? collect();
              $userChatCount = $allAppointments->where('package', 2)->count();
              $userCallCount = $allAppointments->where('package', 3)->count();
              $userVideoCount = $allAppointments->where('package', 1)->count();
              $userTotalCount = $userChatCount + $userCallCount + $userVideoCount;
            @endphp
             <div class="col-6 col-md-3">
               <a href="">
                 <div class="same-card">
                     <div class="same-icon" style="background:#28a745;">
                         <img src="/assets/img/chat.png" alt="">
                     </div>
                     <p>{{ $userChatCount }} Chat</p>
                 </div>
               </a>
             </div>



             <div class="col-6 col-md-3 ">
               <a href="">
                 <div class="same-card">
                     <div class="same-icon" style="background:#5bc0de;">
                         <img src="/assets/img/phone.png" alt="">
                     </div>
                     <p>{{ $userCallCount }} Call</p>
                 </div>
               </a>
             </div>



             <div class="col-6 col-md-3">
               <a href="">
                 <div class="same-card">
                     <div class="same-icon" style="background:#ff4444;">
                         <img src="/assets/img/video-camera.png" alt="">
                     </div>
                     <p>{{ $userVideoCount }} Video</p>
                 </div>
               </a>
             </div>

             <div class="col-6 col-md-3">
               <a href="">
                 <div class="same-card">
                     <div class="same-icon" style="background:#d4a017;">
                         <img src="/assets/img/savings.png" alt="">
                     </div>
                     <p>{{ $userTotalCount }} Total</p>
                 </div>
               </a>
             </div>


         </div>
     </div>
        <!-- <div class="row">
          @php
           $chatConsultations = $appointments->where('package', 2);
          @endphp
          <div class="col-md-3 col-6 pe-md-3">
            <div class="card bg text-center text-white pt-3 mb-1">
              <div class="card_icon bg-white">

              </div>
              <i class="bi bi-chat fs-2 text-black" aria-hidden="true"></i>
              <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{count($chatConsultations)}} Chats</p>
            </div>
          </div>
          @php
            $callConsultations = $appointments->where('package', 3);
          @endphp
         <div class="col-md-3 col-6 pe-md-3">
           <div class="card bg-2 text-center text-white pt-3 mb-1">
             <i class="bi bi-telephone fs-2 text-black"></i>
             <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{count($callConsultations)}} Calls</p>
           </div>
         </div>
           @php
            $videoConsultations = $appointments->where('package', 1);
           @endphp
         <div class="col-md-3 col-6 mt-3 mt-md-0">
           <div class="card bg-3 text-center text-white pt-3 mb-1">
             <i class="bi bi-camera-video text-black fs-2 text"></i>
             <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2">{{count($videoConsultations)}} Videos</p>
           </div>
         </div>

         <div class="col-md-3 col-6 mt-3 mt-md-0">
           <div class="card bg-4 text-center text-white pt-3 mb-1">
             <i class="bi bi-chat-left-text fs-2 text-black"></i>
             <p class="text-uppercase mt-3 text-center text-black fw-bold min-hhh2"> {{count($callConsultations)+count($videoConsultations)+count($chatConsultations)}} Total</p>
           </div>
         </div>
       </div> -->

       <div class="tab-scroll-wrapper mt-3">
         <ul class="nav dashboard_tab nav-tabs dash-tab astro-account-tab mt-3" id="myTab" role="tablist">
           <li class="nav-item" role="presentation">
             <a class="nav-link text-dark {{$tab == 'chat' ? 'active' : ''}}" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false" tabindex="-1">Chat</a>
           </li>
           <li class="nav-item" role="presentation">
             <a class="nav-link text-dark {{$tab == 'call' ? 'active' : ''}}" id="address-tab" data-bs-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="true">Call</a>
           </li>
           <li class="nav-item" role="presentation">
             <a class="nav-link text-dark {{$tab == 'video' ? 'active' : ''}}" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Video</a>
           </li>
         </ul>
       </div>
       <div class="tab-content" id="myTabContent">
         <div class="tab-pane fade {{$tab == 'chat' ? 'active show' : ''}}" id="home" role="tabpanel" aria-labelledby="home-tab">
           <div class="card bg-light mb-3 mt-3">
             <div class="card-body pb-0">
               <div class="row">
                 @php
                   $i = 0;
                 @endphp
                 @forelse($chatConsultations as $key => $item)
                   @php
                     $astrologer = $item->astrologer;
                     $scheduledStartTime = strtotime($item->scheduled_date.' '.$item->scheduled_time);
                     $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->scheduled_end_time);
                     $duration = ceil(($scheduledEndTime - $scheduledStartTime)/60);

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
                     if($duration < 0){
                       $duration = 0;
                     }
                     $i++;
                   @endphp

                   <div class="col-md-4 col-sm-6 mb-4">
                     <div class="earning-card bordr-n p-2 bg-light rounded">

                       <div class="d-flex justify-content-between align-items-center mb-2">
                         <span class="badge k_bg_badge">#{{$i}}</span>
                         <span class="text-muted small">{{date('d-m-Y h:i A', $scheduledStartTime)}}</span>
                       </div>
                       <div class="d-flex justify-content-between mb-2">
                         <span class=""><i class="bi bi-person"></i> Astrologer</span>
                         <span class="text-dark fw-bold">{{ $astrologer ? $astrologer->display_name : 'Astrologer' }}</span>
                       </div>

                       <div class="d-flex justify-content-between mb-2">
                         <span><i class="bi bi-currency-rupee"></i> /min</span>
                         <span class="text-primary fw-bold">₹ {{$item->actualprice}}</span>
                       </div>

                       <div class="d-flex justify-content-between mb-2">
                         <span><i class="bi bi-clock"></i> Duration</span>
                         <span class="text-dark fw-bold">{{ $duration }} min</span>
                       </div>

                       <div class="d-flex justify-content-between border-top pt-2">
                         <span><i class="bi bi-currency-rupee"></i> Amount</span>
                         @if($item->free)
                          @php
                            $totalPrice = $item->total_price - $item->discount;
                          @endphp
                          <span class="text-success fw-bold">₹ {{ $totalPrice > 0 ? $totalPrice : 0 }} (free)</span>
                         @else
                          <span class="text-success fw-bold">₹ {{$item->total_price}}</span>
                         @endif
                       </div>

                       <div class="d-flex justify-content-between border-top pt-2">
                         <span><i class="bi bi-lightning-charge"></i> Action</span>
                         <span>
                           @if($item->status == 'cancel' || $item->user_status == 'cancel')
                             <span class="text-secondary fw-bold">Cancelled</span>
                           @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                             <a class="text-primary fw-bold text-decoration-underline" href="{{route('user.startchat', [$item->room_id])}}">
                               {{ $item->total_price > 0 ? 'Completed' : 'Join' }}
                             </a>
                           @elseif(time() > $scheduledStartTime)
                             <a class="text-primary fw-bold text-decoration-underline" href="{{route('user.startchat', [$item->room_id])}}">Join</a>
                           @else
                             <span class="text-danger fw-bold">Wait</span>
                             @if(date('Y-m-d') != date('Y-m-d', $scheduledStartTime) && $item->appointment_id == 0 )
                               <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                               <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                             @endif
                           @endif
                         </span>
                       </div>
                       @php
                        $review = $reviews->where("appointment_id", $item->id)->where("astrologer_id", $item->astrologer_id)->first();
                        $rating = "";
                        $comment = "";
                        $status = 0;
                        if($review){
                          $rating = $review->rating;
                          $comment = $review->comment;
                          $status = $review->status;
                        }
                       @endphp
                       <div class="d-flex justify-content-between border-top pt-2">
                         @if($item->status == "done" && $status == 0)
                           <a href="javascript:void(0)" class="text-primary fw-bold" onclick="handleReviewModal('open', '{{ $item->id }}', '{{ $item->astrologer_id }}', '{{ $rating }}', '{{ $comment }}')">Review</a>
                         @endif
                       </div>

                     </div>
                   </div>
                 @empty
                   <div class="col-12">
                     <div class="no-data text-center">
                       <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                       <h5 class="mt-3 text-dark"> No Chat Consultation Found!</h5>
                       <p class="text-secondary"> Consultation bookings and history will be displayed here.</p>
                     </div>
                   </div>
                 @endforelse
               </div>

             </div>
           </div>
         </div>

         <div class="tab-pane fade {{$tab == 'call' ? 'active show' : ''}}" id="address" role="tabpanel" aria-labelledby="address-tab">
           <div class="card bg-light mb-3 mt-3">
             <div class="card-body pb-0">
               <div class="row">
                @php
                  $i = 0;
                @endphp
                @forelse($callConsultations as $key => $item)
                  @php
                    $astrologer = $item->astrologer;
                    $scheduledStartTime = strtotime($item->scheduled_date.' '.$item->scheduled_time);
                    $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->scheduled_end_time);
                    $duration = ceil(($scheduledEndTime - $scheduledStartTime)/60);

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
                    if($duration < 0){
                      $duration = 0;
                    }
                    $i++;
                  @endphp
                  <div class="col-md-4 col-sm-6 mb-4">
                    <div class="earning-card border-n p-2 bg-light rounded">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary">#{{$i}}</span>
                        <span class="text-muted small">{{date('d-m-Y h:i A', $scheduledStartTime)}}</span>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-person"></i> Astrologer</span>
                        <span class="text-dark fw-bold">{{ $astrologer ? $astrologer->display_name : 'Astrologer' }}</span>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-currency-rupee"></i> /min</span>
                        <span class="text-primary fw-bold">₹{{ $item->actualprice }}</span>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-clock"></i> Duration</span>
                        <span class="text-dark fw-bold">{{ $duration }} min</span>
                      </div>
                      <div class="d-flex justify-content-between border-top pt-2">
                        <span><i class="bi bi-currency-rupee"></i> Amount</span>
                        <span class="text-success fw-bold">₹{{$item->total_price}}</span>
                      </div>
                      {{--<div class="d-flex justify-content-between border-top pt-2">
                        <span><i class="bi bi-telephone"></i> Action</span>
                        <span>
                          @if($item->status == 'cancel' || $item->user_status == 'cancel')
                            <span class="text-secondary">Cancelled</span>
                          @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                            @if($item->total_price > 0)
                              @php
                                $callHistory = null;
                                if($item->callHistory){
                                  $callHistory = $item->callHistory;
                                }
                              @endphp
                              <a href="{{$callHistory && $callHistory->recordingUrl ? $callHistory->recordingUrl : '#'}}" target="_blank">Completed</a>
                            @else
                              <span class="text-secondary">Completed</span>
                            @endif
                          @elseif(time() > $scheduledStartTime)
                            <a class="text-primary text-decoration-underline" href="javascript:void(0)">On Call</a>
                          @else
                            <span class="text-secondary">Wait</span>
                            @if(date('Y-m-d') != date('Y-m-d', $scheduledStartTime) && $item->appointment_id == 0 )
                              <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                              <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                            @endif
                          @endif
                        </span>
                      </div>--}}

                      <div class="d-flex justify-content-between border-top pt-2">
                        <span><i class="bi bi-lightning-charge"></i> Action</span>
                        <span>
                          @if($item->status == 'cancel' || $item->user_status == 'cancel')
                            <span class="text-secondary fw-bold">Cancelled</span>
                          @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                            <span class="text-primary fw-bold">{{ $item->total_price > 0 ? 'Completed' : 'Join' }}</span>
                          @elseif(time() > $scheduledStartTime)
                            <a class="text-primary fw-bold text-decoration-underline" href="{{route('user.startphonecall', [$item->room_id])}}">Join</a>
                          @else
                            <span class="text-danger fw-bold">Wait</span>
                            @if(date('Y-m-d') != date('Y-m-d', $scheduledStartTime) && $item->appointment_id == 0 )
                              <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                              <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                            @endif
                          @endif
                        </span>
                      </div>
                      @php
                       $review = $reviews->where("appointment_id", $item->id)->where("astrologer_id", $item->astrologer_id)->first();
                       $rating = "";
                       $comment = "";
                       $status = 0;
                       if($review){
                         $rating = $review->rating;
                         $comment = $review->comment;
                         $status = $review->status;
                       }
                      @endphp
                      <div class="d-flex justify-content-between border-top pt-2">
                        @if($item->status == "done" && $status == 0)
                          <a href="javascript:void(0)" class="text-primary fw-bold" onclick="handleReviewModal('open', '{{ $item->id }}', '{{ $item->astrologer_id }}', '{{ $rating }}', '{{ $comment }}')">Review</a>
                        @endif
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="col-12">
                    <div class="no-data text-center">
                      <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                      <h5 class="mt-3 text-dark"> No Call Consultation Found!</h5>
                      <p class="text-secondary"> Consultation bookings and history will be displayed here.</p>
                    </div>
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade {{$tab == 'video' ? 'active show' : ''}}" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <div class="card bg-light mb-3 mt-3">
            <div class="card-body pb-0">
              <div class="row">
                @php
                  $i = 0;
                @endphp
                @forelse($videoConsultations as $key => $item)
                  @php
                    $astrologer = $item->astrologer;
                    $scheduledStartTime = strtotime($item->scheduled_date.' '.$item->scheduled_time);
                    $scheduledEndTime = strtotime($item->scheduled_date.' '.$item->scheduled_end_time);
                    $duration = ceil(($scheduledEndTime - $scheduledStartTime)/60);

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
                    if($duration < 0){
                      $duration = 0;
                    }
                    $i++;
                  @endphp

                  <div class="col-md-4 col-sm-6 mb-4">
                    <div class="earning-card border-n p-2 bg-light rounded">

                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary">#{{$i}}</span>
                        <span class="text-muted small">{{date('d-m-Y h:i A', $scheduledStartTime)}}</span>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-person"></i> Astrologer</span>
                        <span class="text-dark fw-bold">{{ $astrologer ? $astrologer->display_name : 'Astrologer' }}</span>
                      </div>

                      <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-currency-rupee"></i>/min</span>
                        <span class="text-primary fw-bold">₹ {{$item->actualprice}}</span>
                      </div>

                      <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-clock"></i> Duration</span>
                        <span class="text-dark fw-bold">{{ $duration }} min</span>
                      </div>

                      <div class="d-flex justify-content-between border-top pt-2">
                        <span><i class="bi bi-currency-rupee"></i> Amount</span>
                        <span class="text-success fw-bold">₹ {{$item->total_price}}</span>
                      </div>

                      <div class="d-flex justify-content-between border-top pt-2">
                        <span><i class="bi bi-lightning-charge"></i> Action</span>
                        <span>
                          @if($item->status == 'cancel' || $item->user_status == 'cancel')
                            <span class="text-secondary fw-bold">Cancelled</span>
                          @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                            <span class="text-primary fw-bold">{{ $item->total_price > 0 ? 'Completed' : 'Join' }}</span>
                          @elseif(time() > $scheduledStartTime)
                            <a class="text-primary fw-bold text-decoration-underline" href="{{route('user.startvideocall', [$item->room_id])}}">Join</a>
                          @else
                            <span class="text-danger fw-bold">Wait</span>
                            @if(date('Y-m-d') != date('Y-m-d', $scheduledStartTime) && $item->appointment_id == 0 )
                              <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                              <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                            @endif
                          @endif
                        </span>
                      </div>
                      @php
                       $review = $reviews->where("appointment_id", $item->id)->where("astrologer_id", $item->astrologer_id)->first();
                       $rating = "";
                       $comment = "";
                       $status = 0;
                       if($review){
                         $rating = $review->rating;
                         $comment = $review->comment;
                         $status = $review->status;
                       }
                      @endphp
                      <div class="d-flex justify-content-between border-top pt-2">
                        @if($item->status == "done" && $status == 0)
                          <a href="javascript:void(0)" class="text-primary fw-bold" onclick="handleReviewModal('open', '{{ $item->id }}', '{{ $item->astrologer_id }}', '{{ $rating }}', '{{ $comment }}')">Review</a>
                        @endif
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="col-12">
                    <div class="no-data text-center">
                      <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                      <h5 class="mt-3 text-dark"> No Video Consultation Found!</h5>
                      <p class="text-secondary"> Consultation bookings and history will be displayed here.</p>
                    </div>
                  </div>
                @endforelse
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

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
              <div class="validation text-danger fs-8 d-none" id="message-error"></div>
              <div class="form-group my-2">
                <button type="submit" class="btn btn-secondary px-4 py-2 profile-save-btn submit_btn" style="height: 100%;">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg astrologer_rev">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">Write Review</h5>
        <button type="button" class="btn-close" onclick="handleReviewModal('close', 0, 0, 0, '')"></button>
      </div>

      <div class="modal-body">
        <form id="review_form">
          <input type="hidden" name="appointment_id" id="review_appointment_id">
          <input type="hidden" name="astrologer_id" id="astrologer_id">
          <input type="hidden" name="rating" id="rating" value="">
          <div class="col-md-12 stars rati mb-3">
            <i class="bi bi-star" data-value="1" id="star-1"></i>
            <i class="bi bi-star" data-value="2" id="star-2"></i>
            <i class="bi bi-star" data-value="3" id="star-3"></i>
            <i class="bi bi-star" data-value="4" id="star-4"></i>
            <i class="bi bi-star" data-value="5" id="star-5"></i>
          </div>
          <div class="mb-3">
            <textarea name="comment" id="comment" rows="5" class="form-control" placeholder="Write your review..."></textarea>
          </div>
          <div class="text-end">
            <button type="submit" class=" p-add">Submit</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection

  @push('scripts')

</section>
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
        toastr.success(response.message);
        closeModel()
      },
      error:function(error){
        console.log(error);
        if(error.responseJSON && error.responseJSON.errors){
          for (var key in error.responseJSON.errors) {
            toastr.error(error.responseJSON.errors[key][0]);
          }
        }else if(error.responseJSON){
          toastr.error(error.responseJSON.message);
          // closeModel();
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

  document.querySelectorAll(".rati i").forEach(star => {
    star.addEventListener("click", function() {
      let value = this.getAttribute("data-value");
      document.querySelectorAll(".rati i").forEach(s => {
        s.classList.remove("bi-star-fill");
        s.classList.add("bi-star");
      });
      for (let i = 0; i < value; i++) {
        let starEl = document.querySelectorAll(".rati i")[i];
        starEl.classList.remove("bi-star");
        starEl.classList.add("bi-star-fill");
      }
      document.getElementById("rating").value = value;
    });
  });

  function handleReviewModal(action, appointment_id, astrologer_id, rating, comment){
    $("#review_appointment_id").val(appointment_id);
    $("#astrologer_id").val(astrologer_id);
    $("#comment").val(comment);
    rating = Number(rating);
    $("#star-"+rating).click();

    if(action == "open"){
      $("#reviewModal").modal("show");
    }else{
      $("#reviewModal").modal("hide");
    }
  }

  $('#review_form').on('submit', function(e){
      e.preventDefault();
      let formData = $(this).serialize();
      $.ajax({
          url: "{{ route('user.astrologer.review.submit') }}",
          method: 'POST',
          data: formData,
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            toastr.success(response.message);
            setTimeout(function() {
              window.location.reload();
            }, 1000);
          },
          error: function(error){
            let errors = error.responseJSON.errors;
              if(errors){
                for(let key in errors){
                  toastr.error(errors[key][0]);
                }
              }else{
                let message = error.responseJSON.message;
                if(message){
                  toastr.error(message);
                }
              }
          }
      });
  });

</script>
@endpush
