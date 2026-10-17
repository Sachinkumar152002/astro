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
    @if($transaction->referred)
      @if($transaction->referred_type == 'user')
        {{$transaction->referred->name ?? 'N/A'}} (User)
      @else
        {{$transaction->referred->display_name ?? ($transaction->referred->firstname . ' ' . $transaction->referred->lastname) ?? 'N/A'}} (Astrologer)
      @endif
    @else
      <span class="text-muted">N/A</span>
    @endif
  </td>
  <td><strong class="text-success">₹{{number_format($transaction->reward_amount, 2)}}</strong></td>
  <td>
    <span class="badge bg-info">{{ucfirst(str_replace('_', ' ', $transaction->reward_trigger))}}</span>
  </td>
  <td>
    @if($transaction->status == 'credited')
      <span class="badge bg-success">Credited</span>
    @elseif($transaction->status == 'pending')
      <span class="badge bg-warning">Pending</span>
    @elseif($transaction->status == 'expired')
      <span class="badge bg-secondary">Expired</span>
    @else
      <span class="badge bg-danger">Cancelled</span>
    @endif
  </td>
  <td>{{$transaction->created_at ? $transaction->created_at->format('d-m-Y H:i') : 'N/A'}}</td>
  <td class="text-right">
    @if($transaction->order)
      <a href="{{route('admin.order.detail', [$transaction->order->id])}}" class="btn btn-sm btn-info" title="View Order">
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
