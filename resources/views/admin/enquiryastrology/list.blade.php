@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">

       <div class="" id="filter_row">
         <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
         <div class="mt-4">
           <label for="name" class="fs-13">Name</label>
          <input type="text" class="form-control" name="name" id="name">
         </div>
         <div class="mt-2">
           <label for="email" class="fs-13">Email</label>
           <input type="text" class="form-control" name="email" id="email">
         </div>
         <div class="mt-2">
           <label for="phone" class="fs-13">Phone</label>
           <input type="number" class="form-control" name="phone" id="phone">
         </div>
         <div class="mt-2">
           <label for="updatd_at_from" class="fs-13">From</label>
           <input type="date" class="form-control" name="updatd_at_from">
        </div>
        <div class="mt-2">
          <label for="updatd_at_to" class="fs-13">To</label>
          <input type="date" class="form-control" name="updatd_at_to">
       </div>
       <button class="btn btn-success text-white mt-3" onclick="listTable()">Apply</button>
    </div>
  </div>
  </div>
  </div>
<div class="row">
<div class="col-md-6">
<h1 class="text-primary float-left">{{$moduleName}} jj</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    @if(adminCan($nav, 'delete'))
    <button class="btn btn-danger text-white m-1">
      <i class="bi bi-trash"></i>
    </button>
    @endif
    <button class="btn btn-success text-dark m-1 d-none" onclick="filterResults('{{ route('admin.'.$nav.'.all') }}')">Filter</button>
    <button class="filter-div btn btn-success btn-search text-dark m-1 py-2">
      <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th>All</th>
          <th># <i class="icon"></i></th>
          <th class="sortable " data-sort="name">Name <i class="icon"></i></th>
          <th class="sortable " data-sort="email">Email <i class="icon"></i></th>
          <th class="sortable " data-sort="phone">Phone <i class="icon"></i></th>
          <th class="sortable selected" data-sort="updated_at">Date <i class="icon"></i></th>
          <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>

      </thead>
      <tbody>

      </tbody>
      <tfoot>
        <tr>
          <td colspan="8" class="pagination"></td>
        </tr>
      </tfoot>
    </table>
    </div>

@endsection

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.'.$nav.'.delete')}}';
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
