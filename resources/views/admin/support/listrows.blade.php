@if($tickets->count())
@foreach($tickets as $key=>$value)
<tr>
  <th scope="row">{{$key+1+(($tickets->currentPage()-1)*$tickets->perPage())}}</th>
  <td>
    @if(adminCan('support', 'update'))
    <a href="{{route('admin.support.edit', [$value->id])}}" class="text-primary">
      {{$value->ticket_number}}
    </a>
    @else
    {{$value->ticket_number}}
    @endif
  </td>
  <td>{{$value->subject}}</td>

  <td>{{getSupportTypeName($value->type)}}</td>
  <td>{{ucfirst($value->status)}}</td>
  {{--
  <td>
    @if(adminCan('support', 'update'))
      <a href="{{route('admin.support.replies', [$value->id])}}" title="Modules">{{$value->replies->count()}} <i class="icon-pencil icon"></i></a>
    @else
    {{$value->replies->count()}}
    @endif
  </td>
  --}}
  <td>{{ISTDatetimeFormat($value->updated_at)}}</td>
  <td class="text-right text-nowrap">
      @if(adminCan('support', 'update'))
      <a href="{{route('admin.support.edit', [$value->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('support', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$value->id}})">
        <i class="fa fa-trash"></i>
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
