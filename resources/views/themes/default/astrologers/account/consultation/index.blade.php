@extends(themeView().'layout')

@section('title', 'Consultations | '.config("app.name"))

@section('content')
@php
  $_token=request()->has('_token')?request()->get("_token"):'';
@endphp
<section class="dashboad_section" style="background: #f2f2f2;">
 <div class="container top-space mb-3" id="profile-app">
  <div class="row">
  @include(themeView().'astrologers._partials.sidebar')
  @if($user->is_verified==1)
    <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
      <div class="row">
        @php
          $chatConsultations = $appointments->where('package', 2);
        @endphp
        <div class="col-6 col-md-3">
          <a href="">
            <div class="same-card">
                <div class="same-icon" style="background:#28a745;">
                    <img src="/assets/img/chat.png" alt="">
                </div>
                <p>{{count($chatConsultations)}} Chats</p>
            </div>
          </a>
        </div>


        @php
          $callConsultations = $appointments->where('package', 3);
        @endphp
        <div class="col-6 col-md-3 ">
          <a href="">
            <div class="same-card">
                <div class="same-icon" style="background:#5bc0de;">
                    <img src="/assets/img/phone.png" alt="">
                </div>
                <p>{{count($callConsultations)}} Calls</p>
            </div>
          </a>
        </div>
        @php
           $videoConsultations = $appointments->where('package', 1);
        @endphp


        <div class="col-6 col-md-3">
          <a href="">
            <div class="same-card">
                <div class="same-icon" style="background:#ff4444;">
                    <img src="/assets/img/video-camera.png" alt="">
                </div>
                <p> Videos</p>
            </div>
          </a>
        </div>

        <div class="col-6 col-md-3">
          <a href="">
            <div class="same-card">
                <div class="same-icon" style="background:#d4a017;">
                    <img src="/assets/img/savings.png" alt="">
                </div>
                <p>{{count($callConsultations)+count($videoConsultations)+count($chatConsultations)}} Total</p>
            </div>
          </a>
        </div>




  <div class="tab-scroll-wrapper ps-2">
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
        <li class="nav-item" role="presentation">
          <a class="nav-link text-dark {{$tab == 'suggested-remedies' ? 'active' : ''}}" id="suggested-remedies-tab" data-bs-toggle="tab" href="#suggested-remedies" role="tab" aria-controls="suggested-remedies" aria-selected="false" tabindex="-1">Suggested Remedies</a>
        </li>
      </ul>
    </div>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade {{$tab == 'chat' ? 'active show' : ''}}" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="card bg-light mb-3 mt-3 border-n">
          <div class="card-body pb-0">
            <div class="row">
              @php
                $i = 0;
              @endphp
              @forelse($chatConsultations as $key => $item)
                @php
                  $user = $item->user;
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
                  <div class="earning-card shadow-sm p-3 bg-light rounded">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="badge bg-primary">#{{$i}}</span>
                      <span class="text-muted small">{{date('d-m-Y h:i A', $scheduledStartTime)}}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                      <span><i class="bi bi-person"></i> User</span>
                      <span class="text-dark fw-bold">{{ $user ? $user->name : 'User' }}</span>
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
                      @php
                        $redictToUrl=route('astrologer.startchat',[$item->room_id]);
                        if($_token){
                          $redictToUrl=route('astrologer.startchat', [$item->room_id,"_token"=>$_token]);
                        }
                      @endphp
                      <span>
                        @if($item->status == 'cancel' || $item->user_status == 'cancel')
                          <span class="text-secondary fw-bold">Cancelled</span>
                        @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                          <a class="text-primary fw-bold text-decoration-underline" href="{{ $redictToUrl }}">
                            {{ $item->total_price > 0 ? 'Completed' : 'Join' }}
                          </a>
                        @elseif(time() > $scheduledStartTime)
                          <a class="text-primary fw-bold text-decoration-underline" href="{{ $redictToUrl }}">Join</a>
                        @else
                          <span class="text-danger fw-bold">Wait</span>
                          @if(date('Y-m-d') != date('Y-m-d', $scheduledStartTime) && $item->appointment_id == 0 )
                            <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                            <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                          @endif
                        @endif
                      </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                      <span><i class="bi bi-card-text"></i> Kundali</span>
                      <span class="text-dark fw-bold">
                        @php
                          $gender=$item->gender?$item->gender:'male';
                        @endphp
                        <a class="text-primary fw-bold text-decoration-underline pointer" onclick="generateKundali(this,'{{ $item->name }}','{{ $gender }}','{{ $item->dateofbirth }}','{{ $item->birthtime }}','{{ $item->birthplace }}','en')">Click (english)</a>
                        <a class="text-primary fw-bold text-decoration-underline pointer" onclick="generateKundali(this,'{{ $item->name }}','{{ $gender }}','{{ $item->dateofbirth }}','{{ $item->birthtime }}','{{ $item->birthplace }}','hi')">Click (hindi)</a>
                      </span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2">
                      <span><i class="bi bi-heart-pulse"></i> Remedies</span>
                      <button type="button" class="btn text-white fw-bold px-3 py-2" style="background-color: #1e3a8a; border-radius: 8px; border: none;" onclick="openSuggestRemediesModal({{ $item->id }}, {{ $item->user_id }})">Suggest Remedies</button>
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
                 $user = $item->user;
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
                 <div class="earning-card shadow-sm p-3 bg-light rounded">
                   <div class="d-flex justify-content-between align-items-center mb-2">
                     <span class="badge bg-primary">#{{$i}}</span>
                     <span class="text-muted small">{{date('d-m-Y h:i A', $scheduledStartTime)}}</span>
                   </div>
                   <div class="d-flex justify-content-between mb-2">
                     <span><i class="bi bi-person"></i> User</span>
                     <span class="text-dark fw-bold">{{ $user ? $user->name : 'User' }}</span>
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
                     @php
                       $redictToUrl=route('astrologer.startphonecall',[$item->room_id]);
                       if($_token){
                         $redictToUrl=route('astrologer.startphonecall', [$item->room_id,"_token"=>$_token]);
                       }
                     @endphp
                     <span>
                       @if($item->status == 'cancel' || $item->user_status == 'cancel')
                         <span class="text-secondary fw-bold">Cancelled</span>
                       @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                         <span class="text-primary fw-bold">{{ $item->total_price > 0 ? 'Completed' : 'Join' }}</span>
                       @elseif(time() > $scheduledStartTime)
                         <a class="text-primary fw-bold text-decoration-underline" href="{{ $redictToUrl }}">Join</a>
                       @else
                         <span class="text-danger fw-bold">Wait</span>
                         @if(date('Y-m-d') != date('Y-m-d', $scheduledStartTime) && $item->appointment_id == 0 )
                           <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                           <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                         @endif
                       @endif
                     </span>
                   </div>
                   <div class="d-flex justify-content-between mb-2">
                     <span><i class="bi bi-card-text"></i> Kundali</span>
                     <span class="text-dark fw-bold">
                       @php
                         $gender=$item->gender?$item->gender:'male';
                       @endphp
                       <a class="text-primary fw-bold text-decoration-underline pointer" onclick="generateKundali(this,'{{ $item->name }}','{{ $gender }}','{{ $item->dateofbirth }}','{{ $item->birthtime }}','{{ $item->birthplace }}','en')">Click (english)</a>
                       <a class="text-primary fw-bold text-decoration-underline pointer" onclick="generateKundali(this,'{{ $item->name }}','{{ $gender }}','{{ $item->dateofbirth }}','{{ $item->birthtime }}','{{ $item->birthplace }}','hi')">Click (hindi)</a>
                     </span>
                   </div>
                   <div class="d-flex justify-content-between border-top pt-2">
                     <span><i class="bi bi-heart-pulse"></i> Remedies</span>
                     <button type="button" class="btn text-white fw-bold px-3 py-2" style="background-color: #1e3a8a; border-radius: 8px; border: none;" onclick="openSuggestRemediesModal({{ $item->id }}, {{ $item->user_id }})">Suggest Remedies</button>
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
                 $user = $item->user;
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
                 <div class="earning-card shadow-sm p-3 bg-light rounded">

                   <div class="d-flex justify-content-between align-items-center mb-2">
                     <span class="badge bg-primary">#{{$i}}</span>
                     <span class="text-muted small">{{date('d-m-Y h:i A', $scheduledStartTime)}}</span>
                   </div>
                   <div class="d-flex justify-content-between mb-2">
                     <span><i class="bi bi-person"></i> User</span>
                     <span class="text-dark fw-bold">{{ $user ? $user->name : 'User' }}</span>
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
                     @php
                       $redictToUrl=route('astrologer.startphonecall',[$item->room_id]);
                       if($_token){
                         $redictToUrl=route('astrologer.startphonecall', [$item->room_id,"_token"=>$_token]);
                       }
                     @endphp
                     <span>
                       @if($item->status == 'cancel' || $item->user_status == 'cancel')
                         <span class="text-secondary fw-bold">Cancelled</span>
                       @elseif(time() > strtotime($item->scheduled_date.' '.$item->scheduled_end_time))
                         <span class="text-primary fw-bold">{{ $item->total_price > 0 ? 'Completed' : 'Join' }}</span>
                       @elseif(time() > $scheduledStartTime)
                         <a class="text-primary fw-bold text-decoration-underline" href="{{ $redictToUrl }}">Join</a>
                       @else
                         <span class="text-danger fw-bold">Wait</span>
                         @if(date('Y-m-d') != date('Y-m-d', $scheduledStartTime) && $item->appointment_id == 0 )
                           <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'cancel')"></i>
                           <i class="bi bi-clock text-primary pointer ms-2" onclick="openModel('{{$item->id}}', 'reschedule')"></i>
                         @endif
                       @endif
                     </span>
                   </div>
                   <div class="d-flex justify-content-between mb-2">
                     <span><i class="bi bi-card-text"></i> Kundali</span>
                     <span class="text-dark fw-bold">
                       @php
                         $gender=$item->gender?$item->gender:'male';
                       @endphp
                       <a class="text-primary fw-bold text-decoration-underline pointer" onclick="generateKundali(this,'{{ $item->name }}','{{ $gender }}','{{ $item->dateofbirth }}','{{ $item->birthtime }}','{{ $item->birthplace }}','en')">Click (english)</a>
                       <a class="text-primary fw-bold text-decoration-underline pointer" onclick="generateKundali(this,'{{ $item->name }}','{{ $gender }}','{{ $item->dateofbirth }}','{{ $item->birthtime }}','{{ $item->birthplace }}','hi')">Click (hindi)</a>
                     </span>
                   </div>
                   <div class="d-flex justify-content-between border-top pt-2">
                     <span><i class="bi bi-heart-pulse"></i> Remedies</span>
                     <button type="button" class="btn text-white fw-bold px-3 py-2" style="background-color: #1e3a8a; border-radius: 8px; border: none;" onclick="openSuggestRemediesModal({{ $item->id }}, {{ $item->user_id }})">Suggest Remedies</button>
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

     <!-- Suggested Remedies Tab -->
     <div class="tab-pane fade {{$tab == 'suggested-remedies' ? 'active show' : ''}}" id="suggested-remedies" role="tabpanel" aria-labelledby="suggested-remedies-tab">
       <div class="card bg-light mb-3 mt-3">
         <div class="card-body pb-0">
           <div class="row">
             @forelse($suggestedRemedies as $suggestion)
               <div class="col-12 mb-4">
                 <div class="card shadow-sm">
                   <div class="card-header bg-primary text-white">
                     <div class="d-flex justify-content-between align-items-center">
                       <div>
                         <h6 class="mb-0"><i class="bi bi-person"></i> {{ $suggestion['user_name'] }}</h6>
                         <small>{{ date('d M Y, h:i A', strtotime($suggestion['created_at'])) }}</small>
                       </div>
                     </div>
                   </div>
                   <div class="card-body">
                     @if(count($suggestion['products']) > 0)
                       <h6 class="mb-3">Products:</h6>
                       <div class="row">
                         @foreach($suggestion['products'] as $product)
                           @php
                             $productUrl = route('product.detail', [$product['slug']]);
                             if($suggestion['referral_code']){
                               $productUrl .= '?ref=' . urlencode($suggestion['referral_code']);
                             }
                           @endphp
                           <div class="col-md-6 mb-2">
                             <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                               <span style="font-size: 12px; cursor: pointer; color: #1e3a8a;" class="fw-bold product-link" onclick="copyProductUrl('{{ $productUrl }}', event)">
                                 <strong>{{ $product['title'] }}</strong>
                               </span>
                               <span class="text-primary fw-bold">₹{{ number_format($product['price'], 2) }}</span>
                             </div>
                           </div>
                         @endforeach
                       </div>
                     @endif

                         @if(count($suggestion['reports']) > 0)
                           <h6 class="mb-3 mt-3">Reports:</h6>
                           <div class="row">
                             @foreach($suggestion['reports'] as $report)
                               @php
                                 $reportUrl = route('product.detailservice', [$report['slug']]);
                                 if($suggestion['referral_code']){
                                   $reportUrl .= '?ref=' . urlencode($suggestion['referral_code']);
                                 }
                               @endphp
                               <div class="col-md-6 mb-2">
                                 <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                   <span style="font-size: 12px; cursor: pointer; color: #1e3a8a;" class="fw-bold product-link" onclick="copyProductUrl('{{ $reportUrl }}', event)">
                                     <strong>{{ $report['title'] }}</strong>
                                   </span>
                                   <span class="text-primary fw-bold">₹{{ number_format($report['price'], 2) }}</span>
                                 </div>
                               </div>
                             @endforeach
                           </div>
                         @endif
                         @if(count($suggestion['pujas'] ?? []) > 0)
                           <h6 class="mb-3 mt-3">Pujas:</h6>
                           <div class="row">
                             @foreach($suggestion['pujas'] as $puja)
                               @php
                                 $pujaUrl = route('product.detailpuja', [$puja['slug']]);
                                 if($suggestion['referral_code']){
                                   $pujaUrl .= '?ref=' . urlencode($suggestion['referral_code']);
                                 }
                               @endphp
                               <div class="col-md-6 mb-2">
                                 <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                   <span style="font-size: 12px; cursor: pointer; color: #1e3a8a;" class="fw-bold product-link" onclick="copyProductUrl('{{ $pujaUrl }}', event)">
                                     <strong>{{ $puja['title'] }}</strong>
                                   </span>
                                   <span class="text-primary fw-bold">₹{{ number_format($puja['price'], 2) }}</span>
                                 </div>
                               </div>
                             @endforeach
                           </div>
                         @endif
                       </div>
                     </div>
                   </div>
                 @empty
               <div class="col-12">
                 <div class="no-data text-center">
                   <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                   <h5 class="mt-3 text-dark">No Suggested Remedies Found!</h5>
                   <p class="text-secondary">Remedies you suggest to users will be displayed here.</p>
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

    <style>
      #remedy_search_results {
        position: absolute;
        z-index: 1050;
        background: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        width: 100%;
      }
      #remedy_search_results .form-check {
        display: flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        padding: 8px !important;
      }
      #remedy_search_results .form-check-input {
        margin-top: 0 !important;
        margin-right: 12px !important;
        margin-left: 0 !important;
        margin-bottom: 0 !important;
        flex-shrink: 0 !important;
        width: 18px !important;
        height: 18px !important;
        cursor: pointer;
        vertical-align: middle;
        position: static !important;
        float: none !important;
      }
      #remedy_search_results .form-check-label {
        flex-grow: 1;
        margin-bottom: 0 !important;
        cursor: pointer;
        line-height: 1.5;
        display: flex !important;
        align-items: center !important;
      }
      #remedy_search_results .form-check:hover {
        background-color: #f8f9fa;
      }
      #selected_remedies_list .badge {
        font-size: 0.9em;
      }
      #selected_remedies_list .btn-close {
        opacity: 0.8;
      }
      #selected_remedies_list .btn-close:hover {
        opacity: 1;
      }
      #products_dropdown_text, #reports_dropdown_text {
        visibility: visible !important;
        opacity: 1 !important;
        display: inline-block !important;
        color: #212529 !important;
      }
      #products_dropdown_btn:hover #products_dropdown_text,
      #reports_dropdown_btn:hover #products_dropdown_text,
      #products_dropdown_btn:focus #products_dropdown_text,
      #reports_dropdown_btn:focus #reports_dropdown_text {
        visibility: visible !important;
        opacity: 1 !important;
        color: #212529 !important;
      }
      #products_dropdown_btn, #reports_dropdown_btn {
        color: #212529 !important;
      }
      #products_dropdown_btn span, #reports_dropdown_btn span {
        color: #212529 !important;
      }
      .dropdown-menu.show {
        display: block !important;
      }
    </style>
    <!-- Suggest Remedies Modal -->
    <div class="modal fade" id="suggestRemediesModal" tabindex="-1" aria-labelledby="suggestRemediesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="suggestRemediesModalLabel">Suggest Remedies</h5>
            <button type="button" class="btn-close" onclick="closeSuggestRemediesModal()"></button>
          </div>
          <div class="modal-body">
            <form id="suggestRemediesForm" onsubmit="return submitSuggestRemedies()">
              <input type="hidden" id="remedy_appointment_id" name="appointment_id" value="">
              <input type="hidden" id="remedy_user_id" name="user_id" value="">

              <!-- Search Box -->
              <div class="mb-3 position-relative">
                <label for="remedy_search" class="form-label mb-2">Search Products, Reports & Pujas <span class="text-danger">*</span></label>
                <input type="text" id="remedy_search" class="form-control" placeholder="Type to search products, reports and pujas..." autocomplete="off">
                <div id="remedy_search_results" class="border rounded mt-2" style="max-height: 300px; overflow-y: auto; display: none;">
                  <!-- Search results will appear here -->
                </div>
              </div>

              <!-- Selected Items Display -->
              <div class="mb-3">
                <label class="form-label mb-2">Selected Items</label>
                <div id="selected_remedies_list" class="border rounded p-3" style="min-height: 100px; background-color: #f8f9fa;">
                  <p class="text-muted mb-0">No items selected yet</p>
                </div>
              </div>

              <input type="hidden" id="products_select" name="products[]" value="">
              <input type="hidden" id="reports_select" name="reports[]" value="">
              <input type="hidden" id="pujas_select" name="pujas[]" value="">

              <div class="validation text-danger fs-8 d-none" id="remedies-error"></div>
              <div class="form-group my-2">
                <button type="submit" class="btn btn-secondary px-4 py-2" id="submitRemediesBtn">Submit</button>
                <button type="button" class="btn btn-secondary px-4 py-2" onclick="closeSuggestRemediesModal()">Cancel</button>
              </div>
            </form>
          </div>
        </div>
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
      url:@json(route('astrologer.consultation.request')),
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
</script>
<script type="text/javascript">
  function generateKundali(that,name,gender,date,time,city,lang){
    $(that).prop('disabled',true).text("Generating...");
    let formData={
      name,gender,date,time,city,lang
    };
    $.ajax({
      url:"{{ route('kundali.kundalienquiry') }}",
      type:"POST",
      data:formData,
      headers:{
        'X-CSRF-TOKEN':'{{ csrf_token() }}'
      },
      success:function(response){
        $(that).prop('disabled',false).text(`Click (${lang})`);
        toastr.success(response.message);
        if(response.redirectTo){
          window.open(response.redirectTo, '_blank');
        }
      },
      error:function(xhr){
        $(that).prop('disabled',false).text(`Click (${lang})`);
        console.log(xhr);
        let errors=xhr.responseJSON.errors;
        for(let key in errors){
          toastr.error(errors[key][0]);
        }
        submitBtn.prop("disabled",false);
      }
    });
    return false;
  }

  let selectedProducts = [];
  let selectedReports = [];
  let allProducts = [];
  let allReports = [];
  let searchTimeout = null;

  function openSuggestRemediesModal(appointmentId, userId){
    $('#remedy_appointment_id').val(appointmentId);
    $('#remedy_user_id').val(userId);
    selectedProducts = [];
    selectedReports = [];
    selectedPujas = [];
    allProducts = [];
    allReports = [];
    allPujas = [];
    $('#products_select').val('');
    $('#reports_select').val('');
    $('#pujas_select').val('');
    $('#remedy_search').val('');
    $('#remedy_search_results').hide().html('');
    $('#remedies-error').addClass('d-none');
    updateSelectedRemediesList();

    // Load products, reports and pujas
    loadProductsAndReports();
    $('#suggestRemediesModal').modal('show');
  }

  function closeSuggestRemediesModal(){
    $('#suggestRemediesModal').modal('hide');
    $('#remedy_appointment_id').val('');
    $('#remedy_user_id').val('');
    selectedProducts = [];
    selectedReports = [];
    selectedPujas = [];
    allProducts = [];
    allReports = [];
    allPujas = [];
    $('#products_select').val('');
    $('#reports_select').val('');
    $('#pujas_select').val('');
    $('#remedy_search').val('');
    $('#remedy_search_results').hide().html('');
    $('#remedies-error').addClass('d-none');
    updateSelectedRemediesList();
  }

  function loadProductsAndReports(){
    $.ajax({
      url: "{{ route('astrologer.remedies.get-products-reports') }}",
      type: "GET",
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response){
        if(response.success){
          allProducts = response.products || [];
          allReports = response.reports || [];
          allPujas = response.pujas || [];
        }
      },
      error: function(xhr){
        console.log(xhr);
        toastr.error('Failed to load products and reports');
      }
    });
  }

  // Search functionality
  $(document).on('input', '#remedy_search', function(){
    let searchTerm = $(this).val().toLowerCase().trim();
    let resultsContainer = $('#remedy_search_results');

    clearTimeout(searchTimeout);

    if(searchTerm.length < 2){
      resultsContainer.hide().html('');
      return;
    }

    searchTimeout = setTimeout(function(){
      let results = [];

      // Search in products
      allProducts.forEach(function(product){
        if(product.title.toLowerCase().includes(searchTerm)){
          results.push({
            id: product.id,
            title: product.title,
            type: 'product',
            price: product.base_price || product.sale_price || 0
          });
        }
      });

      // Search in reports
      allReports.forEach(function(report){
        if(report.title.toLowerCase().includes(searchTerm)){
          results.push({
            id: report.id,
            title: report.title,
            type: 'report',
            price: report.base_price || report.sale_price || 0
          });
        }
      });

      // Search in pujas
      allPujas.forEach(function(puja){
        if(puja.title.toLowerCase().includes(searchTerm)){
          results.push({
            id: puja.id,
            title: puja.title,
            type: 'puja',
            price: puja.base_price || puja.sale_price || 0
          });
        }
      });

      if(results.length > 0){
        let resultsHtml = '<div class="p-2">';
        results.forEach(function(item){
          let isSelected = false;
          let checkboxId = '';
          if(item.type === 'product'){
            isSelected = selectedProducts.indexOf(item.id) !== -1;
            checkboxId = 'search_product_' + item.id;
          } else if(item.type === 'report'){
            isSelected = selectedReports.indexOf(item.id) !== -1;
            checkboxId = 'search_report_' + item.id;
          } else if(item.type === 'puja'){
            isSelected = selectedPujas.indexOf(item.id) !== -1;
            checkboxId = 'search_puja_' + item.id;
          }

          resultsHtml += '<div class="form-check p-2 border-bottom d-flex align-items-center" style="gap: 10px; min-height: 40px;">' +
            '<input class="form-check-input" type="checkbox" value="' + item.id + '" id="' + checkboxId + '" ' + (isSelected ? 'checked' : '') +
            ' onchange="toggleRemedyItem(' + item.id + ', \'' + item.type + '\', \'' + item.title.replace(/'/g, "\\'") + '\', this.checked)">' +
            '<label class="form-check-label flex-grow-1 mb-0 d-flex align-items-center" for="' + checkboxId + '" style="cursor: pointer; line-height: 1.5; min-height: 20px;">' +
            '<strong>' + item.title + '</strong> <span class="text-muted ms-1">(' + item.type + ')</span> <span class="text-primary ms-auto">₹' + item.price + '</span>' +
            '</label>' +
            '</div>';
        });
        resultsHtml += '</div>';
        resultsContainer.html(resultsHtml).show();
      } else {
        resultsContainer.html('<div class="p-3 text-muted text-center">No results found</div>').show();
      }
    }, 300);
  });

  // Close search results when clicking outside
  $(document).on('click', function(e){
    if(!$(e.target).closest('#remedy_search, #remedy_search_results').length){
      $('#remedy_search_results').hide();
    }
  });

  function toggleRemedyItem(itemId, itemType, itemTitle, isChecked){
    if(itemType === 'product'){
      if(isChecked){
        if(selectedProducts.indexOf(itemId) === -1){
          selectedProducts.push(itemId);
        }
      } else {
        let index = selectedProducts.indexOf(itemId);
        if(index > -1){
          selectedProducts.splice(index, 1);
        }
      }
      $('#products_select').val(selectedProducts.join(','));
    } else if(itemType === 'report'){
      if(isChecked){
        if(selectedReports.indexOf(itemId) === -1){
          selectedReports.push(itemId);
        }
      } else {
        let index = selectedReports.indexOf(itemId);
        if(index > -1){
          selectedReports.splice(index, 1);
        }
      }
      $('#reports_select').val(selectedReports.join(','));
    } else if(itemType === 'puja'){
      if(isChecked){
        if(selectedPujas.indexOf(itemId) === -1){
          selectedPujas.push(itemId);
        }
      } else {
        let index = selectedPujas.indexOf(itemId);
        if(index > -1){
          selectedPujas.splice(index, 1);
        }
      }
      $('#pujas_select').val(selectedPujas.join(','));
    }

    updateSelectedRemediesList();
    // Update checkbox in search results if it exists
    let checkboxId = '';
    if(itemType === 'product'){
      checkboxId = '#search_product_' + itemId;
    } else if(itemType === 'report'){
      checkboxId = '#search_report_' + itemId;
    } else if(itemType === 'puja'){
      checkboxId = '#search_puja_' + itemId;
    }
    if($(checkboxId).length){
      $(checkboxId).prop('checked', isChecked);
    }
  }

  function updateSelectedRemediesList(){
    let container = $('#selected_remedies_list');
    let allSelected = [];

    // Get selected products
    selectedProducts.forEach(function(productId){
      let product = allProducts.find(p => p.id == productId);
      if(product){
        allSelected.push({
          id: productId,
          title: product.title,
          type: 'product',
          price: product.base_price || product.sale_price || 0
        });
      }
    });

    // Get selected reports
    selectedReports.forEach(function(reportId){
      let report = allReports.find(r => r.id == reportId);
      if(report){
        allSelected.push({
          id: reportId,
          title: report.title,
          type: 'report',
          price: report.base_price || report.sale_price || 0
        });
      }
    });

    // Get selected pujas
    selectedPujas.forEach(function(pujaId){
      let puja = allPujas.find(p => p.id == pujaId);
      if(puja){
        allSelected.push({
          id: pujaId,
          title: puja.title,
          type: 'puja',
          price: puja.base_price || puja.sale_price || 0
        });
      }
    });

    if(allSelected.length === 0){
      container.html('<p class="text-muted mb-0">No items selected yet</p>');
    } else {
      let html = '<div class="d-flex flex-wrap gap-2">';
      allSelected.forEach(function(item){
        html += '<div class="badge bg-primary p-2 d-flex align-items-center gap-2">' +
          '<span>' + item.title + ' <span class="text-light">(' + item.type + ')</span> ₹' + item.price + '</span>' +
          '<button type="button" class="btn-close btn-close-white" style="font-size: 0.7em;" onclick="removeRemedyItem(' + item.id + ', \'' + item.type + '\')"></button>' +
          '</div>';
      });
      html += '</div>';
      container.html(html);
    }
  }

  function removeRemedyItem(itemId, itemType){
    if(itemType === 'product'){
      let index = selectedProducts.indexOf(itemId);
      if(index > -1){
        selectedProducts.splice(index, 1);
      }
      $('#products_select').val(selectedProducts.join(','));
    } else if(itemType === 'report'){
      let index = selectedReports.indexOf(itemId);
      if(index > -1){
        selectedReports.splice(index, 1);
      }
      $('#reports_select').val(selectedReports.join(','));
    } else if(itemType === 'puja'){
      let index = selectedPujas.indexOf(itemId);
      if(index > -1){
        selectedPujas.splice(index, 1);
      }
      $('#pujas_select').val(selectedPujas.join(','));
    }

    updateSelectedRemediesList();
    // Update checkbox in search results if it exists
    let checkboxId = '';
    if(itemType === 'product'){
      checkboxId = '#search_product_' + itemId;
    } else if(itemType === 'report'){
      checkboxId = '#search_report_' + itemId;
    } else if(itemType === 'puja'){
      checkboxId = '#search_puja_' + itemId;
    }
    if($(checkboxId).length){
      $(checkboxId).prop('checked', false);
    }
  }

  function submitSuggestRemedies(){
    $('#remedies-error').addClass('d-none');
    let appointmentId = $('#remedy_appointment_id').val();
    let userId = $('#remedy_user_id').val();
    let products = selectedProducts.length > 0 ? selectedProducts : [];
    let reports = selectedReports.length > 0 ? selectedReports : [];
    let pujas = selectedPujas.length > 0 ? selectedPujas : [];

    if(products.length === 0 && reports.length === 0 && pujas.length === 0){
      $('#remedies-error').removeClass('d-none').text('Please select at least one product, report or puja');
      return false;
    }

    $('#submitRemediesBtn').prop('disabled', true).text('Submitting...');

    $.ajax({
      url: "{{ route('astrologer.remedies.suggest') }}",
      type: "POST",
      data: {
        appointment_id: appointmentId,
        user_id: userId,
        products: products,
        reports: reports,
        pujas: pujas
      },
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response){
        $('#submitRemediesBtn').prop('disabled', false).text('Submit');
        if(response.success){
          toastr.success(response.message);
          closeSuggestRemediesModal();
        } else {
          toastr.error(response.message || 'Failed to submit remedies');
        }
      },
      error: function(xhr){
        $('#submitRemediesBtn').prop('disabled', false).text('Submit');
        console.log(xhr);
        if(xhr.responseJSON && xhr.responseJSON.errors){
          let errors = xhr.responseJSON.errors;
          let errorMsg = '';
          for(let key in errors){
            errorMsg += errors[key][0] + '<br>';
          }
          $('#remedies-error').removeClass('d-none').html(errorMsg);
        } else {
          toastr.error(xhr.responseJSON?.message || 'Failed to submit remedies');
        }
      }
    });
    return false;
  }

  // Function to copy product URL to clipboard when product name is clicked
  function copyProductUrl(productUrl, event){
    if(event){
      event.preventDefault();
      event.stopPropagation();
    }

    if(productUrl){
      // Create a temporary input element
      const tempInput = document.createElement('input');
      tempInput.value = productUrl;
      tempInput.style.position = 'fixed';
      tempInput.style.opacity = '0';
      document.body.appendChild(tempInput);
      tempInput.select();
      tempInput.setSelectionRange(0, 99999); // For mobile devices

      try {
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Show toast notification
        toastr.success('Product URL copied to clipboard!');
      } catch (err) {
        document.body.removeChild(tempInput);
        // Fallback: try modern clipboard API
        if(navigator.clipboard && navigator.clipboard.writeText){
          navigator.clipboard.writeText(productUrl).then(function() {
            toastr.success('Product URL copied to clipboard!');
          }).catch(function() {
            toastr.error('Failed to copy URL. Please copy manually: ' + productUrl);
          });
        } else {
          toastr.error('Failed to copy URL. Please copy manually: ' + productUrl);
        }
      }
    }
  }
</script>
@endpush
