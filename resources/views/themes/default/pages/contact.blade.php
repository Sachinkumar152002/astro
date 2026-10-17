@extends(themeView().(request()->webview?'webview':'layout'))

@section('title', $page->meta_title)
@section('keywords', $page->meta_keywords)
@section('description',$page->meta_description)

@if($page->bannerImage && $page->bannerImage->originalPath)
  @section('image')
  {{$page->bannerImage->originalPath}}
  @endsection
@endif

@section('content')
@if(!request()->webview)
<section class="page-header" @if($page->bannerImage) style="background-image:url('{{$page->bannerImage->originalPath}}')"@endif>
  <div class="container">
      <h1 class="text-center">{{$page->name}}</h1>
      <div class="text-center">
      @include(themeView().'common.breadcrumb')
      </div>
  </div>
  </section>
@endif
<div class="container mt-3">
    <div class="row pt-4 pb-4">
        @if(Session::has('message'))
      <div class="col-md-12">
          <div class="alert alert-success">
            {{Session::get('message')}}
          </div>
        </div>
        @endif

      <div class="col-sm-3">
        <div class="card">
          <div class="card-header">
            <h4 class="h4">Help Center {{request()->webview}}</h4>
          </div>
          <div class="card-body pt-2 help-center">

        {!! $page->content !!}
      </div>
        </div>
      </div>
        <div class="col-sm-6">
            <div class="card phonelogin">
                <div class="card-body">
          <h4 class="h4">Write to Us</h4>
          <hr>
         <form method="POST" action="{{route('contact')}}">
          @csrf
           <div class="form-group">
             <label for="phone">Name</label>
             <input type="text" class="form-control" placeholder="Name" required id="name" name="name">
           </div>

           <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" class="form-control" placeholder="Phone" required id="phone" name="phone">
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" placeholder="Email" required id="email" name="email">
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea  class="form-control" placeholder="Message" required id="message" name="message"></textarea>
          </div>
           <div class="form-group">
           <button id="phonelogin" class="btn btn-dark" type="submit">Submit</button>
           </div>

         </form>
        </div>
        </div>
    </div>
    <div class="col-sm-3">
      <div class="card">
        <div class="card-header">
          <h4 class="h4">Contact Us</h4>
        </div>
        <div class="card-body pt-2 help-center">
          <ul>
            <li><a href="tel:{{optionByKey('contact')}}"><i class="fa fa-phone-volume"></i>: {{optionByKey('contact')}}</a></li>
            <li><a href="mailto:{{optionByKey('contact_email')}}"><i class="fa fa-envelope"></i> {{optionByKey('contact_email')}} </a></li>
            <li class="text-left mt-2"><i class="fa fa-map-marker"></i> S-06, Second Floor, A1/B Sector16, Noida, Uttar Pradesh-201301 </li>
        </ul>
    </div>
      </div>
    </div>

    </div>
 </div>
@endsection
