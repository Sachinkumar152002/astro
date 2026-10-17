<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

        }

        .invoice-container {
            width:600px;
            /* A4 width */
            min-height: 297mm;
            /* A4 height */
            margin: 20px auto;
            background: #ffffff;
            padding:0px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size:12px;
        }

        th,
        td {

            padding: 5px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size:10px;
            color: #555;
        }

        @media print {
            body {
                background: none;
                margin: 0;
            }


        }
    </style>
</head>

<body>
@php
  $invoiceLogoSrc = invoice_logo_data_uri_for_pdf(195, 100);
@endphp
        <table style="border:1px solid #000;">
    <tr>
        <td colspan="10" style="padding-bottom:0px;">
            <table style="width: 100%; margin-bottom:0px;">
                <tr>
                    <td colspan="2" style="text-align: center;">
                        @if($invoiceLogoSrc)
                        <img src="{{ $invoiceLogoSrc }}" alt="Logo" style="height:70px;">
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom: 2px solid #000;">
                    <td style="padding-bottom: 0;">
                        <table style="width:100%;">
                            <tr><td><b>Invoice Number:</b> {{$invoice->prefix}}{{$invoice->number}}</td></tr>
                            @if($invoice->relation)
                            <tr><td><b>Order Number:</b> {{$invoice->relation->order_number?:$invoice->relation->my_txn_id}}</td></tr>
                            @endif
                            @if(strtolower($invoice->state) == 'haryana')
                            <tr><td><b>Nature of Transaction:</b> Intra-State</td></tr>
                            @else
                            <tr><td><b>Nature of Transaction:</b> Inter-State</td></tr>
                            @endif
                            <tr><td style="padding-bottom: 0;"><b>Place of Supply:</b> {{$invoice->state}}</td></tr>
                        </table>
                    </td>
                    <td style="padding-bottom: 0;">
                        <table style="width:100%;">
                            <tr><td><b>State/UT Code:</b> 06</td></tr>
                            <tr><td><b>Invoice Date:</b> {{date('d F Y', strtotime($invoice->created_at))}}</td></tr>
                            @if($invoice->relation)
                            <tr><td><b>Order Date:</b> {{date('d F Y', strtotime($invoice->relation->created_at))}}</td></tr>
                            @endif
                            @if($invoice->relation_type=='order' && $invoice->relation && $invoice->relation->item_type==0)
                            <tr><td style="padding-bottom: 0;"><b>Nature of Supply:</b> Goods</td></tr>
                            @else
                            <tr><td style="padding-bottom: 0;"><b>Nature of Supply:</b> Support Services</td></tr>
                            @endif
                        </table>
                    </td>
                </tr>
                <tr style="padding-bottom:0px;">
                    <td style="padding-top:0; padding-bottom:0px;">
                        <table style="width:100%; margin-bottom:0px;">
                            <tr><th colspan="2" style="text-align: left;">Bill To:</th></tr>
                            <tr>
                                <td colspan="2">
                                  <b>
                                  {{$invoice->billing_full_name }}
                                  </b>
                                  <br>
                                  {{$invoice->billing_address}}
                                  <br>
                                  {{$invoice->billing_city}} - {{$invoice->billing_zipcode}} {{$invoice->billing_state}}, {{$invoice->billing_country}}
                                  <br>
                                  {{$invoice->billing_phone_number}}
                                </td>
                            </tr>
                            {{--<tr><th colspan="2" style="text-align: left;">Bill From:</th></tr>
                            <tr>
                                <td colspan="2">
                                    <b>ASTROVYRA</b>
                                    <br>
                                    <span>Flat no. 8, D Block, Sri Vijay Nagar</span>
                                    <br>
                                    <span>Sri Ganganagar - 335704, Rajasthan, India</span>
                                </td>
                            </tr>--}}
                            <tr>
                              <td style="text-align: left;"><span style="font-weight:bold">GSTIN Number:</span><span>{{ $invoice->gst ?: "08ABLPB8707H3ZJ" }}</span></td>
                            </tr>
                            <tr>
                              <td style="text-align: left;"><span style="font-weight:bold">PAN:</span> AABHY5236P</td>
                            </tr>
                        </table>
                    </td>
                      <td style="padding-top:0 ;padding-bottom:0px;">
                        @if($invoice->relation_type=='order' && $invoice->relation && $invoice->relation->item_type==0)
                        <table style="width:100%; margin-top:16px;">
                            <tr><th colspan="2" style="text-align: left;">Ship To:</th></tr>
                            <tr>
                                <td colspan="2"><b>{{$invoice->name}}</b>
                                  <br>
                                  {{$invoice->address}}
                                  <br>
                                  {{$invoice->city}} - {{$invoice->pincode}} {{$invoice->state}}, {{$invoice->country}}
                                  <br>
                                  {{$invoice->phone}}
                                </td>
                            </tr>
                            <tr><th style="text-align: left;">Ship From:</th></tr>
                            <tr>
                              <td colspan="4">
                                  <b>ASTROVYRA</b>
                                  <br>
                                  <span>Flat no. 8, D Block, Sri Vijay Nagar</span>
                                  <br>
                                  <span>Sri Ganganagar - 335704, Rajasthan, India</span>
                              </td>
                            </tr>
                        </table>
                        @endif
                      </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th style="border: 2px solid #000; font-size:11px; text-align:left;">Sr. No</th>
        <th style="border: 2px solid #000; font-size:11px; text-align:left;">Description</th>
        <th style="border: 2px solid #000; font-size:11px; text-align:left;">Unit</th>
        <th style="border: 2px solid #000; font-size:11px;min-width: 70px;text-align:left;">Price</th>
        <th style=" border: 2px solid #000; font-size:11px; text-align:left;">Discount</th>
        <th style=" border: 2px solid #000; font-size:11px; text-align:left;">Net Amount</th>
        <th style=" border: 2px solid #000; font-size:11px; text-align:left;">Tax Rate</th>
        <th style=" border: 2px solid #000; font-size:11px; text-align:left;">Tax Type</th>
        <th style=" border: 2px solid #000; font-size:11px; text-align:left;">Tax Amount</th>
        <th style="border: 2px solid #000; font-size:11px; text-align:left;">Total Amount</th>
    </tr>
    @php
      $tax_type = 'sgst_cgst';
      $totalInvoiceItems = $invoice_items->count();
    @endphp
    @foreach($invoice_items as $key => $item)
    <tr>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right;">1</td>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right">
          <span>{{$item->item_name}}</span>
          @if($item->hsn_code)
          <br>
          <span>HSN: {{$item->hsn_code}}</span>
          @endif
        </td>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right">{{$item->quantity}}</td>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{sprintf('%.2f', $item->base_amount)}}</td>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{sprintf('%.2f', $item->discount_amount+$item->coupon_amount)}}</td>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{sprintf('%.2f', $item->net_total)}}</td>

        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right">
          @if($item->tax_type == 'igst')
          @php
            $tax_type = 'igst';
          @endphp
          <span>{{$item->igst_percentage}}%</span>
          @else
          <span>{{$item->cgst_percentage}}%</span>
          <br>
          <span>{{$item->sgst_percentage}}%</span>
          @endif
        </td>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right">
          @if($item->tax_type == 'igst')
          <span>IGST</span>
          @else
          <span>CGST</span>
          <br>
          <span>SGST</span>
          @endif
        </td>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right;">
          @if($item->tax_type == 'igst')
          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <span>{{sprintf('%.2f', $item->igst_amount)}}</span>
          @else
          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <span>{{sprintf('%.2f', $item->cgst_amount)}}</span>
          <br>
          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <span>{{sprintf('%.2f', $item->sgst_amount)}}</span>
          @endif
        </td>
        <td style="border:1px solid #000; {{ $key == ($totalInvoiceItems-1) ? '' : 'border-bottom:0px;' }} text-align:right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{sprintf('%.2f', ($item->total_amount))}}</td>
    </tr>
    @endforeach
    @if($invoice->shipping_amount>0)
      <tr>
          <td style="border: 1px solid #000; border-top:0px;border-bottom:2px solid #000;text-align:right"></td>
          <td style="border: 1px solid #000; border-top:0px;border-bottom:2px solid #000;text-align:left">Shipping Charges</td>
          <td style="border: 1px solid #000;border-top:0px;border-bottom:2px solid #000;text-align:right"></td>
          <td style="border: 1px solid #000;border-top:0px;border-bottom:2px solid #000;text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$invoice->shipping_amount}}</td>
          <td style="border: 1px solid #000;border-top:0px;border-bottom:2px solid #000;text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 0.00</td>
          <td style="border: 1px solid #000;border-top:0px;border-bottom:2px solid #000;text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$invoice->shipping_amount}}</td>
          <td style="border: 1px solid #000;border-top:0px;border-bottom:2px solid #000;text-align:right">0.00%</td>
          <td style="border: 1px solid #000; border-top:0px;border-bottom:2px solid #000;text-align:right">
            @if($tax_type == 'igst')
            <span>IGST</span>
            @else
            <span>CGST</span>
            <br>
            <span>SGST</span>
            @endif
          </td>
          <td style="border: 1px solid #000; border-top:0px;border-bottom:2px solid #000;text-align:right">
            @if($tax_type == 'igst')
            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <span>0.00</span>
            @else
            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <span>0.00</span>
            <br>
            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <span>0.00</span>
            @endif
          </td>
          <td style="border: 1px solid #000; border-top:0px;border-bottom:2px solid #000;text-align:right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{sprintf('%.2f', $invoice->shipping_amount)}}</td>
      </tr>
    @endif
    <tr style="border-bottom:2px solid #000;">
      <td colspan="9"><b>Total Amount</b></td>
      <td style="border:1px solid #000;text-align:right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><b> {{sprintf('%.2f', $invoice->total_amount)}}</b></td>
    </tr>
    @php
    $formatter = new NumberFormatter('en_US', NumberFormatter::SPELLOUT);
    $number = $invoice->total_amount;
    $wholePart = floor($number);
    $decimalPart = round(($number - $wholePart) * 100);
    $wholeWords = $formatter->format($wholePart);
    $decimalWords = $formatter->format($decimalPart);
    $amount_in_words = '';
    if(str_contains('zero', $decimalWords)){
      $amount_in_words = ucwords($wholeWords) . " Rupees Only";
    }else{
      $amount_in_words = ucwords($wholeWords) . " Rupees " . ucwords($decimalWords)." Paisa Only";
    }
    @endphp
    <tr style="border-bottom:2px solid #000;">
      <td colspan="10"><b>Amount in Words:</b><br>{{$amount_in_words}}</td>
    </tr>
        <td colspan="10"><b> For {{ config('app.name') }}</b></td>
    </tr>
    <tr>
        <td colspan="10" style="">
            <img src="{{directorSign(100, 100)}}" alt="Authorized Signatory" style="width:100px;"><br>
          Authorized Signatory
        </td>
    </tr>
    <tr>
      <td colspan="3" style="border: 1px solid #000;font-size:10px;"><b>Payment Transaction ID</b></td>
      <td colspan="3" style="border: 1px solid #000;font-size:10px;"><b>Date & Time</b></td>
      <td colspan="2" style="border: 1px solid #000;font-size:10px;"><b>Invoice Value</b></td>
      <td colspan="2" style="border: 1px solid #000;font-size:10px;"><b>Mode of Payment</b></td>
    </tr>
    <tr>
      <td colspan="3" style="border: 1px solid #000;"> {{$invoice->payment_id}} </td>
      <td colspan="3" style="border: 1px solid #000;">{{$invoice->payment_date_time}} hrs</td>
      <td colspan="2" style="border: 1px solid #000;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{sprintf('%.2f', $invoice->total_amount)}}</td>
      <td colspan="2" style="border: 1px solid #000;"> {{$invoice->payment_method?:"Online"}}</td>
    </tr>
    <tr style="border-bottom: 2px solid #000;">
      <td colspan="10">
        <b>DECLARATION</b><br>
        @if($invoice->relation_type=='order' && $invoice->relation && $invoice->relation->item_type==0)
          The goods sold as part of this shipment are intended for end-user consumption and are not for retail sale.
        @else
          The support services provided are exclusively for the end-user’s internal use and shall not be transferred, sublicensed, or offered as part of any third-party service.
        @endif
      </td>
    </tr>
    <tr>
        <td colspan="10">
            If you have any questions, feel free to call customer care at <a href="tel:{{ config('vrdcms.support_number') }}">{{ config('vrdcms.support_number') }}</a> or email us at <a href="mailto:{{ config('vrdcms.support_email') }}">{{ config('vrdcms.support_email') }}</a>
        </td>
    </tr>
</table>
  <div class="footer">
      &copy; 2025 {{ config('app.name') }}. All Rights Reserved.
  </div>
</body>

</html>
