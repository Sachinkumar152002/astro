@extends(themeView() . 'layout')
@section('title','Feedback - ASTROVYRA')

@php
  $user=auth()->guard("web")->user();
@endphp

@section('content')
<div class="container d-flex align-items-center justify-content-center pad-ap2">
  <div class="feedback-box mt-5">
    <h5 class="fw-bold mb-1 text-center">Feedback to the Admin!</h5>
    <p class="text-muted small mb-3 text-center">Share your feedback to help us.</p>
    <form class="feedback" id="feedbackForm">
      @csrf
      <input type="hidden" name="type" value="feedback">
      <div id="form-div" class="">
        <div class="form-group mb-3 {{ $user?'d-none':'' }}">
          <label for="name" class="form-label f4 fs-14">Full Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control fs-7" id="name" placeholder="Enter Full Name" name="name" value="{{ $user?$user->name:"" }}">
        </div>
        <div class="form-group mb-3 {{ $user?'d-none':'' }}">
          <label for="email" class="form-label f4 fs-14">Email Address <span class="text-danger">*</span></label>
          <input type="email" id="email"  class="form-control fs-7" placeholder="Enter Email Address" name="email" value="{{ $user?$user->email:"" }}">
        </div>
        <div class="form-group mb-3 w-100 {{ $user?'d-none':'' }}">
          <label for="mobile" class="form-label f4 fs-14">Mobile Number <span class="text-danger">*</span></label>
          <div class="d-flex align-items-center border radius bg-white">
            <div class="">
              <select class="form-control border-0" name="phone_code_feedback" id="phone_code_feedback">
                @foreach($countries as $key => $item)
                  <option value="{{$item->phonecode}}">+{{ $item->phonecode }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-7 p-0 m-0">
              <input type="text" class="form-control fs-7 border-0 ps-0" placeholder="Enter Mobile Number" id="mobile_number" name="mobile_number" value="{{ $user?$user->phone:"" }}">
            </div>
          </div>
        </div>
        <div class="form-group mb-3">
          <label for="message" class="form-label f4 fs-14">Message <span class="text-danger">*</span></label>
          <textarea class="form-control feedback-textarea" name="message" id="message" placeholder="Start typing here.."></textarea>
        </div>
      </div>
      <button class="btn main_button mt-3 w-100 fw-bold" type="submit">Send Feedback</button>
      {{--<div class="otp-form-div px-3 d-none">
        <div>
          <span>
            Please check, OTP sent on <span class="otp-sent-on text-black fw-bold"></span>
          </span>
        </div>
        <div class="form-group mb-2 d-none">
          <label for="otp-feedback" class="fw-semibold mb-1">OTP <span class="text-danger">*</span></label>
          <input type="number" id="otp-feedback" class="otp-input form-control fs-7" placeholder="Enter OTP" name="otp">
        </div>
        <div class="otp-count-down-div d-none">
          <span>
            Resend OTP in <span id="otp-count-down-feedback" class="otp-count-down text-danger fs-8 fw-bold">120</span>
          </span>
        </div>
      </div>
      @if($user)
        <button class="btn main_button mt-3 w-100 fw-bold" type="submit">Send Feedback</button>
      @else
        <div class="my-2 text-center">
          <button type="button" class="get-otp-for-verify-btn btn main_button w-40 my-3 py-2 px-5 fs-17 f6" onclick="getOTPForVerify()">Get Otp</button>
          <button class="submit-otp-for-verify-btn btn main_button mt-3 w-100 fw-bold d-none" type="button" id="submit-otp-btn-feedback" onclick="submitOTPForFeedback()">Send Feedback</button>
        </div>
      @endif--}}
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
  $(document).ready(function(){
    $('.feedback').on('submit', function(e){
      e.preventDefault();
      let form = $(this);
      let data = form.serialize();
      let button = form.find('button[type=submit]');
      button.addClass('disabled').prop('disabled', true).text('Submitting...');
      $.ajax({
        url: "{{ route('feedback-submit') }}",
        method: "POST",
        data: data,
        success:function(response){
          toastr.success('Feedback Submitted Successfully');
          setTimeout(function(){
            window.location.reload();
          }, 1500);
          // button.removeClass('disabled').prop('disabled', false).text('Send Feedback');
          // form[0].reset();
        },
        error:function(xhr){
          button.removeClass('disabled').prop('disabled', false).text('Send Feedback');
          if(xhr.status === 422){
            let errors = xhr.responseJSON.errors;
            $.each(errors, function(key, value){
              toastr.error(value[0]); // हर validation error message show होगा
            });
          } else {
            toastr.error('Something went wrong! Please try again.');
          }
        }
      });
    });
  });
</script>
<script>
document.addEventListener("keydown", function(e){
    if(e.key === "Enter"){
        let form = document.querySelector("#feedbackForm");
        let active = document.activeElement;
            form.requestSubmit();
    }
});
</script>
@endpush
