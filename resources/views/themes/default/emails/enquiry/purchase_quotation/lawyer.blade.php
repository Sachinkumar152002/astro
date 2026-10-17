@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$lawyer->display_name}},</p>
    <p>You’ve been selected by a client for a case on Advolive.</p>
    <p>Here are the details:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Client Name :</th>
            <td>{{$user->name}}</td>
        </tr>
        <tr>
            <th>Contact Number :</th>
            <td>{{$user->phone}}</td>
        </tr>
        <tr>
            <th>Email :</th>
            <td>{{$user->email}}</td>
        </tr>
        <tr>
            <th>Case Title :</th>
            <td>{{$enquiry->hire_a_lawyer_enquiry ? $enquiry->hire_a_lawyer_enquiry->case_title : ''}}</td>
        </tr>
        <tr>
            <th>Category :</th>
            <td>{{$enquiry->hire_a_lawyer_enquiry ? $enquiry->hire_a_lawyer_enquiry->case_category : ''}}</td>
        </tr>
        <tr>
            <th>Payment Received :</th>
            <td>₹ {{$enquiry->total_amount}}</td>
        </tr>
        <tr>
            <th>Date & Time :</th>
            <td>{{date('d-m-Y h:i a', strtotime($enquiry->payment_date_time))}}</td>
        </tr>
    </table>
    <p>You can now contact the client and begin working on the case.</p>
    <p>Let us know if you need any help from our support team.</p>
  </div>
@endsection
