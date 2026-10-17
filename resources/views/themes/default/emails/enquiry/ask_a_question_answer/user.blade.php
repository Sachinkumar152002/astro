@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$enquiry->name}},</p>
    <p>A lawyer has provided an answer to your question, "{{$enquiry->question}}" on Advolive.</p>
    <a href="{{route('askaquestion.question.detail', [$enquiry->category->slug, $enquiry->question_slug])}}">Click to see Answer</a>
    <p>We encourage you to review the answer and engage with the lawyer if you have any follow-up questions or need further clarification.</p>
  </div>
@endsection
