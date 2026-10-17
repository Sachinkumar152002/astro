@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Dear {{$lawyer->display_name}},</p>
    <p>A new legal question has been posted on {{ config("app.name") }} in your area of expertise ({{$enquiry->case_category}}).</p>
    <p>Here are the details:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Question Title:</th>
            <td>{{$enquiry->question}}</td>
        </tr>
        <tr>
            <th>Question Category:</th>
            <td>{{$enquiry->case_category}}</td>
        </tr>
    </table>
    <p>You can view the full question and provide an answer here
      <a href="{{route('astrologer.askaquestion')}}">Click to see Question</a>
    </p>
    <p>Providing answers to user questions is a great way to showcase your expertise and connect with potential clients.</p>
    <p>Thank you for contributing to the {{ config("app.name") }} community.</p>
  </div>
@endsection
