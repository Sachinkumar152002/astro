@extends('layouts.admin')

@section('content')

<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="w-100" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <!-- <div class="mt-2">
         <label for="" class="fs-13"> Name </label>
          <input type="hidden" name="trashed" value="0" id="trashed">
         </div> -->
       <div class="mt-2">
         <label for="" class="fs-13">Name</label>
    <input type="text" class="form-control" name="course_name" placeholder="Search Course">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Date</label>
         <div class="">
           <input type="date" class="form-control" name="expire_date_from" placeholder="From">
           <input type="date" class="form-control mt-3" name="expire_date_to" placeholder="To">
         </div>
      </div>

      <div class="mt-2">
        <label for="" class="fs-13">Status</label>
        <select class="form-control" name="status">
          <option value="">Select</option>
          @forelse (statusArray() as $k=>$v)
            <option value="{{$k}}">{{$v}}</option>
          @empty
          <option value="0">Inactive</option>
          @endforelse
        </select>
      </div>
      <button class="btn btn-success text-white mt-3" onclick="filterResults('{{ route('admin.customer.course.all', [$user_id]) }}')">Apply</button>

      </div>

    </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
<div class="row p-3">
<div class="col-md-6">
<h1 class="text-primary">{{$moduleName}}</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
  <a href="{{route('admin.customer.course.add', [$user_id])}}">
    <button class="btn btn-primary text-black m-1">
    <i class="bi bi-plus"></i>
    </button>
    </a>

    <button class="filter-div btn btn-success btn-search text-dark m-1 py-2">
      <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>

    <div class="table-responsive p-3">
      <table class="table" id="list-table" data-url="{{route('admin.customer.course.all', [$user_id])}}">
      <thead>
        <tr class="bg-primary text-white">
          <th>All</th>
          <th># <i class="icon"></i></th>
          <th class="sortable " data-sort="course_name">Course <i class="icon"></i></th>
          <th class="sortable " data-sort="amount">Price <i class="icon"></i></th>
          <th class="sortable " data-sort="expiry_date">Expiry <i class="icon"></i></th>
          <th class="sortable" data-sort="status">Status <i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
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
  var deleteUrl = '{{route('admin.customer.course.delete', [$user_id])}}';
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
