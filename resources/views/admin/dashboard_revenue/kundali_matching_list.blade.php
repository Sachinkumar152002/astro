@extends('layouts.admin')

@section('content')
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      <div class="mt-2">
        <label class="fs-13">Boy name</label>
        <input type="text" class="form-control" name="boy_name" placeholder="Boy name">
      </div>
      <div class="mt-2">
        <label class="fs-13">Girl name</label>
        <input type="text" class="form-control" name="girl_name" placeholder="Girl name">
      </div>
      <div class="mt-2">
        <label class="fs-13">Boy birth date</label>
        <input type="date" class="form-control" name="boy_birth_date">
      </div>
      <div class="mt-2">
        <label class="fs-13">Girl birth date</label>
        <input type="date" class="form-control" name="girl_birth_date">
      </div>
      <div class="mt-2">
        <label class="fs-13">Language</label>
        <input type="text" class="form-control" name="lang" placeholder="Language (en/hi)">
      </div>
      <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-success text-white " onclick="filterResults('{{ route('admin.dashboard.free_kundali_matching.list') }}')">Apply</button>
      </div>
    </div>
  </div>
</div>
</div>

<div class="row">
  <div class="col-lg-6">
    <h1 class="text-primary float-left">Free Kundali Matching</h1>
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
  <table class="table" id="list-table" data-url="{{ route('admin.dashboard.free_kundali_matching.list') }}">
    <thead>
      <tr class="bg-primary text-white">
        <th>#</th>
        <th class="sortable" data-sort="boy_name">Boy name <i class="icon"></i></th>
        <th class="sortable" data-sort="girl_name">Girl name <i class="icon"></i></th>
        <th class="sortable selected" data-sort="boy_birth_date">Boy birth date <i class="icon"></i></th>
        <th class="sortable" data-sort="girl_birth_date">Girl birth date <i class="icon"></i></th>
        <th class="sortable" data-sort="lang">Language <i class="icon"></i></th>
      </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
      <tr>
        <td colspan="6" class="pagination"></td>
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
