<!-- check avaibility start -->
@if($product->avaibility && ($product->avaibility > 0))
<!-- check product type and fields count start -->
  @if($product->type==1)
    <div class="number-1 my-2 w-100">
      <span class="minus" id="decrease-qty-btn" data-cart_item_id="{{ $product->cartItem ? $product->cartItem->id : 0 }}">-</span>
      <input type="text" id="cart_item_quantity" value="{{ $product->cartItem ? $product->cartItem->quantity : 1 }}"/>
      <span class="plus" id="increase-qty-btn" data-cart_item_id="{{ $product->cartItem ? $product->cartItem->id : 0 }}">+</span>
    </div>
    <div class="mb-3">
      <form class="cartfom" action="" method="post">
        @csrf
        <input id="main_product" type="hidden" name="item_id" value="{{ $product->id }}">
        <input id="main_product_addtocart_btn" class="main_button mb-4" type="submit" value="Add to cart">
      </form>
    </div>
  @elseif($product->type==3 && $product->fields->count()==0)
    @if($user)
      <a href="{{ route('checkout', ['buynow', 'product', $product->slug]) }}">
        <input class="main_button mb-4" type="button" value="Buy Now">
      </a>
    @else
      <input onclick="checkAuth('{{ route('checkout', ['buynow', 'product', $product->slug]) }}')" class="main_button" type="button" value="Buy Now">
    @endif
  @else
    <button type="button" class="main_button mb-4" data-bs-toggle="modal" data-bs-target="#shareDetailsModal" data-item-id="{{ $product->id }}">
      Buy Now
    </button>
  @endif
  <!-- check avaibility else -->
@else
  <!-- Out of Stock -->
  <div class="col-lg-4">
    <button type="button" class="btn btn-secondary my-3" name="button" disabled>Out Of Stock</button>
  </div>
@endif
<!-- check avaibility end -->

<!-- Wishlist Logic start -->
@if(auth()->guard('web')->check() || auth()->guard('astrologer')->check())
  @if($product->wishlist)
    <button data-item_id="{{$product->id}}" data-type="shop" data-wishlist_id="{{$product->wishlist->id}}" data-action-type="remove"  class="mb-3 toggle_wishlist wish wishlist-btnn mt-2 mb-2">
      <i class="fa fa-heart text-danger pointer"></i>
    </button>
  @else
    <button data-item_id="{{$product->id}}"  data-type="shop" data-wishlist_id="0" data-action-type="add"  class=" mb-3 toggle_wishlist wish wishlist-btnn mt-2 mb-2">
      <i class="fa fa-heart pointer"></i>
    </button>
  @endif
@else
  <button onclick="checkAuth('{{Request()->url()}}')" class="mb-3 wish wishlist-btnn mt-2 mb-2 mb-2">
    <i class="fa fa-heart pointer"></i>
  </button>
@endif
<!-- Wishlist Logic end -->
