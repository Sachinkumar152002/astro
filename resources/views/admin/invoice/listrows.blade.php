@if($results->count())
@foreach($results as $key=>$value)
<tr>
  <th scope="row">{{$key+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('invoice','update'))
    <a href="{{route('admin.invoice.download', [$value->id])}}" class="text-primary" target="_blank">
      {{$value->prefix.$value->number}}
    </a>
    @else
    {{$value->prefix.$value->number}}
    @endif
  </td>
  <td>{{ucfirst($value->relation_type)}}</td>
  <td>{{$value->name}}</td>
  <td>{{$value->billing_full_name}}</td>
  <td>₹ {{$value->total_amount}}</td>
  <td>{{ISTDatetimeFormat($value->updated_at)}}</td>
  <td>
    @if(adminCan('invoice','update'))
      <a href="{{route('admin.invoice.download', [$value->id])}}" class="text-primary" target="_blank">
        <i class="fa fa-download"></i>
      </a>
    @endif
  </td>
  @if(adminCan('invoice', 'delete'))
    <button class="btn btn-danger text-white m-1">
      <i class="bi bi-trash"></i>
    </button>
    @endif
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="13"><p>No Records</p></td>
</tr>
@endif
