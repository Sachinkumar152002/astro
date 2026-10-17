@if($results->count())
@foreach($results as $index => $result)
<tr>
  <th scope="row">{{ $index + 1 + (($results->currentPage() - 1) * $results->perPage()) }}</th>
  <td>{{ $result->influencer_name ?? 'N/A' }}</td>
  <td>{{ $result->product_name ?? 'N/A' }}</td>
  <td>
    @if($result->purchase_date)
      {{ \Carbon\Carbon::parse($result->purchase_date)->format('Y-m-d H:i') }}
    @else
      N/A
    @endif
  </td>
  <td>{{ $result->order_number ?? 'N/A' }}</td>
  <td>₹{{ number_format($result->commission_amount ?? 0, 2) }}</td>
  <td>
    @if($result->credited_at)
      {{ \Carbon\Carbon::parse($result->credited_at)->format('Y-m-d H:i') }}
    @else
      N/A
    @endif
  </td>
  <td class="text-right">
    @if($result->id && adminCan('order', 'view'))
      <a href="{{ route('admin.earning-reports.view-detail', ['commission_' . $result->id]) }}" class="btn btn-sm btn-info" title="View Details">
        <i class="bi bi-eye"></i>
      </a>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="8"><p>No Records</p></td>
</tr>
@endif
