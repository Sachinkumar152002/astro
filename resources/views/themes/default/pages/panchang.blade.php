@extends(themeView().'layout')

@push("style")
<style media="screen">

.astro-info {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 18px;
  padding: 20px 0px;
}

.astro-card-2 {

  border-radius: 14px;
  padding: 10px 13px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 2px 1px 10px 0px #00000026;
}

.astro-text h4 {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #1c1c1c;
}

.astro-text p {
  margin: 6px 0 0;
  font-size: 15px;
  font-weight: 500;
  color: #444;
}

.astro-icon img {
  width:65px;
  height:65px;
  object-fit: contain;
}

/* Responsive */
@media (max-width: 992px) {
  .astro-info {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .astro-info {
    grid-template-columns: 1fr;
  }
}



.form-title {
text-align: center;
font-size: 24px;
font-weight: 600;
margin-bottom: 30px;
color: #333;
}

.form-group-custom {
display: flex;
flex-direction: column;
gap: 6px;
}

.label-custom {
font-weight: 500;
color: #333;
}

.input-custom {
height: 44px;
padding: 0 12px;
width: 100%;
border-radius: 8px;
border: 1px solid #ced4da;
transition: border-color 0.3s ease;
}

.input-custom:focus {
border-color: #0d6efd;
outline: none;
box-shadow: none;
}

.button-group-custom {
display: flex;
gap: 10px;
justify-content: space-between;
}

.btn-custom {
flex: 1;
height: 42px;
font-weight: 500;
border-radius:7px;
transition: background 0.3s ease;
background: #0e4677 !important;
border: 1px solid #0e4677;
color: white !important;
font-weight: bold;
}

.btn-outline {
background-color: #f8f9fa;
border-color: #ced4da;
color: #333;
}



@media (max-width: 767px) {
.form-row .col-sm-12 {
  margin-bottom: 15px;
}

.panc-box {
        margin-top: 25px !important;
    }
    .form-wrapper {
      margin-bottom: 0px !important;
    }
.form-title {
  margin-bottom: 13px !important;
}
.ft{
  font-size: 15px;
    line-height: 25px;
}
.button-group-custom {
  flex-direction: column;
}

.btn-custom {
  width: 100%;
}
}
.pun-page{
  margin-bottom: 35px !important;
}
.k_red_color{
  color: red;
}
</style>
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
    /* The mobile header is fixed, so page content must start below it. */
    .panchang-mobile-page { padding-top:72px; }
    .panchang-mobile-page .banner-second { margin-top:0; }
  }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/panchang-ui.css') }}">
@endpush

@section('title', 'Panchang | '.config("app.name"))

@section('content')

<main class="panchang-mobile-page">

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

<!-- Daily Horoscopes-section-start -->
<section class="mb-0 mb-lg-0 mt-5">
  <div class="container">
    <!-- <h2 class="text-center k_red_color">Hindu Panchang or Panchangam</h2> -->
    <div class="container px-0">
          <!-- <h4 class="form-title">Today Punchang</h4> -->
          <!-- <h5 class="text-center ft mb-4 text-success">{{ date('l, F j, Y', $panchangeDate) }} Panchang for {{ $lH?$lH->state:"New Delhi, India" }}</h5> -->
          <div class="row gx-3 align-items-end form-row justify-content-center mt-lg-0 mt-3">
            <div class="col-md-3 col-6">
              <div class="form-group-custom">
                <input type="date" class="input-custom" id="date" name="date">
              </div>
            </div>
            <div class="col-md-3 col-6">
              <input type="text" class="form-control" id="city" onkeyup="locationPlaces('#city', '#city_suggestion')" name="city" placeholder="Search City">
              <div class="suggestion_birth">
                <ul id="city_suggestion"></ul>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <!-- <label class="form-label">Language <span class="text-danger">*</span></label> -->
              <select class="form-select" name="lang" id="lang">
                <option value="en" {{ request()->get('lang')=='en'?'selected':'' }}>English</option>
                <option value="hi" {{ request()->get('lang')=='hi'?'selected':'' }}>Hindi</option>
              </select>
            </div>
            <div class="col-md-3 col-6">
              <div class="button-group-custom">
                <button type="button" class="btn-custom btn-primary-custom mt-3 mt-md-0" onclick="handlePanchang()">Get</button>
                {{--
                @if($selectedDate=request()->get('date'))
                  <a class="btn-custom btn-primary-custom text-center pt-2" href="{{ route('panchang',['date'=>$selectedDate,'lang'=>$lang=='hi'?'en':'hi']) }}">{{ $lang=='hi'?'English':'Hindi' }}</a>
                @else
                  <a class="btn-custom btn-primary-custom text-center pt-2" href="{{ route('panchang',['lang'=>$lang=='hi'?'en':'hi']) }}">{{ $lang=='hi'?'English':'Hindi' }}</a>
                @endif
                --}}
              </div>
            </div>
          </div>

      </div>
</section>
<section class="container">
<div class="astro-info ms-0">
  <div class="astro-card-2">
    <div class="astro-text">
      <h4>Sunrise</h4>
      <p>{{$data['advanced_details']['sun_rise'] ?? null}}</p>
    </div>
    <div class="astro-icon">
      <img src="assets/img/sunrise.png" alt="Sunrise">
    </div>
  </div>

  <div class="astro-card-2">
    <div class="astro-text">
      <h4>Sunset</h4>
      <p>{{$data['advanced_details']['sun_set'] ?? null}}</p>
    </div>
    <div class="astro-icon">
      <img src="assets/img/sun-set.png" alt="Sunset">
    </div>
  </div>

  <div class="astro-card-2">
    <div class="astro-text">
      <h4>Moonrise</h4>
      <p>{{$data['advanced_details']['moon_rise'] ?? null}}</p>
    </div>
    <div class="astro-icon">
      <img src="assets/img/moonrise.png" alt="Moonrise">
    </div>
  </div>

  <div class="astro-card-2">
    <div class="astro-text">
      <h4>Moonset</h4>
      <p>{{$data['advanced_details']['moon_set'] ?? null}}</p>
    </div>
    <div class="astro-icon">
      <img src="assets/img/moon-set.png" alt="Moonset">
    </div>
  </div>
</div>
<div class="col-md-12 col-sm-12 col-12 col-lg-12">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
</div>
</section>

<section>
  <div class="container">
    <div class="row d-flex">
      <div class="col-lg-12 col-12 d-flex" style="flex-direction: column;">
       <div class="mt-0">
         <h5 class="hc-title">Auspicious/ Inauspicious Time</h5>
         <div class="row mt-3">

          <!-- Tithi -->
          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="{{asset('images/Abhijeet-Muhurat-.jpg')}}" class="card-img-top">
              <div class="p-2">
                <h6>Abhijeet Muhurat</h6>

                  <p class="">{{$data['advanced_details']['abhijit_muhurta']['start']}} - {{$data['advanced_details']['abhijit_muhurta']['end']}}</p>

              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/Rahukaal.jpg" class="card-img-top">
              <div class="p-2">
                <h6>Rahukaal</h6>

                  <p class="">{{$data['rahukaal']}}</p>

              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/Yamagand-Kaal.jpg" class="card-img-top">
              <div class="p-2">
                <h6>Yamghant kaal</h6>

                  <p class="">{{$data['yamakanta']}}</p>

              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/Gulika-Kaal.jpg" class="card-img-top">
              <div class="p-2">
                <h6>GuliKaal</h6>

                  <p class="">{{$data['gulika']}}</p>

              </div>
            </div>
          </div>

       </div>
      </div>
     </div>
   </div>
   <div class="col-md-12 col-sm-12 col-12 col-lg-12">
     <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
   </div>
       </div>
      </div>
    </div>

  </div>
</section>
<section>
  <div class="container">
    <div class="row d-flex">
      <div class="col-lg-12 col-12 d-flex" style="flex-direction: column;">
       <div class="mt-0">
         <h5 class="hc-title">Panchang</h5>
         <div class="row mt-3">

          <!-- Tithi -->
          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/Tithi.jpg" class="card-img-top">
              <div class="p-2">
                <h6>Tithi</h6>
                @if(isset($data["tithi"]))
                  @php $tithi = $data["tithi"]; @endphp
                  <p class="">{{ $tithi["name"] }} upto {{ $tithi["end"] }}</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Nakshatra -->
          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/Nakshatra.jpg" class="card-img-top">
              <div class="p-2">
                <h6>Nakshatra</h6>
                @if(isset($data["nakshatra"]))
                  @php $nakshatra = $data["nakshatra"]; @endphp
                  <p class="">{{ $nakshatra["name"] }} next {{ $nakshatra["next_nakshatra"] }} upto {{ $nakshatra["end"] }}</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Karana -->
          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/Karan.jpg" class="card-img-top">
              <div class="p-2">
                <h6>Karan</h6>
                @if(isset($data["karana"]))
                  @php $karana = $data["karana"]; @endphp
                  <p class="">{{ $karana["name"] }} next {{ $karana["next_karana"] }} upto {{ $karana["end"] }}</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Paksha -->
          <!-- <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/krishnapaksh.jpeg" class="card-img-top">
              <div class="p-2">
                <h6>Paksha</h6>
                @if(isset($data["advanced_details"]))
                  @php $advanced_details = $data["advanced_details"]; @endphp
                  <p class="">{{ $advanced_details["masa"]["paksha"] }}</p>
                @endif
              </div>
            </div>
          </div> -->

          <!-- Yoga -->
          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/Yog.jpg" class="card-img-top">
              <div class="p-2">
                <h6>Yog</h6>
                @if(isset($data["yoga"]))
                  @php $yoga = $data["yoga"]; @endphp
                  <p class="">{{ $yoga["name"] }} upto {{ $yoga["end"] }}</p>
                @endif
              </div>
            </div>
          </div>

          <!-- Day -->
          <!-- <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card astro-card as-c">
              <img src="assets/img/Yellow_sunrise.JPG" class="card-img-top">
              <div class="p-2">
                <h6>Day</h6>
                @if(isset($data["day"]))
                  @php $day = $data["day"]; @endphp
                  <p class="">{{ $day["name"] }}</p>
                @endif
              </div>
            </div>
          </div> -->

        </div>

       </div>
      </div>


    </div>
    <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-2">
      <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
    </div>
   </div>

       </div>
      </div>
    </div>

  </div>
</section>
<section class="container hc-wrapper">
  <h2 class="hc-title">Hindu Calendar</h2>

  <div class="hc-card-row">

    <div class="hc-card">
      <div class="hc-icon">
        <img src="assets/img/p-calender.png" alt="Vikram Samvat">
      </div>
      <div class="hc-content">
        <h4>Vikram Samvat</h4>
        <p>{{$data['advanced_details']['years']['vikram_samvaat']}}</p>
      </div>
    </div>

    <div class="hc-card">
      <div class="hc-icon">
        <img src="assets/img/shanka.png" alt="Shaka Samvat">
      </div>
      <div class="hc-content">
        <h4>Shaka Samvat</h4>
        <p>{{$data['advanced_details']['years']['saka']}}</p>
      </div>
    </div>

  </div>

</section>
<section class="container ds-wrapper mt-4">
  <div class="ds-grid">

    <div class="ds-card">
      <div class="ds-icon">
        <img src="assets/img/disha_panchang.png" alt="Disha Shool">
      </div>
      <div class="ds-text">
        <h4>Disha Shool</h4>
        <p>{{$data['advanced_details']['disha_shool']}}</p>
      </div>
    </div>

    <div class="ds-card">
      <div class="ds-icon">
        <img src="assets/img/ayana_panchang.png" alt="Ayana">
      </div>
      <div class="ds-text">
        <h4>Ayana</h4>
        <p>{{$data['advanced_details']['masa']['ayana']}}</p>
      </div>
    </div>

    <div class="ds-card">
      <div class="ds-icon">
        <img src="assets/img/moon_panchang.png" alt="Moon Sign">
      </div>
      <div class="ds-text">
        <h4>Moon Sign</h4>
        <p>ARIES</p>
      </div>
    </div>

    <div class="ds-card">
      <div class="ds-icon">
        <img src="assets/img/sun_panchang.png" alt="Sun Sign">
      </div>
      <div class="ds-text">
        <h4>Sun Sign</h4>
        <p>{{$data['sun_position']['zodiac']}}</p>
      </div>
    </div>

  </div>
  <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-3 mb-2">
    <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
  </div>
</section>

<div class="container pb-2">
  <h2 class="hc-title">Hora Muhurta</h2>

  <!-- Sunrise / Sunset -->
  <div class="d-flex justify-content-between mb-3">
    <div class="sun-info">
      ☀️ Sunrise: <strong>{{$data['advanced_details']['sun_rise'] ?? null}}</strong>
    </div>
    <div class="sun-info">
      🌇 Sunset: <strong>{{$data['advanced_details']['sun_set'] ?? null}}</strong>
    </div>
  </div>

  <div class="row g-4">

    <!-- Day Hora -->
    <div class="col-md-6">
      <table class="table hora-table">
        <thead>
          <tr>
            <th colspan="2" class="hora-header day">
              <i class="bi bi-sun-fill sun-icon"></i> Day Hora Muhurta
            </th>
          </tr>
        </thead>
        <tbody>
          {{--<tr><td>Saturn</td><td>7:14 - 8:14</td></tr>
          <tr><td>Jupiter</td><td>8:14 - 9:14</td></tr>
          <tr><td>Mars</td><td>9:14 - 10:14</td></tr>
          <tr><td>Sun</td><td>10:14 - 11:14</td></tr>
          <tr><td>Venus</td><td>11:14 - 12:14</td></tr>
          <tr><td>Mercury</td><td>12:14 - 13:14</td></tr>
          <tr><td>Moon</td><td>13:14 - 14:14</td></tr>
          <tr><td>Saturn</td><td>14:14 - 15:14</td></tr>
          <tr><td>Jupiter</td><td>15:14 - 16:14</td></tr>
          <tr><td>Mars</td><td>16:14 - 17:14</td></tr>
          <tr><td>Sun</td><td>17:14 - 18:14</td></tr>
          <tr><td>Venus</td><td>18:14 - 19:14</td></tr>--}}
          @foreach($dayHora as $hora)
          <tr>
            <td>{{ $hora['planet'] }}</td>
            <td>{{ $hora['time'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Night Hora -->
    <div class="col-md-6">
      <table class="table hora-table">
        <thead>
          <tr>
            <th colspan="2" class="hora-header night">
              <i class="bi bi-moon-fill moon-icon"></i> Night Hora Muhurta
            </th>
          </tr>
        </thead>
        <tbody>
          {{--<tr><td>Mercury</td><td>19:14 - 20:14</td></tr>
          <tr><td>Moon</td><td>20:14 - 21:14</td></tr>
          <tr><td>Saturn</td><td>21:14 - 22:14</td></tr>
          <tr><td>Jupiter</td><td>22:14 - 23:14</td></tr>
          <tr><td>Mars</td><td>23:14 - 0:14</td></tr>
          <tr><td>Sun</td><td>0:14 - 1:14</td></tr>
          <tr><td>Venus</td><td>1:14 - 2:14</td></tr>
          <tr><td>Mercury</td><td>2:14 - 3:14</td></tr>
          <tr><td>Moon</td><td>3:14 - 4:14</td></tr>
          <tr><td>Saturn</td><td>4:14 - 5:14</td></tr>
          <tr><td>Jupiter</td><td>5:14 - 6:14</td></tr>
          <tr><td>Mars</td><td>6:14 - 7:14</td></tr>--}}
          @foreach($nightHora as $hora)
            <tr>
              <td>{{ $hora['planet'] }}</td>
              <td>{{ $hora['time'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
  <div class="col-md-12 col-sm-12 col-12 col-lg-12">
    <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
  </div>
</div>
<section>
  <div class="container pb-4">
    <h2 class="hc-title">Choghadiya </h2>

  <!-- Sunrise / Sunset -->
  <!-- <div class="d-flex justify-content-between mb-3">
    <div class="sun-box">☀️ Sunrise: <strong>{{$data['advanced_details']['sun_rise'] ?? null}}</strong></div>
    <div class="sun-box">🌇 Sunset: <strong>{{$data['advanced_details']['sun_set'] ?? null}}</strong></div>
  </div> -->

  <div class="row g-4">

    <!-- Day Choghadiya -->
    <div class="col-md-6">
      <table class="table choghadiya-table">
        <thead>
          <tr>
            <th colspan="2" class="ch-header day"><i class="bi bi-sun-fill sun-icon"></i> Day Choghadiya</th>
          </tr>
        </thead>
        <tbody>
          {{--<tr><td class="bad">Kaal - Inauspicious</td><td>07:14 AM – 08:32 AM</td></tr>
          <tr><td class="good">Shubh - Auspicious</td><td>08:32 AM – 09:50 AM</td></tr>
          <tr><td class="bad">Rog - Inauspicious</td><td>09:50 AM – 11:08 AM</td></tr>
          <tr><td class="bad">Udveg - Inauspicious</td><td>11:08 AM – 12:26 PM</td></tr>
          <tr><td class="neutral">Char - Good</td><td>12:26 PM – 01:43 PM</td></tr>
          <tr><td class="good">Labh - Auspicious</td><td>01:43 PM – 03:01 PM</td></tr>
          <tr><td class="good">Amrit - Auspicious</td><td>03:01 PM – 04:19 PM</td></tr>
          <tr><td class="bad">Kaal - Inauspicious</td><td>04:19 PM – 05:37 PM</td></tr>--}}
          @foreach($dayChoghadiya as $row)
          @php
            if($row['name'] == 'Labh' || $row['name'] == 'Shubh' || $row['name'] == 'Amrit'){
              $color = "#12ce29";
            }elseif($row['name'] == 'Udveg' || $row['name'] == 'Rog' || $row['name'] == 'Kaal'){
              $color = "#d61c00";
            }else {
              $color = "#2a75c1";
            }
          @endphp
          <tr>
            <td class="{{ $row['type'] }}" style="color : {{$color}}">{{ $row['name'] }} - {{ $row['type'] }}</td>
            <td>{{ $row['time'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Night Choghadiya -->
    <div class="col-md-6">
      <table class="table choghadiya-table">
        <thead>
          <tr>
            <th colspan="2" class="ch-header night"><i class="bi bi-moon-fill moon-icon"></i> Night Choghadiya</th>
          </tr>
        </thead>
        <tbody>
          {{--<tr><td class="good">Labh - Auspicious</td><td>05:37 PM – 07:19 PM</td></tr>
          <tr><td class="bad">Udveg - Inauspicious</td><td>07:19 PM – 09:01 PM</td></tr>
          <tr><td class="good">Shubh - Auspicious</td><td>09:01 PM – 10:43 PM</td></tr>
          <tr><td class="good">Amrit - Auspicious</td><td>10:43 PM – 12:26 AM</td></tr>
          <tr><td class="neutral">Char - Good</td><td>12:26 AM – 02:08 AM</td></tr>
          <tr><td class="bad">Rog - Inauspicious</td><td>02:08 AM – 03:50 AM</td></tr>
          <tr><td class="bad">Kaal - Inauspicious</td><td>03:50 AM – 05:32 AM</td></tr>
          <tr><td class="good">Labh - Auspicious</td><td>05:32 AM – 07:14 AM</td></tr>--}}
          @foreach($nightChoghadiya as $row)
          @php
            if($row['name'] == 'Labh' || $row['name'] == 'Shubh' || $row['name'] == 'Amrit'){
              $color = "#12ce29";
            }elseif($row['name'] == 'Udveg' || $row['name'] == 'Rog' || $row['name'] == 'Kaal'){
              $color = "#d61c00";
            }else {
              $color = "#2a75c1";
            }
          @endphp
          <tr>
            <td class="{{ $row['type'] }}" style="color : {{$color}}">{{ $row['name'] }} - {{ $row['type'] }}</td>
            <td>{{ $row['time'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>

  <!-- Legend -->
  <div class="ch-legend">
    <span class="legend-item">
      <i class="legend-box good-bg"></i> Good
    </span>
    <span class="legend-item">
      <i class="legend-box auspicious-bg"></i> Auspicious
    </span>
    <span class="legend-item">
      <i class="legend-box bad-bg"></i> Inauspicious
    </span>
  </div>


</div>
</section>



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
</main>
@endsection

@push('scripts')
<script type="text/javascript">
  function handlePanchang(){
    let selectedDate = $("#date").val();
    let selectedCity = $("#city").val();
    let selectedLang = $("#lang").val();
    if(!selectedDate){
      selectedDate = @json(date('Y-m-d'));
    }
    let url="{{ route('panchang') }}";
    url=`${url}?date=${selectedDate}&lang=${selectedLang}&city=${selectedCity}`;
    window.location.href=url;
  }
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
document.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        handlePanchang();
    }
});
</script>
@endpush
