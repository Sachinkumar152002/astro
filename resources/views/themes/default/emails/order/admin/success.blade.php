@extends(themeView().'emails.layout')
@section('content')
  <div class="">
      <p>Dear Admin,</p>
      <p>
        I hope this message finds you well. This email serves as a notification regarding new orders placed on both our app and web portal of {{ config('app.name') }}.
      </p>
      <p>Please review the following details:</p>
      <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
          <tr>
              <th>Order ID</th>
              <td>{{$order->order_number}}</td>
          </tr>
          <tr>
              <th>Customer Name</th>
              <td>{{$order->full_name}}</td>
          </tr>
          <tr>
              <th>Customer Email</th>
              <td>{{$order->email}}</td>
          </tr>
          <tr>
              <th>Customer Mobile</th>
              <td>{{$order->phone_number}}</td>
          </tr>
          <tr>
              <th>Customer Address</th>
              <td>{{$order->address}}</td>
          </tr>
          <tr>
              <th>Customer City</th>
              <td>{{$order->city}}</td>
          </tr>
          <tr>
              <th>Customer State</th>
              <td>{{$order->state}}</td>
          </tr>
          <tr>
              <th>Customer Country</th>
              <td>{{$order->country}}</td>
          </tr>
          <tr>
              <th>Customer Pin Code</th>
              <td>{{$order->zipcode}}</td>
          </tr>
          <tr>
              <th>Customer Payment Status</th>
              <td>{{$order->payment_status}}</td>
          </tr>
          <tr>
              <th>Order Date</th>
              <td>{{date('d/m/Y', strtotime($order->created_at))}}</td>
          </tr>
          <tr>
              <th>Total Amount</th>
              <td>{{$order->total_price}}</td>
          </tr>
          <tr>
              <th>Order Status</th>
              <td>{{$order->order_status}}</td>
          </tr>
      </table>
      <p>
        Ensure that the orders are processed in a timely manner and any updates are communicated to the customers. If you have any questions or need further assistance, please do not hesitate to reach out.
      </p>
      <p>
        Thank you for your prompt attention to this matter.
      </p>
  </div>
@endsection
