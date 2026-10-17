@extends('layouts.admin')

@section('content')

<!-- filter-design-new start-->
<div class="" style="position:relative;">
  <div class="">
    <div class="row search-filters" id="filters">
      <div class="w-100" id="filter_row">
        <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label class="fs-13">Customer name</label>
         <input type="text" class="form-control" name="customer_name" placeholder="Search by customer name">
       </div>
       <div class="mt-2">
         <label class="fs-13">Item name</label>
         <input type="text" class="form-control" name="item_name" placeholder="Search by product / course name">
       </div>
       <div class="mt-2">
         <label class="fs-13">Date</label>
         <div class="">
           <input type="date" class="form-control" name="updated_at_from" placeholder="From">
           <input type="date" class="form-control mt-3" name="updated_at_to" placeholder="To">
         </div>
      </div>
      <button class="btn btn-success text-white mt-3" onclick="filterResults('{{ route('admin.customer.cartitem.all', [$user_id]) }}')">Apply</button>
    </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-md-6">
<h1 class="text-primary">{{ $moduleName }}</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">

    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
      Filter <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>

    <div class="table-responsive p-3">
      <table class="table" id="list-table" data-url="{{route('admin.customer.cartitem.all', [$user_id])}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th>Customer</th>
          <th>Image</th>
          <th>Item</th>
          <th>Quantity</th>
          {{--<th>Buy Now Url</th>--}}
          <th class="sortable" data-sort="created_at">Added At <i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
        </tr>

      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="7" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->

@endsection

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.customer.cartitem.delete', [$user_id])}}';
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

function copyBuyNowUrl(that){
  navigator.clipboard.writeText($(that).attr("data-buynowUrl"));
  toastr.success("copied");
}
</script>
@endpush
