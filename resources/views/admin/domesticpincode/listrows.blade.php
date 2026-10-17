@forelse($results as $index=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td> {{$row->city}} </td>
  <td>{{$row->post_office}}</td>
  <td>{{$row->pincode}}</td>

  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    @if(adminCan($nav, 'update'))
    <a href="javascript:void(0)" class="text-success" onclick="editForm('{{$row->id}}', '{{$row->city}}', '{{$row->post_office}}', '{{$row->pincode}}')">
      <i class="fa fa-edit"></i>
    </a>
    @endif
    @if(adminCan($nav, 'delete'))
    |
    <a href="javascript:void(0)" class="text-danger" onclick="deleteItem({{$row->id}})">
      <i class="fa fa-trash"></i>
    </a>
    @endif
  </td>
</tr>
@empty
@endforelse
