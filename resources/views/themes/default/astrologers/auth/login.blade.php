@extends(themeView().'layout')

@section('title', "Astrologer Sign In | ".config("app.name"))

@push('style')
<style>
body.astrologer-login-route { background:#fffaf4!important; }
body.astrologer-login-route>section.astrologer-login-page { min-height:calc(100vh - 84px);display:flex;align-items:center;padding:34px 0 48px!important;margin:0!important;background:radial-gradient(circle at 8% 10%,rgba(245,166,48,.12),transparent 28%),linear-gradient(135deg,#fffaf4,#f7f1fb); }
body.astrologer-login-route .container_register { width:100%;max-width:100%;margin:0!important; }
body.astrologer-login-route .mx-600 { max-width:930px;margin:0 auto;border:1px solid #eee3d8;border-radius:22px;background:#fff;box-shadow:0 18px 48px rgba(49,27,67,.12);overflow:hidden; }
body.astrologer-login-route .mx-600>.row { margin:0!important;border:0!important;border-radius:22px; }
body.astrologer-login-route .login-left { min-height:500px;border-radius:0;background:radial-gradient(circle at 50% 34%,rgba(255,255,255,.5),transparent 31%),linear-gradient(150deg,#ffe2bd,#fff2df 76%); }
body.astrologer-login-route .login-left-inner { max-width:360px; }
body.astrologer-login-route .login-left-inner h4 { color:#56366f;font-size:16px; }
body.astrologer-login-route .login-left-inner h3 { margin:8px 0 14px;color:#21183e;font-family:Georgia,serif;font-size:25px;line-height:1.25; }
body.astrologer-login-route .guru-img { display:block;width:190px;height:190px;object-fit:contain;margin:15px auto 12px; }
body.astrologer-login-route .login-left-inner p { color:#38294c;font-size:14px; }
body.astrologer-login-route .store-btns img { max-width:140px;height:40px;object-fit:contain; }
body.astrologer-login-route .astro-login-form-panel { display:flex;align-items:center;padding:38px 34px!important;background:#fff; }
body.astrologer-login-route #guest-login-page,body.astrologer-login-route #guest-login-page>.col-lg-12 { width:100%; }
body.astrologer-login-route #guest-login-page .p-3 { padding:0!important; }
body.astrologer-login-route #guest-login-page h5 { margin:0 0 26px;color:#21183e!important;font-family:Georgia,serif;font-size:26px;font-weight:700; }
body.astrologer-login-route #guest-login-page label { margin-bottom:8px;color:#342747;font-size:14px; }
body.astrologer-login-route #phone-div>.d-flex { min-height:48px;border:1px solid #e5dce9!important;border-radius:10px;background:#fff;overflow:hidden; }
body.astrologer-login-route #phone_code { width:78px;background:#fff;font-size:14px; }
body.astrologer-login-route #phone { height:46px;padding-left:8px!important;font-size:14px; }
body.astrologer-login-route #guest-login-page .form-control:focus { border-color:#9d75b8;box-shadow:0 0 0 3px rgba(91,39,126,.1); }
body.astrologer-login-route #get-otp-btn,body.astrologer-login-route #submit-otp-btn { min-width:160px;width:auto!important;height:44px;margin:15px 0!important;padding:0 25px!important;border:0;border-radius:10px;background:linear-gradient(120deg,#f6a20a,#e88700);color:#fff;font-size:15px;font-weight:700;box-shadow:0 7px 15px rgba(236,145,0,.2); }
body.astrologer-login-route #get-otp-btn:hover,body.astrologer-login-route #submit-otp-btn:hover { filter:brightness(1.04); }
body.astrologer-login-route .otp-digit-box { width:42px!important;height:46px!important;max-width:42px;border:1px solid #e4d9eb;border-radius:10px!important;line-height:46px; }
body.astrologer-login-route #guest-login-page .text-primary-n { color:#613381!important; }
body.astrologer-login-route #guest-login-page p { color:#474052; }
body.astrologer-login-route .astrologer-login-skeleton { position:fixed;inset:0;z-index:20000;display:grid;place-items:center;padding:20px;background:#fffaf5;transition:opacity .3s ease; }
body.astrologer-login-route .astrologer-login-skeleton.is-dismissing { opacity:0;pointer-events:none; }
.astrologer-login-skeleton__card { display:grid;grid-template-columns:1fr 1fr;width:min(900px,100%);min-height:430px;overflow:hidden;border:1px solid #eee4da;border-radius:20px;background:#fff;box-shadow:0 14px 36px rgba(48,25,70,.08); }
.astrologer-login-skeleton__card aside,.astrologer-login-skeleton__card>div { display:flex;flex-direction:column;justify-content:center;gap:15px;padding:34px; }
.astrologer-login-skeleton__card aside { align-items:center;background:#fff0dd; }
.astrologer-login-skeleton__card i,.astrologer-login-skeleton__card b,.astrologer-login-skeleton__card span,.astrologer-login-skeleton__card em { display:block;border-radius:8px;background:linear-gradient(100deg,#eee8e2 20%,#faf5ef 40%,#eee8e2 60%);background-size:220% 100%;animation:astrologer-login-shimmer 1.35s linear infinite; }
.astrologer-login-skeleton__card aside i { width:150px;height:150px;border-radius:50%; }.astrologer-login-skeleton__card aside b { width:80%;height:24px; }.astrologer-login-skeleton__card aside span { width:70%;height:12px; }.astrologer-login-skeleton__card>div b { width:55%;height:28px;margin:0 auto 15px; }.astrologer-login-skeleton__card>div i { height:48px; }.astrologer-login-skeleton__card>div em { width:48%;height:44px;margin:6px auto; }
@keyframes astrologer-login-shimmer { to { background-position-x:-220%; } }
@media(max-width:767px) {
 body.astrologer-login-route>section.astrologer-login-page { min-height:calc(100vh - 70px);padding:20px 0 32px!important; }
 body.astrologer-login-route .mx-600 { margin:0 12px; }
 body.astrologer-login-route .login-left { display:flex!important;min-height:0;padding:20px 16px; }
 body.astrologer-login-route .login-left-inner h4 { margin:0;font-size:13px; }
 body.astrologer-login-route .login-left-inner h3 { margin:5px 0;font-size:19px; }
 body.astrologer-login-route .guru-img { width:95px;height:95px;margin:5px auto; }
 body.astrologer-login-route .login-left-inner p { margin:0;font-size:12px; }
 body.astrologer-login-route .store-btns img { height:28px;max-width:112px;margin:6px 3px 0; }
 body.astrologer-login-route .astro-login-form-panel { padding:24px 19px!important; }
 body.astrologer-login-route #guest-login-page h5 { margin-bottom:18px;font-size:22px; }
 body.astrologer-login-route .otp-boxes-wrap { gap:5px!important; }
 body.astrologer-login-route .otp-digit-box { flex:1;width:auto!important;max-width:none;height:42px!important;line-height:42px; }
 .astrologer-login-skeleton__card { grid-template-columns:1fr;min-height:0; }
 .astrologer-login-skeleton__card aside { display:none; }
 .astrologer-login-skeleton__card>div { padding:28px 20px; }
}
</style>
@endpush

@section('content')
<section class="astrologer-login-page">
  <div class="container container_register mt-4">
    <div class="mx-600">
      <div class="row mx-3 mx-md-0 border-n radius-8">
        <!-- ================= LEFT DESIGN PANEL ================= -->
        <div class="col-md-6  d-md-flex login-left">
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
      <div class="col-md-6 bg-white ms-0 ps-0 radius-8 astro-login-form-panel">
        <div class="" id="guest-login-page">
          <div class="">
            <div class="col-lg-12 signup-form border-0">
              <div class="">

                <div class="p-3">
                  <div class="p-3 ps-0 radius mb-0 w-100 pb-1">
                    <h5 class="text-black text-center">Astrologer Login</h5>
                  </div>
                  <div class="form-group w-100" id="phone-div">
                    <label class="form-label fw-semibold fs-14">Enter Your Mobile Number<span class="text-danger">*</span></label>
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
                  <div class="d-none" id="otp-phone-number-div">
                    <span>
                      Please check, OTP sent on <span id="otp-phone-number" class="text-black fw-bold"></span>
                    </span>
                  </div>
                  <div class="form-group mb-2 d-none" id="otp-div">
                    <label class="fw-semibold mb-1">OTP <span class="text-danger">*</span></label>
                    <div class="d-flex align-items-center gap-2 otp-boxes-wrap">
                      <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                      <input type="text" class="form-control text-center otp-digit-box" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                    </div>
                  </div>
                  <div class="d-none" id="resend-otp-count-down">
                    <span>
                      Resend OTP in <span id="otp-count-down" class="text-danger fs-8 fw-bold">120</span>
                    </span>
                  </div>
                  <div class="my-2 text-center">
                    <button type="button" class="main_button w-40 my-3 py-2 px-5 fs-17 f6" id="get-otp-btn" onclick="getOTP()">Get Otp</button>
                    <button type="button" class="main_button w-40 mt-3 py-2 fs-6 d-none" id="submit-otp-btn" onclick="verifyOTP()">Sign In</button>
                  </div>
                  <div class="text-center">
                    <p class="mb-0 fw-semibold">
                            Don't have an account?
                            <a href="{{ route('astrologer.register') }}" class="text-primary-n fw-semibold ms-1">
                              Sign Up
                            </a>
                          </p>
                          <p class="mt-3 fs-14"><a class="text-primary-n fw-semibold" href="{{route('home')}}">Go back to home</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<style>
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
  function bindAstrologerLoginOtpBoxes(){
    const otpBoxes = document.querySelectorAll('.otp-digit-box');
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
  function clearAstrologerLoginOtpBoxes(){
    const otpBoxes = document.querySelectorAll('.otp-digit-box');
    otpBoxes.forEach(function(box){ box.value = ''; });
  }
  function getAstrologerLoginOtpValue(){
    const otpBoxes = document.querySelectorAll('.otp-digit-box');
    let otp = '';
    otpBoxes.forEach(function(box){ otp += (box.value || '').trim(); });
    return otp;
  }
  bindAstrologerLoginOtpBoxes();

  function getOTP(){
    $("#get-otp-btn").prop("disabled", true);
    let phone = $('#phone').val();
    $.ajax({
        type: "POST",
        url: @json(route('astrologer.login')),
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
          clearAstrologerLoginOtpBoxes();
          const firstOtpBox = document.querySelector('.otp-digit-box');
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
    const otpValue = getAstrologerLoginOtpValue();
    if(otpValue.length !== 6){
      toastr.error('Please enter 6 digit OTP.');
      return;
    }
    $.ajax({
        type: "POST",
        url: @json(route('astrologer.verifyotp')),
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
@endpush
