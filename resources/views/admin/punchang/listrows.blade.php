@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>{{date('d-m-Y', strtotime($row->date))}}</td>
  <td>{{substr($row->english, 0, 50)}}</td>
  <td>{{substr($row->hindi, 0, 50)}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
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
  <td class="text-center" colspan="5"><p>No Records</p></td>
</tr>
@endif
