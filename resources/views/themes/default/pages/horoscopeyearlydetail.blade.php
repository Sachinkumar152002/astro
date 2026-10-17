@extends(themeView().'layout')

@section('title', "Horoscope | MoonAstro.AI")
@section('keywords', "Horoscope | MoonAstro.AI")
@section('description', "Horoscope | MoonAstro.AI")

<!-- banner-section-start -->
@section('content')
<!-- Daily Horoscopes-section-start -->
<section class="py-5">
  <div class="container">
    @php
      $zodiacSignName=$zodiacSign->name;
      $timeFrameArray=[
        "today"=>"Today's Horoscope",
        "tomorrow"=>"Tomorrow's Horoscope",
        "yesterday"=>"Yesterday's Horoscope",
        "weekly"=>"Weekly Horoscope",
        "yearly"=>"Yearly Horoscope"
      ];
    @endphp
    <h2 class="text-center">{{ $zodiacSignName }} {{ ucfirst($timeFrame) }} Horoscope</h2>
    <p class="text-center fw-bold">{{ $horoscopeDate }}</p>
    <div class="hor-bor-b mx-0 mt-4 mb-5">

    </div>
    <div class="row mt-5 mt-lg-3">
      <div class="col-md-3 col-12 ri-box">
        <div class="">
          <div class="form-group">
            <label for="zodiacSigns" class="h-label fw-semibold">Select Other Sign</label>
            <select class="form-control mt-3 hor-s" name="zodiac" id="zodiacSigns" onchange="handleZodiacSign(this)">
              @foreach(zodiacSigns() as $key => $value)
                <option value="{{ $value['name'] }}" {{ $zodiacSignName==$value['name']?"selected":"" }} data-url="{{ route('horoscopedetail',[$value['name'],'today']) }}">{{ $value['name'] }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="zodiacSigns" class="fw-semibold mt-3 h-label">Horoscopes:</label>
            <ul class="mt-3 hor-list">
              @foreach($timeFrameArray as $key => $value)
                <li class="{{ $key==$timeFrame?'text-primary':'text-warning' }}"><a href="{{ route('horoscopedetail',[$zodiacSignName,$key]) }}">{{ $value }}</a></li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-9 col-12">
        <div class="">
          <h4>{{ $zodiacSignName }} {{ ucfirst($timeFrame) }} Horoscope</h4>
          <span>{{ $horoscopeDate }}</span>
          @if(isset($data["phase_1"]))
            @php
              $phase_1=$data["phase_1"];
            @endphp
            @if(isset($phase_1["relationship"]))
              <div class="">
                <p>
                  <strong>Love and Relationships</strong>
                  <br>
                  <br>
                  {{ $phase_1["relationship"]["prediction"] }}
                </p>
              </div>
            @endif
            @if(isset($phase_1["health"]) || isset($phase_1["physique"]))
              <div class="">
                <p>
                  <strong>Health and Wellness</strong>
                  <br>
                  <br>
                  @if(isset($phase_1["health"]))
                    {{ $phase_1["health"]["prediction"] }}
                  @endif
                  <br>
                  @if(isset($phase_1["physique"]))
                    {{ $phase_1["physique"]["prediction"] }}
                  @endif
                </p>
              </div>
            @endif
            @if(isset($phase_1["career"]))
              <div class="">
                <p>
                  <strong>Career and Education</strong>
                  <br>
                  <br>
                  {{ $phase_1["career"]["prediction"] }}
                </p>
              </div>
            @endif
            @if(isset($phase_1["finances"]))
              <div class="">
                <p>
                  <strong>Money and Finances</strong>
                  <br>
                  <br>
                  {{ $phase_1["finances"]["prediction"] }}
                </p>
              </div>
            @endif
            @if(isset($phase_1["family"]) || isset($phase_1["friends"]))
              <div class="">
                <p>
                  <strong>Family and Friends</strong>
                  <br>
                  <br>
                  @if(isset($phase_1["family"]))
                    {{ $phase_1["family"]["prediction"] }}
                  @endif
                  <br>
                  @if(isset($phase_1["friends"]))
                    {{ $phase_1["friends"]["prediction"] }}
                  @endif
                </p>
              </div>
            @endif
            @if(isset($phase_1["total_score"]))
              <div class="">
                <p>
                  <strong>Tip of the Month for the Year:</strong>
                  <br>
                  <br>
                  {{ $phase_1["total_score"]["prediction"] }}
                </p>
              </div>
            @endif
            <div class="">
              <p><strong>Wishing you a fantastic and fulfilling {{ date("Y") }}, {{ $zodiacSignName }}! </strong>✨</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Daily Horoscopes-section-end -->

@endsection

@push('scripts')
<script type="text/javascript">
  function handleZodiacSign(that){
    let url = $(that).find("option:selected").attr("data-url");
    window.location.href=url;
  }
</script>
@endpush
