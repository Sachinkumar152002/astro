<!DOCTYPE html>
<html>
<head>
    <title>Puja Earnings Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            background-color: #e7f3ff;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Puja Earnings Report</h2>
        <p>Generated on: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Astrologer Name:</strong> {{ $astrologer->display_name }}</p>
        <p><strong>Email:</strong> {{ $astrologer->email }}</p>
        <p><strong>Phone:</strong> {{ $astrologer->phone }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Date</th>
                <th>Puja Name</th>
                <th>Puja Type</th>
                <th>Order Number</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if($pujaEarnings && count($pujaEarnings) > 0)
                @foreach($pujaEarnings as $index => $earning)
                    @php
                        $assignment = $earning->pujaAssignment;
                        $product = $assignment ? $assignment->product : null;
                        $orderItem = $assignment ? $assignment->orderItem : null;
                        $order = $orderItem ? $orderItem->order : null;
                        $isOneToOne = $product && $product->is_one_to_one_puja == 1;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $earning->created_at ? date('d-m-Y', strtotime($earning->created_at)) : '-' }}</td>
                        <td>{{ $product ? $product->title : 'N/A' }}</td>
                        <td>
                            @if($isOneToOne)
                                <span class="badge badge-info">One-to-One Puja</span>
                            @else
                                <span class="badge badge-success">Group Puja</span>
                            @endif
                        </td>
                        <td>{{ $order ? $order->order_number : 'N/A' }}</td>
                        <td>{{ number_format($earning->amount, 2) }}</td>
                        <td>
                            @if($earning->status == 1)
                                Success
                            @else
                                Failed
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No puja earnings found</td>
                </tr>
            @endif
        </tbody>
        @if($pujaEarnings && count($pujaEarnings) > 0)
            <tfoot>
                <tr class="total">
                    <td colspan="5" style="text-align: right;"><strong>Total Earnings:</strong></td>
                    <td colspan="2"><strong> {{ number_format($totalEarnings, 2) }}</strong></td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
