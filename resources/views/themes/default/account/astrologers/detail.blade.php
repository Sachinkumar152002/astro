@extends(themeView() . 'layout')

@section('title', 'Astrologers')
@section('keywords', '')
@section('description', '')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
        }

        .time-slot input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
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
        }

        .time-slot input[type="radio"]:checked+.custom-radio {
            background-color: #ffc107;
            color: #fff;
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

    </style>
    <!-- banner section start -->
    <section class=" p-3">
        <div class="container consult_banner rounded">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="card">

                        <div class="card-header card-detail border-0 p-0">
                          <div class="row gx-0">
                            <div class="col-md-4 col-6 align-items-center card_image justify-content-center d-flex flex-column">
                              <div class="{{ $astrologer->call ? onlineStatus($astrologer->call) : ($astrologer->chat ? onlineStatus($astrologer->chat) : '') }}">

                                </div>
                                <img src="{{ $astrologer->image ? $astrologer->image->image(175) : no_image() }}"
                                    class="astro-img rounded-circle border-white img-fluid"
                                    alt="{{ $astrologer->display_name }}">
                                <div class="rating">{{ $astrologer->average_rating }} <i class="bi bi-star-fill"></i></div>
                            </div>
                            <div class="col-6 col-md-8 card_detail">
                                <!-- <div class="tags">
                                    <div class="tag-first">Best Deals</div>
                                    <div class="tag-second">Offers</div>
                                </div> -->
                                <h3 class=" fs-5 fs-md-3 text-capitalize fw-bold text">{{ $astrologer->display_name }}{{$astrologer->call}}</h3>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item fs-md-6 fs-7 lh-base d-flex text-break gap-2"><i class="bi bi-menu-button-wide"></i>
                                        {{ $astrologer->my_language }}</li>
                                    <li class="list-group-item fs-md-6 fs-7 lh-base"><i class="bi bi-award"></i>{{ $astrologer->my_expertise }}
                                    </li>
                                    <li class="list-group-item fs-md-6 fs-7 lh-base"><i class="bi bi-mortarboard"></i>
                                        {{ $astrologer->year_exp }} years</li>
                                    <li class="list-group-item fs-md-6 fs-7 lh-base">
                                        @if ($astrologer->offer_price)
                                            <small><i class="bi bi-telephone"></i> Call@ ₹<s>{{ $astrologer->call_price }}.</s></small>
                                            <span class="badge bg-warning text-dark">Offer Price <i
                                                    class="fa fa-rupee-sign">₹</i>{{ $astrologer->call_special_price }}
                                                /min.</span>
                                        @else
                                            <i class="bi bi-telephone"></i> Call@ ₹{{ $astrologer->price }}/min.
                                        @endif
                                    </li>

                                </ul>
                            </div>
                          </div>

                        </div>

                        <div class="card-body px-0 pb-0">
                            <div class="footer d-footer mt-3">
                                <ul class="icon_list">
                                  @if($astrologer->call)
                                        <li>
                                          <a
                                              href="javascript:void(0)"
                                              class="btn btn-primary float-hover active"

                                                  onclick="$('#offline-report').toggleClass('d-none'); showConsultation('voice'); activetab()"

                                           id="calltab"> Call <i class="bi text-call bi-telephone-forward-fill"></i>
                                                <!-- <img src="{{ asset('assets/images/icon_11.png') }}"
                                                    alt="icon"> -->
                                            </a>
                                        </li>
                                        @endif
                                    @if ($astrologer->video)
                                        <li>
                                            <a  onclick="$('#offline-report').toggleClass('d-none'); showConsultation('call'); activetab()"

                                                href="javascript:void(0)" class="btn btn-primary float-hover" onclick="activetab()" id="videotab"> Video <i class="bi text-video_icon bi-camera-video-fill"></i>
                                                <!-- <img src="{{ asset('assets/images/icon_12.png') }}"
                                                    alt="icon"> -->
                                            </a>
                                        </li>
                                    @endif

                                    @if ($astrologer->chat)
                                        <li>
                                            <a  onclick="$('#offline-report').toggleClass('d-none'); showConsultation('chat'); activetab()"

                                                href="javascript:void(0)" class="btn btn-primary float-hover" onclick="activetab()" id="chattab"> Chat <i class="bi text-chat_icon bi-chat-fill"></i>
                                                <!-- <img src="{{ asset('assets/images/icon_10.png') }}"
                                                    alt="icon"> -->
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 user_description">
    <h3 class="fw-bold">About</h3>

    @php
        $aboutShort = substr($astrologer->about, 0, 500); // First 500 characters
        $aboutFull = $astrologer->about;
    @endphp

    @if(strlen($astrologer->about) > 500)
        <!-- Show truncated content with collapse -->
        <p class="text-justify">
            {{ $aboutShort }}<span class="dots"></span>
            <span class="more collapse" id="moreText">{{ substr($aboutFull, 500) }}</span>
        </p>
        <p class="fw-semibold" id="readMoreLink" data-bs-toggle="collapse" href="#moreText" aria-expanded="false" aria-controls="moreText">
            Read More <i class="bi bi-arrow-right"></i>
        </p>
    @else
        <!-- Show full content without collapse -->
        <p class="text-justify fs-7">{{ $aboutFull }}</p>
    @endif
</div>



            </div>
        </div>
    </section>
    <!-- banner section End -->
    <!-- form section -->

        <section class="p-2">
            <div class="container-fluid container-xl">
                <div class="row d-flex justify-content-center">
                    {{-- video call section Start --}}
                    <div id="videocallsection" class="col-lg-8 bg-light p-5 text-center " style="display: none">
                        <div>
                             <h3 class="fs-3 text text-center fw-semibold mb-3">
                            Video Consultation Price : <span>₹ {{ $astrologer->special_price }}/min</span>
                        </h3>
                        <form action="{{ route('astrologers.call.scheduled') }}" method="post"
                     onsubmit="return validateAndSubmit(this)">
                     @csrf
                     <input type="hidden" name="actualprice" id="actualprice"
                         value="{{ $astrologer->special_price }}">
                     <input type="hidden" name="astrologer_id" id="astro_id" value="{{ $astrologer->slug }}">
                     <input type="hidden" name="user_id" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->id : '' }}">
                     <input type="hidden" name="total_price" value="" id="total_price">

                                <input type="hidden" name="type" value="1">
                                <div class="col-md-12 time-slot-container d-flex justify-content-center align-items-center">
                                    <div class="time-slot-container d-flex flex-wrap">
                                        <!-- Consult now -->
                                        <div class="time-slot">
                                            <input type="radio" id="consult" name="scheduled_for" value="0"
                                                required checked>
                                            <label for="consult-now" id="scheduled-later" class="custom-radio">Consult
                                                Now</label>
                                        </div>
                                        <!-- Scheduled Later -->
                                        <div class="time-slot">
                                            <input type="radio" id="schedule" name="scheduled_for" value="1">
                                            <label for="schedule-later" id="scheduled-later" class="custom-radio">Schedule
                                                for Later</label>
                                        </div>
                                    </div>

                                </div>
                        </div>

                        <div class="my-4">
                            <h4 class="fs-5 text fw-semibold mb-4">
                                Select Meeting Duration
                            </h4>
                            <div class="col-md-12 time-slot-container d-flex justify-content-center align-items-center">
                                <div class="time-slot-container">
                                    <!-- 30-Minute Slot -->
                                    <div class="time-slot">
                                        <input type="radio" id="slotmin-30" checked name="timeslot" value="30" required>
                                        <label for="slotmin-30" class="custom-radio">30 min </label>
                                    </div>
                                    <!-- 15-Minute Slot -->
                                    <div class="time-slot">
                                        <input type="radio" id="slotmin-15" name="timeslot" value="15">
                                        <label for="slotmin-15" class="custom-radio">15 min</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <h4 class="fs-5 text fw-semibold mb-3">
                                Consultation Charges
                            </h4>
                            <ul class="list-group d-flex flex-row flex-wrap justify-content-center gap-2 align-items-center p-0 m-0">
                                <li class="list-group-item w-25"><b>Duration :</b> <span id="selectedminute"> 30 Min</span></li>
                                <li class="list-group-item w-25"><b>Charges :</b> <span id="charge">{{ $astrologer->special_price * 30 }}</span> Min</li>
                                <li class="w-100" id="datetime"></li>
                            </ul>
                        </div>
                        {{--  @if ($isTodayAvailable)

                        @else
                            @if (!$isTodayAvailable)
                                <p class="isavailable text-center">Today is not available for consultation. Please select
                                    another date.</p>
                            @endif
                        @endif --}}
                        <div class="schedule justify-content-center align-items-start mt-2 d-none" id="schedulemeeting">
                            <div class="col-lg-8">
                                <h5 class="fs-6 text fw-bold">
                                    Select Date
                                </h5>
                                <div class="my-4">
                                    <div class="col-md-12  align-items-center">
                                        <input type="date" class="d-none" name="scheduled_date" id="inline-calendar"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 slot">
                                <h5 class="fs-6 text fw-bold mb-4">
                                    Select Slot
                                </h5>
                                <div class="slots-time">
                                    <ul class="list-group">
                                        <li>
                                            <div
                                                class="col-md-12 time-slot-container d-flex flex-column justify-content-center align-items-center">
                                                <div class="slot_time d-flex flex-column gy-3 w-100">
                                                  <div id="intervaltimejs"></div>
                                                    <div id="intervaltime"></div>
                                                    <input type="hidden" id="selected_time_slot"
                                                        name="selected_time_slot">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0">
                            <h4 class="fs-5 text fw-semibold mb-4">
                                Share some required details
                            </h4>
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" name="name" placeholder="Name"
                                        aria-label="Name" value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->name : ''}}">
                                </div>
                                <div class="col">
                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                        aria-label="Email" value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->email : '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="number" class="form-control" name="mobile" placeholder="Mobile"
                                        aria-label="Mobile" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->phone : ''}}">
                                </div>
                                <div class="col">
                                    <input type="text" id="video" class="form-control" name="dateofbirth"
                                        placeholder="Date of Birth" aria-label="Date of Birth"
                                        value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_date: '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                              <div class="col">
                                <input class="form-control" type="time" name="birth_time" value="" required>
                              </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="birthplace"
                                        placeholder="Birth Place" aria-label="Birth Place"
                                        value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_place : '' }}" required>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    {{-- <button class="btn btn-secondary"> Submit</button> --}}

                                    <input
                                      type="submit"
                                      class="btn btn-secondary ps-5 pe-5 pt-2 pb-2"
                                      value="Submit"
                                      @if (!auth()->guard('web')->check())
                                          onclick="openLoginModal(); return false;"
                                      @endif
                                  >

                                </div>
                            </div>

                        </div>
                        </form>
                    </div>
                    {{-- video call section end --}}

                    <div id="chatsection" class="col-lg-8 bg-light p-5 text-center " style="display:none;">
                        <div>
                          <h3 class="fs-3 text text-center fw-semibold mb-3">
                         Chat Consultation Price : <span>₹ {{ $astrologer->chat_special_price }}/min</span>
                     </h3>
                            <form action="{{ route('astrologers.call.scheduled') }}" method="post"
                                onsubmit="return validateAndSubmit(this)">
                                @csrf
                                <input type="hidden" name="actualprice" id="chatactualprice"
                                    value="{{ $astrologer->chat_special_price }}">
                                <input type="hidden" name="astrologer_id" id="astro_id"
                                    value="{{ $astrologer->slug }}">
                                <input type="hidden" name="user_id" value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->id: '' }}">
                                <input type="hidden" name="total_price" value="" id="chattotal_price">
                                <input type="hidden" name="type" value="2">
                                <div
                                    class="col-md-12 time-slot-container d-flex justify-content-center align-items-center">
                                    <div class="time-slot-container d-flex flex-wrap">
                                        <!-- Consult now -->
                                        <div class="time-slot">
                                            <input type="radio" id="chatconsult" name="scheduled_for" value="0"
                                                required checked>
                                            <label for="consult-now" id="scheduled-later" class="custom-radio">Consult
                                                Now</label>
                                        </div>
                                        <!-- Scheduled Later -->
                                        <div class="time-slot">
                                            <input type="radio" id="chatschedule" name="scheduled_for" value="1">
                                            <label for="schedule-later" id="scheduled-later"
                                                class="custom-radio">Schedule
                                                for Later</label>
                                        </div>
                                    </div>

                                </div>
                        </div>

                        <div class="my-4">
                            <h4 class="fs-5 text fw-semibold text-center mb-4">
                                Select Chat Duration
                            </h4>
                            <div class="col-md-12 time-slot-container d-flex justify-content-center align-items-center">
                                <div class="time-slot-container">
                                    <!-- 30-Minute Slot -->
                                    <div class="time-slot">
                                        <input type="radio" id="slotmin-30" checked name="timeslot" value="30" required>
                                        <label for="slotmin-30" class="custom-radio">30 min</label>
                                    </div>
                                    <!-- 15-Minute Slot -->
                                    <div class="time-slot">
                                        <input type="radio" id="slotmin-15" name="timeslot" value="15">
                                        <label for="slotmin-15" class="custom-radio">15 min</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-2">
                            <h4 class="fs-5 text text-center fw-semibold mb-3">
                                Consultation Charges
                            </h4>
                            <ul class="list-group d-flex flex-row flex-wrap justify-content-center gap-2 m-0 p-0">
                                <li class="list-group-item w-25"><b>Duration :</b> <span id="chatselectedminute">30 Min</span> Min</li>
                                <li class="list-group-item w-25"><b>Charges :</b> <span id="chatcharge">{{ $astrologer->chat_special_price *30}} Min</span></li>
                                <li class="w-100" id="chatdatetime"></li>
                            </ul>
                        </div>
                        {{--  @if ($isTodayAvailable)

                        @else
                            @if (!$isTodayAvailable)
                                <p class="isavailable text-center">Today is not available for consultation. Please select
                                    another date.</p>
                            @endif
                        @endif--}}


                        <div class="schedule justify-content-center align-items-start mt-2 d-none" id="schedulechat">
                            <div class="col-lg-8">
                                <h5 class="fs-6 text fw-bold">
                                    Select Date
                                </h5>
                                <div class="my-4">
                                    <div class="col-md-12  align-items-center">
                                        <input type="date" class="d-none" name="scheduled_date"
                                            id="chatinline-calendar" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 slot">
                                <h5 class="fs-6 text fw-bold mb-4">
                                    Select Slot
                                </h5>
                                <div class="slots-time">
                                    <ul class="list-group">
                                        <li>
                                            <div
                                                class="col-md-12 time-slot-container d-flex flex-column justify-content-center align-items-center">
                                                <div class="slot_time d-flex flex-column gy-3 w-100">
                                                  <div id="chatintervaltimejs"></div>
                                                    <div id="chatintervaltime"></div>
                                                    <input type="hidden" id="chatselected_time_slot"
                                                        name="selected_time_slot">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0">
                            <h4 class="fs-5 text text-center fw-semibold mb-4">
                                Share some required details
                            </h4>

                            <div class="row mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" name="name" placeholder="Name"
                                        aria-label="Name" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->name: '' }}">
                                </div>
                                <div class="col">
                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                        aria-label="Email" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->email: '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="number" class="form-control" name="mobile" placeholder="Mobile"
                                        aria-label="Mobile" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->phone: '' }}">
                                </div>
                                <div class="col">
                                    <input type="text" id="chat" class="form-control" name="dateofbirth"
                                        placeholder="Date of Birth" aria-label="Date of Birth"
                                        value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_date :''}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                  <div class="grid d-flex gap-2">
                                    <input class="form-control" type="time" name="birth_time" value="" required>
                                  </div>
                                </div>
                                <div class="col bp-suggestion">
                                  <input type="text" id="birthplacechat" onkeyup="birthplaces()" class="form-control" name="birthplace"
                                  placeholder="Birth Place" aria-label="Birth Place"
                                  value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_place : '' }}" required>
                                  <div class="suggestion_birth">
                                    <ul id="birth_place_suggestion">
                                    </ul>
                                  </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col">
                                    {{-- <button class="btn btn-secondary"> Submit</button> --}}
                                    <input
                                            type="submit"
                                            class="btn btn-secondary ps-5 pe-5 pt-2 pb-2"
                                            value="Submit"
                                            @if (!auth()->guard('web')->check())
                                                onclick="openLoginModal(); return false;"
                                            @endif
                                        >
                                </div>
                            </div>

                        </div>
                        </form>
                    </div>
                    {{-- video call section end  --}}

                    <div id="voicesection" class="col-lg-8 bg-light p-5 text-center " >
                        <div>
                          <h3 class="fs-3 text fw-semibold mb-4">
                         Call Consultation Price : <span>₹ {{ $astrologer->call_special_price }}/min</span>
                     </h3>
                            <form action="{{ route('astrologers.call.scheduled') }}" method="post"
                                onsubmit="return validateAndSubmit(this)">
                                @csrf
                                <input type="hidden" name="actualprice" id="voiceactualprice"
                                    value="{{ $astrologer->call_special_price }}">
                                <input type="hidden" name="astrologer_id" id="astro_id"
                                    value="{{ $astrologer->slug }}">
                                <input type="hidden" name="user_id" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->id: '' }}">
                                <input type="hidden" name="total_price" value="" id="voicetotal_price">
                                <input type="hidden" name="type" value="3">
                                <div
                                    class="col-md-12 time-slot-container d-flex justify-content-center align-items-center">
                                    <div class="time-slot-container d-flex flex-wrap">
                                        <!-- Consult now -->
                                        <div class="time-slot">
                                            <input type="radio" id="voiceconsult" name="scheduled_for" value="0"
                                                required checked>
                                            <label for="consult-now" id="scheduled-later" class="custom-radio">Consult
                                                Now</label>
                                        </div>
                                        <!-- Scheduled Later -->
                                        <div class="time-slot">
                                            <input type="radio" id="voiceschedule" name="scheduled_for"
                                                value="1">
                                            <label for="schedule-later" id="scheduled-later"
                                                class="custom-radio">Schedule
                                                for Later</label>
                                        </div>
                                    </div>

                                </div>
                        </div>

                        <div class="my-4">
                            <h4 class="fs-5 text fw-semibold mb-4">
                                Select Voice Call Duration
                            </h4>
                            <div class="col-md-12 time-slot-container d-flex justify-content-center align-items-center">
                                <div class="time-slot-container">
                                    <!-- 30-Minute Slot -->
                                    <div class="time-slot">
                                        <input type="radio" id="slotmin-30" checked name="timeslot" value="30" required>
                                        <label for="slotmin-30" class="custom-radio">30 min</label>
                                    </div>
                                    <!-- 15-Minute Slot -->
                                    <div class="time-slot">
                                        <input type="radio" id="slotmin-15" name="timeslot" value="15">
                                        <label for="slotmin-15" class="custom-radio">15 min</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="fs-5 text fw-semibold mb-3">
                                Consultation Charges
                            </h4>
                            <ul class="list-group d-flex flex-row flex-wrap gap-2 justify-content-center p-0 m-0">
                                <li class="list-group-item w-25"><b>Duration :</b> <span id="voiceselectedminute">30 min</span> Min</li>
                                <li class="list-group-item w-25"><b>Charges :</b> <span id="voicecharge">{{ $astrologer->call_special_price *30}} Min</span></li>
                                <li class="w-100" id="voicedatetime"></li>
                            </ul>
                        </div>
                      {{--  @if ($isTodayAvailable)
                            @else
                            @if (!$isTodayAvailable)
                                <p class="isavailable text-center">Today is not available for consultation. Please select
                                    another date.</p>
                            @endif
                        @endif--}}
                        <div class="schedule justify-content-center align-items-start d-none mt-2" id="schedulevoice">
                            <div class="col-lg-8">
                                <h5 class="fs-6 text fw-bold">
                                    Select Date
                                </h5>
                                <div class="my-4">
                                    <div class="col-md-12  align-items-center">
                                        <input type="date" class="d-none" name="scheduled_date"
                                            id="voiceinline-calendar" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 slot">
                                <h5 class="fs-6 text fw-bold mb-4">
                                    Select Slot
                                </h5>
                                <div class="slots-time">
                                    <ul class="list-group">
                                        <li>
                                            <div
                                                class="col-md-12 time-slot-container d-flex flex-column justify-content-center align-items-center">
                                                <div class="slot_time d-flex flex-column gy-3 w-100">
                                                  <div id="voiceintervaltimejs"></div>
                                                    <div id="voiceintervaltime"></div>
                                                    <input type="hidden" id="voiceselected_time_slot"
                                                        name="selected_time_slot">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="my-2">
                            <h4 class="fs-5 text fw-semibold mb-4">
                                Share some required details
                            </h4>

                            <div class="row mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" name="name" placeholder="Name"
                                        aria-label="Name" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->name: '' }}">
                                </div>
                                <div class="col">
                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                        aria-label="Email" value="{{ auth() && auth()->guard('web')->user() ?auth()->guard('web')->user()->email: '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="number" class="form-control" name="mobile" placeholder="Mobile"
                                        aria-label="Mobile" value="{{ auth() && auth()->guard('web')->user() ?auth()->guard('web')->user()->phone: '' }}">
                                </div>
                                <div class="col">
                                    <input type="text" id="call" class="form-control" name="dateofbirth"
                                        placeholder="Date of Birth" aria-label="Date of Birth"
                                        value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_date: '' }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                  <div class="grid d-flex gap-2">
                                    <input class="form-control" type="time" name="birth_time" value="" required>
                                  </div>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="birthplace"
                                        placeholder="Birth Place" aria-label="Birth Place"
                                        value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_place: '' }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    {{-- <button class="btn btn-secondary"> Submit</button> --}}
                                    <input
                                        type="submit"
                                        class="btn btn-secondary ps-5 pe-5 pt-2 pb-2"
                                        value="Submit"
                                        @if (!auth()->guard('web')->check())
                                            onclick="openLoginModal(); return false;"
                                        @endif
                                    >
                                </div>
                            </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    <!-- form section End -->
    <!-- review section -->
    <section class="reviews p-5">
        <div class="container-fluid container-xl">
            <!-- <div class="row">
                <div class="col-lg-12">

                </div>
            </div> -->
            <div class="row">
                <div class="col-lg-6 review-col">
                  <h4 class="fs-5 text">
                      Ratings and Reviews
                      <span class="fs-6 text">( <i class="bi bi-person-fill"></i> 158)</span>
                  </h4>
                  <p>Only ratings from verified purchases are included in the final evaluation.</p>
                    <div class="inner justify-content-center align-items-center flex-wrap">
                        <div class=" col-lg-6 col-12 text-center">
                            <div class="rating">
                                <span class="rating-num">{{ round($average,1) }}</span>
                                <div class="rating-stars">
                                    <?php
                                    $filledStars = floor($average);
                                    $halfStar = ($average - $filledStars) >= 0.5;
                                    for ($i = 1; $i <= $filledStars; $i++) {
                                            echo '<span><i class="bi bi-star-fill"></i></span>'; // Filled star
                                        }if ($halfStar) {
                                            echo '<span><i class="bi bi-star-half"></i></span>'; // Half star
                                        } else {
                                            echo '<span><i class="bi bi-star"></i></span>'; // Unfilled star
                                        }
                                    ?>
                                </div>
                            </div>

                            @if($astroreviews)
                            <div class="rating-users">
                                <i class="bi bi-person-fill"></i>{{count($astroreviews)}} total
                            </div>
                            @endif
                        </div>

                        <div class="col-lg-6 col-12 histo">
                            <div class="five histo-rate">

                                <span class="histo-star">
                                    <i class="bi bi-star-fill"></i> 5 </span>
                                    <div class="progress">
                                      <?php
                                      $r = [];
                                      foreach ($astroreviews as $re) {
                                          $r[] = $re->review;
                                      }
                                      $count = array_count_values($r)[5] ?? 0;
                                      $totalReviews = count($astroreviews);
                                      $avePer = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                      if ($avePer > 0) {
                                          echo '<div class="determinate" style="width: ' . $avePer . '%;"></div>';
                                      }
                                      ?>
                                    </div>
                                <span class="bar-block">


                                    <?php
                                    $r = [];
                                    foreach ($astroreviews as $re) {
                                        $r[] = $re->review;
                                    }

                                    $count = array_count_values($r)[5] ?? 'N/A';
                                    if ($count > 0) {
                                        echo '<span>' . $count . '</span>&nbsp;';
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="four histo-rate">
                                <span class="histo-star">
                                    <i class="bi bi-star-fill"></i> 4 </span>
                                    <div class="progress">
                                      <?php
                                      $r = [];
                                      foreach ($astroreviews as $re) {
                                          $r[] = $re->review;
                                      }
                                      $count = array_count_values($r)[4] ?? 0;
                                      $totalReviews = count($astroreviews);
                                      $avePer = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                      if ($avePer > 0) {
                                          echo '<div class="determinate" style="width: ' . $avePer . '%;"></div>';
                                      }
                                      ?>
                                    </div>
                                <span class="bar-block">

                                    <?php
                                    $r = [];
                                    foreach ($astroreviews as $re) {
                                        $r[] = $re->review;
                                    }

                                    $count = array_count_values($r)[4] ?? 'N/A';
                                    if ($count > 0) {
                                        echo '<span>' . $count . '</span>&nbsp;';
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="three histo-rate">
                                <span class="histo-star">
                                    <i class="bi bi-star-fill"></i> 3 </span>
                                    <div class="progress">
                                      <?php
                                      $r = [];
                                      foreach ($astroreviews as $re) {
                                          $r[] = $re->review;
                                      }
                                      $count = array_count_values($r)[3] ?? 0;
                                      $totalReviews = count($astroreviews);
                                      $avePer = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                      if ($avePer > 0) {
                                          echo '<div class="determinate" style="width: ' . $avePer . '%;"></div>';
                                      }
                                      ?>
                                    </div>
                                <span class="bar-block">
                                    <?php
                                    $r = [];
                                    foreach ($astroreviews as $re) {
                                        $r[] = $re->review;
                                    }

                                    $count = array_count_values($r)[3] ?? 'N/A';
                                    if ($count > 0) {
                                        echo '<span>' . $count . '</span>&nbsp;';
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="two histo-rate">
                                <span class="histo-star">
                                    <i class="bi bi-star-fill"></i> 2 </span>
                                    <div class="progress">
                                      <?php
                                      $r = [];
                                      foreach ($astroreviews as $re) {
                                          $r[] = $re->review;
                                      }
                                      $count = array_count_values($r)[2] ?? 0;
                                      $totalReviews = count($astroreviews);
                                      $avePer = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                      if ($avePer > 0) {
                                          echo '<div class="determinate" style="width: ' . $avePer . '%;"></div>';
                                      }
                                      ?>
                                    </div>
                                <span class="bar-block">
                                    <?php
                                    $r = [];
                                    foreach ($astroreviews as $re) {
                                        $r[] = $re->review;
                                    }

                                    $count = array_count_values($r)[2] ?? 'N/A';
                                    if ($count > 0) {
                                        echo '<span>' . $count . '</span>&nbsp;';
                                    }
                                    ?>
                                </span>
                            </div>

                            <div class="one histo-rate">
                                <span class="histo-star">
                                    <i class="bi bi-star-fill"></i> 1 </span>
                                    <div class="progress">
                                      <?php
                                      $r = [];
                                      foreach ($astroreviews as $re) {
                                          $r[] = $re->review;
                                      }
                                      $count = array_count_values($r)[1] ?? 0;
                                      $totalReviews = count($astroreviews);
                                      $avePer = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                      if ($avePer > 0) {
                                          echo '<div class="determinate" style="width: ' . $avePer . '%;"></div>';
                                      }
                                      ?>
                                    </div>
                                <span class="bar-block">
                                    <?php
                                    $r = [];
                                    foreach ($astroreviews as $re) {
                                        $r[] = $re->review;
                                    }

                                    $count = array_count_values($r)[1] ?? 'N/A';
                                    if ($count > 0) {
                                        echo '<span>' . $count . '</span>&nbsp;';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>


                    </div>
                </div>
                @if(auth()->check())
                <div class="col-lg-6 review-col-2">
                  <h4 class="fs-5 text">
                      Submit a Review
                  </h4>
                  <p>Share Your Experience with Our Astrologer</p>
                    <div class="card p-3 ">

                        @if(Session::has('message'))
                         <script>
                           document.addEventListener('DOMContentLoaded', function() {
                               toastr.success('Review Submitted Successfully!');
                           });
                        </script>
                         @endif
                        <form action="{{ route('astrologers.review', ['slug' => $astrologer->slug]) }}" method="post">
                            @csrf
                            <div class="row">
                            <div class="col-lg-12 col-12">
                                <!-- Textarea for review comments -->
                                <div class="mb-2">
                                    <!-- <label for="comments" class="form-label">Write a Review</label> -->
                                    <textarea class="form-control" placeholder="Write a Review" name="comments" id="comments" rows="2" cols="100" required></textarea>
                                </div>

                                <!-- Radio buttons for star rating -->
                                <div class="btn-group mb-2 ratings" role="group" aria-label="Star Rating">
                                  <input type="hidden" id="rating" name="rating" value="0">
                                      <i class="ratings_stars bi fs-2 text bi-star" data-rating="1"></i>
                                      <i class="ratings_stars bi fs-2 text bi-star" data-rating="2"></i>
                                      <i class="ratings_stars bi fs-2 text bi-star" data-rating="3"></i>
                                      <i class="ratings_stars bi fs-2 text bi-star" data-rating="4"></i>
                                      <i class="ratings_stars bi fs-2 text bi-star" data-rating="5"></i>
                                </div>
                            </div>
                            </div>

                            <!-- Submit button -->
                            <input type="submit" class="btn btn-secondary text-black" value="Write a Review">
                        </form>

                    </div>
                </div>
                @endif
            </div>
            @if(count($reviews)>0)
            <div class="row bg-light my-5 p-5 client-review">
                @foreach($reviews as $review)
                <div class="col-lg-12 d-flex justify-content-between flex-wrap align-items-center mb-3">
                    <div class="col-lg-10 col-12">
                        <div class="card mb-3">
                            <div class="row w-100">
                                <div class="col-md-2 d-flex justify-content-center align-items-center">
                                    <img src="{{$review->user && $review->user->image ? $review->user->image->image(50,50) : ''}}" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-10">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $review->user->name }}</h5> <!-- Display user name -->
                                        <p class="card-text">
                                            <small class="text-body-secondary">{{ $review->created_at->format('d M Y') }}</small> <!-- Review date -->
                                        </p>
                                        <p class="card-text">{{ $review->comment }}</p> <!-- Review comment -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-12 d-flex justify-content-center flex-column align-items-center">
                        <div class="rating-stars">
                            @for($i = 1; $i <=$review->review; $i++)
                                <span>
                                        <i class="bi bi-star-fill"></i>
                                </span>
                            @endfor
                        </div>
                        <h6 class="fs-6 text">
                            Client Rating <i class="bi bi-info-circle"></i>
                        </h6>
                    </div>
                </div>
                @endforeach
            @else

            @endif

            </div>
        </div>

    </section>
    <!-- review section -->
    <!-- Insufficient Balance Modal -->
<div class="modal fade" id="insufficientBalanceModal" tabindex="-1" aria-labelledby="insufficientBalanceLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="insufficientBalanceLabel">Insufficient Balance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="text-center">
           <img src="../../assets/images/marketing-automation_16766995.gif" class="img-fluid w-25" alt="Insufficient Funds">
       </div>
       <div class="text-center">
           <h5><b>Insufficient Funds</b></h5>
           <p>Your Current balance is <b><i class="bi bi-currency-rupee"></i> 100</b></p>
       </div>
     </div>
      <div class="modal-footer d-flex justify-content-between ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-secondary" id="rechargeWalletButton">Recharge Wallet</button>
      </div>
    </div>
  </div>
</div>

    <section>
    <!-- Button trigger modal -->
    <button
        type="button"
        class="btn btn-primary btn-lg d-none"
        data-bs-toggle="modal"
        data-bs-target="#modalId"
    >
        Launch
    </button>

    <!-- Modal -->
    <div
        class="modal fade"
        id="modalId"
        tabindex="-1"
        role="dialog"
        aria-labelledby="callconnecting"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="callconnecting"></h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <ul class="d-flex justify-content-between">
                            <li class="list-group-item call">
                                <img src="../../assets/images/call.png" alt="call">
                            </li>
                            <li class="list-group-item">
                                <img src="../../assets/images/exchange.png" alt="exchange">
                            </li>
                            <li class="list-group-item call">
                                <img src="../../assets/images/call_p.png" alt="call_p">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        ok
                  {{--  </button>
                    <button type="button" class="btn btn-primary">Save</button> --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        var modalId = document.getElementById('modalId');
        modalId.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            let button = event.relatedTarget;
            // Extract info from data-bs-* attributes
            let recipient = button.getAttribute('data-bs-whatever');
            // Use above variables to manipulate the DOM
        });
    </script>
</section>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      var currentUrl = window.location.href;
      var urlParams = new URLSearchParams(window.location.search);
      var typeValue = urlParams.get('type');

      if (typeValue === 'call') {
          // Handle 'call' case
          var voiceSection = document.getElementById('voicesection');
          if (voiceSection) {
              voiceSection.style.display = '';
          }

          var callTab = document.getElementById('calltab');
          if (callTab) {
              callTab.classList.add('active');
          }
      } else if (typeValue === 'video') {
          // Handle 'video' case
          var videoSection = document.getElementById('videocallsection');
            var voiceSection = document.getElementById('voicesection');
          if (videoSection) {
              videoSection.style.display ='';
              voiceSection.style.display = 'none';
          }
          var videoTab = document.getElementById('videotab');
          if (videoTab) {
              videoTab.classList.add('active');
          }
      }
      else if (typeValue === 'chat') {
          // Handle 'chat' case
          var chatSection = document.getElementById('chatsection');
            var voiceSection = document.getElementById('voicesection');
          if (chatSection) {
              chatSection.style.display = '';
                voiceSection.style.display = 'none';
          }

          var chatTab = document.getElementById('chattab');
          if (chatTab) {
              chatTab.classList.add('active');
          }
      }
  });

    document.addEventListener('DOMContentLoaded', function() {
      const readMoreLink = document.getElementById('readMoreLink');
      const moreText = document.getElementById('moreText');

      readMoreLink.addEventListener('click', function() {
          // Check if the moreText is currently shown
          if (moreText.classList.contains('show')) {
              readMoreLink.innerHTML = 'Read More <i class="bi bi-arrow-right"></i>';
          } else {
              readMoreLink.innerHTML = 'Read Less <i class="bi bi-arrow-up"></i>';
          }
      });
  });


    document.addEventListener('DOMContentLoaded', function() {
  flatpickr("#inline-calendar, #chatinline-calendar, #voiceinline-calendar", {
      inline: true,
      minDate: "today",
      disable: [
          function(date) {
              const today = new Date();
              const allowedDays = [
                  @foreach ($weekDaysArray as $day)
                      {{ $day }},
                  @endforeach
              ]; // Allowed days like Monday (1), Tuesday (2)

              // Disable the current date and any day not in the allowedDays array
              return date.toDateString() === today.toDateString() || !allowedDays.includes(date.getDay());
          }
      ],
  });
});

        var button = $('input[type="submit"]');
        var validateAndSubmit = function(form) {
            const total_price = $('#charge').text();
            $('#total_price,#chattotal_price,#voicetotal_price').val(total_price);
            var _f = $(form);

            button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
            _f.find('.btn-outline-danger').removeClass('btn-outline-danger');
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
                .done(function(msg) {
                    console.log(msg);
                    button.removeClass('disabled').removeAttr('disabled').val('Submit');
                    toastr.success(msg.message); // Display success message
                    $('.loader').addClass("d-none");
                    // Redirect if 'redirectto' is available in the response
                    if (msg.redirectto) {
                    // Check if the consultation is scheduled for "now" or "later"
                    let confirmationText = msg.scheduled_for === 'now'
                        ? "Your Consultation is Scheduled. Click to Join"
                        : "Your Consultation is Scheduled for Later";

                    Swal.fire({
                        title: "Are you sure?",
                        text: confirmationText, // Use dynamic text based on 'msg.scheduled_for'
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: msg.scheduled_for === 'now' ? "Join Meeting" : "OK" // Button text for 'now' or 'later'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to the provided URL if 'msg.scheduled_for' is 'now'
                            if (msg.scheduled_for === 'now') {
                                window.location.href = msg.redirectto;
                            } else {
                                // Redirect to homepage if 'later'
                                window.location.href = '/';
                            }
                        }
                    });
                }
                else if (msg.chat_id) {
                    // Check if the consultation is scheduled for "now" or "later"
                    let confirmationText = msg.scheduled_for === 'now'
                        ? "Your Consultation is Scheduled. Click to Join"
                        : "Your Consultation is Scheduled for Later";

                    Swal.fire({
                        title: "Are you sure?",
                        text: confirmationText, // Dynamic text based on 'msg.scheduled_for'
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: msg.scheduled_for === 'now' ? "Join Meeting" : "OK" // Button text for 'now' or 'later'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (msg.scheduled_for === 'now') {
                                // Open the chat in a new tab if 'msg.scheduled_for' is 'now'
                                window.open(`/account/${msg.chat_id}/chat`, '_blank');
                            } else {
                                // Redirect to homepage if 'later'
                                window.location.href = '/';
                            }
                        }
                    });
                }
                else if (msg.redirectto === '') {
      const modal = new bootstrap.Modal(document.getElementById('modalId'));
      modal.show();

      // Check if the consultation is scheduled for "now" or "later"
      let connectionText = msg.scheduled_for === 'now'
          ? "Connecting with Astrologer"
          : "Your Consultation is Scheduled for Later. You will be notified.";

      document.getElementById('callconnecting').innerText = connectionText; // Dynamic text

      if (msg.scheduled_for === 'now') {
          const astrologerId = "{{ $astrologer->slug }}";
          const callRoute = `{{ route('click-to-call') }}`;
          $.ajax({
              url: callRoute,
              type: 'POST',
              data: {
                  astrologer_slug: astrologerId,
                  call_history_id: msg.chat_history_id,
              },
              success: function(response) {
                  console.log('Route hit successfully:', response);
              },
              error: function(xhr, status, error) {
                  console.error('Error hitting the route:', error);
              }
          });

          // If the modal is hidden, open the external page for the call
          $('#modalId').on('hidden.bs.modal', function () {
              window.open(`/`);
          });
      } else {
          // For later, close the modal after a message and redirect to the homepage
          $('#modalId').on('hidden.bs.modal', function () {
              window.location.href = '/';
          });
      }
  }
              else if(msg.bal === 'lowbalance'){
                              // Show the insufficient balance modal
                const modal = new bootstrap.Modal(document.getElementById('insufficientBalanceModal'));
                modal.show();

                // Handle the recharge wallet button click
                document.getElementById('rechargeWalletButton').onclick = function() {
                    window.location.href = msg.recharge; // Redirect to the recharge URL
                };
              }
                })
                .fail(function(xhr) {
                    var response = $.parseJSON(xhr.responseText);
                    $('.loader').addClass("d-none");
                    // Check if the response has a 'message' key
                    if (response.message) {

                        toastr.error(response.message); // Display the custom error message
                        button.removeClass('disabled').removeAttr('disabled').val('Submit');
                        // Redirect if 'redirectto' is available in the response
                        if (response.redirectto) {
                            setTimeout(function() {
                                window.location.href = response.redirectto;
                            }, 3000);

                            Swal.fire({
                              title: "Are you sure?",
                              text: "You won't be able to revert this!",
                              icon: "warning",
                              showCancelButton: true,
                              confirmButtonColor: "#3085d6",
                              cancelButtonColor: "#d33",
                              confirmButtonText: "Yes, delete it!"
                            }).then((result) => {
                              if (result.isConfirmed) {
                                Swal.fire({
                                  title: "Deleted!",
                                  text: "Your file has been deleted.",
                                  icon: "success"
                                });
                              }
                            });
                        }
                        if(response.chat_id){

                        }
                    }
                    // Check if the response has an 'errors' key
                    else if (response.errors) {
                        $.each(response.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                toastr.error(message); // Display each validation error message
                            });
                        });

                        button.removeClass('disabled').removeAttr('disabled').val('Submit');
                    }
                });

            return false;
        }
        function activetab() {
    const sections = ['videocallsection', 'chatsection', 'voicesection'];

    sections.forEach(section => {
        const element = document.getElementById(section);
        if (element && element.style.display !== 'none') {
            // Activate the tab if the section is visible
            if (section === 'videocallsection') {
                $('#videotab').addClass('active');
            } else if (section === 'chatsection') {
                $('#chattab').addClass('active');
            } else if (section === 'voicesection') {
                $('#calltab').addClass('active');
            }
        } else {
            // Ensure the tab is not active if the section is not visible
            if (section === 'videocallsection') {
                $('#videotab').removeClass('active');
            } else if (section === 'chatsection') {
                $('#chattab').removeClass('active');
            } else if (section === 'voicesection') {
                $('#calltab').removeClass('active');
            }
        }
    });
}
var typingTimer; // Declare typing timer for debounce

function birthplaces() {
    clearTimeout(typingTimer); // Clear the timer if it exists (debounce logic)

    // Set the timer to run the AJAX request after 2 seconds (2000ms)
    typingTimer = setTimeout(function() {
        var inputVal = $('#birthplacechat').val(); // Get the value of the input field

        if (inputVal.length > 2) { // Make sure input is at least 3 characters
            $.ajax({
                url: '/search-location/' + encodeURIComponent(inputVal), // Make the GET request
                method: 'GET',
                success: function(response) {
                    $('#chatbirthplaceresponce').val(response); // Set the response in the hidden input field
                    console.log(response); // Log the response to the console

                    var suggestionBox = $('#birth_place_suggestion');
                    suggestionBox.empty(); // Clear previous suggestions

                    response.forEach(function(location) {
                        var li = $('<li></li>'); // Create a new list item
                        li.text(location.place + ', ' + location.state + ', ' + location.country);

                        // Handle click on the suggestion
                        li.on('click', function() {
                            $('#birthplacechat').val(location.place); // Set clicked place in input
                            suggestionBox.empty(); // Clear suggestions after selection
                        });

                        suggestionBox.append(li); // Append the list item to the suggestion box
                    });
                },
                error: function(xhr, status, error) {
                    // Handle any error from the AJAX call
                    console.error(error);
                }
            });
        } else {
            $('#birth_place_suggestion').empty(); // Clear suggestions if input is too short
        }
    }); // Delay for 2 seconds (2000 milliseconds)
}

        $(document).ready(function() {

          $("#video, #call, #chat").datepicker({
                  dateFormat: "mm/dd/yy",        // Set your preferred date format
                  maxDate: 0,                    // Disallow future dates
                  changeYear: true,              // Enable year dropdown
                  changeMonth: true,             // Enable month dropdown
                  yearRange: "1900:c",           // Set year range from 1900 to the current year
                  showButtonPanel: true,         // Optionally, show buttons like "Today" and "Done"
              });
            $('#intervaltime, #intervaltimejs,#chatintervaltime, #chatintervaltimejs,#voiceintervaltime, #voiceintervaltimejs')
                .on('change', 'input[name="scheduled_time"]', function() {
                    var selectedValue = $(this).val();
                    console.log('Selected time:', selectedValue);
                    $('#selected_time_slot ,#chatselected_time_slot,#voiceselected_time_slot').val(
                        selectedValue);
                    const selectedSlot = $(this).val();
                    const actualDate1 = $('#inline-calendar').val();
                    const actualDate2 = $('#chatinline-calendar').val();
                    const actualDate3 = $('#voiceinline-calendar').val();
                    const selectedDate = actualDate3 || actualDate2 || actualDate1;
                    // console.log('pratyus' + actualDate);
                    const [hour, minute] = selectedSlot.split(':');
                    const selectedHourNumber = parseFloat(hour);
                    const selectedMinuteNumber = parseFloat(minute);
                    if (selectedDate) {
                        $('#datetime, #chatdatetime, #voicedatetime').html('<b>Scheduled Date :</b> <span>' +
                            selectedDate + ', ' + selectedSlot + '</span>');
                    }
                });
            $('input[id^="slotmin-"]').on('change', function() {
                const selectedSlot = parseFloat($(this).val());
                const actualPrice = $('#actualprice').val(); // Regular price
                const actualChatPrice = $('#chatactualprice').val(); // Chat price
                const actualVoicePrice = $('#voiceactualprice').val(); // Voice price
                // Calculate the total price for both regular and chat
                const totalPrice = actualPrice * selectedSlot;
                const totalChatPrice = actualChatPrice * selectedSlot;
                const totalVoicePrice = actualVoicePrice * selectedSlot;
                // Update the HTML for both regular and chat
                $('#selectedminute, #chatselectedminute, #voiceselectedminute').text(selectedSlot.toFixed(
                    2));
                $('#charge').text(totalPrice.toFixed(2));
                $('#chatcharge').text(totalChatPrice.toFixed(2));
                $('#voicecharge').text(totalVoicePrice.toFixed(2));
            });
            $('input[id^="slot-"]').on('change', function() {
                alert("pratysu");
                const selectedSlot = $(this).val();
                const actualDate = $('#inline-calendar').val();
                console.log('pratyus' + actualDate);
                const [hour, minute] = selectedSlot.split(':');
                const selectedHourNumber = parseFloat(hour);
                const selectedMinuteNumber = parseFloat(minute);
                $('#datetime').html('Scheduled Date: <span> ' + actualDate + ', ' + selectedSlot +
                    '</span>');
            });

            $('#inline-calendar ,#chatinline-calendar,#voiceinline-calendar').on('change', function() {
                const selectedDate = new Date($(this).val());
                const dayOfWeek = selectedDate.getDay();
                const astro_id = $('#astro_id').val();
                // Log the day of the week to the console
                // console.log('Day of the week:', dayOfWeek);
                $.ajax({
                        method: "GET",
                        url: '{{ route('astrologers.date.response') }}',
                        data: {
                            dayOfWeek: dayOfWeek,
                            astro_id: astro_id
                        }

                    })
                    .done(function(response) {
                        // Clear any previous time slots
                        $('#intervaltime ,#chatintervaltime,#voiceintervaltime').empty();
                        $('#intervaltimejs ,#chatintervaltimejs,#voiceintervaltimejs').empty();

                        // Append 15-minute interval time slots
                        response.timeIntervals15.forEach(function(time, index) {
                            $('#intervaltime ,#chatintervaltime,#voiceintervaltime').append(`
                            <div class="time-slot mb-2">
                                <input type="radio" id="slot-15-${index}" name="scheduled_time" value="${time}">
                                <label for="slot-15-${index}" class="custom-radio">${time}</label>
                            </div>
                        `);
                        });

                        // Optionally append 30-minute interval time slots (if needed)
                        // You can comment this out if you don't want 30-minute intervals in the same list
                        response.timeIntervals30.forEach(function(time, index) {
                            $('#intervaltimejs ,#chatintervaltimejs,#voiceintervaltimejs')
                                .append(`
                                <div class="time-slot mb-2">
                                    <input type="radio" id="slot-15-${index}" name="scheduled_time" value="${time}">
                                    <label for="slot-15-${index}" class="custom-radio">${time}</label>
                                </div>
                            `);
                        });

                        // Optionally append 30-minute interval time slots (if needed)
                        // You can comment this out if you don't want 30-minute intervals in the same list
                        response.timeIntervals30.forEach(function(time, index) {
                            $('#intervaltimejs ,#chatintervaltimejs').append(`
                                    <div class="time-slot mb-2">
                                        <input type="radio" id="slot-30-${index}" name="scheduled_time" value="${time}">
                                          <label for="slot-30-${index}" class="custom-radio">${time}</label>
                                    </div>
                                `);
                        });

                    }).fail(function() {
                        toastr.error("Failed to load time slots.");
                    });
            });

           // Handle scheduling buttons
$('#schedule, #chatschedule, #voiceschedule').click(function() {
    // Hide all elements first
    $("#schedulemeeting, #schedulechat, #schedulevoice").addClass('d-none');
    // Show only the relevant scheduling element based on the clicked button
    if ($(this).is('#schedule')) {
        $("#schedulemeeting").removeClass('d-none');
    } else if ($(this).is('#chatschedule')) {
        $("#schedulechat").removeClass('d-none');
    } else if ($(this).is('#voiceschedule')) {
        $("#schedulevoice").removeClass('d-none');
    }

    // Adjust button styles
    $("#consult").removeClass('btn-secondary').addClass('btn-light');
    $("#schedule").removeClass('btn-light').addClass('btn-secondary');

    // Hide availability and consultation buttons
    $(".isavailable").addClass('d-none');
    $("#consultnowid, #chatconsultnowid, #voiceconsultnowid").addClass('d-none');
});

// Handle consultation buttons
$('#consult, #chatconsult, #voiceconsult').click(function() {
    // Hide all scheduling options
    $("#schedulemeeting, #schedulechat, #schedulevoice").addClass('d-none');

    // Show availability and consultation buttons based on the clicked button
    $(".isavailable").removeClass('d-none');
    if ($(this).is('#consult')) {
        $("#consultnowid").removeClass('d-none');
    } else if ($(this).is('#chatconsult')) {
        $("#chatconsultnowid").removeClass('d-none');
    } else if ($(this).is('#voiceconsult')) {
        $("#voiceconsultnowid").removeClass('d-none');
    }

    // Adjust button styles
    $("#consult").removeClass('btn-light').addClass('btn-secondary');
    $("#schedule").removeClass('btn-secondary').addClass('btn-light');
});

            $('input[type="radio"]').on('click', function() {
                // If "slotmin-15" is clicked
                if ($(this).attr('id') === 'slotmin-15') {
                    $('#intervaltimejs ,#chatintervaltimejs ,#voiceintervaltimejs')
                        .hide(); // Hide the div with id="intervaltimejs"
                    $('#consultNow30min').hide(); // Hide the div with id="intervaltimejs"
                    $('#intervaltime ,#chatintervaltime ,#voiceintervaltime').show(); // Show the div with id="intervaltime"
                    $('#consultNow15min').show(); // Show the div with id="intervaltime"
                }
                // If "slotmin-30" is clicked
                else if ($(this).attr('id') === 'slotmin-30') {
                    $('#intervaltime ,#chatintervaltime ,#voiceintervaltime').hide(); // Hide the div with id="intervaltime"
                    $('#consultNow15min').hide(); // Hide the div with id="intervaltime"
                    $('#intervaltimejs ,#chatintervaltimejs ,#voiceintervaltimejs')
                        .show(); // Show the div with id="intervaltimejs"
                    $('#consultNow30min').show(); // Show the div with id="intervaltimejs"
                }
            });

            var reviewdata = function(form) {
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
                .done(function(msg) {
                    console.log(msg);
                    // button.removeClass('disabled').removeAttr('disabled').val('Submit');
                    toastr.success(msg.message); // Display success message
                    $('.loader').addClass("d-none");
                    // Redirect if 'redirectto' is available in the response
                    // if (msg.redirectto) {
                    //     setTimeout(function() {
                    //         window.location.href = msg.redirectto;
                    //     }, 3000);
                    // }
                })
                .fail(function(xhr) {
                    var response = $.parseJSON(xhr.responseText);
                    $('.loader').addClass("d-none");
                    // Check if the response has a 'message' key
                    if (response.message) {

                    }
                    // Check if the response has an 'errors' key
                    else if (response.errors) {

                    }
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
    </script>
@endpush
