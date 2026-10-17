<div class="panel panel-default checkout-panel" id="panel-payment">
    <div class="panel-heading bg-blue checkout-heading" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-payment-method">
        <h4 class="panel-title checkout-acc-title"> <a class="accordion-checkout" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-payment-method">Payment Method</a> </h4>
    </div>
    <div id="collapse-payment-method" class="panel-collapse details collapse in">
        <div class="panel-body checkout-inside">
            <div class="col-md-12">
                @if(Auth::guard('employer')->check())
                <form action="{{route('emp.order.place')}}" method="post" id="place_order_form">
                    {{csrf_field()}}
                @endif
                <div class="col-md-6">
                    @if($cart['total'])
                    <div class="col-md-1">
                        <input type="radio" name="payment_method" required="" value="paytm">
                    </div>
                    <div class="image-section1 col-md-11">
                        <img src="{{asset('images/payment-gateway/paytm.png')}}" class="img-responsive"> <br><small>(You will be rediredcted to Paytm)</small>
                    </div>
                  @else
                        <div class="col-md-1">
                            <input type="radio" name="payment_method" required="" value="no-payment" checked="">
                        </div>
                        <div class="image-section1 col-md-11">
                            <p>No Payment required</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-12" style="padding-top: 20px;">
                <div class="btn-section cart-login text-center">
                    <button type="button" class="" @if(!Auth::guard('employer')->check()) disabled @else onclick="$('#place_order_form').submit(); return false;" @endif >Proceed to pay</button>
                </div>
                </div>
                @if(Auth::guard('employer')->check())
                </form>
                @endif
            </div>

        </div>
    </div>
</div>