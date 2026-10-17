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
          <a href="{{ route('admin.product.edit', [$row->id, $productTypeName]) }}" class="text-primary">
            {{ $row->title }}
          </a>
        @else
          {{ $row->title }}
        @endif
      </td>
      @php
        $productAndCategoryId=$row->productAndCategoryIds->where("category_id",$category_id)
      @endphp
      @if($productAndCategoryId)
        <td>
          <input type="number" name="order" value="{{ $productAndCategoryId->order }}">
        </td>
        <td>
          <input type="checkbox" name="selected" value="1" checked onchange="handleSelectProduct(this)">
        </td>
      @else
        <td>
          <input type="number" name="order">
        </td>
        <td>
          <input type="checkbox" name="selected" value="1" onchange="handleSelectProduct(this)">
        </td>
      @endif
    </tr>
  @endforeach
@else
  <tr>
    <td class="text-center" colspan="5"><p>No Records</p></td>
  </tr>
@endif
