@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
      <div class="mt-2">
        <label for="" class="fs-13"> Title </label>
         <input type="text" class="form-control" name="title">
      </div>
       <div class="mt-2">
         <label for="" class="fs-13">Page</label>
         <select class="form-control" name="page">
           @foreach(pages() as $key => $item)
           <option value="{{$key}}">{{$item}}</option>
           @endforeach
         </select>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Status</label>
         <select class="form-control" name="display_on">
           <option value="">Select</option>
           <option value="page">Page</option>
           <option value="popup">Popup</option>
         </select>
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Status</label>
         <select class="form-control" name="status">
           <option value="">Select</option>
           <option value="1">Active</option>
           <option value="0">Inactive</option>
         </select>
       </div>
        <div class="mt-2">
          <label for="" class="fs-13"> Start </label>
           <input type="date" class="form-control" name="start_date">
        </div>
         <div class="mt-2">
           <label for="" class="fs-13"> End </label>
            <input type="date" class="form-control" name="end_date">
         </div>
        <div class="mt-2">
          <label for="" class="fs-13">Updated At</label>
          <div class="d-flex">
            <div class="">
              <input type="date" class="form-control" name="updated_at_from">
            </div>
            <div class="">
              <input type="date" class="form-control" name="updated_at_to">
            </div>
          </div>
        </div>
      <button class="btn btn-success mt-3" onclick="listTable()">Apply</button>
    </div>

    </div>
    </div>
  </div>
<!-- filter-design-new-end -->
<div class="row">
<div class="col-md-6">
<h1 class="text-primary float-left">Advertisements</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    @if(adminCan('advertisement', 'add'))

      <a href="{{route('admin.advertisement.add')}}" class="text-white">
        <button class="btn btn-primary m-1">
      <i class="bi bi-plus"></i>  </button>
      </a>

    @endif

    <button class="filter-div btn btn-success btn-search text-dark m-1 py-2">
      <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="list-table" data-url="{{route('admin.advertisement.all')}}">
      <thead>

        <tr class="bg-primary text-white">
          <!-- <th >All</th> -->
          <th ># <i class="icon"></i></th>
          <th>Image</th>
          <th class="sortable selected" data-sort="title">Title <i class="icon"></i></th>
          <th class="sortable" data-sort="page">Page <i class="icon"></i></th>
          <th class="sortable" data-sort="display_on">Display On <i class="icon"></i></th>
          <th class="sortable" data-sort="status">Satus <i class="icon"></i></th>
          <th class="sortable" data-sort="order">Order <i class="icon"></i></th>
          <th class="sortable" data-sort="start_date">Start <i class="icon"></i></th>
          <th class="sortable" data-sort="end_date">End <i class="icon"></i></th>
          <th class="sortable" data-sort="updated_at">Last Updated<i class="icon"></i></th>
          <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
          <tr>
              <td colspan="11" class="pagination"></td>
          </tr>
      </tfoot>
    </table>
    </div><!--end .table-responsive -->
    @include('admin.media.view')
@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('theme-admin/js/listtable.js')}}"> </script>
<script type="text/javascript">
    var deleteUrl = '{{route('admin.advertisement.delete')}}';
    var confirmDeleteModal = $('#modal-delete');
    var mediaModelView = $('#media-img-view');
    var advertisementId = 0;
    var deleteCategory = function(advertisement_id){
          advertisementId  = advertisement_id;
          confirmDeleteModal.modal('show');
     }

     var confirmDelete = function(){
        $.ajax({
           url: deleteUrl,
           type : 'DELETE',
           data : {advertisement_id:advertisementId, _token:window.Laravel.csrfToken},
           dataType: 'json'
         })
         .done(function(response) {
           toastr.success(response.message);
             advertisementList();
             advertisementId= 0;
             confirmDeleteModal.modal('hide');
         })
         .fail(function(response, code) {
             toastr.error(response.message);
         });
     }

</script>
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
