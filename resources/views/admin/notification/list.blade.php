@extends('layouts.admin')

@section('content')
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="px-0 w-100" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label for="user" class="fs-13">User/Astrologer</label>
         <input type="text" class="form-control" name="user" id="user">
       </div>
       {{--
       <div class="mt-2">
         <label for="astrologer" class="fs-13">Astrologer</label>
         <input type="text" class="form-control" name="astrologer" id="astrologer">
       </div>
       --}}
       <div class="mt-2">
         <label for="for" class="fs-13">For</label>
         <select class="form-control" name="for" id="for">
           <option value="">Select</option>
           <option value="0">All</option>
           <option value="1">User</option>
           <option value="2">Astrologer</option>
         </select>
       </div>
       {{--
       <div class="mt-2">
         <label for="view" class="fs-13">Viewed</label>
         <select class="form-control" name="view" id="view">
           <option value="">Select</option>
           <option value="0">No</option>
           <option value="1">Yes</option>
         </select>
       </div>
       --}}
       <div class="mt-2">
         <label for="on" class="fs-13">On</label>
         <select class="form-control" name="on" id="on">
           <option value="">Select</option>
           @foreach(notificationOn() as $key=>$value)
            <option value="{{ $key }}">{{ $value }}</option>
           @endforeach
         </select>
       </div>
       <div class="mt-2">
         <label for="status" class="fs-13">Status</label>
         <select class="form-control" name="status" id="status">
           <option value="">Select</option>
           <option value="0">Inactive</option>
           <option value="1">Active</option>
         </select>
       </div>
        <div class="mt-2">
          <label for="" class="fs-13">From</label>
          <input type="date" class="form-control" name="updated_at_from" id="updated_at_from">
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">To</label>
          <input type="date" class="form-control" name="updated_at_to" id="updated_at_to">
        </div>

        <button class="btn btn-success mt-3" onclick="listTable()">Apply</button>
     </div>
  </div>
    </div>
  </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-md-6 d-flex align-items-center">
  @if($user_id || $astrologer_id)
  <a href="{{route('admin.'.$nav.'.index', [0, 0])}}" class="mx-4 btn btn-primary">All</a>
  @endif
  <h1 class="text-primary float-left">{{$moduleName}}</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    @if(adminCan($nav, 'add'))
      <button class="btn btn-primary text-black m-1">
        <a href="{{route('admin.'.$nav.'.add', [$user_id, $astrologer_id])}}" class="text-white">
          <i class="bi bi-plus"></i>
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
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all', [$user_id, $astrologer_id])}}">
      <thead>
        <tr class="bg-primary text-white">
          <th>All</th>
          <th># <i class="icon"></i></th>
          <th class="sortable" data-sort="user">User <i class="icon"></i></th>
          <th class="sortable" data-sort="astrologer">Astrologer <i class="icon"></i></th>
          <th class="sortable" data-sort="for">For<i class="icon"></i></th>
          <!-- <th class="sortable" data-sort="view">Viewed <i class="icon"></i></th> -->
          <th class="sortable" data-sort="message">Message<i class="icon"></i></th>
          <th>On</th>
          <th>Status</th>
          <th class="sortable selected" data-sort="updated_at">Last Updated <i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
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
  var deleteUrl = '{{route('admin.'.$nav.'.delete', [$user_id, $astrologer_id])}}';
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
