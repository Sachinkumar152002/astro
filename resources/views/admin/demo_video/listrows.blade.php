@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    <a href="javascript:void(0)" data-img="{{$row->thumbnail}}" class="img-view">
        <img src="{{$row->thumbnail}}" width="75" height="75" alt="{{$row->title}}" class="img-fluid">
    </a>
  </td>
  <td>
      <a href="{{route('admin.software.video.edit', [$data_id, $row->id])}}" class="text-primary">
        {{$row->title}}
      </a>
  </td>
  <td>{{$row->order}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
      <a href="{{route('admin.software.video.edit', [$data_id, $row->id])}}" class="text-success">
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
  <td class="text-center" colspan="12"><p>No Records</p></td>
</tr>
@endif
