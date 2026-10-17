@extends(themeView().'layout')

@push("style")
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/horoscopes-ui.css') }}">
@endpush

@section('title', 'Horoscope | '.config("app.name"))

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"Horoscope",
    "description":"Get personalized horoscope insights based on your zodiac sign and planetary positions.",
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
<section class="container mt-5 pt-4 pt-md-0 mt-md-0">
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
<!-- Daily Horoscopes-section-start -->
<!-- <section>
  <div class="container">
    <h2 class="section-heading">What Is My Horoscope?</h2>
    <p class="content mb-0">Your free Horoscope online is considered to be a forecast of one’s future. Based on one’s birth details, the zodiac sign or moon sign of an individual is calculated. Using these signs, an astrologer makes predictions about the nature of coming events in one’s life.</p>
  </div>
</section> -->
<section class="pb-0 mt-5">
  <div class="container">
    <h2 class="text-center fs-30">Daily Horoscopes</h2>
    <p class="text-center">Daily horoscopes are like a guiding light in the vast universe of possibilities.</p>

  </div>
</section>
<!-- Daily Horoscopes-section-end -->
<section>
  <div class="container my-5">
  <div class="row g-4">

    <!-- LEFT : ZODIAC ICONS -->
    <div class="col-md-7">
      <div class="zodiac-box">
        <div class="row text-center">

          <div class="col-3 zodiac-item active" data-sign="Aries" data-img = "{{asset('assets/img/ARIES.png')}}" data-range="Mar 21 - Apr 19">
            <div class="zodiac-icon"><img src="{{asset('assets/img/ARIES.png')}}" alt="">
            </div>
            <p>Aries</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Taurus" data-img = "{{asset('assets/img/TAURUS.png')}}" data-range="Apr 20 - May 20">
            <div class="zodiac-icon"><img src="{{asset('assets/img/TAURUS.png')}}" alt="">
            </div>
            <p>Taurus</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Gemini" data-img = "{{asset('assets/img/GEMINI.png')}}" data-range="May 21 - Jun 21">
            <div class="zodiac-icon"><img src="{{asset('assets/img/GEMINI.png')}}" alt="">
            </div>
            <p>Gemini</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Cancer" data-img = "{{asset('assets/img/CANCER.png')}}" data-range="Jun 22 - Jul 22">
            <div class="zodiac-icon"><img src="{{asset('assets/img/CANCER.png')}}" alt="">
            </div>
             <p>Cancer</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Leo" data-img = "{{asset('assets/img/LEO.png')}}" data-range="Jul 23 - Aug 22">
            <div class="zodiac-icon"><img src="{{asset('assets/img/LEO.png')}}" alt="">
            </div>
            <p>Leo</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Virgo" data-img = "{{asset('assets/img/VIRGO.png')}}" data-range="Aug 23 - Sep 22">
            <div class="zodiac-icon"><img src="{{asset('assets/img/VIRGO.png')}}" alt="">
            </div>
            <p>Virgo</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Libra" data-img = "{{asset('assets/img/LIBRA.png')}}" data-range="Sep 23 - Oct 23">
            <div class="zodiac-icon"><img src="{{asset('assets/img/LIBRA.png')}}" alt="">
            </div>
            <p>Libra</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Scorpio" data-img = "{{asset('assets/img/SCORPIO.png')}}" data-range="Oct 24 - Nov 21">
            <div class="zodiac-icon"><img src="{{asset('assets/img/SCORPIO.png')}}" alt="">
            </div>
             <p>Scorpio</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Sagittarius" data-img = "{{asset('assets/img/SAGITTARIUS.png')}}" data-range="Nov 22 - Dec 21">
            <div class="zodiac-icon"><img src="{{asset('assets/img/SAGITTARIUS.png')}}" alt="">
            </div>
            <p>Sagittarius</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Capricorn" data-img = "{{asset('assets/img/CAPRICORN.png')}}" data-range="Dec 22 - Jan 19">
            <div class="zodiac-icon"><img src="{{asset('assets/img/CAPRICORN.png')}}" alt="">
            </div>
            <p>Capricorn</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Aquarius" data-img = "{{asset('assets/img/AQUARIUS.png')}}" data-range="Jan 20 - Feb 18">
            <div class="zodiac-icon"><img src="{{asset('assets/img/AQUARIUS.png')}}" alt="">
            </div>
           <p>Aquarius</p>
          </div>

          <div class="col-3 zodiac-item" data-sign="Pisces" data-img = "{{asset('assets/img/PISCES.png')}}" data-range="Feb 19 - Mar 20">
            <div class="zodiac-icon"><img src="{{asset('assets/img/PISCES.png')}}" alt="">
            </div>
            <p>Pisces</p>
          </div>

        </div>
      </div>
    </div>

    @php
    $currentZodiac = 'Aries';
      $timeFrameArray=[
        "yesterday"=>"Yesterday's Horoscope",
        "today"=>"Today's Horoscope",
        "tomorrow"=>"Tomorrow's Horoscope",
        "weekly"=>"Weekly Horoscope",
        "monthly"=>"Monthly Horoscope",
        "yearly"=>"Yearly Horoscope"
      ];
    @endphp

    <!-- RIGHT : HOROSCOPE SLIDER -->
    <div class="col-md-5">
      <div class="horoscope-box">
        <div class="slide active">
          <div class="d-flex pt-2">
            <div class="pv-img">
              <img id="zodiacImage" src="{{asset('assets/img/ARIES.png')}}" alt="">
            </div>
            <div class="" style="width:60%;">
              <h3 id="zodiacName" class="text-center">{{$currentZodiac}}</h3>
              <span id="zodiacRange" class="text-center">Mar 21 - Apr 19</span>
            </div>
          </div>
          <ul class="text-center">
            @foreach($timeFrameArray as $key => $value)
            <li>
              <a href="{{ route('horoscopedetail', ['zodiac' => $currentZodiac, 'timeFrame' => $key]) }}"
                 class="horoscope-link"
                 data-timeframe="{{ $key }}">
                {{ $value }}
              </a>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>
</section>
<section class="ax-connect-wrap">
  <div class="ax-connect-card">
    <p class="ax-connect-text">
      Connect with an Astrologer
    </p>
    <p>Get personalized guidance from expert astrologers</p>

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
 <!-- call-to-action-start -->
<section class="py-5">
  <div class="container">
    <h2 class="fw-semibold ho-heading">Does a Horoscope work?</h2>
    <p>Talking about the online horoscope predictions, we can say that the answer to this question is subjective. For those who believe and have correct information about their birth, they can come around to find accurate predictions in their horoscope.
<br>
Horoscopes are part of astrology, which is often regarded as an ancient science that relies on planetary movements and cosmic energy for predictions. These predictions are made explicitly for one using their birth details. Therefore, the likelihood of receiving vague or incorrect predictions is relatively low.</p>
<h2 class="fw-semibold ho-heading mt-3">Astrology Signs Horoscope: Sun, Moon, & Ascendant</h2>
<p>The Sun Sign, Moon Sign, and Ascendant come together to answer questions about a person’s personality, their purpose and future. Let’s read about these different signs of a free Horoscope online in detail:</p>
<div class="hor-content-box mt-3">
  <h2 class="fw-semibold ho-heading mt-3">1.Sun Sign</h2>
  <p>Western astrology uses sun signs to make online horoscope predictions for one’s future, with the sun constellation and solar ascendant serving as the main determinants. Sun signs are also referred to as zodiac signs.</p>
  <p>They are the cornerstone of Western Astrology and represent a person's personality. Zodiac signs also know their behaviour and others’ understanding of them.
</p>
<h2 class="fw-semibold ho-heading mt-3">2.Moon Sign</h2>
<p>In astrology, the Moon is the second-most significant planet. It deals with a person's feelings and emotions. The moon sign is essential when analysing a person's natal chart. The moon is a symbol of one's feelings and psychological burdens.</p>
<p>You can clearly see your emotions with the moon sign chart. The Moon's free horoscope at the time of marriage has received significant preference.
</p>
<h2 class="fw-semibold ho-heading mt-3">3.Ascendant Or Lagna in Astrology Signs</h2>
<p>The Ascendant is the degree of the zodiac sign that is highest on the eastern horizon at the time and place of one’s birth. Since there are 12 signs in the zodiac, the rising sign shifts on the Eastern horizon every two hours because the sky appears to move when seen from Earth.</p>
<p>Understanding the rising sign, Ascendant, or Lagna, makes it clear why certain individuals do not correspond to Sun or Moon signs. It represents one’s physical features and also their approach to life.
</p>
</div>

  </div>
</section>

@endsection
<!-- banner-section-end -->
@push('scripts')
<script>
  const slide = document.querySelector('.slide');

  document.querySelectorAll('.zodiac-item').forEach(item => {
    item.addEventListener('click', () => {

      // active icon
      document.querySelectorAll('.zodiac-item').forEach(el => el.classList.remove('active'));
      item.classList.add('active');

      // slide out
      slide.classList.add('slide-out');

      setTimeout(() => {
        slide.classList.remove('slide-out');
        slide.classList.add('slide-in');

        setTimeout(() => {
          slide.classList.remove('slide-in');
        }, 50);

      }, 400);

    });
  });

  $(document).on('click', '.zodiac-item', function () {

    let zodiac = $(this).data('sign');
    let img    = $(this).data('img');
    let range  = $(this).data('range');

    // UI update
    $('#zodiacName').text(zodiac);
    $('#zodiacImage').attr('src', img);
    $('#zodiacRange').text(range);

    // update links properly
    $('.horoscope-link').each(function () {
        let timeframe = $(this).data('timeframe');
        let url = `/horoscope/${zodiac}/${timeframe}/detail`;
        $(this).attr('href', url);
    });

    $('.zodiac-item').removeClass('active');
    $(this).addClass('active');
});


</script>
@endpush
