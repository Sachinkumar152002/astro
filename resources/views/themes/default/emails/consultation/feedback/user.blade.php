@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$appointment->name}}</p>
    <p>Thank you for using {{ config("app.name") }} for your consultation with {{$astrologer->display_name}}. We hope you found the session helpful and informative.</p>
    <p>We would greatly appreciate your feedback on your experience. Your insights help us improve our services. You can share your feedback by clicking on the link below:</p>
    {{--<a href="{{route('astrologers.publicview', [$astrologer->type, $astrologer->slug])}}#submit_review_form">Click to review</a>--}}
    <p>If you have any further questions or require additional, you can book another consultation through our platform at {{route('home')}}.</p>
  </div>
@endsection
