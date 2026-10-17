@extends(themeView().'layout')

@push("style")
<style media="screen">
  .banner-second{
    margin-top: 15px;
    background-size: cover;
    background-repeat: no-repeat;
    width: 100%;
    height: 100%;
    background-position: center;
    border-radius: 12px;
  }
  @media(max-width:768px){
    .banner-second{
      margin-top: 60px;
    }
  }
</style>
<link rel="stylesheet" href="{{ asset('assets/lib/material-timepicker/mdtimepicker.css') }}">
@endpush

@section('title', 'Kundali | '.config("app.name"))

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"Kundali",
    "description":"Get your free online Kundali with accurate horoscope, birth chart, and planetary positions.",
    "inLanguage":"en-IN",
    "isPartOf":{
      "@id":"{{ url('/') }}/#website"
    },
    "about":{
      "@id":"{{ url('/') }}/#org"
    },
    "datePublished":"{{ date('Y-m-d') }}",
    "dateModified":"{{ date('Y-m-d') }}"
  }
  </script>
@endpush

@section('content')

  @php
    $mobileView = str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile') ? true : false;
  @endphp
<!-- Banner Section Start -->
<section class="container">
  @foreach($banners as $index => $banner)
    @if($banner)
      @php
        $bannerImage = $banner->image;
        $bannerMobileImage = $banner->mobileImage;
        $bannerImageUrl = "";
        if($mobileView && $bannerMobileImage){
          $bannerImageUrl = $bannerMobileImage->image();
        }elseif($bannerImage){
          $bannerImageUrl = $bannerImage->image(1250, 250);
        }
      @endphp
      <a href="{{ $banner->link ?: '' }}" class="">
        <img src="{{ $bannerImageUrl }}" alt="{{ $banner->alt }}" class="banner-second">
      </a>
    @endif
  @endforeach
</section>
<!-- Banner Section End -->


  <!-- form-sectio-start -->
  <section class="pb-5 py-5 pt-2 pt-md-5">
    <div class="container">

    <div class="row">
      <div class="col-md-8 col-12">
        <div class="kundli-banner-top">
          <img src="assets/img/kundli-top.png" alt="">

        </div>
        <div class="">
          <div class="kundli-form-box p-0">
            <div class="form-h form-hh">
              <h4 class="h5 py-3 text-center bg-p fs-24 f6">Enter Details</h4>
            </div>
            <div class="p-4">
              <form id="kundliForm" onsubmit="return validateAndSubmit(this)">

                <div class="row">
                 <div class="col-md-6 col-12">
                   <div class="mb-3">
                     <label class="form-label">Name <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" name="name" data-name="Name">
                   </div>
                 </div>
                  <div class="col-md-6 col-12">
                    <div class="mb-3">
                      <label class="form-label">Gender <span class="text-danger">*</span></label>
                      <select class="form-select" name="gender">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6 col-12">
                    <div class="mb-3">
                      <label class="form-label">Birth Date <span class="text-danger">*</span></label>
                      <input type="date" class="form-control" name="date">
                    </div>
                  </div>
                  <div class="col-md-6 col-12">
                    <div class="mb-3">
                      <label class="form-label">Birth Time <span class="text-danger">*</span></label>
                      <i class="bi bi-clock kuu_time_icon" onclick="handleTimePickerIcon('#birth_time')"></i>
                      <input type="text" class="form-control timepicker" id="birth_time" name="time">
                    </div>
                  </div>
                  <div class="col-md-6 col-12">
                    <div class="mb-3 bp-suggestion">
                      <label class="form-label">Birth Place <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="birth_place" id="birth_place" onkeyup="locationPlaces('#birth_place', '#place_suggestions')">
                      <div class="suggestion_birth">
                        <ul id="place_suggestions"></ul>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6 col-12">
                    <div class="mb-3">
                      <label class="form-label">Language <span class="text-danger">*</span></label>
                      <select class="form-select" name="lang">
                        <option value="">Select</option>
                        <option value="en">English</option>
                        <option value="hi">Hindi</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn main_button px-4 fw-bold py-3">Generate Kundli</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-12">
       <img src="assets/img/free-kundli-right-banner.jpg" alt="" class="free-kundli-right-banner w-100 radius-8">
      </div>
    </div>
    </div>
  </section>
  <!-- form-sectio-end -->


  <section class="ax-connect-wrap">
    <div class="ax-connect-card">
      <p class="ax-connect-text">
        Connect with an Astrologer
      </p>

      <div class="ax-connect-actions">
        <a href="{{ route('astrologers.chatwithastrologers') }}" class="astro-btn chat-btn">
          <i class="fa fa-commenting"></i>
          Chat with Astrologer
        </a>

        <a href="{{ route('astrologers.talktoastrologers') }}" class="astro-btn call-btn">
          <i class="fa fa-volume-control-phone"></i>
          Talk to Astrologer
        </a>
      </div>
    </div>
  </section>


<!-- Daily Horoscopes-section-start -->
<section class="pb-3 pb-md-5 pb-lg-5 pt-5 d-none">
  <div class="container">
    <h2 class="text-center">Daily Horoscopes</h2>
    <p class="text-center">Daily horoscopes are like a guiding light in the vast universe of possibilities.</p>
    <div class="row mt-4 mt-lg-3">
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Aries','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-1.png')}}" alt="">
            <h6>Aries</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Taurus','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-2.png')}}" alt="">
            <h6>Taurus</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Gemini','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-3.png')}}" alt="">
            <h6>Gemini</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Cancer','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-4.png')}}" alt="">
            <h6>Cancer</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Leo','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-5.png')}}" alt="">
            <h6>Leo</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Virgo','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-6.png')}}" alt="">
            <h6>Virgo</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Libra','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-7.png')}}" alt="">
            <h6>Libra</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Scorpio','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-8.png')}}" alt="">
            <h6>Scorpio</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Sagittarius','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-9.png')}}" alt="">
            <h6>Sagittarius</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Capricorn','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-10.png')}}" alt="">
            <h6>Capricorn</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Aquarius','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-11.png')}}" alt="">
            <h6>Aquarius</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Pisces','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/d-12.png')}}" alt="">
            <h6>Pisces</h6>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- Daily Horoscopes-section-end -->



@endsection

@push('scripts')
  <script src="{{ asset('assets/lib/material-timepicker/mdtimepicker.js') }}"></script>
  <script type="text/javascript">
    $('document').ready(function(){
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
<script type="text/javascript">
  function validateAndSubmit(that){
    let _form=$(that);
    let formData=new FormData(that);
    formData.append('_token', '{{csrf_token() }}')
    let submitBtn=_form.find("button[type=submit]");
    submitBtn.prop("disabled",true);
    $.ajax({
      url:"{{ route('kundalienquiry') }}",
      type:"POST",
      data:formData,
      processData:false,
      contentType:false,
      success:function(response){
        toastr.success(response.message);
        if(response.redirectTo){
          window.location.href=response.redirectTo;
        }
      },
      error:function(xhr){
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
  var typingTimer;
  function locationPlaces(that, suggestionId) {
    clearTimeout(typingTimer);
    typingTimer=setTimeout(function(){
      var inputVal = $(that).val(); // Get the value of the input field
      if (inputVal.length > 2) { // Make sure input is at least 3 characters
        $(suggestionId).html('<li>Searching...</li>');
        $.ajax({
          url: '/search-location/' + encodeURIComponent(inputVal), // Make the GET request
          method: 'GET',
          success: function(response) {
            $(suggestionId).empty(); // Clear previous suggestions
            response.forEach(function(location) {
              var li = $('<li class="pointer"></li>'); // Create a new list item
              li.text(location.state);
              li.on('click', function(){
                  $(that).val($(this).text()); // Set clicked place in input
                  $("#city").val($(this).text()); // Set clicked place in input
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
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();  // Prevent default form submission on Enter key press
            let form = document.getElementById('kundliForm');  // Form ko get karna
            validateAndSubmit(form);  // Form ko submit karna
        }
    });
});


</script>
@endpush
