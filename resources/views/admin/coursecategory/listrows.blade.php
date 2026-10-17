@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  @if($row->image)
  <td>
    <a href="javascript:void(0)" data-img="{{$row->image->image(800,800)}}" class="img-view">
        <img src="{{$row->image->image(75,75)}}" alt ="image">
    </a>
  </td>
  @else
  <td>
        <img class="border border-1 border-dark" src="{{no_image()}}" width="75" height="75" alt ="image">
  </td>
  @endif
  <td>
    @if(adminCan('course-category', 'update'))
      <a href="{{route('admin.coursecategory.edit', [$lang,$row->id])}}" class="text-primary">
        {{$row->name}}
      </a>
      @else
      {{$row->name}}
      @endif
  </td>
  <td>{{$row->parent ? $row->parent->name: ''}}</td>
  <td>{{$row->order}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>

  <td class="text-right text-nowrap">
      <a href="{{route('course.handle', [$row->slug])}}" class="text-primary" targe="_blank">
        <i class="fa fa-eye"></i>
      </a>
      @if(adminCan('course-category', 'update'))
      |
      <a href="{{route('admin.coursecategory.edit', [$lang,$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('course-category', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.coursecategory.copy', [$lang,$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('course-category', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="7"><p>No Records</p></td>
</tr>
@endif
