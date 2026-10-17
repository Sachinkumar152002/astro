<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Cancel Request Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #333;
        }
        p {
            margin: 10px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Product Cancel Request Approved</h2>
        <p>Dear {{$order->billing_full_name}},</p>
        <p>I hope this message finds you well. I am writing to confirm that your product cancel request has been approved by our admin team at the Web & App portal of {{ config("app.name") }}.</p>
        <p>{{$orderRequest->message}}</p>
        <p><strong>Cancel Details:</strong></p>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
            @if($order->invoice)
            <tr>
                <th>Invoice Number</th>
                  <td>{{sprintf('%s', '%s', $order->invoice->prefix, $order->invoice->number)}}</td>
            </tr>
            @endif
            <tr>
                <th>Order Number</th>
                <td>{{$order->order_number}}</td>
            </tr>
            <tr>
                <th>Order Date</th>
                <td>{{date('d-m-Y', strtotime($order->updated_at))}}</td>
            </tr>
            <tr>
                <th>Products</th>
                @foreach($order->orderItems as $key => $item)
                  <td>{{$item->name}}</td>
                @endforeach
            </tr>
            {{--
            <tr>
                <th>Total Amount</th>
                <td>₹ {{$order->total_price}}</td>
            </tr>
            --}}
        </table>
        <p>We appreciate your understanding and cooperation. If you have any questions or need further assistance, please feel free to contact us.</p>
        <p>Best Regards,</p>
        <p><strong>{{ config("app.name") }} Team</strong></p>
        <p>+91 8800-850-853, 9873-850-800</p>
        <p><a href="{{route('home')}}">{{route('home')}}</a></p>
        <p>Email: <a href="mailto:{{ config('vrdcms.custome_care_email') }}">{{ config("vrdcms.custome_care_email") }}</a></p>
        <p class="footer">Please do not reply to this email. For any queries, use our official contact channels.</p>
    </div>
</body>
</html>
