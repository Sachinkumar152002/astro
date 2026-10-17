@if($results->count())
@foreach($results as $key=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$key+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
  @if(adminCan('course-review', 'update'))
      <a href="{{route('admin.course.review.edit', [$row->id])}}" class="text-primary">
        {!! substr($row->review,0,50) !!}
      </a>
      @else
      {!! substr($row->review,0,50) !!}
      @endif
  </td>
  <td>{{$row->courseReviews ? $row->courseReviews->name : ''}}</td>
  @if($row->user_type == 'user')
    <td>{{$row->user ? $row->user->name : 'user' }}</td>
  @else
    <td>{{$row->astrologer ? $row->astrologer->fullname : 'astrologer' }}</td>
  @endif
  <td>{{$row->rating}}</td>

  <td>{!! statusBadge($row->status) !!}</td>
<td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right text-nowrap">
  @if(adminCan('course-review', 'update'))
      <a href="{{route('admin.course.review.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      {{--
      @if(adminCan('course-review', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
      --}}
  </td>

</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="13"><p>No Records</p></td>
</tr>
@endif
