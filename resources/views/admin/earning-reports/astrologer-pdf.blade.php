<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Astrologer Report</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      margin: 15px;
    }
    h1, h2 {
      margin: 0;
      padding: 0;
    }
    .header {
      text-align: center;
      margin-bottom: 15px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: left;
    }
    th {
      background-color: #f0f0f0;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="header">
    <h2>Astrologer Report</h2>
    <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th>Sr.No</th>
        <th>Astrologer Name</th>
        <th>Service Type</th>
        <th>Amount</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse($results as $index => $result)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $result->astrologer_name ?: ($result->firstname . ' ' . $result->lastname) ?: 'N/A' }}</td>
          <td>{{ $result->service_type ?? 'Other' }}</td>
          <td>₹{{ number_format($result->amount, 2) }}</td>
          <td>{{ $result->created_at ? \Carbon\Carbon::parse($result->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align: center;">No data available.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
