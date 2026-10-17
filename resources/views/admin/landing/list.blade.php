@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
  <div class="">
    <div class="row search-filters" id="filters">
      <div class="px-0 w-100" id="filter_row">
        <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
          <div class="mt-2">
           <label for="" class="fs-13">Title</label>
           <input type="text" class="form-control" name="title">
          </div>
          <div class="mt-2">
            <label for="" class="fs-13">Status</label>
            <select class="form-control" name="status">
               <option value="">Select</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="mt-2">
            <label for="" class="fs-13">Show In Navigation</label>
            <select class="form-control" name="show_in_navigation">
               <option value="">Select</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="mt-2">
            <label for="" class="fs-13">Date</label>
            <input type="date" class="form-control" name="updated_at">
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
    @if(adminCan('landing', 'add'))
      <button class="btn btn-primary text-black m-1">
        <a  href="{{route('admin.'.$nav.'.add')}}" class="text-white">
          <i class="bi bi-plus"></i>
        </a>
    </button>
    <button class="btn btn-seconday text-black m-1">
      <i class="bi bi-copy"></i>
    </button>
    @endif
    @if(adminCan('landing', 'delete'))
    <button class="btn btn-danger text-white m-1">
      <i class="bi bi-trash"></i>
    </button>
    @endif
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
          <th class="sortable " data-sort="name">Title <i class="icon"></i></th>
          <th class="sortable " data-sort="slug">Slug <i class="icon"></i></th>
          <th class="sortable" data-sort="status">Status <i class="icon"></i></th>
          <th class="sortable" data-sort="show_in_navigation">Navigation <i class="icon"></i></th>
          <th class="sortable selected" data-sort="updated_at">Last Updated <i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
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
