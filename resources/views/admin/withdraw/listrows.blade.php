@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  @php
    $astrologer = $row->astrologer;
  @endphp
  <td>{{$astrologer ? $astrologer->display_name : ''}}</td>
  <td>{{$astrologer ? $astrologer->phone : ''}}</td>
  <td>{{$row->amount}}</td>
  <td>{{$row->status}}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    <a href="{{route('admin.'.$nav.'.edit', [$row->id])}}" class="text-success">
      <i class="fa fa-edit"></i>
    </a>
    {{--
    |
    <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.'.$nav.'.copy', [$row->id])}}')" class="text-secondary">
      <i class="fa fa-copy"></i>
    </a>
    |
    <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
      <i class="fa fa-trash"></i>
    </a>
    --}}
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
