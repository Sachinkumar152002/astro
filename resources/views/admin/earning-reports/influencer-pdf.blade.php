<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Influencer Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .filters {
            margin-bottom: 20px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
            background-color: #e7f3ff;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Influencer Report</h1>
        <p>Generated on: {{ date('d M Y H:i:s') }}</p>
    </div>

    @if(isset($filters) && (isset($filters['influencer_name']) || isset($filters['product_name']) || isset($filters['date_from']) || isset($filters['date_to'])))
    <div class="filters">
        <strong>Filters Applied:</strong><br>
        @if(isset($filters['influencer_name']) && $filters['influencer_name'])
            Influencer Name: {{ $filters['influencer_name'] }}<br>
        @endif
        @if(isset($filters['product_name']) && $filters['product_name'])
            Product Name: {{ $filters['product_name'] }}<br>
        @endif
        @if(isset($filters['date_from']) && $filters['date_from'])
            From Date: {{ $filters['date_from'] }}<br>
        @endif
        @if(isset($filters['date_to']) && $filters['date_to'])
            To Date: {{ $filters['date_to'] }}<br>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Influencer Name</th>
                <th>Product Name</th>
                <th>Purchase Date</th>
                <th>Order Number</th>
                <th>Commission Amount</th>
                <th>Credited Date</th>
            </tr>
        </thead>
        <tbody>
            @if($results && count($results) > 0)
                @php
                    $totalCommission = 0;
                @endphp
                @foreach($results as $index => $result)
                    @php
                        $totalCommission += $result->commission_amount ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $result->influencer_name ?? 'N/A' }}</td>
                        <td>{{ $result->product_name ?? 'N/A' }}</td>
                        <td>
                            @if($result->purchase_date)
                                {{ \Carbon\Carbon::parse($result->purchase_date)->format('d M Y H:i') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $result->order_number ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($result->commission_amount ?? 0, 2) }}</td>
                        <td>
                            @if($result->credited_at)
                                {{ \Carbon\Carbon::parse($result->credited_at)->format('d M Y H:i') }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5" class="text-right"><strong>Total Commission:</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalCommission, 2) }}</strong></td>
                    <td></td>
                </tr>
            @else
                <tr>
                    <td colspan="7" class="text-center">No Records Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
