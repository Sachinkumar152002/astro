@if($results->count())
@foreach($results as $index => $row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('admin', 'update'))
    <a href="{{route('admin.user.edit',[$row->id])}}" class="text-primary">
      {{$row->name}}
    </a>
    @else
      {{$row->name}}
    @endif
  </td>
  <td>{{$row->email}}</td>
  <td>{{$row->role?$row->role->name:''}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    @if(adminCan('admin', 'update'))
    <a href="{{route('admin.user.edit',[$row->id])}}" class="text-success">
      <i class="fa fa-edit"></i>
    </a>
    @endif
    @if(adminCan('admin', 'delete'))
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
  <td class="text-center" colspan="8"><p>No Records</p></td>
</tr>
@endif
