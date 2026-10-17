@php
  $totalCartItemsAmount=$adminSideCartItems->sum("unit_sale_price");
  $subTotal=$adminSideCartItems->sum("unit_sale_price");
  $discount=$couponAmount+$discount_amount;
  $gstAmount=0;
  $shippingAmount=$shipping_amount;
  $total=0;
  $pincode=$shippingAddress?$shippingAddress->zipcode:0;
  $adminSideCartItemsCount=$adminSideCartItems->count();
@endphp
@foreach($adminSideCartItems as $index=>$item)
  @php
    $product=$item->product;
    $unit_sale_price=$item->unit_sale_price;
    $item_coupon_amount=($unit_sale_price/$totalCartItemsAmount)*$discount_amount;
    $unit_sale_price=$unit_sale_price-$item_coupon_amount;
    $gstAmount+=($unit_sale_price)*($item->tax_percentage/100);
  @endphp
@endforeach
@php
  $total=($subTotal-$discount)+$gstAmount+$shippingAmount;
@endphp
<table class="table total_box bg-transparent table-borderless">
  <tbody>
    <tr class="table-light">
      <th>Subtotal</th>
      <th class="text-right">
        <span>₹ {{ sprintf('%.2f', $subTotal) }}</span>
      </th>
    </tr>
    <tr class="table-light">
      <th onclick="openDiscountAmountModal()" class="text-primary pointer">Discount</th>
      <th class="text-right">
        <span>- ₹ {{ sprintf('%.2f', $discount) }}</span>
      </th>
    </tr>
    <tr class="table-light">
      <th>GST & Taxes</th>
      <th class="text-right">
        <span>+ ₹ {{ sprintf('%.2f', $gstAmount) }}</span>
      </th>
    </tr>
    <tr class="table-light">
      <th onclick="openShippingAmountModal()" class="text-primary pointer">Shipping Charges</th>
      <th class="text-right">
        <span>+ ₹ {{ sprintf('%.2f', $shippingAmount) }}</span>
      </th>
    </tr>
    <tr class="table-light">
      <th>Total</th>
      <th class="text-right">
        <span>₹ {{ sprintf('%.2f', $total) }}</span>
      </th>
    </tr>
  </tbody>
</table>
<div class="d-flex justify-content-between">
  <div class="">
    <button class="btn btn-primary" id="place-cod-order-btn" onclick="handlePlaceOrder(this,'cod')" type="button">Place COD Order</a>
  </div>
  <div class="">
    <button class="btn btn-primary" id="generate-order-link-btn" onclick="generateCheckoutLink(this)" type="button">Generate Order Link</a>
  </div>
</div>
