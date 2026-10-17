@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="w-100" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      <div class="mt-2">
        <label for="" class="fs-13">UUID</label>
     <input type="text" class="form-control" name="uuid">
      </div>
       <div class="mt-2">
         <label for="" class="fs-13">Name</label>
      <input type="text" class="form-control" name="name">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Phone</label>
       <input type="text" class="form-control" name="phone">
      </div>
      <div class="mt-2">
        <label for="" class="fs-13">Email</label>
     <input type="text" class="form-control" name="email">
      </div>
      <div class="mt-2">
        <label for="cart_items" class="fs-13">Cart Items</label>
        <select class="form-control" name="cart_items">
          <option value="">Select</option>
          <option value="shop">Proudct</option>
        </select>
      </div>

      <div class="mt-2">
        <label for="" class="fs-13">Status</label>
        <select class="form-control" name="status">
          <option value="">Select</option>
          @forelse (statusArray() as $k=>$v)
            <option value="{{$k}}" {{ request()->get("status") == "active" && $k == 1 ? "selected" : '' }}>{{$v}}</option>
          @empty
          <option value="0">Inactive</option>
          @endforelse
        </select>
      </div>
      <div class="mt-2">
        <label for="" class="fs-13">Date</label>
        <input type="date" class="form-control" name="updated_at">
      </div>
      <button class="btn btn-success text-white mt-3" onclick="listTable()">Apply</button>
      </div>

    </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
<div class="row px-4">
<div class="col-md-6">
<h1 class="text-primary float-left">{{$moduleName}}</h1>
<p class="h3 m-0 ml-3 pt-2 float-left">(Total - <span id="total-results">-</span>)</p>
</div>
<div class="col-md-6">

  <div class="d-flex text-align-end justify-content-end">
    {{--
    <a class="btn btn-primary text-black m-1 text-white">
      <input type="checkbox" onchange="loadTrashedRecords(this)" id="only_trashed">
      <i class="bi bi-recycle"></i>
    </a>
    --}}
    @if(adminCan('customer', 'add'))
    <button class="btn btn-primary m-1">
    <a class="text-white" href="{{route('admin.'.$nav.'.add')}}">
        <i class="bi bi-plus"></i>
    </a>
    </button>

    @endif
    @if(adminCan('customer', 'delete'))
    @endif
    <button class="btn btn-success m-1 py-2 text-white" onclick="exportCsv(this, '{{ route('admin.'.$nav.'.export') }}')">Export</button>
    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
      Filter <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>
    <div class="table-responsive px-4">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th>All</th>
          <th># <i class="icon"></i></th>
          <th>UUID <i class="bi bi-person fs-4"></i></th>
          <th>Name <i class="bi bi-person fs-4"></i></th>
          <th>Mobile<i class="bi bi-phone"></i></th>
          <th>Email <i class="bi bi-envelope"></i></th>
          <th>Status <i class="icon"></i></th>
          {{-- <th class="sortable " data-sort="cart_items">Cart (C) <i class="icon"></i></th> --}}
          <th class="sortable " data-sort="cart_items">Cart (P) <i class="icon"></i></th>
          {{-- <th class="sortable " data-sort="courses">Course <i class="icon"></i></th> --}}
          <th class="sortable ">Notification <i class="icon"></i></th>
          <th class="sortable selected" data-sort="updated_at">Last Updated <i class="icon"></i></th>
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
    </div><!--end .table-responsive -->

@endsection

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.'.$nav.'.delete')}}';
  var loadTrashedRecords = function(){
    if($('#only_trashed').is(':checked')){
      $('#trashed').val(1);
      listTable();
    }else {
      resetFilter();
    }
  }
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
