@extends(themeView() . 'layout')
@section('content')
<!-- <section class="home-banner h-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 d-flex justify-content-center align-items-center h-100 p-5">
                    <h2 class="text-white fw-bold">Cart</h2>
                </div>
            </div>
        </div>
</section> -->
<section>
        <div class="container product-details">
            <div class="row">
                <div class="col-lg-7 col-12">
                    <div class="row border-bottom py-3 flex-nowrap">
                        <div class="col-sm-6 col-4">
                            <h5 class="text-center fs-6 fw-bold">Products</h5>
                        </div>
                        <div class="col-sm-2 col-2">
                            <h5 class="text-center fs-6 fw-bold">Price</h5>
                        </div>
                        <div class="col-sm-2 col-3">
                            <h5 class="text-center fs-6 fw-bold">Quantity</h5>
                        </div>
                        <div class="col-sm-2 col-3">
                            <h5 class="text-center fs-6 fw-bold">Total</h5>
                        </div>
                    </div>
                    <div id="cartdata">
                    </div>


                </div>
                <div class="col-lg-5 col-12 bg-light p-5">
                    <table class="table bg-transparent">
                        <tbody>
                            <tr class="table-light">
                                <td>Subtotal</td>
                                <th class="text-end" scope="row"><i class="bi text-dark bi-currency-rupee"></i><span id="totalPrice"></span>
                                </th>
                            </tr>
                            <tr class="table-light">
                                <td>Shipping</td>
                                <th class="text-end" scope="row"><i class="bi text-dark bi-currency-rupee"></i><span id="shippingCharges"></span>
                                </th>
                            </tr>

                            <tr class="table-light">
                                <th>Total</th>
                                <th class="text-end" scope="row"><i class="bi text-dark bi-currency-rupee"></i><span id="finalTotalPrice"></span>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-12 col">
                        <button class="btn btn-cart" onclick="validateAndCheckOut('{{route('checkout')}}')">Proceed to checkout</button>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @endsection
    @push('scripts')
<script type="text/javascript">
$(document).ready(function() {
var userId = "{{ \Session::get('cart_user_id') }}"; // Get user ID from Blade
fetchCartData()
    // Make an AJAX request to fetch the cart data
      function fetchCartData() {
    $.ajax({
        type: 'POST',
        url: '/user/cart/' + userId, // Updated URL
        data: {
            // Include any necessary CSRF token if needed
            _token: '{{ csrf_token() }}',
        },
        success: function(response) {
            // Check if the response has HTML data
            if (response.html) {
                // Append the HTML data to the cartdata div
                $('#cartdata').html(response.html);
                $('#totalPrice').html(response.totalPrice);
                $('#shippingCharges').html(response.shippingCharges);
                $('#discount').html(response.discount);
                $('#finalTotalPrice').html(response.finalTotalPrice);
            } else {
                $('#cartdata').html('<p>Your cart is empty.</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching cart data:', error);
        }
    });
}

    $(document).on('click', '.increase-quantity', function() {
    var cartItemId = $(this).data('id');

    $.ajax({
        type: 'POST',
        url: '/cart/increase-quantity/' + cartItemId,
        data: {
            _token: '{{ csrf_token() }}' // Include CSRF token
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#quantity-' + cartItemId).val(response.cart_item.quantity);
                fetchCartData()
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error increasing quantity:', error);
        }
    });
});

$(document).on('click', '.decrease-quantity', function() {
    var cartItemId = $(this).data('id');

    $.ajax({
        type: 'POST',
        url: '/cart/decrease-quantity/' + cartItemId,
        data: {
            _token: '{{ csrf_token() }}' // Include CSRF token
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#quantity-' + cartItemId).val(response.cart_item.quantity);
                fetchCartData()
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error decreasing quantity:', error);
        }
    });
});
$(document).ready(function() {
    // Remove item from the cart
    $(document).on('click', '.remove-item', function() {
        var itemId = $(this).data('id');

        $.ajax({
            url: '/cart/remove/' + itemId,  // Assuming this is your remove route
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.status === 'success') {
                    $('#cart-item-' + itemId).remove();
                    fetchCartData()
                }
            },
            error: function(xhr) {
                alert('An error occurred while removing the item.');
            }
        });
    });
});


});

</script>
    @endpush
