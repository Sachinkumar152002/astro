@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <td scope="row"> <input type="checkbox" name="" value=""> </td>
  <td>{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
      <a href="{{route('admin.customer.course.edit', [$user_id, $row->id])}}" class="text-primary">
        {{$row->course_name}}
      </a>
  </td>
  <td>Rs. {{$row->amount}}</td>
  <td>{{ISTDatetimeFormat($row->expire_date)}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td class="text-right">
      <a href="{{route('admin.customer.course.edit', [$user_id, $row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      {{--
        |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.customer.course.copy', [$service_id, $row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a> |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      --}}
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="5"><p>No Records</p></td>
</tr>
@endif
