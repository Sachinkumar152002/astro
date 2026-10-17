@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      <div class="mt-2">
        <label for="product_type" class="fs-13">Product Type</label>
        <select class="form-control" name="product_type" id="product_type">
          <option value="">Select</option>
          <option value="product">Product</option>
          <option value="pooja">Pooja</option>
          <option value="service">Service</option>
        </select>
     </div>
       <div class="mt-2">
         <label for="" class="fs-13"> Order Number </label>
         <input type="text" class="form-control" name="order_number" placeholder="Order Number">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Invoice Number</label>
    <input type="text" class="form-control" name="invoice_number" placeholder="Invoice Number">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Order Status</label>
         <select class="form-control" name="order_status">
         <option value="">Select</option>
           @foreach(orderStatus() as $key => $item)
             @if($key)
              <option value="{{$key}}" {{$key == request()->status ?'selected':''}}>{{$item}}</option>
             @endif
           @endforeach
           <option value="pending" {{'pending' == request()->status ?'selected':''}}>Pending</option>
           <option value="cancel" {{ 'cancel' == request()->status ?'selected':''}}>Cancel</option>
         </select>
      </div>
      <div class="mt-2">
        <label for="" class="fs-13">Payment Status</label>
        <select class="form-control" name="payment_status">
          <option value="">Select</option>
          <option value="pending">Pending</option>
          <option value="success">Success</option>
        </select>
     </div>
      <div class="mt-2">
        <label for="" class="fs-13">Date</label>
        <div class="d-flex">
          <div class="">
            <input type="date" class="form-control" name="updated_at_from">
        </div>
        <div class="">
          <input type="date" class="form-control" name="updated_at_to">
      </div>
        </div>
      </div>
      <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-success text-white " onclick="filterResults('{{ route('admin.order.all') }}')">Apply</button>
      </div>
    </div>
  </div>
</div>
</div>

<!-- filter-design-new-end -->
      <div class="row">
        <div class="col-lg-6">
            <h1 class="text-primary float-left">{{ucfirst(request()->status)}} Orders</h1>
        </div>
        <div class="col-md-6">
          <div class="d-flex text-align-end justify-content-end">
          <button class="btn btn-success m-1 py-2 text-white" onclick="exportCsv(this, '{{ route('admin.order.export') }}')">Export</button>
            <button class="filter-div btn btn-success btn-search m-1 py-2">
            Filter <i class="bi bi-funnel text-white"></i>
            </button>
          </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" id="list-table" data-url="{{ route('admin.order.all') }}">
            <thead>
                <tr class="bg-primary text-white">
                    <th>#</th>
                    <th>Order Number</th>
                    <th>Invoice Number</th>
                    <th>User</th>
                    <th>Order Status</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Request</th>
                    <th class="sortable selected" data-sort="updated_at">Updated At <i class="icon"></th>
                    <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
                </tr>
              </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10" class="pagination"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <!--end .table-responsive -->
@endsection

@push('scripts')

    <script type="text/javascript">
        var deleteUrl = '{{ route('admin.order.delete') }}';
    </script>
    <script src="{{asset('theme-admin/js/listtable.js')}}"></script>
    <script type="text/javascript">
    var search = $('.btn-search');
    var close = $('.close-search');
    var overlay = $('.filter-backdrop');
    search.click(function() {
      filter_show();
    });
    close.click(function() {
      filter_hide();
    });

    function filter_show() {
      $('#filters').addClass('filtered');
      overlay.show(20);
    }

    function filter_hide() {
      $('#filters').removeClass('filtered');
      overlay.hide();
    }
    </script>
@endpush
