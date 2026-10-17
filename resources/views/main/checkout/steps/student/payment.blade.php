<div class="course_summary_wrap" id="step-3-payment">
    <div class="summary_inner">
        <div class="summary_heading">Secure Payment</div>
    </div>
    @if(Auth::check())
        <form action="{{route('order.place')}}" method="post" id="place_order_form">
            {{csrf_field()}}
            @endif

            <div class="course-summary-section" style="display: none;">
                <div class="payment-section">

                    @if($cart['total'] && $cart['net_total']!=0)
                        <div class="radio">
                            <label><input type="radio" name="payment_method" required="" value="paytm" checked="">
                                <img alt="logo" src="{{asset('images/paytm-logo.jpg')}}">
                                <small>(It will redirect to paytm payment gateway) <i
                                            class="fa fa-inr">&nbsp;</i> {{number_format($cart['net_total'])}}</small>
                            </label>
                        </div>
                    @else
                        <div class="radio">
                            <label><input type="radio" name="payment_method" required="" value="no-payment" checked="">
                                <p>No Payment required</p></label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="course-summary-section">
                <div class="payment-section">
                    <div class="payment-button text-center">
                        <button class="btn btn-signin" @if(!Auth::check()) disabled
                                @else onclick="$('#place_order_form').submit(); return false;" @endif>Prodeed to Pay <i
                                    class="fa fa-arrow-circle-right">&nbsp;</i></button>
                        <a style="padding-top: 49px;display: block;font-size: 16px;" class="btn-cancel" onclick="confirmation();">Cancel</a>
                    </div>
                </div>
            </div>
            @if(Auth::check())
        </form>
    @endif
</div>