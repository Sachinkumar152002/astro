@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$lawyer->display_name}},</p>
    <p>Thank you for purchasing the lead for {{$user->name}}.</p>
    <a href="{{route('astrologer.lead')}}">Purchase Leads</a>
    <p>We encourage you to reach out to {{$user->name}} at your earliest convenience to discuss their case further.</p>
    <p>We wish you success in your engagement with this client.</p>
  </div>
@endsection
