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
         <label for="" class="fs-13">Order Number</label>
        <input type="text" class="form-control" name="order_number" placeholder="Order Number">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Puja Name</label>
        <input type="text" class="form-control" name="product_name" placeholder="Puja Name">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Astrologer Name</label>
        <input type="text" class="form-control" name="astrologer_name" placeholder="Astrologer Name">
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
      @if(isset($type) && $type == 'group')
        <div class="mt-2">
          <label for="" class="fs-13">Family Reference</label>
          <input type="text" class="form-control" name="family_reference" placeholder="Family Reference (e.g., FM0001)">
        </div>
      @else
        <div class="mt-2">
          <label for="" class="fs-13">Status</label>
          <select class="form-control" name="status">
            <option value="">All</option>
            <option value="assigned">Assigned</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      @endif
      <button class="btn btn-success text-white mt-2" onclick="filterResults('{{ route('admin.booked-puja.all', ['type' => isset($type) ? $type : 'one-to-one']) }}')">Apply</button>
      </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Booked Puja</h1>
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
  <table class="table" id="list-table" data-url="{{route('admin.booked-puja.all', ['type' => isset($type) ? $type : 'one-to-one'])}}">
    <thead>
      <tr class="bg-primary text-white">
        <th># <i class="icon"></i></th>
        @if(!isset($type) || $type != 'group')
          <th>Order Number</th>
        @endif
        <th class="sortable" data-sort="product_id">Puja Name <i class="icon"></i></th>
        <th class="sortable" data-sort="astrologer_id">Astrologer <i class="icon"></i></th>
        @if(isset($type) && $type == 'group')
          <th class="sortable" data-sort="total_bookings">Total Booking <i class="icon"></i></th>
        @else
          <th>Video & Images</th>
          <th class="sortable" data-sort="status">Puja Status <i class="icon"></i></th>
        @endif
        <th class="sortable selected" data-sort="{{ isset($type) && $type == 'group' ? 'expire_at_date' : 'created_at' }}">
          {{ isset($type) && $type == 'group' ? 'Scheduled At' : 'Created At' }} <i class="icon"></i>
        </th>
        <th class="text-right"><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
      </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
      <tr>
        <td colspan="{{ isset($type) && $type == 'group' ? '7' : '8' }}" class="pagination"></td>
      </tr>
    </tfoot>
  </table>
</div><!--end .table-responsive -->

@endsection

@push('scripts')
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

function filterResults(url) {
  var formData = {};
  formData['type'] = '{{ isset($type) ? $type : "one-to-one" }}';
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
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
@endpush
