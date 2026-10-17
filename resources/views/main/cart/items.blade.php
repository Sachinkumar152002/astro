<div class="panel panel-default checkout-panel" id="panel-shipping">
    <div class="panel-heading bg-blue checkout-heading" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-delivery">
        <h4 class="panel-title checkout-acc-title"> <a class="accordion-checkout collapsed" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-delivery">Cart Details</a> </h4>
    </div>
    <div id="collapse-delivery" class="panel-collapse details collapse in">
        <div class="panel-body checkout-inside table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <td></td>
                        <th>Name</th>
                        <td>Duration</td>
                        <td>Modules</td>
                        <td>Certificate by</td>
                        <td class="text-right">Base Price</td>
                        <td class="text-right">GST</td>
                        <td class="text-right">Price</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
            @foreach($cartItems as $item_id=>$item)

                    <tr>
                        <td class="text-center"><img class="img-responsive" src="{{$item['image']}}" alt="{{$item['name']}}"></td>
                        <td>{{$item['name']}}</td>
                        <td>{{$item['access']}} Days</td>
                        <td>{{$item['modules']}}</td>
                        <td>{{$item['certified_by']}}</td>
                        <td class="text-right"><i class="fa fa-inr"></i> {{number_format($item['without_gst'],2)}}</td>
                        <td class="text-right"><i class="fa fa-inr"></i> {{number_format($item['gst'],2)}}</td>
                        <td class="text-right"><i class="fa fa-inr"></i> {{number_format($item['purchase_price'],2)}}</td>
                        <td class="text-right"><div class="cross-icon"><a href="{{route('cart.item.remove', $item_id)}}"><i class="fa fa-times-circle"></i></a></div></td>
                    </tr>


{{--<div class="cartsection">
<div class="col-md-2 cart-img"><img class="img-responsive" src="{{$item['image']}}"></div>
<div class="col-md-10  cart-content">
<h4>{{$item['name']}}</h4>
<div class="cart-details">
<div class="cart-detail-box"><i class="fa fa-clock-o"></i> Duration<br/>{{$item['access']}} Days</div>
<div class="cart-detail-box"><i class="fa fa-check-circle"></i> No. of Modules<br/>{{$item['modules']}}</div>
<div class="cart-detail-box"><i class="fa fa-newspaper-o"></i> Type of Certification <br/>{{$item['certified_by']}}</div>
<div class="cart-detail-box">
@if($item['offer_price'])
Offer Price :
<strike><i class="fa fa-inr"></i> {{number_format($item['price'])}}/-</strike>
<i class="fa fa-inr"></i> {{number_format($item['offer_price'])}}/-
@else
Price :
<i class="fa fa-inr"></i> {{number_format($item['price'])}}/-
@endif
</div>
</div>

</div>
</div>--}}
            @endforeach
            </tbody>
             </table>
            <div class="row">

                <div class="col-md-6">
                    <div class="coupon-box pull-left wid1100">
                        <form method="post" action="{{route('cart.coupon.apply')}}" id="coupon-form">
                            {{csrf_field()}}
                            <div class="pull-left col-md-6">
                                <input type="text" placeholder="Coupon" name="coupon" required="" value="{{$cart['coupon_code']}}">
                            </div>
                            <div class="pull-left col-md-6">
                                <button class="pull-left" type="submit">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 pull-right table-responsive">
                    <table class="table table-condensed">
                        <tr><th>Total</th><td>:</td><td class="text-right"><i class="fa fa-inr"></i> {{number_format($cart['subtotal'], 2)}}</td></tr>
                        <tr><th>GST ({{number_format($cart['gst_percentage'], 2)}}%)</th><td>:</td><td class="text-right"><i class="fa fa-inr"></i> {{number_format($cart['gst'], 2)}}</td></tr>
                        <tr><th>Grand Total</th><td>:</td><td class="text-right"><i class="fa fa-inr"></i> {{number_format($cart['total'], 2)}}</td></tr>
                        @if($cart['discount'])
                        <tr>
                            <th>
                            Discount ({{number_format($cart['coupon_perc'],2)}}%)
                             <a href="{{route('cart.coupon.remove')}}" class="pull-right"><i class="text-danger fa fa-trash"></i></a>
                        </th><td>:</td>
                            <td class="text-right"><i class="fa fa-inr"></i> {{number_format($cart['discount'],2)}}</td>
                        </tr>
                        <tr>
                            <th>Net Total</th><td>:</td>
                            <td class="text-right"><i class="fa fa-inr"></i> {{number_format($cart['net_total'], 2)}}</td>
                        </tr>
                        @endif
                    </table>
                    {{--<div class="btn-section cart-login">
                        <button class="proceed-to-pay">Next</button>
                    </div>--}}

                </div>

            </div>

        </div>
    </div>
</div>