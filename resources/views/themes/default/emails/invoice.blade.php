<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name')}} : Invoice #{{$invoice->invoice_id}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f6f6f6;
        }

        .info {
            margin-left: 0px;
            padding-bottom: 0px;
            text-align: center;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
        }

        .flex-container1 {
            display: flex;
            justify-content: space-between;
            line-height: 0px;
        }

        .total {
            text-align: right;
            line-height: 2;

        }

        ol.list li {
            padding: 7px 7px;
        }

        p {
            text-align: center;
            line-height: 8px;
        }



        .price {
            padding-left: 25px;
            text-align: right;
        }
    </style>
</head>

<body>
<table style="max-width:800px; min-width:400px; margin:auto; background:#fff;">
    <tr>
        <td>
            <table style="width:100%">
                <tr>
                    <td style="text-align: center; border-bottom:1px solid #f1f1f1;"><img src="{{logo()}}" alt="{{config('app.name')}}" style=" width: 150px;"></td>
                </tr>
                <tr>
                    <td style="border-bottom:1px solid #f1f1f1;">
                        <p style="font-size: 13px;">
                          <strong>Website:</strong>{{url('/')}},
                          <strong>Email:</strong>{{optionByKey('contact_email')}},
                          <strong>Phone:</strong>{{optionByKey('contact')}}
                        </p>
                        <p style="font-size: 13px;"><strong>GSTNo.</strong>:06BNCPS7469F1ZM, <strong>Address:</strong>{{optionByKey('address')}}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #f1f1f1;">
            <table  style="width:100%">
                <tr>
                    <td>
                        <div style="font-size: 13px;"><strong>Invoice Number</strong>-{{$invoice->invoice_id}}<br><strong>Invoice Date</strong>-{{date('M d, Y', strtotime($invoice->created_at))}}
                        </div>
                    </td>
                    <td> <div style="line-height: 25px; font-size: 13px;"><strong>{{$invoice->customer_name}}</strong><br>
                        {{$invoice->address}},{{$invoice->city}}
                        {{$invoice->state}} {{$invoice->pincode}} {{$invoice->country}}<br>
                    </div></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    <td>
    <table  style="width:100%">
    <tr style="border-bottom:1px solid #f1f1f1;">
        <th>Item</th>
        <th>Description</th>
        <th>Price</th>
    </tr>
    <tr style="border-bottom:1px solid #f1f1f1;">
        <td>{{$invoice->item_name}}</td>
        <td>{!! $invoice->item_description !!}</td>
        <td style="text-align:right;">Rs.{{$invoice->amount}}</td>
    </tr>
    </table>
    </td>
    </tr>
    <tr>
        <td>
    <table  style="width:100%">
        <tr style="border-bottom:1px solid #f1f1f1;">
            <th style="text-align: right;">Subtotal</th>
            <td style="text-align: right; width:150px;">Rs.{{$invoice->amount}}</td>
        </tr>
        <tr style="border-bottom:1px solid #f1f1f1;">
            <th style="text-align: right;"> @if($invoice->discount)<strong>Discount : </strong><br>@endif</th>
            <td style="text-align: right; width:150px;">@if($invoice->discount) Rs.{{$invoice->discount}}<br>@endif</td>
        </tr>
        <tr style="border-bottom:1px solid #f1f1f1;">
            <th style="text-align: right;">Taxable Amount :</th>
            <td style="text-align: right; width:150px;">Rs.{{$invoice->amount-$invoice->discount}}</td>
        </tr>
        <tr style="border-bottom:1px solid #f1f1f1;">
            <th style="text-align: right;">  @if($invoice->tax_type=='cgst-sgst')
                <strong>SGST {{$invoice->cgst}}% :</strong><br>
                <strong>CGST {{$invoice->sgst}}% :</strong><br>
                @endif</th>
            <td style="text-align: right; width:150px;">@if($invoice->tax_type=='cgst-sgst')
                Rs.{{$invoice->cgst_amount}}<br>
                Rs.{{$invoice->sgst_amount}}<br>
                @endif</td>
        </tr>
        <tr style="border-bottom:1px solid #f1f1f1;">
            <th style="text-align: right;"> @if($invoice->tax_type=='igst')
                <strong>IGST {{$invoice->igst}}% :</strong><br>
                @endif</th>
            <td style="text-align: right; width:150px;">@if($invoice->tax_type=='igst')
                Rs.{{$invoice->igst_amount}}<br>
                @endif</td>
        </tr>
        <tr style="border-bottom:1px solid #f1f1f1;">
            <th style="text-align: right;">
                Total Payable Amount :
            </th>
            <td></td>
        </tr>
    </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3 style="font-size: 14px;"><u>Terms and Condition:</u></h3>
            <ol class="list" type="1" style="font-size: 13px;">
                <li>All payment are in advance and it will be non-refundable.</li>
                <li>This is an automated invoice generated by Planet Vedic , Hence doesn't require any signature and stamp.
                </li>
                <li>By using our Services, you are agreeing to these terms <a href="{{route('pages', 'terms-and-conditions')}}">{{route('pages', 'terms-and-conditions')}}</a>.
                    Please read them carefully.</li>
                <li>Subject to Noida Jurisdiction Only.</li>
            </ol>
        </td>
    </tr>
</table>
 </div>
 @if(isset($print) && $print)
 <script>window.print();</script>
 @endif
</body>
</html>
