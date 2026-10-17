@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$enquiry->name}},</p>
    <p>Good news! Your question, "{{$enquiry->question}}" has been approved and published on Advolive.</p>
    <p>Lawyers can now view your question and provide answers. You will be notified when a lawyer responds to your question.</p>
    <p>We hope you receive helpful advice from our legal experts.</p>
  </div>
@endsection
