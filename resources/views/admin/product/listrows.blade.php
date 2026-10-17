@if($products->count())
@foreach($products as $key => $product)
<tr>
  <td> <input type="checkbox" name="" value=""> </td>
  <td scope="row">{{$key+1+(($products->currentPage()-1)*$products->perPage())}}</td>
  <td>
    <a href="javascript:void(0)" data-img="{{$product->image ? $product->image->image(800,800) : ''}}" class="img-view">
        <img src="{{$product->image ? $product->image->image(75,75) : ''}}" alt="{{$product->title}}" style="max-width: 50%;">
    </a>
  </td>
  <td>
    @if(adminCan('product', 'update'))
      <a href="{{ $product->parent_id ? route('admin.product.variation.edit', [$product->id, $product->parent_id, $productTypeName]) : route('admin.product.edit', [$product->id, $productTypeName]) }}" class="text-primary">
        {{ $product->title }}
      </a>
    @else
      {{ $product->title }}
    @endif
  </td>
  @php
    $productAndCategoryIds = $product->productAndCategoryIds;
    $productAndCategoryIdsCount=$productAndCategoryIds->count();
    $productAndCategoryId=$productAndCategoryIds->first();
    $category=null;
    if($productAndCategoryId){
      $category=$productAndCategoryId->category;
    }
  @endphp
  <td class="pointer categories" style="position:relative" onmouseover="handleMouseOver(this)" onmouseout="handleMouseOut(this)">
    {{$category?$category->name:''}}{{ $productAndCategoryIdsCount>1?", +".($productAndCategoryIdsCount-1):"" }}
    @if($productAndCategoryIdsCount>1)
      <i class="fas fa-info-circle fs-1"></i>
      <div class="bg-primary text-white p-2 allcategoriesname d-none " style="position:absolute;top:0%;right:0%">
        @foreach($productAndCategoryIds as $index=>$item)
          @php
            $cateogry=$item->category;
          @endphp
          @if($index==$productAndCategoryIdsCount-1)
            {{ $cateogry?$cateogry->name:"" }}
          @else
            {{ $cateogry?$cateogry->name.", ":", " }}
          @endif
        @endforeach
      </div>
    @endif
  </td>
  <td>Rs. {{number_format($product->base_price,0)}}</td>
  <td>
    <span class="noneditable pointer">
      <span class="order">{{$product->order}}</span> <i class="icon-pencil icon" onclick="handleEditOrder(this)"></i>
    </span>
    <span class="editable w-25 d-none">
      <span class="d-flex align-items-center">
        <input type="number" name="order" value="{{ $product->order }}" class="form-control">
        <i class="bi bi-floppy bg-primary text-white mx-1 p-1" onclick="handleChangeOrder(this,'{{$product->id}}')"></i>
      </span>
    </span>
  </td>
    <td>{!! statusBadge($product->status) !!}</td>
    @if($product->type != 3)
      <td>
        @if($product->has_variation)
          @if($product->parent_id==0)
            @if(adminCan('product', 'update'))
              <a href="{{route('admin.product.variation.index', [$product->id, $productTypeName])}}">
                Products {{$product->variations->count()}} <i class="icon-pencil icon"></i>
              </a>
            @else
              Products {{$product->variations->count()}}
            @endif
          @endif
          @foreach($product->attributes as $key => $value)
            <div> <strong>{{$value->attribute->name}}:</strong> {{$value->attribute_value}}</div>
          @endforeach
        @endif
      </td>
    @endif
    <td>{{ISTDatetimeFormat($product->updated_at)}}</td>
  <td class="text-nowrap">
    @if($product->status && false)
    <a href="{{route('product.handle', [$product->slug])}}" class="text-primary" target="_blank">
      <i class="fa fa-eye"></i>
    </a>
    @endif
    @if(adminCan('product', 'update'))
    |
    <a href="{{$product->parent_id ? route('admin.product.variation.edit', [$product->id, $product->parent_id, $productTypeName]) : route('admin.product.edit', [$product->id, $productTypeName])}}" class="text-success">
      <i class="fa fa-edit"></i>
    </a>
    @endif
    @if(adminCan('product', 'add'))
    |
    <a href="javascript:void(0)"  onclick="cloneRecord( '{{$product->parent_id ? route('admin.product.variation.copy', [$product->id, $product->parent_id]) : route('admin.product.copy', [$product->id])}}')" class="text-secondary">
      <i class="fa fa-copy"></i>
    </a>
    @endif
    @if(adminCan('product', 'delete'))
    |
    <a href="javascript:void(0)" class="text-danger"  onclick="deleteRecord({{$product->id}})">
      <i class="fa fa-trash"></i>
    </a>
    @endif
  </td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="13"><p>No Records</p></td>
</tr>
@endif
