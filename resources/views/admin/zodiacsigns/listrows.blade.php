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
    @foreach($subPagesName as $i=>$spname)
    {!! $i>0 && $i%3==0?'<br>':'' !!}
    <a href="{{route('admin.zodiacsigns.page', [$row->slug, str_slug($spname)])}}" class="badge badge-primary"> {{$spname}}</a>
    @endforeach
  </td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right">
      <a href="{{route('admin.zodiacsigns.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="5"><p>No Records</p></td>
</tr>
@endif