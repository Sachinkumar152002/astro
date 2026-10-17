@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>Hi {{$user->name}},</p>
    <p>Your payment has been received, and you’ve successfully hired a lawyer for your case.</p>
    <p>Here are the details:</p>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Lawyer Name :</th>
            <td>{{substr($lawyer->firstname, 0, 1)}}. {{$lawyer->lastname}}{{$lawyer->unique_id?'-'.$lawyer->unique_id:''}}</td>
        </tr>
        <tr>
            <th>Contact Number :</th>
            <td>{{$lawyer->phone}}</td>
        </tr>
        <tr>
            <th>Email :</th>
            <td>{{$lawyer->email}}</td>
        </tr>
        <tr>
            <th>Case Title :</th>
            <td>{{$data->hire_a_lawyer_enquiry->case_title}}</td>
        </tr>
        <tr>
            <th>Total Paid :</th>
            <td>₹ {{$data->total_amount}}</td>
        </tr>
        <tr>
            <th>Payment Date :</th>
            <td>{{date('d-m-Y h:i a', strtotime($data->payment_date_time))}}</td>
        </tr>
        @if($data->payment_id)
          <tr>
              <th>Transaction ID :</th>
              <td>{{$data->payment_id}}</td>
          </tr>
        @endif
    </table>
    <p> Your lawyer will be in touch shortly, or you can reach out to them directly.</p>
    <p>Thank you for trusting Advolive for your legal needs.</p>
  </div>
@endsection
