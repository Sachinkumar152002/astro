<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Login - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('theme-admin/vendor/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('theme-admin/vendor/font-awesome/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme-admin/css/styles.css') }}">
    <style>
        * { box-sizing: border-box; }
        body.admin-login-page { min-height:100vh; margin:0; color:#29233a; background:#f7f4fa; }
        .admin-login-page .login-shell { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:32px 18px; background:radial-gradient(ellipse at 12% 10%,rgba(245,158,11,.12),transparent 34%),radial-gradient(ellipse at 90% 88%,rgba(91,39,126,.12),transparent 38%),#f8f6fb; }
        .admin-login-page .login-card { width:100%; max-width:940px; display:grid; grid-template-columns:1fr 1fr; overflow:hidden; border:1px solid #ebe5f0; border-radius:22px; background:#fff; box-shadow:0 24px 70px rgba(47,31,64,.13); }
        .admin-login-page .login-intro { display:flex; flex-direction:column; justify-content:center; align-items:flex-start; padding:54px 48px; color:#fff; background:linear-gradient(145deg,#32144c,#623887 68%,#80539c); }
        .admin-login-page .intro-mark { display:inline-flex; align-items:center; gap:9px; margin-bottom:34px; color:#f7bd59; font-size:12px; font-weight:700; letter-spacing:2px; text-transform:uppercase; }
        .admin-login-page .intro-mark:before { content:""; width:28px; height:2px; background:#f7bd59; }
        .admin-login-page .login-intro h1 { margin:0 0 16px; color:#fff; font-family:Georgia,serif; font-size:36px; line-height:1.15; }
        .admin-login-page .login-intro p { max-width:340px; margin:0; color:rgba(255,255,255,.78); font-size:15px; line-height:1.7; }
        .admin-login-page .login-form-panel { padding:48px 52px; }
        .admin-login-page .brand-logo { display:flex; justify-content:center; align-items:center; min-height:72px; margin-bottom:25px; }
        .admin-login-page .brand-logo img { display:block; max-width:min(210px,100%); max-height:72px; object-fit:contain; }
        .admin-login-page .form-heading { margin:0 0 7px; text-align:center; color:#2d2040; font-size:25px; font-weight:700; }
        .admin-login-page .form-subheading { margin:0 0 28px; text-align:center; color:#81798a; font-size:14px; }
        .admin-login-page .alert { margin-bottom:18px; border:0; border-radius:10px; }
        .admin-login-page .field-label { display:block; margin:0 0 8px; color:#443a50; font-size:13px; font-weight:600; }
        .admin-login-page .form-control { width:100%; height:48px; border:1px solid #e4dfea; border-radius:10px; padding:10px 13px; box-shadow:none; }
        .admin-login-page .form-control:focus { border-color:#80539c; box-shadow:0 0 0 3px rgba(98,56,135,.12); }
        .admin-login-page .form-group { margin-bottom:19px; }
        .admin-login-page .login-options { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:4px 0 24px; }
        .admin-login-page .remember-option { display:flex; align-items:center; gap:8px; margin:0; color:#6d6577; font-size:13px; }
        .admin-login-page .remember-option input { accent-color:#623887; }
        .admin-login-page .forgot-link { color:#623887; font-size:13px; font-weight:600; }
        .admin-login-page .submit-button { width:100%; min-height:48px; border:0; border-radius:10px; color:#fff; background:linear-gradient(110deg,#603483,#43215e); font-size:15px; font-weight:700; box-shadow:0 8px 18px rgba(67,33,94,.2); }
        .admin-login-page .submit-button:hover { color:#fff; filter:brightness(1.08); }
        .admin-login-page .security-note { margin:22px 0 0; text-align:center; color:#8d8595; font-size:12px; }
        @media(max-width:767px) {
            .admin-login-page .login-shell { padding:18px 14px; }
            .admin-login-page .login-card { max-width:440px; grid-template-columns:1fr; border-radius:18px; }
            .admin-login-page .login-intro { display:none; }
            .admin-login-page .login-form-panel { padding:34px 24px 30px; }
            .admin-login-page .brand-logo { margin-bottom:20px; }
            .admin-login-page .form-heading { font-size:23px; }
        }
        @media(max-width:360px) {
            .admin-login-page .login-form-panel { padding:28px 18px; }
            .admin-login-page .login-options { align-items:flex-start; flex-direction:column; }
        }
    </style>
</head>
<body class="admin-login-page">
<main class="login-shell">
    <div class="login-card">
        <section class="login-intro" aria-label="Admin portal">
            <div class="intro-mark">{{ config('app.name') }}</div>
            <h1>Welcome back</h1>
            <p>Sign in to manage your platform, review activity, and keep everything running smoothly.</p>
        </section>
        <section class="login-form-panel" aria-labelledby="login-heading">
            <a class="brand-logo" href="{{ url('/') }}" aria-label="{{ config('app.name') }} home">
                <img src="{{ logo() }}" alt="{{ config('app.name') }} logo">
            </a>
            <h2 class="form-heading" id="login-heading">Admin sign in</h2>
            <p class="form-subheading">Enter your admin credentials to continue.</p>

            @if($errors->count())
                <div class="alert alert-danger" role="alert">
                    @foreach($errors->all() as $message)
                        <div>{{ $message }}</div>
                    @endforeach
                </div>
            @endif

            <form role="form" method="POST" action="{{ route('admin.login.submit') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="field-label" for="email">Email address</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="name@example.com" autocomplete="username" required autofocus>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="field-label" for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                </div>
                <div class="login-options">
                    <label class="remember-option" for="remember_me">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} id="remember_me">
                        <span>Remember me</span>
                    </label>
                    <a class="forgot-link" href="{{ route('admin.password.request') }}">Forgot password?</a>
                </div>
                <button type="submit" class="submit-button">Sign in to admin</button>
            </form>
            <p class="security-note"><i class="fa fa-lock" aria-hidden="true"></i> Secure administrator access</p>
        </section>
    </div>
</main>
</body>
</html>
