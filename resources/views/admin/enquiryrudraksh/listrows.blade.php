@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <th scope="row"> <input type="checkbox" name="" value=""> </th>
  <th>{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</th>
  <td>
    @if(adminCan($nav, 'update'))
    <a href="{{route('admin.'.$nav.'.view', [$row->id])}}" class="text-primary">
    {{$row->fullName}}
    </a>
    @else
    {{$row->fullName}}
    @endif
  </td>
  <td>{{$row->email}}</td>
  <td>{{$row->mobile}}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right">
      @if(adminCan($nav, 'update'))
      <a href="{{route('admin.'.$nav.'.view', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
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
  <td class="text-center" colspan="8"><p>No Records</p></td>
</tr>
@endif
