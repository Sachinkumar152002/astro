@if($results->count())
@foreach($results as $index=>$row)
<tr >
  <th> <input type="checkbox" name="" value=""> </th>
  <th scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan('landing', 'update'))
    <a class="text-primary" href="{{route('admin.'.$module.'.edit', [$row->id])}}">
      {{$row->title}}
    </a>
    @else
    {{$row->title}}
    @endif
  </td>
  <td>{{$row->slug}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{$row->show_in_navigation?'Yes':'No'}}</td>
  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td>
    <a href="{{route('landing.page', [$row->slug])}}" target="_blank" class="text-primary">
      <i class="fa fa-eye"></i>
    </a>
    |
    @if(adminCan('landing', 'update'))
      <a href="{{route('admin.'.$module.'.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('landing', 'add'))
      {{-- |
      <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.'.$module.'.copy', [$row->id])}}')" class="text-secondary">
        <i class="fa fa-copy"></i>
      </a> --}}
      @endif
      @if(adminCan('landing', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
      @if(adminCan('landing', 'copy'))
      |
      <a href="{{route('admin.'.$module.'.copy', [$row->id])}}" class="text-success">
        <i class="fa fa-copy"></i>
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
