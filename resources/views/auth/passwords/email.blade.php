@extends(themeView().'layout')

@section('title', 'Register')
@section('keywords', 'Register')
@section('description', 'Register')


@section('content')
<section class="hero is-fullheight">
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-6-tablet is-5-desktop is-4-widescreen">
                <h1 class="is-centered is-size-1 has-text-centered">Reset Password</h1>
                @if (session('status'))
                <div class="hero is-primary">
                    {{ session('status') }}
                </div>
            @endif
                <form class="box" role="form" method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
              <div class="field">
                <label for="" class="label">Email</label>
                <div class="control has-icons-left {{ $errors->has('email') ? ' has-error' : '' }}">

                  <input id="email" type="email" class="input" name="email" value="{{ old('email') }}" required autofocus placeholder="Email ID"> @if ($errors->has('email'))
                            <span class="help-block ">
                                    <strong class="has-text-danger">{{ $errors->first('email') }}</strong>
                                </span> @endif
                  <span class="icon is-small is-left">
                    <i class="fa fa-envelope"></i>
                  </span>
                </div>
              </div>

              <div class="field has-text-centered">
                <button class="button is-success" type="submit" >
                    Send Password Reset Link
                </button>

              </div>
              <div class="field has-text-centered">
                <p><a href="{{ route('login') }}" id="signinbtn">Login</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
