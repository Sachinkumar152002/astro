@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
        <div class="mt-2">
          <label for="" class="fs-13">Product</label>
          <input type="text" class="form-control" name="product_id" placeholder="Search Proudct">
        </div>
        <div class="mt-2">
          <label for="" class="fs-13">User</label>
          <input type="text" class="form-control" name="review_by" placeholder="Search User">
        </div>
         <div class="mt-2">
           <label for="" class="fs-13"> Review </label>
           <input type="text" class="form-control" name="review" placeholder="Search Review">
         </div>

         <div class="mt-2">
           <label for="" class="fs-13">Rating</label>
           <div class="d-flex">
            <div class="">
                 <input type="number" class="form-control" name="rating_from" placeholder="From">
            </div>
            <div class="ml-2">
              <input type="number" class="form-control" name="rating_to" placeholder="To">
            </div>
           </div>
          </div>
          <div class="mt-2">
            <label for="" class="fs-13">Status</label>
            <select class="form-control" name="status">
              <option value="">Select Status</option>
              @forelse (statusArray() as $k=>$v)
                <option value="{{$k}}" {{ $status == $k ? "selected" : "" }}>{{$v}}</option>
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
          <button class="btn btn-success text-white mt-2" onclick="filterResults('{{ route('admin.product.review.all', ['productTypeName' => $productTypeName]) }}')">Apply</button>
        </div>
      </div>
    </div>
  </div>
<!-- filter-design-new-end -->
    <div class="row">
        <div class="col-md-6">
            <h1 class="text-primary float-left">Review</h1>
        </div>
          <div class="col-md-6">
            <div class="d-flex text-align-end justify-content-end">

              <button class="filter-div btn btn-success btn-search text-dark m-1 py-2">
                <i class="bi bi-funnel text-white"></i>
              </button>
            </div>
          </div>

    </div>
    <div class="table-responsive">
        <table class="table" id="list-table" data-url="{{ route('admin.product.review.all', ['productTypeName' => $productTypeName]) }}">
            <thead>
                <tr class="bg-primary text-white">
                    <th>All</th>
                    <th>#</th>
                    <th>Product <i class="icon"></th>
                      <th>User <i class="icon"></th>
                    <th>Review <i class="icon"></th>
                    <th>Rating <i class="icon"></th>
                    <th>Status <i class="icon"></th>
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
    @include('admin.media.view')
@endsection

@push('scripts')
    <script type="text/javascript">
        // var deleteUrl = '{{ route('admin.course.delete') }}';
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
