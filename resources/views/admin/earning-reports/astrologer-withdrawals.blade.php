@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="filter-backdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;"></div>
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      <div class="mt-2" style="position: relative;">
         <label for="" class="fs-13">Astrologer Name</label>
         <input type="text" class="form-control" name="astrologer_name" id="astrologer_name_input" placeholder="Astrologer Name" autocomplete="off">
         <div id="astrologer_suggestions" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; display: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2);"></div>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Status</label>
         <select class="form-control" name="status">
           <option value="">Select</option>
           <option value="pending">Pending</option>
           <option value="done">Done</option>
           <option value="cancel">Cancel</option>
         </select>
      </div>
      <div class="mt-2">
        <label for="" class="fs-13">Date Range</label>
        <div class="d-flex">
          <div class="">
            <input type="date" class="form-control" name="date_from" placeholder="From Date">
        </div>
        <div class="ml-2">
          <input type="date" class="form-control" name="date_to" placeholder="To Date">
      </div>
        </div>
      </div>
      <button class="btn btn-success text-white mt-2" onclick="filterResults('{{ route('admin.earning-reports.astrologer-withdrawals.list') }}')">Apply</button>
    </div>
  </div>
</div>
</div>
</div>
<!-- filter-design-new-end -->
      <div class="row p-3">
        <div class="col-lg-6">
            <h1 class="text-primary float-left">Astrologer Withdrawals Report</h1>
        </div>
        <div class="col-md-6">
          <div class="d-flex text-align-end justify-content-end">
            <div id="download-btn-container" style="display: none; margin-right: 10px;">
              <button class="btn btn-primary text-white m-1 py-2" onclick="downloadReport()">
                <i class="bi bi-download"></i> Download Data
              </button>
            </div>
            <button class="filter-div btn btn-success btn-search m-1 py-2">
            Filter <i class="bi bi-funnel text-white"></i>
            </button>
          </div>
        </div>
    </div>

    <div class="table-responsive p-3">
        <table class="table" id="list-table" data-url="{{ route('admin.earning-reports.astrologer-withdrawals.list') }}">
            <thead>
                <tr class="bg-primary text-white">
                    <th>Sr.No</th>
                    <th class="sortable selected" data-sort="created_at">Date <i class="icon"></i></th>
                    <th>Astrologer Name</th>
                    <th>Available Balance</th>
                    <th>Request Amount</th>
                    <th>Wallet Balance</th>
                    <th>Status</th>
                    <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
                </tr>
              </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="pagination-container" class="mt-3 p-3"></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Filter button functionality
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

    loadList();

    function loadList() {
        var url = $('#list-table').data('url');
        var filterData = getFilterData();
        $.ajax({
            url: url,
            type: 'GET',
            data: filterData,
            success: function(response) {
                $('tbody').html(response.rows);
                $('#pagination-container').html(response.pagination);
                reinitPagination();
                // Show/hide download button based on data
                if(response.rows && response.rows.trim() !== '' && !response.rows.includes('No Records')) {
                    $('#download-btn-container').show();
                } else {
                    $('#download-btn-container').hide();
                }
            }
        });
    }

    function reinitPagination() {
        // Use event delegation since pagination is dynamically loaded
        $(document).off('click', '#pagination-container .pagination a');
        $(document).on('click', '#pagination-container .pagination a', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            if(href) {
                // Extract page number from URL
                var urlParts = href.split('?');
                var baseUrl = urlParts[0];
                var params = urlParts[1] || '';
                var filterData = getFilterData();

                // Parse URL parameters
                if(params) {
                    var urlParams = new URLSearchParams(params);
                    if(urlParams.has('page')) {
                        filterData.page = urlParams.get('page');
                    }
                }

                $.ajax({
                    url: baseUrl,
                    type: 'GET',
                    data: filterData,
                    success: function(response) {
                        $('tbody').html(response.rows);
                        $('#pagination-container').html(response.pagination);
                        // reinitPagination will be called again after HTML is updated
                    }
                });
            }
        });
    }

    function getFilterData() {
        var data = {};
        $('input[name], select[name]').each(function() {
            if($(this).val()) {
                data[$(this).attr('name')] = $(this).val();
            }
        });
        return data;
    }

    window.filterResults = function(url) {
        loadList();
        filter_hide();
    };

    window.resetFilter = function() {
        $('input[name], select[name]').val('');
        loadList();
    };

    // Sort functionality
    $(document).on('click', '.sortable', function() {
        var sortBy = $(this).data('sort');
        var sortDir = $(this).hasClass('selected') && $(this).find('.icon').hasClass('fa-arrow-up') ? 'asc' : 'desc';
        loadList();
    });

    // Download report function
    window.downloadReport = function() {
        var filterData = getFilterData();
        var url = '{{ route('admin.earning-reports.astrologer-withdrawals.pdf') }}?' + $.param(filterData);
        window.open(url, '_blank');
    };

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
                url: '{{ route("admin.earning-reports.astrologer.suggestions") }}',
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
});
</script>
@endpush
