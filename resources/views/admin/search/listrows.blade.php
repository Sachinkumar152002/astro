@if(count($astrologers)>0 || count($ai_astrologers)>0 || count($products)>0 || count($pujas)>0 || count($services)>0)
  @php
    $canastrologeredit=adminCan('astrologer','update');
    $canaiedit=adminCan('ai','update');
    $canproductedit=adminCan('product','update');
    $canpujaedit=adminCan('puja','update');
    $canserviceedit=adminCan('service','update');
  @endphp
  @if(count($astrologers)>0)
    <tr>
      <td colspan="2">Astrologers</td>
    </tr>
    @foreach($astrologers as $index=>$item)
      @php
        $image=$item->image;
      @endphp
      <tr>
        <td>
          <img src="{{ $image?$image->image(100,100):no_image() }}" class="img-fluid">
        </td>
        <td>
          @if($canastrologeredit)
            <a href="{{ route('admin.Astrologer.edit',[$item->id]) }}" class="text-primary">
              {{ $item->display_name }}
            </a>
          @else
            {{ $item->display_name }}
          @endif
        </td>
      </tr>
    @endforeach
  @endif
  @if(count($ai_astrologers)>0)
    <tr>
      <td colspan="2">Ai Astrologers</td>
    </tr>
    @foreach($ai_astrologers as $index=>$item)
      @php
        $image=$item->image;
      @endphp
      <tr>
        <td>
          <img src="{{ $image?$image->image(100,100):no_image() }}" class="img-fluid">
        </td>
        <td>
          @if($canaiedit)
            <a href="{{ route('admin.AI.edit',[$item->id]) }}" class="text-primary">
              {{ $item->display_name }}
            </a>
          @else
            {{ $item->display_name }}
          @endif
        </td>
      </tr>
    @endforeach
  @endif
  @if(count($products)>0)
    <tr>
      <td colspan="2">Products</td>
    </tr>
    @foreach($products as $index=>$item)
      @php
        $image=$item->image;
      @endphp
      <tr>
        <td>
          <img src="{{ $image?$image->image(100,100):no_image() }}" class="img-fluid">
        </td>
        <td>
          @if($canproductedit)
            <a href="{{ route('admin.product.edit', [$item->id, 'Product']) }}" class="text-primary">
              {{ $item->title }}
            </a>
          @else
            {{ $item->title }}
          @endif
        </td>
      </tr>
    @endforeach
  @endif
  @if(count($pujas)>0)
    <tr>
      <td colspan="2">Pujas</td>
    </tr>
    @foreach($pujas as $index=>$item)
      @php
        $image=$item->image;
      @endphp
      <tr>
        <td>
          <img src="{{ $image?$image->image(100,100):no_image() }}" class="img-fluid">
        </td>
        <td>
          @if($canpujaedit)
            <a href="{{ route('admin.product.edit', [$item->id, 'Pooja']) }}" class="text-primary">
              {{ $item->title }}
            </a>
          @else
            {{ $item->title }}
          @endif
        </td>
      </tr>
    @endforeach
  @endif
  @if(count($services)>0)
    <tr>
      <td colspan="2">Services</td>
    </tr>
    @foreach($services as $index=>$item)
      @php
        $image=$item->image;
      @endphp
      <tr>
        <td>
          <img src="{{ $image?$image->image(100,100):no_image() }}" class="img-fluid">
        </td>
        <td>
          @if($canserviceedit)
            <a href="{{ route('admin.product.edit', [$item->id, 'Service']) }}" class="text-primary">
              {{ $item->title }}
            </a>
          @else
            {{ $item->title }}
          @endif
        </td>
      </tr>
    @endforeach
  @endif
@else
  <tr>
    <td class="text-center" colspan="2"><p>No Records</p></td>
  </tr>
@endif
