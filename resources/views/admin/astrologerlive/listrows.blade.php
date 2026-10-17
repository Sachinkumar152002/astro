@if($results->count())
@php
  $canastrologerupdated=adminCan("astrologer","updated");
  $canastrologerliveupdated=adminCan($module,"updated");
  $canastrologerlivedelete=adminCan($module,"delete");
  $statusArray=["scheduled"=>"Scheduled","started"=>"Joined","leaved"=>"Ended","cancelled"=>"Cancelled"];
@endphp
@foreach($results as $index=>$row)
<tr>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  @php
    $astrologer=$row->astrologer;
  @endphp
  <td>
    @if($canastrologerupdated)
      <a href="{{route('admin.Astrologer.edit', [$row->astrologer_id])}}" class="text-primary">
        {{ $astrologer?$astrologer->display_name:"Astrologer" }}
      </a>
      @else
        {{ $astrologer?$astrologer->display_name:"Astrologer" }}
      @endif
  </td>
  <td>{{ $row->astrologerLiveUser->count() }}</td>
  <td>{{ $row->start_at!=null?ISTDatetimeFormat($row->start_at):"-" }}</td>
  <td>{{ $row->joined_at!=null?ISTDatetimeFormat($row->joined_at):"-" }}</td>
  <td>{{ $row->end_at!=null?ISTDatetimeFormat($row->end_at):"" }}</td>
  <td>{{ $row->leaved_at!=null?ISTDatetimeFormat($row->leaved_at):"-" }}</td>
  <td>{{ isset($statusArray[$row->status])?$statusArray[$row->status]:"-" }}</td>

  {{--
    <td class="text-right">
    @if($row->status=="started" || $row->status=="leaved")
      @if($canastrologerlivedelete)
        <a href="{{route('admin.'.$module.'.view', [$row->id])}}" class="text-primary">
          <i class="fa fa-eye"></i>
        </a>
      @endif
    @endif
    @if($row->status=="scheduled" && $row->end_at> date("Y-m-d H:i:s"))
      @if($canastrologerliveupdated)
        |
        <a href="{{route('admin.'.$module.'.edit', [$row->id])}}" class="text-success">
          <i class="fa fa-edit"></i>
        </a>
      @endif
    @endif
    @if($row->status=="cancelled" || $row->status=="scheduled")
      @if($canastrologerlivedelete)
        |
        <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
          <i class="fa fa-trash"></i>
        </a>
      @endif
    @endif
  </td>
  --}}

</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="7"><p>No Records</p></td>
</tr>
@endif
