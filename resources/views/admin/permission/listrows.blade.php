@forelse($results as $index => $row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('permission', 'add'))
    <a href="{{route('admin.permission.edit', [$row->parent_id])}}" class="text-primary">
    {{$row->role? $row->role->name : ''}}
    </a>
    @else
    {{$row->role?$row->role->name:''}}
    @endif
  </td>
  {{--<td>{{$row->module->name}}</td>
  <td>{{$row->can_add?'Yes':'No'}}</td>
  <td>{{$row->can_view?'Yes':'No'}}</td>
  <td>{{$row->can_update?'Yes':'No'}}</td>
  <td>{{$row->can_delete?'Yes':'No'}}</td>--}}
    <td class="text-right">
    @if(adminCan('permission', 'update'))
      <a href="{{route('admin.permission.edit', [$row->parent_id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('permission', 'delete'))
      <a href="javascript:void(0)" class="text-danger" onclick="deleteRecord({{$row->parent_id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@empty
<tr>
  <td colspan="10" class="text-center"><p>No Data</td>
</tr>

@endforelse
