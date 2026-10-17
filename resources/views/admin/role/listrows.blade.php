@forelse($results as $index => $row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('role', 'add'))
    <a href="javascript:void(0)" onclick="editForm({{$row->id}}, '{{$row->name}}')" class="text-primary">
    {{$row->name}}
    </a>
    @else
    {{$row->name}}
    @endif
  </td>
    <td class="text-right">
    @if(adminCan('role', 'update'))
      <a href="javascript:void(0)" class="text-success" onclick="editForm({{$row->id}}, '{{$row->name}}')">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('role', 'delete'))
      <a href="javascript:void(0)" class="text-danger" onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@empty
<tr>
  <td colspan="2"><p>No Data <a href="javascript:void(0)" onclick="editForm(0,'')">Add new</a></p></td>
</tr>

@endforelse
