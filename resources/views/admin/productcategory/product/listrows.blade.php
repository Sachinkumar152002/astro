@if($results->count())
  @foreach($results as $index=>$row)
    <tr>
      <td scope="row">{{$index+1+(($results->currentPage()-1)*$results->perPage())}}</td>
      <td>
        @if($image=$row->image)
          <img src="{{$image->image(75,75)}}" alt ="image">
        @else
          <img class="border border-1 border-dark" src="{{no_image()}}" width="75" height="75" alt ="image">
        @endif
      </td>
      <td>
        @if(adminCan('product', 'update'))
          <a href="{{ route('admin.product.edit', [$row->id, $productTypeName]) }}" class="text-primary" target="_blank">
            {{ $row->title }}
          </a>
        @else
          {{ $row->title }}
        @endif
      </td>
      <td scope="row">{!! statusBadge($row->status) !!}</td>
      @php
        $productAndCategoryId=$row->productAndCategoryIds->where("category_id",$category_id)->first();
      @endphp
      @if($productAndCategoryId)
        <td>
          <input type="number" class="form-control" name="order" value="{{ $productAndCategoryId->order }}">
        </td>
        <td>
          <i class="bi bi-floppy mx-1 p-1 bg-primary text-white pointer" onclick="handleSelectProduct(this,'add','{{ $row->id }}')"></i>
          |
          <i class="fa fa-trash text-danger pointer" onclick="handleSelectProduct(this,'delete','{{ $row->id }}')"></i>
        </td>
      @else
        <td>
          <input type="number" class="form-control" name="order">
        </td>
        <td>
          <i class="fa fa-plus text-success pointer" onclick="handleSelectProduct(this,'add','{{ $row->id }}')"></i>
        </td>
      @endif
    </tr>
  @endforeach
@else
  <tr>
    <td class="text-center" colspan="5"><p>No Records</p></td>
  </tr>
@endif
