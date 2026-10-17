@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    <a href="javascript:void(0)" data-img="{{$row->image->image(800,800)}}" class="img-view">
        <img src="{{$row->image ? $row->image->image(75,75) : no_image()}}" width="75" height="75" alt="{{$row->name}}" class="img-fluid">
    </a>
  </td>
  <td>
      @if(adminCan('vastu-page', 'update'))
      <a href="{{route('admin.vastu.edit', [$row->id])}}" class="text-primary">
        {{$row->name}}
      </a>
      @else
      {{$row->name}}
      @endif
  </td>
  <td>
    @if(adminCan('vastu-page', 'update'))
    <a href="{{route('admin.vastu.package', [$row->id])}}" title="video">{{$row->packageServices->count()}}<i class="icon-pencil icon"></i></a>
    @else
    {{$row->packageServices->count()}}
    @endif
  </td>
  <td>{!! statusBadge($row->status) !!}</td>
<td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
      <a href="{{route('vastu.page', [$row->slug])}}" class="text-primary" target="_blank">
        <i class="fa fa-eye"></i>
      </a>
      @if(adminCan('vastu-page', 'update'))
      |
      <a href="{{route('admin.vastu.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('vastu-page', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.vastu.copy', [$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('vastu-page', 'delete'))
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
  <td class="text-center" colspan="5"><p>No Records</p></td>
</tr>
@endif
