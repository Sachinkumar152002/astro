@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
@php
  $_token=request()->has('_token')?request()->get("_token"):'';
@endphp
<section class="dashboad_section" style="background: #f2f2f2;">
<div class="container">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
        <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
           <div class="row">
               @if(Session::has('message'))
               <div class="col-md-12">
                   <div class="alert alert-success">
                       {{Session::get('message')}}
                   </div>
               </div>
               @endif
               <div class="col-sm-12">
                   <h3>Subscribe to our newsletter</h3>
                   @if($isSubscribed)
                     @php
                       $redictToUrl=route('astrologer.newsletter.status',[0]);
                       if($_token){
                         $redictToUrl=route('astrologer.newsletter.status',[0,"_token"=>$_token]);
                       }
                     @endphp
                     <label for="subscribe" class="form-label">You have Subscribed Successfully.</label>
                     <hr class="m-0 p-0">
                     <a href="{{ $redictToUrl }}">
                       <button class="btn btn-bg btn-lg btn-rounded" type="submit">Unsubscribed</button>
                     </a>
                   @else
                     @php
                       $redictToUrl=route('astrologer.newsletter.status',[1]);
                       if($_token){
                         $redictToUrl=route('astrologer.newsletter.status',[1,"_token"=>$_token]);
                       }
                     @endphp
                     <label for="subscribe" class="form-label"> Yes, I want to subsribe</label>
                     <hr class="m-0 p-0">
                     <a href="{{ $redictToUrl }}">
                       <button class="main_button btn-rounded" type="submit">Subscribe</button>
                     </a>
                   @endif
               </div>
           </div>
         </div>
    </div>
  </div>
</section>

@endsection
