@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Dear Admin,</p>
    <p>A new question has been posted by user {{$enquiry->name}} and is awaiting your review.</p>
    <p>Please review the question to ensure it complies with our community guidelines and is assigned to the appropriate category.</p>
    <p>You can review the question here: <a href="{{route('admin.askaquestion.edit', [$enquiry->id])}}">Click to review</a></p>
    <p>Once you approve the question, it will be published and visible to lawyers.</p>
  </div>
@endsection
