@extends(themeView().'layout')

@section('title', 'My Profile | '.config("app.name"))

@push("style")
<style media="screen">
  input[type="time"]:invalid:before {
    content: 'hh:mm:ss';
    position: absolute;
    font-size: 14px;
    letter-spacing: 1.5;
    background:#fff;
  }
  input[type="time"]:focus:before {
    width: 0;
    content: '';
  }
  .otp-boxes-wrap { gap: 8px; }
  .otp-digit-box {
    width: 42px !important;
    height: 42px !important;
    max-width: 42px;
    padding: 0;
    border-radius: 50% !important;
    font-size: 18px;
    font-weight: 600;
    line-height: 42px;
  }
</style>
<link rel="stylesheet" href="{{ asset('assets/lib/material-timepicker/mdtimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/account-dashboard.css') }}?v={{ filemtime(public_path('assets/css/account-dashboard.css')) }}">
@endpush

@section('content')
<section class="account-page-ui account-profile-page dashboad_section">
  <div class="container">
    <div class="row">
      @include(themeview().'account._sidebar')
      <div class="col-lg-9 col-sm-12 mt-5 mt-lg-0 all_pages">
        <!-- tab start -->
        <ul class="nav dashboard_tab nav-tabs dash-tab astro-account-tab" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link text-dark px-2 {{ $tab == 'my-profile' ? 'active' : '' }}" id="profile-tab" href="{{ route('user.profile', ['tab' => 'my-profile']) }}">
              My Profile
            </a>
          </li>
          {{--<li class="nav-item" role="presentation">
            <a class="nav-link text-dark" id="additional-tab" href="#additional">
              Educational  Details
            </a>
          </li>--}}
          <li class="nav-item" role="presentation">
            <a class="nav-link text-dark px-2 {{ $tab == 'change-phone-number' ? 'active' : '' }}" id="verify_phone_tab" href="{{  route('user.profile', ['tab' => 'change-phone-number']) }}" role="tab" aria-controls="profile" aria-selected="false">
              Phone Number
            </a>
          </li>
          {{--<li class="nav-item" role="presentation">
            <a class="nav-link text-dark px-2 {{ $tab == 'change-email-address' ? 'active' : '' }}" id="verify_phone_tab" href="{{  route('user.profile', ['tab' => 'change-email-address']) }}" role="tab" aria-controls="profile" aria-selected="false">
              Email Address
            </a>
          </li>--}}
        </ul>
        <!-- tab ends -->

        <!-- tab content start -->
        <div class="tab-content" id="myTabContent">
          <!-- my profile tab content start -->
          <div class="tab-pane {{ $tab == 'my-profile' ? 'active' : '' }}" id="profile">
            <form class="mt-4" method="post" action="{{route('user.profile')}}" onsubmit="return validateAndSubmit(this)">
              <input type="hidden" name="tab" value="my-profile">
              <div class="row">
                <div class="col-sm-12">
                  <div class="card mb-3 bg-white border-n">
                    <div class="card-body">
                      <h4 class="mb-4 dash-heading">Profile Info</h4>
                      <div class="row mb-3">
                        @php
                          $userImage = $user->image;
                        @endphp
                        {{--<div class="col-md-3 col-12 mb-3">
                          <div class="form-group">
                            {{ Form::label('image_id', 'Image', ['class' => 'control-label']) }}
                            <input type="file" onchange="showTempProfileImage('image_id', 'image_src')" class="form-control" id="image_id" name="file">
                            <div class="col-sm-12 float-right"></div>
                          </div>
                        </div>
                        <div class="col-md-1 col-12 mb-3">
                          <img id="image_src" src="{{ $userImage ? $userImage->image(100,100) : no_image() }}" width="100" height="100" class="img-responsive dashboad_profileimg border border-1" style="border-radius:50%;">
                        </div>--}}
                        <div class="col-md-3 col-12 mb-3">
                          <label>Image</label>
                          <img id="image_src" src="{{ $userImage ? $userImage->image(100,100) : no_image() }}" width="100" height="100" onclick="handleChooseImage('image_id')" class="img-responsive border border-1 pointer ms-3" style="border-radius:50%;">
                          <input type="file" onchange="showTempProfileImage('image_id', 'image_src')" class="form-control d-none" id="image_id" name="file">
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>Full Name <em class="text-danger">*</em></label>
                            <input type="text" class="form-control" placeholder="Full Name" name="name" value="{{ $user->name }}" id="username">
                          </div>
                        </div>
                        <div class="col-sm-4 col-12 mb-3">
                          <div class="form-group">
                            <label>Email Id <em class="text-danger">*</em></label>
                            <input type="emal" class="form-control" placeholder="Email" name="email" value="{{ $user->email }}" id="useremail">
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>Gender <em class="text-danger">*</em></label>
                            <select name="gender" class="form-select text-cap">
                              @foreach (genders() as $gid=>$gname)
                                <option value="{{ $gid }}" {{$user->gender==$gid?'selected':''}}>{{$gname}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>Preferred Language <em class="text-danger">*</em></label>
                            <select name="language_id" class="form-select">
                              <option value="">Select</option>
                              @foreach ($languages as $lang)
                                <option value="{{$lang->id}}" {{$user->language_id==$lang->id?'selected':''}}>{{$lang->name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <label for="birth_date">Date Of Birth  <span class="text-danger">*</span></label>
                          <div class="form_group_img">
                            <input type="date" max="{{ date('Y-m-d') }}" min="{{ date('Y-m-d', strtotime('-100 years')) }}" id="birth_date" class="form-control" name="birth_date" placeholder="Date of Birth" value="{{$user->birth_date!='1970-01-01' ? $user->birth_date : '' }}">
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <label>Birth Time <em class="text-danger">*</em></label>
                          <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#birth_time')" style="left:170px; top:35px"></i>
                          <input type="text" class="form-control timepicker" id="birth_time" name="birth_time" value="{{ $user->birth_time }}">
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <label for="birthplace" >Birth Place <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="birth_place" onkeyup="birthplaces('#birth_place', '#birth_place_suggestions')" name="birth_place" placeholder="Birth Place" value="{{ $user->birth_place }}">
                          <div class="suggestion_birth">
                            <ul id="birth_place_suggestions"></ul>
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>Country <em class="text-danger">*</em></label>
                            <select name="country_id" class="form-select custom-select" onchange="fetchLocations('State', this, 'state_id')">
                              <option value="0">Select Country</option>
                              @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $country->id == $user->country_id ? "selected" : "" }}>
                                  {{$country->name}}
                                </option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>State <em class="text-danger">*</em></label>
                            <select name="state_id" class="form-select custom-select" onchange="fetchLocations('City', this, 'city_id')" id="state_id">
                              <option value="0">Select State</option>
                              @foreach($states as $state)
                                <option value="{{ $state->id }}"  {{ $state->id == $user->state_id ? "selected" : "" }}>{{$state->name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>City <em class="text-danger">*</em></label>
                            <select name="city_id" class="form-select custom-select" id="city_id">
                              <option value="0">Select City</option>
                              @foreach($cities as $city)
                                <option value="{{ $city->id }}"  {{ $city->id == $user->city_id ? "selected" : "" }}>{{$city->name}}</option>
                              @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>Pincode <em class="text-danger">*</em></label>
                            <input type="number" class="form-control" placeholder="Pincode" name="pincode" value="{{$user->pincode}}" min="100000" max="999999">
                          </div>
                        </div>
                        <div class="col-12 mb-3">
                          <label>Address <em class="text-danger">*</em></label>
                          <textarea class="form-control" placeholder="Address" name="address" rows="4" >{{$user->address}}</textarea>
                        </div>

                        @if($user->is_influencer == 1)
                        <div class="col-12 mt-4 mb-3">
                          <h5 class="mb-3 dash-heading">Bank Details</h5>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>Account Number</label>
                            <input type="text" class="form-control" placeholder="Account Number" name="account_number" value="{{ $user->account_number }}">
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>IFSC Code</label>
                            <input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code" value="{{ $user->ifsc_code }}">
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>UPI ID</label>
                            <input type="text" class="form-control" placeholder="UPI ID" name="upi_id" value="{{ $user->upi_id }}">
                          </div>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                          <div class="form-group">
                            <label>Passbook Photo</label>
                            <div class="d-flex align-items-center">
                              @php
                                $passbookImage = $user->passbookImage;
                              @endphp
                              <img id="passbook_image_src" src="{{ $passbookImage ? $passbookImage->image(100,100) : no_image() }}" width="100" height="100" onclick="handleChooseImage('passbook_file')" class="img-responsive border border-1 pointer ms-3" style="border-radius:8px;">
                              <input type="file" onchange="showTempProfileImage('passbook_file', 'passbook_image_src')" class="form-control d-none" id="passbook_file" name="passbook_file" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp">
                            </div>
                          </div>
                        </div>
                        @endif

                      </div>


                      <div class="col-12 otp-verify-box-email" style="display:none;">
                        <div class="form-group">
                          <label>OTP to verify email</label>
                          <div class="d-flex align-items-center otp-boxes-wrap">
                            <input type="text" class="form-control text-center otp-digit-box otp-email-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-email-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-email-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-email-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-email-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-email-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                          </div>
                          <input type="hidden" name="otp_email" value="" id="otp_email">
                        </div>
                      </div>
                      <div class="col-12 otp-verify-box-phone" style="display:none;">
                        <div class="form-group">
                          <label>OTP to verify phone</label>
                          <div class="d-flex align-items-center otp-boxes-wrap">
                            <input type="text" class="form-control text-center otp-digit-box otp-phone-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-phone-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-phone-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-phone-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-phone-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            <input type="text" class="form-control text-center otp-digit-box otp-phone-legacy-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                          </div>
                          <input type="hidden" name="otp_phone" value="" id="otp_phone">
                        </div>
                      </div>
                      <div class="col-12 mt-3 text-center">
                        <button type="submit" class="main_button" style="height:100%;">Save </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <!-- my profile tab content end -->

          <!-- education detials tab content start -->
          {{--<div class="tab-pane {{ $tab == 'educational-details' ? 'active' : '' }}" id="additional">
            <form class="mt-4" action="{{route('user.additional')}}" onsubmit="validateAndSubmit">
              <input type="hidden" name="tab" value="educational-details">
              <div class="card mb-3 bg-light">
                <div class="card-body">
                  <h5 class="dash-heading">Educational Details</h5>
                  <div class="row mb-3 mt-3">
                    <div class="col-sm-5 col-12">
                     <div class="form-group">
                       <label>Qualification <em class="text-danger">*</em></label>
                       <select name="qualification"id="qualification" class="form-select custom-select" required>
                         @foreach(userQualification() as $key => $item)
                         <option {{$user && $user->additionalInformation && $user->additionalInformation->qualification==$key?'selected':''}} value="{{$key}}">{{$item}}</option>
                         @endforeach
                       </select>
                     </div>
                   </div>
                   <div class="col-sm-7 col-12">
                     <div class="form-group">
                       <label>Institution Name/Board <em class="text-danger">*</em></label>
                       <input type="text" class="form-control" placeholder="Institution Name/Board" name="institution" value="{{$user && $user->additionalInformation ? $user->additionalInformation->institution:''}}" required>
                     </div>
                   </div>
                 </div>
                 <div class="row">
                   <div class="col-sm-4 col-12">
                     <div class="form-group">
                       <label for="passing_year">Passing Year <span class="text-danger">*</span></label>
                       <div class="form_group_img">
                         <select class="form-select custom-select" name="passing_year" id="passing_year" required>
                           <option value="">Choose Year</option>
                           @for($i=date('Y', strtotime('-50 years')); $i<=date('Y'); $i++)
                             <option value="{{$i}}" {{$user && $user->additionalInformation && $user->additionalInformation->passing_year==$i ? 'selected' :''}}>{{$i}}</option>
                           @endfor
                         </select>
                     </div>
                   </div>
                 </div>
                 <div class="col-sm-4 col-12">
                   <div class="form-group">
                     <label>Division/Grade</label>
                     <input type="text" class="form-control" placeholder="Division/Grade" id="division_grade" name="division_grade" value="{{$user && $user->additionalInformation ? $user->additionalInformation->division_grade:''}}" >
                   </div>
                 </div>
                 <div class="col-sm-4 col-12">
                   <div class="form-group">
                     <label>Marks(%) <em class="text-danger">*</em></label>
                     <input type="text" class="form-control" placeholder="Marks(%)" name="marks" value="{{$user && $user->additionalInformation ? $user->additionalInformation->marks:''}}" required>
                   </div>
                 </div>
                 <div class="row">
                   <div class="col-sm-6 col-12">
                     <div class="form-group mb-3 mt-3"><label> Astrology Experience (if any) </label>
                       <textarea placeholder="Astrology Experience" name="experience" rows="1" class="form-control fs-7">{{$user && $user->additionalInformation ? $user->additionalInformation->experience:''}}</textarea>
                     </div>
                   </div>
                   <div class="form-group col-sm-5 col-12 mt-3">
                     {{ Form::label('marks_file', 'Image', ['class' => 'control-label']) }}
                     <input type="file" onchange="showTempProfileImage('marks_file', 'marks_file_img')" accept="image/png, image/gif, image/jpeg, image/jpg, image/webp" id="marks_file" class="form-control mb-3" placeholder="Upload Image" name="file">
                   </div>
                   <div class="col-sm-1 float-right">
                     <img id="marks_file_img" src="{{$user && $user->additionalInformation && $user->additionalInformation->image ? $user->additionalInformation->image->image(100, 100): no_image()}}" alt="{{$user && $user->additionalInformation ? $user->additionalInformation->passing_year:''}}" class="img-responsive w-100 border border-1" style="border-radius:50%;margin-top: 34px;">
                   </div>
                 </div>
                 <div class="mt-3">
                   <div class="form-group text-center">
                     <button type="submit" class="main_button" style="height: 100%; width: 15%;">
                       Save
                     </button>
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </form>
       </div>--}}
       <!-- education detials tab content end -->

       <!-- change phone number tab content start -->
       <div class="tab-pane {{ $tab == 'change-phone-number' ? 'active' : '' }}" id="verify_email">
         <form class="mt-4" method="post">
           <div class="card mb-3 bg-light border-n">
             <div class="card-body">
               <div class="row mb-3">
                 <div class="col-4 col-lg-4 col-md-2">
                   <div class="form-group">
                     <label>Country Code <em class="text-danger">*</em></label>
                     <select class="form-control" name="phone_code" id="phone_code">
                       @if($countries)
                         @foreach($countries as $key => $item)
                           <option value="{{ $item->phonecode }}" {{ $user->phone_code == $item->phonecode ? "selected" : "" }} >
                             +{{ $item->phonecode }}
                           </option>
                         @endforeach
                       @endif
                     </select>
                   </div>
                 </div>
                 <div class="col-md-4 col-8">
                   <div class="form-group">
                     <label>Phone Number <em class="text-danger">*</em></label>
                     <input type="number" class="form-control" placeholder="mobile number 10 digit" id="phone" value="{{ $user->phone }}" name="phone">
                   </div>
                 </div>
               </div>
               <div class="row mb-3 otp-field d-none" id="otp-div">
                <div class="col-12">
                   <div class="form-group">
                     <label>Otp <em class="text-danger">*</em></label>
                    <div class="d-flex align-items-center otp-boxes-wrap" id="phone-otp-boxes">
                      <input type="text" class="form-control text-center otp-digit-box phone-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box phone-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box phone-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box phone-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box phone-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box phone-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                    </div>
                   </div>
                 </div>
               </div>
               <div class="resend-otp-count-down d-none"><span>Resend otp in <span id="otp_count_down" class="text-danger fs-8">120</span> or <span class="text-gold pointer" onclick="changeMobileNumber()">Change Phone Number</span></span></div>
               <div class="mt-3">
                 <button type="button" id="get_otp_btn" class="main_button login-submit" onclick="getOtp()">Get Otp</button>
                 <button type="button" id="submit_otp_btn" class="main_button login-submit d-none" onclick="userVerifyOtp()">Submit Otp</button>
               </div>
             </div>
           </div>
         </form>
       </div>
       <!-- change phone number tab content end -->

       <!-- change email tab content start -->
       <div class="tab-pane {{ $tab == 'change-email-address' ? 'active' : '' }}" id="verify_phone">
         <form class="mt-4" method="post">
           <div class="card mb-3 bg-light">
             <div class="card-body">
               <div class="row mb-3">
                 <div class="col-md-4 col-8">
                   <div class="form-group">
                     <label>Email <em class="text-danger">*</em></label>
                     <input type="email" class="form-control" placeholder="Email Address" id="email" value="{{ $user->email }}" name="email">
                   </div>
                 </div>
               </div>
               <div class="row mb-3 email-otp-field d-none" id="email-otp-div">
                <div class="col-12">
                   <div class="form-group">
                     <label>Otp <em class="text-danger">*</em></label>
                    <div class="d-flex align-items-center otp-boxes-wrap" id="email-otp-boxes">
                      <input type="text" class="form-control text-center otp-digit-box email-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box email-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box email-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box email-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box email-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box email-otp-digit" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                    </div>
                   </div>
                 </div>
               </div>
               <div class="resend-email-otp-count-down d-none"><span>Resend otp in <span id="email_otp_count_down" class="text-danger fs-8">120</span> or <span class="text-gold pointer" onclick="changeEmailAddress()">Change Email Address</span></span></div>
               <div class="mt-3">
                 <button type="button" id="get_email_otp_btn" class="main_button login-submit" onclick="getEmailOtp()">Get Otp</button>
                 <button type="button" id="submit_email_otp_btn" class="main_button login-submit d-none" onclick="userVerifyEmailOtp()">Submit Otp</button>
               </div>
             </div>
           </div>
         </form>
       </div>
       <!-- change email tab content end -->

     </div>
     <!-- tab content end -->
   </div>
 </div>
</div>
</section>
@endsection


@push('scripts')
  <script src="{{ asset('assets/lib/material-timepicker/mdtimepicker.js') }}"></script>
  <script type="text/javascript">
    $("document").ready(function(){
      @if(\Session::has("message"))
        toastr.warning("{{ \Session::get('message') }}");
      @endif

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

    var resendOtpInterval;
  function bindOtpDigitBoxes(selector, submitFn, hiddenInputSelector){
    const otpBoxes = document.querySelectorAll(selector);
    if(!otpBoxes.length) return;
    const setHiddenValue = function(){
      if(!hiddenInputSelector) return;
      const hiddenInput = document.querySelector(hiddenInputSelector);
      if(hiddenInput) hiddenInput.value = getOtpDigitValue(selector);
    };
    otpBoxes.forEach(function(box, idx){
      box.addEventListener('input', function(){
        this.value = (this.value || '').replace(/\D/g, '').slice(0, 1);
        if(this.value && idx < otpBoxes.length - 1) otpBoxes[idx + 1].focus();
        setHiddenValue();
      });
      box.addEventListener('keydown', function(e){
        if(e.key === 'Backspace' && !this.value && idx > 0) otpBoxes[idx - 1].focus();
        if(e.key === 'Enter' && typeof submitFn === 'function'){ e.preventDefault(); submitFn(); }
      });
      box.addEventListener('paste', function(e){
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
        if(!pasted) return;
        e.preventDefault();
        pasted.split('').forEach(function(ch, i){ if(otpBoxes[i]) otpBoxes[i].value = ch; });
        otpBoxes[Math.min(pasted.length, otpBoxes.length - 1)].focus();
        setHiddenValue();
      });
    });
  }
  function clearOtpDigitBoxes(selector, hiddenInputSelector){
    const otpBoxes = document.querySelectorAll(selector);
    otpBoxes.forEach(function(box){ box.value = ''; });
    if(hiddenInputSelector){
      const hiddenInput = document.querySelector(hiddenInputSelector);
      if(hiddenInput) hiddenInput.value = '';
    }
  }
  function getOtpDigitValue(selector){
    const otpBoxes = document.querySelectorAll(selector);
    let otp = '';
    otpBoxes.forEach(function(box){ otp += (box.value || '').trim(); });
    return otp;
  }
  bindOtpDigitBoxes('.phone-otp-digit', userVerifyOtp);
  bindOtpDigitBoxes('.email-otp-digit', userVerifyEmailOtp);
  bindOtpDigitBoxes('.otp-email-legacy-digit', null, '#otp_email');
  bindOtpDigitBoxes('.otp-phone-legacy-digit', null, '#otp_phone');

    function getOtp(){
      $.ajax({
          type: "POST",
          url: @json(route('user.getotp')),
          data:{
            phone_code:$('#phone_code').val(),
            phone:$('#phone').val(),
          },
          headers: {
              'X-CSRF-TOKEN': @json(csrf_token())
          },
          success: function(response) {
            toastr.success(response.message);
            $('#phone').attr('disabled', true);
            $('#get_otp_btn').addClass('d-none');
            $('#submit_otp_btn').removeClass('d-none');
            $('.otp-field').removeClass('d-none');
            clearOtpDigitBoxes('.phone-otp-digit');
            const firstPhoneOtpBox = document.querySelector('.phone-otp-digit');
            if(firstPhoneOtpBox) firstPhoneOtpBox.focus();
            resendOtpIn = 120;
            resendOtpInterval = setInterval(function (){
              if(resendOtpIn == 0){
                $('#get_otp_btn').removeClass('d-none');
                $('.resend-otp-count-down').addClass('d-none');
                clearInterval(resendOtpInterval);
              }else{
                resendOtpIn--;
                $('#otp_count_down').text(resendOtpIn);
              }
            }, 1000);
            $('.resend-otp-count-down').removeClass('d-none');
          },
          error: function(error){
            let errors = error.responseJSON.errors;
            if(errors){
              for(var key in errors){
                toastr.error(errors[key][0]);
              }
            }
            console.log(error)
          }
      });
    }

  function changeMobileNumber(){
    clearInterval(resendOtpInterval);
    $('#submit_otp_btn').addClass('d-none');
    $('.resend-otp-count-down').addClass('d-none');
    $('#get_otp_btn').removeClass('d-none');
    $('#otp-div').addClass('d-none');
    $('#phone').attr('disabled', false);
    clearOtpDigitBoxes('.phone-otp-digit');
  }

  function userVerifyOtp (){
    const otpValue = getOtpDigitValue('.phone-otp-digit');
    if(otpValue.length !== 6){
      toastr.error('Please enter 6 digit OTP.');
      return;
    }
    $.ajax({
        type: "POST",
        url: @json(route('user.verifyotp')),
        data:{
          otp: otpValue,
        },
        headers: {
            'X-CSRF-TOKEN': @json(csrf_token())
        },
        success: function(response) {
          toastr.success(response.message);
          setTimeout(function(){
            window.location.href = response.redirectTo;
          }, 2000);
        },
        error: function(error){
          let errors = error.responseJSON.errors;
          if(errors){
            for(var key in errors){
              toastr.error(errors[key][0]);
            }
          }
          console.log(error)
        }
    });
  }

  function getEmailOtp(){
    $.ajax({
        type: "POST",
        url: @json(route('user.getotp')),
        data:{
          email:$('#email').val(),
        },
        headers: {
            'X-CSRF-TOKEN': @json(csrf_token())
        },
        success: function(response) {
          toastr.success(response.message);
          $('#email').attr('disabled', true);
          $('#get_email_otp_btn').addClass('d-none');
          $('#submit_email_otp_btn').removeClass('d-none');
          $('.email-otp-field').removeClass('d-none');
          clearOtpDigitBoxes('.email-otp-digit');
          const firstEmailOtpBox = document.querySelector('.email-otp-digit');
          if(firstEmailOtpBox) firstEmailOtpBox.focus();
          resendOtpIn = 120;
          resendOtpInterval = setInterval(function (){
            if(resendOtpIn == 0){
              $('#get_email_otp_btn').removeClass('d-none');
              $('.email-resend-otp-count-down').addClass('d-none');
              clearInterval(resendOtpInterval);
            }else{
              resendOtpIn--;
              $('#email_otp_count_down').text(resendOtpIn);
            }
          }, 1000);
          $('.email-resend-otp-count-down').removeClass('d-none');
        },
        error: function(error){
          let errors = error.responseJSON.errors;
          if(errors){
            for(var key in errors){
              toastr.error(errors[key][0]);
            }
          }
          console.log(error)
        }
    });
  }

  function changeEmailAddress(){
    clearInterval(resendOtpInterval);
    $('#submit_email_otp_btn').addClass('d-none');
    $('.resend-email-otp-count-down').addClass('d-none');
    $('#get_email_otp_btn').removeClass('d-none');
    $('#email-otp-div').addClass('d-none');
    $('#email').prop('disabled', false);
    clearOtpDigitBoxes('.email-otp-digit');
  }

  function userVerifyEmailOtp (){
    const otpValue = getOtpDigitValue('.email-otp-digit');
    if(otpValue.length !== 6){
      toastr.error('Please enter 6 digit OTP.');
      return;
    }
    $.ajax({
        type: "POST",
        url: @json(route('user.verifyotp')),
        data:{
          otp: otpValue,
        },
        headers: {
            'X-CSRF-TOKEN': @json(csrf_token())
        },
        success: function(response) {
          toastr.success(response.message);
          setTimeout(function(){
            window.location.href = response.redirectTo;
          }, 2000);
        },
        error: function(error){
          let errors = error.responseJSON.errors;
          if(errors){
            for(var key in errors){
              toastr.error(errors[key][0]);
            }
          }
          console.log(error)
        }
    });
  }

  var typingTimer;
  function birthplaces(inputId, suggestionId){
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function(){
      var inputVal = $(inputId).val(); // Get the value of the input field
      if (inputVal.length > 2) { // Make sure input is at least 3 characters
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
            console.error(error);
          }
        });
      } else {
        $(suggestionId).empty(); // Clear suggestions if input is too short
      }
    }); // Delay for 2 seconds (2000 milliseconds)
  }
</script>
  <script type="text/javascript">
    function handleChooseImage(fileInputId){
      let value = $(`#${fileInputId}`).click();
    }
    function showTempProfileImage(file, src) {
      var fileInput = document.getElementById(file);
      var file = fileInput.files[0];
      if(file){
        var reader = new FileReader();
        reader.onload = function(e){
          var imageElement = document.getElementById(src);
          imageElement.src = e.target.result;
        }
        reader.readAsDataURL(file);
      }
    }

    function validateAndSubmit(that){
      let _form = $(that);
      var formData = new FormData(that);
      _form.find("button[type=submit]").prop("disabled", true).text("Submitting");
      $.ajax({
          url: "{{ route('user.profile') }}",
          enctype: 'multipart/form-data',
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
          _form.find("button[type=submit]").prop("disabled", true).text("Submitted");
            toastr.success(response.message);
            setTimeout(function(){
              window.location.href = response.redirectTo;
            }, 2000);
          },
          error: function(xhr){
            let errors = xhr.responseJSON.errors;
            if(errors){
              for (var key in errors) {
                toastr.error(errors[key][0]);
              }
            }
            _form.find("button[type=submit]").prop("disabled", false).text("Save");
            console.log(xhr)
          }
      });
      return false;
    }

    function fetchLocations(table, that, elementId){
      let url = "{{ route('location') }}";
      let relationTableId = $(that).val();
      url += `/?table=${table}&relation_table_id=${relationTableId}`;
      let data = `<option value="">Select ${table}</option>`;
      $.ajax({
        type: "GET",
        url: url,
        success: function(response){
          for(let item in response){
            data += `<option value="${item}">${response[item]}</option>`
          }
          $('#'+elementId).html('');
          $('#'+elementId).append(data);
        },
        error: function(error){
          $('#'+elementId).html('');
          $('#'+elementId).append(data);
          console.log("fetch states by country:", error);
        }
      });
    }
  </script>
  <script>
  document.addEventListener("keydown", function (e) {
      if (e.key !== "Enter") return;

      e.preventDefault();

      // Active tab find
      let activeTab = document.querySelector(".tab-pane.active");
      if (!activeTab) return;

      // ACTIVE FORM FIND KARO
      let form = activeTab.querySelector("form");
      if (!form) return;

      // URL se tab identify karo
      let urlParams = new URLSearchParams(window.location.search);
      let tab = urlParams.get('tab') || 'my-profile';


      // 🔹 1. Profile Form → Normal submit
      if (tab === "my-profile") {
          if (form.checkValidity()) {
              form.requestSubmit();
          } else {
              form.reportValidity();
          }
          return;
      }

      // 🔹 2. Change Phone Number Form → getOtp() call
      if (tab === "change-phone-number") {
          let otpBtn = form.querySelector("#get_otp_btn"); // button ka id
          if (otpBtn) {
              otpBtn.click();  // button ka click → getOtp() function call
          }
          return;
      }

  });
  </script>
@endpush
