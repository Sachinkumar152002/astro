@extends(themeView().'layout')
@section('title', 'Newsletter')
@section('keywords', '')
@section('description','')
@section('content')

<section class="page-header">
<div class="container">
    <h1 class="text-center">Newsletter</h1>
    <div class="text-center">
        @include(themeView().'common.breadcrumb')
    </div>
</div>
</section>
<div class="container mt-3">
<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8 bg-warning pt-3 mb-4">
        @if(Session::has('message'))
            <div class="col-md-12">
                <div class="alert alert-success">
                    {{Session::get('message')}}
                </div>
            </div>
            @endif
    <div class="card  mb-3">
        <div class="card-header">
        <h4 class="h4 text-orange"><i class="fa fa-envelope"></i> Subscribe to our Newsletter</h4>
        </div>
        <div class="card-body">

            <form method="POST" action="{{route('newsletter')}}">
                @csrf
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input id="name" type="text" class="form-control" placeholder="Name" required name="name" value="{{old('name')}}">
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone</label>
                <input id="phone" type="tel" class="form-control" placeholder="Phone Number" required name="phone" value="{{old('phone')}}">
                @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" placeholder="name@example.com" required name="email"  value="{{old('email')}}">
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group text-right">
                <button class="btn btn-warning btn-lg btn-rounded" type="submit">Subscribe</button>
            </div>
            </form>
        </div>
    </div>
    </div>

    <div class="col-sm-2"></div>
</div>

</div>
@endsection