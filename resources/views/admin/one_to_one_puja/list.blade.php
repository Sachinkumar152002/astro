@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="filter-backdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;"></div>
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label for="" class="fs-13">Puja Name</label>
        <input type="text" class="form-control" name="title" placeholder="Search Puja Name">
       </div>
      <div class="mt-2">
       <label for="" class="fs-13">Price Range</label>
       <div class="d-flex">
        <div class="">
            <input type="number" class="form-control" name="base_price_from" placeholder="From">
        </div>
        <div class="ml-2">
          <input type="number" class="form-control" name="base_price_to" placeholder="To">
          </div>
       </div>
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
      <button class="btn btn-success text-white mt-2" onclick="filterResults('{{ route('admin.'.$nav.'.all') }}')">Apply</button>
      </div>
    </div>
    </div>
    </div>
 <!-- filter-design-new-end -->
<div class="row">
<div class="col-lg-12">
  <div class="d-flex text-align-end justify-content-end">
    <a class="btn btn-primary m-1 text-white" href="{{route('admin.'.$nav.'.add')}}">
      <i class="bi bi-plus-lg"></i>
    </a>
    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
      Filter <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
<div class="col-lg-12">
<h1 class="text-primary float-left">{{$moduleName}}</h1>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th>Image</th>
          <th class="sortable" data-sort="title">Puja Name <i class="icon"></i></th>
          <th class="sortable" data-sort="base_price">Price <i class="icon"></i></th>
          <th>Astrologers</th>
          <th class="sortable" data-sort="status">Status <i class="icon"></i></th>
          <th class="sortable selected" data-sort="updated_at">Last Updated <i class="icon"></i></th>
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

function filterResults(url) {
  var formData = {};
  $('#filter_row input, #filter_row select').each(function() {
    var name = $(this).attr('name');
    var value = $(this).val();
    if(name && value) {
      formData[name] = value;
    }
  });

  var newUrl = url + '?' + $.param(formData);
  $('#list-table').attr('data-url', newUrl);
  listTable();
  filter_hide();
}
</script>
@endpush
