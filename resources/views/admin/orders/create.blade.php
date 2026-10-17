@extends('layouts.admin')
@section('content')
  <div id="profile-app">
    <div class="row">
      <div class="col-md-6">
        <h1 class="text-primary float-left">Create Order</h1>
      </div>
      <div class="col-md-6 text-right mt-2"></div>
    </div>
    <div class="clearfix"></div>
    <ul class="nav nav-tabs">
      <li class="nav-item active"><a data-toggle="tab" class="nav-link active" href="#create_order">Create</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade in active show" id="create_order">
        <div class="row">
          <div class="col-6">
            <div class="row" id="filter_row_second_temp">
              <div class="col-8">
                <div class="form-group">
                  <input type="text" name="customer_temp" id="customer_temp" class="form-control" placeholder="🔍 Search Customer" onkeyup="openUsersListModal()">
                </div>
              </div>
              <div class="col-4">
                <button type="button" class="btn btn-success" id="filter-apply-btn-second-temp" onclick="openUsersListModal()">
                  Browse
                </button>
                <button type="button" class="btn btn-success" id="add-user-btn-temp" onclick="openAddCustomUserModal()">
                  Add Custom
                </button>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="row" id="filter_row_temp">
              <div class="col-8">
                <div class="form-group">
                  <input type="text" name="product_title_temp" id="product_title_temp" class="form-control" onkeyup="openProductsListModal()" placeholder="🔍 Search Product">
                </div>
              </div>
              <div class="col-4">
                <button type="button" class="btn btn-success" id="filter-apply-btn-temp" onclick="openProductsListModal()">
                  Browse
                </button>
                <button type="button" class="btn btn-success" id="add-product-btn-temp" onclick="openAddCustomProductModal(0,'','','',0)">
                  Add Custom
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-12 pe-5">
            @php
              $shippingAddressId=0;
            @endphp
            @if($user)
            <div id="customer-detail-section">
              <h2 class="fs-20 f5">Customer Detail</h2>
              <div class="invoice-box p-2" style="position:relative;">
                <p class="mb-0">
                  Name:
                  <a href="{{route('admin.customer.edit', [$user->id])}}" class="text-primary" target="_blank">
                    {{ $user->name }}
                  </a>
                </p>
                <p class="mb-0">Phone: {{$user->phone}}</p>
                <p class="mb-0">Email: {{$user->email}}</p>
                <div class="" style="position:absolute;top:5px;right:5px">
                  <i class="far fa-times-circle pointer" onclick="handleRemoveUser(this)"></i>
                </div>
              </div>
            </div>
              <div id="shipping-address-section">
                <h2 class="fs-20 f5 mt-3">Delivery Address</h2>
                <div class="">
                  @foreach($shippingAddresses as $index=>$item)
                    @php
                      if($index==0){
                        $shippingAddressId=$item->id;
                      }
                    @endphp
                    <div class="card check-card1 mb-2" style="border-radius:10px">
                      <div class="row p-2 align-items-center">
                        <div class="col-1">
                          <input id="shippingAddress{{ $item->id }}" onclick="selectAddress(this,'{{ $item->id }}')" type="radio" {{ $index == 0 ? "checked" : "" }} name="selected_address" class="c2radio pointer" value="{{ $item->id }}" />
                        </div>
                        <div class="col-10 cart_text courses_width p-0 pointer" onclick="selectAddress('#shippingAddress{{ $item->id }}','{{ $item->id }}')">
                          <div class="card-title mb-0">
                            <h6 class="m-0">{{ $item->name }}</h6>
                          </div>
                          <div class="quantity">
                            {{$item->address}}, {{$item->city}}, {{$item->state}} - {{$item->zipcode}}
                          </div>
                        </div>
                        <div class="col-1">
                          <i class="fa fa-edit text-success pointer" onclick="handleShippingAddress(this,'edit','{{ $item->id }}','{{ $item->name }}','{{ $item->email }}','{{ $item->phone }}','{{ $item->country_id }}','{{ $item->state_id }}','{{ $item->city_id }}','{{ $item->address }}','{{ $item->zipcode }}')"></i>
                        </div>
                      </div>
                    </div>
                  @endforeach
                  <div class="col-12 m-0 p-2" style="border-radius:10px">
                    <div class="">
                      <div class="cart_text courses_width p-0">
                        <div class="card-title mb-0">
                          <h6 class="add-address m-0 pointer">
                            <a onclick="handleShippingAddress(this,'add',0,'','','',0,0,0,'','')" class="text-primary">
                              <i class="bi bi-plus-circle me-2"></i> Add a new delivery address
                            </a>
                          </h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif
            @if($user)
              <div id="billing-address-section">
                <h2 class="fs-20 f5 mt-3">Billing Address</h2>
                <div class="row">
                  <div class="form-group col-4">
                    <label>Name</label>
                    <input type="text" name="billing_name" id="billing_name" class="form-control" value="{{ $user->name }}">
                  </div>
                  <div class="form-group col-4">
                    <label>Email</label>
                    <input type="text" name="billing_email" id="billing_email" class="form-control" value="{{ $user->email }}">
                  </div>
                  <div class="form-group col-4">
                    <label>Phone</label>
                    <input type="text" name="billing_phone" id="billing_phone" class="form-control" value="{{ $user->phone }}">
                  </div>
                  <div class="form-group col-8">
                    <label>Address</label>
                    <input type="text" name="billing_address" id="billing_address" class="form-control" value="{{ $user->address }}">
                  </div>
                  <div class="form-group col-4">
                    <label>Country</label>
                    <select name="country_id" v-model="country_id" class="form-control nosearch" v-on:change="loadMaster('states')">
                      <option value="0">Select Country</option>
                      @foreach($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-4">
                    <label>State</label>
                    <select name="state_id" v-model="state_id" class="form-control nosearch" v-on:change="loadMaster('cities')">
                      <option v-for="(item, id) in master.states" :key="id" :value="id">@{{item}}</option>
                    </select>
                  </div>
                  <div class="form-group col-4">
                    <label>City</label>
                    <select name="city_id" v-model="city_id" class="form-control nosearch">
                      <option v-for="(item, id) in master.cities" :key="id" :value="id">@{{item}}</option>
                    </select>
                  </div>
                  <div class="form-group col-4">
                    <label>Pincode</label>
                    <input type="text" name="billing_pincode" id="billing_pincode" class="form-control" value="{{ $user->pincode }}">
                  </div>
                </div>
              </div>
              {{--<h2 class="fs-20 f5 mt-3">Payment Methods</h2>
              <div class="">
                <div class="" style="border-radius:10px">
                  <div class="card check-card1 pb-2">
                    <div class="">
                      <input onclick="selectPaymentMethod('cod')" type="radio" checked name="cod" class="c2radio pointer" value="cod" />
                    </div>
                    <div class="cart_text courses_width p-0 pointer" onclick="selectPaymentMethod('cod')">
                      <h6 class="m-0">Cash On Delivery</h6>
                    </div>
                  </div>
                </div>
              </div>--}}
            @endif
          </div>
          <div class="col-md-6 col-12 pe-5">
            <div class="" id="cart-section"></div>
            <div class="" id="coupon-section"></div>
            <div class="" id="amount-section">
              <table class="table total_box bg-transparent table-borderless">
                <tbody>
                  <tr class="table-light">
                    <th>Subtotal</th>
                    <th class="text-right">
                      <span>₹ 0</span>
                    </th>
                  </tr>
                  <tr class="table-light">
                    <th>Discount</th>
                    <th class="text-right">
                      <span>- ₹ 0</span>
                    </th>
                  </tr>
                  <tr class="table-light">
                    <th>GST & Taxes</th>
                    <th class="text-right">
                      <span>+ ₹ 0</span>
                    </th>
                  </tr>
                  <tr class="table-light">
                    <th>Shipping Charges</th>
                    <th class="text-right">
                      <span>+ ₹ 0</span>
                    </th>
                  </tr>
                  <tr class="table-light">
                    <th>Total</th>
                    <th class="text-right">
                      <span>₹ 0</span>
                    </th>
                  </tr>
                </tbody>
              </table>
              {{--<div class="d-flex justify-content-between">
                <div class="">
                  <button class="btn btn-primary" id="place-cod-order-btn" onclick="handlePlaceOrder(this,'cod')" type="button">Place COD Order</a>
                </div>
                <div class="">
                  <button class="btn btn-primary" id="generate-order-link-btn" onclick="generateCheckoutLink(this)" type="button">Generate Order Link</a>
                </div>
              </div>--}}
            </div>
            <div class="d-none mt-2" id="checkout-link-section">
              <button type="button" id="checkout-url-btn" onclick="copyCheckoutUrl(this)" class="btn btn-primary text-white" data-checkout-url="">
                <i class="far fa-copy"></i> Checkout Url
              </button>
            </div>
          </div>
        </div>
        <!-- shipping address modal start -->
        <div class="modal fade" id="shippingAddressModal" tabindex="-1" role="dialog" aria-labelledby="shippingAddressModalLabelled" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="shippingAddressModalLabelled">Shipping Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <input type="hidden" name="shipping_id" id="shipping_id" class="form-control">
                  <div class="form-group col-4">
                    <label>Shipping Name</label>
                    <input type="text" name="shipping_name" id="shipping_name" class="form-control">
                  </div>
                  <div class="form-group col-4">
                    <label>Shipping Email</label>
                    <input type="text" name="shipping_email" id="shipping_email" class="form-control">
                  </div>
                  <div class="form-group col-4">
                    <label>Shipping Phone</label>
                    <input type="text" name="shipping_phone" id="shipping_phone" class="form-control">
                  </div>
                  <div class="form-group col-8">
                    <label>Shipping Address</label>
                    <input type="text" name="shipping_address" id="shipping_address" class="form-control">
                  </div>
                  <div class="form-group col-4">
                    <label>Shipping Country</label>
                    <select name="shipping[country_id]" v-model="shipping.country_id" class="form-control nosearch" v-on:change="loadShippingMaster('states')">
                      <option value="0">Select Country</option>
                      @foreach($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-4">
                    <label>Shipping State</label>
                    <select name="shipping[state_id]" v-model="shipping.state_id" class="form-control nosearch" v-on:change="loadShippingMaster('cities')">
                      <option v-for="(item, id) in mastershipping.states" :key="id" :value="id">@{{item}}</option>
                    </select>
                  </div>
                  <div class="form-group col-4">
                    <label>Shipping City</label>
                    <select name="shipping[city_id]" v-model="shipping.city_id" class="form-control nosearch">
                      <option v-for="(item, id) in mastershipping.cities" :key="id" :value="id">@{{item}}</option>
                    </select>
                  </div>
                  <div class="form-group col-4">
                    <label>Shipping Pincode</label>
                    <input type="text" name="shipping_pincode" id="shipping_pincode" class="form-control">
                  </div>
                </div>
                <button type="button" class="btn btn-primary m-1" onclick="saveShippingAddress(this)">
                  submit
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- shipping address modal end -->
      </div>
    </div>
  </div>

  <!-- product list modal start -->
  <div class="modal fade" id="productsListModal" tabindex="-1" role="dialog" aria-labelledby="productsListModalLabelled" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productsListModalLabelled">Products List</h5>
          <button type="button" class="close" onclick="closeProductsListModal(this)">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row" id="filter_row">
            <div class="col-8">
              <input type="hidden" name="user_id" id="user_id" value="{{ $user?$user->id:0 }}">
              <input type="hidden" name="user_type" id="user_type" value="user">
              <div class="form-group">
                <input type="text" name="product_title" id="product_title" class="form-control" placeholder="🔍 Search Product" onkeyup="handleBrowseProductsList(this)">
              </div>
              <input type="hidden" name="item_type" id="item_type" value="product">
            </div>
            <div class="col-4">
              @php
                $filterResultUrl=route("admin.order.product.list");
              @endphp
              <button type="button" class="btn btn-success" id="filter-apply-btn" onclick="parentFilterResults('{{ $filterResultUrl }}')">
                Browse
              </button>
              <button type="button" class="btn btn-success" id="filter-reset-btn"  onclick="resetFilterResults()">
                <i class="fas fa-redo-alt pointer"></i>
              </button>
              <button type="button" class="btn btn-success" id="add-product-btn" onclick="openAddCustomProductModal(0,'','','',0)">
                Add Custom
              </button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table" id="list-table" data-url="{{ $filterResultUrl }}">
              <thead>
                <tr class="bg-primary text-white">
                  <!-- <th>#</th> -->
                  <th>Image</th>
                  <th>Title</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="pagination"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- product list modal end -->

  <!-- add custom product modal start -->
  <div class="modal fade" id="customeProductModal" tabindex="-1" role="dialog" aria-labelledby="customeProductModalLabelled" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="customeProductModalLabelled">Add Custom Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <input type="hidden" name="admin_side_cart_item_id" id="admin_side_cart_item_id">
            <div class="form-group col-4">
              <label>Title</label>
              <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Sale Price</label>
              <input type="number" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="sale_price" id="sale_price" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Quantity</label>
              <input type="number" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="quantity" id="quantity" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Hsn Code</label>
              <select name="hsn_code_id" class="form-control nosearch" id="hsn_code_id">
                <option value="0">Select</option>
                @foreach($hsnCodes as $index=>$item)
                  <option value="{{$item->id}}">{{ $item->name }} - {{ $item->hsn_code }} - {{ $item->gst_percentage }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <button type="button" class="btn btn-primary m-1" onclick="handleEditProduct(this)">
            submit
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- add custom product modal end -->

  <!-- customers list modal start -->
  <div class="modal fade" id="usersListModal" tabindex="-1" role="dialog" aria-labelledby="usersListModalLabelled" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="usersListModalLabelled">Customers List</h5>
          <button type="button" class="close" onclick="closeUsersListModal(this)">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row" id="filter_row_second">
            <div class="col-8">
              <div class="form-group">
                <input type="text" name="customer" id="customer" class="form-control" placeholder="🔍 Search Customer" onkeyup="handleBrowseUsersList(this)">
              </div>
              <input type="hidden" name="new_user_id" id="new_user_id" value="{{ $user?$user->id:0 }}">
              <input type="hidden" name="old_user_id" id="old_user_id" value="{{ $user?$user->id:0 }}">
            </div>
            <div class="col-4">
              @php
                $filterResultUrlSecond=route("admin.order.customer.list");
              @endphp
              <button type="button" class="btn btn-success" id="filter-apply-btn-second" onclick="parentFilterResultsSecond('{{ $filterResultUrlSecond }}')">
                Browse
              </button>
              <button type="button" class="btn btn-success" id="filter-reset-btn-second"  onclick="resetFilterResultsSecond()">
                <i class="fas fa-redo-alt pointer"></i>
              </button>
              <button type="button" class="btn btn-success" id="add-user-btn" onclick="openAddCustomUserModal()">
                Add Custom
              </button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table" id="list-table-second" data-url="{{ $filterResultUrlSecond }}">
              <thead>
                <tr class="bg-primary text-white">
                  <!-- <th>#</th> -->
                  <th>Image</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5" class="pagination-second"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- customers list modal end -->

  <!-- add custom product modal start -->
  <div class="modal fade" id="customeUserModal" tabindex="-1" role="dialog" aria-labelledby="customeUserModalLabelled" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="customeUserModalLabelled">Add Custom Customer</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-4">
              <label>Name</label>
              <input type="text" name="user_name" id="user_name" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Email</label>
              <input type="email" name="user_email" id="user_email" class="form-control">
            </div>
            <div class="form-group col-4">
              <label>Mobile Number</label>
              <input type="number" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="user_mobile_number" id="user_mobile_number" class="form-control">
            </div>
          </div>
          <button type="button" class="btn btn-primary m-1" onclick="handleEditUser(this)">
            submit
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- add custom product modal end -->

  <!-- add shipping amount modal start -->
  <div class="modal fade" id="shippingAmountModal" tabindex="-1" role="dialog" aria-labelledby="shippingAmountModalLabelled" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h5 class="modal-title" id="shippingAmountModalLabelled">Shipping Amount</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-12">
              <label>Amount</label>
              <input type="number" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="shipping_amount" id="shipping_amount" class="form-control">
            </div>
            <div class="col-12 text-center">
              <button type="button" class="btn btn-primary m-1" onclick="handleShippingAmount()">Add</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- add shipping amount modal end -->

  <!-- add discount amount modal start -->
  <div class="modal fade" id="discountAmountModal" tabindex="-1" role="dialog" aria-labelledby="discountAmountModalLabelled" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h5 class="modal-title" id="discountAmountModalLabelled">Discount Amount</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-12">
              <label>Amount</label>
              <input type="number" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="discount_amount" id="discount_amount" class="form-control">
            </div>
            <div class="col-12 text-center">
              <button type="button" class="btn btn-primary m-1" onclick="handleDiscountAmount()">Add</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- add shipping amount modal end -->



@endsection

@push('scripts')
<script src="{{asset('js/vue.min.js')}}"></script>
<script src="{{asset('js/axios.min.js')}}"></script>
<script>
  var profileapp = new Vue({
    el: '#create_order',
    data: {
      country_id: '{{$user?$user->country_id:0}}',
      state_id: '{{$user?$user->state_id:0}}',
      city_id: '{{$user?$user->city_id:0}}',
      shipping:{
        country_id: 0,
        state_id: 0,
        city_id: 0
      },
      master: {
        states: [],
        cities: []
      },
      mastershipping: {
        states: [],
        cities: []
      },
      errors: {},
      upload_type: '',
      successmessage: '',
      errormessage: '',
    },
    methods: {
      loadMaster(options) {
        let that = this;
        let url = '{{route('locations')}}';
        if (options == 'states') {
          url += '/master_states/?country_id=' + that.country_id
        } else if (options == 'cities') {
          url += '/master_cities/?state_id=' + that.state_id
        }
        axios.post(url)
          .then(function (response) {
            that.master[options] = response.data;
          })
          .catch(function (response) {
          })
      },
      loadShippingMaster(options) {
        let that = this;
        let url = '{{route('locations')}}';
        if (options == 'states') {
          url += '/master_states/?country_id=' + that.shipping.country_id
        } else if (options == 'cities') {
          url += '/master_cities/?state_id=' + that.shipping.state_id
        }
        axios.post(url)
          .then(function (response) {
            that.mastershipping[options] = response.data;
          })
          .catch(function (response) {
          })
      }
    },
    mounted() {
      @if($user && $user->id)
        this.loadMaster('states');
        this.loadMaster('cities');
      @endif
    }
  });
</script>
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
<script src="{{asset('theme-admin/js/listtablesecond.js')}}"></script>
<script type="text/javascript">
  let userId="{{ $user?$user->id:0 }}";
  let couponCode="";
  let couponCodeId=0;
  let discountAmount=0;
  let shippingAmount=0;
  let shippingAddressId="{{ $shippingAddressId }}";
  let filterResultUrl="{{ $filterResultUrl }}";
  let paginationPage=1;
  function parentFilterResults(url){
    paginationPage=1;
    filterResults(url);
  }
  function resetFilterResults(){
    $("#product_title").val("");
    $("#filter-apply-btn").click();
  }
  let filterResultUrlSecond="{{ $filterResultUrlSecond }}";
  let paginationPageSecond=1;
  function parentFilterResultsSecond(url){
    paginationPageSecond=1;
    filterResultsSecond(url);
  }
  function resetFilterResultsSecond(){
    $("#customer").val("");
    $("#filter-apply-btn-second").click();
  }
  function openUsersListModal(){
    $("#customer").val($("#customer_temp").val());
    $("#filter-apply-btn-second").click();
    $("#usersListModal").modal("show");
    setTimeout(() => {
      $("#customer").focus();
    }, 500);
  }
  function closeUsersListModal(that){
    $("#customer_temp").val("");
    $("#usersListModal").modal("hide");
  }
  function handleBrowseUsersList(that){
    $("#filter-apply-btn-second").click();
  }
  function openAddCustomUserModal(){
    $("#user_name").val("");
    $("#user_email").val("");
    $("#user_phone").val("");
    $("#customeUserModal").modal("show");
  }
  function handleEditUser(that){
    $.ajax({
       url: "{{ route('admin.order.customer.edit') }}",
       type : 'GET',
       data : {
         user_id:userId,
         name:$("#user_name").val(),
         email:$("#user_email").val(),
         mobile_number:$("#user_mobile_number").val(),
       },
       success:function(response){
         // console.log(response)
         toastr.success(response.message);
         if(response.redirectTo){
           setTimeout(function (){
             window.location.href=response.redirectTo;
           },1500);
         }
         handleCartList();
       },
       error:function(error){
         // console.log(error)
         let message=error.responseJSON.message;
         let errors=error.responseJSON.errors;
         if(errors){
           for(var key in errors){
             toastr.error(errors[key][0]);
           }
         }else if(message){
           toastr.error(message);
         }else{
           toastr.error("Error occured.");
         }
       }
     });
  }
  function handleRemoveUser(type){
    userId=0;
    $("#new_user_id").val(0);
    $("#customer-detail-section").addClass("d-none");
    $("#shipping-address-section").addClass("d-none");
    $("#billing-address-section").addClass("d-none");
    resetFilterResultsSecond();
    handleCartList();
  }
  function openProductsListModal(){
    if(userId==0){
      toastr.error("Add Customer First.");
      $("#product_title_temp").val("");
      $("#product_title").val("");
      return false;
    }
    $("#product_title").val($("#product_title_temp").val());
    $("#filter-apply-btn").click();
    $("#productsListModal").modal("show");
    setTimeout(() => {
      $("#product_title").focus();
    }, 500);
  }
  function closeProductsListModal(that){
    $("#product_title_temp").val("");
    $("#productsListModal").modal("hide");
  }
  function handleBrowseProductsList(that){
    $("#filter-apply-btn").click();
  }
  function openAddCustomProductModal(admin_side_cart_item_id,title,sale_price,quantity,hsn_code_id){
    $("#admin_side_cart_item_id").val(admin_side_cart_item_id);
    $("#title").val(title);
    $("#sale_price").val(sale_price);
    $("#quantity").val(quantity);
    $("#hsn_code_id").val(hsn_code_id);
    $("#customeProductModal").modal("show");
  }
  function handleAddProduct(that,product_id){
    $.ajax({
       url: "{{ route('admin.order.product.add') }}",
       type : 'GET',
       data : {
         user_id:userId,
         user_type:"user",
         product_id:product_id,
         item_type:"product",
       },
       success:function(response){
         // console.log(response)
         toastr.success(response.message);
         discountAmount=0;
         shippingAmount=0;
         listTable();
         handleCartList();
       },
       error:function(error){
         // console.log(error)
         toastr.error("Error occured.");
       }
     });
  }
  function handleEditProduct(that){
    $.ajax({
       url: "{{ route('admin.order.product.edit') }}",
       type : 'GET',
       data : {
         user_id:userId,
         admin_side_cart_item_id:$("#admin_side_cart_item_id").val(),
         title:$("#title").val(),
         sale_price:$("#sale_price").val(),
         quantity:$("#quantity").val(),
         hsn_code_id:$("#hsn_code_id").val(),
       },
       success:function(response){
         // console.log(response)
         toastr.success(response.message);
         discountAmount=0;
         shippingAmount=0;
         handleCartList();
         $("#customeProductModal").modal("hide");
       },
       error:function(error){
         // console.log(error)
         let message=error.responseJSON.message;
         let errors=error.responseJSON.errors;
         if(errors){
           for(var key in errors){
             toastr.error(errors[key][0]);
           }
         }else if(message){
           toastr.error(message);
         }else{
           toastr.error("Error occured.");
         }
       }
     });
  }
  function handleRemoveProduct(that,product_id){
    $.ajax({
       url: "{{ route('admin.order.product.remove') }}",
       type : 'GET',
       data : {
         user_id:userId,
         user_type:"user",
         product_id:product_id,
         item_type:"product",
       },
       success:function(response){
         // console.log(response)
         toastr.success(response.message);
         discountAmount=0;
         shippingAmount=0;
         listTable();
         handleCartList();
       },
       error:function(error){
         // console.log(error)
         toastr.error("Error occured.");
       }
     });
  }
  function increaseQuantity(that,product_id){
    $.ajax({
       url: "{{ route('admin.order.product.increase.quantity') }}",
       type : 'GET',
       data : {
         user_id:userId,
         user_type:"user",
         product_id:product_id,
         item_type:"product",
       },
       success:function(response){
         // console.log(response)
         toastr.success(response.message);
         discountAmount=0;
         shippingAmount=0;
         handleCartList();
       },
       error:function(error){
         // console.log(error)
         toastr.error("Error occured.");
       }
     });
  }
  function decreaseQuantity(that,product_id){
    $.ajax({
       url: "{{ route('admin.order.product.decrease.quantity') }}",
       type : 'GET',
       data : {
         user_id:userId,
         user_type:"user",
         product_id:product_id,
         item_type:"product",
       },
       success:function(response){
         // console.log(response)
         toastr.success(response.message);
         discountAmount=0;
         shippingAmount=0;
         handleCartList();
       },
       error:function(error){
         // console.log(error)
         toastr.error("Error occured.");
       }
     });
  }
  function handleCartList(){
    $.ajax({
       url: "{{ route('admin.order.product.cartlist') }}",
       type : 'GET',
       data : {
         shipping_address_id:shippingAddressId,
         user_id:userId,
         user_type:"user",
         productTypeName:"Product",
         item_type:"product",
         coupon_code:couponCode,
         discount_amount:discountAmount,
         shipping_amount:shippingAmount,
       },
       success:function(response){
         // console.log(response)
         // toastr.success(response.message);
         $("#cart-section").html(response.cartHtml);
         $("#coupon-section").html(response.couponHtml);
         $("#amount-section").html(response.amountHtml);
       },
       error:function(error){
         // console.log(error)
         toastr.error("Error occured.");
       }
     });
     couponCode="";
  }
  handleCartList();
  function applyCouponCode(type){
    $.ajax({
       url: "{{ route('admin.coupons.apply') }}",
       type : 'GET',
       data : {
         type:type,
         coupon_code:$("#coupon_code").val(),
         user_id:userId,
         user_type:"user",
       },
       success:function(response){
         // console.log(response)
         toastr.success(response.message);
         couponCode=response.coupon_code;
         handleCartList();
         couponCode=response.coupon_code;
         $("#coupon_code").prop("disabled",true);
         $("#coupon-apply-btn").addClass("d-none");
         $("#coupon-remove-btn").removeClass("d-none");
       },
       error:function(error){
         // console.log(error);
         couponCode="";
         let message=error.responseJSON.message;
         if(message){
           toastr.error(message);
         }else{
           toastr.error("Error occured.");
         }
       }
     });
  }
  function removeCouponCode(type){
    couponCode="";
    handleCartList();
  }
  function addDiscount(){
    discountAmount=$("#discountAmount").val()?$("#discountAmount").val():0;
    handleCartList();
  }
  function removeDiscount(){
    discountAmount=0;
    handleCartList();
  }
  function selectAddress(that,shipping_address_id){
    shippingAddressId=shipping_address_id;
    $(that).prop("checked",true);
  }
  let billingAddressType="different";
  function selectBillingAddress(that,billing_address_type){
    billingAddressType=billing_address_type;
    $(that).prop("checked",true);
  }
  function copyCheckoutUrl(that){
    navigator.clipboard.writeText($(that).attr("data-checkout-url"));
    toastr.success("copied");
  }
  function openShippingModal(){
    $('#shippingAddressModal').modal('show');
  }
  function closeShippingModal(){
    $('#shippingAddressModal').modal('hide');
  }
  function handleShippingAddress(that,action,id,name,email,phone,country_id,state_id,city_id,address,pincode){
    $("#shipping_id").val(id?id:"");
    $("#shipping_name").val(name?name:"");
    $("#shipping_email").val(email?email:"");
    $("#shipping_phone").val(phone?phone:"");
    profileapp._data.shipping.country_id=country_id;
    if(country_id){
      profileapp.loadShippingMaster("states");
    }
    profileapp._data.shipping.state_id=state_id;
    if(state_id){
      profileapp.loadShippingMaster("cities");
    }
    profileapp._data.shipping.city_id=city_id;
    $("#shipping_address").val(address?address:"");
    $("#shipping_pincode").val(pincode?pincode:"");
    console.log(profileapp)
    console.log(profileapp._data)
    openShippingModal();
  }
  function saveShippingAddress(that){
    $.ajax({
       url: "{{ route('admin.customer.saveshippingaddress') }}",
       type : 'GET',
       data : {
         user_id:userId,
         shipping_id:$("#shipping_id").val(),
         shipping_name:$("#shipping_name").val(),
         shipping_email:$("#shipping_email").val(),
         shipping_phone:$("#shipping_phone").val(),
         shipping_country_id:profileapp._data.shipping.country_id,
         shipping_state_id:profileapp._data.shipping.state_id,
         shipping_city_id:profileapp._data.shipping.city_id,
         shipping_address:$("#shipping_address").val(),
         shipping_pincode:$("#shipping_pincode").val(),
       },
       success:function(response){
         toastr.success(response.message);
         setTimeout(function(){
           window.location.reload();
         },1500);
       },
       error:function(error){
         let message=error.responseJSON.message;
         let errors=error.responseJSON.errors;
         if(errors){
           for(var key in errors){
             toastr.error(errors[key][0]);
           }
         }else if(message){
           toastr.error(message);
         }else{
           toastr.error("Error occured.");
         }
       }
     });
  }
  function openDiscountAmountModal(){
    $("#discount_amount").val(discountAmount);
    $('#discountAmountModal').modal('show');
  }
  function handleDiscountAmount(){
    let discountAmountValue=$("#discount_amount").val();
    discountAmount=discountAmountValue && discountAmountValue>0?discountAmountValue:0;
    handleCartList();
    $('#discountAmountModal').modal('hide');
  }
  function openShippingAmountModal(){
    $("#shipping_amount").val(shippingAmount);
    $('#shippingAmountModal').modal('show');
  }
  function handleShippingAmount(){
    let shippingAmountValue=$("#shipping_amount").val();
    shippingAmount=shippingAmountValue && shippingAmountValue>0?shippingAmountValue:0;
    handleCartList();
    $('#shippingAmountModal').modal('hide');
  }
  let orderId=0;
  function handlePlaceOrder(that,payment_method){
    $("#place-cod-order-btn").prop("disabled",true);
    $("#generate-order-link-btn").prop("disabled",true);
    $("#checkout-link-section").addClass("d-none");
    $.ajax({
       url: "{{ route('admin.order.customer.place') }}",
       type : 'GET',
       data : {
         user_id:userId,
         user_type:"user",
         shipping_address_id:shippingAddressId,
         payment_method:payment_method,
         coupon_code:couponCode,
         discount_amount:discountAmount,
         shipping_amount:shippingAmount,
         order_id:orderId,
         billing_address_type:billingAddressType,
         billing_name:$("#billing_name").val(),
         billing_email:$("#billing_email").val(),
         billing_phone:$("#billing_phone").val(),
         billing_country:profileapp._data.country_id,
         billing_state:profileapp._data.state_id,
         billing_city:profileapp._data.city_id,
         billing_address:$("#billing_address").val(),
         billing_pincode:$("#billing_pincode").val(),
       },
       success:function(response){
         // console.log(response)
         $("#place-cod-order-btn").prop("disabled",false);
         $("#generate-order-link-btn").prop("disabled",false);
         toastr.success(response.message);
         orderId=response.order_id;
         if(response.link){
           $("#checkout-link-section").removeClass("d-none");
           $("#checkout-url-btn").attr("data-checkout-url",response.link);
         }
         if(response.redirectTo){
           setTimeout(function(){
             window.location.href=response.redirectTo;
           },1500);
         }
       },
       error:function(error){
         // console.log(error);
         $("#place-cod-order-btn").prop("disabled",false);
         $("#generate-order-link-btn").prop("disabled",false);
         let message=error.responseJSON.message;
         let errors=error.responseJSON.errors;
         if(errors){
           for(var key in errors){
             toastr.error(errors[key][0]);
           }
         }else if(message){
           toastr.error(message);
         }else{
           toastr.error("Error occured.");
         }
       }
     });
  }
  function generateCheckoutLink(that){
    $("#place-cod-order-btn").prop("disabled",true);
    $("#generate-order-link-btn").prop("disabled",true);
    $("#checkout-link-section").addClass("d-none");
    $.ajax({
       url: "{{ route('admin.order.customer.generatecheckoutlink') }}",
       type : 'GET',
       data : {
         user_id:userId,
         user_type:"user",
         shipping_address_id:shippingAddressId,
         discount_amount:discountAmount,
         coupon_amount:0,
         shipping_amount:shippingAmount,
       },
       success:function(response){
         // console.log(response)
         $("#place-cod-order-btn").prop("disabled",false);
         $("#generate-order-link-btn").prop("disabled",false);
         toastr.success(response.message);
         if(response.link){
           $("#checkout-link-section").removeClass("d-none");
           $("#checkout-url-btn").attr("data-checkout-url",response.link);
         }
       },
       error:function(error){
         // console.log(error);
         $("#place-cod-order-btn").prop("disabled",false);
         $("#generate-order-link-btn").prop("disabled",false);
         let message=error.responseJSON.message;
         let errors=error.responseJSON.errors;
         if(errors){
           for(var key in errors){
             toastr.error(errors[key][0]);
           }
         }else if(message){
           toastr.error(message);
         }else{
           toastr.error("Error occured.");
         }
       }
     });
  }
  if(performance.navigation.type===performance.navigation.TYPE_RELOAD){
    window.location.href="{{ route('admin.order.create') }}";
  }
</script>
@endpush
