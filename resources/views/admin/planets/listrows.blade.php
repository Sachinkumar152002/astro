@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if($row->image_id && $row->image)
    <a href="javascript:void(0)" data-img="{{$row->image->image(400,400)}}" class="img-view">
        <img src="{{$row->image->image(100,100)}}" alt="{{$row->title}}" class="img-fluid">
    </a>
    @endif
  </td>
  <td>{{$row->name}}</td>
  <td>
    @if($row->is_transit)
      <a href="{{route('admin.planets.edit', [App\Model\Planet::where('parent_id', $row->id)->where('page_type', 'transit')->first()->id])}}" class="badge badge-primary"> Transit</a>
    @endif
    @if($row->is_retrograde)
      <a href="{{route('admin.planets.edit', [App\Model\Planet::where('parent_id', $row->id)->where('page_type', 'retrograde')->first()->id])}}" class="badge badge-success"> Retrograde</a>
    @endif
    @if($row->is_grahan)
      <a href="{{route('admin.planets.edit', [App\Model\Planet::where('parent_id', $row->id)->where('page_type', 'grahan')->first()->id])}}" class="badge badge-warning text-dark"> {{$row->grahan_name}}</a>
    @endif

  </td>
  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right">
      <a href="{{route('admin.planets.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="5"><p>No Records</p></td>
</tr>
@endif