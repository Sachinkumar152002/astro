@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
    <div class="w-100" id="filter_row">
    <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-4">
         <label for="" class="fs-13">Title</label>
        <input type="text" class="form-control" name="title" placeholder="Search Title">
       </div>
       <button class="btn btn-success text-white mt-3" onclick="filterResults('{{route('admin.software.feature.all', [$data_id])}}')">Apply</button>

   </div>
  </div>
  </div>
  </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-lg-6">
<h1 class="text-primary float-left">{{$moduleName}}</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    <button class="btn btn-primary m-1">
      <a href="{{route('admin.software.feature.add', [$data_id])}}" class="text-white">
        <i class="bi bi-plus-lg"></i>
      </a>
    </button>
    <button class="btn btn-seconday text-black m-1">
      <i class="bi bi-copy"></i>
    </button>
    <button class="btn btn-danger text-white m-1">
      <i class="bi bi-trash"></i>
    </button>
    <button class="btn btn-success text-dark m-1 d-none" onclick="filterResults('{{ route('admin.course.all') }}')">Filter</button>
    <button class="filter-div btn btn-success btn-search text-dark m-1 py-2">
      <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.software.feature.all', [$data_id])}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th>Image</th>
          <th class="sortable" data-sort="title">Title <i class="icon"></i></th>
          <th class="sortable" data-sort="order">Order <i class="icon"></i></th>
          <th class="sortable selected" data-sort="updated_at">Last Updated <i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>

      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="12" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->
    @include('admin.media.view')

@endsection

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.software.feature.delete', [$data_id])}}';
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
