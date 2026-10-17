<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Group Puja Bookings</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      margin: 20px;
    }
    h1, h2, h3 {
      margin: 0;
      padding: 0;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .meta {
      margin-bottom: 15px;
    }
    .meta p {
      margin: 2px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #000;
      padding: 5px 8px;
      font-size: 11px;
    }
    th {
      background-color: #f0f0f0;
      font-weight: bold;
      text-align: left;
    }
  </style>
</head>
<body>
  <div class="header">
    <h2>{{ $pujaName }}</h2>
  </div>

  <div class="meta">
    <p><strong>Puja Name:</strong> {{ $pujaName }}</p>
    <p><strong>Astrologer Name:</strong> {{ $astrologerName }}</p>
    <p><strong>Scheduled At:</strong> {{ $scheduledAt ?? 'N/A' }}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Family Reference</th>
        <th>Gotra Name</th>
        <th>Members</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $index => $row)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $row['reference'] }}</td>
          <td>{{ $row['gotra'] ?? 'N/A' }}</td>
          <td>
            @if(!empty($row['persons']) && is_array($row['persons']))
              @foreach($row['persons'] as $person)
                {{ $person['label'] ?? 'Name' }}: {{ $person['name'] ?? '' }}<br>
              @endforeach
            @else
              N/A
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4">No data available.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
