@if($results->count())
@foreach($results as $key=>$row)
<tr>
  <!-- <td> <input type="checkbox" name="" value=""> </td> -->
  <td scope="row">{{$key+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  @php
    $astrologer = $row->astrologer;
    $user = $row->user;
  @endphp
  <td>{{$astrologer ? $astrologer->display_name : $astrologerType}}</td>
  <td>{{$row->user ? $row->user->name : 'User' }}</td>
  <td>
    @if(adminCan($moduleKey, 'update'))
      <a href="{{route('admin.astrologer.review.edit', [$astrologerType, $row->id])}}" class="text-primary">
        {!! substr($row->comment,0,50) !!}
      </a>
    @else
      {!! substr($row->comment,0,50) !!}
    @endif
  </td>
  <td>{{$row->rating}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right text-nowrap">
    @if(adminCan($moduleKey, 'update'))
      <a href="{{route('admin.astrologer.review.edit', [$astrologerType, $row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
    @endif
      {{--
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
  <td class="text-center" colspan="13"><p>No Records</p></td>
</tr>
@endif
