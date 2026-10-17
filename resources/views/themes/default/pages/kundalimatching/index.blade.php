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
  .time_icon{
    position: relative;
    top: 35px;
    left: 72%;
  }
  .clockpicker-popover .popover-title{
    background-color: #FCD7C5 !important;
    color: #000;
  }
  .clockpicker-span-hours{
    color: black !important;

  }
  @media(max-width:768px){
    .banner-second{
      margin-top: 60px;
    }
  }

</style>
<link rel="stylesheet" href="{{ asset('assets/lib/material-timepicker/mdtimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/kundali-ui.css') }}">
@endpush

@section('title', 'Kundali Matching | '.config("app.name"))

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"Kundali Deatil",
    "description":"View your complete Kundali details with planetary positions, houses, and horoscope insights.",
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
  <section class="mb-3 pb-3 pt-3 pt-md-5">
    <div class="container">
     <div class="row">
       <div class="col-md-8 col-12">
         <div class="kundli-banner-top">
           <img src="assets/img/kundli-top-b.jpg" alt="">

         </div>
         <form id="kundliForm" onsubmit="return validateAndSubmit(this)">
           <div class="kundli-f-box bg-white">
             <!-- <div class="form-h bor-7">
               <h4 class="h5 py-3 text-center bg-p fs-24 f6">Enter Details</h4>
             </div> -->
             <div class="row p-2 p-md-2 w-100 m-0">
               <div class="col-md-6 col-12">
                 <div class="form-container">
                   <div class="d-flex align-items-center">
                     <img src="{{ asset('assets/images/icons/man.png') }}" width="30px" height="30px" alt="man">
                     <h6 class="form-subheading mb-0 ps-1">Boy Details</h6>
                   </div>
                   <div class="mb-3 mt-3">
                     <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                     <input type="text" id="boy_name" name="boy_name" class="form-control" placeholder="Enter Full Name">
                   </div>

                   <div class="row">
                     <div class="col-md-6 col-12">
                       <div class="mb-3">
                         <label for="dob" class="form-label">Birth Date <span class="text-danger">*</span></label>
                         <input type="date" id="boy_birth_date" name="boy_birth_date" class="form-control">
                       </div>


                     </div>
                     <div class="col-md-6 col-12">
                       <div class="mb-0">
                         <label for="time" class="form-label">Birth Time <span class="text-danger">*</span></label>
                         <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#boy_birth_time')"></i>
                         <input type="text" id="boy_birth_time" name="boy_birth_time" class="form-control timepicker" step="60">
                       </div>

                     </div>
                   </div>
                   <div class="mb-0">
                     <label for="boy_birth_place_search" class="form-label">Birth Place <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" name="boy_birth_place_search" id="boy_birth_place_search" placeholder="Enter Birth Place" onkeyup="locationPlaces('#boy_birth_place_search', '#boy_birth_place_suggestions', '#boy_birth_place')">
                     <div class="suggestion_birth">
                       <ul id="boy_birth_place_suggestions"></ul>
                     </div>
                     <input type="hidden" name="boy_birth_place" id="boy_birth_place">
                   </div>
                 </div>
               </div>
               <div class="col-md-6 col-12">
                 <div class="form-container girl_d">
                   <div class="d-flex align-items-center">
                     <img src="{{ asset('assets/images/icons/woman.png') }}" width="30px" height="30px" alt="woman">
                     <h6 class="form-subheading mb-0 ps-1">Girl Details <span class="text-danger">*</span></h6>
                   </div>
                   <div class="mb-3 mt-3">
                     <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                     <input type="text" id="girl_name" name="girl_name" class="form-control" placeholder="Enter Full Name">
                   </div>

                   <div class="row">
                     <div class="col-md-6 col-12">
                       <div class="mb-3">
                         <label for="dob" class="form-label">Birth Date <span class="text-danger">*</span></label>
                         <input type="date" id="girl_birth_date" name="girl_birth_date" class="form-control">
                       </div>

                     </div>
                     <div class="col-md-6 col-12">
                       <div class="mb-0">
                         <label for="time" class="form-label">Birth Time <span class="text-danger">*</span></label>
                         <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#girl_birth_time')"></i>
                         <input type="text" id="girl_birth_time" name="girl_birth_time" class="form-control timepicker" step="60">
                       </div>

                     </div>

                   </div>
                   <div class="mb-0">
                     <label for="girl_birth_place_search" class="form-label">Birth Place <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" name="girl_birth_place_search" id="girl_birth_place_search" placeholder="Enter Birth Place" onkeyup="locationPlaces('#girl_birth_place_search', '#girl_birth_place_suggestions', '#girl_birth_place')">
                     <div class="suggestion_birth">
                       <ul id="girl_birth_place_suggestions"></ul>
                     </div>
                     <input type="hidden" name="girl_birth_place" id="girl_birth_place">
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
               <div class="col-md-6 col-12">
                <div class="mt-4">
                    <button type="submit" class="p-add btn main_button px-4 fw-bold py-3" >Get a Report</button>
                </div>
               </div>
             </div>


             </form>

         </div>
       </div>
       <div class="col-md-4 col-12 mt-3 mt-md-0">
         <img src="assets/img/kundli-matching-right.jpg" alt="" class="kundli-right-banner">

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
@endsection

@push('scripts')
<script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script src="{{ asset('assets/lib/material-timepicker/mdtimepicker.js') }}"></script>
<script type="text/javascript">
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
<script type="text/javascript">
  function validateAndSubmit(that){
    let _form=$(that);
    let formData=new FormData(that);
    let submitBtn=_form.find("button[type=submit]");
    submitBtn.prop("disabled",true);
    $.ajax({
      url:"{{ route('kundalimatchingenquiry') }}",
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
  function locationPlaces(that, suggestionId, inputId){
    $(inputId).val("");
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
</script>
<script type="text/javascript">
$(document).ready(function(){
// var now = new Date();
// $("#releasetime").val(getTimeString(now));
$("#boy_birth_time").clockpicker({
  donetext: 'Okay',
  autoclose:true
});
$("#girl_birth_time").clockpicker({
  donetext: 'Okay',
  autoclose:true
});
});
// $("input#releasetime").change(function(e){
//   e.preventDefault();
//   var timestr = $(this).val();
//   var now = new Date();
//   if(!isValidTimeString(timestr)){
//     $(this).val(getTimeString(now));
//   }
//   var release = new Date();
//   release.setHours(parseInt(timestr.split(":")[0]));
//   release.setMinutes(parseInt(timestr.split(":")[1]));
//   release.setSeconds(0);
//   console.log(release);
//   console.log(now);
//   if(now >= release){
//     $("body").addClass("valid").removeClass("invalid");
//   }else{
//     $("body").addClass("invalid").removeClass("valid");
//   }
// });
//
// $(".input-group.clockpicker").click(function(e){
//   e.stopPropagation();
//   $("#releasetime").clockpicker('show');
// });
//
// $("input#releasetime").trigger("change");
//
// setInterval(function(){
//   $("input#releasetime").trigger("change");
// },1000);
//
// }); // end document ready
//
// // helper: get string from date (format: hh:mm)
// function getTimeString(datetime){
// var hours = datetime.getHours();
// var minutes = datetime.getMinutes();
// if(parseInt(hours) < 10){
//   hours = "0" + hours;
// }
// if(parseInt(minutes) < 10){
//   minutes = "0" + minutes;
// }
// return hours + ":"  + minutes;
// };
//
// // helper: is time string valid? (format: hh:mm)
// function isValidTimeString(timestr){
// var hours = timestr.split(":")[0];
// var minutes = timestr.split(":")[1];
// if(parseInt(hours) >= 0 && parseInt(hours) <= 24 && parseInt(minutes) >= 0 && parseInt(minutes) <=59 ){
//   return true;
// }
// return false;
// };
</script>
<script type="text/javascript">
document.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        let form = document.getElementById('kundliForm');
        validateAndSubmit(form);
    }
});
</script>
@endpush
