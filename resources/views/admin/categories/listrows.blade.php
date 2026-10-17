@if($results->count())
@foreach($results as $index=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>
    @if(adminCan('blog-category', 'update'))
    <a href="{{route('admin.category.edit', [$lang,$row->id])}}">
    {{$row->name}}
    </a>
    @else
    {{$row->name}}
    @endif
  </td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{$row->parent ? $row->parent->name: ''}}</td>
  <td>{{$row->updated_at != $row->created_at ? $row->updated_at : $row->created_at}}</td>

  <td class="text-right">
    @if(adminCan('blog-category', 'update'))
    <a href="{{route('admin.category.edit', [$lang,$row->id])}}" class="text-success">
      <i class="fa fa-edit"></i>
    </a>
    @endif
    @if(adminCan('blog-category', 'add'))
    |
    <a href="javascript:void(0)"  onclick="cloneRecord('{{route('admin.category.copy', [$lang,$row->id])}}')" class="text-secondary">
      <i class="fa fa-copy"></i>
    </a>
    @endif
    @if(adminCan('blog-category', 'delete'))
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
  <td class="text-center" colspan="7"><p>No Records</p></td>
</tr>
@endif
