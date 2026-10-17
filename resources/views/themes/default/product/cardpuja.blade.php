@php
  $productId = $product->id;
  $productSlug = $product->slug;
  $productTitle = $product->title;
  $productImage = $product->image;
  $productQuantity = $product->avaibility;
  $productFormFields = $product->fields;
  $todayDate = date("Y-m-d");
  $productSpecialPriceFromDate = $product->sale_price_start_date;
  $productSpecialPriceToDate = $product->sale_price_end_date;
  $productPrice = $product->base_price;
  $productSpecialPrice = $product->sale_price;
  $productWishlist = $product->wishlist;
  $isOneToOnePuja = isset($is_one_to_one_puja) && $is_one_to_one_puja == 1;
  $detailRoute = $isOneToOnePuja ? route('one.to.one.puja.slug', ['slug' => $productSlug]) : route('product.detailpuja', [$productSlug]);
@endphp

<div class="col-lg-3 col-6 mb-4 {{\Request::routeIs('product.handle') ? '' : ''}}">
    <div class="d3-card h-100 d-flex flex-column">

        <a href="{{ $detailRoute }}" class="d-block">
            <div class="d3-img-wrapper">
                <img src="{{$productImage ? $productImage->image(600, 600) : '/assets/img/placeholder.jpg'}}"
                     alt="{{ $product->alt_text ?: $productTitle }}">

                @if(!$isOneToOnePuja)
                    <div style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.7); color: #fcd34d; font-size: 11px; padding: 4px 10px; border-radius: 12px; font-weight: bold; white-space: nowrap; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                        <i class="fa fa-calendar-alt me-1"></i> {{ date("D, d M Y", strtotime($product->expire_at)) }}
                    </div>
                @endif
            </div>
        </a>

        <div class="d3-body d-flex flex-column flex-grow-1">
            <a href="{{ $detailRoute }}" class="text-decoration-none">
                <h4 class="d3-title">{{ $productTitle }}</h4>
            </a>

            <div class="mt-auto">
                @if($isOneToOnePuja)
                    <div class="d3-price-wrap">
                        @if($productSpecialPriceFromDate && ($productSpecialPriceFromDate <= $todayDate) && ($productSpecialPriceToDate >= $todayDate) )
                            <strike>₹{{ $productPrice }}</strike>
                            <span style="color: #ef4444;">₹{{ $productSpecialPrice }}</span>
                        @else
                            <span style="color: #ef4444;">₹{{ $productPrice }}</span>
                        @endif
                    </div>
                @else
                    <!-- Spacer if no price to keep cards aligned -->
                    <div class="d3-price-wrap" style="visibility: hidden;">
                        <span style="color: #ef4444;">Spacer</span>
                    </div>
                @endif

                @if($isOneToOnePuja || $productFormFields->count() > 0)
                    <a href="{{ $detailRoute }}" class="d3-btn">
                        <i class="fa fa-info-circle me-1"></i> Detail
                    </a>
                @else
                    @if($user)
                        <a href="{{ route('checkout', ['shop', 'buynow', $productTypeName, $productSlug]) }}" class="d3-btn">
                            <i class="fa fa-check-circle me-1"></i> Book Now
                        </a>
                    @else
                        <button onclick="handleUserLoginModal('open')" type="button" class="d3-btn w-100">
                            <i class="fa fa-check-circle me-1"></i> Book Now
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>