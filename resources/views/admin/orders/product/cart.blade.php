<style media="screen">

.mx-hscroll {
  max-height: 400px;
  overflow-y: auto;
}

.ct-i img{
  width: 100%;
}
.mx-hscroll::-webkit-scrollbar {
  width: 4px;
}

.mx-hscroll::-webkit-scrollbar-track {
  background: #e0e0e0;
}

.mx-hscroll::-webkit-scrollbar-thumb {
  background: #a8a8a8;
  border-radius: 10px;
}

.mx-hscroll::-webkit-scrollbar-thumb:hover {
  background: #888;
}

</style>
@php
  $adminSideCartItemsCount=$adminSideCartItems->count();
@endphp
@if($adminSideCartItemsCount>0)
  <h2 class="fs-20 f5">Items</h2>
  @php
    $currentDate=date("Y-m-d");
  @endphp
  <div class="row mx-hscroll">
    @foreach($adminSideCartItems as $index=>$item)
      @php
        $product=$item->product;
        $title=$item->title;
      @endphp
    <div class="col-md-6">
      <div class="cart-item border border-black p-2 mb-2">
        <div class="">
          <div class="ct-i">
            <img src="{{ $item->image_url }}" class="img-fluid">
          </div>
          <div class=" mt-2">
            @if($product)
              <a href="{{ route('admin.product.edit', [$product->id, $productTypeName]) }}" target="_blank"><p class="text-truncate mb-1" style="max-width:170px;">{{ $title }}</a></p>
            @else
              <a><p class="text-truncate mb-1" style="max-width:170px;">{{ $title }}</p></a>
            @endif
            @if($item->base_price == $item->sale_price)
              <div class="">
                <span class="text-black">₹ {{ $item->sale_price }}</span>
              </div>
            @else
              <div class="">
                <strike class="text-danger">₹ {{ $item->base_price }}</strike>
                <span class="text-black">₹ {{ $item->sale_price }}</span>
              </div>
            @endif
          </div>
          <div class="d-flex justify-content-between">
            <div class="ml-3 d-flex align-items-center">
              <i class="fa fa-minus text-danger pointer" onclick="decreaseQuantity(this,'{{ $item->item_id }}')"></i>
              <span class="text-black mx-1">{{ $item->quantity }}</span>
              <i class="fa fa-plus text-success pointer" onclick="increaseQuantity(this,'{{ $item->item_id }}')"></i>
            </div>
            <div class="ml-3 d-flex align-items-center">
              <i class="fa fa-edit text-success pointer" onclick="openAddCustomProductModal('{{ $item->id }}','{{ $item->title }}','{{ $item->sale_price }}','{{ $item->quantity }}','{{ $item->hsn_code_id }}')"></i>
              |
              <i class="fa fa-trash text-danger pointer" onclick="handleRemoveProduct(this,'{{ $item->item_id }}')"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
@endif
