@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">

       <div class="" id="filter_row">
    <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
    <input type="hidden" class="form-control" name="type" value="{{ $type }}">
       <div class="mt-4">
         <label for="" class="fs-13">Name</label>
        <input type="text" class="form-control" name="name">
       </div>
     <div class="mt-2">
       <label for="" class="fs-13">Email</label>
       <input type="text" class="form-control" name="email">
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Date</label>
      <input type="text" class="form-control" name="phone">
      </div>


    <button class="btn btn-success text-white mt-3" onclick="listTable()">Apply</button>


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
          <th>All</th>
          <th># <i class="icon"></i></th>
          <th class="sortable " data-sort="name">Name <i class="icon"></i></th>
          <th class="sortable " data-sort="email">Email <i class="icon"></i></th>
          <th class="sortable " data-sort="phone">Phone <i class="icon"></i></th>
          <!-- <th class="sortable " data-sort="message">Message <i class="icon"></i></th> -->
          <th class="sortable selected" data-sort="created_at">Date time <i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
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
    </div><!--end .table-responsive -->

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
