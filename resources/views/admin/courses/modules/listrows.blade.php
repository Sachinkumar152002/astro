@if($modules->count())
@foreach($modules as $key => $module)

<tr>
  <th scope="row">{{$key+1+(($modules->currentPage()-1)*$modules->perPage())}}</th>
  <td>
    <a href="{{route('admin.course.module.edit', [$course_id, $module->id])}}">
      {{$module->name}}</td>
    </a>
  <td>{{$module->order}}</td>
  {{--
  <td><a href="{{route('admin.course.module.content', [$course_id, $module->id])}}">{{$module->content->count()}}<i class="icon icon-pencil"></i></a></td>
  --}}
  <td>{{ISTDatetimeFormat($module->updated_at)}}</td>
  <td>{!! statusBadge($module->status) !!}</td>
  <td class="p-0 m-0 d-none"><input type="checkbox" id="switch" class="toggle-check"/><label class="t-lable" for="switch"></label></td>
  <td class="text-right">
    <a href="{{route('admin.course.module.edit', [$course_id, $module->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.course.module.copy', [$course_id, $module->id])}}')" class="text-seconday">
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
