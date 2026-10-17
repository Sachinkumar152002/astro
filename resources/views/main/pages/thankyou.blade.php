@extends('layouts.app')
@push('styles')
 <style type="text/css">
  .thanks-page {background: #eae6e6;}
 .thanks-box {background: #fff; width: 60%; margin:auto; margin-top: 30px; margin-bottom: 30px; box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);}
 .thanks-box .top-section {background: #084d89; padding: 20px 0;}
 .bottom-box { padding: 20px 0; }
 .bottom-box h1 { text-align: center; font-size: 50px; font-weight: bold; color:#226fb1; margin-top: 0;}
 .bottom-box h2 { text-align: center; font-size: 25px; font-weight: normal; margin-top: 5px;}
 .btn-custom:hover {color:#fff; background: #093a64;}
  .heading{ font-size:32px; color:#7d7a7a; font-family:'Open Sans', sans-serif;margin-bottom: 20px; margin-top:28px; text-align:center;}
 .heading span{ font-size:36px; color:#7d7a7a; font-family:'Open Sans', sans-serif;margin-bottom: 20px; margin-top:28px; text-align:center; font-weight:800;}
 @media screen and (max-width: 1000px){
 .heading{ font-size:28px; color:#7d7a7a; font-family:'Open Sans', sans-serif;margin-bottom: 20px; margin-top:28px; text-align:center;}
 .heading span{ font-size:28px; color:#7d7a7a; font-family:'Open Sans', sans-serif;margin-bottom: 20px; margin-top:28px; text-align:center; font-weight:800;}

}
 .round{ text-align:center;}
 .round img { height:120px; width:200px; border-radius:50%;}
@media screen and (max-width: 600px){
    .thanks-box {width:100%;}
    .bottom-box h1 { font-size: 35px; }
    .bottom-box h2 { font-size: 25px; margin-top: 5px; }
    .bottom-box { padding: 20px; }
}

 </style>
 <!-- Event snippet for Lead conversion page -->
 <script>
  gtag('event', 'conversion', {'send_to': 'AW-822003219/GwDMCJfehHsQk4z7hwM'});
</script>
@endpush
@section('content')
<section class="thanks-page">
    <div class="container">
        <div class="col-md-12">
    <div class="thanks-box">
                <div class="top-section">
                    <div class="text-center"><img src="{{asset('images/thumbs-up.png')}}" class="responsive"></div>
                </div>
            <div class="bottom-box text-center"><h1 class="text-center">Thank You !</h1>
                <h2>for your Query</h2>
                <p>Your <strong>Queries</strong> and the <strong>Solution</strong> are most priority for us</p>
                <p>Our team will get back to you within 24 hrs.</p>
                <a class="btn btn-custom" href="{{route('index')}}">Continue to Home Page</a>
            </div>
    </div>
        </div> <!--round end-->
    </div>
</section>
@endsection