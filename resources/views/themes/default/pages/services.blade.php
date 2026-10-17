@extends(themeView().'layout')

@section('title', "Services | MoonAstro.AI")
@section('keywords', "Services | MoonAstro.AI")
@section('description', "Services | MoonAstro.AI")

<!-- banner-section-start -->
@section('content')
<section class="page-header about-page mb-md-0" style="background-image: url('https://moon-astro.dixinfotech.in/media/5/1752058165.png'); background-repeat: no-repeat; width: 100%; background-size: cover;">

    <div class="container">

  <div class="head-sec">
    <h1 class="text-center text-white">Services</h1>
    <p class="text-center text-white">Discover the secrets of the stars with our personalized astrology readings. Explore your zodiac sign, daily horoscopes, and celestial insights. Unlock the mysteries of the universe and find your true path.</p>
  </div>
</div>
</section>

<!-- Service-card-start -->
<section class="sec_third pt-0 pt-lg-5">
  <div class="container ps-0">
    <div class="row align-items-center gx-0 mx-2 pb">

      <div class="col-lg-12 col-sm-12">
        <div class="heading_main pb-0 mb-0">
        <h2>Astrology Services</h2>
        <p class="pb-0 mb-0">Discover the secrets of the stars with our personalized astrology readings. Explore your zodiac sign, daily horoscopes, and celestial insights. Unlock the mysteries of the universe and find your true path.</p>
      </div>
      </div>
    </div>
    <div class="row align-items-center mx-1 d-none">
        <div class="astro">

          <div class="col-lg-3 vc-card-2">

                <div class="card me-0 ser-c">
                  <img src="{{asset('assets/img/s-1.png')}}" alt="" class="ser-img">
                  <div class="card-body text-center">
                    <p class="card-title">Astrologer Consultation</p>
                    <p>Unlock celestial guidance for your destiny. Gain insights and clarity on life’s journey through expert astrology services tailored just...</p>
                  </div>
              </div>

          </div>

          <div class="col-lg-3 vc-card-2">

                <div class="card me-0 ser-c vcc">
                  <img src="{{asset('assets/img/s-2.png')}}" alt="" class="ser-img">
                  <div class="card-body text-center">
                    <p class="card-title">Kundli Report</p>
                    <p>Unlock celestial guidance for your destiny. Gain insights and clarity on life’s journey through expert astrology services tailored just...</p>
                  </div>
              </div>

          </div>
          <div class="col-lg-3 vc-card-2">

                <div class="card me-0 ser-c">
                  <img src="{{asset('assets/img/s-3.png')}}" alt="" class="ser-img">
                  <div class="card-body text-center">
                    <p class="card-title">Numerology</p>
                    <p>Unlock celestial guidance for your destiny. Gain insights and clarity on life’s journey through expert astrology services tailored just...</p>
                  </div>
              </div>

          </div>
          <div class="col-lg-3 vc-card-2">

                <div class="card  ser-c vcc">
                  <img src="{{asset('assets/img/s-4.png')}}" alt="" class="ser-img">
                  <div class="card-body text-center">
                    <p class="card-title">Palmistry</p>
                    <p>Unlock celestial guidance for your destiny. Gain insights and clarity on life’s journey through expert astrology services tailored just...</p>
                  </div>
              </div>

          </div>

          </div>
    </div>
  </div>
</section>
<!-- Service-card-end -->
<section class="work-culture-section pb-3 pb-md-5">
  <div class="container">
    <div class="row align-items-center">
  <!-- Right: Content -->
      <div class="col-md-6">
        <h2 class="section-title">Astrology</h2>
        <p class="section-text">
        Moon Astro follows this traditional system of Vedic astrology, offering insights based on planetary alignments and astral patterns. By interpreting your birth chart (Kundli), we help you decode your life path, strengths, challenges, and opportunities, as written in the stars.
        </p>
          <a href="{{ route('contactForm') }}">    <button type="button" name="button" class=" btn ab-btn mt-md-4 mt-2 mb-3 mb-md-0">Contact Us <i class="bi bi-arrow-right ms-2"></i></button></a>
      </div>
      <!-- Left: Image -->
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="{{ asset('assets/img/work-2.jpg') }}" alt="Our Work Culture" class="img-fluid rounded shadow-sm">
      </div>
    </div>
  </div>
</section>
<!-- call-to-action-start -->
<section class="work-culture-section py-5 bg-c">
  <div class="container">
    <div class="row align-items-center">



      <!-- Left: Image -->
      <div class="col-md-6 mb-4 mb-md-0 order-2 order-lg-1">
        <img src="{{ asset('assets/img/ser-2.jpg') }}" alt="Our Work Culture" class="img-fluid rounded shadow-sm">
      </div>
      <!-- Right: Content -->
      <div class="col-md-6 order-1 order-lg-2">
        <h2 class="section-title">Numerology</h2>
        <p>Numerology is an ancient metaphysical science that explores the deep and mystical connection between numbers and the events unfolding in your life. According to renowned astrologers in Kolkata, this divine system believes that numbers form a universal language — one that reveals the truths and patterns hidden within your name and date of birth.</p>
  <a href="{{ route('contactForm') }}">    <button type="button" name="button" class="btn ab-btn mt-md-4 mt-2 mb-3 mb-md-0">Contact Us <i class="bi bi-arrow-right ms-2"></i></button></a>
      </div>

    </div>
  </div>
</section>

<section class="work-culture-section py-5">
  <div class="container">
    <div class="row align-items-center">
<!-- Right: Content -->
      <div class="col-md-6">
        <h2 class="section-title">Palmistry</h2>
        <p class="section-text">
          Palmistry is the art of reading the lines, shapes, and mounts on the palm, revealing insights about a person’s character, past experiences, present mindset, and future possibilities. It is truly remarkable how the human hand becomes a map — etched with signs that tell the story of one’s life journey.


        </p>
  <a href="{{ route('contactForm') }}">    <button type="button" name="button" class=" btn ab-btn mt-md-4 mt-2 mb-3 mb-md-0">Contact Us <i class="bi bi-arrow-right ms-2"></i></button></a>
      </div>
      <!-- Left: Image -->
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="{{ asset('assets/img/ser-n.jpg') }}" alt="Our Work Culture" class="img-fluid rounded shadow-sm">
      </div>
    </div>
  </div>
</section>

<section class="work-culture-section py-5 bg-c">
  <div class="container">
    <div class="row align-items-center">



      <!-- Left: Image -->
      <div class="col-md-6 mb-4 mb-md-0 order-2 order-lg-1">
        <img src="{{ asset('assets/img/ser-4.jpg') }}" alt="Our Work Culture" class="img-fluid rounded shadow-sm">
      </div>
      <!-- Right: Content -->
      <div class="col-md-6 order-1 order-lg-2">
        <h2 class="section-title">Tantra</h2>
        <p class="section-text">
          The core purpose behind offering Tantra services is to bring harmony and balance — helping individuals regain control over their circumstances, relationships, and mental well-being. When performed with the right intentions and under expert guidance, Tantra becomes a powerful tool to channel energy, influence thoughts, and align the body and mind with specific outcomes.




        </p>
  <a href="{{ route('contactForm') }}">    <button type="button" name="button" class=" btn ab-btn mt-md-4 mt-2 mb-3 mb-md-0">Contact Us <i class="bi bi-arrow-right ms-2"></i></button></a>
      </div>

    </div>
  </div>
</section>
<section class="call-bg py-5">
  <div class="container">
<div class="row d-flex align-items-center">
  <div class="col-md-8 col-12">
    <div class="sec-call-box">
      <h3>If You Would Like to Our Service to Download App</h3>

      <div class="d-flex flex-wrap m-4 ms-0 justify-content-md-start justify-content-center">


        <!-- iOS App -->
        <a href="#"><div class="d-flex align-items-center mb-2 k-logo-b1">
          <img src="https://moon-astro.dixinfotech.in/assets/img/apple-logo.png" alt="iOS App" style="height: 40px;" class="me-2">
          <div class="text-white">
            <h6 class="mb-0">Download the App</h6>
            <small>App Store</small>
          </div>
        </div></a>
        <!-- Android App -->
      <a href="#">
        <div class="d-flex align-items-center ms-0 ms-lg-4 mt-4 mt-md-0 mb-2 k-logo-b2 bg-black border-0">
          <img src="https://moon-astro.dixinfotech.in/assets/img/google-play.png" alt="Android App" style="height: 40px;" class="me-2">
          <div class="text-white">
            <h6 class="mb-0">Download the App</h6>
            <small>Google Play</small>
          </div>
        </div>
      </a>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-12 d-flex justify-content-center d-md-block mt-4 mt-md-0">
    <img src="{{asset('assets/img/call-back-p.png')}}" alt="">
  </div>
</div>
  </div>
</section>
<!-- call-to-action-end -->

@endsection
<!-- banner-section-end -->
