@extends(themeView() . 'layout')

@section('title', $astrologer->meta_title)
@section('description', $astrologer->meta_description)
@section('keywords', $astrologer->meta_keywords)

@push('style')
  <style>
    h5 span {
        font-weight: 600;
    }
    .time-slot-container {
        display: flex;
        gap: 20px;
    }
    .time-slot {
        position: relative;
    }
    .time-slot input[type="radio"] {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        cursor: pointer;
        border-radius:50px;
    }
    .time-slot input[type="checkbox"] {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .time-slot{
      border-radius: 30px !important;
    }
    .custom-radio {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 79px;
        height: 44px;
        border: 1px solid #d7c320;
        border-radius: 4px;
        text-align: center;
        cursor: pointer;
        border-radius:50px;
    }
    .time-slot input[type="radio"]:checked+.custom-radio {
        background: #FF5D28;
        color: #fff !important;
        border-radius:50px;
    }
    .time-slot input[type="checkbox"]:checked+.custom-radio {
        background-color: #ffc107;
        color: #fff;
    }
    .user_description .more {
        display: none; /* Hide the full content initially */
    }
    .user_description .collapse.show {
        display: block; /* Show full content when expanded */
    }
    .active {
        opacity: 0.8; /* Show full content when expanded */
    }
  </style>
  <link rel="stylesheet" href="{{ asset('assets/lib/material-timepicker/mdtimepicker.css') }}">
  <!-- <style>
    .mdtp__time_holder, .mdtp__digit.active span, .mdtp__digit.active:before, .mdtp__clock .mdtp__clock_dot, .mdtp__clock .mdtp__pm.active{
      background-color: #FCD7C5 !important;
    }
    .mdtp__button{
      color: #FCD7C5;
    }
  </style> -->
@endpush

@section('content')
<!-- mobile-add-end -->
<!-- add-section-end -->
﻿<!-- banner section start -->
<section class="mt-md-0 mt-md-5 mt-lg-2 pad-ap">
  <div class="container mt-md-0 mt-4 ff">

    <style>
      .av3-astro-banner {
          background: linear-gradient(135deg, #0a0f2c 0%, #111936 60%, #0d1530 100%);
          border-radius: 20px;
          padding: 30px;
          position: relative;
          overflow: hidden;
          box-shadow: 0 10px 40px rgba(0,0,0,0.15);
          border: 1px solid rgba(255,255,255,0.05);
      }
      .av3-astro-banner::before {
          content: ''; position: absolute; width: 400px; height: 400px;
          background: radial-gradient(circle, rgba(245,158,11,0.15) 0%, rgba(245,158,11,0) 70%);
          top: -150px; right: -150px; border-radius: 50%; pointer-events: none;
      }
      .av3-profile-img-wrap {
          width: 150px; height: 150px; border-radius: 50%; padding: 4px;
          background: linear-gradient(135deg, #f59e0b, #d97706);
          position: relative; margin: 0 auto;
      }
      .av3-profile-img-wrap img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid #111936; background: #fff; }
      .av3-status-badge {
          position: absolute; bottom: 8px; right: 12px; width: 22px; height: 22px; border-radius: 50%;
          border: 4px solid #111936;
      }
      .av3-status-online { background: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
      .av3-status-bussy { background: #ef4444; box-shadow: 0 0 10px rgba(239,68,68,0.5); }
      .av3-status-offline { background: #6b7280; }

      .av3-info h1 { font-family: 'Georgia', serif; color: white; font-size: 28px; font-weight: 700; margin-bottom: 12px; }
      .av3-info p { color: rgba(255,255,255,0.7); font-size: 14px; margin-bottom: 8px; display: flex; align-items: center; gap: 10px; }
      .av3-info p i { color: #f59e0b; width: 16px; text-align: center; }

      .av3-action-btn {
          width: 100%; border-radius: 12px; padding: 12px 16px; margin-bottom: 10px;
          display: flex; align-items: center; justify-content: space-between;
          transition: all 0.3s; border: none; cursor: pointer;
          background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
      }
      .av3-action-btn:hover:not(:disabled) { background: rgba(255,255,255,0.12); transform: translateY(-2px); border-color: rgba(245,158,11,0.4); }
      .av3-action-btn:disabled { opacity: 0.5; cursor: not-allowed; }
      .av3-action-btn .btn-left { display: flex; align-items: center; gap: 12px; color: white; font-weight: 600; font-size: 15px; }
      .av3-action-btn .btn-left i { font-size: 18px; color: #10b981; }
      .av3-action-btn .btn-right { text-align: right; line-height: 1.2; }
      .av3-action-btn .price { font-size: 16px; font-weight: 800; color: #f59e0b; }
      .av3-action-btn .free-price { font-size: 16px; font-weight: 800; color: #10b981; }
      .av3-action-btn .old-price { font-size: 12px; color: rgba(255,255,255,0.4); text-decoration: line-through; margin-right: 4px; }
      .av3-action-btn.active { background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(217,119,6,0.05)); border-color: #f59e0b; box-shadow: 0 0 15px rgba(245,158,11,0.15); }

      .av3-right-card {
          background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
          border-radius: 16px; padding: 18px; margin-bottom: 12px;
          display: flex; align-items: center; gap: 16px; backdrop-filter: blur(10px);
          transition: all 0.3s;
      }
      .av3-right-card:hover { background: rgba(255,255,255,0.06); border-color: rgba(245,158,11,0.3); }
      .av3-right-card .icon-box {
          width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
          background: rgba(255,255,255,0.05); font-size: 20px;
      }
      .av3-right-card h6 { color: white; font-size: 16px; font-weight: 600; margin: 0 0 2px 0; }
      .av3-right-card p { color: rgba(255,255,255,0.5); font-size: 13px; margin: 0; }
      .av3-right-card .stars { color: #f59e0b; font-size: 12px; }

      @media (max-width: 768px) {
          .av3-astro-banner { padding: 20px; }
          .av3-profile-img-wrap { width: 110px; height: 110px; margin: 0 auto 15px; }
          .av3-info { text-align: center; }
          .av3-info h1 { font-size: 22px; }
          .av3-info p { justify-content: center; }
          .av3-right-card { padding: 14px; gap: 12px; }
      }
    </style>

    <div class="av3-astro-banner mb-4">
      <div class="row align-items-center">

        <!-- PHP Logic setup -->
        @php
          $name = ''; $email = ''; $phone = ''; $birth_date = ''; $birth_time = ''; $birth_place = '';
          $gender = ''; $maritial_status = ''; $occupation = ''; $consult_type = "now"; $slot_time = '5';
          $user_id = 0;
          if($user){
              $user_id = $user->id; $name = $user->name; $email = $user->email; $phone = $user->phone;
              $birth_date = $user->birth_date; $birth_time = $user->birth_time; $birth_place = $user->birth_place;
              if($user->gender == 1) $gender = "male";
              elseif($user->gender == 2) $gender = "female";
              elseif($user->gender == 3) $gender = "other";
          }
          $chatStatus = (int) $astrologer->chat;
          $callStatus = (int) $astrologer->call;
          $videoStatus = (int) $astrologer->video;
          $alreadyJoined = $user_id && $appointment = $astrologer->aiConsultations($user_id) ? true : false;

          $is_busy = ($chatStatus === 3 || $callStatus === 3 || $videoStatus === 3 || $alreadyJoined);
          $is_online = ($chatStatus === 1 || $callStatus === 1 || $videoStatus === 1);
          $astrologer_status = $is_busy ? 'bussy' : ($is_online ? 'online' : 'offline');

          $todayDate = date("Y-m-d");
          $specialPriceFromDate = $astrologer->sp_from_date;
          $specialPriceToDate = $astrologer->sp_to_date;
          $hasSpecialPrice = false;
          if($specialPriceFromDate && ($specialPriceFromDate <= $todayDate) && ($specialPriceToDate >= $todayDate)){
            $hasSpecialPrice = true;
          }
          $activeTabFunction = "activeTab(this)";
          $handleUserLoginModalFunction = "handleUserLoginModal('open')";
          $onClickTabFunction = $user ? $activeTabFunction : $handleUserLoginModalFunction;

          $totalReviews = $astrologerReviews->count();
          $rating = $astrologer->rating;
        @endphp

        <!-- Left: Image & Info -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="row align-items-center">
                <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                    <div class="av3-profile-img-wrap">
                        <img src="{{ $astrologer->image ? $astrologer->image->image(300) : no_image() }}" alt="{{ $astrologer->display_name }}">
                        <div class="av3-status-badge av3-status-{{ $astrologer_status }}" title="{{ ucfirst($astrologer_status) }}"></div>
                    </div>
                </div>
                <div class="col-md-8 av3-info">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3 mb-1">
                        <h1 class="mb-0">{{ $astrologer->display_name }}</h1>
                        <a class="a2a_dd text-white" href="https://www.addtoany.com/share" style="opacity: 0.6; transition: 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.6"><i class="bi bi-share"></i></a>
                    </div>
                    <p><i class="bi bi-award"></i> {{ trim(str_replace(', ,', '', $astrologer->my_expertise_full), " ,") ?: 'Vedic Astrology, Numerology, Tarot' }}</p>
                    <p><i class="bi bi-translate"></i> {{ $astrologer->my_language ?: 'English, Hindi' }}</p>
                    <p><i class="bi bi-mortarboard"></i> {{ $astrologer->year_exp }} Years Experience</p>
                </div>
            </div>
        </div>

        <!-- Right: Actions & Stats -->
        <div class="col-lg-6 position-relative z-1">

            <div class="row">
                <!-- Action Buttons Col -->
                <div class="col-sm-6 mb-3 mb-sm-0">
                    @if(!$loggedInAstrologer)

                        @if($alreadyJoined)
                            <a href="{{ route('user.startaichat', [$appointment->id]) }}" class="av3-action-btn active" style="text-decoration: none;">
                                <div class="btn-left"><i class="fa fa-commenting"></i> Join Chat</div>
                                <div class="btn-right"><span class="price">Active</span></div>
                            </a>
                        @elseif($chatStatus === 1)
                            @php $chatSpecialPrice = $astrologer->chat_special_price; $chatPrice = $astrologer->chat_price; @endphp
                            <button {{ $bookNow || $takeBooking ? "" : "disabled" }} class="select-type-btn book_nowbut av3-action-btn {{$type=='chat'?'active':''}}" data-type="chat" data-price="{{ $hasSpecialPrice ? $chatSpecialPrice : $chatPrice }}" onclick="{{ $onClickTabFunction }}" id="chattab">
                                <div class="btn-left"><i class="fa fa-commenting"></i> Chat</div>
                                <div class="btn-right">
                                    @if($hasSpecialPrice)
                                        @if($astrologer->first_chat_free && $takeConsultation == false)
                                            <div><strike class="old-price">&#8377;{{ $chatSpecialPrice }}</strike> <span class="free-price">FREE</span></div>
                                        @else
                                            <div><strike class="old-price">&#8377;{{ $chatPrice }}</strike> <span class="price">&#8377;{{ $chatSpecialPrice }}/min</span></div>
                                        @endif
                                    @else
                                        @if($astrologer->first_chat_free && $takeConsultation == false)
                                            <div><strike class="old-price">&#8377;{{ $chatPrice }}</strike> <span class="free-price">FREE</span></div>
                                        @else
                                            <div><span class="price">&#8377;{{ $chatPrice }}/min</span></div>
                                        @endif
                                    @endif
                                </div>
                            </button>
                        @endif

                    @endif
                </div>

                <!-- Info Cards Col -->
                <div class="col-sm-6">
                    <div class="av3-right-card p-2 px-3">
                        <div class="icon-box text-warning" style="width:36px; height:36px;"><i class="bi bi-star-fill"></i></div>
                        <div>
                            <h6 style="font-size:14px;">{{ $rating > 0 ? $rating : 'New' }} <span class="stars" style="font-size:10px;">@for($i=1;$i<=$rating;$i++)<i class="bi bi-star-fill"></i>@endfor</span></h6>
                            <p style="font-size:11px;">{{ $totalReviews }} Reviews</p>
                        </div>
                    </div>

                    <div class="av3-right-card p-2 px-3">
                        <div class="icon-box text-info" style="width:36px; height:36px;"><i class="bi bi-person-check-fill"></i></div>
                        <div>
                            <h6 style="font-size:14px;">Verified Expert</h6>
                            <p style="font-size:11px;">AstroVyra Certified</p>
                        </div>
                    </div>

                    <div class="av3-right-card p-2 px-3 mb-0">
                        <div class="icon-box text-success" style="width:36px; height:36px;"><i class="bi bi-shield-lock-fill"></i></div>
                        <div>
                            <h6 style="font-size:14px;">100% Secure</h6>
                            <p style="font-size:11px;">Safe Payments</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Astrologer detail section start -->
<section class="mt-0 mt-lg-2">
  <div class="container">
    @if($astrologer->about || $astrologer->experience || $astrologer->education || $astrologer->expertise)
      <div class="border-0 p-3 ps-0 pb-0 pt-0 pt-lg-2">
        <!-- <h3 class="fw-semibold fs-24">About</h3> -->
        <div class="tab-content mt-2 mx-1100" id="myTabContent">
          @if($astrologer->about)
            <div class="tab-pane fade show active  text-start text-black pb-none" id="about" role="tabpanel" aria-labelledby="about-tab ">
                {!! $astrologer->about !!}
            </div>
          @endif
        </div>
      </div>
    @endif
  </div>
</section>
<!-- Astrologer detail section end -->

  <!-- Booking Consultation Form Modal Start -->
  <div class="modal fade" id="consultationModal" tabindex="-1" aria-labelledby="consultationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md ai_modal"> <!-- modal-xl दिया है ताकि form बड़ा दिखे -->
      <div class="modal-content radius-8">
        <div class="bg-p py-2">
          <div class="chat-form-imgbox d-flex ms-3">
            <img src="/assets/img/form-img-2.jpg">
              <h4 class="modal-title h5 py-3 text-center text-capitalize fs-22 f6 ms-2 f14-m" id="consultationModalLabel">Book Consultation</h4>
          </div>
          <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
        </div>
        @php
          $formSubmitUrl = route("astrologers.bookaiconsultation");
        @endphp
        <div class="modal-body">
          <form method="POST" action="{{ $formSubmitUrl }}" onsubmit="return validateAndSubmit(this)">
            <div class="">
              @if($bookNow)
                <div class="schedule1" id="consultaion-form">
                  <div class="row mb-0 justify-content-center">
                    <div class="row as-details-form justify-contact-center">
                      <div class="col-md-6">
                        <label for="name" class="fs-14 fw-bold">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Your Name" value="{{ $name }}">
                      </div>
                      {{--<div class="col-md-4 d-none">
                        <label for="email" class="fs-14 fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{ $email }}">
                      </div>
                      <div class="col-md-6 d-none">
                        <label for="mobile" class="fs-14 fw-bold">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="phone" value="{{ $phone }}">
                      </div>--}}
                      <div class="col-md-6">
                        <label for="gender" class="fs-14 fw-bold">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select" id="gender">
                          <option value="">Select</option>
                          <option value="male" {{$gender=='male'?'selected':''}}>Male</option>
                          <option value="female" {{$gender=='female'?'selected':''}}>Female</option>
                          <option value="other" {{$gender=='other'?'selected':''}}>Others</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="birth_date" class="fs-14 fw-bold">Date Of Birth <span class="text-danger">*</span></label>
                        <input type="date" id="birth_date" class="form-control" name="birth_date" max="{{date('Y-m-d')}}" min="{{date('Y-m-d', strtotime('-100 years'))}}" value="{{ $birth_date }}">
                      </div>
                      <div class="col-md-6">
                        <label for="time" class="fs-14 fw-bold">Time of Birth <span class="text-danger">*</span></label>
                        <i class="bi bi-clock a_time_icon" onclick="handleTimePickerIcon('#birth_time')"></i>
                        <input class="form-control timepicker" type="text" name="birth_time" value="{{ $birth_time }}" step="1" id="birth_time">
                      </div>
                      <div class="col-md-12">
                        <label for="birth_place" class="fs-14 fw-bold">Place of Birth <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="birth_place" onkeyup="birthplaces('#birth_place', '#birth_place_suggestion_video')" name="birth_place" value="{{ $birth_place }}">
                        <div class="suggestion_birth">
                          <ul id="birth_place_suggestion_video"></ul>
                        </div>
                      </div>


                      @if($serviceDocument)
                        <div class="col-md-12 d-none">
                          <label for="document">{{$serviceDocument->document_label}}</label>
                          <input type="file" accept=".jpg, jpeg, .pdf" class="form-select" name="document" id="document">
                        </div>
                      @endif
                    </div>
                    <div class="row">
                      <div class="col text-center">
                        <button type="submit" class="btn chat-m-btn px-5 py-3 mt-0 fs-16 fw-bold" id="consult_submit_btn">Proceed Chat</button>
                        <p class="text-center mt-3 fs-16 pointer pb-0 mb-0 fw-semibold"data-bs-dismiss="modal">Cancel Chat</p>
                      </div>
                    </div>
                  </div>
                </div>
                @endif
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Booking Consultation Form Modal End -->

  <!-- rating-sectiion-start -->
  @if($totalReviews > 0)
  <section class="Rating pb-3">
    <div class="container">
      <div class="rating-section">
        <h2>Rating &amp; Reviews</h2>
      </div>
      <div class="reviews-container">
        @foreach($astrologerReviews as $key => $review)
          @php
            $reviewUser = $review->user;
          @endphp
          <div class="row">


          @if($reviewUser)
            @php
              $userReviewImage = $reviewUser->image;
            @endphp
            <div class="col-md-6">

            <div class="review-item review-item-2 review-bg mb-2">
              <img src="{{ $userReviewImage ? $userReviewImage->image(88,88) : asset('assets/img/user_default.jpg') }}" alt="Reviewer">
              <div class="review-content">
                <div class="d-flex justify-content-between">
                  <h4>{{ $reviewUser->name }}</h4>
                  <div class="stars">
                    @for($i = 0; $i < $review->rating; $i++)
                      <i class="bi bi-star-fill"></i>
                    @endfor
                  </div>
                </div>
                <p class="re-date">{{$review && $review->created_at ? $review->created_at->format('d M Y') : ''}}</p>
                <p class="mt-1">{{ $review->comment }}</p>
              </div>
            </div>
            </div>
          @else
          </div>
            <div class="review-item review-item-2 review-bg mb-2">
              <img src="{{ asset('assets/img/user_default.jpg') }}" alt="Reviewer">
              <div class="review-content">
                <div class="d-flex justify-content-between">
                  <h4>Unknown</h4>
                  <div class="stars">
                    @for($i = 0; $i < $review->rating; $i++)
                      <i class="bi bi-star-fill"></i>
                    @endfor
                  </div>
                </div>
                <p class="re-date">{{$review && $review->created_at ? $review->created_at->format('d M Y') : ''}}</p>
                <p class="mt-1">{{ $review->comment }}</p>
              </div>
            </div>
          @endif
        @endforeach
      </div>
      <div class="mt-2">
        {!! $astrologerReviews->links('vendor.pagination.bootstrap-5') !!}
      </div>
    </div>
  </section>
  @endif
  <!-- rating-sectiion-end -->

  @if(isset($faqs) && count($faqs))
    <section class="py-0 py-md-0 mt-3">
      <div class="container container-xl pt-0">
        <div class="row justify-content-center">
          <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center">
            <h2 class="fs-3 heading">Frequently Asked Questions</h2>
            <p class="fs-6 text-center">Get answers to all your queries with our FAQs.</p>
          </div>
        </div>
        <div class="row justify-content-center py-3">
          <div class="col-lg-8">
            <div class="accordion" id="faqAccordionDiv">
              @foreach($faqs as $key => $faq)
                @include(themeView()."faq.accordion", ["key" => $key, "faq" => $faq])
              @endforeach()
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif

  <!-- need to recharge modal start -->
    <div class="modal fade recharge-m" id="needToRechargeModal" tabindex="-1" aria-labelledby="needToRechargeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="schedule1 row">
              <div class="col-12">
                <span id="consultation-error-message"></span>
              </div>
            </div>
            <div class="text-end minimum-box mt-3 mt-md-0">
              <button type="button" class="btn btn-ca me-3" onclick="closeNeedToRechargeModal()">Cancel</button>
              <button type="button" name="button" class="btn btn-re">
                <a href="{{ route('user.recharge') }}" >Recharge</a>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!-- need to recharge modal end -->
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/lib/material-timepicker/mdtimepicker.js') }}"></script>
<script type="text/javascript">
const myModal = new bootstrap.Modal(document.getElementById('consultationModal'), {
backdrop: 'static',
keyboard: false
});
</script>
<script type="text/javascript">
  $('document').ready(function(){
    setTimeout(function(){
      @if($user)
        @if($type=='call')
          $('#calltab').click();
        @elseif($type=='chat')
          $('#chattab').click();
        @elseif($type=='video')
          $('#videotab').click();
        @endif
      @else
        handleUserLoginModal("open");
      @endif
    }, 0);
    {{--
    @if($consult_type=='now')
    consultTypeFn('now');
    @elseif($consult_type=='later')
    consultTypeFn('later')
    @endif
    @if($slot_time=='5')
    slotTypeFn('5');
    @elseif($slot_time=='15')
    slotTypeFn('15');
    @elseif($slot_time=='30')
    slotTypeFn('30');
    @endif
    --}}
    $('.timepicker').mdtimepicker({
      timeFormat:'hh:mm:ss',
      format:'h:mm tt',
      theme:'pink',
      clearBtn:true
    });
  });
  function handleTimePickerIcon(that){
    $(that).click();
  }
</script>
<script>
  let astrologerId = "{{ $astrologer->id }}";
  let consultationMode = "{{ $type }}";
  let consultationType = "{{ $consult_type }}";
  let consultationSlot = "{{ $slot_time }}";

  function activeTab(that){
    consultationMode = $(that).data('type');
    console.log(consultationMode);
    let price = $(that).data('price');
    @if($astrologer->first_chat_free && $takeConsultation == false)
      if(consultationMode == "chat"){
        $("#consultationModalLabel").text(`Book ${consultationMode} consultation (Free)`);
      }else{
        $("#consultationModalLabel").text(`Book ${consultationMode} consultation (${price}/min)`);
      }
    @else
      $("#consultationModalLabel").text(`Book ${consultationMode} consultation (${price}/min)`);
    @endif
    $('.select-type-btn').removeClass('active');
    $(that).addClass('active');
    $('#price-heading').find('#price-heading-price').html(`₹${price}/min`);
    $('#consultationModal').modal('show');
    {{--
    price = parseFloat(price)
    $('#consult').prop('checked', true);
    $('.select-slot').addClass('d-none');
    $('#schedulemeeting').addClass('d-none');
    $('#slotmin-15').prop('checked', true)
    $('#selectedminute').html(15)
    $('#charge').html(15*price)
    $('#schedulemeeting').addClass('d-none');

    $('#slotmin-5').parent('.time-slot').show();
    if(consultationMode == 'video'){
      $('#slotmin-5').parent('.time-slot').hide();
    }
    --}}
  }
  function validateAndSubmit(that){
    let _form = $(that);
    let url = _form.attr("action");
    let button = _form.find('button[type=submit]');
    button.addClass('disabled').prop('disabled', true).text('Booking...');
    let formData = new FormData(that);
    formData.append("astrologer_id", astrologerId);
    formData.append("consultation_mode", consultationMode);
    formData.append("consultation_type", consultationType);
    formData.append("time_slot", consultationSlot);
    $.ajax({
      url: url,
      method: "POST",
      data: formData,
      enctype: 'multipart/form-data',
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      beforeSend: function(){
        $('.loader').removeClass("d-none");
      },
      success : function(response){
        button.removeClass('disabled').prop('disabled', false).text('Booked');
        let message = response.message;
        if(message){
          toastr.success(message);
        }
        $('.loader').addClass("d-none");
        let redirectTo = response.redirectTo;
        if(redirectTo){
          setTimeout(function(){
            window.location.href = redirectTo;
          }, 2000);
        }
      },
      error: function(error){
        button.removeClass('disabled').prop('disabled', false).text('Booked');
        let errors = error.responseJSON.errors;
        if(errors){
          for(var key in errors){
            toastr.error(errors[key][0]);
          }
        }else{
          let message = error.responseJSON.message;
          if(message){
            toastr.error(message);
            $("#consultation-error-message").text(message);
          }
        }
        let status = error.responseJSON.status;
        if(status && status=="recharge"){
          $("#consultationModal").modal("hide");
          $("#needToRechargeModal").modal("show");
          return false;
        }
        let redirectTo = error.responseJSON.redirectTo;
        if(redirectTo){
          setTimeout(function(){
            window.location.href = redirectTo;
          }, 2000);
        }
        console.log(error);
      }
    });
    return false;
  }

  function closeNeedToRechargeModal(){
    $("#needToRechargeModal").modal("hide");
  }

  {{--
  function consultTypeFn(type){
    $('#consult_type').val(type);
    slotTypeFn(15)
    if(type=='later'){
      $('.select-slot').addClass('d-none');
      $('#schedulemeeting').show();
      $('#consultaion-form').hide();
      $('#slotmin-5').parent('.time-slot').hide();
    }else{
      $('#slotmin-5').parent('.time-slot').show();
      if(consultationMode == 'video'){
        $('#slotmin-5').parent('.time-slot').hide();
      }
      $('.select-slot').addClass('d-none');
      $('#schedulemeeting').hide();
      $('#consultaion-form').show();
    }
  }

  function showforms(){
    $('#consultaion-form').show();
  }

  function slotTypeFn(time){
    $('#selectedminute').html(time)
    $('#confirmed').val('no');
    $('#slot_time').val(time);
    $('.select-slot').addClass('d-none');
    $('#slotmin-'+time).prop('checked', true);
    $('#charge').html(time*parseFloat($('#special_price').val()));
    if(time==5){
      $('#selectedminute').html('Per')
      $('#charge').html(time*parseFloat($('#special_price').val())+' minimum');
    }
    time = parseInt(time);
    $('#consultaion-form').show();
    if($('#consult_type').val()=='later'){
      $('#consultaion-form').hide();
    }
    if($('.flatpickr-day.selected').length==0){
      $('.flatpickr-day:not(.flatpickr-disabled)')[0].click();
    }else{
      $('.flatpickr-day.selected')[0].click();
    }
  }
  --}}

  $(document).ready(function (){
    // $("#birth_date").datepicker({
    //         dateFormat: "mm/dd/yy",
    //         maxDate: 0,
    //         changeYear: true,
    //         changeMonth: true,
    //         yearRange: "1900:c",
    //         showButtonPanel: true,
    // });
    let todayDate = new Date();
    const lastDayOfMonth = new Date(todayDate.getFullYear(), todayDate.getMonth() + 1, 0);
    flatpickr("#inline-calendar", {
        inline: true,
        minDate: "today",
        maxDate: lastDayOfMonth,
        disable: [
            function(date){
                let today = new Date();
                let allowedDays = @json($weekDaysArray);
                if(!allowedDays.includes(date.getDay())){
                  let d=date.getFullYear()+'-'+(date.getMonth()+1)+'-'+(date.getDate());
                  return d;
                }
            }
        ],
    });
  });

  var typingTimer;
  function birthplaces(inputId, suggestionId) {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function() {
      var inputVal = $(inputId).val(); // Get the value of the input field
      if (inputVal.length > 2) { // Make sure input is at least 3 characters
        $(suggestionId).html('<li>Searching...</li>');
        $.ajax({
          url: '/search-location/' + encodeURIComponent(inputVal), // Make the GET request
          method: 'GET',
          success: function(response) {
            $(suggestionId).empty(); // Clear previous suggestions
            response.forEach(function(location) {
              var li = $('<li></li>'); // Create a new list item
              li.text(location.state);
              li.on('click', function(){
                $(inputId).val($(this).text()); // Set clicked place in input
                $(suggestionId).empty(); // Clear suggestions after selection
              });
              $(suggestionId).append(li); // Append the list item to the suggestion box
            });
          },
          error: function(xhr, status, error) {
            // console.error(error);
            $(suggestionId).html('');
          }
        });
      } else {
        $(suggestionId).empty(); // Clear suggestions if input is too short
      }
    }, 1000);
  }

  $(document).ready(function() {
    $('#inline-calendar ,#chatinline-calendar,#voiceinline-calendar').on('change', function(){
      const selectedDate = new Date($(this).val());
      const dayOfWeek = selectedDate.getDay();
      const astro_id = '{{$astrologer->id}}';
      const slot = $('#slot_time').val();
      $.ajax({
        method: "GET",
        url: '{{ route('astrologers.date.response') }}',
        data: {
          date : selectedDate.getDate(),
          month : (selectedDate.getMonth()+1),
          year : selectedDate.getFullYear(),
          dayOfWeek: dayOfWeek,
          astro_id: astro_id,
          slot: slot
        },
        beforeSend: function() {
          $('.loader').removeClass("d-none");
        },
      })
      .done(function(response) {
        // Clear any previous time slots
        console.log($(this))
        $('.select-slot').removeClass('d-none');
        $('#intervaltime ,#chatintervaltime,#voiceintervaltime').empty();
        $('#intervaltimejs ,#chatintervaltimejs,#voiceintervaltimejs').empty();
        $('.loader').addClass("d-none");
        // Append 15-minute interval time slots
        let i = 0;
        if(slot=='15'){
          for (var key in response.timeIntervals15){
            $('#intervaltimejs ,#chatintervaltimejs, #voiceintervaltimejs').append(`
              <div class="time-slot mb-2">
              <input type="radio" id="slot-15-${i}" name="scheduled_time" value="${key}" onclick="showforms()">
              <label for="slot-15-${i}" class="custom-radio">${response.timeIntervals15[key]}</label>
              </div>
              `);
              i++;
            }
          }else if(slot=='30'){
            for (var key in response.timeIntervals30){
              $('#intervaltimejs ,#chatintervaltimejs,#voiceintervaltimejs').append(`
                <div class="time-slot mb-2">
                <input type="radio" id="slot-30-${response.timeIntervals30[key]}" name="scheduled_time" value="${key}" onclick="showforms()">
                <label for="slot-30-${response.timeIntervals30[key]}" class="custom-radio">${response.timeIntervals30[key]}</label>
                </div>
                `);
              }
              i++;
            }
            $('#intervaltimejs').show();
          }).fail(function() {
            toastr.error("Failed to load time slots.");
          });
        });

        function reviewdata(form) {
          var _f = $(form);
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({
            method: "POST",
            url: _f.attr('action'),
            data: _f.serialize(),
            beforeSend: function() {
              $('.loader').removeClass("d-none");
              // toastr.success("Payment completed");
            }
          })
          .done(function(msg){
            toastr.success(msg.message); // Display success message
            $('.loader').addClass("d-none");
          })
          .fail(function(xhr) {
            var response = $.parseJSON(xhr.responseText);
            $('.loader').addClass("d-none");
          });
          return false;
        }
      });
      $('.ratings').on('click', '.ratings_stars', function () {
        var star = $(this);
        $('.ratings_stars').removeClass('bi-star').addClass('bi-star-fill');
        star.addClass('bi-star-fill');
        star.prevAll().addClass('bi-star-fill');
        star.nextAll().removeClass('bi-star-fill').addClass('bi-star');
        $('#rating').val(star.data('rating'));
    });

    $('#astro_rev').on('submit',function(e){
      e.preventDefault();
      let frmData = $(this).serialize();
      $.ajax({
        url : "{{ route('astrologers.review', ['slug' => $astrologerSlug]) }}",
        type : 'POST',
        data: frmData,
        success: function(){
          toastr.success('Review Submitted Successfully');
          $('#astro_rev')[0].reset();
        }
      })
    });
</script>

<script type="text/javascript">
  let user_id = '{{$user ? $user->id : 0}}';
  function notify(){
    if(user_id != '0'){
      Swal.fire({
        text: 'You want us to notify you when astrologer online on email?',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Yes",
        cancelButtonColor: "#d33",
        cancelButtonText: "No",
      }).then((result)=>{
        if(result.isConfirmed){
          userNotification(user_id, "{{$astrologer->id}}", 'user', 'astrologer online', 'Notify to user when astrologer online back.', 'Thank You! We will notifiy you on email when astrologer online.');
        }else{
          window.location.reload();
        }
      });
    }else{
      checkAuth("{{ url()->current() }}", "{{ route('login') }}");
    }
  }
</script>
<script>
document.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        let form = document.querySelector("form");
        if (validateAndSubmit(form)) {
            form.submit(); // Only submit if validation success
        }
    }
});
</script>
@endpush
