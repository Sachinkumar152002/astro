@php
 $todayDate = date("Y-m-d");
 $productSpeicalPriceFromDate = $product->sale_price_start_date;
 $productSpeicalPriceToDate = $product->sale_price_end_date;
 $productPrice = $product->base_price;
 $productSpecialPrice = $product->sale_price;
 $percentPrice = $productPrice - $productSpecialPrice;
 $percent = ($percentPrice / $productPrice) * 100;
@endphp
<div class="mx-205">
  @if($productSpeicalPriceFromDate && ($productSpeicalPriceFromDate <= $todayDate) && ($productSpeicalPriceToDate >= $todayDate))
    <div class="">
      <strike class="text-mute fs-5 me-2">₹{{ $productPrice }}</strike>
      <span class="text-danger fw-bold fs-5">₹{{ $productSpecialPrice }}</span>
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
