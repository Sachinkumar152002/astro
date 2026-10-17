@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$user->name}},</p>
    <p>Good news! A lawyer has submitted a quote for your case:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Lawyer :</th>
            <td>{{substr($lawyer->firstname, 0, 1)}}. {{$lawyer->lastname}}{{$lawyer->unique_id?'-'.$lawyer->unique_id:''}}</td>
        </tr>
        <tr>
            <th>Quote Amount :</th>
            <td>₹ {{$enquiry->amount}}</td>
        </tr>
        <tr>
            <th>Payment Type :</th>
            <td>{{$enquiry->type=='full'?'Full':'Per Hearing'}}</td>
        </tr>
        <tr>
            <th>Submitted On :</th>
            <td>{{date('d-m-Y h:i a', strtotime($enquiry->created_at))}}</td>
        </tr>
    </table>
    <a href="{{route('user.hirealawyerenquiries')}}">Click here to review and compare all quotes you’ve received.</a>
    <p>Choose the best fit and proceed to hire directly from your dashboard.</p>
    <p>Need help? We’re here to support you.</p>
  </div>
@endsection
