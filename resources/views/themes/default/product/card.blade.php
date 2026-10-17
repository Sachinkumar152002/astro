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

<style>
/* Premium Soft Design 2 */
.prod-d2-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.04); transition: transform 0.3s, box-shadow 0.3s; border: 1px solid #f9fafb; height: 100%; display: flex; flex-direction: column; }
.prod-d2-card:hover { transform: translateY(-4px); box-shadow: 0 12px 20px rgba(0,0,0,0.08); }
.prod-d2-img-wrap { width: 100%; aspect-ratio: 1; padding: 12px; position: relative; }
.prod-d2-img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
.prod-d2-body { padding: 0 16px 16px; text-align: center; display: flex; flex-direction: column; flex-grow: 1; }
.prod-d2-title { font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 6px; text-decoration: none; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.prod-d2-title:hover { color: #d97706; }
.prod-d2-price { color: #f59e0b; font-weight: 800; font-size: 17px; margin-bottom: 16px; }
.prod-d2-btn-group { display: flex; gap: 8px; margin-top: auto; }
.prod-d2-btn { flex: 1; padding: 10px 0; text-align: center; border-radius: 50px; font-size: 12px; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: opacity 0.2s; text-decoration: none; display: inline-block; }
.prod-d2-btn:hover { opacity: 0.9; }
.prod-d2-btn-buy { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff !important; border: none; }
.prod-d2-btn-cart { background: #f3f4f6; color: #4b5563 !important; border: none; }
.prod-d2-btn-out { background: #fee2e2; color: #ef4444 !important; border: none; cursor: not-allowed; width: 100%; }
.prod-d2-wishlist { position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.95); border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1); cursor: pointer; transition: all 0.2s; z-index: 10; }
.prod-d2-wishlist:hover { background: #fff; transform: scale(1.1); }
</style>

<div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
  <div class="prod-d2-card">
    <div class="prod-d2-img-wrap">
      <a href="{{ route('product.detail', [$productSlug]) }}">
        <!-- Temporarily using the premium placeholder if image is ugly/empty, but preserving original logic just in case -->
        <img class="prod-d2-img" src="{{$productImage ? $productImage->image(600, 600) : asset('assets/images/shop.png')}}" alt="{{ $product->alt_text ?: $productTitle }}">
      </a>
      
      <!-- Wishlist Action -->
      @if($user)
        @if($productWishlist)
          <button data-item_id="{{ $productId }}" data-type="shop" data-wishlist_id="{{ $productWishlist->id }}" data-action-type="remove" class="toggle_wishlist prod-d2-wishlist">
            <i class="fa fa-heart text-danger"></i>
          </button>
        @else
          <button data-item_id="{{ $productId }}" data-type="shop" data-wishlist_id="0" data-action-type="add" class="toggle_wishlist prod-d2-wishlist">
            <i class="fa fa-heart text-muted"></i>
          </button>
        @endif
      @else
        <button data-item_id="{{ $productId }}" data-type="shop" data-wishlist_id="0" data-action-type="login" class="auth_login prod-d2-wishlist" onclick="handleUserLoginModal('open')">
          <i class="fa fa-heart text-muted"></i>
        </button>
      @endif
    </div>

    <div class="prod-d2-body">
      <a href="{{ route('product.detail', [$productSlug]) }}" class="prod-d2-title" title="{{ $productTitle }}">
        {{ $productTitle }}
      </a>
      
      <div class="prod-d2-price">
        @if($productSpecialPriceFromDate && ($productSpecialPriceFromDate <= $todayDate) && ($productSpecialPriceToDate >= $todayDate) )
          <span>&#8377;{{ $productSpecialPrice }}</span>
          <strike class="text-muted" style="font-size: 12px; font-weight: normal; margin-left: 4px;">&#8377;{{ $productPrice }}</strike>
        @else
          <span>&#8377;{{ $productPrice }}</span>
        @endif
      </div>

      <form class="cartfom mt-auto" method="post" style="width: 100%;">
        @csrf
        <input type="hidden" name="item_id" value="{{ $productId }}">
        @if($productQuantity > 0)
        <div class="prod-d2-btn-group">
          @if($user)
            <button type="button" class="prod-d2-btn prod-d2-btn-buy" onclick="handleBuyNowProduct(this)">Buy Now</button>
          @else
            <button type="button" class="prod-d2-btn prod-d2-btn-buy" onclick="handleUserLoginModal('open')">Buy Now</button>
          @endif
          <button type="submit" class="prod-d2-btn prod-d2-btn-cart">Cart</button>
        </div>
        @else
          <button type="button" disabled class="prod-d2-btn prod-d2-btn-out">Out of Stock</button>
        @endif
      </form>
    </div>
  </div>
</div>