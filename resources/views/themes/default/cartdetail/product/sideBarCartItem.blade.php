@php
  $itemId = $item->id;
  $productImage = $product->image;
  $productSlug = $product->slug;
  $productTitle = $product->title;

  $productAttributes = $product->attributes;
  $productAttributeArray=[];
  foreach($productAttributes as $key => $valueTwo){
    if($attribute=$valueTwo->attribute){
      $productAttributeArray[]="$attribute->name: $valueTwo->attribute_value";
    }
  }
  if(count($productAttributeArray)){
    $title=implode(', ', $productAttributeArray);
    $productTitle="$productTitle ($title)";
  }

  $itemPrice = $item->unit_price;
  $itemSpecialPrice = $item->price;
@endphp
<div class="test card border-0 border-bottom py-3 cart-product" id="cart-item-{{ $itemId }}">

  <div class="row">
    <div class="col-sm-3 col-3">
      <img src="{{ $productImage ? $productImage->image(250, 250) : asset('assets/image/default.png') }}" alt="{{ $productTitle }}" class="thumb img-thumbnail">
    </div>
    <div class="col-sm-9 col-9">
      <div class="d-flex justify-content-between align-items-center">
      <div class="">
        <a href="{{ route('product.detail', [$productSlug, 'id' => $product->id]) }}">
          <h6 class="text-dark text-truncate" style="max-width:180px;">{{ $productTitle }}</h6>
        </a>
      </div>
      <div class="">
        <button type="button" class="btn btn-close close-cart  remove-mini-item" data-id="{{ $itemId }}" aria-label="Remove item">

        </button>
      </div>
    </div>

      @if($product->avaibility > 0)
        <div class="d-flex align-items-centre my-2 justify-content-between">
        <div class="mini-cart-btn">
          <span class="minusnew decrease-qty-btn py-1 pointer" onclick="decreaseQuantity('{{ $itemId }}')" style="height:100%">-</span>
          <span class="text-dark ms-2">{{ $item->quantity }}</span></span>
          <span class="plusnew increase-qty-btn ms-2 py-1 pointer" onclick="increaseQuantity('{{ $itemId }}')" style="height:100%">+</span>
        </div>
        @if($itemPrice != $itemSpecialPrice)
          <div class="cart_p">
            <strike class="text-black cart-p me-2">₹{{ $itemPrice }}</strike>
            <span class="text-danger cart-p">₹{{ $itemSpecialPrice }} x </span>
            <span class="text-danger cart-p">{{ $item->quantity }}</span>
          </div>
        @else
        <div class="">
          <span class="text-danger cart-p">₹{{ $itemPrice }} x </span>
          <span class="text-danger cart-p">{{ $item->quantity }}</span>
        </div>
        @endif
        </div>
      @else
        <div class="number-1 d-flex align-items-centre my-2">Out of stock</div>
      @endif
    </div>
  </div>
</div>
