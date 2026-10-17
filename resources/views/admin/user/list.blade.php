
@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="w-100" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
    <div class="mt-2">
     <label for="" class="fs-13">Name</label>
     <input type="text" class="form-control" name="name">
    </div>
     <div class="mt-2">
    <label for="" class="fs-13">Email</label>
     <input type="text" class="form-control" name="email">
     </div>
     <div class="mt-2">
         <label for="" class="fs-13">Status</label>
     <select class="form-control" name="status">
    <option value="">Select</option>
    @forelse (statusArray() as $k=>$v)
    <option value="{{$k}}">{{$v}}</option>
    @empty
    <option value="0">Inactive</option>
    @endforelse
    </select>
       </div>
         <div class="mt-2">
     <label for="" class="fs-13">Role</label>
     <select class="form-control" name="role_id">
               <option value="">Select</option>
               @foreach (App\Model\Role::pluck('name', 'id')->toArray() as $k=>$v)
                 <option value="{{$k}}">{{$v}}</option>
               @endforeach
           </select>
         </div>
      <div class="mt-2">
    <label for="" class="fs-13">Date</label>
    <input type="date" class="form-control" name="updated_at">
      </div>
    <button class="btn btn-success text-white mt-3" onclick="listTable()">Apply</button>
     </div>
  </div>
    </div>
  </div>
<!-- filter-design-new-end -->
<!-- BEGIN TABLE HOVER -->
    <section>
    <!-- BEGIN INTRO -->
    <div class="row">
    <div class="col-md-6">
    <h1 class="text-primary">Admin List</h1>
    </div>
    <div class="col-md-6">
      <div class="d-flex text-align-end justify-content-end">
                  @if(adminCan('admin', 'add'))
                  <button class="btn btn-primary m-1">
                    <a href="{{route('admin.user.add')}}" class="text-white">
              <i class="bi bi-plus"></i>
                    </a>
        </button>

                  @endif

        <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
          Filter <i class="bi bi-funnel text-white"></i>
        </button>
      </div>
    </div>
    </div><!--end .row -->
    <!-- END INTRO -->
    </section>

    <!-- BEGIN TABLE HOVER -->
    <section class="style-default-bright">
    <div class="section-body">
    <table class="table" id="list-table" data-url="{{route('admin.user.list')}}">
    <thead>
          <tr class="bg-primary text-white">
          <th class="sortable" data-sort="status">#<i class="icon"></i></th>
          <th class="sortable" data-sort="name">Name <i class="icon"></th>
            <th class="sortable" data-sort="email">Email <i class="icon"></i></th>
            <th class="sortable" data-sort="role_id">Role <i class="icon"></i></th>
          <th class="sortable" data-sort="status">Status <i class="icon"></i></th>
          <th class="sortable selected" data-sort="updated_at">Last Updated <i class="icon"></i></th>
          <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
        </tr>
       </thead>
       <tbody>

       </tbody>

      <tfoot>
          <tr>
              <td colspan="8" class="pagination"></td>
          </tr>
      </tfoot>

    </table>
    </div><!--end .section-body -->
    </section>
    <!-- END TABLE HOVER -->
    </div><!--end #content-->
    <!-- END CONTENT -->
@endsection

@push('scripts')
<script type="text/javascript">
     var deleteUrl = '{{route('admin.user.delete')}}';
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
