@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th> <input type="checkbox" name="" value=""> </th>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  @php
  $user = $row->user;
  $astrologer = $row->astrologer;
  @endphp
  <td>{{$user?$user->name:''}}</td>
  <td>{{$astrologer?$astrologer->display_name:''}}</td>
  @if($row->for==1)
    <td>User</td>
  @elseif($row->for==2)
    <td>Astrologer</td>
  @else
    <td>All</td>
  @endif
  {{--
  <td>{{$row->View?'Yes':'No'}}</td>
  --}}
  <td>{{$row->message}}</td>
  <td>{{ notificationOn($row->on) }}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
    @if(adminCan($nav, 'update'))
      <a href="{{route('admin.'.$nav.'.edit', [$row->id, $row->user_id, $row->astrologer_id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan($nav, 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.'.$nav.'.copy', [$row->id, $row->user_id, $row->astrologer_id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan($nav, 'delete'))
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
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
