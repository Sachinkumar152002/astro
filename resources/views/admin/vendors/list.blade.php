@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
  <div class="">
    <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
        <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
        <div class="mt-2">
          <label for="" class="fs-13">Name</label>
          <input type="text" class="form-control" name="name">
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Email</label>
          <input type="text" class="form-control" name="email">
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Phone</label>
          <input type="text" class="form-control" name="phone">
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Business Name</label>
          <input type="text" class="form-control" name="business_name">
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Status</label>
          <select class="form-control" name="status">
            <option value="">Select</option>
            @foreach (statusArray() as $k=>$v)
              <option value="{{$k}}">{{$v}}</option>
            @endforeach
          </select>
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Date</label>
          <div class="d-flex">
            <div class="">
              <input type="date" class="form-control" name="updated_at_from">
            </div>
            <div class="ml-2">
              <input type="date" class="form-control" name="updated_at_to">
            </div>
          </div>
        </div>
        <button class="btn btn-success mt-2" onclick="filterResults('{{route('admin.'.$module.'.list')}}')">Apply</button>
      </div>
    </div>
  </div>
</div>
 <!-- filter-design-new-end -->
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{ $moduleName }} List</h1>
  </div>
  <div class="col-md-6">
    <div class="d-flex text-align-end justify-content-end">
      @if(adminCan($module, 'add'))
        <button class="btn btn-primary m-1">
          <a href="{{route('admin.'.$module.'.add')}}" class="text-white">
            <i class="bi bi-plus-lg"></i>
          </a>
        </button>
      @endif
      <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
      Filter <i class="bi bi-funnel text-white"></i>
      </button>
    </div>
  </div>
</div>
<div class="table-responsive">
  <table class="table" id="list-table" data-url="{{route('admin.'.$module.'.list')}}">
    <thead>
      <tr class="bg-primary text-white">
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Business Name</th>
        <th>Status</th>
        <th class="sortable selected" data-sort="updated_at">Updated At <i class="icon"></i></th>
        <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
      </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <td colspan="9" class="pagination"></td>
      </tr>
    </tfoot>
  </table>
</div>
<!--end .table-responsive -->
@endsection
@include('admin.media.view')

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.'.$module.'.delete')}}';
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
