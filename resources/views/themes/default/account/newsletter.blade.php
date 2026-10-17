@extends(themeView().'layout')

@section('title', 'Newsletter | '.config("app.name"))

@section('content')
<section class="account-page-ui dashboad_section">
<div class="container" id="profile-app">
    <div class="row">
     @include(themeview().'account._sidebar')
     <div class="col-sm-9 mt-5 mt-md-0 pad-ap3">
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
                  <label for="subscribe" class="form-label">You have Subscribed Successfully.</label>
                  <hr class="m-0 p-0">
                  <a href="{{route('user.newsletter.status', [0])}}">
                    <button class="main_button" type="submit">Unsubscribed</button>
                  </a>
                @else
                  <label for="subscribe" class="form-label"> Yes, I want to subsribe</label>
                  <hr class="m-0 p-0">
                  <a href="{{route('user.newsletter.status', [1])}}">
                    <button class="main_button" type="submit">Subscribe</button>
                  </a>
                @endif
            </div>
        </div>
      </div>
    </div>
</div>
</section>
@endsection
