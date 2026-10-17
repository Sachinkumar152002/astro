@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="px-0 w-100" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label for="astrologer" class="fs-13">Name</label>
         <input type="text" class="form-control" name="astrologer">
       </div>
       <div class="mt-2">
         <label for="phone" class="fs-13">Phone</label>
         <input type="text" class="form-control" name="phone">
       </div>
        <div class="mt-2">
          <label for="status" class="fs-13">Status</label>
          <select class="form-control" name="status">
            <option value="">Select</option>
            <option value="pending">Pending</option>
            <option value="done">Done</option>
            <option value="cancel">Cancel</option>
          </select>
        </div>
        <div class="mt-2">
          <label for="updated_at" class="fs-13">Date</label>
          <div class="d-flex">
            <div class="">
              <label for="updated_at_from" class="fs-13">From</label>
              <input type="date" class="form-control" name="updated_at_from">
            </div>
            <div class="">
              <label for="updated_at_to" class="fs-13">To</label>
              <input type="date" class="form-control" name="updated_at_to">
            </div>
          </div>
        </div>
        <button class="btn btn-success mt-3" onclick="listTable()">Apply</button>
     </div>
  </div>
    </div>
  </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-md-6">
<h1 class="text-primary float-left">{{$moduleName}}</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    {{--
    <a href="{{route('admin.'.$nav.'.add')}}">
      <button class="btn btn-primary text-black m-1" data-toggle="modal" data-target="#addModal">
        <i class="bi bi-plus"></i>
      </button>
    </a>
    --}}
    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
      Filter <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th>Name</th>
          <th>Phone</th>
          <th>Amount</th>
          <th>Status</th>
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
    </div>
    <!--end .table-responsive -->

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
