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

.hc-title {
  font-size: 26px;
  font-weight: 700;
  margin-bottom: 18px;
  color: #111;
}

.hc-card-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
}

.hc-card {
  background:#f7f7f7 !important;
  border-radius: 14px;
  padding: 22px 24px;
      box-shadow: 1px 2px 6px 0px #00000040;
  display: flex;
  align-items: center;
  gap: 18px;
  background: #f7f7ff;
  /* box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08); */
}

.hc-icon img {
  width: 52px;
  height: 52px;
  object-fit: contain;
}

.hc-content h4 {
  margin: 0;
  font-size:25px;
  font-weight: 600;
  color: #1a1a1a;
}

/* Sunrise / Sunset box */
.sun-info {
  background:#f7f7f7;
  padding: 10px 14px;
  border-radius: 6px;
  border: 1px solid #bdbdbd;
  font-size: 14px;
}
.hora-header.day,.hora-header.night{
  background: #f7f7f7;
}
/* Table */
.hora-table {
  border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #bdbdbd;
    border-radius: 12px;
    overflow: hidden;
}

.hora-table th {
  border: 1px solid #d3d3d3;
  padding: 11px 16px;
  font-size: 14px;
}
.hora-table td{
  border: 1px solid #d3d3d3;
  font-size: 15px;

}
/* Header */
.hora-header {
  background: #ffcf3c;
  font-weight: 600;
  font-size: 16px;
  text-align: left;
}

/* Align time column */
.hora-table td:last-child {
  text-align: right;
}

/* Responsive */
@media (max-width: 576px) {
  .hora-table th,
  .hora-table td {
    font-size: 13px;
    padding: 10px;
  }

  .sun-info {
    font-size: 13px;
  }
}


/* Planet column text */
.choghadiya-table .bad{
  color: #d61c00;   /* red text like image */
  font-weight: normal !important;

}
.choghadiya-table .good{
  color: #12ce29;
  font-weight: normal !important;
}
.choghadiya-table .neutral {
  color: #2a75c1;
  font-weight: normal !important;
}
/* Center align time */
.hora-table td:nth-child(2),
.hora-table td:nth-child(3) {
  text-align: center;
  /* font-weight: 500; */
}

/* Responsive */
@media (max-width: 576px) {
  .hora-table thead th,
  .hora-table td {
    font-size: 13px;
    padding: 10px;
  }
}
/* Sunrise / Sunset */
.sun-box {
  background: #f7f7f7;
  padding: 10px 14px;
  border-radius: 6px;
  font-size: 14px;
  border: 1px solid #bdbdbd;
}

/* Table */
.choghadiya-table {
  border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #bdbdbd;
    border-radius: 12px;
    overflow: hidden;
}
.moon-icon{
  font-size:20px;
}
.sun-icon{
  font-size:20px;
  color:#ff9f15;
}
.choghadiya-table th{
  border: 1px solid #ddd;
  padding: 11px 14px;
  font-size: 15px;
}
.choghadiya-table td {
  border: 1px solid #ddd;
  font-size: 15px;
  line-height: 19px;

}
/* Header */
.ch-header {
  font-weight: 600;
  font-size: 16px;
  text-align: left;
}
.mdtp__time_m.active {
  color: #000 !important;
}
.ch-header.day,
.ch-header.night {
  background: #f7f7f7;
}

/* Text Colors */
.good {
  color: #0aaf00;
  font-weight: 600;
}

.bad {
  color: #ff1a1a;
  font-weight: 600;
}

.neutral {
  color: #1a55ff;
  font-weight: 600;
}

.ch-legend {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 22px;
  font-size: 14px;
  margin-top: 12px;
}

.legend-item {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-weight: 500;
}

.legend-box {
  width: 14px;
  height: 14px;
  border-radius: 2px;
}

/* Colors */
.good-bg {
  background: #1a55ff;
}

.auspicious-bg {
  background: #00c900;
}

.bad-bg {
  background: #ff3b3b;
}

/* Mobile */
@media (max-width: 576px) {
  .ch-legend {
    justify-content: center;
    font-size: 13px;
  }
}


.good-bg { background: #1a55ff; }
.auspicious-bg { background: #0aaf00; }
.bad-bg { background: #ff1a1a; }

/* Responsive */
@media (max-width: 576px) {
  .choghadiya-table th,
  .choghadiya-table td {
    font-size: 13px;
  }
}
.ds-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.ds-card {
  background: #ffff;
  box-shadow: 2px 2px 6.1px 0px #00000026;
  border-radius: 14px;
  padding:7px 22px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.ds-icon img {
  width: 44px;
  height: 44px;
  object-fit: contain;
}

.ds-text h4 {
  margin: 0;
  font-size: 14px;
  font-weight: 600;
  color: #111;
}

.ds-text p {
  margin: 4px 0 0;
  font-size: 13px !important;
  font-weight: 500;
  letter-spacing: 0.6px;
  color: #555;
}

/* Responsive */
@media (max-width: 992px) {
  .ds-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .ds-grid {
    grid-template-columns: 1fr;
  }
}

.hc-content p {
  margin: 6px 0 0;
  font-size: 20px;
  font-weight: 500;
  color: #000;
}

/* Responsive */
@media (max-width: 768px) {
  .hc-card-row {
    grid-template-columns: 1fr;
  }
}


.astro-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.astro-card img {
  height: 110px;
  object-fit: cover;
}

.astro-small-card {
  background: #fff5e8;
  border-radius: 16px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.08);
  transition: 0.3s;
}

.astro-small-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 14px rgba(0,0,0,0.12);
}

.s-icon {
  width: 48px;
  height: 48px;
}
.astro-small-card {
  min-height: 100px;
  background: #fff5e8;
  border-radius: 16px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.s-icon {
  width: 48px;
  height: 48px;
}
.specal_head{
  border-bottom: 2px solid #dddddd;
}
.p-de-card{
  height: 100px !important;
}
.as-c{
  min-height: 100% !important;
}
.as-c h6{
  color: #1E5AA1;
  font-size: 15px;
  font-weight: bold;
}
.astro-card-2:nth-child(25n+1){ background:rgb(255 221 148 / 64%);  }
.astro-card-2:nth-child(25n+2) { background:  rgb(255 170 90 / 54%);  }
.astro-card-2:nth-child(25n+3){ background: #CFF5E0; }
.astro-card-2:nth-child(25n+4) { background: #F5E8FF; }
.astro-card-2:nth-child(25n+5) { background: #FFEAC2; }
.astro-card-2:nth-child(25n+6) { background: #DFF7E6; }
.astro-card-2:nth-child(25n+7) { background: #CFF5E0; }
.astro-card-2:nth-child(25n+8) { background: #E6F7D9; }
.time-text {
  margin: 0;
  font-size: 14px;
  color: #444;
}

.ax-connect-wrap {
  padding: 40px 20px;
  background: #fdd2ac;
}

.ax-connect-card {
  max-width: 1100px;
  margin: auto;
  background: #ffffff;
  border-radius: 16px;
  padding: 28px 20px;
  text-align: center;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.28);
}

.ax-connect-text {
  font-size:30px !important;
  color:black;
  font-weight: bold !important;
  margin-bottom:2px;
  font-weight: 500;
}

.ax-connect-text strong {
  font-weight: 700;
}

.ax-connect-actions {
  display: flex;
  justify-content: center;
  gap: 26px;
  flex-wrap: wrap;
}





.ax-ic {
  width: 18px;
  height: 18px;
  display: inline-block;
}


@media (max-width: 576px) {
  .ax-connect-text {
    font-size: 18px;
  }

  .ax-connect-btn {
    width: 100%;
    justify-content: center;
  }
}
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

/* Panchang page refresh */
.panchang-dashboard { background:#f7f8fc; padding:24px 0 34px; color:#211838; }
.panchang-dashboard > section { margin:0 0 22px !important; }
.panchang-dashboard .banner-second { display:block; height:230px; margin:0; border-radius:18px; object-fit:cover; box-shadow:0 10px 24px rgba(30,18,59,.12); }
.panchang-dashboard .form-row { margin:0; padding:20px; border-radius:16px; background:#fff; box-shadow:0 4px 16px rgba(35,24,63,.07); }
.panchang-dashboard .input-custom,.panchang-dashboard #city,.panchang-dashboard #lang { height:45px; border:1px solid #ded9e7; border-radius:9px; font-size:14px; box-shadow:none; }
.panchang-dashboard .btn-custom { height:45px; border:0!important; border-radius:9px; background:linear-gradient(135deg,#ffb00b,#ef8900)!important; box-shadow:0 5px 13px rgba(245,147,0,.22); }
.panchang-dashboard .astro-info { gap:14px; padding:0; }
.panchang-dashboard .astro-card-2 { min-height:104px; border:1px solid #eee9f4; border-radius:14px; background:#fff; box-shadow:0 4px 14px rgba(35,24,63,.07); }
.panchang-dashboard .astro-text h4 { color:#281745; font-size:15px; }.panchang-dashboard .astro-text p { color:#7d5a1f; font-size:14px; font-weight:700; }.panchang-dashboard .astro-icon img { width:54px; height:54px; }
.panchang-dashboard .hc-title { margin:0 0 14px; color:#211838; font-size:20px; font-weight:800; }
.panchang-dashboard .\-astro-card.as-c { height:100%; overflow:hidden; border:1px solid #eee9f4!important; border-radius:14px; background:#fff; box-shadow:0 4px 14px rgba(35,24,63,.07); }
.panchang-dashboard .\-astro-card.as-c > img { height:112px; object-fit:cover; }.panchang-dashboard .\-astro-card.as-c h6 { margin:0 0 5px; font-size:14px; font-weight:800; color:#24163d; }.panchang-dashboard .\-astro-card.as-c p { margin:0; color:#756c81; font-size:12px; line-height:1.4; }
.panchang-dashboard .hora-table,.panchang-dashboard .choghadiya-table { width:100%; overflow:hidden; border:1px solid #e5e0ec; border-radius:12px; background:#fff; box-shadow:0 3px 11px rgba(35,24,63,.05); }.panchang-dashboard .hora-table th,.panchang-dashboard .hora-table td,.panchang-dashboard .choghadiya-table th,.panchang-dashboard .choghadiya-table td { padding:11px 14px; border-color:#eeeaf2; }.panchang-dashboard .hora-header,.panchang-dashboard .ch-header { background:#f4edff!important; color:#321b56; }.panchang-dashboard .ch-legend { justify-content:flex-start; margin-top:10px; }
.panchang-dashboard .panchang_line_img1 { display:none; }
.panchang-dashboard .ax-connect-wrap { margin:28px auto 0!important; }.panchang-dashboard .ax-connect-card { border-radius:16px; box-shadow:0 8px 22px rgba(35,24,63,.1); }
@media(max-width:768px){.panchang-dashboard{padding:76px 0 16px}.panchang-dashboard .banner-second{height:140px;margin:0}.panchang-dashboard .form-row{padding:14px;gap:10px}.panchang-dashboard .astro-info{grid-template-columns:repeat(2,1fr)}.panchang-dashboard .astro-card-2{min-height:90px}.panchang-dashboard .\-astro-card.as-c>img{height:95px}}
</style>
@endpush

@section('title', 'Panchang | '.config("app.name"))

@section('content')

<main class="panchang-dashboard">

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
                <input type="date" class="input-custom" id="date" name="date" value="{{date('Y-m-d', strtotime($date))}}">
              </div>
            </div>
            <div class="col-md-3 col-6">
              <input type="text" class="form-control" id="city" onkeyup="locationPlaces('#city', '#city_suggestion')" name="city" placeholder="Search City" value="{{$lH ? $lh->state : 'New Delhi, India'}}">
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
      <img src="https://astrovyra.dixinfotech.in/assets/img/sunrise.png" alt="Sunrise">
    </div>
  </div>

  <div class="astro-card-2">
    <div class="astro-text">
      <h4>Sunset</h4>
      <p>{{$data['advanced_details']['sun_set'] ?? null}}</p>
    </div>
    <div class="astro-icon">
      <img src="https://astrovyra.dixinfotech.in/assets/img/sun-set.png" alt="Sunset">
    </div>
  </div>

  <div class="astro-card-2">
    <div class="astro-text">
      <h4>Moonrise</h4>
      <p>{{$data['advanced_details']['moon_rise'] ?? null}}</p>
    </div>
    <div class="astro-icon">
      <img src="https://astrovyra.dixinfotech.in/assets/img/moonrise.png" alt="Moonrise">
    </div>
  </div>

  <div class="astro-card-2">
    <div class="astro-text">
      <h4>Moonset</h4>
      <p>{{$data['advanced_details']['moon_set'] ?? null}}</p>
    </div>
    <div class="astro-icon">
      <img src="https://astrovyra.dixinfotech.in/assets/img/moon-set.png" alt="Moonset">
    </div>
  </div>
</div>
<div class="col-md-12 col-sm-12 col-12 col-lg-12">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="https://astrovyra.dixinfotech.in/assets/img/rectangle_panchang.png">
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
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/abhijeet__muhurat.png" class="card-img-top">
              <div class="p-2">
                <h6>Abhijeet Muhurat</h6>

                  <p class="">{{$data['advanced_details']['abhijit_muhurta']['start']}} - {{$data['advanced_details']['abhijit_muhurta']['end']}}</p>

              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/rahu__kaal.png" class="card-img-top">
              <div class="p-2">
                <h6>Rahukaal</h6>

                  <p class="">{{$data['rahukaal']}}</p>

              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/yamaganda__kaal.png" class="card-img-top">
              <div class="p-2">
                <h6>Yamghant kaal</h6>

                  <p class="">{{$data['yamakanta']}}</p>

              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 col-12 mb-3">
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/gulika__kaal.png" class="card-img-top">
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
     <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="https://astrovyra.dixinfotech.in/assets/img/rectangle_panchang.png">
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
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/tithi_panchang.png" class="card-img-top">
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
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/nakshatra_panchang.png" class="card-img-top">
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
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/karan_panchang.png" class="card-img-top">
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
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/krishnapaksh.jpeg" class="card-img-top">
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
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/yog_panchang.png" class="card-img-top">
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
            <div class="card -astro-card as-c">
              <img src="https://astrovyra.dixinfotech.in/assets/img/Yellow_sunrise.JPG" class="card-img-top">
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
      <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="https://astrovyra.dixinfotech.in/assets/img/rectangle_panchang.png">
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
        <img src="https://astrovyra.dixinfotech.in/assets/img/p-calender.png" alt="Vikram Samvat">
      </div>
      <div class="hc-content">
        <h4>Vikram Samvat</h4>
        <p>{{$data['advanced_details']['years']['vikram_samvaat']}}</p>
      </div>
    </div>

    <div class="hc-card">
      <div class="hc-icon">
        <img src="https://astrovyra.dixinfotech.in/assets/img/shanka.png" alt="Shaka Samvat">
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
        <img src="https://astrovyra.dixinfotech.in/assets/img/disha_panchang.png" alt="Disha Shool">
      </div>
      <div class="ds-text">
        <h4>Disha Shool</h4>
        <p>{{$data['advanced_details']['disha_shool']}}</p>
      </div>
    </div>

    <div class="ds-card">
      <div class="ds-icon">
        <img src="https://astrovyra.dixinfotech.in/assets/img/ayana_panchang.png" alt="Ayana">
      </div>
      <div class="ds-text">
        <h4>Ayana</h4>
        <p>{{$data['advanced_details']['masa']['ayana']}}</p>
      </div>
    </div>

    <div class="ds-card">
      <div class="ds-icon">
        <img src="https://astrovyra.dixinfotech.in/assets/img/moon_panchang.png" alt="Moon Sign">
      </div>
      <div class="ds-text">
        <h4>Moon Sign</h4>
        <p>ARIES</p>
      </div>
    </div>

    <div class="ds-card">
      <div class="ds-icon">
        <img src="https://astrovyra.dixinfotech.in/assets/img/sun_panchang.png" alt="Sun Sign">
      </div>
      <div class="ds-text">
        <h4>Sun Sign</h4>
        <p>{{$data['sun_position']['zodiac']}}</p>
      </div>
    </div>

  </div>
  <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-3 mb-2">
    <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="https://astrovyra.dixinfotech.in/assets/img/rectangle_panchang.png">
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
    <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="https://astrovyra.dixinfotech.in/assets/img/rectangle_panchang.png">
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
