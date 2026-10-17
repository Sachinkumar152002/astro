@if($results->count())
@foreach($results as $key=>$row)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$key+1+(($results->currentPage()-1)*$results->perPage())}}</td>
  @php
    $product = $row->previews;
    $user = $row->user;
  @endphp
  <td>{{$product ? $product->title : $productTypeName}}</td>
  <td>{{$user ? $user->name : 'User' }}</td>
  <td>
    @if(adminCan($moduleKey, 'update'))
      <a href="{{route('admin.product.review.edit', [$row->id, 'productTypeName' => $productTypeName])}}" class="text-primary">
        {!! substr($row->review,0,50) !!}
      </a>
    @else
      {!! substr($row->review,0,50) !!}
    @endif
  </td>
  <td>{{$row->rating}}</td>
  <td>{!! statusBadge($row->status) !!}</td>
  <td>{{ISTDatetimeFormat($row->updated_at)}}</td>
  <td class="text-right text-nowrap">
    @if(adminCan($moduleKey, 'update'))
      <a href="{{route('admin.product.review.edit', [$row->id, 'productTypeName' => $productTypeName])}}" class="text-success">
        <i class="fa fa-edit"></i>
      </a>
    @endif
    {{--
    @if(adminCan($moduleKey, 'delete'))
    |
    <a href="javascript:void(0)" class="text-danger"  onclick="deleteCategory({{$row->id}})">
      <i class="fa fa-trash"></i>
    </a>
    @endif
    --}}
  </td>

</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="13"><p>No Records</p></td>
</tr>
@endif
