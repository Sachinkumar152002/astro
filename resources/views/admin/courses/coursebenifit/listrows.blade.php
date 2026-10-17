@if($videos->count()>0)
@foreach($videos as $key => $module)
<tr>
  <th scope="row">{{$key+1+(($videos->currentPage()-1)*$videos->perPage())}}</th>
  <td>
      <a href="{{route('admin.course.benifit.edit', [$course_id, $module->id])}}" class="text-primary">
        {{$module->title}}
      </a>
  </td>
  <td>{{ISTDatetimeFormat($module->updated_at)}}</td>
    <td>{!! statusBadge($module->status) !!}</td>
  <td class="text-right">

    <a href="{{route('admin.course.benifit.edit', [$course_id, $module->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.course.benifit.copy', [$course_id, $module->id])}}')" class="text-seconday">
          <i class="fa fa-copy"></i>
        </a> |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$module->id}})">
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
