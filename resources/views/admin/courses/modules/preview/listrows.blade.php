@if($questions->count())
@foreach($questions as $question)
<tr>
  <th scope="row">{{$question->id}}</th>
  <td><img src="{{$question->thumbnail}}" alt="admin image" style="max-width: 60px;"></td>
  <td>{{$question->video_id}}</td>
    <td>{{$question->video_url}}</td>
  <td>{{$question->time}}</td>
  <td>{{$question->createdby ? $question->createdby->name : ''}}</td>


  <td class="text-right">
    <a href="{{route('admin.course.module.question.edit', [$course_id, $module_id, $question->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$question->id}})">
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
