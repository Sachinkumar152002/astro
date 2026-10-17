@extends(themeView().'layout')

@section('title', 'Wishlists | '.config("app.name"))

@section('content')
  <section class="dashboad_section">
    <div class="container">
    <div class="row justify-content-between">
      @include(themeview().'account._sidebar')
      <div class="col-lg-9 col-12 mt-5 mt-md-0 all_pages pad-ap3">
        <div id="products">
          @php
            $hasProduct = false;
          @endphp
          <div class="row">
           @forelse($products as $key => $item)
             @php
              $product = $item->product;
             @endphp
            @if($product)
              @php
                $hasProduct = true;
                $productSlug = $product->slug;
                $productImage = $product->image;
                $todayDate = date("Y-m-d");
                $productSpecialPriceFromDate = $product->sale_price_start_date;
                $productSpecialPriceToDate = $product->sale_price_end_date;
                $productPrice = $product->base_price;
                $productSpecialPrice = $product->sale_price;
                $category = $product->category;
                $productUrl = route('product.detail', [$productSlug, 'id'=>$product->id]);
                if($product->type==2){
                  $productUrl = route('product.detailpuja', [$productSlug, 'id'=>$product->id]);
                }elseif($product->type==3){
                  $productUrl = route('product.detailservice', [$productSlug, 'id'=>$product->id]);
                }
              @endphp
              @if($category && $product->status == 1)
                @php
                  $categorySlug = $category->slug;
                @endphp
                <div class="col-lg-4 col-6 m-0 p-0">
                  <div class="item m-2 pp-bb mt-0 bg-white border-n">
                    <div class="p-box service-card2">
                      <img class="rounded" src="{{$productImage ? $productImage->image(600, 600) : ''}}">
                      <div class="effect"></div>
                      <div class="btn-effect effect-2">
                        <button type="button" data-wishlist_id="{{$item->id}}" class="remove_from_wishlist_btn p-icon me-2">
                          <i class="fa fa-trash pointer text-danger"></i>
                        </button>
                      </div>
                    </div>
                    <div class="p-2">
                      <a href="{{ $productUrl }}" class="prod-name fs-12 text m-0 mt-2 pointer fw-semibold text-truncate" style="max-width:250px;">
                        {{ $product->title }}
                      </a>
                      <div class="d-md-flex justify-content-between align-items-center mt-1">
                        @if($productSpecialPriceFromDate && ($productSpecialPriceFromDate <= $todayDate) && ($productSpecialPriceToDate >= $todayDate) )
                          <div class="">
                            <span class="text-danger fw-bold">₹{{ $productSpecialPrice }}</span>
                              <strike class="text-muted fw-bold">₹{{ $productPrice }}</strike>
                          </div>
                        @else
                          <div class="">
                            <span class="text-danger fw-bold">₹{{ $productPrice }}</span>
                          </div>
                        @endif
                        @if($product->avaibility > 0)
                          <button type="button" onclick="addToCart('{{ route('addtocart') }}', '{{ $product->id }}')" class="p-add res_btn">Add To Cart</button>
                        @else
                          <button type="button" disabled class="p-add res_btn stock_btn">Out of Stock</button>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @else
                <div class="col-lg-4 col-6 m-0 p-0">
                  <div class="item m-2 pp-bb mt-0">
                    <div class="p-box service-card2">
                      <img class="rounded" src="{{$productImage ? $productImage->image(600, 600) : ''}}">
                      <div class="effect"></div>
                      <div class="btn-effect effect-2">
                        <button type="button" data-wishlist_id="{{$item->id}}" class="remove_from_wishlist_btn p-icon me-2">
                          <i class="fa fa-trash pointer text-danger"></i>
                        </button>
                      </div>
                    </div>
                    <div class="p-2">
                      <a href="javascript:void(0)" class="prod-name fs-12 text m-0 mt-2 pointer fw-semibold text-truncate" style="max-width:250px;">
                        {{ $product->title }}
                      </a>
                      <div class="d-md-flex justify-content-between align-items-center mt-1">
                        @if($productSpecialPriceFromDate && ($productSpecialPriceFromDate <= $todayDate) && ($productSpecialPriceToDate >= $todayDate) )
                          <div class="">
                            <span class="text-danger fw-bold">₹{{ $productSpecialPrice }}</span>
                              <strike class="text-muted fw-bold">₹{{ $productPrice }}</strike>
                          </div>
                        @else
                          <div class="">
                            <span class="text-danger fw-bold">₹{{ $productPrice }}</span>
                          </div>
                        @endif
                        <button type="button" disabled class="p-add res_btn stock_btn">Not Available</button>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            @endif
         @endforeach
         <div class="col-12 text-center">
           {!! $products->links('vendor.pagination.bootstrap-5') !!}
         </div>

         @if(!$hasProduct)
           <div class="col-12 text-center">
             <img src="/assets/images/wish.webp" alt="No Item" class="nodata">
             <h5 class="mt-3 text-dark"> No Items Found!</h5>
             <p class="text-secondary"> Your Favourite items will be displayed here.</p>
           </div>
         @endif
       </div>
     </div>
   </div>
 </div>
</section>
@endsection

@push('scripts')
<script type="text/javascript">
  function addToCart(url, item_id){
    $.ajax({
      url:url,
      method:'POST',
      data: {
        item_id:item_id
      },
      headers:{
        "X-CSRF-TOKEN":@json(csrf_token())
      },
      success:function(response){
        if(response.status == 'success'){
          toastr.success(response.message);
        }else if(response.status == 'warning'){
          toastr.warning(response.message);
        }else{
          toastr.error(response.message);
        }
        showCart2();
      },
      error:function(error){
        console.log(error)
      }
    });
  }
</script>
@endpush
