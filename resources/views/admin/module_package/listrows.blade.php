@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
      <a href="{{route('admin.software.package.edit', [$data_id, $row->id])}}" class="text-primary">
        {{$row->heading}}
      </a>
  </td>
  <td>Rs. {{$row->title}}</td>
  <td>{{$row->order}}</td>
  <td class="text-right">
      <a href="{{route('admin.software.package.edit', [$data_id, $row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.software.package.copy', [$data_id, $row->id])}}')" class="text-secondary">
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
