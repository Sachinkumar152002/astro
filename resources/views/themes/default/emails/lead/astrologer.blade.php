@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$astrologer->display_name}},</p>
    <p>A user you recently consulted with, {{$user->name}}, is interested in hiring you for detailed case handling or having a more in-depth discussion.</p>
    <p>To view this lead and potentially connect with the user, you will need to purchase their contact information.</p>
    <p>Click here to view the lead and purchase contact details:</p>
    <a href="{{route('astrologer.lead')}}">View</a>
    <p>Once you purchase the lead, you will be able to access the user's contact information and reach out to them directly.</p>
    <p>This is a great opportunity to further assist a client you've already connected with.</p>
  </div>
@endsection
