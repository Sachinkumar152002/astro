@if($files->count())
@foreach($files as $file)
<tr>
  <th scope="row">{{$file->id}}</th>
  <td>{{$file->file_type}}</td>
  <td>{{$file->title}}</td>
  <td>{{$file->createdby ? $file->createdby->name : ''}}</td>
  <td>{{$file->created_at}}</td>
  <td>{{$file->updatedby ? $file->updatedby->name : ''}}</td>
  <td>{{$file->updated_by ? $file->updated_at : ''}}</td>
  <td>{!! statusBadge($file->status) !!}</td>
  <td class="text-right">
      <a href="{{$file->link}}" target="_blank" class="text-primary">
        <i class="fa fa-eye"></i>
      </a>
      <a href="{{route('admin.course.module.file.edit', [$course_id, $module_id, $file->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$file->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="9"><p>No Records</p></td>
</tr>
@endif