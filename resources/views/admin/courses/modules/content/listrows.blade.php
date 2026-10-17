@if($contents->count())
@foreach($contents as $key => $content)
<tr>
  <th scope="row">{{$key+1+(($contents->currentPage()-1)*$contents->perPage())}}</th>
  <td>
      <a href="{{route('admin.course.module.content.edit', [$course_id, $module_id, $content->id])}}" class="text-primary">
        {{$content->title}}
      </a>
  </td>
  <td>{{$content->order}}</td>
  <td>{!! statusBadge($content->status) !!}</td>
  <td><a href="{{route('admin.course.module.content.question', [$course_id, $module_id, $content->id, 'assignment'])}}">{{$content->assignmentQuestions->count()}} <i class="icon icon-pencil"></i></a></td>
  <td><a href="{{route('admin.course.module.content.question', [$course_id, $module_id, $content->id, 'quizz'])}}">{{$content->quizzQuestions->count()}} <i class="icon icon-pencil"></i></a></td>
<td>{{ISTDatetimeFormat($content->updated_at)}}</td>
  <td class="text-right">
    <a href="{{route('admin.course.module.content.edit', [$course_id, $module_id, $content->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.course.module.content.copy', [$course_id, $module_id, $content->id])}}')" class="text-seconday">
        <i class="fa fa-copy"></i>
      </a> |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$content->id}})">
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
