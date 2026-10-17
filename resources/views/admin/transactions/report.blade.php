<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transaction Reports</title>
  <style>
    * {
      font-family: DejaVu Sans, Arial, sans-serif;
    }
    body {
      margin: 0;
      padding: 20px;
      background: #fff;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 2px solid #000;
      padding-bottom: 15px;
    }
    .header h1 {
      margin: 0;
      font-size: 24px;
      font-weight: bold;
    }
    .header p {
      margin: 5px 0 0 0;
      font-size: 12px;
      color: #666;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    thead {
      background-color: #f0f0f0;
    }
    th {
      padding: 12px 8px;
      text-align: left;
      border: 1px solid #ddd;
      font-weight: bold;
      font-size: 11px;
      background-color: #333;
      color: #fff;
    }
    td {
      padding: 10px 8px;
      border: 1px solid #ddd;
      font-size: 10px;
    }
    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tbody tr:hover {
      background-color: #f5f5f5;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .footer {
      margin-top: 30px;
      padding-top: 15px;
      border-top: 1px solid #ddd;
      text-align: center;
      font-size: 10px;
      color: #666;
    }
    .amount {
      font-weight: bold;
      color: #000;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>Transaction Reports</h1>
    <p>Generated on: {{ date('d M Y, h:i A') }}</p>
    <p>Total Transactions: {{ count($transactions) }}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 5%;">#</th>
        <th style="width: 30%;">Name</th>
        <th style="width: 20%;" class="text-right">Amount (₹)</th>
        <th style="width: 30%;">Transaction Type</th>
        <th style="width: 15%;">Date</th>
      </tr>
    </thead>
    <tbody>
      @if(count($transactions) > 0)
        @foreach($transactions as $index => $transaction)
          <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $transaction['name'] }}</td>
            <td class="text-right amount">₹{{ number_format($transaction['amount'], 2) }}</td>
            <td>{{ $transaction['transaction_type'] }}</td>
            <td>{{ date('d M Y, h:i A', strtotime($transaction['date'])) }}</td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="5" class="text-center" style="padding: 20px;">No transactions found</td>
        </tr>
      @endif
    </tbody>
  </table>

  <div class="footer">
    <p>This is a computer-generated report. No signature required.</p>
  </div>
</body>
</html>
