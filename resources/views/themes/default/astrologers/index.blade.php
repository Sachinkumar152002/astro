@extends(themeView().'layout')

@push('style')
@if(request()->is('chat-with-astrologers', 'talk-to-astrologers', 'astrologers/ai'))
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/chat-astrologers-ui.css') }}">
@endif
@endpush

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"{{ $pageName }}",
    "description":"{{ config('app.name') }} is a astrology consultant provider.",
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

@section('title', $metaTitle)

@php
  $mobileView=str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile') ? true : false;
@endphp

@section('content')
<style>
/* Clean Header Typography - Light Theme */
.get-instant h1 { font-family: 'Georgia', serif; font-weight: 700; color: #111827; }
.get-instant p { color: #4b5563; max-width: 800px; margin: 0 auto; font-size: 15px; }

/* Light & Professional Filter Bar */
.mobile.astro-filter .accordion-button {
    background: #ffffff !important; border: 1px solid #e5e7eb !important; color: #1f2937 !important; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.search_top_filter .dropdown .btn-light, .search_top_filter .searches {
    background: #ffffff !important; border: 1px solid #e5e7eb !important; color: #374151 !important; border-radius: 8px !important; padding: 10px 16px !important; box-shadow: 0 2px 6px rgba(0,0,0,0.02); font-size: 14px; font-weight: 500;
}
.search_top_filter .searches::placeholder { color: #9ca3af !important; }
.search_top_filter .dropdown .btn-light:hover, .search_top_filter .searches:focus {
    background: #f9fafb !important; border-color: #10b981 !important;
}

/* Pro Card Styles */
.astro-pro-card {
    transition: all 0.3s ease; border: 1px solid #eaeaea !important; border-radius: 12px !important; position: relative;
}
.astro-pro-card:hover {
    transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important; border-color: #d1d5db !important;
}

/* Blinking Status Dot Animation */
@keyframes blink-animation { 50% { opacity: 0; } }
.pro-status-dot.online { background: #10b981; animation: blink-animation 1.5s infinite; }
.pro-status-dot.busy { background: #ef4444; animation: blink-animation 1.5s infinite; }
.pro-status-dot.offline { background: #9ca3af; }

.pro-btn {
    border-radius: 6px; font-weight: 600; font-size: 13px; padding: 6px 12px; text-align: center; flex: 1;
    transition: all 0.2s; border: 1px solid transparent; background: #fff;
}
.pro-btn-chat { border-color: #10b981; color: #10b981; }
.pro-btn-chat:hover { background: #10b981; color: #fff; }
.pro-btn-call { border-color: #3b82f6; color: #3b82f6; }
.pro-btn-call:hover { background: #3b82f6; color: #fff; }
.pro-btn-video { border-color: #8b5cf6; color: #8b5cf6; }
.pro-btn-video:hover { background: #8b5cf6; color: #fff; }
</style>

<!-- banner-start -->
<section class="sm-0 mb-md-2 mb-0 mt-4 mt-md-5 mt-lg-4 ip-p">
  <div class="container">
    <div class="row">
      <div class="col-md-12 mt-5 mt-md-0">
        <div class="get-instant list-iconnew text-center">
          <!-- <h4 >Talk to a Lawyer Online â€“ Get Instant Legal Advice with AdvoLive</h4> -->
          <h1>Consult an {{ $astrologerType == "ai" ? "AI Astrologer" : 'Astrologer' }}</h1>
          <p class="mb-0 pb-0">Get accurate and personalized astrological guidance from expert astrologers. Whether it's about love, career, health, or finance, connect instantly and uncover the cosmic insights you need to make better life decisions. </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- banner-end -->
<!-- filter section start -->
<section class="pb-md-0 mt-4">
  <div class="container">
    <!-- filter-mobile -->
    @if($mobileView)
      <div class="accordion border rounded my-3 mobile astro-filter search_top_filter" id="accordionExample">
        <div class="accordion-col-lg-4">
          <h2 class="accordion-header" style="background:transparent;">
            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              <i class="bi text-black bi-funnel"></i>  Filters
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body" id="product-description-div">
              <div class="row justify-content-between mb-4">
                <div class="col-lg-3 col-sm-6 col-12 mb-2 mb-sm-2">
                  <input type="text" class="form-control searches text-left text-black bg-pi" id="searchByNameM"  placeholder="Search by name" onkeyup="filterByName()">
                </div>
                <div class="col-lg-3 col-sm-6 col-12 mb-2 mb-sm-2">
                  <div class="dropdown ">
                    <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      Categories
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="categoryDropdown">
                      @foreach($services as $index => $item)
                        <li>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $item->id }}" id="category{{ $index }}" {{ $item->id == $category_id ? "checked" : '' }}>
                            <label class="form-check-label" for="">{{ $item->name }}</label>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 mb-2 mb-sm-2">
                  <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle text-start w-100" type="button" id="expDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      Select Exp.
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="expDropdown">
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="0-5" id="exp1">
                          <label class="form-check-label" for="">0-5 years</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="5-10" id="exp2">
                          <label class="form-check-label" for="">5-10 years</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="10-15" id="exp3">
                          <label class="form-check-label" for="">10-15 years</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="15+" id="exp4">
                          <label class="form-check-label" for="">15+ years</label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 mb-2 mb-sm-2">
                  <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle text-start w-100" type="button" id="priceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Price
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="priceDropdown">
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="0-100" id="price1">
                          <label class="form-check-label" for="">&#8377;0 - &#8377;100</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-c
                                    heck">
                          <input class="form-check-input" type="checkbox" value="100-200" id="price2">
                          <label class="form-check-label" for="">&#8377;100 - &#8377;200</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="200-300" id="price3">
                          <label class="form-check-label" for="">&#8377;200 - &#8377;300</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="300+" id="price4">
                          <label class="form-check-label" for="">&#8377;300+</label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 mb-2 mb-sm-2">
                  <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      Language
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="languageDropdown" style="max-height: 300px; overflow-y: auto;">
                      @foreach($languages as $index => $item)
                        <li>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="languages[]" value="{{ $item->id }}" id="language{{ $index }}">
                            <label class="form-check-label" for="">{{ $item->name }}</label>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
                <!-- <div class="col-lg-3 col-sm-6 col-12 mb-2 mb-sm-2">
                  <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="genderDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      Gender
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="genderDropdown" style="max-height: 300px; overflow-y: auto;">
                      @foreach(genders() as $index => $item)
                        @if($index)
                          <li>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="genders[]" value="{{ $index }}" id="gender{{ $index }}">
                              <label class="form-check-label" for="">{{ $item }}</label>
                            </div>
                          </li>
                        @endif
                      @endforeach
                    </ul>
                  </div>
                </div> -->
                <div class="col-lg-3 col-sm-6 col-12 mb-2 mb-sm-2">
                  <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle text-start w-100" type="button" id="ratingDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      Rating
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="ratingDropdown">
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="rating[]" value="4+" id="star5">
                          <label class="form-check-label" for="">4+ Star</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="rating[]" value="3" id="star4">
                          <label class="form-check-label" for="">3 Star</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="rating[]" value="2" id="star3">
                          <label class="form-check-label" for="">2 Star</label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- <div class="col-lg-3 col-sm-6 col-12 mb-2 mb-sm-2">
                  <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="sortByDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      Sort by
                    </button>
                    <ul class="dropdown-menu w-100 p-2 language-side" aria-labelledby="genderDropdown" style="max-height: 300px; overflow-y: auto;">
                      <li>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="sort_by" value="" checked id="sortByValue" onclick="filterSortBy()">
                          <label class="form-check-label" for="sortByValue">Select</label>
                        </div>
                      </li>
                      @foreach(filterSortBy() as $key => $item)
                        @if($index)
                          <li>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="sort_by" value="{{ $key }}" id="sortBy{{ $key }}" onclick="filterSortBy()">
                              <label class="form-check-label" for="sortBy{{ $key }}">{{ $item }}</label>
                            </div>
                          </li>
                        @endif
                      @endforeach
                    </ul>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- filter-mobile -->
    @else
      <div class="search_top_filter d-none d-lg-block">
        <div class="row justify-content-start desktop astro-filter">
          <div class="col-lg-2 col-sm-6 col-12 mb-2 mb-sm-2">
            <input type="text" class="form-control searches text-left text-dark" id="searchByName"  placeholder="Search by name" onkeyup="filterByName()">
          </div>
          <div class="col-lg-2 col-sm-6 col-12 mb-2 mb-sm-2">
            <div class="dropdown ">
              <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Categories
              </button>
              <ul class="dropdown-menu w-100 p-2" aria-labelledby="categoryDropdown">
                @foreach($services as $index => $item)
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $item->id }}" id="category{{ $index }}" {{ $item->id == $category_id ? "checked" : '' }}>
                    <label class="form-check-label" for="">{{ $item->name }}</label>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6 col-12 mb-2 mb-sm-2">
            <div class="dropdown">
              <button class="btn btn-light dropdown-toggle text-start w-100 text-left" type="button" id="expDropdown" data-bs-toggle="dropdown" aria- expanded="false">
                Experience
              </button>
              <ul class="dropdown-menu w-100 p-2" aria-labelledby="expDropdown">
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="0-5" id="exp1">
                    <label class="form-check-label" for="">0-5 years</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="5-10" id="exp2">
                    <label class="form-check-label" for="">5-10 years</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="10-15" id="exp3">
                    <label class="form-check-label" for="">10-15 years</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="15+" id="exp4">
                    <label class="form-check-label" for="">15+ years</label>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6 col-12 mb-2 mb-sm-2">
            <div class="dropdown">
              <button class="btn btn-light dropdown-toggle text-start w-100" type="button" id="priceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Price
              </button>
              <ul class="dropdown-menu w-100 p-2" aria-labelledby="priceDropdown">
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="0-100" id="price1">
                    <label class="form-check-label" for="">&#8377;0 - &#8377;100</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="100-200" id="price2">
                    <label class="form-check-label" for="">&#8377;100 - &#8377;200</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="200-300" id="price3">
                    <label class="form-check-label" for="">&#8377;200 - &#8377;300</label>
                  </div>
                </li>
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="300+" id="price4">
                    <label class="form-check-label" for="">&#8377;300+</label>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-2 col-sm-6 col-12 mb-2 mb-sm-2">
            <div class="dropdown">
              <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Language
              </button>
              <ul class="dropdown-menu w-100 p-2 language-side" aria-labelledby="languageDropdown" style="max-height: 300px; overflow-y: auto;">
                @foreach($languages as $index => $item)
                <li>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="languages[]" value="{{ $item->id }}" id="language{{ $index }}">
                    <label class="form-check-label" for="">{{ $item->name }}</label>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
          {{--<div class="col-lg-2 col-sm-6 col-12 mb-2 mb-sm-2">
            <div class="dropdown">
              <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="genderDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Gender
              </button>
              <ul class="dropdown-menu w-100 p-2 language-side" aria-labelledby="genderDropdown" style="max-height: 300px; overflow-y: auto;">
                @foreach(genders() as $index => $item)
                  @if($index)
                    <li>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="genders[]" value="{{ $index }}" id="gender{{ $index }}">
                        <label class="form-check-label" for="">{{ $item }}</label>
                      </div>
                    </li>
                  @endif
                @endforeach
              </ul>
            </div>
          </div>--}}
          <div class="col-lg-2 col-sm-6 col-12 mb-2 mb-sm-2">
            <div class="dropdown">
              <button class="btn btn-light dropdown-toggle text-start w-100" type="button" id="ratingDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              Rating
            </button>
            <ul class="dropdown-menu w-100 p-2" aria-labelledby="ratingDropdown">
              <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="rating[]" value="4+" id="star5">
                  <label class="form-check-label" for="">4+ Star</label>
                </div>
              </li>
              <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="rating[]" value="3" id="star4">
                  <label class="form-check-label" for="">3 Star</label>
                </div>
              </li>
              <li>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="rating[]" value="2" id="star3">
                  <label class="form-check-label" for="">2 Star</label>
                </div>
              </li>
            </ul>
          </div>
        </div>
        {{--<div class="col-lg-2 col-sm-6 col-12 mb-2 mb-sm-2 mt-3">
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="sortByDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              Sort by
            </button>
            <ul class="dropdown-menu w-100 p-2 language-side" aria-labelledby="genderDropdown" style="max-height: 300px; overflow-y: auto;">
              <li>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sort_by" value="" checked id="sortByValue" onclick="filterSortBy()">
                  <label class="form-check-label" for="sortByValue">Select</label>
                </div>
              </li>
              @foreach(filterSortBy() as $key => $item)
                @if($index)
                  <li>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sort_by" value="{{ $key }}" id="sortBy{{ $key }}" onclick="filterSortBy()">
                      <label class="form-check-label" for="sortBy{{ $key }}">{{ $item }}</label>
                    </div>
                  </li>
                @endif
              @endforeach
            </ul>
          </div>
        </div>--}}
    </div>
  </div>
@endif

<!-- tab-start -->
<div id="myTabContent" class="tab-content">
  <div id="astro" role="tabpanel" aria-labelledby="astro-tab" class="tab-pane fade active show">
    <div class="">
      <div class="row talk_astrologer flex-wrap mt-0 mx-md-0" id="astrologer-list">
        <!-- astrologers -->
      </div>
    </div>
  </div>
</div>
<!-- tab-end -->
<div class="row mb-0 justify-content-center mt-5">
  <div class="col-lg-2">
    <button id="load-more" data-page="1" class="btn btn-secondary float-shadow w-100">View More</button>
  </div>
</div>
</div>
</section>


<!-- filter section End -->
<!-- testimonials shop section start -->
@include(themeView().'common.textTestimonial')
<!-- testimonials service section start -->
<!-- video shop section start -->
@include(themeView().'common.videoTestimonial')
<!-- video service section start -->

<!-- faq section start -->
@if(isset($faqs) && count($faqs))
<section class="d-none">
    <div class="container rounded py-md-5 py-2 mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-sm-3">
                </div>
                <div class="col-lg-8 col-sm-6">
                <div class="heading_main">
                    <img src="/assets/img/icons/heading_icon.svg">
                    <h2 class="fw-bold">Frequently Asked Questions</h2>
                    <p class="fs-6">Get answers to all your queries with our FAQs, covering everything you need to know about astrology, Vastu, and our services.</p>
                    </div>
            </div>
            <div class="col-lg-2 col-sm-3">
            </div>
        </div>
        <div class="row justify-content-center py-3">
            <div class="col-lg-8">
                <div class="accordion" id="accordionPanelsStayOpenExample">
                  @foreach($faqs as $key => $f)
                  <div class="accordion-item">
                      <h2 class="accordion-header">
                          <button class="accordion-button text-black aco-btn collapsed pe-0" type="button" data-bs-toggle="collapse"
                              data-bs-target="#collapse{{ $key }}"
                              aria-controls="collapse{{ $key }}">
                              {!! $f->question !!}
                          </button>
                      </h2>
                      <div id="collapse{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#accordionPanelsStayOpenExample">
                          <div class="accordion-body text14 aco-btn">
                              {!! $f->answer !!}
                          </div>
                      </div>
                  </div>
              @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- faq section End -->


@endsection
@push('scripts')
<script>
var astrologerType = "{{ $astrologerType }}";
var consultModel = "{{ $mode }}";
$('#load-more').on('click', function(){
  filterResult($(this).data('page'), astrologerType);
});
$('.form-check-input').on('click',function(){
  var that = this;
  filterResult(0, astrologerType)
});
$('.form-check-label').on('click',function(){
  var chkBox = $(this).parent().find('.form-check-input');
  chkBox.prop('checked', !chkBox.is(":checked"));
  filterResult(0, astrologerType)
});
var filterResult = function(page, type){
  page = parseInt(page);
  if(!page){
    page = 1;
  }
  // Gather the search input
  let searchByName = '';
  if($('#searchByName') && $('#searchByName').val()){
    searchByName = $('#searchByName').val().trim();
  }

  @if($mobileView)
    if(!searchByName && $('#searchByNameM')){
      searchByName = $('#searchByNameM').val().trim();
    }
  @endif

  // Gather selected filters
  let selectedCategories = [];
  let selectedCategoriesText = [];
  $('input[id^=category]:checked').each(function () {
    let textValue = $(this).parent().find('label').text();
    if(textValue){
      selectedCategoriesText.push(textValue);
    }
      selectedCategories.push($(this).val());
  });
  if(selectedCategoriesText.length){
    $('#categoryDropdown').text(selectedCategoriesText.join(', '));
  }else{
    $('#categoryDropdown').text('Categories');
  }

  let selectedExperience = [];
  let selectedExperienceText = [];
  $('input[id^=exp]:checked').each(function () {
    let textValue = $(this).parent().find('label').text();
    if(textValue){
      selectedExperienceText.push(textValue);
    }
    selectedExperience.push($(this).val());
  });
  if(selectedExperienceText.length){
    $('#expDropdown').text(selectedExperienceText.join(', '));
  }else{
    $('#expDropdown').text('Experience');
  }

  let selectedlanguage = [];
  let selectedlanguageText = [];
  $('input[id^=language]:checked').each(function(){
    let textValue = $(this).parent().find('label').text();
    if(textValue){
      selectedlanguageText.push(textValue);
    }
    selectedlanguage.push($(this).val());
  });
  if(selectedlanguageText.length){
    $('#languageDropdown').text(selectedlanguageText.join(', '));
  }else{
    $('#languageDropdown').text('Language');
  }


  let selectedPrice = [];
  let selectedPriceText = [];
  $('input[id^=price]:checked').each(function () {
    let textValue = $(this).parent().find('label').text();
    if(textValue){
      selectedPriceText.push(textValue);
    }
    selectedPrice.push($(this).val());
  });
  if(selectedPriceText.length){
    $('#priceDropdown').text(selectedPriceText.join(', '));
  }else{
    $('#priceDropdown').text('Price');
  }

  let selectedGender = [];
  let selectedGenderText = [];
  $('input[id^=gender]:checked').each(function () {
    let textValue = $(this).parent().find('label').text();
    if(textValue){
      selectedGenderText.push(textValue);
    }
    selectedGender.push($(this).val());
  });
  if(selectedGenderText.length){
    $('#genderDropdown').text(selectedGenderText.join(', '));
  }else{
    $('#genderDropdown').text('Gender');
  }

  let selectedRating = [];
  let selectedRatingText = [];
  $('input[id^=star]:checked').each(function () {
    let textValue = $(this).parent().find('label').text();
    if(textValue){
      selectedRatingText.push(textValue);
    }
    selectedRating.push($(this).val());
  });
  if(selectedRatingText.length){
    $('#ratingDropdown').text(selectedRatingText.join(', '));
  }else{
    $('#ratingDropdown').text('Rating');
  }

  // Make the AJAX request to the correct POST route
  $.ajax({
      url: '{{ route("astrologers.filterData") }}?&page='+page,
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        type: type,
        mode: consultModel,
        searchByName: searchByName,
        categories: selectedCategories,
        experience: selectedExperience,
        price: selectedPrice,
        rating: selectedRating,
        language: selectedlanguage,
        genders: selectedGender,
        sortBy: $('input[name=sort_by]:checked').val(),
      },
      success: function(response){
        if(page === 1){
          $('#astrologer-list').html(response.html);
        }else{
          $('#astrologer-list').append(response.html);
        }
        if(response.hasMorePages){
          $('#load-more').data('page', response.currentPage + 1).show();
        }else{
          $('#load-more').hide();
        }
      }
  });
}
var filterByName = function(){
  $('#load-more').data('page', 1);
    filterResult(0, astrologerType)
}
var filterSortBy = function(){
  $('#load-more').data('page', 1);
    filterResult(0, astrologerType)
}
$(function() {
  filterResult(0, astrologerType);
});

function handleNotifyMe(that, astrologer_id, type){
  let frmData = {astrologer_id,type};
  $.ajax({
    url : "{{ route('user.notify.save') }}",
    type : 'POST',
    headers:{
      "X-CSRF-TOKEN":"{{ csrf_token() }}"
    },
    data: frmData,
    success: function(response){
      toastr.success(response.message);
    }
  })
}
</script>
@endpush







