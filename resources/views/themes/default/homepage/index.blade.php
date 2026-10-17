@extends(themeView() . 'layout')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
@endpush

@section('content')
@php
  $user = auth()->guard('web')->user();
  $todayDate = date('Y-m-d');
@endphp
@php
  $heroAstrologer = isset($astrologers) && count($astrologers) > 0 ? $astrologers->first() : null;
@endphp
<!-- ============ HERO SECTION ============ -->
<section class="av-hero">
  <div class="av-hero-stars"></div>
  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center">
      <!-- LEFT: Hero Text -->
      <div class="col-lg-5 mb-4 mb-lg-0">
        <div class="av-hero-badge mb-3"><span>⭐</span> 2M+ Happy Users</div>
        <h1 class="av-hero-title">Your Life.<br>Written in the <span class="av-highlight">Stars.</span></h1>
        <p class="av-hero-subtitle">Get accurate predictions, personalized guidance and connect with verified astrologers.</p>
        
        <div class="av-trust-row">
          <div class="av-trust-item"><i class="fa fa-check-circle"></i> 100% Verified<br>Astrologers</div>
          <div class="av-trust-item"><i class="fa fa-lock"></i> Secure &<br>Private</div>
          <div class="av-trust-item"><i class="fa fa-headphones"></i> 24x7 Expert<br>Support</div>
          <div class="av-trust-item"><i class="fa fa-magic"></i> AI-Powered<br>Solutions</div>
        </div>

        <div class="d-flex gap-3 mt-4 flex-wrap">
          <a href="{{ route('astrologers.index') }}" class="av-btn-primary"><i class="fa fa-comments me-2"></i> Chat with Astrologers</a>
          <a href="{{ route('kundali.index') }}" class="av-btn-outline"><i class="fa fa-file-text-o me-2"></i> Generate Your Kundli</a>
        </div>
      </div>

      <!-- CENTER: Talk to Astrologer Card -->
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="av-astro-card">
          <div class="av-astro-card-header">
            <span>Talk to Astrologers</span>
            <span class="av-online-dot"><i class="fa fa-circle text-success"></i> Online</span>
          </div>
          @if($heroAstrologer)
          <div class="av-astro-card-body">
            <img src="{{ $heroAstrologer->image ? $heroAstrologer->image->image(300) : no_image('astrologer') }}" alt="{{ $heroAstrologer->display_name }}" class="av-astro-photo">
            <h5 class="mt-3 mb-1 fw-bold text-white">{{ $heroAstrologer->display_name }}</h5>
            <p class="text-white-50 mb-2 fs-13">{{ $heroAstrologer->year_exp }} Years Exp. &bull; {{ $heroAstrologer->my_expertise }}</p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-warning fw-bold"><i class="fa fa-star"></i> {{ $heroAstrologer->rating ?? '4.9' }} (12.5K)</span>
              <a href="{{ route('astrologers.publicview', [$heroAstrologer->type, $heroAstrologer->slug]) }}" class="av-call-btn"><i class="fa fa-phone"></i></a>
            </div>
          </div>
          @endif
        </div>
      </div>

      <!-- RIGHT: AI + Horoscope Cards -->
      <div class="col-lg-3">
        <div class="av-side-card mb-3">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h6 class="fw-bold text-white mb-2">AI Astrologer</h6>
              <p class="text-white-50 fs-13 mb-2">Ask Unlimited Questions</p>
              <a href="{{ route('astrologers.index', ['type' => 'ai']) }}" class="av-link">Get Instant Answers →</a>
            </div>
            <div class="av-ai-icon">🤖</div>
          </div>
        </div>
        <div class="av-side-card">
          <h6 class="fw-bold text-white mb-2">Daily Horoscope</h6>
          <p class="text-white-50 fs-13 mb-2">Know what the stars have in store for you</p>
          <a href="{{ route('horoscopes') }}" class="av-link">Read Now →</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ SERVICES STRIP ============ -->
<div class="container av-services-strip-wrap">
  <div class="av-services-strip">
    <a href="{{ route('kundali.index') }}" class="av-service-icon-item">
      <div class="av-icon-circle" style="background:#fff5e6;color:#ff9800;"><i class="fa fa-sun-o"></i></div>
      <span>Kundli</span>
    </a>
    <a href="{{ route('panchang') }}" class="av-service-icon-item">
      <div class="av-icon-circle" style="background:#e6ffe6;color:#4caf50;"><i class="fa fa-calendar"></i></div>
      <span>Panchang</span>
    </a>
    <a href="{{ route('horoscopes') }}" class="av-service-icon-item">
      <div class="av-icon-circle" style="background:#f0e6ff;color:#9c27b0;"><i class="fa fa-star-half-o"></i></div>
      <span>Horoscope</span>
    </a>
    <a href="{{ route('kundalimatching') }}" class="av-service-icon-item">
      <div class="av-icon-circle" style="background:#ffe6f0;color:#e91e63;"><i class="fa fa-heart"></i></div>
      <span>Matching</span>
    </a>
    <a href="{{ route('pages', 'puja') }}" class="av-service-icon-item">
      <div class="av-icon-circle" style="background:#fff0e0;color:#ff5722;"><i class="fa fa-fire"></i></div>
      <span>Puja</span>
    </a>
    <a href="{{ route('astrologers.index') }}" class="av-service-icon-item">
      <div class="av-icon-circle" style="background:#e0f7fa;color:#00bcd4;"><i class="fa fa-comments"></i></div>
      <span>Chat / Call</span>
    </a>
    <a href="{{ route('product.handle') }}" class="av-service-icon-item">
      <div class="av-icon-circle" style="background:#e8f5e9;color:#43a047;"><i class="fa fa-diamond"></i></div>
      <span>Gemstones</span>
    </a>
    <a href="{{ route('product.handle') }}" class="av-service-icon-item">
      <div class="av-icon-circle" style="background:#fff3e0;color:#ef6c00;"><i class="fa fa-shopping-bag"></i></div>
      <span>Store</span>
    </a>
  </div>
</div>

<!-- ============ OUR SERVICES GRID ============ -->
<section class="av-services-grid py-4" id="service-grid">
  <div class="container">
    <div class="av-services-heading">
      <h2><i class="fa fa-moon-o"></i> Our Services <span></span></h2>
      <a href="#service-grid">View All <i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="row g-3">
      <div class="col-md-6 col-lg-3">
        <a href="{{ route('kundali.index') }}" class="av-svc-card av-svc-orange">
          <div class="av-svc-content">
            <h5>Kundli</h5>
            <p>Generate your free Kundli and know about your life in details.</p>
            <span class="av-svc-link">Generate Now →</span>
          </div>
          <div class="av-svc-icon"><i class="fa fa-sun-o fa-2x"></i></div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="{{ route('panchang') }}" class="av-svc-card av-svc-green">
          <div class="av-svc-content">
            <h5>Panchang</h5>
            <p>Check Tithi, Nakshatra, Yog, Karana and Auspicious Muhurat.</p>
            <span class="av-svc-link">View Panchang →</span>
          </div>
          <div class="av-svc-icon"><i class="fa fa-calendar fa-2x"></i></div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="{{ route('horoscopes') }}" class="av-svc-card av-svc-purple">
          <div class="av-svc-content">
            <h5>Horoscope</h5>
            <p>Read daily, weekly, monthly & yearly horoscope predictions.</p>
            <span class="av-svc-link">Read Now →</span>
          </div>
          <div class="av-svc-icon"><i class="fa fa-star fa-2x"></i></div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="{{ route('kundalimatching') }}" class="av-svc-card av-svc-pink">
          <div class="av-svc-content">
            <h5>Marriage</h5>
            <p>Match your Kundli and check compatibility for relationships.</p>
            <span class="av-svc-link">Match Now →</span>
          </div>
          <div class="av-svc-icon"><i class="fa fa-heart fa-2x"></i></div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="{{ route('pages', 'puja') }}" class="av-svc-card av-svc-orange">
          <div class="av-svc-content">
            <h5>Puja</h5>
            <p>Book pujas for peace, prosperity and blessings.</p>
            <span class="av-svc-link">Book Puja →</span>
          </div>
          <div class="av-svc-icon"><i class="fa fa-fire fa-2x"></i></div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="{{ route('product.handle') }}" class="av-svc-card av-svc-green">
          <div class="av-svc-content">
            <h5>Gemstones</h5>
            <p>Certified gemstones for prosperity and protection.</p>
            <span class="av-svc-link">Shop Now →</span>
          </div>
          <div class="av-svc-icon"><i class="fa fa-diamond fa-2x"></i></div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="{{ route('product.handle') }}" class="av-svc-card av-svc-purple">
          <div class="av-svc-content">
            <h5>Store</h5>
            <p>Spiritual products, yantras, rudraksha and more.</p>
            <span class="av-svc-link">Explore Store →</span>
          </div>
          <div class="av-svc-icon"><i class="fa fa-shopping-bag fa-2x"></i></div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="{{ route('astrologers.index') }}" class="av-svc-card av-svc-pink">
          <div class="av-svc-content">
            <h5>Ask Question</h5>
            <p>Ask your question to our experts for free.</p>
            <span class="av-svc-link">Ask Now →</span>
          </div>
          <div class="av-svc-icon"><i class="fa fa-question-circle fa-2x"></i></div>
        </a>
      </div>
    </div>
  </div>
</section>


@if(isset($aiAstrologers) && count($aiAstrologers) > 0)
  <section class="sec_third mt-2 mt-md-0 av-home-ai-section">
    <div class="container">
      <div class="row align-items-center gx-0 mx-0 mb-3 me-lg-2">
        <div class="col-lg-10 col-sm-8 col-8">
          <div class="d-flex align-items-center">
          <i class="fa fa-robot me-2  "></i>  <h2 class=" ms-lg-0 ms-md-1 mb-0 me-2">Chat With AI Astrologers</h2>

          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-4 mb-2 mb-md-0">
          <a href="{{ route('astrologers.index', ['type' => 'ai']) }}">
            <button class="view_all_new d-block ms-auto">
              View All
              <!-- <i class="bi bi-caret-right-fill k_down"></i> -->
            </button>
          </a>
        </div>
      </div>
      <div class="kundli-slider-new talk_guru guru_main guru2 guru slick-s">
        @foreach($aiAstrologers as $index => $item)
          @php
            $aiAstrologerImage = $item->image;
            $aiAstrologeryName = $item->display_name;
            $aiAstrologeryChatPrice = $item->chat_price;
            $aiAstrologeryChatSpecialPrice = $item->chat_special_price;
          @endphp
          <div class="px-2 py-3">
          <a href="{{ $user ? route('astrologers.publicview', [$item->type, $item->slug]) : 'javascript:void(0)' }}" {{ $user ? '' : "onclick=handleUserLoginModal('open')" }} class="text-decoration-none text-dark">
            <div class="card av-vertical-card h-100 border-0 shadow-sm">
              <div class="position-relative">
                <img src="{{ $aiAstrologerImage ? $aiAstrologerImage->image(300) : no_image('astrologer') }}" alt="{{ $aiAstrologeryName }}" class="card-img-top av-card-img">
                <div class="av-status-badge">
                  <span class="badge bg-success rounded-pill px-2 py-1"><i class="fa fa-circle text-white" style="font-size: 8px;"></i> Online</span>
                </div>
              </div>
              <div class="card-body text-center p-3">
                <h6 class="fw-bold mb-1 text-truncate">{{ $aiAstrologeryName }}</h6>
                @if($item->badge && $item->badge->status == 1)
                  <p class="text-muted fs-12 mb-2 text-truncate">{{ $item->badge->name }}</p>
                @endif
                <div class="d-flex justify-content-center align-items-center mb-3">
                  @if($aiAstrologeryChatSpecialPrice > 0 && ($todayDate >= $item->sp_from_date) && ($todayDate <= $item->sp_to_date))
                    <span class="text-danger fw-bold fs-14">₹{{ $aiAstrologeryChatSpecialPrice }}/min</span>
                    <strike class="text-muted fs-12 ms-2">₹{{ $aiAstrologeryChatPrice }}</strike>
                  @else
                    <span class="text-danger fw-bold fs-14">₹{{ $aiAstrologeryChatPrice }}/min</span>
                  @endif
                </div>
                <button class="btn btn-primary w-100 rounded-pill fs-13 py-2 shadow-sm" style="background: var(--av-dark); border: none;"><i class="fa fa-commenting me-2"></i> Chat</button>
              </div>
            </div>
          </a>
        </div>
</a>
        @endforeach
      </div>
      <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-3">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
</div>
    </div>
  </section>
@endif

@if(isset($astrologers) && count($astrologers) > 0)
  <section class="instructors_bg mt-3 av-home-chat-section">
    <div class="container">
      <div class="row align-items-center gx-0 mx-0 mb-3 me-md-2">
        <div class="col-lg-10 col-sm-8 col-8">
          <div class="d-flex align-items-center">
          <i class="fa fa-commenting me-2"></i>  <h2 class="ms-lg-0 mb-0 ms-2 me-2">Chat With Astrologers</h2>
            <!-- <h2 class=" mb-0">Consult on Call, Chat, and Video</h2> -->

          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-4 mb-2 mb-md-0">
          <a href="{{ route('astrologers.chatwithastrologers') }}">
            <button class="view_all_new d-block ms-auto">
              View All
              <!-- <i class="bi bi-caret-right-fill k_down"></i> -->
            </button>
          </a>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-12 m-0">
          <div class="col-lg-12">
            <div class="kundli-slider-new talk_guru guru_main guru2 guru slick-s">
              @foreach($astrologers as $index => $item)
                @php
                  $astrologerImage = $item->image;
                  $astrologeryName = $item->display_name;
                  $astrologeryChatPrice = $item->chat_price;
                  $astrologeryChatSpecialPrice = $item->chat_special_price;
                @endphp
                <div class="px-2 py-3">
                <a href="{{ $user ? route('astrologers.publicview', [$item->type, $item->slug]) : 'javascript:void(0)' }}" {{ $user ? '' : "onclick=handleUserLoginModal('open')" }} class="text-decoration-none text-dark">
                  <div class="card av-vertical-card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                      <img src="{{ $astrologerImage ? $astrologerImage->image(300) : no_image('astrologer') }}" alt="{{ $astrologeryName }}" class="card-img-top av-card-img">
                      <div class="av-status-badge">
                        @if($item->chat == 1)
                          <span class="badge bg-success rounded-pill px-2 py-1"><i class="fa fa-circle text-white" style="font-size: 8px;"></i> Online</span>
                        @elseif($item->chat == 3)
                          <span class="badge bg-danger rounded-pill px-2 py-1"><i class="fa fa-clock-o text-white"></i> Busy</span>
                        @else
                          <span class="badge bg-secondary rounded-pill px-2 py-1">Offline</span>
                        @endif
                      </div>
                    </div>
                    <div class="card-body text-center p-3">
                      <h6 class="fw-bold mb-1 text-truncate">{{ $astrologeryName }}</h6>
                      @if($item->badge && $item->badge->status == 1)
                        <p class="text-muted fs-12 mb-2 text-truncate">{{ $item->badge->name }}</p>
                      @endif
                      <div class="d-flex justify-content-center align-items-center mb-1">
                        <span class="text-warning fw-bold fs-13"><i class="fa fa-star"></i> {{ $item->rating ?? '4.8' }}</span>
                      </div>
                      <div class="d-flex justify-content-center align-items-center mb-3">
                        @if($astrologeryChatSpecialPrice > 0 && ($todayDate >= $item->sp_from_date) && ($todayDate <= $item->sp_to_date))
                          <span class="text-danger fw-bold fs-14">₹{{ $astrologeryChatSpecialPrice }}/min</span>
                          <strike class="text-muted fs-12 ms-2">₹{{ $astrologeryChatPrice }}</strike>
                        @else
                          <span class="text-danger fw-bold fs-14">₹{{ $astrologeryChatPrice }}/min</span>
                        @endif
                      </div>
                      <button class="btn btn-primary w-100 rounded-pill fs-13 py-2 shadow-sm" style="background: var(--av-dark); border: none;"><i class="fa fa-commenting me-2"></i> Chat</button>
                    </div>
                  </div>
                </a>
              </div>
</a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-3">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
</div>
    </div>
</section>
@endif

@if(isset($talkAstrologers) && count($talkAstrologers) > 0)
  <section class="instructors_bg mt-3 av-home-chat-section av-home-talk-section">
    <div class="container">
      <div class="row align-items-center gx-0 mx-0 mb-3">
        <div class="col-lg-10 col-sm-8 col-8">
          <div class="d-flex align-items-center">
            <i class="fa fa-volume-control-phone me-2"></i> <h2 class="mb-0 ms-md-1 ms-lg-0 me-2">Talk To Astrologers</h2>


          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-4 mb-2 mb-md-0">
          <a href="{{ route('astrologers.talktoastrologers') }}">
            <button class="view_all_new d-block ms-auto">
              View All
              <!-- <i class="bi bi-caret-right-fill k_down"></i> -->
            </button>
          </a>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-12 m-0 mx-0">
          <div class="col-lg-12">
            <div class="kundli-slider-new talk_guru guru_main guru2 guru slick-s">
              @foreach($talkAstrologers as $index => $item)
                @php
                  $astrologerImage = $item->image;
                  $astrologeryName = $item->display_name;
                  $astrologeryChatPrice = $item->call_price;
                  $astrologeryChatSpecialPrice = $item->call_special_price;
                @endphp
                <div class="px-2 py-3">
                <a href="{{ $user ? route('astrologers.publicview', [$item->type, $item->slug]) : 'javascript:void(0)' }}" {{ $user ? '' : "onclick=handleUserLoginModal('open')" }} class="text-decoration-none text-dark">
                  <div class="card av-vertical-card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                      <img src="{{ $astrologerImage ? $astrologerImage->image(300) : no_image('astrologer') }}" alt="{{ $astrologeryName }}" class="card-img-top av-card-img">
                      <div class="av-status-badge">
                        @if($item->call == 1)
                          <span class="badge bg-success rounded-pill px-2 py-1"><i class="fa fa-circle text-white" style="font-size: 8px;"></i> Online</span>
                        @elseif($item->call == 3)
                          <span class="badge bg-danger rounded-pill px-2 py-1"><i class="fa fa-clock-o text-white"></i> Busy</span>
                        @else
                          <span class="badge bg-secondary rounded-pill px-2 py-1">Offline</span>
                        @endif
                      </div>
                    </div>
                    <div class="card-body text-center p-3">
                      <h6 class="fw-bold mb-1 text-truncate">{{ $astrologeryName }}</h6>
                      @if($item->badge && $item->badge->status == 1)
                        <p class="text-muted fs-12 mb-2 text-truncate">{{ $item->badge->name }}</p>
                      @endif
                      <div class="d-flex justify-content-center align-items-center mb-1">
                        <span class="text-warning fw-bold fs-13"><i class="fa fa-star"></i> {{ $item->rating ?? '4.8' }}</span>
                      </div>
                      <div class="d-flex justify-content-center align-items-center mb-3">
                        @if($astrologeryChatSpecialPrice > 0 && ($todayDate >= $item->sp_from_date) && ($todayDate <= $item->sp_to_date))
                          <span class="text-danger fw-bold fs-14">₹{{ $astrologeryChatSpecialPrice }}/min</span>
                          <strike class="text-muted fs-12 ms-2">₹{{ $astrologeryChatPrice }}</strike>
                        @else
                          <span class="text-danger fw-bold fs-14">₹{{ $astrologeryChatPrice }}/min</span>
                        @endif
                      </div>
                      <button class="btn btn-primary w-100 rounded-pill fs-13 py-2 shadow-sm" style="background: var(--av-dark); border: none;"><i class="fa fa-phone me-2"></i> Call</button>
                    </div>
                  </div>
                </a>
              </div>
</a>
              @endforeach
            </div>

          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-3">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
</div>
    </div>
</section>
@endif

@if(isset($productCategories) && count($productCategories) > 0)
  <section class="sec_third mt-5 mt-lg-3 av-home-standard-section av-home-mall-section">
    <div class="container">
      <div class="row align-items-center gx-0 mx-0 mb-3 pe-md-2">
        <div class="col-lg-10 col-sm-8 col-8">
          <div class="d-flex align-items-center">
            <i class="fa fa-shopping-bag me-2"></i>
 <h2 class="mb-0 ms-md-1 ms-lg-0 me-2">Astro Mall</h2>


          </div>
        </div>

        <div class="col-lg-2 col-sm-4 col-4 mb-2 mb-md-0">
          <a href="{{ route('product.handle') }}">
            <button class="view_all_new d-block ms-auto">
              View All
              <!-- <i class="bi bi-caret-right-fill k_down"></i> -->
            </button>
          </a>
        </div>
      </div>
      <div class="kundli-slider mx-lg-1">
        @foreach($productCategories as $index => $item)
          @php
            $productCategoryImage = $item->image;
            $productCategoryName = $item->name;
          @endphp
          <a href="{{ route('product.handle', [$item->slug]) }}">
            <div class="card h-100 border-0">
              <img src="{{ $productCategoryImage ? $productCategoryImage->image() : no_image() }}" alt="{{ $productCategoryName }}" class="card-img-top radious-8 hh">
              <div class="card-body text-center">
                <span class="card-title fs11">{{ $productCategoryName }}</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>
      <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-4">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
</div>
    </div>
  </section>
@endif

@if(isset($serviceProducts) && count($serviceProducts) > 0)
  <section class="sec_third mt-5 mt-lg-3 av-home-standard-section av-home-reports-section">
    <div class="container">

      <div class="row align-items-center gx-0 mx-0 mb-3 pe-md-2">
        <div class="col-lg-10 col-sm-8 col-8">
          <div class="d-flex align-items-center">
            <i class="fa fa-file-text-o me-2"></i>
 <h2 class="mb-0 ms-md-1 ms-lg-0 me-2">Reports</h2>


          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-4 mb-2 mb-md-0">
          <a href="{{ route('service.handle') }}">
            <button class="view_all_new d-block ms-auto">
              View All
              <!-- <i class="bi bi-caret-right-fill k_down"></i> -->
            </button>
          </a>
        </div>
      </div>
      <div class="kundli-slider mx-lg-1">
        @foreach($serviceProducts as $index => $item)
          @include(themeView()."product.cardservice", [
            "product" => $item,
            "user" => $user,
            "productTypeName" => "Service",
            "productTypeValue" => 3
          ])
        @endforeach
      </div>
      <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-5">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
</div>
    </div>
  </section>
@endif

@if(isset($poojaProducts) && count($poojaProducts) > 0)
  <section class="mt-lg-3 pb-lg-0 mt-5 pb-0 av-home-standard-section av-home-pooja-section">
    <div class="container">

      <div class="row align-items-center gx-0 mx-0 mb-3 pe-md-2">
        <div class="col-lg-10 col-sm-8 col-8">
          <div class="d-flex align-items-center">
            <i class="fa fa-calendar-check-o me-2"></i>
 <h2 class="mb-0 ms-md-1 ms-lg-0 me-2">Book a Pooja</h2>


          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-4 mb-2 mb-md-0">
          <a href="{{ route('puja.handle') }}">
            <button class="view_all_new d-block ms-auto">
              View All
              <!-- <i class="bi bi-caret-right-fill k_down"></i> -->
            </button>
          </a>
        </div>
      </div>
      <div class="kundli-slider mx-lg-1">
        @foreach($poojaProducts as $index => $item)
          @include(themeView()."product.cardpuja", [
            "product" => $item,
            "user" => $user,
            "productTypeName" => "Service",
            "productTypeValue" => 3
          ])
        @endforeach
      </div>
      <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-4">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
</div>

    </div>
  </section>
@endif

@if(isset($articles) && count($articles) > 0)
  <section class="instructors_bg mb-2 mt-3 av-home-standard-section av-home-blog-section">
    <div class="container">

      <div class="row align-items-center gx-0 mx-0 mb-3 mx-lg-1">
        <div class="col-lg-10 col-sm-8 col-8">
          <div class="d-flex align-items-center">
            <i class="fa fa-pencil-square-o me-2"></i>
 <h2 class="mb-0 ms-md-1 ms-lg-0 me-2">Latest Blog</h2>


          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-4 mb-2 mb-md-0">
          <a href="{{ route('blog.index') }}">
            <button class="view_all_new d-block ms-auto">
              View All
              <!-- <i class="bi bi-caret-right-fill k_down"></i> -->
            </button>
          </a>
        </div>
      </div>
      <div class="blogs align-items-center mx-0">
        <div class="p-0 row">
          <div class="articles-slider">
              @foreach($articles as $article)
                  @php
                      $articleCategory = $article->category;
                      $articleImage = $article->image;
                      $articleTitle = $article->title;
                      $postedOnSeconds = strtotime($article->posted_on);
                  @endphp
                  <div class="slider-item">
                    <!-- slick item wrapper -->
                      <a href="{{ route('blog.post', [$articleCategory ? $articleCategory->slug : 'all', $article->slug]) }}">
                          <div class="card h-100 blg_card" style="box-shadow: none;">
                              <img src="{{ $articleImage ? $articleImage->image(390, 200) : no_image() }}" class="card-img-top" alt="{{ $articleTitle }}">
                              <div class="card-body d-flex flex-column p-0">
                                  <div class="strip">
                                      <h6><span class="fw-semibold">{{ date("d", $postedOnSeconds) }}</span><br>{{ date("M", $postedOnSeconds) }}</h6>
                                  </div>
                                  <h5 class="fw-semibold card-title text-truncate mt-1 ps-2 fs-16 text-black">{{ $articleTitle }}</h5>
                                  <small class="mb-p">{!! $article->short_description !!}</small>
                              </div>
                          </div>
                      </a>
                  </div>
              @endforeach
          </div>

  </div>

      </div>
      <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-4">
        <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
      </div>
    </div>
  </section>
@endif
@include(themeView().'common.videoTestimonial')
@include(themeView().'common.textTestimonial')
@include(themeView().'common.adpopup', ['page'=>'home'])
@endsection
@push('scripts')

<script type="text/javascript">
$('.bannerslider').slick({
  dots:false,
  infinite: true,
  speed: 300,
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay:true,
  autoplaySpeed: 2000,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        dots:false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots:false
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
          dots:false
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
          dots:false
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
</script>
@endpush
