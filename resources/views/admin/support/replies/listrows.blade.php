@if($replies->count())
@foreach($replies as $key => $value)
<tr>
  <th scope="row">{{$key+1+(($replies->currentPage()-1)*$replies->perPage())}}</th>
  <td>
    <a href="{{route('admin.support.replies.edit', [$support->id, $value->id])}}" class="text-success">
      {{strlen($value->reply)>=50?substr($value->reply, 0, 50).'...':$value->reply}}
    </a>
  </td>
  <td>{{$value->reply_type=='admin'?'Admin':'User'}}</td>
  <td>{{ISTDatetimeFormat($value->created_at)}}</td>
  <td class="text-right">
    <a href="{{route('admin.support.replies.edit', [$support->id, $value->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a> |
      {{--
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.support.replies.copy', [$support->id, $value->id])}}')" class="text-seconday">
        <i class="fa fa-copy"></i>
      </a> |
      --}}
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$value->id}})">
        <i class="fa fa-trash"></i>
      </a>
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="12"><p>No Records</p></td>
</tr>
@endif
