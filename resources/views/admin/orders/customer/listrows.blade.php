@if($results->count())
  @foreach($results as $index=>$row)
    <tr>
      {{--<td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>--}}
      <td>
        @if($image=$row->image)
          <img src="{{$image->image(75,75)}}" alt ="image">
        @else
          <img class="border border-1 border-dark" src="{{no_image()}}" width="75" height="75" alt ="image">
        @endif
      </td>
      <td>
        @if(adminCan('customer', 'update'))
          <a href="{{route('admin.customer.edit', [$row->id])}}" class="text-primary" target="_blank">
            {{ $row->name }}
          </a>
        @else
          {{ $row->name }}
        @endif
      </td>
      <td scope="row">{{$row->email}}</td>
      <td scope="row">{{$row->phone}}</td>
      @if($row->id == $new_user_id)
        <td>
          <i class="fa fa-trash text-danger pointer" onclick="handleRemoveUser(this)"></i>
        </td>
      @else
        @php
          $createOrderUrl=route('admin.order.create',['user_id'=>$row->id]);
        @endphp
        @if($old_user_id>0)
          @php
            $createOrderUrl=route('admin.order.create',['user_id'=>$row->id,'old_user_id'=>$old_user_id]);
          @endphp
        @endif
        <td>
          <a href="{{ $createOrderUrl }}">
            <i class="fas fa-check-square text-success"></i>
          </a>
        </td>
      @endif
    </tr>
  @endforeach
@else
  <tr>
    <td class="text-center" colspan="5"><p>No Records</p></td>
  </tr>
@endif
