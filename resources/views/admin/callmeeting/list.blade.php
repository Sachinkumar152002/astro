@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">

       <div class="" id="filter_row">
    <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-4">
         <label for="" class="fs-13">{{$userType}} Name</label>
         <input type="text" class="form-control" name="astrologer_name">
           </div>
       <div class="mt-2">
         <label for="" class="fs-13">User Name</label>
         <input type="text" class="form-control" name="user_name">
       </div>
     {{--<div class="mt-2">
       <label for="" class="fs-13">Schedule For</label>
       <select class="form-control" name="scheduled_for">
         <option value="">Select</option>
         <option value="0">Now</option>
         <option value="1">Later</option>
       </select>
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Time Slot</label>
       <select class="form-control" name="timeslot">
         <option value="">Select</option>
         <option value="15">15 Min</option>
         <option value="30">30 Min</option>
       </select>
     </div>--}}
     <div class="mt-2">
       <label for="" class="fs-13">Price</label>
       <div class="d-flex">
        <div class="">
        <input type="number" class="form-control" name="total_price_from">
        </div>
        <div class="ml-2">
          <input type="number" class="form-control" name="total_price_to">
          </div>
       </div>
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Status</label>
       <select class="form-control" name="status">
         <option value="">Select</option>
         <option value="booked">Booked</option>
         <option value="reschedule">Rescheduled</option>
         <option value="pending">Pending</option>
         <option value="complete">Complete</option>
         <option value="other">Other</option>
       </select>
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Date</label>
       <div class="d-flex">
         <div class="">
           <input type="date" class="form-control" name="updated_at_from">
         </div>
         <div class="">
           <input type="date" class="form-control" name="updated_at_to">
         </div>
       </div>
     </div>
     <button class="btn btn-success text-white mt-3" onclick="listTable('{{route('admin.consultation.call.all', [$userType])}}')">Apply</button>

    </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-md-6">
<h1 class="text-primary float-left">Call Consultation</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    <button class="btn btn-success text-dark m-1 d-none" onclick="filterResults('{{ route('admin.consultation.call.all', [$userType]) }}')">Filter</button>
    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
    Filter <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.consultation.call.all', [$userType])}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th >{{$userType}} <i class="icon"></i></th>
          <th >User <i class="icon"></i></th>
          <th >Schedule For <i class="icon"></i></th>
          <th >Time Slot <i class="icon"></i></th>
          <th >Price <i class="icon"></i></th>
          <th >Status<i class="icon"></i></th>
          {{--<th >Cancel<i class="icon"></i></th>
          <th >Reschedule<i class="icon"></i></th>--}}
          <th class="sortable selected" data-sort="updated_at">Updated At<i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
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
