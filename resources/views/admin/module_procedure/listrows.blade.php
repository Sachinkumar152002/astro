@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    <a href="javascript:void(0)" data-img="{{$row->image ? $row->image->image(800,800) : no_image(0)}}" class="img-view">
        <img src="{{$row->image ? $row->image->image(75,75) : no_image()}}" width="75" height="75" alt="{{$row->title}}" class="img-fluid">
    </a>
  </td>
  <td>
      <a href="{{route('admin.software.procedure.edit', [$data_id, $row->id])}}" class="text-primary">
        {{$row->title}}
      </a>
  </td>
  <td>{{$row->order}}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
      <a href="{{route('admin.software.procedure.edit', [$data_id, $row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.software.procedure.copy', [$data_id, $row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a> |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="12"><p>No Records</p></td>
</tr>
@endif
