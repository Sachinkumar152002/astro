<div class="card bg-light p-3 border-0 d-flex align-items-center justify-content-center">
  <div class="input-group w-100 mb-3">
    <input type="text" id="coupon_code" name="coupon_code" class="form-control mr-5" placeholder="Discount code" value="{{ $coupon_code }}"  {{ $couponAmount>0?"disabled":'' }}>
    <button class="btn btn-primary {{ $couponAmount>0?'d-none':'' }}" type="button" onclick="applyCouponCode('order')" id="coupon-apply-btn">Apply</button>
    <button class="btn btn-danger {{ $couponAmount>0?'':'d-none' }}" type="button" onclick="removeCouponCode()" id="coupon-remove-btn">Remove</button>
  </div>
</div>
