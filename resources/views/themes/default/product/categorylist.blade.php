@extends(themeView() . 'layout')

@push("style")
@if(request()->is('collections', 'collections/*', 'reports-collections', 'reports-collections/*', 'pujas-collections', 'pujas-collections/*', 'one-to-one-puja', 'one-to-one-puja/*'))
<link rel="stylesheet" href="{{ asset('assets/css/astrovyr-home.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/collections-ui.css') }}">
@endif
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
  }
  /* Horizontal filter bar - pastel layout */
  .product-filter-bar {
    width: 100%;
  }
  .product-filter-bar #product-filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
  }
  .product-filter-bar .filter-bar-item {
    border-radius: 10px;
    padding: 10px 14px;
    min-height: 44px;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    flex: 1;
    min-width: 120px;
  }
  .product-filter-bar .filter-bar-item .form-control,
  .product-filter-bar .filter-bar-item .form-select {
    font-size: 0.95rem;
    color: #333;
  }
  .product-filter-bar .filter-bar-item .form-control::placeholder {
    color: #666;
  }
  .product-filter-bar .filter-bar-item select.form-select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23333' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
  }
  .product-filter-bar .filter-product-search {
    min-width: 180px;
    flex: 1;
  }
  .product-filter-bar .product-search-suggestions {
    position: absolute;
    left: 0;
    right: 0;
    top: 100%;
    margin-top: 4px;
    z-index: 1050;
  }
  .product-filter-bar .product-search-suggestions .dropdown-item {
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  @media (max-width: 991px) {
    .product-filter-bar .filter-bar-item {
      min-width: 100%;
    }
  }
</style>
@endpush

@section('title', ($category ? ($category->meta_title ? : $category->name) :  $productTypeName).' | '.config("app.name"))

@push("og_tags")
  <meta property="og:type" content="website" />
  @if($category)
    <meta property="og:title" content="{{ $category->meta_title ? : $category->name }}" />
    <meta property="og:description" content="{{ $category->meta_description }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ $category->image?$category->image->image():logo() }}" />
    <meta name="twitter:title" content="{{ $category->meta_title ? : $category->name }}" />
    <meta name="twitter:description" content="{{  $category->meta_description }}" />
    <meta property="twitter:image" content="{{ $category->image?$category->image->image():logo() }}" />
  @else
  <meta property="og:title" content="Astro mall" />
  <meta property="og:description" content="" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:image" content="{{ logo() }}" />
  <meta name="twitter:title" content="All" />
  <meta name="twitter:description" content="" />
  <meta name="twitter:image" content="{{ logo() }}" />
  @endif
@endpush

@php
  $user = auth()->guard('web')->user();
@endphp

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"CollectionPage",
    "name":"{{ $category?$category->name:'All' }}",
    "mainEntity":{
      "@type":"ItemList",
      "itemListElement":[
        @foreach($products as $key => $itemm)
          @php
            $detaiPagelUrl = "";
            if($productTypeValue == 1){
              $detaiPagelUrl = route('product.detail', [$itemm->slug]);
            }elseif($productTypeValue == 2){
              if(isset($isOneToOnePuja) && $isOneToOnePuja == 1){
                $detaiPagelUrl = route("one.to.one.puja.slug", ['slug' => $itemm->slug]);
              }else{
                $detaiPagelUrl = route("product.detailpuja", [$itemm->slug]);
              }
            }elseif($productTypeValue == 3){
              $detaiPagelUrl = route("product.detailservice", [$itemm->slug]);
            }
          @endphp
          {
            "@type": "ListItem",
            "position": "{{ $key+1 }}",
            "url": "{{ $detaiPagelUrl }}"
            "name": "{{ $itemm->title }}",
          }
          @if($key!=($products->count()-1))
          ,
          @endif
        @endforeach
      ]
    }
  }
  </script>
@endpush

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "BreadcrumbList",
  "itemListElement": [
    @foreach($products as $key => $p)
      @php
        $cat = $p->category;
      @endphp
      {
        "@type": "ListItem",
        "position": "{{$key+1}}",
        "name": "{{$p->title}}",
        "item": "{{ route('product.handle', [$productTypeName, ($cat ? $cat->slug : 'all'), $p->slug]) }}"
      }
      @if($key+1!=count($products))
      ,
      @endif
    @endforeach
  ]
}
</script>
@endpush

@section('content')

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
<!-- shop  listing section start -->
<section class="shop_filter h pad-top mt-5 mt-md-4 mt-lg-0">
  <div class="container">
    {{-- Horizontal filter bar above products --}}
    <div class="row mb-3">
      <div class="col-12" id="filter-area">
        @include(themeView().'product.filter-bar')
      </div>
    </div>

    <div class="row">
      <div class="col-12 scroll-section-side">
        <div class="row justify-content-end mb-3">
          <div class="col-lg-4 mt-2 forshowingresultl d-none d-lg-block">
            <h6 class="fs-7 text fw-semibold">
              Showing  {{$count}} results of {{ $productTypeName }}
            </h6>
          </div>

          <div class="col-lg-8">
            <div class="d-flex d-md-block filter-d">

              <button class="btn btn-primary d-block d-lg-none mt-3 mb-2 filterbtn"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileFilter"
                aria-controls="mobileFilter">
                <i class="bi bi-funnel"></i> Filter
              </button>

              <div class="row justify-content-end align-items-center mobilesort">
                <div class="col-lg-3 main-filter p-0">
                  <h6 class="fs-7 text d-md-block fw-semibold text-md-end shortfont">Sort by</h6>
                </div>
                <div class="col-lg-3">
                  <div class="input-group">
                    <select class="form-select p-12" id="sort_by">
                      @foreach(filterSortBy() as $key => $item)
                        <option value="{{$key}}" {{$sortBy == $key ? 'selected' : ''}}>{{$item}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 mt-2 forshowingresultl d-lg-none">
            <h6 class="fs-7 text fw-semibold">
              Showing {{$count}} results of {{ $productTypeName }}
            </h6>
          </div>
        </div>


        <div class="row" id="products-list">
          @if($productTypeValue == 1)
            @foreach($products as $product)
              @include(themeView()."product.card", ["product" => $product, "user" => $user, "productTypeName" => $productTypeName, "productTypeValue" => $productTypeValue])
            @endforeach
          @elseif($productTypeValue == 2)
            @foreach($products as $product)
              @include(themeView()."product.cardpuja", ["product" => $product, "user" => $user, "productTypeName" => $productTypeName, "productTypeValue" => $productTypeValue, "is_one_to_one_puja" => isset($isOneToOnePuja) ? $isOneToOnePuja : 0])
            @endforeach
          @elseif($productTypeValue == 3)
            @foreach($products as $product)
              @include(themeView()."product.cardservice", ["product" => $product, "user" => $user, "productTypeName" => $productTypeName, "productTypeValue" => $productTypeValue])
            @endforeach
          @endif

          @if($count > $take)
            <div class="row mt-2">
              <div class="col-lg-12" id="load-more">
                {!! $products->links('vendor.pagination.bootstrap-5') !!}
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>


  <div class="offcanvas offcanvas-start filter_width" tabindex="-1" id="mobileFilter" aria-labelledby="mobileFilterLabel" style="z-index:9999;">
    <div class="offcanvas-header">
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body pt-3 p-0">
      <div id="mobile-filter-content">
        @include(themeView().'product.filter-bar')
      </div>
    </div>
  </div>
</section>


  <section>
    <div class="container">
      <div class="row">
        <div class="col-md-3 d-md-none d-block pt-3 mt-md-0 mb-0 pb-0">
          <div class="offcanvas offcanvas-start menu-offf p-canvas mt-5" tabindex="-1" id="offcanvasExampleBestSeller" aria-labelledby="offcanvasExampleBestSellerLabel">
            <div class="offcanvas-header">
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body off-body-m mt-0 pt-0">
              <div class="product-off mt-5 side-scrollbar p-2">
                <div class="main-filter-box  pb-3">
                  <div class="btn-bg p-3 radius">
                    <h5 class="h5 ms-2 mb-0">Best Sellers</h5>
                  </div>
                  @foreach($bestSellerProducts as $key => $bestProduct)
                    @php
                      $cat = $bestProduct->category;
                      $bestProductImage = $bestProduct->image;
                    @endphp
                    <div class="seller-box px-3 my-3 ">
                      <a href="{{ route('product.detail', [$productTypeName, ($cat ? $cat->slug : 'all'), $bestProduct->slug]) }}">
                        <div class="row pb-3 border-bottom">
                          <div class="col-md-5 col-12">
                            <div class="card">
                              <img src="{{ $bestProductImage ? $bestProductImage  ->image(300, 300) : '' }}" alt="{{$bestProduct->title}}" class="img-fluid">
                            </div>
                          </div>
                          <div class="col-md-7 col-12">
                            <div class="m-0 p-0">
                              <h5 class="fs-6 mb-0 pb-0">{{$bestProduct->title}}</h5>
                              <div class="">
                                @if($bestProduct->sale_price && (date('Y-m-d') >= $bestProduct->sale_price_start_date) && (date('Y-m-d') <= $bestProduct->sale_price_end_date) )
                                  <p class="fs-7 text fw-semibold mb-0 pb-0">₹{{$bestProduct->sale_price}}</p>
                                @else
                                  <p class="fs-7 text fw-semibold mb-0 pb-0">₹{{$bestProduct->base_price}}</p>
                                @endif
                              </div>
                              <ul class="p-0">
                                <li class="list-type-group d-flex align-col-lg-4s-center">
                                  <i class="bi text-rating bi-star-fill"></i>
                                  <i class="bi text-rating bi-star-fill"></i>
                                  <i class="bi text-rating bi-star-fill"></i>
                                  <i class="bi text-rating bi-star-fill"></i>
                                  <i class="bi text-rating bi-star-fill"></i>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  @endforeach
                </div>
                @foreach(ads('product') as $key => $ad )
                  <div class="mt-2">
                    @if($ad->image)
                      <a href="{{$ad->link}}" target="_blank">
                        <img src="{{$ad->image->image(800)}}" alt="ad-image" class="w-100 mt-3">
                      </a>
                    @else
                      {!! $ad->content !!}
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @include(themeView().'common.adpopup', ['page'=>'product'])
  <!-- shop  listing section End -->
@endsection
@push('scripts')
<script type="text/javascript">
  $(document).ready(function(){
    $('.cartfom').on('submit', function(event) {
      event.preventDefault();
      const form = $(this);
      var formData = form.serialize();
      $.ajax({
          url: "{{ route('addtocart') }}",
          method: "POST",
          data: formData,
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            sessionUserId = response.user_id;
            let redirectTo = response.redirectTo;
            if(redirectTo){
              form.find("input[name=buynow]").remove();
              window.location.href = redirectTo;
              return 0;
            }
            if(response.status == 'success'){
              toastr.success(response.message);
            }else if(response.status == 'warning'){
              toastr.warning(response.message);
            }else{
              toastr.error(response.message);
            }
            showCart2();
          },
          error: function(xhr) {
            toastr.error('Error adding item to cart. Please try again.');
          }
      });
    });
  });

  function handleBuyNowProduct(that){
    const form = $(that).closest("form");
    form.find('input[name="buynow"]').remove();
    form.append('<input type="hidden" name="buynow" value="1">');
    form.submit();
  }
</script>
<script>
  var slug = '{{$slug}}';
  var type = '';
  {{--
  filterTypeOnLoad('{{$type}}');
  --}}
  var priceFrom = parseInt('{{$priceFrom}}');
  // var priceFrom = 0;
  var priceTo = parseInt('{{$priceTo}}');
  // var priceTo = 0;
  var maxPrice = parseInt('{{$maxPrice}}');
  var minPrice = parseInt('{{$minPrice}}');
  var sortBy = '{{$sortBy}}';
  var ratingFilter = '{{ $ratingFilter ?? "" }}';
  // document.getElementById('apply-btn').addEventListener('click', applyFilter)
  function applyFilter (){
    let queryParams = [];
    if(sortBy){
      queryParams.push('sortBy='+sortBy);
    }
    if(priceFrom && priceFrom != minPrice){
      queryParams.push('priceFrom='+priceFrom);
    }
    if(priceTo && priceTo != maxPrice){
      queryParams.push('priceTo='+priceTo);
    }
    if(ratingFilter && ratingFilter !== 'all'){
      queryParams.push('rating='+encodeURIComponent(ratingFilter));
    }
    var productName = ($('.js-product-search-input').length && $('.js-product-search-input').val()) ? $('.js-product-search-input').val().trim() : '';
    if(productName){
      queryParams.push('product_name='+encodeURIComponent(productName));
    }
    let queryString = queryParams.length > 0 ? '?' + queryParams.join('&') : '';

    @if($productTypeValue == 1)
      let baseUrl = "{{ route('product.handle') }}";
      if(slug && slug != "all"){
        baseUrl += "/"+slug;
      }
      window.location.href = baseUrl + queryString;
    @elseif($productTypeValue == 2)
      @if(isset($isOneToOnePuja) && $isOneToOnePuja == 1)
        let baseUrl;
        if(slug && slug != "all"){
          baseUrl = "{{ url('one-to-one-puja') }}/"+slug;
        }else{
          baseUrl = "{{ route('one.to.one.puja.handle') }}";
        }
        window.location.href = baseUrl + queryString;
      @else
        let baseUrl = "{{ route('puja.handle') }}";
        if(slug && slug != "all"){
          baseUrl += "/"+slug;
        }
        window.location.href = baseUrl + queryString;
      @endif
    @elseif($productTypeValue == 3)
      let baseUrl = "{{ route('service.handle') }}";
      if(slug && slug != "all"){
        baseUrl += "/"+slug;
      }
      window.location.href = baseUrl + queryString;
    @endif
  }
  function filterCategory(s){
    slug = s;
    applyFilter();
  }
  function filterTypeOnLoad(t){
    slug = "all";
    type = 'Product';
    if(t==2){
      type = 'Pooja';
    }else if(t==3){
      type = 'Service';
    }
  }
  function filterType(t){
    slug = "all";
    type = 'Product';
    if(t==2){
      type = 'Pooja';
    }else if(t==3){
      type = 'Service';
    }
    applyFilter();
  }
  $('#sort_by').change(function (){
    sortBy = $(this).val();
    applyFilter();
  });

  $(document).on('change', '.js-price-range', function(){
    var val = $(this).val();
    if (val === 'all') {
      priceFrom = minPrice;
      priceTo = maxPrice;
    } else {
      var parts = val.split('-');
      priceFrom = parseInt(parts[0], 10);
      priceTo = parseInt(parts[1], 10);
    }
    applyFilter();
  });

  $(document).on('change', '.js-category-slug', function(){
    filterCategory($(this).val());
  });

  $(document).on('change', '.js-rating', function(){
    ratingFilter = $(this).val();
    applyFilter();
  });

  $('#filter_type').change(function (){
    filterType($(this).val());
  });

  // Product search suggestions (autocomplete)
  var productSearchSuggestionsTimer = null;
  var productTypeValue = parseInt('{{ $productTypeValue ?? 1 }}');
  var isOneToOnePuja = {{ isset($isOneToOnePuja) && $isOneToOnePuja ? '1' : '0' }};
  $(document).on('input', '.js-product-search-input', function(){
    var $input = $(this);
    var q = $input.val().trim();
    var $dropdown = $('#product-search-suggestions');
    if(q.length < 2){
      $dropdown.hide().empty();
      return;
    }
    clearTimeout(productSearchSuggestionsTimer);
    productSearchSuggestionsTimer = setTimeout(function(){
      var url = '{{ route("product.search.suggestions") }}?q=' + encodeURIComponent(q) + '&type=' + productTypeValue;
      if(productTypeValue === 2){
        url += '&is_one_to_one_puja=' + isOneToOnePuja;
      }
      $.get(url).done(function(res){
        var list = res.suggestions || [];
        if(list.length === 0){
          $dropdown.hide().empty();
          return;
        }
        var html = '';
        list.forEach(function(item){
          html += '<a href="#" class="dropdown-item product-search-suggestion-item text-truncate" data-title="' + (item.title || '').replace(/"/g, '&quot;') + '">' + (item.title || '') + '</a>';
        });
        $dropdown.html(html).show();
      }).fail(function(){
        $dropdown.hide().empty();
      });
    }, 300);
  });
  $(document).on('click', '.product-search-suggestion-item', function(e){
    e.preventDefault();
    var title = $(this).data('title');
    $('.js-product-search-input').val(title);
    $('#product-search-suggestions').hide().empty();
    applyFilter();
  });
  $(document).on('click', function(e){
    if(!$(e.target).closest('.filter-product-search').length){
      $('#product-search-suggestions').hide();
    }
  });
  $('#product-filter-form').on('submit', function(e){
    e.preventDefault();
    applyFilter();
    return false;
  });
  $('.js-product-search-input').on('keydown', function(e){
    if(e.which === 13){
      e.preventDefault();
      applyFilter();
    }
  });

</script>
<script>
  var page = '{{$page}}';
  $('#load-more a').on('click', function(e){
    e.preventDefault();
    filterResult($(this).attr('href'));
  });
  var filterResult = function(paginateUrl){
      var sep = paginateUrl.indexOf('?') >= 0 ? '&' : '?';
      if(sortBy){
        paginateUrl += sep + 'sortBy=' + sortBy;
        sep = '&';
      }
      if(type){
        paginateUrl += sep + 'type=' + type;
        sep = '&';
      }
      if(priceFrom && priceFrom != minPrice){
        paginateUrl += sep + 'priceFrom=' + priceFrom;
        sep = '&';
      }
      if(priceTo && priceTo != maxPrice){
        paginateUrl += sep + 'priceTo=' + priceTo;
        sep = '&';
      }
      if(ratingFilter && ratingFilter !== 'all'){
        paginateUrl += sep + 'rating=' + encodeURIComponent(ratingFilter);
        sep = '&';
      }
      var productName = $('.js-product-search-input').length ? $('.js-product-search-input').val() : '';
      if(productName && productName.trim()){
        paginateUrl += sep + 'product_name=' + encodeURIComponent(productName.trim());
      }
      window.location.href = paginateUrl;
  }
</script>
</script>
@endpush
