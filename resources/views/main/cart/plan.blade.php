<div class="panel panel-default checkout-panel" id="panel-shipping">
    <div class="panel-heading bg-blue checkout-heading" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-delivery">
        <h4 class="panel-title checkout-acc-title"> <a class="accordion-checkout collapsed" data-toggle="collapse" data-parent="#checkout-accordion" href="#collapse-delivery">Cart Details</a> </h4>
    </div>
    <div id="collapse-delivery" class="panel-collapse details collapse in">
        <div class="panel-body checkout-inside table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Plan</th>
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
                        <td>{{$cartItems['duration']}} Days</td>
                        <td>{{$cartItems['downloads']}}</td>
                        <td class="text-right"><i class="fa fa-inr"></i> {{number_format($cartItems['without_gst'],2)}}</td>
                        <td class="text-right"><i class="fa fa-inr"></i> {{number_format($cartItems['gst'],2)}}</td>
                        <td class="text-right"><i class="fa fa-inr"></i> {{number_format($cartItems['price'],2)}}</td>
                        <td class="text-right"><div class="cross-icon"><a href="{{route('cart.plan.remove')}}"><i class="fa fa-times-circle"></i></a></div></td>
                    </tr>
            </tbody>
             </table>
            <div class="row">
                <div class="col-md-4 pull-right table-responsive">
                    <table class="table table-condensed">
                        <tr><th>Total</th><td>:</td><td class="text-right"><i class="fa fa-inr"></i> {{number_format($cart['subtotal'], 2)}}</td></tr>
                        <tr><th>GST ({{number_format($cart['gst_percentage'], 2)}}%)</th><td>:</td><td class="text-right"><i class="fa fa-inr"></i> {{number_format($cart['gst'], 2)}}</td></tr>
                        <tr><th>Grand Total</th><td>:</td><td class="text-right"><i class="fa fa-inr"></i> {{number_format($cart['total'], 2)}}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>