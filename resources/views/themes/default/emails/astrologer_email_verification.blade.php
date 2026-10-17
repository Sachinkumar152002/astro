@extends(themeView().'emails.layout')
@section('content')
  <div class="">
      <h2 style="text-align: center; color: #333;">Welcome to {{config('app.name')}}!</h2>
      <p>Hi {{$astrologer->dispaly_name}},</p>
      <p>Thank you for registering at {{config('app.name')}}! We are excited to have you on board.</p>
      <p>To access your account and start exploring our web and app portal, please verify your email address by clicking the link below:</p>
      <p style="text-align: center;">
          <a href="{{route('astrologers.verify.email', encrypt($astrologer->email))}}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Verify Your Email</a>
      </p>
      <p>If the link doesn’t work, you can copy and paste the URL into your browser:</p>
      <p style="text-align: center;">
          <strong>{{route('astrologers.verify.email', encrypt($astrologer->email))}}</strong>
      </p>
      <p>If you did not create an account using this email address, please disregard this message.</p>
  </div>
@endsection
