@extends(themeView().'layout')
@section('content')
<!-- Banner Section Start -->
<div class="container-fluid p-0 m-0">
  <div class="bannerslider">
    @if(str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile'))
    @foreach($banners as $banner)
        <div class="slide d-flex align-items-center p-5" style="background-image: url('{{$banner->mobileImage ? $banner->mobileImage->image() : ''}}'); background-repeat: no-repeat; background-size: cover; background-position:center;height:400px;">
          <div class="container">
            <div class="row w-100">
              <div class="col-lg-9 col-md-8 col-12 text-white d-flex flex-column align-items-center align-items-md-start justify-content-center">
                <h2 class="white fs-1 text-center text-md-start">{{$banner->title}}</h2>
                {!! $banner->caption !!}
                <a href="{{route('astrologers.index')}}">
                <!-- <button class="roboto-bold btn btn-light btn-padding btn-radius ">Consult Now</button></a> -->
              </div>
              {{--<div class="col-lg-3 col-md-4 col-12 d-flex align-items-center justify-content-center py-3 py-md-0">
                <div class="banner-img">
                  <img src="{{ asset('media/'.$banner->image->id.'/'.$banner->image->file) }}" alt="Banner Image">
                </div>
              </div>--}}
            </div>
          </div>
        </div>
    @endforeach
    @else
@foreach($banners as $banner)
    <div class="slide d-flex align-items-center p-5" style="background-image: url('{{$banner->image ? $banner->image->image() : ''}}'); background-repeat: no-repeat; background-size: cover; background-position:center;height:400px;">
      <div class="container">
        <div class="row w-100">
          <div class="col-lg-9 col-md-8 col-12 text-white d-flex flex-column align-items-center align-items-md-start justify-content-center">
            <h2 class="white fs-1 text-center text-md-start">{{$banner->title}}</h2>
            {!! $banner->caption !!}
            <a href="{{route('astrologers.index')}}">
            {{-- <button class="roboto-bold btn btn-light btn-padding btn-radius ">Consult Now</button></a> --}}
          </div>
          {{--<div class="col-lg-3 col-md-4 col-12 d-flex align-items-center justify-content-center py-3 py-md-0">
            <div class="banner-img">
              <img src="{{ asset('media/'.$banner->image->id.'/'.$banner->image->file) }}" alt="Banner Image">
            </div>
          </div>--}}
        </div>
      </div>
    </div>
@endforeach
@endif
    </div>
    </div>
<!-- Banner Section End -->
<!-- filter section start -->
<section class="py-md-4 py-2">
    <div class="container">
        <div class="row justify-content-between mb-2">
            {{-- <div class="col-lg-2">
                    <select class="form-select form-select-lg mb-3" aria-label="Large select example">
                        <option selected>Filter</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div> --}}
            <div class="col-lg-3 col-sm-6 col-12 mb-3 mb-sm-2">
                  <input type="text" class="form-control searches text-left text-dark" id="searchByName"  placeholder="Search by name" onkeyup="filterByName()">
              </div>

            <div class="col-lg-3 col-sm-6 col-12 mb-3 mb-sm-2">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle text-start w-100" type="button" id="expDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Select Exp.
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="expDropdown">
                      <li>
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="{{date('Y')}}-{{date('Y', strtotime('-5 years'))}}" id="exp1">
                              <label class="form-check-label" for="exp1">0-5 years</label>
                          </div>
                      </li>
                      <li>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{date('Y', strtotime('-6 years'))}}-{{date('Y', strtotime('-10 years'))}}" id="exp2">
                              <label class="form-check-label" for="exp2">5-10 years</label>
                          </div>
                      </li>
                      <li>
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="{{date('Y', strtotime('-11 years'))}}-{{date('Y', strtotime('-15 years'))}}" id="exp3">
                              <label class="form-check-label" for="exp3">10-15 years</label>
                          </div>
                      </li>
                      <li>
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="{{date('Y', strtotime('-15 years'))}}-{{date('Y', strtotime('-100 years'))}}" id="exp4">
                              <label class="form-check-label" for="exp4">15+ years</label>
                          </div>
                      </li>
                  </ul>

                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-12 mb-3 mb-sm-2">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle text-start w-100" type="button" id="priceDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Select Price
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="priceDropdown">
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="0-100" id="price1">
                                <label class="form-check-label" for="price1">₹0 - ₹100</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="100-200" id="price2">
                                <label class="form-check-label" for="price2">₹100 - ₹200</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="200-300" id="price3">
                                <label class="form-check-label" for="price3">₹200 - ₹300</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="300+" id="price4">
                                <label class="form-check-label" for="price4">₹300+</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-12 mb-3 mb-sm-2">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle text-start w-100" type="button" id="ratingDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Rating
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="ratingDropdown">
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="5" id="star5">
                                <label class="form-check-label" for="star5">5 Star</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="4" id="star4">
                                <label class="form-check-label" for="star4">4 Star</label>
                            </div>
                        </li>
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="3" id="star3">
                                <label class="form-check-label" for="star3">3 Star</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- <div class="col mb-3 mb-sm-2">
                <div class="dropdown ">
                    <button class="btn btn-light dropdown-toggle w-100" type="button" id="categoryDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Category
                    </button>
                    <ul class="dropdown-menu w-100 p-2" aria-labelledby="categoryDropdown">
                        @foreach($services as $index => $item)
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $item->id }}" id="category{{ $index }}">
                                <label class="form-check-label" for="category{{ $index }}">{{ $item->name }}</label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div> --}}

        </div>
        <div class="row talk_astrologer flex-wrap" id="astrologer-list">

            {{-- @foreach ($astrologers as $astrologer)
            @include(themeView() . 'astrologers._item', ['astrologer' => $astrologer])
            @endforeach --}}

        </div>

        {{-- @if ($astrologers->hasMorePages()) --}}
        <!-- <div class="row justify-content-center">
            <div class="col-lg-2">
                <button id="load-more" data-page="1" class="btn btn-secondary float-shadow w-100 ">View More</button>
            </div>
        </div> -->
        {{-- @endif --}}
    </div>
</section>
<!-- filter section End -->

<!-- faq section start -->
@if($faq)
<section>
    <div class="container bg-light rounded py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center">
                <h2 class="heading">Frequently Asked Questions</h2>
                <p class="fs-6 fw-semibold">Learn about the world class Astrologer we provide</p>
            </div>
        </div>
        <div class="row justify-content-center py-3">
            <div class="col-lg-8">
                <div class="accordion" id="accordionPanelsStayOpenExample">
                  @foreach($faq as $f)
                    <div class="accordion-item">
                        <h2 class="accordion-header ">
                            <button class="accordion-button fw-bold text-black" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseOne">
                                {!! $f->question !!}
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionPanelsStayOpenExample">
                            <div class="accordion-body fs-6">
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
$('#load-more').on('click', function(){ filterResult(); });
$('.form-check-input').on('click',function(){
  // e.preventDefault();
  $('#load-more').data('page', 1);
  var that = this;
  $(this).prop('checked',that.checked);
  filterResult()
} );
var filterResult = function(){
  let page = $('#load-more').data('page') || 1;

  // Gather the search input
  let searchByName = $('#searchByName').val().trim();

  // Gather selected filters
  let selectedCategories = [];
  $('input[id^=category]:checked').each(function () {
      selectedCategories.push($(this).val());
  });

  let selectedExperience = [];
  $('input[id^=exp]:checked').each(function () {
      selectedExperience.push($(this).val());
  });

  let selectedPrice = [];
  $('input[id^=price]:checked').each(function () {
      selectedPrice.push($(this).val());
  });

  let selectedRating = [];
  $('input[id^=star]:checked').each(function () {
      selectedRating.push($(this).val());
  });

  // Make the AJAX request to the correct POST route
  $.ajax({
      url: '{{ url("/talk-to-astrologers/filter") }}?page=' + page,
      method: 'POST',
      data: {
          _token: '{{ csrf_token() }}',
          searchByName: searchByName, // Add search input to the data
          categories: selectedCategories,
          experience: selectedExperience,
          price: selectedPrice,
          rating: selectedRating
      },
      success: function (response) {
          if (page === 1) {
              $('#astrologer-list').html(response.html); // Replace list if page 1 (initial filter)
          } else {
              $('#astrologer-list').append(response.html); // Append new astrologers if paginating
          }

          if (response.hasMorePages) {
              $('#load-more').data('page', page + 1).show(); // Update and show the load-more button
          } else {
              $('#load-more').hide(); // Hide button if no more pages
          }
      }
  });
}
var filterByName = function(){
  $('#load-more').data('page', 1);
    filterResult()
}
$(function() {
  filterResult();
});
</script>
@endpush
