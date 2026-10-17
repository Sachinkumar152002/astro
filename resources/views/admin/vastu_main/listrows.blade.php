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
    <a href="{{route('admin.vastu.package', [$row->id])}}" title="video"> {{$row->packageServices->count()}}<i class="icon-pencil icon"></i></a>
    @else
    {{$row->packageServices->count()}}
    @endif
  </td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
      <a href="{{asset('vastu-shastra')}}" class="text-primary" target="_blank">
        <i class="fa fa-eye"></i>
      </a>
      @if(adminCan('vastu-page', 'update'))
      |
      <a href="{{route('admin.vastu.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
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
