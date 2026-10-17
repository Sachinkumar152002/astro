@extends('layouts.app')

@section('content')
<section class="middle-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-group" id="checkout-accordion">

    @if($cartItems && is_array($cartItems) && count($cartItems)>0)
                        @if(Auth::guard('employer')->check())
                            @include('main.cart.plan')
                            @include('main.cart.employerdetails')
                            @include('main.cart.employerpayment')
                        @else
                            @include('main.cart.items')
                            @include('main.cart.details')
                            @include('main.cart.payment')
                        @endif
    @else
                       @if(Auth::guard('employer')->check())
                            <p>No item in cart. Click <a href="{{route('employer.plan')}}">here</a> to continue shopping</p>
                        @else
                          <p>No item in cart. Click <a href="{{route('courses')}}">here</a> to continue shopping</p>
                        @endif
                    @endif


                </div>
            </div>

        </div>
    </div>
</section>

@if($courses)
 <section class="">
        <div class="container cross-sell-box">
            <div class="row">
               <h2 class="title-1 text-uppercase">You may also like</h2>
               <div class="container">
               <div class="course-main-div">
               @include('main.courses.list')
           </div>
       </div>
        </div>  <!--row end-->
    </div>
</section>
@endif

@endsection

@push('scripts')
@if(!Auth::guard('employer')->check())
<script type="text/javascript">
    $('#coupon-form').validate({
    submitHandler : function(form){
        var form = $(form);
            axios.post(form.attr('action'), form.serialize())
            .then(function (response) {
                console.log(response);
                toastr.success(response.data.message);
                setTimeout(function(){
                    window.location.href = '{{route('cart.view')}}';
                }, 2000);
            })
            .catch(function (error) {
                 var errors = error.response.data;
                for(i in errors){
                    toastr.error(errors[i]);
                }
            });
             return false;
        }
});
</script>
@endif
@endpush
