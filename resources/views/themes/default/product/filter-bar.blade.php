{{-- Horizontal filter bar - Premium Redesign --}}
<style>
  .product-filter-bar .filter-bar-item {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px !important;
    padding: 6px 14px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    transition: all 0.3s ease;
  }
  .product-filter-bar .filter-bar-item:hover,
  .product-filter-bar .filter-bar-item:focus-within {
    border-color: #f59e0b;
    box-shadow: 0 4px 6px rgba(245, 158, 11, 0.1);
  }
  .product-filter-bar .form-control:focus, 
  .product-filter-bar .form-select:focus {
    box-shadow: none !important;
    outline: none !important;
  }
</style>

<div class="product-filter-bar mb-4">
  <form method="get" action="" class="js-product-filter-form d-flex flex-wrap gap-3 align-items-center" id="product-filter-form">
    
    <div class="filter-bar-item filter-product-search position-relative">
      <input type="text" class="form-control border-0 bg-transparent js-product-search-input" name="product_name" placeholder="Search product..." value="{{ $productSearchQuery ?? '' }}" autocomplete="off" aria-label="Search product">
      <div class="product-search-suggestions dropdown-menu w-100 shadow-sm" id="product-search-suggestions" style="max-height: 220px; overflow-y: auto; display: none;"></div>
    </div>
    
    @if(isset($categories) && count($categories))
    <div class="filter-bar-item filter-categories">
      <select class="form-select border-0 bg-transparent js-category-slug" name="category_slug" aria-label="Categories">
        <option value="all" {{ $slug == "all" ? 'selected' : '' }}>All Categories</option>
        @foreach($categories as $item)
          <option value="{{ $item->slug }}" {{ $slug == $item->slug ? 'selected' : '' }}>{{ $item->name }}</option>
        @endforeach
      </select>
    </div>
    @endif
    
    <div class="filter-bar-item filter-price">
      <select class="form-select border-0 bg-transparent js-price-range" name="price_range" aria-label="Price">
        <option value="all" {{ ($priceRangeKey ?? 'all') == 'all' ? 'selected' : '' }}>Any Price</option>
        <option value="1000-2000" {{ ($priceRangeKey ?? '') == '1000-2000' ? 'selected' : '' }}>&#8377;1000 - &#8377;2000</option>
        <option value="2001-4000" {{ ($priceRangeKey ?? '') == '2001-4000' ? 'selected' : '' }}>&#8377;2001 - &#8377;4000</option>
        <option value="4001-6000" {{ ($priceRangeKey ?? '') == '4001-6000' ? 'selected' : '' }}>&#8377;4001 - &#8377;6000</option>
        <option value="6001-10000" {{ ($priceRangeKey ?? '') == '6001-10000' ? 'selected' : '' }}>&#8377;6001 - &#8377;10000</option>
      </select>
    </div>
    
    <div class="filter-bar-item filter-rating">
      <select class="form-select border-0 bg-transparent js-rating" name="rating" aria-label="Rating">
        <option value="all" {{ ($ratingFilter ?? 'all') == 'all' ? 'selected' : '' }}>Any Rating</option>
        <option value="1" {{ ($ratingFilter ?? '') == '1' ? 'selected' : '' }}>1+ Star</option>
        <option value="2" {{ ($ratingFilter ?? '') == '2' ? 'selected' : '' }}>2+ Stars</option>
        <option value="3" {{ ($ratingFilter ?? '') == '3' ? 'selected' : '' }}>3+ Stars</option>
        <option value="4" {{ ($ratingFilter ?? '') == '4' ? 'selected' : '' }}>4+ Stars</option>
        <option value="5" {{ ($ratingFilter ?? '') == '5' ? 'selected' : '' }}>5 Stars</option>
      </select>
    </div>
    
  </form>
</div>