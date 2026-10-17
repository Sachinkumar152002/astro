<style media="screen">
.icon-bg {
  width: 52px;
  height: 52px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 10px;
}
.icon-bg i{
  font-size: 18px;
}
.icon-bg-1{
  background:#dca5a3;

}

.icon-bg-2{
  background:#c9b47b;

}
.icon-bg-3{
  background:#19191ad6;

}
.icon-bg-4{
  background:#93a5b2;

}
.icon-bg-5{
  background:green;

}
.icon-bg-6{
  background:#9e6ed4;

}
.icon-bg-7{
  background:#ca8947;

}

.icon-white {
  color: #fff;
  font-size: 22px;
}

</style>

@extends(themeView().'layout')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/horoscopes-ui.css') }}?v={{ filemtime(public_path('assets/css/horoscopes-ui.css')) }}">
@endpush


@php
  $zodiacSignName=$zodiacSign->name;
  $timeFrameArray=[
    "today"=>"Today's Horoscope",
    "tomorrow"=>"Tomorrow's Horoscope",
    "yesterday"=>"Yesterday's Horoscope",
    "weekly"=>"Weekly Horoscope",
    "monthly"=>"Monthly Horoscope",
    "yearly"=>"Yearly Horoscope"
  ];
@endphp

@section('title', "$zodiacSignName Horoscope | ASTROVYRA")
@section('keywords', "$zodiacSignName Horoscope | ASTROVYRA")
@section('description', "$zodiacSignName Horoscope | ASTROVYRA")

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"{{ $zodiacSignName }} Horoscope Deatil",
    "description":"Read your daily, weekly, and monthly horoscope with accurate zodiac predictions.",
    "inLanguage":"en-IN",
    "isPartOf":{
      "@id":"{{ url('/') }}/#website"
    },
    "about":{
      "@id":"{{ url('/') }}/#org"
    },
    "datePublished":"{{ date('Y-m-d',strtotime($zodiacSign->created_at)) }}",
    "dateModified":"{{ date('Y-m-d',strtotime($zodiacSign->updated_at)) }}"
  }
  </script>
@endpush

<!-- banner-section-start -->
@section('content')
<!-- Daily Horoscopes-section-start -->
<section class="horoscope-detail-page">
  <div class="container">
  <div class="px-3 px-lg-0">
    <div class="horoscope-detail-hero">
      <div class="horoscope-detail-sign"><img src="{{ asset('assets/img/'.strtoupper($zodiacSignName).'.png') }}" alt="{{ $zodiacSignName }} zodiac sign"></div>
      <div>
        <span class="horoscope-detail-eyebrow"><i class="bi bi-stars"></i> Your cosmic forecast</span>
        <h1>{{ $zodiacSignName }} {{ $timeFrameArray[$timeFrame] ?? 'Horoscope' }}</h1>
        <p>{{ $horoscopeDate }} <span aria-hidden="true">&bull;</span> Discover what the stars have in store for you.</p>
      </div>
    </div>
    <div class="zodiac-row">

      <a href="{{ route('horoscopedetail', ['Aries','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/ARIES.png')}}" />
      </div>
      <div class="zodiac-name">Aries</div>
      </div>
    </a>

      <a href="{{ route('horoscopedetail', ['Taurus','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/TAURUS.png')}}" />
      </div>
      <div class="zodiac-name">Taurus</div>
      </div>
    </a>

      <a href="{{ route('horoscopedetail', ['Gemini','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/GEMINI.png')}}" />
      </div>
      <div class="zodiac-name">Gemini</div>
      </div>
    </a>
      <a href="{{ route('horoscopedetail', ['Cancer','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/CANCER.png')}}" />
      </div>
      <div class="zodiac-name">Cancer</div>
      </div>
    </a>

      <a href="{{ route('horoscopedetail', ['Leo','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/LEO.png')}}" />
      </div>
      <div class="zodiac-name">Leo</div>
      </div>
    </a>

    <a href="{{ route('horoscopedetail', ['Virgo','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/VIRGO.png')}}" />
      </div>
      <div class="zodiac-name">Virgo</div>
      </div>
    </a>

    <a href="{{ route('horoscopedetail', ['Libra','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/LIBRA.png')}}" />
      </div>
      <div class="zodiac-name">Libra</div>
      </div>
    </a>

    <a href="{{ route('horoscopedetail', ['Scorpio','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/SCORPIO.png')}}" />
      </div>
      <div class="zodiac-name">Scorpio</div>
      </div>
    </a>

    <a href="{{ route('horoscopedetail', ['Sagittarius','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/SAGITTARIUS.png')}}" />
      </div>
      <div class="zodiac-name">Sagittarius</div>
      </div>
    </a>

    <a href="{{ route('horoscopedetail', ['Capricorn','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/CAPRICORN.png')}}" />
      </div>
      <div class="zodiac-name">Capricorn</div>
      </div>
    </a>
    <a href="{{ route('horoscopedetail', ['Aquarius','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/AQUARIUS.png')}}" />
      </div>
      <div class="zodiac-name">Aquarius</div>
      </div>
    </a>
    <a href="{{ route('horoscopedetail', ['Pisces','today']) }}">
      <div class="zodiac-item">
      <div class="zodiac-circle">
      <img src="{{asset('assets/img/PISCES.png')}}" />
      </div>
      <div class="zodiac-name">Pisces</div>
      </div>
    </a>
      </div>
  </div>
    <!-- <h2 class="text-center">{{ $zodiacSignName }} {{ ucfirst($timeFrame) }} Horoscope</h2>
    <p class="text-center fw-bold text-green">{{ $horoscopeDate }}</p> -->
    <div class="hor-bor-b mx-0 mt-4 mb-0">

    </div>
    <div class="row mt-2 mt-lg-3">
      <div class="col-md-12 col-12">
        <div class="row">
          <div class="col-md-4 d-none">
            <div class="form-group">
              <label for="zodiacSigns" class="h-label fw-semibold">Select Other Sign</label>
              <select class="form-control mt-3 hor-s" name="zodiac" id="zodiacSigns" onchange="handleZodiacSign(this)">
                @foreach(zodiacSigns() as $key => $value)
                  <option value="{{ $value['name'] }}" {{ $zodiacSignName==$value['name']?"selected":"" }} data-url="{{ route('horoscopedetail',[$value['name'],'today']) }}">{{ $value['name'] }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <!-- <label for="zodiacSigns" class="fw-semibold mt-3 h-label">Horoscopes:</label> -->
            <ul class="mt-3 hor-list d-md-flex justify-content-between px-0">
            @foreach($timeFrameArray as $key => $value)
              <li class="hor-tab {{ $key == $timeFrame ? 'active' : '' }}">
                <a href="{{ route('horoscopedetail', [$zodiacSignName, $key]) }}">
                  {{ $value }}
                </a>
              </li>
            @endforeach
          </ul>

          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="">
          <!-- <div class="mb-4 mb-lg-0">
            <h4 class="mt-4 mt-lg-0">{{ $zodiacSignName }} {{ ucfirst($timeFrame) }} Horoscope</h4>
            <span class="text-green fw-bold">{{ $horoscopeDate }}</span>
          </div> -->

          <div class=" my-4">

            <h2 class="fw-bold mb-3 horoscope-detail-section-title">Your Lucky Factors</h2>

            <div class="row g-3">

              <!-- Colours -->
              <div class="col-md-4">
                <div class="lucky-card lucky-colors">
                  <h5>Colours</h5>
                  <div class="color-dots">
                    <span class="dot purple"></span>
                    <span class="dot orange"></span>
                  </div>
                </div>
              </div>

              <!-- Numbers -->
              <div class="col-md-4">
                <div class="lucky-card lucky-numbers">
                  <h5>Numbers</h5>
                  <p>5, 11, 22</p>
                </div>
              </div>

              <!-- Alphabets -->
              <div class="col-md-4">
                <div class="lucky-card lucky-alphabets">
                  <h5>Alphabets</h5>
                  <p>A, D</p>
                </div>
              </div>

            </div>

          </div>
          <div class="my-4">
          <div class="d-flex flex-wrap gap-3 justify-content-between aspect-tabs">

            <a href="#love"><div class="aspect-pill love">
              <i class="bi bi-heart-fill"></i> Love
            </div></a>

            <a href="#health">
              <div class="aspect-pill health">
                <i class="bi bi-plus-circle-fill"></i> Health
              </div>
            </a>

          <a href="#career">
            <div class="aspect-pill career">
              <i class="bi bi-briefcase-fill"></i> Career
            </div>
          </a>

          <a href="#money">
            <div class="aspect-pill emotions">
              <i class="bi bi-coin"></i> Money
            </div>
          </a>
          <a href="#family">
            <div class="aspect-pill family">
              <i class="bi bi-person-hearts"></i> Family
            </div>
          </a>

            <a href="#travel">
              <div class="aspect-pill travel">
                <i class="bi bi-airplane-fill"></i> Travel
              </div>
            </a>

          </div>
        </div>
          @if(isset($data["bot_response"]))
            @php
              $bot_response=$data["bot_response"];
            @endphp
            @if(isset($bot_response["relationship"]))
            <div class="hors-b hor-detail-1 hor-detail">

            <div class="hor-ic icon-bg icon-bg-1" id="love">
            <i class="bi bi-heart-fill icon-white"></i>
            </div>

            <h6>Love and Relationships</h6>
            <p>
            {{ $bot_response["relationship"]["split_response"] }}
            </p>

            </div>

            @endif
            @if(isset($bot_response["health"]) || isset($bot_response["physique"]))
              <div class="hors-b hor-detail-2 hor-detail" id="health">

                <div class="hor-ic icon-bg icon-bg-2">

                    <i class="bi bi-hospital-fill" style="color:white;"></i></div>
                    <h6> Health and Wellness</h6>

                  <p>

                  @if(isset($bot_response["health"]))
                    {{ $bot_response["health"]["split_response"] }}
                  @endif
                  <br>
                  @if(isset($bot_response["physique"]))
                    {{ $bot_response["physique"]["split_response"] }}
                  @endif
                </p>
              </div>
            @endif
            @if(isset($bot_response["career"]))
              <div class="hors-b hor-detail-3 hor-detail" id="career">
                <div class="hor-ic icon-bg icon-bg-3">

                <i class="fa fa-graduation-cap thick-icon" style="color:white;"></i></div>
                <h6> Career and Education</h6>


                <p>
                  {{ $bot_response["career"]["split_response"] }}
                </p>
              </div>
            @endif
            @if(isset($bot_response["finances"]))
              <div class="hors-b hor-detail-4 hor-detail" id="money">
                <div class="hor-ic icon-bg icon-bg-4">

                <i class="bi bi-currency-rupee" style="color:white;"></i>
              </div>
                 <h6>Money and Finances</h6>


                <p>  {{ $bot_response["finances"]["split_response"] }}
                </p>
              </div>
            @endif
            @if(isset($bot_response["family"]) || isset($bot_response["friends"]))
              <div class="hors-b hor-detail-5 hor-detail" id="family">
                <div class="hor-ic icon-bg icon-bg-5">

                <i class="bi bi-people-fill" style="color:white;"></i>
              </div>
                <h6> Family and Friends</h6>

                  <p>

                  @if(isset($bot_response["family"]))
                    {{ $bot_response["family"]["split_response"] }}
                  @endif
                  <br>
                  @if(isset($bot_response["friends"]))
                    {{ $bot_response["friends"]["split_response"] }}
                  @endif
                </p>
              </div>
            @endif
            @if(isset($bot_response["total_score"]))
              <div class="hors-b hor-detail-6 hor-detail" id="travel">
                <div class="hor-ic icon-bg icon-bg-6">

                <i class="bi bi-lightbulb-fill" style="color:white;"></i>
              </div>
                 <h6>Tip of the Month for the Year:</h6>
                  <p>

                  {{ $bot_response["total_score"]["split_response"] }}
                </p>
              </div>
            @endif
            <div class="hors-b hor-detail-7 hor-detail">
              <div class="hor-ic icon-bg icon-bg-7">

              <i class="fa fa-magic" style="color:white;"></i>
            </div>

            <h6>
              Wishing you a fantastic and fulfilling {{ date("Y") }}, {{ $zodiacSignName }}! ✨
            </h6>

            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Daily Horoscopes-section-end -->
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
<script type="text/javascript">
  function handleZodiacSign(that){
    let url = $(that).find("option:selected").attr("data-url");
    window.location.href=url;
  }
</script>
@endpush
