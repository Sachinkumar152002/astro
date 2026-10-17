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
         <label for="" class="fs-13">Astrologer Name</label>
        <input type="text" class="form-control" name="astrologer_name">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">User Name</label>
        <input type="text" class="form-control" name="user_name">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Date</label>
         <input type="date" class="form-control" name="date">
      </div>
       <button class="btn btn-success mt-2" onclick="listTable()">Apply</button>
      </div>
    </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
<div class="px-3">
  <div class="row">
  <div class="col-md-6">
  <h1 class="text-primary">Suggested Remedies by Astrologer</h1>
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
        <table class="table" id="list-table" data-url="{{route('admin.suggested_remedies.all')}}">
        <thead>
          <tr class="bg-primary text-white">
            <th># <i class="icon"></i></th>
            <th class="sortable" data-sort="astrologer_id">Astrologer Name <i class="icon"></i></th>
            <th class="sortable" data-sort="user_id">User Name <i class="icon"></i></th>
            <th class="sortable" data-sort="referral_code">Used Referral Code <i class="icon"></i></th>
            <th class="sortable selected" data-sort="created_at">Date <i class="icon"></i></th>
            <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="pagination"></td>
            </tr>
        </tfoot>
      </table>
      </div>
</div>
    <!--end .table-responsive -->
@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('theme-admin/js/listtable.js')}}"> </script>
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
</script>
@endpush
