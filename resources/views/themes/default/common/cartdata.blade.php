@foreach ($cartItems as $item)
        <div class="card border-0 border-bottom py-3">
        <a class="text-dark" href="{{ route('remove.cart', $item->id) }}">  <button type="button" class="btn btn-close rounded-circle" name="button"></button></a>
            <div class="row align-items-center mt-3">
                <div class="col-sm-2 col-6 mb-2">
                    <img src="{{$item && $item->image ? $item->image->image(250, 250) : ''}}" alt="{{ $item->product->title }}" class="img-thumbnail"> <!-- Display product image -->
                </div>
                <div class="col-lg-10 col-6">
                    <div class="row">
                        <div class="col-sm-5 col-12 d-flex flex-column">
                            <span class="text-gold fs-5 text">{{ $item->product->title }}</span>
                        </div>
                        <div class="col-sm-2 col-12 text-center">
                            <span class="fw-semibold"><i class="bi text-dark bi-currency-rupee"></i>{{ $item->product_price }}</span>
                        </div>
                        <div class="col-sm-3 col-12 text-center">
                            <div class="input-group add_number">
                                <button class="btn btn-outline-secondary text-center" type="button" id="button-minus">-</button>
                                <input type="text" class="form-control text-center" id="quantity" value="{{ $item->product_quantity }}" min="1">
                                <button class="btn btn-outline-secondary text-center" type="button" id="button-plus">+</button>
                            </div>
                        </div>
                        <div class="col-sm-2 col-12 text-center">
                            <span class="fw-bold"><i class="bi text-dark bi-currency-rupee"></i>{{ $item->product_total_price }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
