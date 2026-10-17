@if($orders->count())
@foreach($orders as $key=>$value)
<tr>
  <th scope="row">{{$key+1+(($orders->currentPage()-1)*$orders->perPage())}}</th>

  <td>
      @if(adminCan('order','update'))
      <a href="{{route('admin.order.edit', [$value->id])}}" class="text-primary">
        {{$value->order_number}}
      </a>
      @else
      {{$value->order_number}}
      @endif
  </td>
  @if($value->invoice)
    <td>
      <a href="{{route('admin.invoice.download', [$value->invoice->id])}}" class="text-primary" targe="_blank">
        {{$value->invoice->prefix.$value->invoice->number}}
      </a>
    </td>
  @else
    <td>-</td>
  @endif
  <td>{{$value->billing_full_name}}</td>
  <td>{{$value->order_status}}</td>
  <td>{{$value->total_price}}</td>
  <td>{{ $value->payment_status }}</td>
  <td>
    <div>
      @if(count(orderRequests($value->user_id, $value->id, 'cancel')))
        C:
        @foreach(orderRequests($value->user_id, $value->id, 'cancel') as $key => $item)
          @if($item->status == 1)
          <i class="far fa-window-close text-success"></i>
          @elseif($item->status == 2)
          <i class="far fa-window-close text-danger"></i>
          @else
          <i class="far fa-window-close text-primary"></i>
          @endif
        @endforeach
      @endif
    </div>
    <div>
      @if(count(orderRequests($value->user_id, $value->id, 'return')))
      R:
        @foreach(orderRequests($value->user_id, $value->id, 'return') as $key => $item)
          @if($item->status == 1)
          <i class="far fa-window-close text-success"></i>
          @elseif($item->status == 2)
          <i class="far fa-window-close text-danger"></i>
          @else
          <i class="far fa-window-close text-primary"></i>
          @endif
        @endforeach
      @endif
    </div>
  </td>
  <td>{{ISTDatetimeFormat($value->updated_at)}}</td>
  <td>
    @if(adminCan('order','update'))
    <a href="{{route('admin.order.edit', [$value->id])}}" class="text-success targe="_blank">
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
