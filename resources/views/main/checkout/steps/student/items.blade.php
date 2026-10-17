<div class="course_summary_wrap" id="step-1-summary">
    <div class="summary_inner">
        <div class="summary_heading active_heading">Course Summary
            <a href="javascript:void(0)" class="pull-right" onclick="moveToStep(1)">
                <i class="fa fa-edit pull-right"></i>
            </a>
        </div>

    </div>
    <div class="course-summary-section">
        <table class="sep sep1 w100">
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
                    <td class="text-center"><img class="img-responsive" src="{{$item['image']}}"
                                                 alt="{{$item['name']}}"></td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['access']}} Days</td>
                    <td>{{$item['modules']}}</td>
                    <td>{{$item['certified_by']}}</td>
                    <td class="text-right"><i class="fa fa-inr"></i> {{number_format($item['without_gst'],2)}}</td>
                    <td class="text-right"><i class="fa fa-inr"></i> {{number_format($item['gst'],2)}}</td>
                    <td class="text-right"><i class="fa fa-inr"></i> {{number_format($item['purchase_price'],2)}}</td>
                    <td class="text-right">
                        <div class="cross-icon "><a href="{{route('cart.item.remove', $item_id)}}"
                                                    class="text-danger"><i class="fa fa-trash"></i></a></div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="discount-section">
            <div class="discount">
                <table class="sep w100 cp-table">

                    <tbody>
                    <tr class="cp1table">
                        <th>Total</th>
                        <td><i class="fa fa-inr"></i> {{number_format($cart['subtotal'], 2)}}</span></td>
                    </tr>
                    <tr class="cp2table">
                        <th>GST ({{number_format($cart['gst_percentage'], 2)}}%)</th>
                        <td><i class="fa fa-inr"></i> {{number_format($cart['gst'], 2)}}</td>
                    </tr>
                    <tr class="cp3table">
                        <th>Grand Total</th>
                        <td><i class="fa fa-inr"></i> {{number_format($cart['total'], 2)}}</td>
                    </tr>
                    @if($cart['discount'])
                        <tr class="cp3table">
                            <th> Discount ({{number_format($cart['coupon_perc'],2)}}%) <a
                                        href="{{route('cart.coupon.remove')}}" class=""><i
                                            class="text-danger fa fa-trash"></i></a></th>
                            <td><i class="fa fa-inr"></i> {{number_format($cart['discount'],2)}}</td>
                        </tr>

                        <tr class="cp3table">
                            <th> Net Total</th>
                            <td><i class="fa fa-inr"></i> {{number_format($cart['net_total'], 2)}}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="coupon-wrapper">
                    @if(!$cart['discount'])
                        <div class="form-group couponbox">
                            <form method="post" action="{{route('cart.coupon.apply')}}" id="coupon-form">
                                {{csrf_field()}}
                                <input class="form-control1" placeholder="Enter Coupon Code" name="coupon" required=""
                                       value="{{$cart['coupon_code']}}">
                                <button class="btn btn-info btnsubmitelement" type="submit" id="">Apply</button>
                            </form>
                        </div>
                    @endif
                    <div class="form-group">
                        <button class="btn-signin" onclick="moveToStep(2)">Proceed</button>
                        <a class="btn-cancel" onclick="confirmation();">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>