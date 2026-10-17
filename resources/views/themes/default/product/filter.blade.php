<div class="main-filter-box">
  <fieldset class="pb-3 mb-3">
    <div class="p-3 pt-0">
      <h6 class="fw-semibold m-0 p-0">Price Range</h6>
    </div>
    <div class="p-3 pb-1">
      <select class="form-select form-control" id="price_range" name="price_range">
        <option value="all" {{ ($priceRangeKey ?? 'all') == 'all' ? 'selected' : '' }}>All</option>
        <option value="1000-2000" {{ ($priceRangeKey ?? '') == '1000-2000' ? 'selected' : '' }}>₹1000 - ₹2000</option>
        <option value="2001-4000" {{ ($priceRangeKey ?? '') == '2001-4000' ? 'selected' : '' }}>₹2001 - ₹4000</option>
        <option value="4001-6000" {{ ($priceRangeKey ?? '') == '4001-6000' ? 'selected' : '' }}>₹4001 - ₹6000</option>
        <option value="6001-10000" {{ ($priceRangeKey ?? '') == '6001-10000' ? 'selected' : '' }}>₹6001 - ₹10000</option>
      </select>
    </div>
  </fieldset>

  @if(isset($categories) && count($categories))
  <fieldset class="py-3 p-3 pt-1">
    <h5 class="fs-6 text fw-semibold">Categories</h5>
    <select class="form-select form-control" id="category_slug" name="category_slug">
      <option value="all" {{ $slug == "all" ? 'selected' : '' }}>All</option>
      @foreach($categories as $key => $item)
        <option value="{{ $item->slug }}" {{ $slug == $item->slug ? 'selected' : '' }}>{{ $item->name }}</option>
      @endforeach
    </select>
  </fieldset>
  @endif

  <fieldset class="py-3 p-3 d-none">
    <h5 class="fs-6 text fw-semibold">Type</h5>
    <select class="form-select form-control" id="filter_type" name="type">
      @foreach(productype() as $key => $item)
        <option value="{{ $key }}" {{ $type == $key ? 'selected' : '' }}>{{ productTypeName($item) }}</option>
      @endforeach
    </select>
  </fieldset>

  <fieldset>
    @if(isset($bestSellerProducts) && count($bestSellerProducts))
      <div class="pb-3">
        <div class="f-heading-main p-3">
          <h5 class="ms-2 mb-0">Best Sellers</h5>
        </div>
        @foreach($bestSellerProducts as $key => $bestProduct)
          <div class="seller-box px-3 my-3">
            <a href="{{ route('product.handle', [$bestProduct->slug]) }}">
              <div class="row pb-3 d-flex m-0 align-items-centre link link__0 border-bottom">
                <div class="col-md-6 col-6">
                  <div class="card border-0">
                    <img src="{{ $bestProduct->image ? $bestProduct->image->image(256, 256) : '' }}" alt="{{ $bestProduct->title }}" class="img-fluid">
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="m-0 p-0">
                    <h5 class="fs-6 mb-0 pb-0">{{ $bestProduct->title }}</h5>
                    <div class="">
                      @if($bestProduct->sale_price && (date('Y-m-d') >= $bestProduct->sale_price_start_date) && (date('Y-m-d') <= $bestProduct->sale_price_end_date))
                        <p class="fs-7 text fw-semibold mb-0 pb-0 sale_price">₹{{ $bestProduct->sale_price }}</p>
                      @else
                        <p class="fs-7 text fw-semibold mb-0 pb-0 sale_price">₹{{ $bestProduct->base_price }}</p>
                      @endif
                    </div>
                    <ul class="p-0 m-0 start_rating">
                      <li class="list-type-group d-flex align-col-lg-4s-center">
                        @for($i = rand(4, 5); $i > 0; $i--)
                          <i class="bi text-rating bi-star-fill"></i>
                        @endfor
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    @endif
  </fieldset>
</div>
