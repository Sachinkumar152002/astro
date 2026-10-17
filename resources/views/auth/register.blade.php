@extends(themeView().'layout')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/auth-register-ui.css') }}">
@endpush

@section('title', "Registration | ".config("app.name"))

@section('content')
  @if($verifyUser)
    <section class="mb-5 mt-5">
      <div class="container container_register mt-4 pad-p2">
        <div class="row">
          <div class="login-boxx" id="guest-login-page">
            <div class="container sticky-sidebar-2 p-0">
              <div class="col-lg-12 signup-form">
                <div class="card radius8 pb-4">
                  <h4 class="h5 py-3 text-center bg-p fs-24 f6">Welcome to ASTROVYRA</h4>

                  <div class="p-3">
                    <div class="p-3 radius mb-0 w-100">

                    </div>
                    <div class="form-group mb-3">
                      <label for="name" class="form-label f4 fs-14">Full Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control fs-7" id="name" placeholder="Enter Full Name" name="name" onkeypress="handleEnter(this,event,'get-otp-btn')">
                    </div>
                    <div class="form-group mb-3">
                      <label for="email" class="form-label f4 fs-14">Email Address <span class="text-danger">*</span></label>
                      <input type="email" id="email"  class="form-control fs-7" placeholder="Enter Email Address" name="email" onkeypress="handleEnter(this,event,'get-otp-btn')">
                    </div>
                    <div class="form-group w-100">
                      <label for="mobile" class="form-label fw-semibold fs-14">Mobile Number <span class="text-danger">*</span></label>
                      <div class="d-flex align-items-center border radius bg-white">
                        <div class="">
                          <select class="form-control border-0" name="phone_code" id="phone_code">
                            @foreach($countries as $key => $item)
                              <option value="{{$item->phonecode}}">+{{ $item->phonecode }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-7 p-0 m-0">
                          <input type="text" class="form-control fs-7 border-0 ps-0" placeholder="Enter Mobile Number" id="phone" name="phone" value="{{ $phone }}" readonly  maxlength="10" minlength="10" pattern="[0-9]+" onkeyup="this.value=this.value.replace(/[a-zA-Z]/g, '')" onkeypress="handleEnter(this,event,'get-otp-btn')">
                        </div>
                      </div>
                    </div>
                    <div class="form-group mb-3">
                      <label for="referral_code" class="form-label f4 fs-14">Referral Code <span class="text-muted">(Optional)</span></label>
                      <input
                        type="text"
                        class="form-control fs-7"
                        id="referral_code"
                        placeholder="Enter Referral Code"
                        name="referral_code"
                        value="{{ old('referral_code', request('referral_code') ?: request('ref') ?: session('referral_code')) }}"
                        onkeypress="handleEnter(this,event,'get-otp-btn')"
                      >
                      <small class="text-muted">If you have a referral code, enter it here</small>
                    </div>
                    <div class="form-group mb-3 d-none" id="otp-div">
                      <label for="otp" class="fw-semibold mb-1">OTP <span class="text-danger">*</span></label>
                      <input type="number" id="otp"  class="form-control fs-7" placeholder="Enter OTP" name="otp">
                    </div>
                    <div class="d-none" id="resend-otp-count-down">
                      <span>Resend otp in <span id="otp-count-down" class="text-danger fs-8">120</span> or <span class="text-gold pointer" onclick="changeMobileNumber()">Change Phone Number</span>
                      </span>
                    </div>
                    <div class="my-2 text-center">
                      <button type="button" class="main_button w-40 my-3 py-2 px-5 fs-17 f6" id="get-otp-btn" onclick="getOtp()">Sign Up</button>
                      <button type="button" class="main_button w-40 my-3 py-2 px-5 fs-17 f6 d-none" id="submit-otp-btn" onclick="getOtp()">Get Otp</button>
                    </div>
                    <div class="d-flex align-items-center justify-content-between px-2">
                      <a class="text-primary-n fw-semibold" href="{{route('home')}}">Go back to home</a>
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-gold">Sign In</a></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @else
    <div class="container mt-5 mt-lg-0 py-5">
      <div class="mx-600">
        <div class="row px-3 px-md-0 border-n radius-8">
          <!-- ================= LEFT DESIGN PANEL ================= -->
          <div class="col-md-6  d-md-flex login-left ">
            <div class="login-left-inner text-center">

              <h4>Have Question about</h4>
              <h3>Marriage, Career or Relationship?</h3>

              <img src="/assets/img/login-banner-4.png" class="guru-img" alt="Astrologer">

              <p class="fw-bold">Talk to India’s best Astrologers</p>

              <div class="store-btns">
                <img src="/assets/img/google-1.svg">
                <img src="/assets/img/google-2.svg">
              </div>

            </div>
          </div>
         <div class="col-md-6 bg-white ms-0 ps-0 radius">
           <div class="">
             <div class="">
               <!-- <h4 class="h5 py-3 text-center bg-p fs-24 f6">Welcome Customer</h4> -->
               <div class="" style="margin:auto">
                 <div class="p-3 radius mb-0 w-100">
                   <h5 class="text-center mt-3">User Registration</h5>
                 </div>
                 <div class="login-boxx">
                   <div class="form-group px-3" id="phone-div">
                     <label class="form-label fw-semibold fs-14">Mobile Number <span class="text-danger">*</span></label>
                     <div class="d-flex align-items-center border radius bg-white">
                       <div class="">
                         <select class="form-control border-0" name="phone_code" id="phone_code">
                           @foreach($countries as $key => $item)
                           <option value="{{$item->phonecode}}">+{{ $item->phonecode }}</option>
                           @endforeach
                         </select>
                       </div>
                       <div class="col-7 p-0 m-0">
                         <input type="text" class="form-control fs-7 border-0 ps-0" placeholder="Enter Mobile Number" id="phone" name="phone" onkeypress="handleEnter(this,event,'get-otp-btn')">
                       </div>
                     </div>
                   </div>
                 </div>
                 <div class="px-3">
                   <div class="d-none" id="otp-phone-number-div">
                     <span>
                       Please check, OTP sent on <span id="otp-phone-number" class="text-black fw-bold"></span>
                     </span>
                   </div>
                   <div class="form-group mb-2 d-none" id="otp-div">
                     <label for="otp" class="fw-semibold mb-1">OTP <span class="text-danger">*</span></label>
                     <input type="number" id="otp"  class="form-control fs-7" placeholder="Enter OTP" name="otp" onkeypress="handleEnter(this,event,'submit-otp-btn')">
                   </div>
                   <div class="d-none" id="resend-otp-count-down">
                     <span>
                       Resend OTP in <span id="otp-count-down" class="text-danger fs-8 fw-bold">120</span>
                     </span>
                   </div>
                 </div>
                 <div class="my-2 text-center">
                   <button type="button" class="main_button w-40 my-3 py-2 px-5 fs-17 f6" id="get-otp-btn" onclick="getOTP()">Get Otp</button>
                   <button type="button" class="main_button w-40 mt-3 py-2 fs-6 d-none" id="submit-otp-btn" onclick="verifyOTP()">Sign Up</button>
                 </div>
                 <div class="text-center">

                   <p class="fw-semibold">Already have an account?  <a href="{{ route('login') }}" class="text-primary-n fw-semibold">Sign In</a></p>
                    <p class="mt-3 fs-14"><a class="text-primary-n fw-semibold" href="{{route('home')}}">Go back to home</a></p>
                 </div>
               </div>
             </div>
           </div>
         </div>
        </div>
      </div>
    </div>
  @endif
@endsection

@push('scripts')
  @if($verifyUser)
    <script>
      var resendOtpInterval;
      function getOtp(){
        $("#get-otp-btn").prop("disabled", true).text("Submitting");
        $.ajax({
            type: "POST",
            url: @json(route('user.register')),
            data:{
              name:$('#name').val(),
              email:$('#email').val(),
              phone_code:$('#phone_code').val(),
              phone:$('#phone').val(),
              referral_code:$('#referral_code').val(),
            },
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(response) {
              toastr.success(response.message);
              $("#get-otp-btn").prop("disabled", true).text("Submitted");
              // $('#phone').prop('disabled', true);
              // $('#get-otp-btn').addClass('d-none');
              // $('#otp-div').removeClass('d-none');
              // $('#submit-otp-btn').removeClass('d-none');
              // resendOtpIn = 120;
              // resendOtpInterval = setInterval(function (){
              //   if(resendOtpIn == 0){
              //     $('#get-otp-btn').removeClass('d-none');
              //     $('#resend-otp-count-down').addClass('d-none');
              //     clearInterval(resendOtpInterval);
              //   }else{
              //     resendOtpIn--;
              //     $('#otp-count-down').text(resendOtpIn);
              //   }
              // }, 1000);
              // $('#resend-otp-count-down').removeClass('d-none');
              let redirectTo = response.redirectTo;
              if(redirectTo){
                setTimeout(function(){
                  window.location.href = redirectTo;
                }, 2000);
              }
            },
            error: function(error){
              $("#get-otp-btn").prop("disabled", false).text("Submit");
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
    </script>
  @else
    <script>
      var resendOtpInterval;
      function getOTP(){
        $("#get-otp-btn").prop("disabled", true);
        let phone = $('#phone').val();
        $.ajax({
            type: "POST",
            url: @json(route('verifyusergetphoneotp')),
            data:{
              page:"user",
              name:$('#name').val(),
              email:$('#email').val(),
              phone_code:$('#phone_code').val(),
              phone:phone,
            },
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(response) {
              toastr.success(response.message);
              $('#phone-div').addClass('d-none');
              $('#get-otp-btn').addClass('d-none');
              $('#otp-phone-number-div').removeClass('d-none');
              $('#otp-phone-number').text(phone);
              $('#otp-div').removeClass('d-none');
              $('#submit-otp-btn').removeClass('d-none');
              resendOtpIn = 120;
              resendOtpInterval = setInterval(function (){
                if(resendOtpIn == 0){
                  $("#get-otp-btn").prop("disabled", false).removeClass('d-none');
                  $('#resend-otp-count-down').addClass('d-none');
                  clearInterval(resendOtpInterval);
                }else{
                  resendOtpIn--;
                  $('#otp-count-down').text(resendOtpIn);
                }
              }, 1000);
              $('#resend-otp-count-down').removeClass('d-none');
            },
            error: function(error){
              $("#get-otp-btn").prop("disabled", false);
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

      function verifyOTP(){
        $.ajax({
            type: "POST",
            url: @json(route('verifyuserphoneotp')),
            data:{
              page:"user",
              phone:$('#phone').val(),
              otp:$('#otp').val(),
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
    </script>
  @endif
@endpush
