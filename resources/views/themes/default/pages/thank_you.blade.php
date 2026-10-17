@extends(themeView().'layout')

@section('title', 'Thank You | ASTROVYRA')
@section('keywords', 'ASTROVYRA, thankyou')
@section('description','ASTROVYRA')
@section('image',  '')

@section('content')
<div class="container pt-4 mt-5  pb-5 pb-md-0 pb-md-4">
     <div class="thank-you-message text-center mt-3 mt-md-0">
          <img src="{{ asset('assets/images/thankyou.png') }}" alt="thankyou" style="width:300px;">
           <h1 class="text-center">Thank You!</h1>
           @if($message)
            {!! $message !!}
           @endif
           <a href="{{route('home')}}" class="btn btn-bg">Back to Home</a>
       </div>
   </div>
@endsection

@push('scripts')
@endpush
