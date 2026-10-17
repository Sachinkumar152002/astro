@if($questions->count())
@foreach($questions as $question)
<tr>
  <th scope="row">{{$question->id}}</th>
  <td>{{$question->question}}</td>
  <td>{{$question->time}}</td>
  <td>{{$question->createdby ? $question->createdby->name : ''}}</td>
  <td>{{$question->created_at}}</td>
  <td>{{$question->updatedby ? $question->updatedby->name : ''}}</td>
  <td>{{$question->updated_by ? $question->updated_at : ''}}</td>
  <td>{!! statusBadge($question->status) !!}</td>
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