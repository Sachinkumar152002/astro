@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <td scope="row"> <input type="checkbox" name="" value=""> </td>
  <td>{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if(adminCan('recharge-plan', 'update'))
    <a href="{{route('admin.'.$url_key.'.edit', [$row->id])}}" class="text-primary">
      {{$row->name}}
    </a>
    @else
    {{$row->name}}
    @endif
  </td>
  <td>{{$row->one_time?'Yes':'No'}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right">
    @if(adminCan('recharge-plan', 'update'))
      <a href="{{route('admin.'.$url_key.'.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('recharge-plan', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.'.$url_key.'.copy', [$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('recharge-plan', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="5"><p>No Records</p></td>
</tr>
@endif
