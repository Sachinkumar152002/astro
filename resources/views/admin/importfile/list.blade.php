@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
  <div class="">
    <div class="row search-filters" id="filters">
      <div class="px-0 w-100" id="filter_row">
        <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
        <div class="mt-2">
          <label for="type" class="fs-13">Type</label>
          <select class="form-control" name="type">
            <option value="">Select</option>
            <option value="panchang">Punchang</option>
          </select>
        </div>
        <div class="mt-2">
          <label for="is_processed" class="fs-13">Is Porcessed</label>
          <select class="form-control" name="is_processed">
            <option value="">Select</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">Last Updated</label>
          <div class="d-flex">
           <div class="">
              <input type="date" class="form-control" name="updated_at_from">
           </div>
           <div class="ml-2">
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
    <button class="btn btn-success text-dark m-1 d-none" onclick="filterResults('{{ route('admin.course.all') }}')">Filter</button>
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
          <th># <i class="icon"></i></th>
          <th>Type</th>
          <th>Is Processed</th>
          <th>Total</th>
          <th>Processed</th>
          <th>Last Updated</th>
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
search.click(function(){
  filter_show();
});

close.click(function(){
  filter_hide();
});

function filter_show(){
  $('#filters').addClass('filtered');
  overlay.show(20);
}

function filter_hide(){
  $('#filters').removeClass('filtered');
  overlay.hide();
}
</script>
@endpush
