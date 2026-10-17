@extends(themeView().'layout')

@section('title', "Kundli Matching Detail | ".config("app.name"))

@push('style')
<style>
  body {
    background-color: #f9f9f9;
    font-family: 'Segoe UI', sans-serif;
  }
  .nav-pills .nav-link {
    font-weight: 500;
    color: #555;
  }
  .nav-pills .nav-link.active {
    background-color: #fdd835;
    color: #000;
  }
  .nav-tabs {
    border-bottom: 2px solid #ddd;
  }
  .nav-tabs .nav-link {
    border: none;
    color: #555;
    font-weight: 500;
    padding: 10px 20px;
    position: relative;
    transition: all 0.3s ease;
  }
  .nav-tabs .nav-link:hover {
    color: #000;
  }
  .nav-tabs .nav-link.active {
    color: #000;
    background-color: transparent;
  }
  .nav-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 10%;
    width: 80%;
    height: 3px;
    background-color: #fdd835;
    border-radius: 3px;
  }
  .kundli-label {
    font-weight: 500;
    color: #666;
  }
  .kundli-value {
    font-weight: 500;
    color: #222;
  }
  .card-header {
    font-weight: 600;
    background: #f4f4f4;
    font-size: 1rem;
  }
  .tab-content {
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 6px 6px;
  }
  .kundli-section {
    margin-bottom: 30px;
  }
  @media (max-width: 768px) {
    .nav-tabs .nav-link {
      font-size: 14px;
      padding: 8px 12px;
    }
  }
  .card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
  }
  .btn-warning {
    background-color: #ff6f00;
    border: none;
  }

  .btn-warning:hover {
    background-color: #e65c00;
  }
</style>
@endpush

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

@push('header_script')
@endpush


<!-- banner-section-start -->
@section('content')
@php
    $mobileView = str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile') ? true : false;
  @endphp
@foreach($page as $index => $banner)
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
    @endif
  @endforeach
  <section class="page-header about-page" style="background-image: url('{{ $bannerImageUrl }}'); background-repeat: no-repeat; width: 100%; background-size: cover;">
    <div class="container">
      <div class="head-sec">
        <h1 class="text-center text-white">Kundali Matching Detail</h1>
        @include(themeView().'common.breadcrumb')
      </div>
    </div>
  </section>
  <section class="pb-4 pb-md-3 pb-lg-4">
    <div class="container py-4">
      <div id="boy_girl_detail">
        <h5 class="mt-4">Details Of Boy and Girl</h5>
        <div class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead class="table-light">
            <tr>
              <th></th>
              <th>Name</th>
              <th>Date/Time</th>
              <th>Place</th>
              <th>Latitude</th>
              <th>Longitude</th>
              <th>Time Zone</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Boy</td>
              <td>{{ $kundaliMatchingEnquiry->boy_name }}</td>
              <td>{{ date("d.m/Y h:i a", strtotime($kundaliMatchingEnquiry->boy_birth_date." ".$kundaliMatchingEnquiry->boy_birth_time)) }}</td>
              <td>{{ $kundaliMatchingEnquiry->boy_birth_place }}</td>
              <td>{{ $kundaliMatchingEnquiry->boy_lat }}</td>
              <td>{{ $kundaliMatchingEnquiry->boy_lon }}</td>
              <td>5.5</td>
            </tr>
            <tr>
              <td>Girl</td>
              <td>{{ $kundaliMatchingEnquiry->girl_name }}</td>
              <td>{{ date("d.m/Y h:i a", strtotime($kundaliMatchingEnquiry->girl_birth_date." ".$kundaliMatchingEnquiry->girl_birth_time)) }}</td>
              <td>{{ $kundaliMatchingEnquiry->girl_birth_place }}</td>
              <td>{{ $kundaliMatchingEnquiry->girl_lat }}</td>
              <td>{{ $kundaliMatchingEnquiry->girl_lon }}</td>
              <td>5.5</td>
            </tr>
          </tbody>
        </table>
        </div>
      </div>
      <div id="guna_milan" class="mt-4">
        <h5 class="mt-4">Guna Millan</h5>
        <div class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead class="table-light">
            <tr>
              <th>Guna</th>
              <th>Boy</th>
              <th>Girl</th>
              <th>Maximum Obtained</th>
              <th>Obtained Point</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($jsonResponse["tara"]))
              @php
                $tara = $jsonResponse["tara"];
              @endphp
              <tr>
                <td>{{ $tara["name"] }}</td>
                <td>{{ $tara["boy_tara"] }}</td>
                <td>{{ $tara["girl_tara"] }}</td>
                <td>{{ $tara["full_score"] }}</td>
                <td>{{ $tara["tara"] }}</td>
                <td>{{ $tara["description"] }}</td>
              </tr>
            @endif
            @if(isset($jsonResponse["gana"]))
              @php
                $gana = $jsonResponse["gana"];
              @endphp
              <tr>
                <td>{{ $gana["name"] }}</td>
                <td>{{ $gana["boy_gana"] }}</td>
                <td>{{ $gana["girl_gana"] }}</td>
                <td>{{ $gana["full_score"] }}</td>
                <td>{{ $gana["gana"] }}</td>
                <td>{{ $gana["description"] }}</td>
              </tr>
            @endif
            @if(isset($jsonResponse["yoni"]))
              @php
                $yoni = $jsonResponse["yoni"];
              @endphp
              <tr>
                <td>{{ $yoni["name"] }}</td>
                <td>{{ $yoni["boy_yoni"] }}</td>
                <td>{{ $yoni["girl_yoni"] }}</td>
                <td>{{ $yoni["full_score"] }}</td>
                <td>{{ $yoni["yoni"] }}</td>
                <td>{{ $yoni["description"] }}</td>
              </tr>
            @endif
            @if(isset($jsonResponse["bhakoot"]))
              @php
                $bhakoot = $jsonResponse["bhakoot"];
              @endphp
              <tr>
                <td>{{ $bhakoot["name"] }}</td>
                <td>{{ $bhakoot["boy_rasi_name"] }}</td>
                <td>{{ $bhakoot["girl_rasi_name"] }}</td>
                <td>{{ $bhakoot["full_score"] }}</td>
                <td>{{ $bhakoot["bhakoot"] }}</td>
                <td>{{ $bhakoot["description"] }}</td>
              </tr>
            @endif
            @if(isset($jsonResponse["grahamaitri"]))
              @php
                $grahamaitri = $jsonResponse["grahamaitri"];
              @endphp
              <tr>
                <td>{{ $grahamaitri["name"] }}</td>
                <td>{{ $grahamaitri["boy_lord"] }}</td>
                <td>{{ $grahamaitri["girl_lord"] }}</td>
                <td>{{ $grahamaitri["full_score"] }}</td>
                <td>{{ $grahamaitri["grahamaitri"] }}</td>
                <td>{{ $grahamaitri["description"] }}</td>
              </tr>
            @endif
            @if(isset($jsonResponse["vasya"]))
              @php
                $vasya = $jsonResponse["vasya"];
              @endphp
              <tr>
                <td>{{ $vasya["name"] }}</td>
                <td>{{ $vasya["boy_vasya"] }}</td>
                <td>{{ $vasya["girl_vasya"] }}</td>
                <td>{{ $vasya["full_score"] }}</td>
                <td>{{ $vasya["vasya"] }}</td>
                <td>{{ $vasya["description"] }}</td>
              </tr>
            @endif
            @if(isset($jsonResponse["nadi"]))
              @php
                $nadi = $jsonResponse["nadi"];
              @endphp
              <tr>
                <td>{{ $nadi["name"] }}</td>
                <td>{{ $nadi["boy_nadi"] }}</td>
                <td>{{ $nadi["girl_nadi"] }}</td>
                <td>{{ $nadi["full_score"] }}</td>
                <td>{{ $nadi["nadi"] }}</td>
                <td>{{ $nadi["description"] }}</td>
              </tr>
            @endif
            @if(isset($jsonResponse["varna"]))
              @php
                $varna = $jsonResponse["varna"];
              @endphp
              <tr>
                <td>{{ $varna["name"] }}</td>
                <td>{{ $varna["boy_varna"] }}</td>
                <td>{{ $varna["girl_varna"] }}</td>
                <td>{{ $varna["full_score"] }}</td>
                <td>{{ $varna["varna"] }}</td>
                <td>{{ $varna["description"] }}</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
      </div>
      <div class="row mt-4 gx-4">
        <div class="col-md-8">
          <div class="card shadow-sm" style="background-color: #fff8dc;">
            <div class="card-body">
              <h4 class="mb-3">Kundali Matching Results</h4>
              @if(isset($jsonResponse["bot_response"]))
                @php
                  $bot_response = $jsonResponse["bot_response"];
                @endphp
                <ul class="mb-0" style="list-style-type: disc; padding-left: 20px;">
                  <li>{{ $bot_response }}</li>
                </ul>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-4 obtain">
          <div class="card shadow-sm text-center" style="background-color: #fff8dc;">
            <div class="card-body">
              @if(isset($jsonResponse["score"]))
                @php
                  $score = $jsonResponse["score"];
                @endphp
                <h5>Obtained Point</h5>
                <div style="font-size: 36px; font-weight: bold; margin: 10px 0;">{{ $score }} <small>/36</small></div>
              @endif
              <a href="{{ route('astrologers.talktoastrologers') }}" class="btn btn-warning text-white fw-bold" target="_blank">Talk to Astrologer</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
@endpush
