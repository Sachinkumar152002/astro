

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
       <label for="" class="fs-13">Type</label>
       <select class="form-control" name="type">
         <option value="">Select Type</option> @foreach (courseTypes() as $k=>$v) <option value="{{$k}}">{{$v}}</option> @endforeach
       </select>
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Status</label>
       <select class="form-control" name="status">
         <option value="">Select Status</option> @forelse (statusArray() as $k=>$v) <option value="{{$k}}">{{$v}}</option> @empty <option value="0">Inactive</option> @endforelse
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
     <button class="btn btn-success  m-2 text-white" onclick="filterResults('{{ route('admin.course.all') }}')">Apply</button>
     <div class="d-none">
       <th colspan="2"><input type="text" class="form-control" name="course_category_id" placeholder="Search Category"></th>
        <th class=""><div class=""><input type="number" class="form-control" name="price_from" placeholder="From"><input type="number" class="form-control" name="price_to" placeholder="To"></div></th>
        <th class=""><select class="form-control" name="publish"><option value="">Select </option><option value="1">Publish</option><option value="0">Unpublish</option></select></th>
     </div>

      </div>
    </div>
    </div>
    </div>
<!-- filter-design-new-end -->
    <div class="row">
        <div class="col-md-6">
        <h1 class="text-primary">Courses</h1>
      </div>
      <div class="col-md-6">
        <div class="d-flex text-align-end justify-content-end">
          @if(adminCan('course', 'add'))
          <button class="btn btn-primary m-1">
            <a href="{{ route('admin.course.add') }}" class="text-white">
              <i class="bi bi-plus-lg"></i>
            </a>
          </button>
          <button class="btn btn-seconday text-black m-1">
            <i class="bi bi-copy"></i>
          </button>
          @endif

          @if(adminCan('course', 'delete'))
          <button class="btn btn-danger text-white m-1">
            <i class="bi bi-trash"></i>
          </button>
          @endif
          <button class="btn btn-success text-dark m-1 d-none" onclick="filterResults('{{ route('admin.course.all') }}')">Filter</button>
          <button class="filter-div btn btn-success btn-search text-dark m-1 py-2">
            <i class="bi bi-funnel text-white"></i>
          </button>
        </div>
      </div>
    <div class="table-responsive">
        <table class="table" id="list-table" data-url="{{ route('admin.course.all') }}">
            <thead>
                <tr class="bg-primary text-white">
                    <th>All</th>
                    <th>#</th>
                    <th>Image</th>
                    <th class="sortable" data-sort="name" colspan="2">Name <i class="icon"></th>
                    <th class="sortable" data-sort="type">Type <i class="icon"></th>
                    <!-- <th class="sortable" data-sort="course_category_id">Category <i class="icon"></th>
                    <th class="sortable" data-sort="price">Price <i class="icon"></th> -->
                      <th class="sortable" data-sort="publish">Publish <i class="icon"></th>
                    <th>Modules</th>
                    <th>Benefits</th>
                    <th class="sortable" data-sort="status">Status <i class="icon"></th>
                    <th class="sortable selected" data-sort="updated_at">Updated At <i class="icon"></th>

                    <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
                </tr>

                <tr class="d-none" id="filter_row">

                  <th scope="row"> <input type="checkbox" name="selectall" value=""> </th>
                  <th></th>

                  <th colspan=""><input type="text" class="form-control" name="name" placeholder="Search Name"></th>
                  <th colspan="">
                    <select class="form-control" name="type">
                      <option value="">Select Type</option>
                      @foreach (courseTypes() as $k=>$v)
                        <option value="{{$k}}">{{$v}}</option>
                      @endforeach
                    </select>
                  </th>
                  <th></th>
                  <th></th>
                  <!-- <th colspan="2"><input type="text" class="form-control" name="course_category_id" placeholder="Search Category"></th> -->
                  <!-- <th>
                    <div class="">
                      <input type="number" class="form-control" name="price_from" placeholder="From">
                      <input type="number" class="form-control" name="price_to" placeholder="To">
                  </div>
                </th> -->
                <!-- <th>
                  <select class="form-control" name="publish">
                    <option value="">Select </option>
                    <option value="1">Publish</option>
                    <option value="0">Unpublish</option>
                  </select>
                </th> -->
                <th colspan="">
                  <select class="form-control" name="status">
                    <option value="">Select Status</option>
                    @forelse (statusArray() as $k=>$v)
                      <option value="{{$k}}">{{$v}}</option>
                    @empty
                    <option value="0">Inactive</option>
                    @endforelse
                  </select>
                </th>

                  <th colspan="">  <input type="date" class="form-control" name="updated_at_from"></th>
                  <th>
                  <input type="date" class="form-control" name="updated_at_to">
                </th>

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
        var deleteUrl = '{{ route('admin.course.delete') }}';
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
