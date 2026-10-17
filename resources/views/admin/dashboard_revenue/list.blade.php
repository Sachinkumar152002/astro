@extends('layouts.admin')

@section('content')
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      <div class="mt-2">
        <label for="has_image" class="fs-13">Product image</label>
        <select class="form-control" name="has_image" id="has_image">
          <option value="">All</option>
          <option value="1">With image</option>
          <option value="0">Without image</option>
        </select>
     </div>
       <div class="mt-2">
         <label for="product_name" class="fs-13">Product name</label>
         <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product name">
       </div>
       <div class="mt-2">
         <label class="fs-13">Price</label>
         <div class="d-flex">
           <input type="number" step="0.01" class="form-control mr-1" name="price_min" placeholder="Min">
           <input type="number" step="0.01" class="form-control" name="price_max" placeholder="Max">
         </div>
       </div>
       <div class="mt-2">
         <label class="fs-13">Date</label>
         <div class="d-flex">
           <div class="">
             <input type="date" class="form-control" name="date_from">
           </div>
           <div class="ml-2">
             <input type="date" class="form-control" name="date_to">
           </div>
         </div>
       </div>
      <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-success text-white " onclick="filterResults('{{ route('admin.dashboard.revenue.list', ['segment' => $segment]) }}')">Apply</button>
      </div>
    </div>
  </div>
</div>
</div>

      <div class="row">
        <div class="col-lg-6">
            <h1 class="text-primary float-left">{{ $segment === 'products' ? 'Products' : ($segment === 'puja' ? 'Puja' : 'Reports') }} Revenue — line items</h1>
        </div>
        <div class="col-md-6">
          <div class="d-flex text-align-end justify-content-end align-items-center">
            <i class="icon icon-reload pointer mr-3 fs-20" onclick="resetFilter()" title="Reset filters"></i>
            <button class="filter-div btn btn-success btn-search m-1 py-2">
            Filter <i class="bi bi-funnel text-white"></i>
            </button>
          </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table" id="list-table" data-url="{{ route('admin.dashboard.revenue.list', ['segment' => $segment]) }}">
            <thead>
                <tr class="bg-primary text-white">
                    <th>Product image</th>
                    <th class="sortable" data-sort="products.title">Product name <i class="icon"></th>
                    <th class="sortable" data-sort="order_items.total_price">Price <i class="icon"></th>
                    <th class="sortable selected" data-sort="orders.created_at">Date <i class="icon"></th>
                </tr>
              </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="pagination"></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('theme-admin/js/listtable.js') }}"></script>
    <script type="text/javascript">
        sort_by = sort_by || 'orders.created_at';
    </script>
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
