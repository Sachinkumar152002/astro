@if($products->count())
  @foreach($products as $index => $product)
    @php
      $typeLabel = 'Product';
      if ($product->type == 2) $typeLabel = !empty($product->is_one_to_one_puja) ? 'One-to-One Puja' : 'Puja';
      elseif ($product->type == 3) $typeLabel = 'Report';
      $productUrl = $product->getPublicDetailUrl();
    @endphp
    <tr data-product-id="{{ $product->id }}">
      <td>{{ $index + 1 + (($products->currentPage()-1) * $products->perPage()) }}</td>
      <td>{{ $product->title }}</td>
      <td>{{ $typeLabel }}</td>
      <td class="text-center align-middle">
        <div class="form-check form-switch d-inline-block">
          <input class="form-check-input" type="checkbox" role="switch" name="show_in_astrologer_profile[]" value="1"
            id="show_astrologer_{{ $product->id }}"
            {{ $product->show_in_astrologer_profile ? 'checked' : '' }}
            data-product-id="{{ $product->id }}">
          <label class="form-check-label small" for="show_astrologer_{{ $product->id }}">
            {{ $product->show_in_astrologer_profile ? 'On' : 'Off' }}
          </label>
        </div>
      </td>
      <td class="text-center">
        <input type="number" name="astrologer_profile_sort_order[]" class="form-control form-control-sm" min="0" style="width:70px; margin:0 auto;"
          value="{{ $product->astrologer_profile_sort_order ?? '' }}"
          placeholder="Order" data-product-id="{{ $product->id }}">
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-url" data-url="{{ $productUrl }}" title="Copy URL">
          <i class="bi bi-clipboard"></i> Copy URL
        </button>
        <a href="{{ $productUrl }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Open / Share">
          <i class="bi bi-share"></i> Share
        </a>
      </td>
    </tr>
  @endforeach
@else
  <tr>
    <td colspan="6" class="text-center py-4">No products found.</td>
  </tr>
@endif
