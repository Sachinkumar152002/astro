@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>{{$row->meta_title}}</td>
  <td>{{$row->meta_keywords}}</td>
  <td>{{$row->meta_description}}</td>
  <td>{{implode(',', json_decode($row->overwrite))}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{$row->updated_at != $row->created_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right">
      <a href="{{route('admin.meta.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="8"><p>No Records</p></td>
</tr>
@endif
