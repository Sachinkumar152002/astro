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
  .a_time_iconn {
    position: relative;
    left: 69%;
    top: 37px;
}
  
</style>
<link rel="stylesheet" href="{{ asset('assets/lib/material-timepicker/mdtimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/kundali-ui.css') }}?v={{ filemtime(public_path('assets/css/kundali-ui.css')) }}">
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
          $bannerImageUrl = $bannerImage->image(1200, 675);
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
  <section class="pb-5 py-5" style="  background: #f5f7fa;">
    <div class="container ">
      <div class="row">
        <div class="col-md-8 col-12">
          <div class="kundli-banner-top">
            <img src="assets/img/kundli-top.png" alt="">
          </div>
           <div class="kundli-form-box p-0">
              <!-- <div class="form-h form-hh">
                <h4 class="h5 py-3 text-center bg-p fs-24 f6">Get Your Free Kundali</h4>
              </div> -->
              <div class="padding-tb">
                <form id="kundliForm" onsubmit="return validateAndSubmit(this)">
                  <div class="row">
                   <div class="col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" data-name="Name" value="{{$kundali ? $kundali->name : ''}}">
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select class="form-select" name="gender">
                      <option value="">Select</option>
                      <option value="male" {{$kundali && $kundali->gender=='male' ? 'selected' : ''}}>Male</option>
                      <option value="female" {{$kundali && $kundali->gender=='female' ? 'selected' : ''}}>Female</option>
                      <option value="other" {{$kundali && $kundali->gender=='other' ? 'selected' : ''}}>Other</option>
                    </select>
                  </div>
                </div>

                  <div class="col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Birth Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" value="{{$kundali ? $kundali->date : ''}}">
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Birth Time <span class="text-danger">*</span></label>
                    <i class="bi bi-clock a_time_iconn" onclick="handleTimePickerIcon('#birth_time')"></i>
                        <input class="form-control timepicker"  type="text" name="time" value="{{$kundali ? $kundali->time : ''}}" step="1" id="birth_time">
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="mb-3 bp-suggestion">
                    <label class="form-label">Birth Place <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="birth_place" id="birth_place" onkeyup="locationPlaces('#birth_place', '#place_suggestions')" value="{{$kundali ? $kundali->city : ''}}">
                    <div class="suggestion_birth">
                      <ul id="place_suggestions"></ul>
                    </div>
                  </div>
                </div>

                  <div class="mb-3 d-none">
                    <input type="text" class="form-control" name="city" id="city" value="{{$kundali ? $kundali->city : ''}}">
                  </div>
                  <div class="col-md-6 col-12">
                  <div class="mb-3">
                    <label class="form-label">Language <span class="text-danger">*</span></label>
                    <select class="form-select" name="lang">
                      <option value="">Select</option>
                      <option {{$kundali && $kundali->lang=='en' ? 'selected' : ''}} value="en">English</option>
                      <option {{$kundali && $kundali->lang=='hi' ? 'selected' : ''}} value="hi">Hindi</option>
                      <!-- <option {{$kundali && $kundali->lang=='be' ? 'selected' : ''}} value="be">Bengali</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='fr' ? 'selected' : ''}} value="fr">French</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='ka' ? 'selected' : ''}} value="ka">Kannada</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='ml' ? 'selected' : ''}} value="ml">Malayalam</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='sp' ? 'selected' : ''}} value="sp">Spanish</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='ta' ? 'selected' : ''}} value="ta">Tamil</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='te' ? 'selected' : ''}} value="te">Telugu</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='mr' ? 'selected' : ''}} value="mr">Marathi</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='si' ? 'selected' : ''}} value="si">Sinhalese</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='ne' ? 'selected' : ''}} value="ne">Nepali</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='ko' ? 'selected' : ''}} value="ko">Korean</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='ja' ? 'selected' : ''}} value="ja">Japanese</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='gu' ? 'selected' : ''}} value="gu">Gujarati</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='de' ? 'selected' : ''}} value="de">German</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='pt' ? 'selected' : ''}} value="pt">Portuguese</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='tr' ? 'selected' : ''}} value="tr">Turkish</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='ru' ? 'selected' : ''}} value="ru">Russian</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='it' ? 'selected' : ''}} value="it">Italian</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='nl' ? 'selected' : ''}} value="nl">Dutch</option> -->
                      <!-- <option {{$kundali && $kundali->lang=='pl' ? 'selected' : ''}} value="pl">Polish</option> -->
                    </select>
                  </div>
                </div>
              </div>
                  <div class="text-center">
                    <button type="submit" class="btn kundli-btn px-4 fw-bold py-3">Generate Kundali</button>
                  </div>
                </form>
              </div>
            </div>

        </div>
        <div class="col-md-4 col-12 mt-3 mt-md-0">
         <img src="assets/img/free-kundli-right-banner.jpg" alt="" class="free-kundli-right-banner w-100 radius-8">
        </div>
      </div>
    </div>
  </section>
  <!-- form-sectio-end -->
<!-- Daily Horoscopes-section-start -->
<section class="pb-3 pb-md-5 pb-lg-5 pt-5">
  <div class="container">
    <h2 class="text-center">Daily Horoscopes</h2>
    <p class="text-center">Daily horoscopes are like a guiding light in the vast universe of possibilities.</p>
    <div class="row mt-4 mt-lg-3">
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Aries','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/ARIES.png')}}" alt="">
            <h6>Aries</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Taurus','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/TAURUS.png')}}" alt="">
            <h6>Taurus</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Gemini','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/GEMINI.png')}}" alt="">
            <h6>Gemini</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Cancer','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/CANCER.png')}}" alt="">
            <h6>Cancer</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Leo','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/LEO.png')}}" alt="">
            <h6>Leo</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Virgo','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/VIRGO.png')}}" alt="">
            <h6>Virgo</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Libra','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/LIBRA.png')}}" alt="">
            <h6>Libra</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Scorpio','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/SCORPIO.png')}}" alt="">
            <h6>Scorpio</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Sagittarius','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/SAGITTARIUS.png')}}" alt="">
            <h6>Sagittarius</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Capricorn','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/CAPRICORN.png')}}" alt="">
            <h6>Capricorn</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Aquarius','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/AQUARIUS.png')}}" alt="">
            <h6>Aquarius</h6>
          </div>
        </a>
      </div>
      <div class="col-lg-2 col-md-3 col-6">
        <a href="{{ route('horoscopedetail', ['Pisces','today']) }}">
          <div class="hor-b">
            <img src="{{asset('assets/img/PISCES.png')}}" alt="">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/lib/material-timepicker/mdtimepicker.js') }}"></script>
<script type="text/javascript">
  function validateAndSubmit(that){
      $('#city').val($('#birth_place').val()); // Sync visible input with hidden input

    let _form=$(that);
    let formData=new FormData(that);
    formData.append('_token', '{{ csrf_token() }}');
    let submitBtn=_form.find("button[type=submit]");
    submitBtn.prop("disabled",true);
    $.ajax({
      url:"{{ route('kundali.index') }}",
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
document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();  // Prevent default form submission on Enter key press
            let form = document.getElementById('kundliForm');  // Form ko get karna
            validateAndSubmit(form);  // Form ko submit karna
        }
    });
});
$(document).ready(function(){
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

@endpush
