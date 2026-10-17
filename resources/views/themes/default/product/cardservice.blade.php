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
@endphp



<div class="col-lg-3 col-6 mb-4 {{\Request::routeIs('product.handle') ? '' : ''}}">
    <div class="d3-card h-100 d-flex flex-column">

        <a href="{{ route('product.detailservice', [$productSlug]) }}" class="d-block">
            <div class="d3-img-wrapper">
                <img src="{{$productImage ? $productImage->image(600, 600) : '/assets/img/placeholder.jpg'}}"
                     alt="{{ $product->alt_text ?: $productTitle }}">
            </div>
        </a>

        <div class="d3-body d-flex flex-column flex-grow-1">
            <a href="{{ route('product.detailservice', [$productSlug]) }}" class="text-decoration-none">
                <h4 class="d3-title">{{ $productTitle }}</h4>
            </a>

            <div class="mt-auto">
                <div class="d3-price-wrap">
                    @if($productSpecialPriceFromDate && ($productSpecialPriceFromDate <= $todayDate) && ($productSpecialPriceToDate >= $todayDate) )
                        <strike>₹{{ $productPrice }}</strike>
                        <span style="color: #ef4444;">₹{{ $productSpecialPrice }}</span>
                    @else
                        <span style="color: #ef4444;">₹{{ $productPrice }}</span>
                    @endif
                </div>

                @if($productFormFields->count() > 0)
                    <a href="{{ route('product.detailservice', [$productSlug]) }}" class="d3-btn">
                        <i class="fa fa-info-circle me-1"></i> Detail
                    </a>
                @else
                    @if($user)
                        <a href="{{ route('checkout', ['shop', 'buynow', $productTypeName, $productSlug]) }}" class="d3-btn">
                            <i class="fa fa-shopping-cart me-1"></i> Buy Now
                        </a>
                    @else
                        <button onclick="handleUserLoginModal('open')" type="button" class="d3-btn w-100">
                            <i class="fa fa-shopping-cart me-1"></i> Buy Now
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>