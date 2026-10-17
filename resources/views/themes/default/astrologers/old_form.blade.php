@if(!auth()->guard('astrologer')->user())
        <section class="p-2">
            <div class="container-fluid container-xl">
                <div class="row d-flex justify-content-center">
                      <!-- video call section Start  -->
                    <div id="videocallsection" class="bg-light p-5" style="display: none">
                        <div class="row">
                            <div class="col-sm-4 border-end">
                                <h3 class="fs-4 text fw-semibold mb-3">
                            Video Price : <span>₹ {{ $astrologer->special_price }}/min</span>
                        </h3>
                        <form data-form="video" action="{{ route('astrologers.call.scheduled') }}" method="post"
                     onsubmit="return validateAndSubmit(this)">
                     @csrf
                     <input type="hidden" name="actualprice" id="actualprice" value="{{ $astrologer->special_price }}">
                     <input type="hidden" name="astrologer_id" id="astro_id" value="{{ $astrologer->slug }}">
                     <input type="hidden" name="user_id" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->id : '' }}">
                     <input type="hidden" name="total_price" value="" id="total_price">

                                <input type="hidden" name="type" value="1">
                                <div class="col-md-12 time-slot-container d-flex ">
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


                        <div class="my-4">
                            <h3 class="fs-4 text fw-semibold mb-3">
                                Select Meeting Duration
                            </h3>
                            <div class="col-md-12 time-slot-container d-flex  ">
                                <div class="time-slot-container">
                                  <!-- 15-Minute Slot -->
                                  <div class="time-slot">
                                      <input type="radio" checked id="slotmin-15" name="timeslot" value="15">
                                      <label for="slotmin-15" class="custom-radio">15 min</label>
                                  </div>
                                  <!-- 30-Minute Slot -->
                                  <div class="time-slot">
                                      <input type="radio" id="slotmin-30"  name="timeslot" value="30" >
                                      <label for="slotmin-30" class="custom-radio">30 min </label>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <h3 class="fs-4 text fw-semibold mb-3">
                                Consultation Charges
                            </h3>
                            <ul class="list-group d-flex flex-row flex-wrap gap-2  p-0 m-0">
                                <li class="list-group-item text-center"><strong>Duration :</strong> <span id="selectedminute"> 15 </span> min |  <strong>Total Charges :</strong> ₹<span id="charge">{{ $astrologer->special_price * 15 }}</span></li>
                                <li class="w-100 text-center" id="datetime"></li>
                            </ul>
                        </div>
                    </div>
                        {{--  @if ($isTodayAvailable)

                        @else
                            @if (!$isTodayAvailable)
                                <p class="isavailable text-center">Today is not available for consultation. Please select
                                    another date.</p>
                            @endif
                        @endif --}}
                        <div class="col-sm-8">
                        <div class="schedule d-none" id="schedulemeeting">
                            <div class="col-lg-8">
                                <h3 class="fs-4 text fw-semibold mb-3">
                                    Select Date
                                </h3>
                                <div class="my-4">
                                    <div class="col-md-12  align-items-center">
                                        <input type="date" class="d-none" name="scheduled_date" id="inline-calendar"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 slot video-slot d-none">
                                <h3 class="fs-4 text fw-semibold mb-3">
                                    Select Slot
                                </h3>
                                <div class="slots-time">
                                    <ul class="list-group">
                                        <li>
                                            <div
                                                class="col-md-12 time-slot-container d-flex flex-column">
                                                <div class="slot_time d-flex flex-column gy-3 w-100">
                                                  <div id="intervaltimejs" class="select-slot"></div>
                                                    <div id="intervaltime" class="select-slot"></div>
                                                    <input type="hidden" id="selected_time_slot"
                                                        name="selected_time_slot">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0" id="videoform">
                            <h3 class="fs-4 text fw-semibold mb-3" data-form="video">
                                Please share your birth detailsgg
                            </h3>
                            <div class="row mb-3">
                                <div class="col-6 col-md-4">
                                  <label for="name" >Name <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="name" placeholder="Enter Your Name" aria-label="Name" value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->name : ''}}">
                                </div>
                                <div class="col-6 col-md-4">
                                  <label for="email" >Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" placeholder="Email" aria-label="Email" value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->email : '' }}">
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="mobile" >Mobile<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mobile" placeholder="Mobile" aria-label="Mobile" value="{{ auth()->guard('web')->user() ? auth()->guard('web')->user()->phone : '' }}" maxlength="10" pattern="\d{10}">
                                  </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col-6 col-md-4">
                                  <label for="dateofbirth">Date Of Birth <span class="text-danger">*</span></label>
                                    <input type="text" id="video" class="form-control" name="dateofbirth"
                                        placeholder="Date of Birth" aria-label="Date of Birth"
                                        value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_date: '' }}">
                                </div>

                              <div class="col-6 col-md-4">
                                <label for="time" >Birth Time <span class="text-danger">*</span></label>
                                <input class="form-control" type="time" name="birth_time" value="" >
                              </div>
                              <div class="col-6 col-md-4 bp-suggestion">
                                <label for="birthplace" >Birth Place <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="birthplacechat3" onkeyup="birthplaces('#birthplacechat3', '#birth_place_suggestion_video')" name="birthplace"
                                           placeholder="Birth Place" aria-label="Birth Place"
                                           value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_place : '' }}" >
                                    <div class="suggestion_birth">
                                        <ul id="birth_place_suggestion_video"></ul>
                                    </div>
                                </div>
                                <div class="col col-md-4">
                                  <label for="maritial_status" >Marital Status <span class="text-danger">*</span></label>
                                  <select name="maritial_status" class="form-select" aria-label="Default select example" id="maritial_status">
                                     <option value="">Select</option>
                                     <option value="divorced">Divorce</option>
                                     <option value="married">Married</option>
                                     <option value="not_specified">Not Specified</option>
                                     <option value="single">Single</option>
                                     <option value="widow">Widow</option>
                                   </select>
                                </div>
                                <div class="col col-md-4">
                                  <label for="occupation" >Occupation <span class="text-danger">*</span></label>
                                  <select class="form-select" name="occupation" aria-label="Default select example" id="occupation">
                                  <option value="">Select</option>
                                    <option value="business_person">Bussiness Persone</option>
                                    <option value="employed">Employe</option>
                                    <option value="house_wife">House Wife</option>
                                    <option value="not_specified">Not Specified</option>
                                    <option value="retaired">Retaired</option>
                                    <option value="student">Student</option>
                                 </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-end">
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
                    {{-- video call section end --}}

                    <div id="chatsection" class="bg-light p-5  " style="display:none;">
                        <div class="row">
                            <div class="col-sm-4 border-end">
                        <div>
                          <h3 class="fs-4 text fw-semibold mb-3">
                         Chat Price : <span>₹ {{ $astrologer->chat_special_price }}/min</span>
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
                                    class="col-md-12 time-slot-container d-flex align-items-center">
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

                        <div class="my-4" >
                            <h4 class="fs-4 text fw-semibold mb-4">
                                Select Chat Duration
                            </h4>
                            <div class="col-md-12 time-slot-container">
                                <div class="time-slot-container">
                                  <!-- 15-Minute Slot -->
                                  <div class="time-slot">
                                      <input type="radio" id="slotmin-15" checked name="timeslot" value="15">
                                      <label for="slotmin-15" class="custom-radio">15 min</label>
                                  </div>
                                  <!-- 30-Minute Slot -->
                                  <div class="time-slot">
                                      <input type="radio" id="slotmin-30"  name="timeslot" value="30" >
                                      <label for="slotmin-30" class="custom-radio">30 min</label>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-2">
                            <h4 class="fs-4 text fw-semibold mb-3">
                                Consultation Charges
                            </h4>
                            <ul class="list-group gap-2 m-0 p-0">
                                <li class="list-group-item fs-6"><b>Duration :</b> <span id="chatselectedminute">15 </span> min | <b>Total Charges :</b>₹<span id="chatcharge">{{ number_format($astrologer->chat_special_price * 15, 0, '.', ',') }}</span></li>
                                <li class="w-100 text-center" id="chatdatetime"></li>
                            </ul>
                        </div>
                        {{--  @if ($isTodayAvailable)

                        @else
                            @if (!$isTodayAvailable)
                                <p class="isavailable text-center">Today is not available for consultation. Please select
                                    another date.</p>
                            @endif
                        @endif--}}

                    </div>
                    <div class="col-sm-8">
                        <div class="desktop-pl-40">
                        <div class="schedule d-none" id="schedulechat">
                            <div class="col-lg-8">
                                <h3 class="fs-4 text fw-semibold mb-3">
                                    Select Date
                                </h3>
                                <div class="my-4">
                                    <div class="col-md-12  align-items-center">
                                        <input type="date" class="d-none" name="scheduled_date"
                                            id="chatinline-calendar" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 slot chat-slot d-none">
                                <h3 class="fs-4 text fw-semibold mb-3">
                                    Select Slot
                                </h3>
                                <div class="slots-time">
                                    <ul class="list-group">
                                        <li>
                                            <div
                                                class="col-md-12 time-slot-container d-flex flex-column justify-content-center align-items-center">
                                                <div class="slot_time d-flex flex-column gy-3 w-100">
                                                  <div id="chatintervaltimejs" class="select-slot"></div>
                                                    <div id="chatintervaltime" class="select-slot"></div>
                                                    <input type="hidden" id="chatselected_time_slot"
                                                        name="selected_time_slot">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mt-0" id="chatform">
                            <h3 class="fs-4 text fw-semibold mb-3">Please share your birth details1</h3>

                            <div class="row mb-3">
                                <div class="col col-md-4">
                                  <label for="name" >Name <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" name="name" placeholder="Name"
                                        aria-label="Name" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->name: '' }}">
                                </div>
                                <div class="col col-md-4">
                                  <label for="email" >Email <span class="text-danger">*</span></label>

                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                        aria-label="Email" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->email: '' }}">
                                </div>
                                <div class="col col-md-4">
                                    <label for="mobile" >Mobile<span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" name="mobile" placeholder="Mobile"
       aria-label="Mobile" value="{{ auth()->guard('web')->user() ? auth()->guard('web')->user()->phone : '' }}" maxlength="10" pattern="\d{10}" required title="Please enter a 10-digit mobile number.">

                                  </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col col-md-4">
                                  <label for="dateofbirth">Date Of Birth <span class="text-danger">*</span></label>

                                    <input type="text" id="chat" class="form-control" name="dateofbirth"
                                        placeholder="Date of Birth" aria-label="Date of Birth"
                                        value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_date :''}}">
                                </div>

                                <div class="col col-md-4">
                                  <div class="grid  gap-2">
                                    <label for="time" >Birth Time <span class="text-danger">*</span></label>

                                    <input class="form-control" type="time" name="birth_time" value="" >
                                  </div>
                                </div>
                                <div class="col col-md-4 bp-suggestion">
                                  <label for="birthplace" >Birth Place <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="birthplacechat2" onkeyup="birthplaces('#birthplacechat2', '#birth_place_suggestion')" name="birthplace"
                                             placeholder="Birth Place" aria-label="Birth Place"
                                             value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_place : '' }}" >
                                      <div class="suggestion_birth">
                                          <ul id="birth_place_suggestion"></ul>
                                      </div>
                                  </div>
                                  <div class="col col-md-4">
                                    <label for="maritial_status" >Marital Status <span class="text-danger">*</span></label>
                                          <select name="maritial_status" class="form-select" id="maritial_status">
                                             <option value="">Select</option>
                                             <option value="divorced">Divorce</option>
                                             <option value="married">Married</option>
                                        <option value="not_specified">Not Specified</option>
                                          <option value="single">Single</option>
                                          <option value="widow">Widow</option>
                                           </select>
                                  </div>
                                  <div class="col col-md-4">
                                    <label for="occupation" >Occupation <span class="text-danger">*</span></label>
                                      <select class="form-select" name="occupation" aria-label="Default select example" id="occupation">
                                       <option value="">Select</option>
                                       <option value="business_person">Bussiness Persone</option>
                                       <option value="employed">Employe</option>
                                       <option value="house_wife">House Wife</option>
                                        <option value="not_specified">Not Specified</option>
                                        <option value="retaired">Retaired</option>
                                        <option value="student">Student</option>
                                     </select>
                                  </div>

                            </div>
                            <div class="row">
                                <div class="col text-end">
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
                    </div>
                        </div>
                        </div>
                    </div>
                        </form>

                    {{-- video call section end  --}}

                    <!-- Voice Call Start from here -->

                    <div id="voicesection" class="bg-light p-5" style="display:none;">
                        <div class="row">
                            <div class="col-sm-4 border-end">

                          <h3 class="fs-4 text fw-semibold mb-3">
                         Call Price : <span>₹ {{ $astrologer->call_special_price }}/min</span>
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
                                    class="col-md-12 time-slot-container">
                                    <div class="time-slot-container d-flex flex-wrap">
                                        <!-- Consult now -->
                                        <div class="time-slot">
                                            <input type="radio" id="voiceconsult" name="scheduled_for" value="0"
                                                required checked>
                                            <label for="consult-now" id="scheduled-later" class="custom-radio custom-r">Consult
                                                Now</label>
                                        </div>
                                        <!-- Scheduled Later -->
                                        <div class="time-slot">
                                            <input type="radio" id="voiceschedule" name="scheduled_for"
                                                value="1">
                                            <label for="schedule-later" id="scheduled-later"
                                                class="custom-radio custom-r">Schedule
                                                for Later</label>
                                        </div>
                                    </div>

                                </div>


                        <div class="my-4">
                            <h3 class="fs-4 text fw-semibold mb-3">
                                Select Voice Call Duration
                            </h3>
                            <div class="col-md-12 time-slot-container d-flex">
                                <div class="time-slot-container">
                                <!-- 15-Minute Slot -->
                                <div class="time-slot">
                                    <input type="radio" checked id="slotmin-15" name="timeslot" value="15">
                                    <label for="slotmin-15" class="custom-radio">15 min</label>
                                </div>
                                  <!-- 30-Minute Slot -->
                                  <div class="time-slot">
                                      <input type="radio" id="slotmin-30"  name="timeslot" value="30">
                                      <label for="slotmin-30" class="custom-radio">30 min</label>
                                  </div>
                                </div>
                            </div>
                        </div>

                            <h3 class="fs-4 text fw-semibold mb-3">
                                Consultation Charges
                            </h3>
                            <ul class="list-group p-0 m-0">
                                <li class="list-group-item fs-6"><strong>Duration :</strong> <span id="voiceselectedminute">15 </span> min | <strong>Total Charges :</strong> ₹<span id="voicecharge">{{ $astrologer->call_special_price *15}}</span></li>
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

                        <!-- Schedule Later Calender -->
                         <div class="col-sm-8">
                            <div class="desktop-pl-40">
                        <div class="schedule  d-none" id="schedulevoice">
                            <div class="col-lg-8">
                                <h3 class="fs-4 text fw-semibold mb-3">Select Date</h3>
                                <div class="my-4">
                                    <div class="col-md-12  align-items-center">
                                        <input type="date" class="d-none" name="scheduled_date"
                                            id="voiceinline-calendar" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 slot call-slot d-none">
                                <h3 class="fs-4 text fw-semibold mb-3">Select Slot</h3>
                                <div class="slots-time">
                                    <ul class="list-group">
                                        <li>
                                            <div
                                                class="col-md-12 time-slot-container d-flex flex-column justify-content-center align-items-center">
                                                <div class="slot_time d-flex flex-column gy-3 w-100">
                                                  <div id="voiceintervaltimejs" class="select-slot"></div>
                                                    <div id="voiceintervaltime" class="select-slot"></div>
                                                    <input type="hidden" id="voiceselected_time_slot"
                                                        name="selected_time_slot">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Birth Details -->
                        <div id="callform" >
                            <h3 class="fs-4 text fw-semibold mb-3">
                               Please share your birth details
                            </h3>

                            <div class="row mb-3">
                                <div class="col col-md-4">
                                  <label for="name" >Name <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" name="name" placeholder="Name"
                                        aria-label="Name" value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->name: '' }}">
                                </div>
                                <div class="col col-md-4">
                                  <label for="email" >Email <span class="text-danger">*</span></label>

                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                        aria-label="Email" value="{{ auth() && auth()->guard('web')->user() ?auth()->guard('web')->user()->email: '' }}">
                                </div>
                                <div class="col col-md-4">
                                    <label for="mobile" >Mobile<span class="text-danger">*</span></label>

                                      <input type="number" class="form-control" name="mobile" placeholder="Mobile"
                                          aria-label="Mobile" value="{{ auth() && auth()->guard('web')->user() ?auth()->guard('web')->user()->phone: '' }}">
                                  </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col col-md-4">
                                  <label for="dateofbirth">Date Of Birth <span class="text-danger">*</span></label>

                                    <input type="text" id="call" class="form-control" name="dateofbirth"
                                        placeholder="Date of Birth" aria-label="Date of Birth"
                                        value="{{auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_date: '' }}">
                                </div>

                                <div class="col">
                                  <div class="grid gap-2">
                                    <label for="time" >Birth Time <span class="text-danger">*</span></label>

                                    <input class="form-control" type="time" name="birth_time" value="" >
                                  </div>
                                </div>
                                <div class="col col-md-4 bp-suggestion">
                                  <label for="birthplace" >BirthPlace <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" id="birthplacechat1" onkeyup="birthplaces('#birthplacechat1', '#birth_place_suggestion_call')" name="birthplace"
                                         placeholder="Birth Place" aria-label="Birth Place"
                                         value="{{ auth() && auth()->guard('web')->user() ? auth()->guard('web')->user()->birth_place : '' }}" >
                                  <div class="suggestion_birth">
                                      <ul id="birth_place_suggestion_call"></ul>
                                  </div>
                              </div>
                              <div class="col col-md-4">
                                <label for="maritial_status" >Marital Status <span class="text-danger">*</span></label>
                                      <select name="maritial_status" class="form-select" id="maritial_status">
                                         <option value="">Select</option>
                                         <option value="divorced">Divorce</option>
                                         <option value="married">Married</option>
                                    <option value="not_specified">Not Specified</option>
                                      <option value="single">Single</option>
                                      <option value="widow">Widow</option>
                                       </select>
                              </div>
                              <div class="col col-md-4">
                                <label for="occupation" >Occupation <span class="text-danger">*</span></label>
                                  <select class="form-select" name="occupation" aria-label="Default select example" id="occupation">
                                   <option value="">Select</option>
                                   <option value="business_person">Bussiness Persone</option>
                                   <option value="employed">Employe</option>
                                   <option value="house_wife">House Wife</option>
                                    <option value="not_specified">Not Specified</option>
                                    <option value="retaired">Retaired</option>
                                    <option value="student">Student</option>
                                 </select>
                              </div>

                            </div>
                            <div class="row">
                                <div class="col text-end">
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
            </div>
                    <!-- Call Consultation End -->
                </div>
            </div>
            </div>
        </section>
        @else
        <p class="text-center">You are logged in as Astrologer</p>
        @endif
