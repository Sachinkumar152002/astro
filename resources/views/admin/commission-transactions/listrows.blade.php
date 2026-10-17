@if($results->count())
@foreach($results as $index=>$transaction)
<tr>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if($transaction->referrer)
      @if($transaction->referrer_type == 'user')
        {{$transaction->referrer->name ?? 'N/A'}} (User)
      @else
        {{$transaction->referrer->display_name ?? ($transaction->referrer->firstname . ' ' . $transaction->referrer->lastname) ?? 'N/A'}} (Astrologer)
      @endif
    @else
      <span class="text-muted">N/A</span>
    @endif
  </td>
  <td>
    @if($transaction->order)
      <a href="{{route('admin.order.detail', [$transaction->order->id])}}">
        #{{$transaction->order->order_number ?? $transaction->order_id}}
      </a>
    @else
      #{{$transaction->order_id}}
    @endif
  </td>
  <td>
    @if($transaction->category)
      {{$transaction->category->name ?? 'N/A'}}
    @else
      <span class="text-muted">N/A</span>
    @endif
  </td>
  <td>₹{{number_format($transaction->order_amount, 2)}}</td>
  <td>
    <strong class="text-success">₹{{number_format($transaction->commission_amount, 2)}}</strong>
    <br>
    <small class="text-muted">
      @if($transaction->commission_mode == 'PERCENTAGE')
        {{number_format($transaction->commission_rate, 2)}}%
      @else
        Fixed
      @endif
    </small>
  </td>
  <td>
    @if($transaction->status == 'credited')
      <span class="badge bg-success">Credited</span>
    @elseif($transaction->status == 'pending')
      <span class="badge bg-warning">Pending</span>
    @elseif($transaction->status == 'refunded')
      <span class="badge bg-danger">Refunded</span>
    @else
      <span class="badge bg-secondary">Cancelled</span>
    @endif
  </td>
  <td>{{$transaction->created_at ? $transaction->created_at->format('d-m-Y H:i') : 'N/A'}}</td>
  <td class="text-right">
    <a href="#" class="btn btn-sm btn-info" title="View Details">
      <i class="bi bi-eye"></i>
    </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="9"><p>No Records</p></td>
</tr>
@endif
