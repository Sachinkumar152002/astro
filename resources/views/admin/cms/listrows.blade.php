@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('cms', 'update'))
      <a href="{{route('admin.cms.edit', [$row->id])}}" class="text-primary">
        {{$row->name}}
      </a>
    @else
      {{$row->name}}
    @endif
  </td>
  <td>{{$row->slug}}</td>
  <td>{{pageTemplates($row->template)}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td class="text-center">
    @if($row->display_in_user_app ?? 0)<span class="badge badge-success">User App</span>@endif
    @if($row->display_in_customer_app ?? 0)<span class="badge badge-info">Customer App</span>@endif
    @if(!($row->display_in_user_app ?? 0) && !($row->display_in_customer_app ?? 0))<span class="text-muted">—</span>@endif
  </td>
  <td>{{$row->created_at != $row->updated_at ? $row->updated_at : $row->updated_at}}</td>
  <td class="text-right">
      {{--
      <a href="{{route('pages', [$row->slug])}}" class="text-primary" target="_blank">
          <i class="fa fa-eye"></i>
      </a>
      |
      --}}
      @if(adminCan('cms', 'update'))
      <a href="{{route('admin.cms.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('cms', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.cms.copy', [$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('cms', 'delete'))
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
  <td class="text-center" colspan="8"><p>No Records</p></td>
</tr>
@endif
