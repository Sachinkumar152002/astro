@if($results->count())
@foreach($results as $key=>$value)
<tr>
  <th scope="row">{{$key+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
      @if(adminCan('user-recharge','update'))
      <a href="{{route('admin.user_recharge.edit', [$value->id])}}" class="text-primary">
        {{$value->my_txn_id}}
      </a>
      @else
        {{$value->my_txn_id}}
      @endif
  </td>
  @if($value->invoice)
  <td>
    <a href="{{route('admin.invoice.download', [$value->invoice->id])}}" class="text-primary" target="_blank">
      {{$value->invoice->prefix.$value->invoice->number}}
    </a>
  </td>
  @else
  <td>-</td>
  @endif
  @if($value->user)
  <td>{{$value->user->name}}</td>
  @else
  <td>Unknown</td>
  @endif
  <td>{{ucfirst($value->payment_status)}}</td>
  <td>{{ISTDatetimeFormat($value->updated_at)}}</td>
  <td>
    @if(adminCan('user-recharge','update'))
    <a href="{{route('admin.user_recharge.edit', [$value->id])}}" class="text-primary" target="_blank">
      <i class="fa fa-edit"></i>
    </a>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="13"><p>No Records</p></td>
</tr>
@endif
