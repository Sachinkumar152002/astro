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
         <label for="" class="fs-13">Type</label>
      <input type="text" class="form-control" name="type">
       </div>
       <div class="mt-2">
         <label for="" class="fs-13">Date</label>
         <input type="date" class="form-control" name="updated_at">
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
  <!-- <a class="btn btn-primary float-right" href="{{route('admin.banner.add')}}">Add New</a> -->
  <h1 class="text-primary">Banners</h1>
  </div>
  <div class="col-md-6">
    <div class="d-flex text-align-end justify-content-end">
      @if(adminCan('banner', 'add'))
      <button class="btn btn-primary m-1">
        <a href="{{route('admin.banner.add')}}" class="text-white">
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
        <table class="table" id="list-table" data-url="{{route('admin.banner.all')}}">
        <thead>

          <tr class="bg-primary text-white">
            <th>All</th>
            <th># <i class="icon"></i></th>
            <th>Image </th>
            <th class="sortable selected" data-sort="title">Title <i class="icon"></i></th>
            <th class="sortable selected" data-sort="type">Page <i class="icon"></i></th>
            <th class="sortable" data-sort="updated_at">Last Updated<i class="icon"></i></th>
            <th class="text-right"><strong><i class="icon icon-reload pointer" onclick="resetFilter()"></i></strong></th>
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
      </div>
</div>
    <!--end .table-responsive -->
    @include('admin.media.view')
@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('theme-admin/js/listtable.js')}}"> </script>
<script type="text/javascript">
     var deleteUrl = '{{route('admin.banner.delete')}}';
     var confirmDeleteModal = $('#modal-delete');
 var mediaModelView = $('#media-img-view');
     // var bannerList = function(){
     //   var tblObj = $("#banner-list");

     //   $.ajax({
     //     url: tblObj.data('url'),
     //     type : 'GET',
     //     data : {sync:1},
     //     dataType: 'json'
     //   })
     //   .done(function(response) {
     //       tblObj.find('tbody').html(response.rows);
     //       tblObj.find('tfoot tr td').html(response.pagination);
     //       reinitPagination();
     //   })
     //   .fail(function(response, code) {
     //       toastr.error('Error in listing rows');
     //   });
     // };

     // bannerList();

     // var reinitPagination = function(){
     //   $('.pagination a').on('click', function(e){
     //     e.preventDefault();
     //     $("#banner-list").data('url', $(this).attr('href'));
     //       bannerList();
     //   });
     //   $('.banner-img-view').on('click', function(){
     //      var img =  $(this).data('img');
     //      mediaModelView.find('img').attr('src', img);
     //      mediaModelView.modal('show');
     //   });

     // }


      var bannerId = 0;

     var deleteCategory = function(banner_id){
          bannerId  = banner_id;
          confirmDeleteModal.modal('show');
     }

     var confirmDelete = function(){
        $.ajax({
           url: deleteUrl,
           type : 'DELETE',
           data : {banner_id:bannerId, _token:window.Laravel.csrfToken},
           dataType: 'json'
         })
         .done(function(response) {
           toastr.success(response.message);
             bannerList();
             bannerId= 0;
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
</script>
@endpush
