@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    <a href="javascript:void(0)" data-img="{{$row->image ? $row->image->image(800,800) : no_image()}}" class="img-view">
        <img src="{{$row->image ? $row->image->image(75,75) : no_image()}}" width="75" height="75" class="img-fluid">
    </a>
  </td>
  <td>
    <a class="text-primary" href="{{route('admin.'.$module.'.edit', [$row->id])}}">
      {{$row->name}}
    </a>
  </td>
  <td>{{$row->price}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-wrap">
      <a href="{{route('admin.'.$module.'.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.'.$module.'.copy', [$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
