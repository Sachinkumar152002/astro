@if($courses->count())
@foreach($courses as $key=>$course)
<tr>
  <th scope="row"> <input type="checkbox" name="selectall" value=""> </th>
  <th scope="row">{{$key+1+(($courses->currentPage()-1)*$courses->perPage())}}</th>
  <td>
    <a href="javascript:void(0)" data-img="{{$course->image ? $course->image->image(800,800) : no_image()}}" class="img-view">
        <img src="{{$course->image ? $course->image->image(75,75) : no_image()}}" width="75" height="75" alt="{{$course->title}}" class="img-fluid">
    </a>
  </td>
  <td colspan="2">
    @if(adminCan('course', 'update'))
      <a href="{{route('admin.course.edit', [$course->id])}}" class="text-primary">
        {{$course->name}}
      </a>
      @else
      {{$course->name}}
      @endif
  </td>
  <td>{{courseTypes($course->type)}}</td>
  <td class="d-none">{{$course->association ? $course->association->name : ''}}</td>
  <td class="d-none">Rs. {{number_format($course->price,0)}}</td>
  <td>{{$course->publish ? 'Publish' : 'Unpublish'}}</td>
  <td>
    @if(adminCan('course', 'update'))
    <a href="{{route('admin.course.module', [$course->id])}}" title="Modules">
      {{$course->moduless->count()}} <i class="icon-pencil icon"></i>
    </a>
    @else
      {{$course->moduless->count()}}
    @endif
  </td>
  <td>
    @if(adminCan('course', 'update'))
    <a href="{{route('admin.course.benifit', [$course->id])}}" title="video">
      {{$course->benifitss->count()}}<i class="icon-pencil icon"></i>
    </a>
    @else
      {{$course->benifitss->count()}}
    @endif
  </td>
  <td>{!! statusBadge($course->status) !!}</td>
<td>{{ISTDatetimeFormat($course->updated_at)}}</td>
<td class="p-0 m-0 d-none"><input type="checkbox" id="switch" class="toggle-check"/><label class="t-lable" for="switch"></label></td>
  <td class="text-right text-nowrap">
      <a href="{{route('course.handle', [$course->slug])}}" class="text-primary" targe="_blank">
        <i class="fa fa-eye"></i>
      </a>
      @if(adminCan('course', 'update'))
      |
      <a href="{{route('admin.course.edit', [$course->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('course', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.course.copy', [$course->id])}}')" class="text-seconday">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('course', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$course->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="13"><p>No Records</p></td>
</tr>
@endif
