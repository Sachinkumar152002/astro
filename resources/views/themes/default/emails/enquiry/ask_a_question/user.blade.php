@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$enquiry->name}},</p>
    <p>Thank you for posting your question on Advolive.</p>
    <p>Your question, "{{$enquiry->question}}" has been successfully submitted and is now pending review by our admin team. We will review your question to ensure it complies with our community guidelines.</p>
    <p>You will receive a notification once your question is published and visible to lawyers on the platform.</p>
  </div>
@endsection
