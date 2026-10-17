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
      @php
        $title = $row->title;
        $productAttributes = $row->attributes;
        $productAttributeArray = [];
        foreach($productAttributes as $key => $valueTwo){
          if($attribute = $valueTwo->attribute){
            $productAttributeArray[] = "$attribute->name: $valueTwo->attribute_value";
          }
        }
        if(count($productAttributeArray)){
          $title = implode(', ', $productAttributeArray);
          $title="$row->title ($title)";
        }
      @endphp
      <td>
        @if(adminCan('product', 'update'))
          <a href="{{ route('admin.product.edit', [$row->id, $productTypeName]) }}" class="text-primary" target="_blank">
            {{ $title }}
          </a>
        @else
          {{ $title }}
        @endif
      </td>
      @if(in_array($row->id,$productsIdArray))
        <td>
          <i class="fa fa-trash text-danger pointer" onclick="handleRemoveProduct(this,'{{ $row->id }}')"></i>
        </td>
      @else
        <td>
          <i class="fa fa-plus text-success pointer" onclick="handleAddProduct(this,'{{ $row->id }}')"></i>
        </td>
      @endif
    </tr>
  @endforeach
@else
  <tr>
    <td class="text-center" colspan="5"><p>No Records</p></td>
  </tr>
@endif
