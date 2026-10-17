@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>{{ $row->user ? $row->user->name : '' }}</td>
  <td>{{ $row->astrologer ? $row->astrologer->display_name : '' }}</td>
  <td>{{$row->start_time}}</td>
  <td>{{$row->end_time}}</td>
  <td>{{$row->meeting_date}}</td>
  <td class="text-right">
      <a href="{{route('admin.callmeeting.view', [$row->id])}}" class="text-success">
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