<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Return Request</title>
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
        <h2>Product Return Request</h2>
        <p>Dear Admin,</p>
        <p>I hope this message finds you well. I am writing to request a return for a product I purchased through your web and app portal.</p>
        <p>{{$orderRequest->message}}</p>
        <p><strong>Purchase Details:</strong></p>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <th>Invoice Number</th>
                @if($order->invoice)
                  <td>{{sprintf('%s', '%s', $order->invoice->prefix, $order->invoice->number)}}</td>
                @endif
            </tr>
            <tr>
                <th>Products</th>
                @foreach($orderRequest->orderRequestItems as $key => $item)
                  @if($item->item)
                    <td>{{$item->item->name}}</td>
                  @endif
                @endforeach
            </tr>
            <tr>
                <th>Order Number</th>
                <td>{{$order->order_number}}</td>
            </tr>
            <tr>
                <th>Purchase Date</th>
                <td>{{date('d-m-Y', strtotime($order->updated_at))}}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td>₹ {{$order->total_price}}</td>
            </tr>
        </table>
        <p>I would appreciate your guidance on the return process and any necessary steps I need to follow. Thank you for your assistance in this matter.</p>
        <p>Best regards,</p>
        <p><strong>{{$order->user->billing_full_name}}</strong></p>
        <p><strong>Contact Information:</strong> {{$order->user->billing_phone_number}}</p>
        <p><strong>Email:</strong> {{$order->user->billing_email}}</p>
        <p><strong>Address:</strong> {$order->user->billing_address}}, {{$order->billing_city}}, {{$order->billing_state}}, {{$order->billing_country}} ({{$order->billing_zipcode}})</p>
        <p class="footer">This email is intended for the Admin regarding a product return request.</p>
    </div>
</body>
</html>
