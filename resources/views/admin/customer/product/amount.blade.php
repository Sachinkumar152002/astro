@php
  $subTotal=$adminSideCartItems->sum("unit_sale_price");
  $discount=$couponAmount;
  $gstAmount=0;
  $shippingAmount=0;
  $total=0;
@endphp
@foreach($adminSideCartItems as $index=>$item)
  @php
    $product=$item->product;
    if($productHnsCode=$product->hnsCode){
      $gstPercentage=$productHnsCode->gst_percentage;
      $gstAmount+=($item->sale_price)*($gstPercentage/100);
    }
    if($product->apply_shipping){
      $shippingAmount+=calculateShippingAmount($product, $shippingAddress->zipcode)*$item->quantity;
    }
  @endphp
@endforeach
@php
  $total=($subTotal-$discount)+$gstAmount+$shippingAmount;
@endphp
<table class="table total_box bg-transparent table-borderless">
  <tbody>
    <tr class="table-light">
      <th>Subtotal</th>
      <th>
        <span>₹ {{ sprintf('%.2f', $subTotal) }}</span>
      </th>
    </tr>
    <tr class="table-light">
      <th>Discount</th>
      <th>
        <span>- ₹ {{ sprintf('%.2f', $discount) }}</span>
      </th>
    </tr>
    <tr class="table-light">
      <th>GST & Taxes</th>
      <th>
        <span>+ ₹ {{ sprintf('%.2f', $gstAmount) }}</span>
      </th>
    </tr>
    <tr class="table-light">
      <th>Shipping Charges</th>
      <th>
        <span>+ ₹ {{ sprintf('%.2f', $shippingAmount) }}</span>
      </th>
    </tr>
    <tr class="table-light">
      <th>Total</th>
      <th>
        <span>₹ {{ sprintf('%.2f', $total) }}</span>
      </th>
    </tr>
  </tbody>
</table>
<div class="text-center">
  <button class="btn btn-primary" onclick="handlePlaceOrder(this)" type="button">Place Order</a>
</div>
