@if($results->count())
@foreach($results as $index => $result)
<tr>
  <th scope="row">{{ $index + 1 + (($results->currentPage() - 1) * $results->perPage()) }}</th>
  <td>
    {{ $result->astrologer_name ?: ($result->firstname . ' ' . $result->lastname) ?: 'N/A' }}
  </td>
  <td>{{ $result->service_type ?? 'Other' }}</td>
  <td>₹{{ number_format($result->amount, 2) }}</td>
  <td>{{ $result->created_at ? \Carbon\Carbon::parse($result->created_at)->format('Y-m-d H:i') : 'N/A' }}</td>
  <td class="text-right">
    @if($result->id)
      <a href="{{ route('admin.earning-reports.view-detail', [$result->id]) }}" class="btn btn-sm btn-info" title="View Details">
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
  <td class="text-center" colspan="6"><p>No Records</p></td>
</tr>
@endif
