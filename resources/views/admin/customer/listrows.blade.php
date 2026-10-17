@if($results->count())
@foreach($results as $index=>$row)
<tr>
    <td scope="row"><input type="checkbox" name="" value=""> </td>
  <td>{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  <td>{{$row->uuid}}</td>
  <td>
    @if(adminCan('customer', 'add'))
    <a class="text-primary" href="{{route('admin.customer.edit', [$row->id])}}">
      {{$row->name}}
    </a>
    @else
    {{$row->name}}
    @endif
  </td>
  <td>{{$row->phone}}</td>
  <td>{{$row->email}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  {{--
  <td>
    @if(adminCan('customer', 'update'))
    <a href="{{route('admin.customer.cartitem', [$row->id, 'type'=>'course'])}}" title="Lms">{{$row->cartItems->where('type', 'course')->count()}} <i class="icon-pencil icon"></i></a>
    @else
    {{$row->cartItems->count()}}
    @endif
  </td>
  --}}
  <td>
    @if(adminCan('customer', 'update'))
    <a href="{{route('admin.customer.cartitem', [$row->id, 'type'=>'shop'])}}" title="Lms">{{$row->cartItems->where('type', 'shop')->count()}} <i class="icon-pencil icon"></i></a>
    @else
    {{$row->cartItems->count()}}
    @endif
  </td>
  {{--
  <td>
    @if(adminCan('customer', 'update'))
      <a href="{{route('admin.customer.course', [$row->id])}}" title="Lms">{{$row->courses->count()}} <i class="icon-pencil icon"></i></a>
    @else
      {{$row->courses->count()}}
    @endif
  </td>
  --}}
  <td>
    @if(adminCan('customer', 'update'))
      <a href="{{route('admin.notification.index', [$row->id, 0])}}" title="Lms">{{$row->notifications->count()}} <i class="icon-pencil icon"></i></a>
    @else
      {{$row->notifications->count()}}
    @endif
  </td>
  <td>{{$row->created_at!=$row->updated_at ? $row->updated_at : $row->created_at}}</td>
  <td class="text-right text-nowrap">
      @if(adminCan('customer', 'update'))
      <a href="{{route('admin.customer.edit', [$row->id])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
      @endif
      @if(adminCan('customer', 'delete'))
      |
      <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$row->id}})">
        <i class="fa fa-trash"></i>
      </a>
      @endif
      {{--
      @if($trashed)
         | <a href="{{route('admin.customer.restore', [$row->id])}}" class="text-primary">
          <i class="fa fa-undo"></i>
        </a>
      @endif
      --}}
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="10"><p>No Records</p></td>
</tr>
@endif
