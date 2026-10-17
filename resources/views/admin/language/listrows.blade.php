@forelse($results as $index=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if(adminCan('language', 'update'))
    <a href="javascript:void(0)" class="text-primary" onclick="editForm('{{$row->id}}', '{{$row->name}}')">
      {{$row->name}}
    </a>
    @else
    {{$row->name}}
    @endif
  </td>
  <td>{{$row->position}}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    @if(adminCan('language', 'update'))
    <a href="javascript:void(0)" class="text-success" onclick="editForm('{{$row->id}}', '{{$row->name}}', {{$row->position}})">
      <i class="fa fa-edit"></i>
    </a>
    @endif
    @if(adminCan('language', 'delete'))
    |
    <a href="javascript:void(0)" class="text-danger" onclick="deleteItem({{$row->id}})">
      <i class="fa fa-trash"></i>
    </a>
    @endif
  </td>
</tr>
@empty


@endforelse
