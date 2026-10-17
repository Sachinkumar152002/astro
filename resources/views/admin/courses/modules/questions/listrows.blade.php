@if($questions->count())
@foreach($questions as $key=> $question)
<tr>
  <th scope="row">{{$key+1+(($questions->currentPage()-1)*$questions->perPage())}}</th>
  <td>
      <a href="{{route('admin.course.module.content.question.edit', [$course_id, $module_id,$content_id, $faq_type, $question->id])}}" class="text-primary">
        {{$question->question}}
      </a>
  </td>
  {{--
  <td>{{courseQuestionShowIn()[$question->show_in]}}</td>
  --}}
  <td>{{$question->order}}</td>
  <td>{!! statusBadge($question->status) !!}</td>
  <td>{{ISTDatetimeFormat($question->updated_at)}}</td>
  <td class="text-right">
    <a href="{{route('admin.course.module.content.question.edit', [$course_id, $module_id,$content_id, $faq_type, $question->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.course.module.content.question.copy', [$course_id, $module_id,$content_id, $faq_type, $question->id])}}')" class="text-seconday">
          <i class="fa fa-copy"></i>
        </a> |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$question->id}})">
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
