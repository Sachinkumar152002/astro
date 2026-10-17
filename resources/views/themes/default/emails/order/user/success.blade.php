@extends(themeView().'emails.layout')
@section('content')
  <div class="">
    <p>
      Hi {{$order->billing_full_name}},
    </p>
    <p>
      Thank you for your recent purchase from the {{config('app.name')}} Web & App Portal! We are thrilled to have you as part of our community.</p>
    <h3 style="color: #2980b9;">Order Details:</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <th>Order Number</th>
            <td>{{$order->order_number}}</td>
        </tr>
        <tr>
            <th>Order Status</th>
            <td>{{$order->order_status}}</td>
        </tr>
        <tr>
            <th>Purchase Date</th>
            <td>{{date('d/m/Y', strtotime($order->created_at))}}</td>
        </tr>
        <tr>
          <th colspan="2">Item(s) Purchased</th>
        </tr>
        @foreach($order->orderItems as $key => $value)
          <tr>
            <th>{{ $key+1 }}. Name</th>
            <td>{{ $value->name }}</td>
          </tr>
          @php
            $optionsArray = json_decode($value->options, true);
            // Check if this is a one-to-one puja
            $isOneToOnePuja = false;
            if($value->product && $value->product->type == 2 && $value->product->is_one_to_one_puja == 1) {
              $isOneToOnePuja = true;
            }
          @endphp
          @if(isset($optionsArray["start_at"]) && !$isOneToOnePuja)
            <tr>
              <th>Start at</th>
              <td>{{ date("D, d M Y h:i a", strtotime($optionsArray["start_at"])) }}</td>
            </tr>
          @endif
          @if(isset($optionsArray["place"]))
            <tr>
              <th>Place</th>
              <td>{{ $optionsArray["place"] }}</td>
            </tr>
          @endif
          @if(isset($optionsArray["video_call_url"]))
            <tr>
              <th>Video Call Url</th>
              <td><a href="{{ $optionsArray['video_call_url'] }}">Join</a></td>
            </tr>
          @endif
        @endforeach
        <tr>
            <th>Total Amount</th>
            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$order->total_price}}</td>
        </tr>
    </table>
    <p style="font-size: 16px; line-height: 1.6; color: #555;">
      Your order will be processed shortly, and you will receive a notification once it is on its way.
    </p>
    <p style="font-size: 16px; line-height: 1.6; color: #555;">
      If you have any questions regarding your purchase or need further assistance, please do not hesitate to contact us at.

    </p>
  <p style="font-size:14px;"> <a href="tel:{{config('vrdcms.support_number')}}" style="color: #2980b9;ont-size:14px;">{{config('vrdcms.support_number')}}</a></p>
    <p style="font-size:14px;"> <a href="tel:{{config('vrdcms.support_email')}}" style="color: #2980b9;font-size:14px;">{{config('vrdcms.support_email')}}</a></p>
    <p style="font-size: 16px; line-height: 1.6; color: #555;">
      We appreciate your support and look forward to serving you in the future.
    </p>
  </div>
@endsection
