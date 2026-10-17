<h2 class="fs-20 f5">Items</h2>
@php
  $currentDate=date("Y-m-d");
@endphp
<div class="">
  @foreach($adminSideCartItems as $index=>$item)
    @php
      $product=$item->product;
    @endphp
    @if($product)
      @php
        $title = $product->title;
        $productAttributes = $product->attributes;
        $productAttributeArray = [];
        foreach($productAttributes as $key => $valueTwo){
          if($attribute = $valueTwo->attribute){
            $productAttributeArray[] = "$attribute->name: $valueTwo->attribute_value";
          }
        }
        if(count($productAttributeArray)){
          $title = implode(', ', $productAttributeArray);
          $title="$product->title ($title)";
        }
        $item->base_price=$product->base_price;
        $item->sale_price=$product->sale_price;
        if($product->sale_price && $product->sale_price_start_date && $product->sale_price_start_date<=$currentDate && $product->sale_price_end_date<=$currentDate){
          $item->sale_price=$product->sale_price;
        }
        $item->save();
        $item->unit_base_price=$item->base_price*$item->quantity;
        $item->unit_sale_price=$item->sale_price*$item->quantity;
        $item->save();
      @endphp
      <div class="">
        <div class="d-flex pb-2">
          <div class="">
            <img src="{{ $product->image ? $product->image->image(250, 250) : no_image() }}" class="img-fluid">
          </div>
          <div class="">
            <a href="{{ route('admin.product.edit', [$product->id, $productTypeName]) }}" target="_blank">
              {{ $title }}
            </a>
            @if($item->base_price == $item->sale_price)
              <div class="">
                <span class="text-black">₹ {{ $item->sale_price }} x {{ $item->quantity }}</span>
              </div>
            @else
              <div class="">
                <strike class="text-danger">₹ {{ $item->base_price }}</strike>
                <span class="text-black">₹ {{ $item->sale_price }} x {{ $item->quantity }}</span>
              </div>
            @endif
          </div>
          <div class="">
            <i class="fa fa-minus text-white pointer bg-danger p-1" onclick="decreaseQuantity(this,'{{ $item->item_id }}')"></i>
            <span class="text-black">{{ $item->quantity }}</span>
            <i class="fa fa-plus text-white pointer bg-success p-1" onclick="increaseQuantity(this,'{{ $item->item_id }}')"></i>
          </div>
          <div class="">
            <i class="fa fa-trash text-danger pointer" onclick="handleRemoveProduct(this,'{{ $item->item_id }}')"></i>
          </div>
        </div>
      </div>
    @else
      @php
        $item->delete();
      @endphp
    @endif
  @endforeach
</div>
