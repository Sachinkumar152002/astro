@extends('layouts.admin')

@section('content')
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      <div class="mt-2">
        <label class="fs-13">Name</label>
        <input type="text" class="form-control" name="name" placeholder="Name">
      </div>
      <div class="mt-2">
        <label class="fs-13">Gender</label>
        <select class="form-control" name="gender">
          <option value="">All</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div class="mt-2">
        <label class="fs-13">Birth date</label>
        <input type="date" class="form-control" name="birth_date">
      </div>
      <div class="mt-2">
        <label class="fs-13">Birth time</label>
        <input type="time" class="form-control" name="birth_time">
      </div>
      <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-success text-white " onclick="filterResults('{{ route('admin.dashboard.free_kundali.list') }}')">Apply</button>
      </div>
    </div>
  </div>
</div>
</div>

<div class="row">
  <div class="col-lg-6">
    <h1 class="text-primary float-left">Free Kundali</h1>
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
  <table class="table" id="list-table" data-url="{{ route('admin.dashboard.free_kundali.list') }}">
    <thead>
      <tr class="bg-primary text-white">
        <th>#</th>
        <th class="sortable" data-sort="name">Name <i class="icon"></i></th>
        <th class="sortable" data-sort="gender">Gender <i class="icon"></i></th>
        <th class="sortable selected" data-sort="date">Birth date <i class="icon"></i></th>
        <th class="sortable" data-sort="time">Birth time <i class="icon"></i></th>
        <th class="sortable" data-sort="city">Birth place <i class="icon"></i></th>
        <th class="sortable" data-sort="lang">Language <i class="icon"></i></th>
      </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <td colspan="7" class="pagination"></td>
      </tr>
    </tfoot>
  </table>
</div>
@endsection

@push('scripts')
<script src="{{ asset('theme-admin/js/listtable.js') }}"></script>
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
