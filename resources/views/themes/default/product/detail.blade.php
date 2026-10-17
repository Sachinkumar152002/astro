@extends(themeView() . 'layout')

@php
  $user = auth()->guard('web')->user();
@endphp

@push('style')
<style>
  /* Ensure buttons remain visible when disabled or clicked */
  .puja-book-btn,
  .puja-book-btn:disabled,
  .puja-book-btn:active,
  .puja-book-btn:focus,
  .puja-book-btn:hover,
  #service-submit-btn,
  #service-submit-btn:disabled,
  #service-submit-btn:active,
  #service-submit-btn:focus,
  #service-submit-btn:hover,
  .k_participate,
  .k_participate:disabled,
  .k_participate:active,
  .k_participate:focus,
  .k_participate:hover,
  .main_button,
  .main_button:disabled,
  .main_button:active,
  .main_button:focus,
  .main_button:hover,
  .ashirwad-yes-btn,
  .ashirwad-yes-btn:disabled,
  .ashirwad-yes-btn:active,
  .ashirwad-yes-btn:focus,
  .ashirwad-yes-btn:hover,
  .ashirwad-no-btn,
  .ashirwad-no-btn:disabled,
  .ashirwad-no-btn:active,
  .ashirwad-no-btn:focus,
  .ashirwad-no-btn:hover {
    display: inline-block !important;
    visibility: visible !important;
  }
</style>
<link rel="stylesheet" href="{{ asset('assets/lib/material-timepicker/mdtimepicker.css') }}">
@endpush

@section('title', $product->meta_title ?? $product->title)
@section('description', $product->meta_description)
@section('keywords', $product->meta_keywords)

@push("og_tags")
  <meta property="og:type" content="product" />
  <meta property="og:title" content="{{ $product->title }}" />
  <meta property="og:description" content="{{ $product->specifications?strip_tags($product->specifications->short_description):$product->title }}" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:image" content="{{ $product->image?$product->image->image():logo() }}" />

  @if($product->sale_price_start_date && $product->sale_price_start_date <= date("Y-m-d") && $product->sale_price_end_date >= date("Y-m-d"))
    <meta property="product:price:amount" content="{{ $product->sale_price }}" />
  @else
    <meta property="product:price:amount" content="{{ $product->base_price }}" />
  @endif
  <meta property="product:price:currency" content="INR" />
  <meta property="product:availability" content="in stock" />

  <meta name="twitter:title" content="{{ $product->title }}" />
  <meta name="twitter:description" content="{{ $product->specifications?strip_tags($product->specifications->short_description):$product->title }}" />
  <meta name="twitter:image" content="{{ $product->image?$product->image->image():logo() }}" />
@endpush

@push("schema")
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Product",
    "@id": "{{ url()->current() }}#product",
    "name": "{{ $product->title }}",
    "description": "{{ $product->specifications?strip_tags($product->specifications->short_description):"" }}",
    "sku": "{{ $product->sku }}",
    "mpn": "NULL",
    "image": [
      @php
        $productGall=$product->productGall;
      @endphp
      @foreach($productGall as $key => $gallery)
        @php
          $galleryImage = $gallery->image;
        @endphp
        @if($galleryImage)
          "{{ $galleryImage->image() }}"
          @if($key!=($productGall->count()-1))
          ,
          @endif
        @endif
      @endforeach
    ],
    "category": "{{ $category ? $category->name : 'Uncategorized' }}",
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "{{ $maxRating }}",
      "reviewCount": "{{ $productReviews->total() }}"
    },
    "review": [
      @foreach($productReviews as $key => $revieww)
        {
          "@type":"Review",
          "author":{
            "@type":"Person",
            "name":"{{ $revieww->user?$revieww->user->name:'Unknown' }}"
          },
          "datePublished":"{{ $revieww->review_date?date('Y-m-d',strtotime($revieww->review_date)):date('Y-m-d',strtotime($revieww->created_at)) }}",
          "reviewRating":{
            "@type":"Rating",
            "ratingValue":"{{ $revieww }}",
            "bestRating":"{{ $maxRating }}"
          },
          "reviewBody":"{{ $revieww->review }}"
        }
        @if($key!=($productReviews->count()-1))
        ,
        @endif
      @endforeach
    ],
    "offers": {
      "@type": "Offer",
      "url": "{{ url()->current() }}",
      "priceCurrency": "INR",
      @if($product->sale_price_start_date && $product->sale_price_start_date <= date("Y-m-d") && $product->sale_price_end_date >= date("Y-m-d"))
        "price": "{{ $product->sale_price }}",
        "priceValidUntil": "{{ date('Y-m-d',strtotime($product->sale_price_end_date)) }}",
      @else
        "price": "{{ $product->base_price }}",
      @endif
      "availability": "{{ $product->avaibility }}",
      "sku": "{{ $product->sku }}",
      "seller": { "@type": "Organization", "name": "{{ config('app.name') }}" },
    }
  }
  </script>
@endpush

@section('content')
 <!-- .product-select-section -->
 @if(isset($product) && $product)
 <section>
   <div class="container mt-4">
     <div class="row px-0">
       <!-- Product Images Section -->
       <div class="col-md-12 col-12 mt-md-0 mt-5 product_tab_v">
         <div class="row">
           @php
            $productId = $product->id;
            $productGall = $product->productGall;
            $productVideo = $product->video;
            $productQuantity = $product->avaibility;
            $productFormFields = $product->fields;
            $formDataArray = [];
            $todayDate = date("Y-m-d");
            $productSpeicalPriceFromDate = $product->sale_price_start_date;
            $productSpeicalPriceToDate = $product->sale_price_end_date;
            $productPrice = $product->base_price;
            $productSpecialPrice = $product->sale_price;
            $percentPrice = $productPrice - $productSpecialPrice;
            $percent = ($percentPrice / $productPrice) * 100;
            $isGroupPuja = ($product->type == 2 && (int)($product->is_one_to_one_puja ?? 0) !== 1);
           @endphp
           <div class="col-lg-6 col-12 product-section" id="gallery-div">
             @if($productGall->count() > 0)
             <div class="product-gallery-wrapper">
                 <ul id="imageGallery">

                     @foreach($productGall as $key => $gallery)
                         @php
                             $galleryImage = $gallery->image;
                         @endphp

                         @if($galleryImage)
                         <li class="zoom-wrapper"
                             data-thumb="{{ $galleryImage->image(150,150) }}"
                             data-src="{{ $galleryImage->originalPath }}">

                             <img src="{{ $galleryImage->image(800,800) }}"
                                  alt="{{ $gallery->alt_text ?: $product->title }}">

                             <!-- Zoom icon overlay -->
                             <span class="zoom-icon">
                                 <i class="bi bi-zoom-in"></i>
                             </span>

                         </li>
                         @endif
                     @endforeach


                     {{-- If product video exists --}}
                     @if($productVideo)
                     <li class="zoom-wrapper"
                         data-thumb="{{ $productVideo->image }}"
                         data-src="https://img.youtube.com/vi/{{ $productVideo->video_url }}/hqdefault.jpg">

                         <div class="plyr__video-embed" id="player" style="border-radius:10px;width:100%">
                             <iframe
                                 src="https://www.youtube.com/embed/{{ $productVideo->video_url }}?origin=https://plyr.io&iv_load_policy=3&modestbranding=1&playsinline=1&showinfo=0&rel=0&enablejsapi=1">
                             </iframe>
                         </div>

                         <!-- Zoom icon for video -->
                         <span class="zoom-icon">
                             <i class="bi bi-zoom-in"></i>
                         </span>

                     </li>
                     @endif

                 </ul>
             </div>
             @endif
         </div>

          @php
            $productAttributes = $product->attributes;
            $productAttributeArray=[];
            foreach($productAttributes as $key => $valueTwo){
              if($attribute=$valueTwo->attribute){
                $productAttributeArray[]="$attribute->name: $valueTwo->attribute_value";
              }
            }
            $title=$product->title;
            /* if(count($productAttributeArray)){
              $title=implode(', ', $productAttributeArray);
              $title="$product->title ($title)";
            } */
            $offerEnd = $productSpeicalPriceToDate;
          @endphp
          <div class="col-lg-6 col-12 product_details  mt-md-4 mt-lg-0">
            <h2>{{$title}}</h2>
            <div class="s-dis">
              {!! $product->specifications ? $product->specifications->short_description : '' !!}
            </div>
            <div class="col-12 d-flex gap-3 w-review justify-content-between mx-205">
              <div class="d-flex gap-1 align-items-center">
                <p class="fw-medium mb-0 fs-14 f4"><i class="bi text-rating bi-star-fill "></i> {{ $rating }} |</p>
                <p class="fw-medium mb-0  fs-14 f4">{{ $productReviews->total() }} reviews</p>
              </div>
            </div>
            {{--<div class="category-wrapper">
              @php
                $tags = $product->tags;
              @endphp
              <div class="d-flex flex-wrap category-container">
                @if($tags->count())
                  @foreach($tags as $tagKey => $tag)
                    @if($tag=$tag->tag)
                      <a href="{{route('tag', [$tag->slug])}}" target="_blank"><p class="fw-medium me-3"><i class="bi text-dark bi-tag-fill pointer"></i> {{$tag->name}}</p></a>
                    @endif
                  @endforeach
                @endif
              </div>
              <p class="read-more-btn re-btn pointer fw-bold">Read More</p>
            </div>--}}
          <div class="d-flex align-item-center">
            @if(!$isGroupPuja)
            <div id="price-div" class="mx-205">
              @if($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate))
                <div class="">
                  <span class="text-danger fw-bold fs-5">₹{{ $productSpecialPrice }}</span>
                    <strike class="text-mute fs-5 me-2">₹{{ $productPrice }}</strike>
                </div>
              @else
                <div class="">
                  <span class="text-danger fw-bold fs-5">₹{{ $productPrice }}</span>
                </div>
              @endif
            </div>
            @if($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate))
              <span class="offer-price ms-2">{{round($percent)}}% OFF</span>
            @endif
            @endif
          </div>
          @if($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate))
            <div class="mt-2 mb-3">
              <span id="offer-timer" class="text-danger fw-bold fs-6"></span>
            </div>
          @endif
            <section class="puja-section mt-1">
              <div class="container p-0">
                @if($productTypeValue == 2)
                  <div class="temple-info mb-2">
                    @if($product->place)
                    <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-geo-alt-fill text-black me-2"></i> <h6 class=" mb-1 fs-14 f6">{{ $product->place }}</h6>
                    </div>
                    @endif
                    @if(!isset($isOneToOnePujaDetail) || !$isOneToOnePujaDetail)
                    @php
                      $displayExpireDate = isset($pujaNextValidDate) && $pujaNextValidDate ? $pujaNextValidDate : $product->expire_at;
                      $showTimer = isset($pujaHasUpcomingDate) && $pujaHasUpcomingDate;
                    @endphp
                    <p class="text-black mb-1 fs-14 f6">
                      <i class="bi bi-calendar me-2 text-black"></i>
                      {{ $displayExpireDate ? date("d M, Y", strtotime($displayExpireDate)) : '' }}
                    </p>

                    @if($showTimer)
                    <p class="mb-2 fs-14 f6"><i class="bi bi-clock me-2 text-black"></i>
                 Puja Booking Will Close In :</p>
                  </div>
                  <!-- Countdown: next upcoming valid date, then next... -->
                  <div id="countdown" class="d-flex gap-2 mb-3 flex-wrap">
                    <div class="time-box-1 day"><span id="days">0</span><small>Days</small></div>
                    <div class="time-box-1 hour"><span id="hours">0</span><small>Hours</small></div>
                    <div class="time-box-1 min"><span id="minutes">0</span><small>Min</small></div>
                    <div class="time-box-1 sec"><span id="seconds">0</span><small>Sec</small></div>
                  </div>
                  @endif
                  @else
                  </div>
                  @endif
                  <!-- Devotees -->
                  <div class="d-flex align-items-center mb-3">
                    <div class="d-flex devotee">
                      <img src="{{asset('assets/img/pp-1.png')}}" class="rounded-circle me-1" alt="user">
                      <img src="{{asset('assets/img/pp-2.png')}}" class="rounded-circle me-1 me-l" alt="user">
                      <img src="{{asset('assets/img/pp-3.png')}}" class="rounded-circle me-1 me-l" alt="user">
                    </div>
                    <span class="text-danger ms-2">1 Lakh+ Devotees</span>
                  </div>
                @endif
              </div>
            </section>
            @php
              $hasVariations = false;
              $attrValues = [];
              $attrValuesTwo = [];
              $attrValuess = [];
              $allAttributeValues = [];
            @endphp
            @if($product->type == 1)
            <!-- attributes-box-start -->
              <div id="variation-div">
                @foreach($attributes as $key => $value)
                  @php
                    $attribute=$value->attribute;
                    $pAttributeValues = $attributeValue->where('attribute_id', $attribute->id);
                    $allAttributeValues[$attribute->name] = [];
                  @endphp
                  @if(count($pAttributeValues))
                    <h6>{{$attribute->name}}</h6>
                    @php
                     $attrValuess[$attribute->name] = [];
                    @endphp
                    <div class="size-options mb-2">
                      @foreach($pAttributeValues as $key1 => $value1)
                        @php
                          $hasVariations = true;
                          $lV = strtolower($value1->attribute_value);
                          $allAttributeValues[$attribute->name][] = $lV;
                          $attrValuess[$attribute->name][$lV] = $value1->values($parent_id,$value1->attribute_value);
                        @endphp
                        @if(in_array($lV, $productAttributeValue))
                          @php
                            $attrValuesTwo[$attribute->name] = $lV;
                            array_push($attrValues,$lV);
                          @endphp
                          <button class="size-box bg-white text-dark selected" onclick="handleVariation(this)" data-index="{{$key}}" data-value="{{$value1->attribute_value}}">{{$value1->attribute_value}}</button>
                        @else
                          <button class="size-box bg-white notselected-{{$lV}}" onclick="handleVariation(this)" data-index="{{$key}}" data-value="{{$value1->attribute_value}}">{{$value1->attribute_value}}</button>
                        @endif
                      @endforeach
                    </div>
                  @endif
                @endforeach
              </div>
            <!-- attributes-box-end -->
            @else
            <!-- variations starts -->
            <section class="puja-section mt-1">
              <div class="container p-0">
                @if($productVariations->count() > 0)
                  <div class="d-flex gap-3 mb-2">
                    @foreach($productVariations as $index => $productVariation)
                      @php
                        $productVariationImage = $productVariation->image;
                        $productVariationSpeicalPriceFromDate = $productVariation->sale_price_start_date;
                        $productVariationSpeicalPriceToDate = $productVariation->sale_price_end_date;
                        $productVariationPrice = $productVariation->base_price;
                        $productVariationSpecialPrice = $productVariation->sale_price;
                        $variationSpecialPriceActive = $productVariationSpecialPrice && $productVariationSpeicalPriceFromDate && $productVariationSpeicalPriceToDate && ($productVariationSpeicalPriceFromDate <= $todayDate) && ($productVariationSpeicalPriceToDate >= $todayDate);
                        $productVariationCategory = $productVariation->category;
                        $productVariationCategorySlug = $productVariationCategory->slug;
                        $detailPageUrl=route("product.detailpuja",[$productVariation->slug,"id"=>$productVariation->id]);
                        if(isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail && $productTypeName=="OneToOnePuja"){
                          $detailPageUrl=route("one.to.one.puja.slug",['slug' => $productVariation->slug,"id"=>$productVariation->id]);
                        }elseif($productTypeName=="Service"){
                          $detailPageUrl=route("product.detailservice",[$productVariation->slug,"id"=>$productVariation->id]);
                        }
                      @endphp
                      @if($productTypeValue !=3)
                      @php
                        $isFirstVariation = $loop->first;
                      @endphp
                      <div class="puja-card variation-card" onclick="selectVariation({{ $productVariation->id }}, {{ $loop->index }})" data-variation-id="{{ $productVariation->id }}" data-variation-name="{{ $productVariation->attributes->first()->attribute_value }}" data-variation-price="{{ $variationSpecialPriceActive ? $productVariationSpecialPrice : $productVariationPrice }}" data-variation-image="{{ $productVariationImage ? $productVariationImage->image(80,80) : no_image() }}" data-variation-persons="{{ $productVariation->max_order_qty }} person{{ $productVariation->max_order_qty > 1 ? 's' : '' }}" style="cursor: pointer;">
                          <img src="{{ $productVariationImage ? $productVariationImage->image(200,150) : no_image() }}" class="img-fluid rounded" alt="{{ $productVariation->alt_text ?: $productVariation->title }}">
                          <h6 class="mt-2 mb-1 text-truncate" style="max-width:170px;">
                            {{-- $productVariation->title --}}
                            {{$productVariation->attributes->first()->attribute_value}}
                          </h6>
                          <div class="variation-price-row text-danger fw-semibold mb-0" style="font-size: 0.85rem; line-height: 1.3;">
                            @if($variationSpecialPriceActive)
                              <span class="text-muted text-decoration-line-through me-1">₹{{ number_format($productVariationPrice) }}</span><span>₹{{ number_format($productVariationSpecialPrice) }}</span>
                            @else
                              <span>₹{{ number_format($productVariationPrice) }}</span>
                            @endif
                          </div>
                        </div>
                      @endif
                    @endforeach
                  </div>
                @endif
              </div>
            </section>
            <!-- variations ends -->
            @endif
            <!-- attributes-box-end -->
            @php
              $formButtonText = "Buy Now";
              $formSubmitButtonText = "Buy Now";
              if($productTypeValue == 2){
                $formButtonText = "Participate";
                $formSubmitButtonText = "Book Puja";
              }
            @endphp
            <div class="">
              <div class="d-flex gap-2 pt-0 ms-0 mb-1 mt-0 align-items-center" id="addtocart-wishlist-div" style="flex-wrap: wrap;">
                @if($productTypeValue == 1)
                  <div class="">
                    <form class="cartfom" method="post">
                      @csrf
                      <input id="main_product" type="hidden" name="item_id" value="{{ $productId }}">
                      @if($productQuantity > 0)
                        @if($user)
                          <button type="button" class="main_button mb-1" onclick="handleBuyNowProduct(this)">Buy Now</button>
                        @else
                          <button type="button" class="main_button mb-1" onclick="handleUserLoginModal('open')">Buy Now</button>
                        @endif
                        <input id="main_product_addtocart_btn" class="main_button mb-1" type="submit" value="Add to cart">
                      @else
                        <button type="submit" disabled class="main_button stock_btn mb-2">Out of Stock</button>
                      @endif
                    </form>
                  </div>
                @elseif($productFormFields->count() == 0)
                @php
                  $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
                  // Reports (type 3): always show Buy Now. Puja: show if has any upcoming valid date (multi or single)
                  $showButton = ($productTypeValue == 3) || $isOneToOnePuja || (isset($pujaHasUpcomingDate) ? $pujaHasUpcomingDate : ($product->expire_at && time() < strtotime($product->expire_at)));
                @endphp
                @if($showButton)
                  @if($user)
                    @if($isOneToOnePuja)
                      {{-- One-to-One Puja: Show Participate button that opens modal --}}
                      <button type="button" class="main_button mb-1 {{$productTypeValue == 2 ? 'w-70' : ''}} k_participate" id="participateBtn" onclick="handleParticipateClick(event)">
                        {{ $formButtonText }}
                      </button>
                    @else
                      {{-- Regular Puja: Direct checkout link --}}
                      <a href="{{ route('checkout', [strtolower($productTypeName),'item_slug'=>$product->slug]) }}" class="main_button mb-1 {{$productTypeValue == 2 ? 'w-70' : ''}}">
                        {{ $formButtonText }}
                      </a>
                    @endif
                  @else
                    <button onclick="handleUserLoginModal('open')" type="button" class="main_button mb-1 mt-1 {{$productTypeValue == 2 ? 'w-70' : ''}}">
                      {{ $formButtonText }}
                    </button>
                  @endif
                  @else
                    @php
                      $expiredButtonText = ($productTypeValue == 2) ? "Puja has expired" : (($productTypeValue == 3) ? "Report has expired" : "Product has expired");
                    @endphp
                    <button type="button" name="button" class="main_button mb-1 mt-2">{{ $expiredButtonText }}</button>
                  @endif
                @else
                @php
                  $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
                  // Reports (type 3): always show Buy Now. Puja: show if has any upcoming valid date (multi or single)
                  $showButton = ($productTypeValue == 3) || $isOneToOnePuja || (isset($pujaHasUpcomingDate) ? $pujaHasUpcomingDate : ($product->expire_at && time() < strtotime($product->expire_at)));
                @endphp
                @if($showButton)
                  @if($user)
                    @php
                      // For Puja type, disable button if no variations exist (but not for one-to-one puja)
                      $buttonDisabled = false;
                      if($productTypeValue == 2 && !$isOneToOnePuja) {
                        $buttonDisabled = !isset($productVariations) || $productVariations->count() == 0;
                      }
                    @endphp
                    <button type="button" class="main_button mb-1 {{$productTypeValue == 2 ? 'w-70' : ''}} k_participate" id="participateBtn" onclick="handleParticipateClick(event)" {{ $buttonDisabled ? 'disabled' : '' }} style="{{ $buttonDisabled ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
                      {{ $formButtonText }}
                    </button>
                  @else
                    <button onclick="handleUserLoginModal('open')" type="button" class="main_button mb-1 mt-2 {{$productTypeValue == 2 ? 'w-70' : ''}}">
                      {{ $formButtonText }}
                    </button>
                  @endif

                  @else
                    @php
                      $expiredButtonText = ($productTypeValue == 2) ? "Puja has expired" : (($productTypeValue == 3) ? "Report has expired" : "Product has expired");
                    @endphp
                    <button type="button" name="button" class="btn btn-primary p-2">{{ $expiredButtonText }}</button>
                  @endif
                @endif
                @if($productTypeValue == 1)
                  <!-- Wishlist Logic start -->
                  @if(auth()->guard('web')->check())
                    @php
                      $productWishlist = $product->wishlist;
                    @endphp
                    @if($productWishlist)
                      <button data-item_id="{{ $product->id }}" data-type="shop" data-wishlist_id="{{ $productWishlist->id }}" data-action-type="remove"  class="mb-1 toggle_wishlist wish wishlist-btnn mb-2">
                        <i class="fa fa-heart bi-heart-fill text-danger" aria-hidden="true"></i>
                        wishlist
                      </button>
                    @else
                      <button data-item_id="{{ $product->id }}"  data-type="shop" data-wishlist_id="0" data-action-type="add"  class=" mb-2 toggle_wishlist wish wishlist-btnn  mb-1">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        wishlist
                      </button>
                    @endif
                  @else
                    @if($productTypeValue == 1)
                      <button onclick="handleUserLoginModal('open')" class="mb-1 wish wishlist-btnn">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        wishlist
                      </button>
                    @endif
                  @endif
                @endif
                @php
                  $canShowShareAndEarn = false;
                  if($user && $user->is_influencer == 1) {
                    // Check if product is not out of stock or expired
                    if($productTypeValue == 1) {
                      // Regular products - check availability
                      $canShowShareAndEarn = ($productQuantity > 0);
                    } elseif($productTypeValue == 2) {
                      // Puja products - hide if expired (use upcoming valid dates for group puja)
                      $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
                      $isPujaExpired = isset($pujaHasUpcomingDate) ? !$pujaHasUpcomingDate : (isset($product->expire_at) && !empty($product->expire_at) && (strtotime($product->expire_at) <= time()));
                      if($isPujaExpired) {
                        $canShowShareAndEarn = false;
                      } else {
                        $canShowShareAndEarn = true;
                      }
                    } elseif($productTypeValue == 3) {
                      // Services/Reports - hide if expired
                      $isExpired = false;
                      if(isset($product->expire_at) && !empty($product->expire_at)) {
                        $isExpired = (strtotime($product->expire_at) <= time());
                      }
                      // Show button only if not expired and (has availability or availability not set)
                      if($isExpired) {
                        $canShowShareAndEarn = false;
                      } else {
                        $canShowShareAndEarn = ($productQuantity > 0 || !isset($product->avaibility));
                      }
                    }
                  }
                @endphp
                @if($canShowShareAndEarn)
                  <button type="button" class="main_button mb-1 share-earn-btn" onclick="handleShareAndEarn('{{ $product->id }}', 'product')">
                    Share and Earn
                  </button>
                @endif
                <div class="de-share">
                  <a class="a2a_dd" href="https://www.addtoany.com/share">
                  <i class="bi bi-share share-i text-danger"></i>
                  </a>
                </div>
                <!-- Wishlist Logic end -->
              </div>
              @if($productTypeValue == 1 && $productQuantity >= 1)
                <div class="mt-3 mb-3">
                  <span class="border border-0 border-danger p-2 text-danger fw-bold fs-6 unit_left" style="border-radius:4px;border:0px solid !important;">
                    <img src="{{asset('assets/images/boxes.svg')}}" alt="" class="me-2">
                    <span class="me-2 text-black">Last {{ $productQuantity }} Units Left</span>
                  </span>
                </div>
              @endif
              @if($deliveryDate = $product->deliveryDate)
                @php
                  $fromDeliveryDateNumber = $deliveryDate->from;
                  $toDeliveryDateNumber = $deliveryDate->to;
                @endphp
                <div id="deliveryResult" class="delivery-date p-2 mb-3 rounded shadow-sm">
                <div class="d-flex align-items-center mb-1">
                  <i class="bi bi-truck fs-4 text-black me-2"></i>
                  <span class="fw-bold fs-6 text-dark">Expected Delivery</span>
                </div>
                <div class="ps-1">
                  <span class="text-black">Between</span>
                  <span class="fw-semibold text-success">
                    {{ date("D, d M Y", strtotime("now+$fromDeliveryDateNumber days")) }}
                  </span>
                  <span class="text-muted">to</span>
                  <span class="fw-semibold text-success">
                    {{ date("D, d M Y", strtotime("now+$toDeliveryDateNumber days")) }}
                  </span>
                </div>
              </div>
              @endif
              <!-- By now form Modal start -->
              <div class="modal fade " id="shareDetailsModal" tabindex="-1" aria-labelledby="shareDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-md mt-5 pt-5">
                  <div class="modal-content report_popup">
                      @php
                        $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
                      @endphp
                      @if($isOneToOnePuja)
                      <!-- One-to-One Puja Static Form -->
                      <div class="sticky-sidebar p-0">
                        <div class="card bg-white border-0 puja-form-card">
                          <!-- Form Header -->
                          <div class="puja-form-header" id="headingOne">
                            <button type="button" class="btn-close puja-form-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="d-flex align-items-center justify-content-between">
                              <div class="puja-form-header-content">
                                <h4 class="puja-form-title">{{ $product->title }}</h4>
                                <!-- <p class="puja-form-persons mb-0">1 person</p> -->
                                @if(!$isGroupPuja)
                                <p class="puja-form-price mt-2">₹{{ ($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate)) ? $productSpecialPrice : $productPrice }}</p>
                                @endif
                              </div>
                              <div class="puja-form-header-image">
                                <img src="{{ $product->image ? $product->image->image(80,80) : no_image() }}" alt="Puja" class="puja-header-img">
                              </div>
                            </div>
                          </div>
                          <!-- Form Body -->
                          <div class="puja-form-body">
                            <form id="productField" class="js-add-to-cart-form" action="{{ route('cart.addform') }}" method="post">
                              @csrf
                              <input type="hidden" name="item_id" id="form_item_id" value="{{ $product->id }}">
                              <div class="row">
                                <!-- Name Field -->
                                <div class="col-12 mb-3">
                                  <div class="form-group">
                                    <label for="name" class="puja-form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control puja-form-input" required>
                                  </div>
                                </div>

                                <!-- Gotra Field -->
                                <div class="col-12 mb-3">
                                  <div class="form-group">
                                    <label for="gotra" class="form-label fw-semibold mb-1">Gotra</label>
                                    <div class="row">
                                      <div class="col-6">
                                        <input type="text" name="gotra" id="gotra" class="form-control form-control-lg" placeholder="Enter Gotra" oninput='handleGotraInput(this)'>
                                      </div>
                                      <div class="col-6">
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="gotra_unknown" name="gotra_unknown" value="I do not know my gotra" onchange="handleGotraCheckbox(this)">
                                          <label class="form-check-label" for="gotra_unknown" style="font-size: 14px;">
                                            I do not know my gotra
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <!-- Ashirwad Box Section -->
                                <div class="col-12 mb-4">
                                  <div class="ashirwad-box-section">
                                    <h6 class="ashirwad-title">Would you like to receive the Ashirwad box?</h6>
                                    <p class="ashirwad-description">The Ashirwad Box will contain divine blessing elements such as Ganga Jal, and more, sourced from sacred Tirth locations.</p>

                                    <div class="ashirwad-buttons">
                                      <button type="button" class="btn ashirwad-yes-btn" id="ashirwad_yes" onclick="handleAshirwadOption('yes')" style="background-color: #fff; color: #6c757d; border: 2px solid #6c757d;">Yes</button>
                                      <button type="button" class="btn ashirwad-no-btn active" id="ashirwad_no" onclick="handleAshirwadOption('no')" style="background-color: #0e4677; color: #fff; border-color: #0e4677;">No</button>
                                    </div>

                                    <!-- Address Selection (shown when Yes is selected) -->
                                    <div id="ashirwad_address_section" style="display: none;">
                                      <div class="mb-3">
                                        <h6 class="fw-semibold my-2">Select Delivery Address</h6>
                                        <!-- Addresses List -->
                                        <div id="ashirwad_addresses_list" class="mb-3">
                                          <!-- Addresses will be loaded here via JavaScript -->
                                        </div>

                                        <!-- Add New Address Option -->
                                        <div class="mb-3">
                                          <div class="d-flex align-items-center text-purple" style="cursor: pointer;" onclick="toggleNewAddressForm()">
                                            <i class="bi bi-plus-circle me-2 fs-5"></i>
                                            <span class="fw-semibold">Add a new delivery address</span>
                                          </div>
                                        </div>

                                        <!-- New Address Form -->
                                        <div id="ashirwad_new_address_form" style="display: none;" class="border rounded p-3 bg-light mt-3">
                                          <h6 class="fw-semibold mb-3">New delivery address</h6>
                                          <div class="row">
                                            <div class="col-12 mb-2">
                                              <label for="ashirwad_address" class="puja-form-label">Street/House Address <span class="text-danger">*</span></label>
                                              <textarea class="form-control puja-form-input" id="ashirwad_address" name="ashirwad_address" rows="2" placeholder="Enter your address"></textarea>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                              <label for="ashirwad_state_id" class="puja-form-label">State/Region <span class="text-danger">*</span></label>
                                              <select class="form-select puja-form-input" id="ashirwad_state_id" name="ashirwad_state_id" onchange="fetchCitiesForAshirwad(this)">
                                                <option value="">Select State</option>
                                              </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                              <label for="ashirwad_city_id" class="puja-form-label">City <span class="text-danger">*</span></label>
                                              <select class="form-select puja-form-input" id="ashirwad_city_id" name="ashirwad_city_id">
                                                <option value="">Select City</option>
                                              </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                              <label for="ashirwad_pincode" class="puja-form-label">Pin Code <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control puja-form-input" id="ashirwad_pincode" name="ashirwad_pincode" placeholder="Enter pincode" maxlength="6">
                                            </div>
                                            <div class="col-12 text-end">
                                              <!-- <button type="button" class="btn btn-secondary btn-sm me-2" onclick="toggleNewAddressForm()">Cancel</button> -->
                                              <button type="button" class="btn btn-secondary btn-sm" onclick="saveNewAshirwadAddress()">Save Address</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="puja-form-submit-wrapper">
                                <button type="submit" class="btn puja-book-btn" id="service-submit-btn">Book Puja</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      @elseif($productTypeValue != 1 && $productFormFields->count())
                      <div class="sticky-sidebar p-0">
                        <div class="card bg-white border-0 puja-form-card">
                          <!-- Form Header -->
                          <div class="puja-form-header" id="headingOne">
                            <button type="button" class="btn-close puja-form-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="d-flex align-items-center justify-content-between">
                              <div class="puja-form-header-content">
                                <h4 class="puja-form-title" id="variation-header-name">{{ $product->title }}</h4>
                                <p class="puja-form-persons mb-0" id="variation-header-persons">1 person</p>
                                @if(!$isGroupPuja)
                                <p class="puja-form-price" id="variation-header-price">₹{{ ($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate)) ? $productSpecialPrice : $productPrice }}</p>
                                @else
                                <p class="puja-form-price" id="variation-header-price" style="display:none;"></p>
                                @endif
                              </div>
                              <div class="puja-form-header-image">
                                <img id="variation-header-image" src="{{ $product->image ? $product->image->image(80,80) : no_image() }}" alt="Variation" class="puja-header-img">
                              </div>
                            </div>
                          </div>
                          <!-- Form Body -->
                          <div class="puja-form-body">
                            <form id="productFieldReport" class="js-add-to-cart-form" action="{{ route('cart.addform') }}" method="post">
                              @csrf
                              <input type="hidden" name="item_id" id="form_item_id" value="{{ $product->id }}">
                              <div id="variation-form-container" class="row">
                                @php
                                  $index = -1;
                                  $genderNames = ["gender", "p1_gender", "p2_gender"];
                                  $placesNames = ["birth_place", "p1_birth_place", "p2_birth_place"];

                                  // Count fields in "other all form" section (excluding P1 and P2)
                                  $otherFieldsCount = 0;
                                  foreach($productFormFields as $field) {
                                    if($field->field) {
                                      $fieldLabel = $field->field->form_label;
                                      if(!str_contains($fieldLabel, "P1") && !str_contains($fieldLabel, "P2")) {
                                        $otherFieldsCount++;
                                      }
                                    }
                                  }
                                  $gotraIndex = count($productFormFields) * $product->max_order_qty;
                                @endphp
                                @for($i = 1; $i <= $product->max_order_qty; $i++)
                                  {{--@if($product->max_order_qty > 1)
                                    <div class="col-md-6 col-12"><h6 class="fw m-0 mt-2">{{ $i }}. Details</h6></div>
                                  @endif--}}
                                  @php
                                    $p1Heading = false;
                                    $p2Heading = false;
                                  @endphp

                                  @foreach($productFormFields as $indexx => $field)
                                    @if($formField = $field->field)
                                      @php
                                        $index++;
                                        $type = $formField->form_type;
                                        $label = $formField->form_label;
                                        $name = strtolower(str_replace(' ', '_', $label));
                                        $required = $formField->isrequired;
                                        if($i > $product->min_order_qty){
                                          $required = 0;
                                        }
                                        $formFieldArray = [
                                          "type" => $type,
                                          "label" => $label,
                                          "name" => $name,
                                        ];
                                        $formDataArray[] = $formFieldArray;
                                      @endphp

                                      @if(str_contains($formField->form_label, "P1"))
                                        <!-- boy form -->
                                        @if(!$p1Heading)
                                          @php
                                            $p1Heading = true;
                                          @endphp
                                          <div class="col-6">
                                            <div class="row">
                                              <div class="col-12 d-flex align-items-center mb-2">
                                                <img src="{{ asset('assets/images/icons/man.png') }}" width="30px" height="30px" alt="man">
                                                <h6 class="fw m-0 mt-2 ps-1">Boy Details</h6>
                                              </div>
                                        @endif

                                        @if($type == "select" && $name == "p1_gender")
                                          <div class="col-md-6 col-12 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="puja-form-label">{{ str_replace(["P1", "P2"], [" ", "Girl"], $label) }}</label>
                                              <select class="form-select puja-form-input" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                                                @if(in_array($name, $genderNames))
                                                  <option value="">Select</option>
                                                  <option value="male">Male</option>
                                                  <option value="female">Female</option>
                                                  <option value="other">Other</option>
                                                @endif
                                              </select>
                                            </div>
                                          </div>
                                        @elseif($type == "search")
                                          <div class="col-md-6 col-12 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="puja-form-label">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                              @if(in_array($name, $placesNames))
                                                <input type="text" class="form-control puja-form-input" name="{{ $name }}_search" id="{{ $name }}" onkeyup='locationPlaces("#{{ $name }}", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} minlength="3">
                                              @endif
                                              <div class="suggestion_birth">
                                                <ul id="{{ $name }}_suggestions"></ul>
                                              </div>
                                            </div>
                                          </div>
                                        @else
                                          <div class="col-md-6 col-12 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="puja-form-label">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                              @if($type == 'time')
                                                <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
                                              @endif
                                              <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control puja-form-input {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1">
                                            </div>
                                          </div>
                                        @endif
                                      @elseif(str_contains($formField->form_label, "P2"))
                                        <!-- girl form -->
                                        @if(!$p2Heading)
                                          @php
                                            $p2Heading = true;
                                            $p2FormEnd = false;
                                          @endphp
                                            </div>
                                          </div>
                                          <div class="col-6">
                                            <div class="row">
                                              <div class="col-12 d-flex align-items-center mb-2">
                                                <img src="{{ asset('assets/images/icons/woman.png') }}" width="30px" height="30px" alt="woman">
                                                <h6 class="fw m-0 mt-2 ps-1">Girl Details</h6>
                                              </div>
                                        @endif

                                        @if($type == "select" && $name == "p2_gender")
                                          <div class="col-md-6 col-12 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="puja-form-label">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                              <select class="form-select puja-form-input" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                                                @if(in_array($name, $genderNames))
                                                  <option value="">Select</option>
                                                  <option value="male">Male</option>
                                                  <option value="female">Female</option>
                                                  <option value="other">Other</option>
                                                @endif
                                              </select>
                                            </div>
                                          </div>
                                        @elseif($type == "search")
                                          <div class="col-md-6 col-12 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="puja-form-label">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                              @if(in_array($name, $placesNames))
                                                <input type="text" class="form-control puja-form-input" name="{{ $name }}_search" id="{{ $name }}" onkeyup='locationPlaces("#{{ $name }}", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} minlength="3">
                                              @endif
                                              <div class="suggestion_birth">
                                                <ul id="{{ $name }}_suggestions"></ul>
                                              </div>
                                            </div>
                                          </div>
                                        @else
                                          <div class="col-md-6 col-12 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="puja-form-label">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
                                              @if($type == 'time')
                                                <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
                                              @endif
                                              <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control puja-form-input {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1">
                                            </div>
                                          </div>
                                        @endif
                                        @if($productFormFields->count()-1 == $indexx && isset($p2FormEnd) && $p2FormEnd == false)
                                          </div>
                                        </div>
                                          @php
                                            $p2FormEnd = true;
                                          @endphp
                                        @endif
                                      @else
                                        <!-- other all form -->
                                        @if(isset($p2FormEnd) && $p2FormEnd == false)
                                          </div>
                                        </div>
                                          @php
                                            $p2FormEnd = true;
                                          @endphp
                                        @endif

                                        @if($type == "select" && $name == "language")
                                          <div class="col-6 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="puja-form-label">{{ str_replace(["P1", "P2"], [" ", "Girl"], $label) }}</label>
                                              <select class="form-select puja-form-input" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                                                <option value="">Select</option>
                                                <option value="english">English</option>
                                                <option value="hindi">Hindi</option>
                                              </select>
                                            </div>
                                          </div>
                                        @elseif($type == "select" && $name == "gender")
                                          <div class="col-md-6 col-6 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
                                              <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                                                @if(in_array($name, $genderNames))
                                                  <option value="">Select</option>
                                                  <option value="male">Male</option>
                                                  <option value="female">Female</option>
                                                  <option value="other">Other</option>
                                                @endif
                                              </select>
                                            </div>
                                          </div>
                                        @elseif($type == "search")
                                          <div class="col-md-6 col-6 mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
                                              @if(in_array($name, $placesNames))
                                                <input type="text" class="form-control form-control-lg" name="{{ $name }}_search" id="{{ $name }}" onkeyup='locationPlaces("#{{ $name }}", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} minlength="3">
                                              @endif
                                              <div class="suggestion_birth">
                                                <ul id="{{ $name }}_suggestions"></ul>
                                              </div>
                                            </div>
                                          </div>
                                        @else
                                          @php
                                            // Check if this is name field and if it's the only field
                                            $isNameField = (stripos($label, 'name') !== false || $name == 'name' || $name == 'full_name' || $name == 'first_name');
                                            $colClass = 'col-md-6 col-6';
                                            if($isNameField && $otherFieldsCount == 1) {
                                              $colClass = 'col-12';
                                            }
                                          @endphp
                                          <div class="{{ $colClass }} mb-2">
                                            <div class="form-group">
                                              <label for="{{ $name }}" class="puja-form-label">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
                                              @if($type == 'time')
                                                <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
                                              @endif
                                              <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control puja-form-input {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1">
                                            </div>
                                          </div>
                                        @endif
                                      @endif

                                    @endif
                                  @endforeach
                                @endfor

                                @if($productTypeValue == 2)
                                <!-- Gotra Field - Puja only -->
                                <div class="col-12 mb-3">
                                  <div class="form-group">
                                    <label for="gotra" class="form-label fw-semibold mb-1">Gotra</label>
                                    <div class="row">
                                      <div class="col-6">
                                        <input type="text" name="gotra" id="gotra" class="form-control form-control-lg" placeholder="Enter Gotra" oninput='addFormData(this, {"type":"text","label":"Gotra","name":"gotra"}, "{{ $gotraIndex }}")'>
                                      </div>
                                      <div class="col-6">
                                        <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="gotra_unknown" name="gotra_unknown" value="I do not know my gotra" onchange="handleGotraCheckbox(this)">
                                          <label class="form-check-label" for="gotra_unknown" style="font-size: 14px;">
                                            I do not know my gotra
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <!-- Ashirwad Box Section - Puja only -->
                                <div class="col-12 mb-4">
                                  <div class="ashirwad-box-section">
                                    <h6 class="ashirwad-title">Would you like to receive the Ashirwad box?</h6>
                                    <p class="ashirwad-description">The Ashirwad Box will contain divine blessing elements such as Ganga Jal, and more, sourced from sacred Tirth locations.</p>

                                    <div class="ashirwad-buttons">
                                      <button type="button" class="btn ashirwad-yes-btn" id="ashirwad_yes" onclick="handleAshirwadOption('yes')" style="background-color: #fff; color: #6c757d; border: 2px solid #6c757d;">Yes</button>
                                      <button type="button" class="btn ashirwad-no-btn active" id="ashirwad_no" onclick="handleAshirwadOption('no')" style="background-color: #0e4677; color: #fff; border-color: #0e4677;">No</button>
                                    </div>

                                    <!-- Address Selection (shown when Yes is selected) -->
                                    <div id="ashirwad_address_section" style="display: none;">
                                      <div class="mb-3">
                                        <h6 class="fw-semibold my-2">Select Delivery Address</h6>
                                        <!-- Addresses List -->
                                        <div id="ashirwad_addresses_list" class="mb-3">
                                          <!-- Addresses will be loaded here via JavaScript -->
                                        </div>

                                        <!-- Add New Address Option -->
                                        <div class="mb-3">
                                          <div class="d-flex align-items-center text-purple" style="cursor: pointer;" onclick="toggleNewAddressForm()">
                                            <i class="bi bi-plus-circle me-2 fs-5"></i>
                                            <span class="fw-semibold">Add a new delivery address</span>
                                          </div>
                                        </div>

                                        <!-- New Address Form -->
                                        <div id="ashirwad_new_address_form" style="display: none;" class="border rounded p-3 bg-light mt-3">
                                          <h6 class="fw-semibold mb-3">New delivery address</h6>
                                          <div class="row">
                                            <div class="col-12 mb-2">
                                              <label for="ashirwad_address" class="puja-form-label">Street/House Address <span class="text-danger">*</span></label>
                                              <textarea class="form-control puja-form-input" id="ashirwad_address" name="ashirwad_address" rows="2" placeholder="Enter your address"></textarea>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                              <label for="ashirwad_state_id" class="puja-form-label">State/Region <span class="text-danger">*</span></label>
                                              <select class="form-select puja-form-input" id="ashirwad_state_id" name="ashirwad_state_id" onchange="fetchCitiesForAshirwad(this)">
                                                <option value="">Select State</option>
                                              </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                              <label for="ashirwad_city_id" class="puja-form-label">City <span class="text-danger">*</span></label>
                                              <select class="form-select puja-form-input" id="ashirwad_city_id" name="ashirwad_city_id">
                                                <option value="">Select City</option>
                                              </select>
                                            </div>
                                            <div class="col-md-4 mb-2">
                                              <label for="ashirwad_pincode" class="puja-form-label">Pin Code <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control puja-form-input" id="ashirwad_pincode" name="ashirwad_pincode" placeholder="Enter pincode" maxlength="6">
                                            </div>
                                            <div class="col-12 text-end">
                                              <button type="button" class="btn btn-secondary btn-sm me-2" onclick="toggleNewAddressForm()">Cancel</button>
                                              <button type="button" class="btn btn-primary btn-sm" onclick="saveNewAshirwadAddress()">Save Address</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                @endif
                              </div>
                              <div class="puja-form-submit-wrapper">
                                <button type="submit" class="btn puja-book-btn" id="service-submit-btn">{{ $formSubmitButtonText }}</button>
                              </div>
                            </form>
                            <div id="variation-form-loading" style="display: none; text-align: center; padding: 20px;">
                              <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                </div>
              </div>
            </div>
            <!-- By now form Modal end -->
            <div class="astro-cta-wrapper">
            <div class="astro-cta-box">
              <h3>Need Guidance Before Buying?</h3>

              <div class="astro-cta-buttons">
                <a href="{{ route('astrologers.chatwithastrologers') }}" class="astro-btn chat-btn">
                  <i class="fa fa-commenting"></i>
                  Chat With Astrologers

                </a>
              <a href="{{ route('astrologers.talktoastrologers') }}" class="astro-btn call-btn">
                <i class="fa fa-volume-control-phone"></i>
                Talk To Astrologers

              </a>


            </div>
            </div>
          </div>

            <div class="accordion" id="accordionPanelsStayOpenExample">
              @if($product->specifications && $product->specifications->description)
                <div class="accordion-item">
                  <h2 class="accordion-header" id="panelsStayOpen-heading-1">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-1" aria-expanded="true" aria-controls="panelsStayOpen-collapse-1">
                      Description
                    </button>
                  </h2>
                  <div id="panelsStayOpen-collapse-1" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading-1">
                    <div class="accordion-body">
                      {!! $product->specifications ? $product->specifications->description : '' !!}
                    </div>
                  </div>
                </div>
              @endif
              @foreach($product->productSpec as $key => $s)
             <div class="accordion-item">
               <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                 <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse{{$key}}" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                   {{$s->heading}}
                 </button>
               </h2>
               <div id="panelsStayOpen-collapse{{$key}}" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading{{$key}}">
                 <div class="accordion-body">
                   {{$s->text}}
                 </div>
               </div>
             </div>
             @endforeach
           </div>
           @php
            $productFeatures = $product->productFeatures;
            $colors = ['bg-1','bg-2','bg-3','bg-4'];
           @endphp
           @if($productFeatures->count() && $productTypeValue == 1)
           <section class="services py-4 pt-2">
              <div class="container px-0">
                <div class="d-flex align-items-center">
                  @foreach($productFeatures as $key => $productFeature)
                    @php
                      $productFeatureImage = $productFeature->image;
                    @endphp
                    <div class="ps-0 me-2">
                      <div class="service-card py-1 px-2 h-100 {{$colors[$key % count($colors)]}}">
                        <img src="{{$productFeatureImage ?  $productFeatureImage->image(100,100) : no_image()}}" alt="{{ $productFeature->alt_text ?: $productFeature->title }}" class="de-i">
                        <h6 class="mb-0">{{ $productFeature->f_heading }}</h6>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </section>
          @endif
        </div>
    </div>



              <!-- tab-setion-start -->
              <ul id="myTab" role="tablist" class="nav nav-tabs mt-2 product-tab-new">
                   {{--<li role="presentation" class="nav-item" style="font-size: 16px;">
                   <a id="description-tab" data-bs-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="false" class="nav-link text-dark active" tabindex="-1">Description</a>
                 </li>--}}
                   {{--<li role="presentation" class="nav-item" style="font-size: 16px;">
                   <a id="specifications-tab" data-bs-toggle="tab" href="#specifications" role="tab" aria-controls="specifications" aria-selected="false" class="nav-link text-dark" tabindex="-1">Specifications</a>
                   </li>--}}
                   {{--<li role="presentation" class="nav-item" style="font-size: 16px;">
                   <a id="features-tab" data-bs-toggle="tab" href="#features" role="tab" aria-controls="features" aria-selected="false" class="nav-link text-dark" tabindex="-1">Features</a>
                  </li>--}}
                   {{--@if($tags->count())
                     <li role="presentation" class="nav-item" style="font-size: 16px;">
                     <a id="tags-tab" data-bs-toggle="tab" href="#tags" role="tab" aria-controls="tags" aria-selected="false" class="nav-link text-dark" tabindex="-1">Tags</a>
                     </li>
                   @endif--}}
               </ul>

               <div id="myTabContent" class="tab-content">
               <div id="description" role="tabpanel" aria-labelledby="description-tab" class="tab-pane fade active show">
                 {{--<div class="mt-3">
                       {!! $product->specifications ? $product->specifications->description : '' !!}
                 </div>--}}
                 </div>
                  {{--<div id="specifications" role="tabpanel" aria-labelledby="specifications-tab" class="tab-pane fade">
                     <div class="mt-2">
                       @if($product && count($product->productSpec))
                       <table class="table table-bordered">
                         <tbody>
                           @if($model_no = $product->model_no)
                           <tr>
                             <th class="">Model No.</th>
                             <td class="">{{$model_no}}</td>
                           </tr>
                           @endif
                           @if($isbn = $product->isbn)
                           <tr>
                             <th class="">ISBN</th>
                             <td class="">{{$isbn}}</td>
                           </tr>
                           @endif
                           @foreach($product->productSpec as $p)
                           <tr>
                             <th class="">{{$p->heading}}</th>
                             <td class="">{{$p->text}}</td>
                           </tr>
                           @endforeach
                         </tbody>
                       </table>
                       @endif
                     </div>
                   </div>--}}
                   {{--<div id="features" role="tabpanel" aria-labelledby="features-tab" class="tab-pane fade">
                     <div class="row mt-2">
                        @foreach($product->productFeatures as $feature)
                        <div class="col-md-6 mb-2">
                          <div class="card feature-ll-card p-2">
                            <div class="icon-f-main d-flex align-items-center ">
                            <div class="">
                              <div class="feature-list-img d-flex align-items-center justify-content-center">
                                <i class="bi bi-star-fill text-white fs-5"></i>
                              </div>
                            </div>
                              <p class="ms-3 fs-7 pb-0 mb-0 feature-card-text side-scrollbar" style="font-weight: 600">{{ $feature->heading }}</p>
                            </div>
                          </div>
                        </div>
                          @endforeach
                      </div>
                  </div>--}}
                  {{--@if($tags->count())
                  <div id="tags" role="tabpanel" aria-labelledby="tags-tab" class="tab-pane fade">
                    <div class="mt-2">
                      @foreach($tags as $tagKey => $tag)
                        @if($tag=$tag->tag)
                          <a class="badge text-bg-danger py-2 px-3" href="{{route('tag', [$tag->slug])}}">{{$tag->name}}</a>
                        @endif
                      @endforeach
                    </div>
                  </div>
                  @endif--}}
              </div>
              <!-- tab-setion-end -->

               </div>
               <div class="col-md-3 px-0">

               </div>
           </div>
        </div>
    </div>
</section>
@endif



<!-- ratings-section -->


<!-- new-review-section-end -->


@php
  $totalReviews = $productReviews->count();
@endphp
@if($totalReviews > 0)
<section class="Rating pt-3 pt-lg-5">
  <div class="container">
    <div class="rating-section">
       <h2>Rating & Reviews</h2>
      <div class="row">
        <div class="col-lg-4 col-12 px-0">
          <!-- ====== Review Summary Section ====== -->
             <section class="review-summary-box mb-4 mt-1 mt-lg-3">
               <div class="container">
                 <div class="review-card border p-4 rounded-3">

                   <h5 class="review-title fw-bold mb-4 text-black  text-center">Reviews</h5>

                   <div class="mb-4 text-center">
                     <div class="col-md-12">
                       <div class="review-score d-flex justify-content-center align-items-center">
                         <i class="fa fa-star text-warning fs-1 me-2"></i>
                         <h1 class="mb-0 text-warning fw-bold">{{ $maxRating }}</h1>
                       </div>
                       <p class="mb-0 text-muted">
                         <span class="fw-semibold text-dark">{{ $productReviews->total() }}</span> reviews
                       </p>
                     </div>

                     <div class="col-md-12">
                       <div class="rating-bar">
                         <div class="d-flex align-items-center mb-1">
                           <span class="me-2">5 <i class="fa fa-star text-warning small"></i></span>
                           <div class="progress flex-grow-1 me-2">
                             <div class="progress-bar bg-warning" style="width: {{ ($ratingCounts[5]/$productReviews->total())*100 }}%;"></div>
                           </div>
                           <span class="text-muted small">{{ $ratingCounts[5] }}</span>
                         </div>
                         <div class="d-flex align-items-center mb-1">
                           <span class="me-2">4 <i class="fa fa-star text-warning small"></i></span>
                           <div class="progress flex-grow-1 me-2">
                             <div class="progress-bar bg-warning" style="width: {{ ($ratingCounts[4]/$productReviews->total())*100 }}%;"></div>
                           </div>
                           <span class="text-muted small">{{ $ratingCounts[4] }}</span>
                         </div>
                         <div class="d-flex align-items-center mb-1">
                           <span class="me-2">3 <i class="fa fa-star text-warning small"></i></span>
                           <div class="progress flex-grow-1 me-2">
                             <div class="progress-bar bg-warning" style="width: {{ ($ratingCounts[3]/$productReviews->total())*100 }}%;"></div>
                           </div>
                           <span class="text-muted small">{{ $ratingCounts[3] }}</span>
                         </div>
                         <div class="d-flex align-items-center mb-1">
                           <span class="me-2">2 <i class="fa fa-star text-warning small"></i></span>
                           <div class="progress flex-grow-1 me-2">
                             <div class="progress-bar bg-warning" style="width: {{ ($ratingCounts[2]/$productReviews->total())*100 }}%;"></div>
                           </div>
                           <span class="text-muted small">{{ $ratingCounts[2] }}</span>
                         </div>
                         <div class="d-flex align-items-center">
                           <span class="me-2">1 <i class="fa fa-star text-warning small"></i></span>
                           <div class="progress flex-grow-1 me-2">
                             <div class="progress-bar bg-warning" style="width: {{ ($ratingCounts[1]/$productReviews->total())*100 }}%;"></div>
                           </div>
                           <span class="text-muted small">{{ $ratingCounts[1] }}</span>
                         </div>
                       </div>
                     </div>
                   </div>

                   {{--<div class="review-footer text-center mt-4">
                      <p class="text-warning fw-semibold mb-2">Click to review</p>
                      <div class="click-rating">
                        <i class="fa fa-star-o text-warning fs-4 me-1" data-index="1"></i>
                        <i class="fa fa-star-o text-warning fs-4 me-1" data-index="2"></i>
                        <i class="fa fa-star-o text-warning fs-4 me-1" data-index="3"></i>
                        <i class="fa fa-star-o text-warning fs-4 me-1" data-index="4"></i>
                        <i class="fa fa-star-o text-warning fs-4" data-index="5"></i>
                      </div>
                    </div>--}}
                 </div>
               </div>
             </section>

        </div>
        <div class="col-lg-8 col-12">
          <div class="rating-box rating-box-2">
            @php
             $avgRating = round($productReviews->avg('rating'), 1);
           @endphp
           <span class="stars">
             @for ($i = 1; $i <= 5; $i++)
               @if($i <= floor($avgRating))
                 <i class="bi bi-star-fill"></i>
               @else
                 <i class="bi bi-star"></i>
               @endif
             @endfor
           </span>
           <span class="score">{{ $avgRating }}/5</span>
         </div>
         <div class="reviews-container">
          @foreach($productReviews as $key => $review)
            @php
              $reviewUser = $review->user;
            @endphp
            @if($reviewUser)
              @php
                $userReviewImage = $reviewUser->image;
              @endphp
              <div class="review-item">
                <div class="d-flex">
                  <img src="{{ $userReviewImage ? $userReviewImage->image(88,88) : asset('assets/img/user_default.jpg') }}" alt="{{ $reviewUser->name }}">
                  <div class="review-content">
                    <h4 class="mt-2">{{ $reviewUser->name }}</h4>
                    <div class="stars">
                      @for($i = 0; $i < $review->rating; $i++)
                        <i class="bi bi-star-fill"></i>
                      @endfor
                    </div>
                    <p class="mb-2">{{ $review->review }}</p>
                  </div>
                </div>
                @if($review->images)
                  @php
                    $images  = json_decode($review->images, true);
                  @endphp
                  <div class="col-12">
                    <ul id="reviewSlider" class="cs-hidden">
                      @foreach($images as $image)
                          <li data-src="{{ $image['original'] }}">
                              <img src="{{ $image['img'] }}"
                                   alt="{{ $image['file'] }}"
                                   style="width:100%; border-radius:6px; cursor: zoom-in;">
                          </li>
                      @endforeach
                  </ul>
                </div>
                @endif
              </div>
            @else
              <div class="review-item">
                <img src="{{ asset('assets/img/user_default.jpg') }}" alt="Unknown">
                <div class="review-content">
                  <h4 class="mt-2">Unknown</h4>
                  <div class="stars">
                    @for($i = 0; $i < $review->rating; $i++)
                      <i class="bi bi-star-fill"></i>
                    @endfor
                  </div>
                  <p>{{ $review->review }}</p>
                </div>
              </div>
            @endif
          @endforeach
          {!! $productReviews->links('vendor.pagination.bootstrap-5') !!}
        </div>
        </div>

        </div>
      </div>
  </div>
</section>
@endif
<!-- Related Products -->
@php
if ($productTypeValue == 2) {
      $products = $products->where('expire_at', ">=", now());
  }
@endphp
@if(isset($products) && count($products))
  <section class="overflow-hidden">
    <div class="container">
      @if($productTypeValue == 1)
        <h2 class="m-heading mt-3 mt-lg-5">Related Products</h2>
        <div class="all-category brands-shops">
          @foreach($products as $key => $item)
            @include(themeView()."product.card", [
              "product" => $item,
              "user" => $user,
              "productTypeName" => $productTypeName,
              "productTypeValue" => 1
            ])
          @endforeach
        </div>
      @elseif($productTypeValue == 2)
        <h2 class="m-heading mt-3 mt-md-5">Related Pujas</h2>
        <div class="all-category brands-shops">
          @foreach($products as $key => $item)
            @include(themeView()."product.cardpuja", [
              "product" => $item,
              "user" => $user,
              "productTypeName" => $productTypeName,
              "productTypeValue" => 2
            ])
          @endforeach
        </div>
      @else
        <h2 class="m-heading mt-3 mt-md-5">Related Services</h2>
        <div class="all-category brands-shops">
          @foreach($products as $key => $item)
            @include(themeView()."product.cardservice", [
              "product" => $item,
              "user" => $user,
              "productTypeName" => $productTypeName,
              "productTypeValue" => 3
            ])
          @endforeach
        </div>
      @endif
    </div>
</section>
@endif

@if(isset($faqs) && count($faqs))
  <section class="py-0 py-md-5 mt-3">
    <div class="container  container-xl pt-0">
      <div class="row justify-content-center">
        <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center">
          <h2 class="fs-3 heading">Frequently Asked Questions</h2>
          <p class="fs-6 text-center text-black">Get answers to all your queries with our FAQs.</p>
        </div>
      </div>
      <div class="row justify-content-center py-3">
        <div class="col-lg-8">
          <div class="accordion" id="faqAccordionDiv">
            @foreach($faqs as $key => $faq)
              @include(themeView()."faq.accordion", ["key" => $key, "faq" => $faq])
            @endforeach()
          </div>
        </div>
      </div>
    </div>
  </section>
@endif

@endsection

@push('scripts')
<script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
<script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
<!-- Lodash via jsDelivr CDN -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script src="{{ asset('assets/lib/material-timepicker/mdtimepicker.js') }}"></script>

<script type="text/javascript">
let myModal;

// Global variable to store selected variation ID
@if(isset($productVariations) && $productTypeValue != 3 && $productVariations->count() > 0)
  let selectedVariationId = null; // Don't auto-select, user must select a variation first
  let hasVariations = true; // Product has variations
  console.log('Product has variations, selectedVariationId initially:', selectedVariationId);
@else
  let selectedVariationId = null;
  let hasVariations = false; // Product has no variations
  console.log('Product has no variations');
@endif

var productTypeValue = @json($productTypeValue ?? 1);
var isGroupPuja = @json($isGroupPuja ?? false);
var currentProductSlug = @json($product->slug ?? '');
var baseUrlForCheckout = "{{ url('/') }}";
var selectedAshirwadAddressId = null;

function handleGotraCheckbox(checkbox) {
  if($(checkbox).is(':checked')) {
    $('#gotra').val('I do not know my gotra').prop('disabled', true);
    if(typeof updateGotraInFormData === 'function') updateGotraInFormData('I do not know my gotra');
  } else {
    $('#gotra').val('').prop('disabled', false);
    if(typeof formDataArray !== 'undefined') {
      for(var i = 0; i < formDataArray.length; i++) {
        if(formDataArray[i] && formDataArray[i].name === 'gotra') {
          formDataArray[i].value = '';
          break;
        }
      }
    }
  }
}

// Ensure formDataArray, addFormData and locationPlaces are always available (e.g. for Report birth place field)
var formDataArray = typeof formDataArray !== 'undefined' ? formDataArray : @json($formDataArray ?? []);
function addFormData(that, jsonObject, index){
  var value = $(that).val();
  if(value){ jsonObject.value = value; }
  if(typeof index !== 'undefined'){ formDataArray[index] = jsonObject; }
}
var typingTimerPlaces;
function locationPlaces(that, suggestionId, inputId, jsonObject, index){
  if(typeof addFormData === 'function') addFormData(inputId, jsonObject, index);
  clearTimeout(typingTimerPlaces);
  typingTimerPlaces = setTimeout(function(){
    var inputVal = $(that).val();
    if (inputVal.length > 2) {
      $(suggestionId).html('<li>Searching...</li>');
      $.ajax({
        url: '/search-location/' + encodeURIComponent(inputVal),
        method: 'GET',
        success: function(response) {
          $(suggestionId).empty();
          response.forEach(function(location) {
            var li = $('<li class="pointer"></li>');
            li.text(location.state);
            li.on('click', function(){
              $(inputId).val($(this).text());
              $(suggestionId).empty();
              if(typeof addFormData === 'function') addFormData(inputId, jsonObject, index);
            });
            $(suggestionId).append(li);
          });
        },
        error: function() {
          $(suggestionId).html('');
        }
      });
    } else {
      $(suggestionId).empty();
    }
  }, 1000);
}

// Initialize modal when document is ready
$(document).ready(function() {
  // Check if this is one-to-one puja
  @php
    $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
  @endphp
  let isOneToOnePuja = @json($isOneToOnePuja ?? false);

  // Initialize participate button state for Puja/Report products
  if($('#participateBtn').length && typeof hasVariations !== 'undefined') {
    // Report (type 3): always keep Buy Now enabled, never disable
    if(productTypeValue == 3) {
      $('#participateBtn').prop('disabled', false).css({
        'opacity': '1',
        'cursor': 'pointer'
      });
    } else if(isOneToOnePuja) {
      // For one-to-one puja, always enable the button (no variation check needed)
      $('#participateBtn').prop('disabled', false).css({
        'opacity': '1',
        'cursor': 'pointer'
      });
    } else {
      // For group puja, check variations
      if(!hasVariations) {
        $('#participateBtn').prop('disabled', true).css({
          'opacity': '0.5',
          'cursor': 'not-allowed'
        });
      } else {
        if(!selectedVariationId) {
          $('#participateBtn').prop('disabled', true).css({
            'opacity': '0.5',
            'cursor': 'not-allowed'
          });
        }
      }
    }
  }

  var modalElement = document.getElementById('shareDetailsModal');
  if(modalElement) {
    myModal = new bootstrap.Modal(modalElement, {
      backdrop: 'static',
      keyboard: false
    });

    // Initialize button states when modal is shown
    modalElement.addEventListener('shown.bs.modal', function() {
      // Set initial state: No button active, Yes button with border
      $('#ashirwad_no').addClass('active').css({
        'background-color': '#0e4677',
        'color': '#fff',
        'border-color': '#0e4677'
      });
      $('#ashirwad_yes').removeClass('active').css({
        'background-color': '#fff',
        'color': '#6c757d',
        'border': '2px solid #6c757d'
      });
      // Hide address section by default (since No is selected)
      $('#ashirwad_address_section').hide();
      selectedAshirwadAddressId = null;
      $('#ashirwad_new_address_form').hide();
    });
  }

  // Don't auto-select first variation - keep parent product data in header
  // Header will show parent product data by default
});

// Function to handle variation selection
function selectVariation(variationId, index) {
  console.log('selectVariation called with:', variationId, index);

  // Remove selected styling from all variation cards
  $('.variation-card').each(function() {
    $(this).removeClass('selected-variation');
    $(this).css('border', '2px solid #00000029');
  });

  // Add selected class to clicked variation
  var clickedCard = $('.variation-card[data-variation-id="' + variationId + '"]');
  clickedCard.addClass('selected-variation');
  clickedCard.css('border', '2px solid #FFA200');

  // Store selected variation ID
  selectedVariationId = variationId;
  console.log('Selected variation ID:', selectedVariationId);

  // Update modal header immediately with variation data from data attributes
  var variationName = clickedCard.data('variation-name');
  var variationPrice = clickedCard.data('variation-price');
  var variationImage = clickedCard.data('variation-image');
  var variationPersons = clickedCard.data('variation-persons');

  if(variationName) {
    $('#variation-header-name').text(variationName);
  }
  if(!isGroupPuja && variationPrice) {
    $('#variation-header-price').text('₹' + variationPrice);
  }
  if(variationImage) {
    $('#variation-header-image').attr('src', variationImage);
  }
  if(variationPersons) {
    $('#variation-header-persons').text(variationPersons);
  }

  // Enable participate button only if variations exist and one is selected (for group puja)
  // Report (type 3): always enabled. One-to-one puja: always enabled.
  if($('#participateBtn').length) {
    @php
      $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
    @endphp
    let isOneToOnePuja = @json($isOneToOnePuja ?? false);

    if(productTypeValue == 3) {
      // Report: always keep Buy Now enabled
      $('#participateBtn').prop('disabled', false).css('opacity', '1').css('cursor', 'pointer');
    } else if(isOneToOnePuja) {
      $('#participateBtn').prop('disabled', false).css('opacity', '1').css('cursor', 'pointer');
    } else {
      if(hasVariations && variationId) {
        $('#participateBtn').prop('disabled', false).css('opacity', '1').css('cursor', 'pointer');
      } else if(!hasVariations) {
        $('#participateBtn').prop('disabled', true).css('opacity', '0.5').css('cursor', 'not-allowed');
      }
    }
  }

  // Load variation form dynamically
  loadVariationForm(variationId);
}

// Function to load variation form via AJAX
function loadVariationForm(variationId) {
  console.log('Loading form for variation:', variationId);

  // Show loading indicator
  $('#variation-form-container').hide();
  $('#variation-form-loading').show();

  $.ajax({
    url: "{{ route('product.variation.form') }}",
    method: "POST",
    data: {
      variation_id: variationId
    },
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    success: function(response) {
      console.log('Form loaded successfully:', response);

      if(response.success && response.form_html) {
        // Update form item_id
        $('#form_item_id').val(response.product_id);

        // Update modal header with variation details
        if(response.variation_name) {
          $('#variation-header-name').text(response.variation_name);
        }
        if(!isGroupPuja && response.variation_price) {
          $('#variation-header-price').text('₹' + response.variation_price);
        }
        if(response.variation_image) {
          $('#variation-header-image').attr('src', response.variation_image);
        }
        if(response.variation_persons) {
          $('#variation-header-persons').text(response.variation_persons);
        }

        // Replace form container HTML
        $('#variation-form-container').html(response.form_html);

        // Update submit button text to "Book Puja" for puja products
        @if($productTypeValue == 2)
          $('#service-submit-btn').text('Book Puja');
        @endif

        // Initialize button states after form is loaded
        $('#ashirwad_no').addClass('active').css({
          'background-color': '#0e4677',
          'color': '#fff',
          'border-color': '#0e4677'
        });
        $('#ashirwad_yes').removeClass('active').css({
          'background-color': '#fff',
          'color': '#6c757d',
          'border': '2px solid #6c757d'
        });
        // Hide address section by default (since No is selected)
        $('#ashirwad_address_section').hide();
        if(typeof selectedAshirwadAddressId !== 'undefined') {
          selectedAshirwadAddressId = null;
        }
        $('#ashirwad_new_address_form').hide();

        // Hide loading and show form
        $('#variation-form-loading').hide();
        $('#variation-form-container').show();

        // Reinitialize form data array
        if(typeof formDataArray !== 'undefined') {
          formDataArray = [];
        }
      }
    },
    error: function(error) {
      console.error('Error loading form:', error);
      $('#variation-form-loading').hide();
      $('#variation-form-container').show();
      if(typeof toastr !== 'undefined') {
        toastr.error('Failed to load form. Please try again.');
      }
    }
  });
}

// Function to handle participate button click
function handleParticipateClick(e) {
  if(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  @php
    $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
  @endphp

  // For one-to-one puja, always show modal with static form
  @if(isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail)
    @php
      $todayDate = date("Y-m-d");
      $productPrice = $product->base_price;
      $productSpecialPrice = $product->sale_price;
      $productSpeicalPriceFromDate = $product->sale_price_start_date;
      $productSpeicalPriceToDate = $product->sale_price_end_date;
      $finalPrice = ($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate)) ? $productSpecialPrice : $productPrice;
    @endphp
    $('#form_item_id').val({{ $product->id }});

    // Show modal
    if(myModal) {
      myModal.show();
    } else {
      var modalElement = document.getElementById('shareDetailsModal');
      if(modalElement) {
        myModal = new bootstrap.Modal(modalElement, {
          backdrop: 'static',
          keyboard: false
        });
        myModal.show();
      }
    }

    // Initialize Ashirwad button states
    setTimeout(function() {
      $('#ashirwad_no').addClass('active').css({
        'background-color': '#0e4677',
        'color': '#fff',
        'border-color': '#0e4677'
      });
      $('#ashirwad_yes').removeClass('active').css({
        'background-color': '#fff',
        'color': '#6c757d',
        'border': '2px solid #6c757d'
      });
      $('#ashirwad_address_section').hide();
      if(typeof selectedAshirwadAddressId !== 'undefined') {
        selectedAshirwadAddressId = null;
      }
      $('#ashirwad_new_address_form').hide();
    }, 100);

    return;
  @endif

  console.log('handleParticipateClick called, selectedVariationId:', selectedVariationId, 'hasVariations:', hasVariations);

  @php
    $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
  @endphp
  let isOneToOnePuja = @json($isOneToOnePuja ?? false);

  // Check if product has variations and variation is not selected (only for group puja)
  // For one-to-one puja, skip this check as variations are not required
  if(!isOneToOnePuja && hasVariations && !selectedVariationId) {
    // Show toastr error message
    if(typeof toastr !== 'undefined') {
      toastr.error('Please select a puja type first');
    }
    return false;
  }

  // If no variation selected (and product has no variations), ensure header shows parent product data
  if(!selectedVariationId) {
    @php
      $todayDate = date("Y-m-d");
      $productPrice = $product->base_price;
      $productSpecialPrice = $product->sale_price;
      $productSpeicalPriceFromDate = $product->sale_price_start_date;
      $productSpeicalPriceToDate = $product->sale_price_end_date;
      $finalPrice = ($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate)) ? $productSpecialPrice : $productPrice;
    @endphp
    $('#variation-header-name').text('{{ $product->title }}');
    @if(!$isGroupPuja)
    $('#variation-header-price').text('₹{{ $finalPrice }}');
    @endif
    $('#variation-header-persons').text('1 person');
    $('#variation-header-image').attr('src', '{{ $product->image ? $product->image->image(80,80) : no_image() }}');
    $('#form_item_id').val({{ $product->id }});
    console.log('Form item_id set to parent product:', {{ $product->id }});
  } else {
    // If variation is selected, it's already handled in selectVariation function
    $('#form_item_id').val(selectedVariationId);
    console.log('Form item_id set to:', selectedVariationId);
  }

  // Show modal
  if(myModal) {
    myModal.show();
    // Initialize button states when modal is shown
    setTimeout(function() {
      $('#ashirwad_no').addClass('active').css({
        'background-color': '#0e4677',
        'color': '#fff',
        'border-color': '#0e4677'
      });
      $('#ashirwad_yes').removeClass('active').css({
        'background-color': '#fff',
        'color': '#6c757d',
        'border': '2px solid #6c757d'
      });
      $('#ashirwad_address_section').hide();
      if(typeof selectedAshirwadAddressId !== 'undefined') {
        selectedAshirwadAddressId = null;
      }
      $('#ashirwad_new_address_form').hide();
    }, 100);
  } else {
    var modalElement = document.getElementById('shareDetailsModal');
    if(modalElement) {
      myModal = new bootstrap.Modal(modalElement, {
        backdrop: 'static',
        keyboard: false
      });
      myModal.show();
      // Initialize button states when modal is shown
      setTimeout(function() {
        $('#ashirwad_no').addClass('active').css({
          'background-color': '#0e4677',
          'color': '#fff',
          'border-color': '#0e4677'
        });
        $('#ashirwad_yes').removeClass('active').css({
          'background-color': '#fff',
          'color': '#6c757d',
          'border': '2px solid #6c757d'
        });
        $('#ashirwad_address_section').hide();
        if(typeof selectedAshirwadAddressId !== 'undefined') {
          selectedAshirwadAddressId = null;
        }
        $('#ashirwad_new_address_form').hide();
      }, 100);
    }
  }
}
</script>

<script>
let parent_id = @json($parent_id);
let attrValues = @json($attrValues);
let allAttributeValues = @json($allAttributeValues);
let attrValuess = @json($attrValuess);
let attrValuesTwo = @json($attrValuesTwo);
@if($hasVariations)
  for(var key in attrValuesTwo){
    for(var key2 in allAttributeValues){
      if(key2!=key){
        let diff = _.difference(allAttributeValues[key2], attrValuess[key][attrValuesTwo[key]]);
        _.forEach(diff, (value, index) => {
          $('.notselected-'+value).removeClass('size-box').addClass('size-box2').prop('disabled',true);
        });
      }
    }
  }
@endif
function handleVariation(that){
  $('#loader-web').show();
  $(that).parent().find('button').removeClass('selected').addClass('bg-white');
  $(that).removeClass('bg-white').addClass('selected');
  $('#main_product_addtocart_btn').prop('disabled', false).removeClass('main_button_fade');
  let index = $(that).attr('data-index');
  let value = $(that).attr('data-value');
  let formData = new FormData();
  formData.append('parent_id', parent_id);
  $('#variation-div').find('button.selected').each(function(){
    formData.append('variation[]', $(this).attr('data-value'));
  });
  $.ajax({
      url: "{{ route('product.variation') }}",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response){
        //console.log(response.redirectTo);
        $('#loader-web').hide();
        window.history.replaceState({}, "",response.redirectTo);
        // console.log(response.redirectTo);
        // if(response.redirectTo){
        //   window.location.href = response.redirectTo;
        // }
        $('#main_product').val(response.product_id);
        $('#price-div').html(response.price);
        // $('#gallery-div').html(response.gallery);
        // $('#thumbnail li').on('click', handleGalleries);
        // $('#addtocart-st-div').html(response.addtocart);
        // $('.cartfom').off('submit');
        // addToCartt();

      },
      error: function(error){
        // $('#main_product').val(0);
        // $('#decrease-qty-btn').data('cart_item_id', 0);
        // $('#increase-qty-btn').data('cart_item_id', 0);
        // $('#main_product_addtocart_btn').prop('disabled', true).addClass('main_button_fade');
        $('#loader-web').show();
        toastr.error(error.responseJSON.message);
      }
  });
}
  const player = new Plyr('#player');
  $('.gallery-photo').on('click', function(){
    player.pause();
  })
</script>
<script type="text/javascript">

function addToCartt(){
    $('.cartfom').on('submit', function(event){
        event.preventDefault(); // Prevent default form submission
        const form = $(this);
        var formData = form.serialize();
        $.ajax({
            url: "{{ route('addtocart') }}", // Define your route for adding to cart
            method: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
            },
            success: function(response) {
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
              // $('#decrease-qty-btn').data('cart_item_id', response.cart_item.id);
              // $('#increase-qty-btn').data('cart_item_id', response.cart_item.id);
              showCart2();
            },
            error: function(xhr) {
                toastr.error('Error adding item to cart. Please try again.');
            }
        });
    });
}

function handleBuyNowProduct(that){
  const form = $(that).closest("form");
  form.find('input[name="buynow"]').remove();
  form.append('<input type="hidden" name="buynow" value="1">');
  form.submit();
}
addToCartt();
$(document).ready(function() {
  $('#increase-qty-btn').on('click', function(event) {
    let url = @json(url('/cart/increase-quantity'));
    url = url+'/'+$('#increase-qty-btn').data('cart_item_id');
      $.ajax({
          url: url,
          method: "POST",
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
          },
          success: function(response) {
            toastr.success(response.message);
            showCart2();
          },
          error: function(error) {
            let errors = error.responseJSON
            if(errors.message){
              toastr.error(errors.message);
            }
          }
      });
  });

  $('#decrease-qty-btn').on('click', function(event) {
    let url = @json(url('/cart/decrease-quantity'));
    url = url+'/'+$('#decrease-qty-btn').data('cart_item_id');
      $.ajax({
          url: url,
          method: "POST",
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
          },
          success: function(response) {
            toastr.success(response.message);
            if(response.qty){
              showCart2();
            }else{
              $('#totalcartproduct').text(0); // Update the total count based on response
              $('#totalcartproductmob').text(0); // Update the total count based on response
              $('#decrease-qty-btn').data('cart_item_id', 0);
              $('#increase-qty-btn').data('cart_item_id', 0);
            }
          },
          error: function(error) {
            let errors = error.responseJSON
            if(errors.message){
              toastr.error(errors.message);
            }
          }
      });
  });


});

$('.ratings').on('click', '.ratings_stars', function () {
  var star = $(this);
  $('.ratings_stars').removeClass('bi-star').addClass('bi-star-fill');
  star.addClass('bi-star-fill');
  star.prevAll().addClass('bi-star-fill');
  star.nextAll().removeClass('bi-star-fill').addClass('bi-star');
  $('#rating').val(star.data('rating'));
});

$('#productR').on('submit',function(e){
  e.preventDefault();
  $('.validataions').addClass('d-none')
  let frmData = $(this).serialize();
  $.ajax({
    url : "{{route('product.review', ['slug' => $product->slug])}}",
    type : 'POST',
    data : frmData,
    success: function(response){
      toastr.success('Review Submitted Successfully');
      $('#productR')[0].reset();
    },
    error:function (error){
      error = error.responseJSON.errors;
      if(error.rating){
        $('#rating-validation').text(error.rating[0]).removeClass('d-none');
      }
      if(error.comments){
        $('#comments-validation').text(error.comments[0]).removeClass('d-none');
      }
    }
  })
});

$('#normal_wish').on('click',function(){
  Swal.fire({
    icon: "success",
    title: "Successfully Added",
  });
})
$('#add_wishlist').on('click',function(){
  Swal.fire({
    icon: "success",
    title: "Successfully Added",
  });
})
$('#auth_wish').on('click',function(){
  Swal.fire({
    icon: "success",
    title: "Successfully Added",
  });
});

function handleGalleries(){
  var thisIndex = $(this).index();
  if(thisIndex < $('#thumbnail li.active').index()){
    prevImage(thisIndex, $(this).parents("#thumbnail").prev("#image-slider"));
  }else if(thisIndex > $('#thumbnail li.active').index()){
    nextImage(thisIndex, $(this).parents("#thumbnail").prev("#image-slider"));
  }
  $('#thumbnail li.active').removeClass('active');
  $(this).addClass('active');
}
$('#thumbnail li').on('click', handleGalleries);

var width = $('#image-slider').width();

function nextImage(newIndex, parent){
    parent.find('li').eq(newIndex).addClass('next-img').css('left', width).animate({left: 0},600);
    parent.find('li.active-img').removeClass('active-img').css('left', '0').animate({left: -width},600);
    parent.find('li.next-img').attr('class', 'active-img');
}
function prevImage(newIndex, parent){
    parent.find('li').eq(newIndex).addClass('next-img').css('left', -width).animate({left: 0},600);
    parent.find('li.active-img').removeClass('active-img').css('left', '0').animate({left: width},600);
    parent.find('li.next-img').attr('class', 'active-img');
}

/* Thumbails */
var ThumbailsWidth = ($('#image-slider').width() - 18.5)/7;
$('#thumbnail li').find('img').css('width', ThumbailsWidth);


</script>

<script>
  $(document).ready(function(){
    var categoryCount = $(".category-container p").length;
    if(categoryCount > 4){
      $(".read-more-btn").show();
    }

    $(".read-more-btn").click(function () {
      $(".category-container").toggleClass("show-more");
      $(this).text($(this).text() === "Read More" ? "Show Less" : "Read More");
    });

    $('.timepicker').mdtimepicker({
      timeFormat:'hh:mm:ss',
      format:'h:mm tt',
      theme:'pink',
      clearBtn:true
    }).on("timechanged", function(e){
      (this.oninput)()
    });
  });
  function handleTimePickerIcon(that){
    $(that).click();
  }
</script>

<script>
  // Puja countdown: multiple valid dates – countdown to next upcoming date; when it passes, switch to next
  @php
    $isOneToOnePuja = isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail;
  @endphp
  @if(!$isOneToOnePuja && isset($pujaUpcomingValidDatetimes) && count($pujaUpcomingValidDatetimes) > 0)
    let pujaUpcomingDates = @json($pujaUpcomingValidDatetimes);
    let currentIndex = 0;

    function getCountDownTime() {
      return new Date(pujaUpcomingDates[currentIndex].replace(" ", "T")).getTime();
    }

    let timer = setInterval(function () {
      let countDownDate = getCountDownTime();
      let now = new Date().getTime();
      let distance = countDownDate - now;

      if (distance <= 0) {
        currentIndex++;
        if (currentIndex < pujaUpcomingDates.length) {
          // Next valid date – keep timer running for next date
          countDownDate = getCountDownTime();
          distance = countDownDate - now;
        } else {
          clearInterval(timer);
          var countdownEl = document.getElementById("countdown");
          if (countdownEl) countdownEl.innerHTML = "<div class='time-over'>Expired</div>";
          return;
        }
      }

      if (distance > 0) {
        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        var daysEl = document.getElementById("days");
        var hoursEl = document.getElementById("hours");
        var minutesEl = document.getElementById("minutes");
        var secondsEl = document.getElementById("seconds");
        if (daysEl) daysEl.innerText = days;
        if (hoursEl) hoursEl.innerText = hours;
        if (minutesEl) minutesEl.innerText = minutes;
        if (secondsEl) secondsEl.innerText = seconds;
      }
    }, 1000);
  @endif

  formDataArray = @json($formDataArray);
  productTypeValue = @json($productTypeValue ?? 1);

  function addFormData (that, jsonObject, index){
    let value = $(that).val();
    if(value){
      jsonObject.value = value;
    }
    formDataArray[index] = jsonObject;
    console.log(formDataArray);
  }

  var typingTimer;
  function locationPlaces(that, suggestionId, inputId, jsonObject, index){
    addFormData(inputId, jsonObject, index);
    clearTimeout(typingTimer);
    typingTimer=setTimeout(function(){
      var inputVal = $(that).val();
      if (inputVal.length > 2) {
        $(suggestionId).html('<li>Searching...</li>');
        $.ajax({
          url: '/search-location/' + encodeURIComponent(inputVal),
          method: 'GET',
          success: function(response) {
            $(suggestionId).empty();
            response.forEach(function(location) {
              var li = $('<li class="pointer"></li>');
              li.text(location.state);
              li.on('click', function(){
                  $(inputId).val($(this).text());
                  $(suggestionId).empty();
                  addFormData(inputId, jsonObject, index);
              });
              $(suggestionId).append(li);
            });
          },
          error: function(xhr, status, error) {
            $(suggestionId).html('');
          }
        });
      } else {
        $(suggestionId).empty();
      }
    }, 1000);
  }

  function addToCartForm(that, event){
    if(event) { event.preventDefault(); }
    try {
    let _form = $(that);
    let item_id = _form.find("input[name=item_id]").val();
    if(!item_id) {
      if(typeof toastr !== 'undefined') toastr.error('Invalid form. Please refresh and try again.');
      return false;
    }
    if(!Array.isArray(formDataArray)) formDataArray = [];

    // Handle name field (for one-to-one puja static form)
    let nameValue = $('#name').val();
    if(nameValue) {
      let nameExists = false;
      for(let i = 0; i < formDataArray.length; i++) {
        if(formDataArray[i] && (formDataArray[i].name === 'name' || formDataArray[i].name === 'full_name' || formDataArray[i].name === 'first_name')) {
          formDataArray[i].value = nameValue;
          nameExists = true;
          break;
        }
      }
      if(!nameExists) {
        formDataArray.push({
          type: "text",
          label: "Name",
          name: "name",
          value: nameValue
        });
      }
    }

    var gotraValue = '';
    var ashirwadAddressId = null;
    if(typeof productTypeValue !== 'undefined' && productTypeValue == 2) {
    // Handle gotra field (Puja only)
    if($('#gotra_unknown').length && $('#gotra_unknown').is(':checked')) {
      gotraValue = 'I do not know my gotra';
    } else if($('#gotra').length) {
      gotraValue = $('#gotra').val();
    }

    // Normalise formDataArray
    if(!Array.isArray(formDataArray)) {
      formDataArray = [];
    }

    // Build gotra field object
    let gotraField = {
      type: "text",
      label: "Gotra",
      name: "gotra",
      value: gotraValue || "I do not know my gotra"
    };

    // Replace existing gotra entry if present, otherwise append once
    let replacedGotra = false;
    for(let i = 0; i < formDataArray.length; i++) {
      if(formDataArray[i] && formDataArray[i].name === 'gotra') {
        formDataArray[i] = gotraField;
        replacedGotra = true;
        break;
      }
    }
    if(!replacedGotra) {
      formDataArray.push(gotraField);
    }

    // Handle Ashirwad box selection (Puja only)
    let ashirwadBoxValue = 0;
    let ashirwadOption = $('#ashirwad_yes').length && ($('#ashirwad_yes').hasClass('active') || $('#ashirwad_yes').css('background-color') === 'rgb(14, 70, 119)') ? 'yes' : 'no';
    if(ashirwadOption === 'yes') {
      ashirwadBoxValue = 1; // Yes = 1
    }

    // Add ashirwad_box to formDataArray
    let ashirwadBoxExists = false;
    for(let i = 0; i < formDataArray.length; i++) {
      if(formDataArray[i] && formDataArray[i].name === 'ashirwad_box') {
        formDataArray[i].value = ashirwadBoxValue;
        ashirwadBoxExists = true;
        break;
      }
    }
    if(!ashirwadBoxExists) {
      formDataArray.push({
        type: "hidden",
        label: "Ashirwad Box",
        name: "ashirwad_box",
        value: ashirwadBoxValue
      });
    }

    // Handle Ashirwad address - get selected address ID
    if(ashirwadOption === 'yes') {
      // First check if user selected an existing address (from radio button or variable)
      let checkedRadio = $('input[name="ashirwad_selected_address"]:checked');
      if(checkedRadio.length > 0) {
        ashirwadAddressId = checkedRadio.val();
        selectedAshirwadAddressId = ashirwadAddressId; // Update variable too
      } else if(selectedAshirwadAddressId) {
        ashirwadAddressId = selectedAshirwadAddressId;
      }
      // If no existing address selected and user has filled new address form, save it first
      if(!ashirwadAddressId && $('#ashirwad_new_address_form').length && $('#ashirwad_new_address_form').is(':visible')) {
        let address = $('#ashirwad_address').val();
        let stateId = $('#ashirwad_state_id').val();
        let cityId = $('#ashirwad_city_id').val();
        let pincode = $('#ashirwad_pincode').val();

        // Only save if all fields are filled
        if(address && stateId && cityId && pincode) {
          // Save new address synchronously to get the address ID before redirect
          $.ajax({
            url: "{{ route('ashirwad.address.save') }}",
            method: "POST",
            async: false, // Make it synchronous to get address_id before redirect
            data: {
              address: address,
              state_id: stateId,
              city_id: cityId,
              pincode: pincode
            },
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
              if(response.success && response.address_id) {
                ashirwadAddressId = response.address_id;
                selectedAshirwadAddressId = response.address_id; // Also update the global variable
              }
            },
            error: function(error) {
              console.error('Error saving address:', error);
            }
          });
        }
      }
    }
    }

    submitFormToCart(_form, item_id, formDataArray, ashirwadAddressId, gotraValue);
    } catch(e) {
      console.error('addToCartForm error:', e);
      if(typeof toastr !== 'undefined') {
        toastr.error('Something went wrong. Please try again.');
      }
      $(that).find("button[type=submit]").prop("disabled", false);
    }
    return false;
  }

  $(document).on('submit', '.js-add-to-cart-form', function(e) {
    e.preventDefault();
    e.stopPropagation();
    addToCartForm(this, e);
    return false;
  });

  function submitFormToCart(_form, item_id, formDataArray, addressId = null, gotraValue = null) {
    // Disable button but keep it visible
    _form.find("button[type=submit]").prop("disabled", true).css({
      'display': 'inline-block',
      'visibility': 'visible',
      'opacity': '0.7'
    });

    let requestData = {
      item_id: item_id,
      product_type_value: productTypeValue,
      type: "shop",
      form_data: formDataArray
    };

    // Also send gotra separately so backend can always append it if missing in form_data
    if(typeof gotraValue !== 'undefined' && gotraValue !== null && gotraValue !== '') {
      requestData.gotra = gotraValue;
    }

    // Don't pass address_id - let user select address on checkout page
    // This matches the product buy flow where all addresses are shown on checkout

    $.ajax({
        url: "{{ route('cart.addform') }}",
        method: "POST",
        data: requestData,
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        success: function(response){
          if(typeof toastr !== 'undefined') {
            toastr.success('Added to cart. Redirecting to checkout...');
          }
          var redirectUrl = '';
          if(response) {
            var res = typeof response === 'string' ? (function(){ try { return JSON.parse(response); } catch(e){ return {}; } })() : response;
            redirectUrl = res.redirectTo || res.redirect_to || '';
            if(redirectUrl && addressId) {
              redirectUrl = redirectUrl.replace(/[?&]address_id=\d+/g, '');
              redirectUrl += (redirectUrl.indexOf('?') > -1 ? '&' : '?') + 'address_id=' + addressId;
            }
          }
          if(!redirectUrl && typeof currentProductSlug !== 'undefined' && currentProductSlug) {
            var productTypeName = (typeof productTypeValue !== 'undefined' && productTypeValue == 2) ? 'pooja' : 'service';
            var base = typeof baseUrlForCheckout !== 'undefined' ? baseUrlForCheckout : "{{ url('/') }}";
            redirectUrl = base + "/" + productTypeName + "/checkout?item_slug=" + encodeURIComponent(currentProductSlug);
          }
          if(redirectUrl) {
            window.location.href = redirectUrl;
          }
        },
        error: function(xhr, status, err){
          var res = xhr && xhr.responseJSON;
          if(res && (res.redirectTo || res.redirect_to)){
            window.location.href = (res.redirectTo || res.redirect_to);
            return;
          }
          _form.find("button[type=submit]").prop("disabled", false).css({
            'display': 'inline-block',
            'visibility': 'visible',
            'opacity': '1'
          });
          var msg = 'Failed to add to cart. Please try again.';
          if(res) {
            if(res.message) msg = res.message;
            else if(res.errors && typeof res.errors === 'object') {
              var firstErr = Object.keys(res.errors).map(function(k){ return res.errors[k][0]; })[0];
              if(firstErr) msg = firstErr;
            }
          }
          if(typeof toastr !== 'undefined') toastr.error(msg);
        }
    });
  }

  // Function to handle gotra input
  function handleGotraInput(input) {
    let gotraValue = $(input).val();
    updateGotraInFormData(gotraValue);
  }

  // Function to update gotra in formDataArray
  function updateGotraInFormData(gotraValue) {
    let gotraExists = false;
    for(let i = 0; i < formDataArray.length; i++) {
      if(formDataArray[i] && formDataArray[i].name === 'gotra') {
        formDataArray[i].value = gotraValue;
        gotraExists = true;
        break;
      }
    }
    if(!gotraExists && gotraValue) {
      formDataArray.push({
        type: "text",
        label: "Gotra",
        name: "gotra",
        value: gotraValue
      });
    }
  }

  // Function to handle gotra checkbox
  function handleGotraCheckbox(checkbox) {
    if($(checkbox).is(':checked')) {
      $('#gotra').val('I do not know my gotra').prop('disabled', true);
      // Add to formDataArray
      updateGotraInFormData('I do not know my gotra');
    } else {
      $('#gotra').val('').prop('disabled', false);
      // Clear value from formDataArray
      for(let i = 0; i < formDataArray.length; i++) {
        if(formDataArray[i] && formDataArray[i].name === 'gotra') {
          formDataArray[i].value = '';
          break;
        }
      }
    }
  }

  // selectedAshirwadAddressId is defined in first script block (global)

  // Function to handle Ashirwad box option (Yes/No)
  function handleAshirwadOption(option) {
    if(option === 'yes') {
      $('#ashirwad_yes').addClass('active').css({
        'background-color': '#0e4677',
        'color': '#fff',
        'border-color': '#0e4677'
      });
      $('#ashirwad_no').removeClass('active').css({
        'background-color': '#fff',
        'color': '#6c757d',
        'border': '2px solid #6c757d'
      });
      $('#ashirwad_address_section').slideDown();
      // Load addresses
      fetchUserShippingAddresses();
      // Load states on first time
      if($('#ashirwad_state_id option').length <= 1) {
        loadStatesForAshirwad();
      }
    } else {
      $('#ashirwad_yes').removeClass('active').css({
        'background-color': '#fff',
        'color': '#6c757d',
        'border': '2px solid #6c757d'
      });
      $('#ashirwad_no').addClass('active').css({
        'background-color': '#0e4677',
        'color': '#fff',
        'border-color': '#0e4677'
      });
      $('#ashirwad_address_section').slideUp();
      selectedAshirwadAddressId = null;
      $('#ashirwad_new_address_form').hide();
    }
  }

  // Function to fetch user's shipping addresses
  function fetchUserShippingAddresses(selectAddressId = null) {
    $.ajax({
      url: "{{ route('user.shipping.address') }}",
      method: "GET",
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success && response.addresses) {
          renderAddresses(response.addresses, selectAddressId);
        } else {
          $('#ashirwad_addresses_list').html('<p class="text-muted">No addresses found. Please add a new address.</p>');
        }
      },
      error: function(error) {
        console.error('Error fetching addresses:', error);
        $('#ashirwad_addresses_list').html('<p class="text-danger">Error loading addresses. Please try again.</p>');
      }
    });
  }

  // Function to render addresses list
  function renderAddresses(addresses, selectAddressId = null) {
    if(!addresses || addresses.length === 0) {
      $('#ashirwad_addresses_list').html('<p class="text-muted">No addresses found. Please add a new address.</p>');
      return;
    }

    // If no specific address ID to select, use the first one or the currently selected one
    if(!selectAddressId && !selectedAshirwadAddressId && addresses.length > 0) {
      selectAddressId = addresses[0].id;
    } else if(!selectAddressId && selectedAshirwadAddressId) {
      selectAddressId = selectedAshirwadAddressId;
    }

    let html = '';
    addresses.forEach(function(address, index) {
      let addressText = address.address || '';
      if(address.city) addressText += ', ' + address.city;
      if(address.state) addressText += ', ' + address.state;
      if(address.zipcode) addressText += ' - ' + address.zipcode;

      // Check if this address should be selected
      let isChecked = (selectAddressId && address.id == selectAddressId) ? 'checked' : '';
      if(isChecked) {
        selectedAshirwadAddressId = address.id;
      } else if(index === 0 && !selectAddressId) {
        // Fallback: select first address if no specific ID provided
        isChecked = 'checked';
        selectedAshirwadAddressId = address.id;
      }

      html += '<div class="mb-2 p-2 border rounded" style="background-color: #fff; border-radius: 6px;">';
      html += '<div class="d-flex align-items-start">';
      html += '<div class="me-3 mt-1">';
      html += '<input type="radio" name="ashirwad_selected_address" class="form-check-input" value="' + address.id + '" ' + isChecked + ' onchange="selectAshirwadAddress(' + address.id + ')">';
      html += '</div>';
      html += '<div class="flex-grow-1">';
      html += '<h6 class="mb-1 fw-semibold">' + (address.name || 'Address') + '</h6>';
      html += '<p class="mb-0 text-muted" style="font-size: 14px;">' + addressText + '</p>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
    });

    $('#ashirwad_addresses_list').html(html);
  }

  // Function to select address
  function selectAshirwadAddress(addressId) {
    selectedAshirwadAddressId = addressId;
    // Hide new address form when an existing address is selected
    $('#ashirwad_new_address_form').slideUp();
  }

  // Function to toggle new address form
  function toggleNewAddressForm() {
    $('#ashirwad_new_address_form').slideToggle();
    if($('#ashirwad_new_address_form').is(':visible')) {
      // Uncheck all radio buttons when showing new form
      $('input[name="ashirwad_selected_address"]').prop('checked', false);
      selectedAshirwadAddressId = null;
      // Load states if not loaded
      if($('#ashirwad_state_id option').length <= 1) {
        loadStatesForAshirwad();
      }
    }
  }

  // Function to save new address
  function saveNewAshirwadAddress() {
    let address = $('#ashirwad_address').val();
    let stateId = $('#ashirwad_state_id').val();
    let cityId = $('#ashirwad_city_id').val();
    let pincode = $('#ashirwad_pincode').val();

    if(!address || !stateId || !cityId || !pincode) {
      if(typeof toastr !== 'undefined') {
        toastr.error('Please fill all address fields.');
      }
      return;
    }

    $.ajax({
      url: "{{ route('ashirwad.address.save') }}",
      method: "POST",
      data: {
        address: address,
        state_id: stateId,
        city_id: cityId,
        pincode: pincode
      },
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success) {
          if(typeof toastr !== 'undefined') {
            toastr.success('Address saved successfully');
          }
          // Set the selected address ID immediately
          selectedAshirwadAddressId = response.address_id;
          // Reload addresses list and select the newly saved address
          fetchUserShippingAddresses(response.address_id);
          // Hide form and clear fields
          $('#ashirwad_new_address_form').slideUp();
          clearAddressForm();
        } else {
          if(typeof toastr !== 'undefined') {
            toastr.error('Failed to save address. Please try again.');
          }
        }
      },
      error: function(error) {
        console.error('Error saving address:', error);
        if(typeof toastr !== 'undefined') {
          toastr.error('Failed to save address. Please try again.');
        }
      }
    });
  }

  // Function to load states for Ashirwad address form
  function loadStatesForAshirwad() {
    $.ajax({
      url: "{{ route('locations', ['city_state' => 'master_states']) }}",
      method: "GET",
      data: {
        country_id: 101 // Default to India
      },
      success: function(response) {
        let options = '<option value="">Select State</option>';
        for(let id in response) {
          options += '<option value="' + id + '">' + response[id] + '</option>';
        }
        $('#ashirwad_state_id').html(options);
      },
      error: function(error) {
        console.error('Error loading states:', error);
      }
    });
  }

  // Function to fetch cities for Ashirwad address form
  function fetchCitiesForAshirwad(selectElement) {
    let stateId = $(selectElement).val();
    if(!stateId) {
      $('#ashirwad_city_id').html('<option value="">Select City</option>');
      return;
    }

    $.ajax({
      url: "{{ route('locations', ['city_state' => 'master_cities']) }}",
      method: "GET",
      data: {
        state_id: stateId
      },
      success: function(response) {
        let options = '<option value="">Select City</option>';
        for(let id in response) {
          options += '<option value="' + id + '">' + response[id] + '</option>';
        }
        $('#ashirwad_city_id').html(options);
      },
      error: function(error) {
        console.error('Error loading cities:', error);
      }
    });
  }

  // Function to clear address form
  function clearAddressForm() {
    $('#ashirwad_address').val('');
    $('#ashirwad_state_id').val('').trigger('change');
    $('#ashirwad_city_id').html('<option value="">Select City</option>');
    $('#ashirwad_pincode').val('');
  }
</script>
<script>
document.querySelectorAll('.product-section').forEach(section => {
const mainImage = section.querySelector('.main_images');
const thumbnails = section.querySelectorAll('.thumb-grid img');
const mainWrapper = section.querySelector('.ine-ext');

thumbnails.forEach(img => {
  img.addEventListener('click', () => {
    thumbnails.forEach(t => t.classList.remove('active'));
    img.classList.add('active');
    mainImage.src = img.dataset.full;
  });
});
if (thumbnails.length > 0) {
    thumbnails[0].classList.add('active');
    if (thumbnails[0].dataset.full) {
      mainImage.src = thumbnails[0].dataset.full;
    }
  }
});
var offerDate = new Date("{{ $offerEnd }}").getTime();

    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = offerDate - now;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        if(document.getElementById("offer-timer").length){
        document.getElementById("offer-timer").innerHTML =
            "Offer ends in " + days + "d " + hours + "h " + minutes + "m " + seconds + "s";
          }
    }, 1000);
</script>
<script src="https://cdn.jsdelivr.net/npm/lightslider@1.1.6/dist/js/lightslider.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/plugins/zoom/lg-zoom.min.js"></script>

<script>
function handleShareAndEarn(itemId, type) {
  // Get referral code from user
  let referralCode = "{{ $user->referral_code ?? '' }}";
  if (!referralCode) {
    toastr.error('You need a referral code to share and earn.');
    return;
  }

  // Get product slug and type from PHP variables
  let productSlug = "{{ $product->slug ?? '' }}";
  let productType = {{ $productTypeValue ?? 1 }};

  // Generate share URL based on product type (similar to remedies checkout format)
  let shareUrl = '';
  if(productType == 1) {
    // Product
    shareUrl = "{{ route('product.detail', ['product_slug' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', productSlug);
  } else if(productType == 2) {
    // Puja - check if one-to-one puja
    @if(isset($isOneToOnePujaDetail) && $isOneToOnePujaDetail)
      shareUrl = "{{ route('product.detailonetoonepuja', ['product_slug' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', productSlug);
    @else
      shareUrl = "{{ route('product.detailpuja', ['product_slug' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', productSlug);
    @endif
  } else if(productType == 3) {
    // Service
    shareUrl = "{{ route('product.detailservice', ['product_slug' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', productSlug);
  }

  // Add referral code as query parameter (similar to remedies checkout)
  if(shareUrl && referralCode) {
    shareUrl += (shareUrl.includes('?') ? '&' : '?') + 'ref=' + encodeURIComponent(referralCode);
  }

  // Copy to clipboard
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(shareUrl).then(function() {
      toastr.success('Share link copied to clipboard: ' + shareUrl);
    }, function() {
      // Fallback for older browsers
      const textArea = document.createElement('textarea');
      textArea.value = shareUrl;
      document.body.appendChild(textArea);
      textArea.select();
      document.execCommand('copy');
      document.body.removeChild(textArea);
      toastr.success('Share link copied to clipboard: ' + shareUrl);
    });
  } else {
    // Fallback for older browsers
    const textArea = document.createElement('textarea');
    textArea.value = shareUrl;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    toastr.success('Share link copied to clipboard: ' + shareUrl);
  }
}

$(document).ready(function() {

  // 1️⃣ LightSlider
  var slider = $('#imageGallery').lightSlider({
    gallery: true,
    item: 1,
    loop: true,
    thumbItem: 5,
    slideMargin: 0,
    enableDrag: true,
    currentPagerPosition: 'middle',
    controls: true
  });

  // 2️⃣ LightGallery (zoom popup on click)
  lightGallery(document.getElementById('imageGallery'), {
    selector: 'li',
    zoom: true
  });

});
</script>
<script type="text/javascript">
document.addEventListener("keydown", function (e) {
    if(e.key === "Enter"){
        var form = document.activeElement && document.activeElement.closest ? document.activeElement.closest('form.js-add-to-cart-form') : (document.getElementById("productField") || document.getElementById("productFieldReport"));
        if(form){
            e.preventDefault();
            if(form.checkValidity()){
                if(typeof addToCartForm === 'function') addToCartForm(form, e);
            } else {
                form.reportValidity();
            }
        }
    }
});
</script>

<script>
    $(document).ready(function () {

        $('#reviewSlider').lightSlider({
            item: 8,   // Desktop
            loop: true,
            slideMove: 1,
            slideMargin: 10,
            controls: true,
            pager: false,

            responsive: [
                {
                    breakpoint: 992, // Tablet
                    settings: {
                        item: 6,
                    }
                },
                {
                    breakpoint: 576, // Mobile
                    settings: {
                        item: 4,
                    }
                }
            ]
        });

        // Lightbox zoom
        lightGallery(document.getElementById('reviewSlider'), {
            selector: 'li',
            download: false
        });

    });
</script>
@endpush
