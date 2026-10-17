@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    <a href="javascript:void(0)" data-img="{{$row->image ? $row->image->image(800,800) : no_image()}}" class="img-view">
        <img src="{{$row->image ? $row->image->image(75,75) : no_image()}}" width="75" height="75" alt="{{$row->name}}" class="img-fluid">
    </a>
  </td>
  <td>
      @if(adminCan('cms-two', 'update'))
      <a href="{{route('admin.software.edit', [$row->id])}}" class="text-primary">
        {{$row->title}}
      </a>
      @else
      {{$row->title}}
      @endif
  </td>
  <td>{{ucfirst($row->page)}}</td>
  <td>
      @if(adminCan('cms-two', 'update'))
      <a href="{{route('admin.software.package', [$row->id])}}" title="video">
        {{$row->packages->count()}}<i class="icon-pencil icon"></i>
      </a>
      @else
      {{$row->packages->count()}}
      @endif
  </td>
  <td>
      @if(adminCan('cms-two', 'update'))
      <a href="{{route('admin.software.feature', [$row->id])}}" title="video">
        {{$row->features->count()}}<i class="icon-pencil icon"></i>
      </a>
      @else
      {{$row->features->count()}}
      @endif
  </td>
  <td>
      @if(adminCan('cms-two', 'update'))
      <a href="{{route('admin.software.procedure', [$row->id])}}" title="video">
        {{$row->procedures->count()}}<i class="icon-pencil icon"></i>
      </a>
      @else
      {{$row->procedures->count()}}
      @endif
  </td>
  <td>
      @if(adminCan('cms-two', 'update'))
      <a href="{{route('admin.software.video', [$row->id])}}" title="video">
        {{$row->videos->count()}}<i class="icon-pencil icon"></i>
      </a>
      @else
      {{$row->videos->count()}}
      @endif
  </td>
<td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right icon-td-main">
      <a href="{{url($row->slug)}}" class="text-primary" target="_blank">
        <i class="fa fa-eye"></i>
      </a>
      @if(adminCan('cms-two', 'update'))
      |
      <a href="{{route('admin.software.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      {{--
      @if(adminCan('cms-two', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
      --}}
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="12"><p>No Records</p></td>
</tr>
@endif
