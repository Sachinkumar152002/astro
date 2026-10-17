@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if($row->image_id && $row->image)
    <a href="javascript:void(0)" data-img="{{$row->image->image(400,400)}}" class="img-view">
        <img src="{{$row->image->image(100,100)}}" alt="{{$row->name}}" class="img-fluid">
    </a>
    @endif
  </td>
  <td>{{$row->name}}</td>
  <td>{{$row->service}}</td>

  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right text-nowrap">
      <a href="{{route('admin.instructor.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>

  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
