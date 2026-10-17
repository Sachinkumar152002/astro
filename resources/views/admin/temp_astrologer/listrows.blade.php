@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if(adminCan('temporary-astrologer-data', 'update') && $row->astrologer)
      <a href="{{route('admin.'.$nav.'.edit', [$row->id])}}">
          {{$row->astrologer->display_name}}
      </a>
    @else
    {{'Astrologer'}}
    @endif
  </td>
  <td>{{$row->display_name}}</td>
  <td>{{$row->phone}}</td>
  <td>{{$row->email}}</td>
  <td>{{ucfirst($row->review_status)}}</td>
  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right text-nowrap">
    @if(adminCan('temporary-astrologer-data', 'update'))
      <a class="text-success"  href="{{route('admin.'.$nav.'.edit', [$row->id])}}">
        <i class="fa fa fa-edit"></i>
      </a>
    @endif
    {{--
    <a href="#" class="text-primary">
      <i class="bi bi-check2-circle"></i>
    </a>
    --}}
    @if(adminCan('temporary-astrologer-data', 'delete'))
    <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
      <i class="fa fa-trash"></i>
    </a>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
