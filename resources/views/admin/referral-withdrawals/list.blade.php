@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2" style="position: relative;">
         <label for="" class="fs-13">Astrologer</label>
         <input type="text" class="form-control" name="astrologer_name" id="astrologer_name_input" placeholder="Search by name" autocomplete="off">
         <div id="astrologer_suggestions" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; display: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2);"></div>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Status</label>
         <select class="form-control" name="status">
           <option value="">Select</option>
           <option value="pending">Pending</option>
           <option value="approved">Approved</option>
           <option value="rejected">Rejected</option>
           <option value="processed">Processed</option>
         </select>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Wallet Type</label>
         <select class="form-control" name="wallet_type">
           <option value="">Select</option>
           <option value="referral">Referral</option>
           <option value="commission">Commission</option>
           <option value="both">Both</option>
         </select>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Date From</label>
         <input type="date" class="form-control" name="created_at_from">
      </div>
       <div class="mt-2">
         <label for="" class="fs-13">Date To</label>
         <input type="date" class="form-control" name="created_at_to">
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
  <h1 class="text-primary">Withdrawal Requests</h1>
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
        <table class="table" id="list-table" data-url="{{route('admin.referral_withdrawals.all')}}">
        <thead>
          <tr class="bg-primary text-white">
            <th># <i class="icon"></i></th>
            <th class="sortable" data-sort="astrologer_id">Astrologer <i class="icon"></i></th>
            <th class="sortable" data-sort="wallet_type">Wallet Type <i class="icon"></i></th>
            <th class="sortable" data-sort="referral_amount">Referral Amount <i class="icon"></i></th>
            <th class="sortable" data-sort="commission_amount">Commission Amount <i class="icon"></i></th>
            <th class="sortable" data-sort="total_amount">Total Amount <i class="icon"></i></th>
            <th class="sortable" data-sort="status">Status <i class="icon"></i></th>
            <th class="sortable" data-sort="created_at">Requested At<i class="icon"></i></th>
            <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="pagination"></td>
            </tr>
        </tfoot>
      </table>
      </div>
</div>
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
      url: '{{ route("admin.referral_withdrawals.astrologer.suggestions") }}',
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
