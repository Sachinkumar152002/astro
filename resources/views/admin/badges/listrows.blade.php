@if($badges->count())
@foreach($badges as $index=>$badge)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($badges->currentPage()-1)*$badges->perPage())}}</td>
  <td>
    @if(adminCan('badge', 'update'))
    <a href="{{route('admin.badge.edit', [$badge->id])}}">
      {{$badge->name}}
    </a>
    @else
      {{$badge->name}}
    @endif
  </td>
  <td>
    @if($badge->status == 1)
      <span class="badge bg-success">Active</span>
    @else
      <span class="badge bg-danger">Inactive</span>
    @endif
  </td>
  <td>{{$badge->updated_at ? $badge->updated_at->format('d-m-Y H:i') : $badge->created_at->format('d-m-Y H:i')}}</td>

  <td class="text-right">
    @if(adminCan('badge', 'update'))
      <a href="{{route('admin.badge.edit', [$badge->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('badge', 'add'))
      |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.badge.copy', [$badge->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a>
      @endif
      @if(adminCan('badge', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$badge->id}})">
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











