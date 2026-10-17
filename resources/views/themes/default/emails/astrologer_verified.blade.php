@extends(themeView().'emails.layout')
@section('content')
  <div class="">
      <p>Hi {{$astrologer->display_name}},</p>
      <p>We are pleased to inform you that your astrologer verification has been successfully completed on the {{config('app.name')}} web and app portal.</p>
      <p>Your profile is now live, and you can start offering your services to clients. Please ensure that your information is up-to-date and accurately reflects your expertise to enhance your visibility on our platform.</p>
      <p>Please click on the link below to login:</p>
      <p><a href="{{route('astrologer.login')}}" style="color: #1a73e8; text-decoration: none;">{{route('astrologer.login')}}</a></p>
      <p>If you have any questions or need assistance, feel free to reach out to our support team.</p>
  </div>
@endsection
