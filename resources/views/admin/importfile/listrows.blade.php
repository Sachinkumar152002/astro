@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td><a href="{{asset($row->url)}}">{{ $row->type == 'panchang' ? 'Thought of the Day' : $row->type }}</a></td>
  <td>{{$row->is_processed ? 'Yes' : 'No'}}</td>
  <td>{{ $row->total }}</td>
  <td>{{ $row->processed }}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    {{--
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
