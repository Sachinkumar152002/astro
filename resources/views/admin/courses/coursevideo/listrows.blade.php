@if($videos->count()>0)
@foreach($videos as $module)
<tr>
  <th scope="row">{{$module->id}}</th>
  <td>{{$module->title}}</td>
  {{-- <td>{{$module->total_time}}</td> --}}
  {{-- <td><a href="{{route('admin.course.module.question', [$course_id, $module->id])}}">{{$module->questionlist->count()}} <i class="icon icon-pencil"></i></a></td> --}}
  {{-- <td><a href="{{route('admin.course.module.file', [$course_id, $module->id])}}">{{$module->files->count()}}<i class="icon icon-pencil"></i></a></td> --}}
  <td>{{$module->file_name }}</td>
  <td>{{$module->caption}}</td>

  <td>{{$module->created_at}}</td>
  {{-- <td>{{$module->created_at}}</td> --}}
  <td>{{$module->updatedby ? $module->updatedby->name : ''}}</td>
  {{-- <td>{{$module->updated_by ? $module->updated_at : ''}}</td> --}}
  <td>{!! statusBadge($module->status) !!}</td>
  <td class="text-right">
    <a href="{{route('admin.course.video.edit', [$course_id, $module->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$module->id}})">
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