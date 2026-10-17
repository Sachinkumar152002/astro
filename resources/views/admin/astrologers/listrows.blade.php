@php
  $activeScopeList = ($activeDashboardScope ?? false) || ($activeAiDashboardScope ?? false) || ($newRequestsScope ?? false);
@endphp
@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <!-- <td> <input type="checkbox" name="" value=""> </td> -->
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td >
    @if($row->image_id && $row->image)
    <a href="javascript:void(0)" data-img="{{$row->image->image(400,400)}}" class="img-view">
        <img src="{{$row->image->image(100,100)}}" alt="{{$row->display_name}}" class="img-fluid">
    </a>
    @else
      <img src="{{no_image()}}" width="100" alt="{{$row->name}}" class="img-fluid">
    @endif
    @if($row->chat == 3 || $row->call == 3 || $row->video == 3)
      <i style="position:absolute" class="text-danger fa fa-circle" aria-hidden="true"></i>
    @elseif($row->chat == 1 || $row->call == 1 || $row->video == 1)
      <i style="position:absolute" class="text-success fa fa-circle" aria-hidden="true"></i>
    @elseif($row->chat==0 && $row->call==0 && $row->video == 0)
    @else
      <i style="position:absolute" class="fa fa-circle" aria-hidden="true"></i>
    @endif
  </td>
  <td>{{$row->uuid}}</td>
  <td class="{{$row->is_delete_requested && is_null($row->delted_at) ? 'blink-text' : ''}}">
    @if(adminCan($moduleKey, 'update'))
    <a href="{{route('admin.'.$astrologerType.'.edit', [$row->id])}}">
      {{$row->display_name}}
    </a>
    @else
      {{$row->display_name}}
    @endif
  </td>
  <td class="{{ $astrologerType=='AI'?'d-none':'' }}">{{$row->phone}}</td>
  <td class="{{ $astrologerType=='AI'?'d-none':'' }}">{{$row->email}}</td>
  @if($payoutPackage=$row->payoutPackage)
    <td class="{{ $astrologerType=='AI'?'d-none':'' }}"><a href="{{route('admin.payoutpackages.edit', [$payoutPackage->id])}}">{{$payoutPackage->name}}</a></td>
  @else
    <td class="{{ $astrologerType=='AI'?'d-none':'' }}">-</td>
  @endif
  @if(!$activeScopeList)
  <td class="{{ $astrologerType=='AI'?'d-none':'' }}">
    @if(adminCan($moduleKey, 'update'))
      <a href="{{route('admin.notification.index', [0, $row->id])}}" title="Lms">{{ $row->notifications->count() }} <i class="icon-pencil icon"></i></a>
    @else
      {{$row->notifications->count()}}
    @endif
  </td>
  @if(adminCan('withdraw','any'))
    <td class="{{ $astrologerType=='AI'?'d-none':'' }}">
      <a href="{{route('admin.withdraw.add', ['astrologer_id' => $row->id])}}" title="Withdraw"><i class="icon-plus icon"></i></a>
    </td>
  @endif
  @endif
  <td>{!! statusBadge($row->status) !!}</td>
<td class="{{ $astrologerType=='AI' ? 'd-none' : '' }}"> <span class="badge bg-success">{{yesNoArray($row->is_verified)}}</span> </td>
  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right text-nowrap">
    @if(false)
    <a href="{{route('astrologers.publicview', [$row->slug])}}" class="text-primary">
      <i class="fa fa-eye"></i>
    </a>
    |
    @endif
    @if(adminCan($moduleKey, 'update'))
    <a href="{{route('admin.'.$astrologerType.'.edit', [$row->id])}}" class="text-success">
      <i class="fa fa-edit"></i>
    </a>
    {{--
    |
    <a href="{{route('admin.'.$astrologerType.'.switch', [$row->id])}}" class="text-primary">
      <i class="fas fa-random"></i>
    </a>
    --}}
    @endif
    @if(adminCan($moduleKey, 'add') && !$activeScopeList)
    |
    <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.'.$astrologerType.'.copy', [$row->id])}}')" class="text-seconday">
      <i class="fa fa-copy"></i>
    </a>
    @endif
    @if(adminCan($moduleKey, 'delete') && !$activeScopeList)
    |
    <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{ $row->id }})">
      <i class="fa fa-trash"></i>
    </a>
    @endif
    @if($trashed)
      | <a href="{{route('admin.'.$astrologerType.'.restore', [$row->id])}}" class="text-primary">
        <i class="fa fa-undo"></i>
      </a>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="{{ $activeScopeList ? 11 : 10 }}"><p>No Records</p></td>
</tr>
@endif
