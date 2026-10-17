@extends('layouts.app')

@section('content')
<section class="middle-section bg-grey">
    <div class="container">
        <div class="row">
    <div class="col-md-6 col-md-offset-3 white-bg">
    <div class="col-md-12 text-center">
    <h1 class="success-icon"><i class="fa fa-times-circle text-danger"></i></h1></h1>
            </div>
            <div class="col-md-12 text-center">

    <h1>Sorry !</h1>
    <h1 class="text-danger">Payment Failed</h1>
    <p>Your Payment is not authorized by payment gateway.</p>
    <p>If your amount is deducted from account than check it with your bank or let us know for any help.</p>


            </div>
 </div>
        </div>
    </div>
</section>
@endsection
