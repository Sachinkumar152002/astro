@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label for="" class="fs-13">Referrer Type</label>
         <select class="form-control" name="referrer_type">
           <option value="">Select</option>
           <option value="user">User</option>
           <option value="astrologer">Astrologer</option>
         </select>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Category</label>
         <select class="form-control" name="category_id">
           <option value="">Select Category</option>
           <!-- Categories will be populated dynamically -->
         </select>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Status</label>
         <select class="form-control" name="status">
           <option value="">Select</option>
           <option value="pending">Pending</option>
           <option value="credited">Credited</option>
           <option value="cancelled">Cancelled</option>
           <option value="refunded">Refunded</option>
         </select>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Date From</label>
         <input type="date" class="form-control" name="created_at_from">
      </div>
       <div class="mt-2">
         <label for="" class="fs-13">Date To</label>
         <input type="date" class="form-control" name="created_at_to">
      </div>
       <button class="btn btn-success mt-2" onclick="listTable()">Apply</button>
      </div>
    </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
<div class="px-3">
  <div class="row">
  <div class="col-md-6">
  <h1 class="text-primary">Commission Transactions</h1>
  </div>
  <div class="col-md-6">
    <div class="d-flex text-align-end justify-content-end">
      <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
      Filter <i class="bi bi-funnel text-white"></i>
      </button>
    </div>
  </div>
  </div>
      <div class="table-responsive">
        <table class="table" id="list-table" data-url="{{route('admin.commission_transactions.all')}}">
        <thead>
          <tr class="bg-primary text-white">
            <th># <i class="icon"></i></th>
            <th class="sortable" data-sort="referrer_id">Referrer <i class="icon"></i></th>
            <th class="sortable" data-sort="order_id">Order <i class="icon"></i></th>
            <th class="sortable" data-sort="category_id">Category <i class="icon"></i></th>
            <th class="sortable" data-sort="order_amount">Order Amount <i class="icon"></i></th>
            <th class="sortable" data-sort="commission_amount">Commission <i class="icon"></i></th>
            <th class="sortable" data-sort="status">Status <i class="icon"></i></th>
            <th class="sortable" data-sort="created_at">Created At<i class="icon"></i></th>
            <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="pagination"></td>
            </tr>
        </tfoot>
      </table>
      </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('theme-admin/js/listtable.js')}}"> </script>
<script type="text/javascript">
var search = $('.btn-search');
var close = $('.close-search');
var overlay = $('.filter-backdrop');
search.click(function(){
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
