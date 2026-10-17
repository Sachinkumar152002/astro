<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice {{ $invoice['invoice_no'] }}</title>
  <style>
    * {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 11px;
    }
    body {
      margin: 0;
      padding: 0;
      background: #fff;
    }
    .page-wrapper {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 20px 0;
    }
    .container {
      width: 400px; /* Narrow slip width (~30% of A4) */
      padding: 8px;
      box-sizing: border-box;
      border: 1px solid #000;
    }
    .box {
      border: 1px solid #000;
      margin-bottom: 8px;
      padding: 6px;
    }
    .title {
      font-weight: bold;
      border-bottom: 1px solid #000;
      margin-bottom: 4px;
      padding-bottom: 3px;
      text-transform: uppercase;
      font-size: 11.5px;
    }
    .logo {
      text-align: right;
    }
    .logo img {
      width: 50px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #000;
      padding: 4px;
      vertical-align: top;
      font-size: 10px;
    }
    .noborder td {
      border: none !important;
      padding: 2px 4px;
    }
    .footer {
      border: 1px solid #000;
      padding: 6px;
      font-size: 9px;
      margin-top: 8px;
    }
    .centered {
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="page-wrapper">
    <div class="container">

      <!-- Ship To -->
      <div class="box">
        <div class="title">Ship To</div>
        <table class="noborder">
          <tr>
            <td style="width: 70%;">
              <strong>{{ $invoice->name }}</strong>
              <br>
              {{$invoice->address}}
              <br>
              {{$invoice->city}} - {{$invoice->zipcode}} {{$invoice->state}}, {{$invoice->country}}
              <br>
              {{$invoice->phone_number}}
            </td>
            @php
              $slipLogoSrc = invoice_logo_data_uri_for_pdf(200, 200);
            @endphp
            <td class="logo">
              @if($slipLogoSrc)
              <img src="{{ $slipLogoSrc }}" alt="">
              @endif
            </td>
          </tr>
        </table>
      </div>

      <!-- Shipped By -->
      <div class="box">
        <div class="title">Shipped By (if undelivered, return to)</div>
        <table class="noborder">
          <tr>
            <td>
              <strong>{{ $invoice->ship_from_company_name }}</strong>
              <br>
              {{ $invoice->ship_from_company_address }}
              <br>
              GSTIN: {{ $invoice->gst }}
              <br>
              Phone: {{ fromSetting("contact") }}
            </td>
          </tr>
          <tr>
            <td>
              <strong>Order #:</strong> {{ $order->order_number }}<br>
              <strong>Invoice #:</strong> {{ $invoice->prefix }}{{ $invoice->number }}<br>
              <strong>Date:</strong> {{ date("d-m-Y", strtotime($invoice->created_at)) }}
            </td>
          </tr>
        </table>
      </div>

      <!-- Footer -->
      <div class="footer">
        All disputes are subject to Uttar Pradesh jurisdiction only.<br>
        Goods once sold will only be taken back/exchanged as per store's return policy.<br><br>
        <em>This is an auto-generated label and does not need signature.</em>
      </div>

    </div>
  </div>

</body>
</html>
