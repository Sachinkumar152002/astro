@extends('layouts.admin')

@section('content')

@php
  $activeScopeList = ($activeDashboardScope ?? false) || ($activeAiDashboardScope ?? false) || ($newRequestsScope ?? false);
@endphp

<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
   <div class="w-100" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      @if(!empty($activeDashboardScope))
      <input type="hidden" name="active_dashboard_scope" value="1">
      @endif
      @if(!empty($activeAiDashboardScope))
      <input type="hidden" name="active_ai_dashboard_scope" value="1">
      @endif
      @if(!empty($newRequestsScope))
      <input type="hidden" name="new_requests_scope" value="1">
      @endif
      <div class="mt-2">
        <label for="" class="fs-13">UUID</label>
        <input type="text" class="form-control" name="uuid">
      </div>
       <div class="mt-2" style="position: relative;">
         <label for="" class="fs-13">Name</label>
         <input type="text" class="form-control" name="display_name" id="astrologer_name_input" autocomplete="off">
         <div id="astrologer_suggestions" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; display: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2);"></div>
       </div>
       <div class="mt-2 {{ $nav=='AI'?'d-none':'' }}">
         <label for="" class="fs-13">Phone</label>
         <input type="text" class="form-control" name="phone">
       </div>
       <div class="mt-2 {{ $nav=='AI'?'d-none':'' }}">
         <label for="" class="fs-13">Email</label>
         <input type="text" class="form-control" name="email">
       </div>
       @if(!$activeScopeList)
       <div class="mt-2 {{ $nav=='AI'?'d-none':'' }}">
         <label for="" class="fs-13">Available Status</label>
         <select class="form-control" name="available_status" id="available_status">
           <option value="">Select</option>
           <option value="1">Online</option>
           <option value="2">Offline</option>
           <option value="3">Busy</option>
           <option value="0">Hide</option>
         </select>
       </div>
       @endif
       {{--
       <div class="mt-2 {{ $nav=='AI'?'d-none':'' }}">
         <label for="" class="fs-13">Available Mode</label>
         <select class="form-control" name="available_mode" id="available_mode">
           <option value="">Select</option>
           <option value="call">Call</option>
           <option value="chat">Chat</option>
           <option value="video">Video</option>
         </select>
       </div>--}}
       <div class="mt-2">
         <label for="" class="fs-13">Status</label>
         <select class="form-control" name="status" id="status">
           <option value="">Select</option>
           @forelse (statusArray() as $k=>$v)
             <option value="{{$k}}" {{ request()->get("status") == "active" && $k == 1 ? "selected" : '' }}>{{$v}}</option>
           @empty
           <option value="0">Inactive</option>
           @endforelse
         </select>
       </div>

     <div class="mt-2 {{ $nav=='AI'?'d-none':'' }}">
       <label for="" class="fs-13">Verified</label>
       <select class="form-control" name="is_verified" id="is_verified">
       <option value="">Select</option>
       @forelse (yesNoArray() as $k=>$v)
         <option value="{{$k}}">{{$v}}</option>
       @empty
       <option value="0">Inactive</option>
       @endforelse
       </select>
     </div>
     <!-- <div class="mt-2">
       <label for="" class="fs-13">Trashed</label>
       <input type="" name="trashed" value="0" id="trashed">
     </div> -->
      @if(!$activeScopeList)
      <div class="mt-2">
        <label for="" class="fs-13">Date</label>
        <input type="date" class="form-control" name="updated_at">
      </div>
      @endif
      <div class="mt-3">
         <button class="btn btn-success" onclick="listTable()">Apply</button>
      </div>
       </div>
      </div>
    </div>
  </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-lg-4">

<h1 class="text-primary float-left">{{ !empty($activeDashboardScope) || !empty($activeAiDashboardScope) || !empty($newRequestsScope) ? $moduleName : ($moduleName=='AI'?'AI Astrologer':$moduleName) }} </h1>
<p class="h3 m-0 ml-3 pt-2 float-left">(Total - <span id="total-results">-</span>)</p>
</div>
<div class="col-md-8">

  <div class="d-flex text-align-center justify-content-end align-items-center">

    <div class="">
      <!-- <a class="btn btn-secondary text-white float-right mr-2">
      <input type="checkbox" onchange="loadTrashedRecords(this)" id="only_trashed"> Trashed <i class="bi bi-recycle"></i></i></a> -->
    </div>
    @if(adminCan($permissionModuleName ?? $moduleName,'add') && !$activeScopeList)
    <button class="btn btn-primary mr-2">
      <a href="{{route('admin.'.$nav.'.add')}}" class="text-white">
        <i class="bi bi-plus-lg"></i>
      </a>
    </button>
    @endif
    <!-- <button class="btn btn-seconday text-black mr-2">
      <i class="bi bi-copy"></i>
    </button>
    <button class="btn btn-danger text-white mr-2">
      <i class="bi bi-trash"></i>
    </button> -->
    <button class="btn btn-success m-1 py-2 text-white" onclick="exportCsv(this, '{{ route('admin.'.$nav.'.export') }}')">Export</button>
    <button class="filter-div btn btn-success btn-search text-white mr-1 py-2">
    Filter  <i class="bi bi-funnel text-white"></i>
    </button>

  </div>

</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.'.$nav.'.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <!-- <th>All <i class="icon"></i></th> -->
          <th># <i class="icon"></i></th>
          <th>Image</th>
          <th>UUID</th>
          <th>Name</th>
          <th class="{{ $nav=='AI'?'d-none':'' }}">Phone</th>
          <th class="{{ $nav=='AI'?'d-none':'' }}">Email</th>
          <th class="{{ $nav=='AI'?'d-none':'' }}">Package</th>
          @if(!$activeScopeList)
          <th class="{{ $nav=='AI'?'d-none':'' }}">Notification</th>
          @if(adminCan('withdraw','any'))
            <th class="{{ $nav=='AI'?'d-none':'' }}">Withdraw</th>
          @endif
          @endif
          <th>Status</th>
          <th class="{{ $nav=='AI'?'d-none':'' }}">Verified</th>
          <th>Last Updated</th>
          <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="{{ $activeScopeList ? 11 : 10 }}" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->

@endsection

@push('scripts')
<script type="text/javascript">
  var deleteUrl = '{{route('admin.'.$nav.'.delete')}}';
  var loadTrashedRecords = function(){
    if($('#only_trashed').is(':checked')){
      $('#trashed').val(1);
      listTable();
    }else {
      resetFilter();
    }
  }
  var unverifiedRecords = function(){
    $('#is_verified').val('');
    if($('#unverified_data').is(':checked')){
      $('#is_verified').val(0);
    }
    listTable();
  }
  var inactiveRecords = function(){
    $('#status').val('');
    if($('#inactive_data').is(':checked')){
      $('#status').val(0);
    }
    listTable();
  }


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

// Astrologer name autocomplete
var typingTimer;
var suggestionsContainer = $('#astrologer_suggestions');
var astrologerInput = $('#astrologer_name_input');

astrologerInput.on('input', function() {
  clearTimeout(typingTimer);
  var query = $(this).val().trim();

  if(query.length < 2) {
    suggestionsContainer.hide().empty();
    return;
  }

  typingTimer = setTimeout(function() {
    $.ajax({
      url: '{{ route("admin.".$nav.".suggestions") }}',
      method: 'GET',
      data: { q: query },
      success: function(response) {
        suggestionsContainer.empty();

        if(response.length > 0) {
          response.forEach(function(astrologer) {
            var suggestionItem = $('<div class="suggestion-item" style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;">' +
              '<strong>' + astrologer.name + '</strong>' +
              '</div>');

            suggestionItem.on('click', function() {
              astrologerInput.val(astrologer.name);
              suggestionsContainer.hide().empty();
            });

            suggestionItem.on('mouseenter', function() {
              $(this).css('background-color', '#f5f5f5');
            });

            suggestionItem.on('mouseleave', function() {
              $(this).css('background-color', 'white');
            });

            suggestionsContainer.append(suggestionItem);
          });
          suggestionsContainer.show();
        } else {
          suggestionsContainer.hide();
        }
      },
      error: function() {
        suggestionsContainer.hide().empty();
      }
    });
  }, 300);
});

// Hide suggestions when clicking outside
$(document).on('click', function(e) {
  if(!$(e.target).closest('#astrologer_name_input, #astrologer_suggestions').length) {
    suggestionsContainer.hide();
  }
});

// Handle keyboard navigation
astrologerInput.on('keydown', function(e) {
  var suggestions = suggestionsContainer.find('.suggestion-item');
  var currentIndex = suggestions.index(suggestions.filter('.highlighted'));

  if(e.key === 'ArrowDown') {
    e.preventDefault();
    suggestions.removeClass('highlighted');
    var nextIndex = currentIndex < suggestions.length - 1 ? currentIndex + 1 : 0;
    suggestions.eq(nextIndex).addClass('highlighted').css('background-color', '#f5f5f5');
  } else if(e.key === 'ArrowUp') {
    e.preventDefault();
    suggestions.removeClass('highlighted');
    var prevIndex = currentIndex > 0 ? currentIndex - 1 : suggestions.length - 1;
    suggestions.eq(prevIndex).addClass('highlighted').css('background-color', '#f5f5f5');
  } else if(e.key === 'Enter') {
    e.preventDefault();
    var highlighted = suggestions.filter('.highlighted');
    if(highlighted.length) {
      highlighted.click();
    }
  } else if(e.key === 'Escape') {
    suggestionsContainer.hide();
  }
});
</script>
@endpush
