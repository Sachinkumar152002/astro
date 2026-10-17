@extends('layouts.admin')

@section('content')<!-- BEGIN CONTENT-->

<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label for="" class="fs-13">Name</label>
       <input type="text" class="form-control" name="name">
     </div>
<button class="btn btn-success text-white mt-2" onclick="filterResults('{{route('admin.role.list')}}')">Apply</button>     </div>
  </div>
    </div>
  </div>
<!-- filter-design-new-end -->
<section>
<div class="section-body contain-lg">

<!-- BEGIN INTRO -->
<div class="row">
<div class="col-md-6">
@if(adminCan('role', 'add'))
<!-- <button class="btn btn-primary float-right" onclick="editItem(0,'')">Add New</button> -->
@endif
<h1 class="text-primary">Roles</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
  @if(adminCan('role', 'add'))
    <button class="btn btn-primary text-white m-1" data-toggle="modal" onclick="openModal('add')">
    <i class="bi bi-plus"></i>
    </button>
    @endif

    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
      Filter <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
<!--end .col -->
<div class="col-lg-8">

<article class="margin-bottom-xxl">
<p class="lead">

</p>
</article>
</div><!--end .col -->
</div><!--end .row -->
<!-- END INTRO -->

<!-- BEGIN RESPONSIVE TABLE 1 -->
<div class="row">

    <div class="col-lg-12">
    <div class="">
    <div class="card-body">
    <div class="table-responsive">
    <table class="table no-margin " data-url="{{route('admin.role.list')}}" id="list-table">
    <thead>
    <tr class="bg-primary text-white">
          <th class="sortable selected" data-sort="id"># <i class="icon"></i></th>
          <th class="sortable" data-sort="name">Name <i class="icon"></i></th>
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
    </div><!--end .table-responsive -->
    </div><!--end .card-body -->
    </div><!--end .card -->
    </div><!--end .col -->


</div><!--end .row -->
<!-- END RESPONSIVE TABLE 1 -->


<!-- END RESPONSIVE TABLE 1 -->

</div>
<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">

    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <div class="modal-body">
    <div class="col-lg-12" id="form-box">
    <div class="card">
    <div class="card-body">
    <h3 class="change-state"></h3>
    <form class="form-horizontal" role="form" method="post" id="role-form" action="{{route('admin.role.submit')}}">
    {{csrf_field()}}
    <input type="hidden" name="role_id" value="0" id="role_id">
    <div class="form-group">
    <label for="role_name" class="col-sm-2 control-label">Role</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="role_name" placeholder="Role Name" name="name"><div class="form-control-line"></div>
    </div>
    </div>
    <div class="form-group">
    <label for="default14" class="col-sm-2 control-label"></label>
    <div class="col-sm-10">
    <input type="submit" name="Save" class="btn btn-primary pull-right">
    </div>
    </div>

    </form>
    </div><!--end .card-body -->
    </div><!--end .card -->
    </div>
    </div>
    </div>
    </div>
</div>
</section>

@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('theme-admin/js/listtable.js')}}"> </script>
<script type="text/javascript">
var deleteUrl = '{{route('admin.role.delete')}}'
var role_name = $('#role_name');
var role_id = $('#role_id');
var form_box = $('#form-box');

$('#role-form').on('submit', function(){
    toastr.remove();

    role_name.parents('.form-group').removeClass('has-error');

    if(role_name.val()=='') {
    toastr.error('Role name cannot be blank!')
    role_name.parents('.form-group').addClass('has-error');
    return false;
    }

    var _frm = $(this);
    var jqxhr = $.ajax( {
    type: "POST",
    url: _frm.attr('action'),
    data: _frm.serialize(),
    cache: false,
    dataType: 'json'
    })
      .done(function(data) {
        toastr.success(data.message);
      $('#addModal').modal('hide');
        listTable();
      })
      .fail(function(data) {

    $.each($.parseJSON(data.responseText), function( index, value ) {
    toastr.error(value );
    role_name.parents('.form-group').addClass('has-error');
    });
      })
      ;
    return false;
});

// var list = function(){
// 	var tblObj = $("#listTable");
//
// 	  $.ajax({
// 	    url: tblObj.data('url'),
// 	    type : 'GET',
// 	    data : {sync:1},
// 	    dataType: 'json'
// 	  })
// 	  .done(function(response) {
// 	      tblObj.find('tbody').html(response.rows);
// 	      tblObj.find('tfoot tr td').html(response.pagination);
// 	      reinitPagination();
// 	  })
// 	  .fail(function(response, code) {
// 	      toastr.error('Error in listing');
// 	  });
//
// }


// var reinitPagination = function(){
//   $('.pagination a').on('click', function(e){
//     e.preventDefault();
//     $("#list-table").data('url', $(this).attr('href'));
//       listTable();
//   });
// }


var editItem = function(id, name) {
    role_id.val(id);
    role_name.val(name);
    if(id==0) {
    $('.change-state').text('New Role');
    } else {
    $('.change-state').text('Edit Role ');
    }
    form_box.removeClass('hidden');
}

// var deleteItem = function(item_id){
//   if(confirm('Are you sure want to delete this item?'))
//   {
//     $.ajax({
//       url: deleteUrl,
//       type : 'DELETE',
//       data : {role_id:item_id, _token:window.Laravel.csrfToken},
//       dataType: 'json'
//     })
//     .done(function(response) {
//       toastr.success(response.message);
//         listTable();
//         editItem(0,'');
//         form_box.addClass('hidden');
//     })
//     .fail(function(response, code) {
//         toastr.error(response.message);
//     });
//   }
// }


/*  on load */
// listTable();

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

function openModal(action) {
  if(action == 'add'){
    $('#role_id').val('');
    $('#role_name').val('');
  }
  $('#addModal').modal('show');
}
function editForm(id, name){
  $('#role_id').val(id);
  $('#role_name').val(name);
  openModal('edit');
}
</script>
@endpush
