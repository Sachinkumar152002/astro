<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style media="screen">
      table tr th{
        text-align:left;
      }
    </style>
</head>
<body>
  @php
    $websiteLogo=logo(50,50);
    $web
  @endphp
  <div style="padding:20px; text-align:center;">
    <img src="{{ asset('assets/images/logo.jpg') }}" alt="{{ config('app.name') }}" style="width:200px; height:70px;">
  </div>

  <div style="padding:20px;">
      @yield('content')
  </div>

  <div style="background-color:#f1f1f1; color:#555; padding:10px; text-align:center;">
    <p>Thank you,</p>
    <strong>Team {{config('app.name')}}</strong>
    <p>
      <span>Contact us at</span>
      <a href="mailto:{{ config('vrdcms.custome_care_email') }}">{{ config('vrdcms.custome_care_email') }}</a>
    </p>
  </div>
</body>
</html>
