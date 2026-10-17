@forelse($results as $index => $row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>{{$row->name}}</td>
  <td>{{$row->key}}</td>
    <td class="text-right">
    <a href="javascript:void(0)" class="text-success" onclick="editItem({{$row->id}}, '{{$row->name}}', '{{$row->key}}')">
        <i class="fa fa-edit"></i>
      </a>
      <a href="javascript:void(0)" class="text-danger" onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@empty
<tr>
  <td colspan="2"><p>No Data </td>
</tr>

@endforelse
