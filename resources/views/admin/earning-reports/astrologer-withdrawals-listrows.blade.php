@if($results->count())
@foreach($results as $index => $result)
<tr>
  <th scope="row">{{ $index + 1 + (($results->currentPage() - 1) * $results->perPage()) }}</th>
  <td>{{ $result->created_at ? \Carbon\Carbon::parse($result->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
  <td>
    {{ $result->astrologer_name ?: ($result->firstname . ' ' . $result->lastname) ?: 'N/A' }}
  </td>
  <td>₹{{ number_format($result->available_balance ?? 0, 2) }}</td>
  <td>₹{{ number_format($result->request_amount ?? 0, 2) }}</td>
  <td>₹{{ number_format($result->wallet_balance ?? 0, 2) }}</td>
  <td>
    @if($result->status == 'done')
      <span class="badge badge-success">Done</span>
    @elseif($result->status == 'cancel')
      <span class="badge badge-danger">Cancel</span>
    @else
      <span class="badge badge-warning">Pending</span>
    @endif
  </td>
  <td class="text-right">
    @if(isset($result->withdraw_id))
      <a href="{{ route('admin.earning-reports.view-detail', ['withdraw_' . $result->withdraw_id]) }}" class="btn btn-sm btn-info" title="View Details">
        <i class="bi bi-eye"></i>
      </a>
    @else
      <span class="btn btn-sm btn-info disabled" title="No Details">
        <i class="bi bi-eye"></i>
      </span>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="8"><p>No Records</p></td>
</tr>
@endif
