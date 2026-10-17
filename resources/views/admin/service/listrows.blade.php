@forelse($results as $index=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    <a href="javascript:void(0)" class="text-primary" onclick="editForm('{{$row->id}}', '{{$row->name}}', '{{$row->short_name}}', '{{$row->icon_id}}', '{{$row->icon?$row->icon->image(100, 100):no_image()}}')">
      {{$row->name}}
    </a>
  </td>
  <td>{{$row->short_name}}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    <a href="javascript:void(0)" class="text-success" onclick="editForm('{{$row->id}}', '{{$row->name}}', '{{$row->short_name}}', '{{$row->icon_id}}', '{{$row->icon?$row->icon->image(100, 100):no_image()}}')">
      <i class="fa fa-edit"></i>
    </a>
    |
    <a href="javascript:void(0)" class="text-danger" onclick="deleteItem({{$row->id}})">
      <i class="fa fa-trash"></i>
    </a>
  </td>
</tr>
@empty
<tr colspan="6">
  no result found
</tr>
@endforelse
