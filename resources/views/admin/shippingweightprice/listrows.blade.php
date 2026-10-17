@forelse($results as $index=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td> {{$row->weight_from}} </td>
  <td>{{$row->weight_to}}</td>
  <td>{{$row->local_price}}</td>
  <td>{{$row->domestic_price}}</td>
  <td>{{$row->national_price}}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    @if(adminCan($nav, 'update'))
    <a href="javascript:void(0)" class="text-success" onclick="editForm('{{$row->id}}', '{{$row->weight_from}}', '{{$row->weight_to}}', '{{$row->local_price}}', '{{$row->domestic_price}}', '{{$row->national_price}}')">
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
