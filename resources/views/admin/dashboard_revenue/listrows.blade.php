@if($items->count())
@foreach($items as $key => $row)
@php
  $product = $row->product;
  $order = $row->order;
@endphp
<tr>
  <td>
    <a href="javascript:void(0)" data-img="{{ $product && $product->image ? $product->image->image(800, 800) : '' }}" class="img-view">
      <img src="{{ $product && $product->image ? $product->image->image(75, 75) : no_image() }}" alt="{{ $product ? $product->title : '' }}" style="max-width: 56px;">
    </a>
  </td>
  <td>{{ $product ? $product->title : '—' }}</td>
  <td>{{ $row->total_price }}</td>
  <td>{{ $order ? ISTDatetimeFormat($order->created_at) : '—' }}</td>
</tr>
@endforeach
@else
<tr>
  <td class="text-center" colspan="4"><p>No Records</p></td>
</tr>
@endif
