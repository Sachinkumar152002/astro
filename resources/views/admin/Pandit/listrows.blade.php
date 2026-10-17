@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  @if($image = $row->image)
    <td>
      <a href="javascript:void(0)" data-img="{{$image->image(800,800)}}" class="img-view">
        <img src="{{$image->image(75,75)}}" alt ="image">
      </a>
    </td>
  @else
    <td>
      <img class="border border-1 border-dark" src="{{no_image()}}" width="75" height="75" alt ="image">
    </td>
  @endif
  <td>
    @if(adminCan($module, 'update'))
      <a href="{{route('admin.'.$module.'.edit', [$row->id])}}" class="text-primary">
        {{$row->name}}
      </a>
      @else
        {{$row->name}}
      @endif
  </td>
  <td>{{ $row->email }}</td>
  <td>{{ $row->phone }}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ $row->order }}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    @if(adminCan($module, 'update'))
      <a href="{{route('admin.'.$module.'.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
    @endif
    @if(adminCan($module, 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.'.$module.'.copy', [$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
    @endif
    @if(adminCan($module, 'delete'))
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
