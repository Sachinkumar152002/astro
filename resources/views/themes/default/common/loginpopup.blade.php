<div class="modal fade" id="userLoginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg mt-5 user_log">
    <div class="modal-content radius-8 overflow-hidden">

      <div class="row g-0 border-n">

        <!-- ================= LEFT DESIGN PANEL ================= -->
        <div class="col-md-6 d-md-none d-lg-block d-md-flex login-left">
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

        <!-- ================= RIGHT LOGIN PANEL ================= -->
        <div class="col-md-12 col-lg-6 bg-white">


          <div class="modal-body">
          <button type="button"
              class="btn-close position-absolute end-0 top-0 m-3"
              onclick="handleUserLoginModal('close')"></button>
            <div class="p-3">
              <h5 class="mb-3 text-center">
              User Login
            </h5>
              @php
                $countries = countries(101);
              @endphp

              <div class="form-group mb-3" id="phone-div">
                <label class=" form-label fw-semibold fs-14">
                  Enter Your Mobile Number <span class="text-danger">*</span>
                </label>

                <div class="d-flex align-items-center border radius bg-white">
                  <select class="form-control border-0 w-25" name="phone_code" id="phone_code">
                    @foreach($countries as $item)
                      <option value="{{$item->phonecode}}">
                        +{{ $item->phonecode }}
                      </option>
                    @endforeach
                  </select>

                  <input type="text"
                    class="form-control border-0"
                    placeholder="Enter Mobile Number"
                    id="phone"
                    name="phone"
                    onkeypress="handleEnter(this,event,'get-otp-btn')">
                </div>
              </div>

              <p class="text-center fw-semibold fs-14">
                You will receive a 6 digit code for verification
              </p>

              <div class="d-none text-center mb-2" id="otp-phone-number-div">
                Please check, OTP sent on
                <span id="otp-phone-number" class="fw-bold"></span>
              </div>

              <div class="form-group d-none mb-2" id="otp-div">
                <div class="d-flex justify-content-center align-items-center otp-boxes-wrap">
                  <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                  <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                  <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                  <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                  <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                  <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                </div>
              </div>

              <div class="d-none text-center" id="resend-otp-count-down">
                Resend OTP in
                <span id="otp-count-down" class="text-danger fw-bold">120</span>
              </div>

              <div class="text-center my-3">
                <button type="button"
                  class="main_button px-5 py-2"
                  id="get-otp-btn"
                  onclick="getOTP()">Get OTP</button>

                <button type="button"
                  class="main_button px-5 py-2 d-none"
                  id="submit-otp-btn"
                  onclick="verifyOTP()">Sign In</button>
              </div>

              <div class="text-center fw-semibold">
                <span>Don't have an account? </span>
                <a href="{{ route('user.register') }}" class="text-primary-n">
                Sign Up
                </a>
              </div>

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>


@push('scripts')
<style>
  #userLoginModal .user_log { max-width: 860px; margin-top: 2rem; }
  #userLoginModal .modal-content { border: 0; border-radius: 20px !important; box-shadow: 0 24px 80px rgba(29, 19, 55, .26); }
  #userLoginModal .modal-content > .row { min-height: 430px; }
  #userLoginModal .login-left { position: relative; overflow: hidden; border-radius: 0; padding: 30px 26px; background: radial-gradient(circle at 50% 43%, rgba(255,255,255,.8), transparent 33%), linear-gradient(145deg,#fff2df 0%,#ffdfbf 100%); color: #251942; }
  #userLoginModal .login-left:after { position:absolute; right:-70px; bottom:-110px; width:230px; height:230px; border:1px solid rgba(111,70,143,.15); border-radius:50%; content:""; }
  #userLoginModal .login-left-inner { position:relative; z-index:1; max-width:350px; }
  #userLoginModal .login-left-inner h4 { margin-bottom:8px; color:#4e3574; font-size:17px; }
  #userLoginModal .login-left-inner h3 { margin-bottom:12px; color:#1e1b42; font-family:Georgia,serif; font-size:23px; line-height:1.25; }
  #userLoginModal .guru-img { width:190px; max-width:70%; height:190px; object-fit:contain; filter:drop-shadow(0 10px 14px rgba(92,52,14,.14)); }
  #userLoginModal .login-left-inner p { margin:2px 0 8px; color:#34244f; font-size:14px; }
  #userLoginModal .store-btns img { height:36px; margin:8px 4px 0; }
  #userLoginModal .modal-body { position:relative; display:flex; min-height:430px; align-items:center; justify-content:center; padding:38px 34px; background:linear-gradient(145deg,#fff 0%,#fcfaff 100%); }
  #userLoginModal .modal-body > .p-3 { width:100%; max-width:380px; padding:0 !important; }
  #userLoginModal .modal-body h5 { margin-bottom:28px !important; color:#201a41; font-size:23px; font-weight:700; }
  #userLoginModal .modal-body .form-label { margin-bottom:8px; color:#292344; font-size:14px; }
  #userLoginModal #phone-div .d-flex { min-height:48px; overflow:hidden; border:1px solid #e4deec !important; border-radius:10px !important; box-shadow:0 2px 7px rgba(41,27,69,.03); }
  #userLoginModal #phone-div .d-flex:focus-within { border-color:#936bb4 !important; box-shadow:0 0 0 3px rgba(113,68,151,.1); }
  #userLoginModal #phone_code,#userLoginModal #phone { height:46px; border:0 !important; background:transparent; color:#2d2545; font-size:14px; box-shadow:none !important; }
  #userLoginModal #phone_code { max-width:82px; padding-left:12px; }
  #userLoginModal #phone::placeholder { color:#9994a2; }
  #userLoginModal .modal-body p { margin:17px 0; color:#625a71; font-size:13px; }
  #userLoginModal .main_button { min-width:150px; min-height:42px; border:0; border-radius:24px; background:linear-gradient(110deg,#f6a20b,#eb8300) !important; color:#fff !important; box-shadow:0 7px 16px rgba(231,136,0,.22); font-size:14px; font-weight:700; transition:transform .18s ease,box-shadow .18s ease; }
  #userLoginModal .main_button:hover { transform:translateY(-1px); box-shadow:0 10px 20px rgba(231,136,0,.28); }
  #userLoginModal .modal-body .fw-semibold { color:#37314b; }
  #userLoginModal .modal-body a.text-primary-n { color:#633a91 !important; font-weight:700; text-decoration:none; }
  #userLoginModal .btn-close { z-index:3; padding:8px; border-radius:50%; background-color:rgba(255,255,255,.8); opacity:.65; }
  #userLoginModal .otp-digit-box { border:1px solid #ded4ea; border-radius:12px !important; background:#fff; color:#21183e; }
  #userLoginModal .otp-digit-box:focus { border-color:#936bb4; box-shadow:0 0 0 3px rgba(113,68,151,.1); }
  @media (max-width:767px) {
    #userLoginModal .user_log { margin:1rem auto; padding:0 10px; }
    #userLoginModal .modal-content > .row { min-height:0; }
    #userLoginModal .modal-body { min-height:410px; padding:36px 22px; }
    #userLoginModal .modal-body h5 { margin-bottom:24px !important; }
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
<script>
  var resendOtpInterval;
  function bindLoginOtpBoxes(){
    const otpBoxes = document.querySelectorAll('#userLoginModal .otp-digit-box');
    if(!otpBoxes.length) return;
    otpBoxes.forEach(function(box, idx){
      box.addEventListener('input', function(){
        this.value = (this.value || '').replace(/\D/g, '').slice(0, 1);
        if(this.value && idx < otpBoxes.length - 1) otpBoxes[idx + 1].focus();
      });
      box.addEventListener('keydown', function(e){
        if(e.key === 'Backspace' && !this.value && idx > 0) otpBoxes[idx - 1].focus();
        if(e.key === 'Enter'){ e.preventDefault(); verifyOTP(); }
      });
      box.addEventListener('paste', function(e){
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
        if(!pasted) return;
        e.preventDefault();
        pasted.split('').forEach(function(ch, i){ if(otpBoxes[i]) otpBoxes[i].value = ch; });
        otpBoxes[Math.min(pasted.length, otpBoxes.length - 1)].focus();
      });
    });
  }
  function clearLoginOtpBoxes(){
    const otpBoxes = document.querySelectorAll('#userLoginModal .otp-digit-box');
    otpBoxes.forEach(function(box){ box.value = ''; });
  }
  function getLoginOtpValue(){
    const otpBoxes = document.querySelectorAll('#userLoginModal .otp-digit-box');
    let otp = '';
    otpBoxes.forEach(function(box){ otp += (box.value || '').trim(); });
    return otp;
  }
  bindLoginOtpBoxes();

  function getOTP(){
    $("#get-otp-btn").prop("disabled", true);
    let phone = $('#phone').val();
    $.ajax({
        type: "POST",
        url: @json(route('login')),
        data:{
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
          clearLoginOtpBoxes();
          const firstOtpBox = document.querySelector('#userLoginModal .otp-digit-box');
          if(firstOtpBox) firstOtpBox.focus();
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
    const otpValue = getLoginOtpValue();
    if(otpValue.length !== 6){
      toastr.error('Please enter 6 digit OTP.');
      return;
    }
    $.ajax({
        type: "POST",
        url: @json(route('verifyOTP')),
        data:{
          phone:$('#phone').val(),
          otp: otpValue,
        },
        headers: {
            'X-CSRF-TOKEN': @json(csrf_token())
        },
        success: function(response) {
          toastr.success(response.message);
          setTimeout(function(){
            // window.location.href = response.redirectTo;
            window.location.reload();
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
@endpush
