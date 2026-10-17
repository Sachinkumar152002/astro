@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">

    <div class="" id="filter_row">
    <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-4">
         <label for="" class="fs-13">Name</label>
         <input type="text" class="form-control" name="name" placeholder="Search Name">
       </div>
     <div class="mt-2">
       <label for="" class="fs-13">Status</label>
       <select class="form-control" name="status">
         <option value="">Select Status</option>
         @forelse (statusArray() as $k=>$v)
           <option value="{{$k}}">{{$v}}</option>
         @empty
         <option value="0">Inactive</option>
         @endforelse
       </select>
     </div>
      <div class="mt-2">
       <label for="" class="fs-13">Date Range</label>
       <div class="d-flex">
        <div class="">
          <input type="date" class="form-control" name="updated_at_from">
        </div>
        <div class="ml-2">
          <input type="date" class="form-control" name="updated_at_to">
          </div>
       </div>
      </div>
     <button class="btn btn-success text-dark mt-3" onclick="filterResults('{{ route('admin.course.module.all', $course_id) }}')">Apply</button>
      </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-md-6">
<h2 class="text-primary float-left">{{$course->name}} Modules</h2>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    <button class="btn btn-primary m-1">
      <a href="{{route('admin.course.module.add',[$course_id])}}" class="text-white">
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
      <table class="table" id="list-table" data-url="{{route('admin.course.module.all', $course_id)}}">
      <thead>
        <tr class="bg-primary text-white">
          <th>#</th>
          <th class="sortable selected" data-sort="name">Name <i class="icon"></th>
          <th>Order</th>

          <!-- <th>Content</th> -->
          <th class="sortable" data-sort="updated_at">Updated At <i class="icon"></th>
            <th class="sortable" data-sort="status">Status <i class="icon"></th>
          <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>

        <tr id="filter_row" class="d-none">
          <th></th>
          <th>
            <input type="text" class="form-control" name="name" placeholder="Search Name">
          </th>
          <th></th>
          <th>
            <select class="form-control" name="status">
              <option value="">Select Status</option>
              @forelse (statusArray() as $k=>$v)
                <option value="{{$k}}">{{$v}}</option>
              @empty
              <option value="0">Inactive</option>
              @endforelse
            </select>
          </th>
          <!-- <th></th> -->
          <th>
            <div class="">
              <input type="date" class="form-control" name="updated_at_from">
              <input type="date" class="form-control" name="updated_at_to">
          </div>
          </th>
          <th>
            <button class="btn btn-success text-dark" onclick="filterResults('{{ route('admin.course.module.all', $course_id) }}')">Filter</button>
          </th>
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
@endsection

@push('scripts')

<script type="text/javascript">
     var deleteUrl = '{{route('admin.course.module.delete', [$course_id])}}';
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
