<div class="course_summary_wrap" id="step-1-summary">
  <div class="summary_inner">
    <div class="summary_heading active_heading">Course Summary <a href="javascript:void(0)" class="pull-right" onclick="moveToStep(1)"><i class="fa fa-edit pull-right"></i></a></div>

  </div>
  <div class="course-summary-section" >
    <table class="sep sep1 w100">
      <thead>
          <tr>
             <th>Plan</th>
             <th>Posts</th>
              <td>Duration</td>
              <td>Downloads</td>
              <td class="text-right">Base Price</td>
              <td class="text-right">GST</td>
              <td class="text-right">Price</td>
              <td></td>
          </tr>
      </thead>

       <tbody>
             <tr>
              <td>{{$cartItems['name']}}</td>
              <td>{{$cartItems['posts']}}</td>
              <td>{{$cartItems['duration']}} Days</td>
              <td>{{$cartItems['downloads']}}</td>
              <td class="text-right"><i class="fa fa-inr"></i> {{number_format($cartItems['without_gst'],2)}}</td>
              <td class="text-right"><i class="fa fa-inr"></i> {{number_format($cartItems['gst'],2)}}</td>
              <td class="text-right"><i class="fa fa-inr"></i> {{number_format($cartItems['price'],2)}}</td>
              <td class="text-right"><div class="cross-icon"><a href="{{route('cart.plan.remove')}}" class="text-danger"><i class="fa fa-trash"></i></a></div></td>
          </tr>
        </tbody>
    </table>
    <div class="discount-section">
  <div class="discount">
    <table class="sep w100 cp-table">
      <tbody>
        <tr class="cp1table">
          <th>Total</th><td><i class="fa fa-inr"></i> {{number_format($cart['subtotal'], 2)}}</span></td>
        </tr>
          <tr class="cp2table"><th>GST ({{number_format($cart['gst_percentage'], 2)}}%)</th><td><i class="fa fa-inr"></i> {{number_format($cart['gst'], 2)}}</td></tr>
          <tr class="cp3table"><th>Grand Total</th><td><i class="fa fa-inr"></i> {{number_format($cart['total'], 2)}}</td></tr>

        </tbody>
      </table>
      <div class="coupon-wrapper">
        <div class="form-group">
          <button class="btn-signin" onclick="moveToStep(2)">Proceed</button>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>