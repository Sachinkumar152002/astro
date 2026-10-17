@if($results->count())
@foreach($results as $index => $item)
<tr>
  <th scope="row">{{ $index + 1 + (($results->currentPage() - 1) * $results->perPage()) }}</th>
  <td>
    @if($item->order && $item->order->user)
      {{ $item->order->user->name ?? $item->order->billing_full_name ?? 'N/A' }}
    @else
      {{ $item->order->billing_full_name ?? 'N/A' }}
    @endif
  </td>
  <td>
    @if($item->product)
      {{ $item->product->title ?? 'N/A' }}
    @else
      N/A
    @endif
  </td>
  <td>₹{{ number_format($item->total_price ?? $item->price ?? 0, 2) }}</td>
  <td>
    @if($item->order)
      {{ $item->order->created_at ? \Carbon\Carbon::parse($item->order->created_at)->format('Y-m-d H:i') : 'N/A' }}
    @else
      {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i') : 'N/A' }}
    @endif
  </td>
  <td>
    @if($item->order)
      <span class="badge badge-{{ $item->order->status == 'Success' ? 'success' : ($item->order->status == 'Pending' ? 'warning' : 'danger') }}">
        {{ $item->order->status ?? 'N/A' }}
      </span>
    @else
      N/A
    @endif
  </td>
  <td class="text-right">
    @if($item->order && adminCan('order', 'update'))
      <a href="{{ route('admin.order.edit', [$item->order->id]) }}" class="btn btn-sm btn-info" title="View Order">
        <i class="bi bi-eye"></i>
      </a>
    @else
      <span class="btn btn-sm btn-info disabled" title="No Order">
        <i class="bi bi-eye"></i>
      </span>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="7"><p>No Records</p></td>
</tr>
@endif
