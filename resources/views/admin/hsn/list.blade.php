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
       <label for="" class="fs-13">Code</label>
         <input type="text" class="form-control" name="hsn_code" placeholder="Search Code">
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Percentage</label>
       <input type="text" class="form-control" name="gst_percentage" placeholder="Search Percentage">
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
     <button class="btn btn-success text-white mt-3" onclick="filterResults('{{ route('admin.hsn.all') }}')">Apply</button>
      </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
    <div class="row">
        <div class="col-md-6">
            <h1 class="text-primary float-left">{{ $moduleName }}</h1>
        </div>
        <div class="col-md-6">
          <div class="d-flex text-align-end justify-content-end">
            @if(adminCan($moduleKey, 'add'))
              <button class="btn btn-primary m-1">
                <a href="{{ route('admin.hsn.add') }}" class="text-white">
                  <i class="bi bi-plus-lg"></i>
                </a>
              </button>
            @endif

            <button class="btn btn-success text-dark m-1 d-none" onclick="filterResults('{{ route('admin.hsn.all') }}')">Filter</button>
            <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
              Filter <i class="bi bi-funnel text-white"></i>
            </button>
          </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table" id="list-table" data-url="{{ route('admin.hsn.all') }}">
            <thead>
                <tr class="bg-primary text-white">
                    <th>#</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Percentage</th>
                    <th class="sortable selected" data-sort="updated_at">Updated At <i class="icon"></th>
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
        var deleteUrl = '{{ route('admin.hsn.delete') }}';
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
