@extends('layouts.admin')

@section('content')
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
     <button class="btn btn-success mt-3" onclick="filterResults('{{route('admin.language.list')}}')">Apply</button>
     </div>
  </div>
    </div>
  </div>
<!-- filter-design-new-end -->
<section>
<div class="section-body contain-lg">
<!-- BEGIN INTRO -->
<div class="row">
<div class="col-md-6">
<h1 class="text-primary">Language</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    @if(adminCan('language', 'add'))
    <button class="btn btn-primary text-white m-1" data-toggle="modal" onclick="openModal('add')">
    <i class="bi bi-plus"></i>
    </button>

    @endif

    <button class="filter-div btn btn-success btn-search text-white m-1 py-2">
    Filter <i class="bi bi-funnel text-white"></i>
    </button>
  </div>
</div>
<div class="col-lg-8">
<article class="margin-bottom-xxl">
<p class="lead"></p>
</article>
</div><!--end .col -->
</div><!--end .row -->
<!-- END INTRO -->

<!-- BEGIN RESPONSIVE TABLE 1 -->
<div class="">
    <div class="card-body">
    <div class="table-responsive">
    <table class="table no-margin" data-url="{{route('admin.language.list')}}" id="list-table">
    <thead>
    <tr class="bg-primary text-white">
      <th>All</th>
              <th class="sortable " data-sort="id"># <i class="icon"></i></th>
    <th class="sortable" data-sort="name">Name <i class="icon"></i></th>
    <th class="sortable" data-sort="position">Position <i class="icon"></i></th>
    <th class="sortable selected" data-sort="updated_at">Updated At <i class="icon"></i></th>
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
    </div>
    </div>
    </div><!--end .card -->
    </div><!--end .col -->

    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add More</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
    <div class="col-md-12">
    <div class="card">
    <div class="card-body">
    <h3 class="change-state"></h3>
    <form class="form-horizontal" role="form" id="form-item" >
    @csrf
    <input type="hidden" name="item_id" value="" id="item_id">
    <div class="form-group">
    <label for="item_name" class="col-sm-2 control-label required">Name</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="name"  name="name"><div class="form-control-line"></div>
    </div>
    </div>
                <div class="form-group">
    <label for="position" class="col-sm-2 control-label required">Position</label>
    <div class="col-sm-10">
    <input type="number" class="form-control" id="position" min="0"  name="position"><div class="form-control-line"></div>
    </div>
    </div>
    <div class="col-12">
    <button type="submit" class="btn btn-primary float-end">Submit</button>
    </div>
    </form>
    </div><!--end .card-body -->
    </div><!--end .card -->
    </div>
          </div>
        </div>
      </div>
    </div>
<!-- END RESPONSIVE TABLE 1 -->


<!-- END RESPONSIVE TABLE 1 -->

</div>
</section>

@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('theme-admin/js/listtable.js')}}"> </script>
<script type="text/javascript">
var deleteUrl = "{{route('admin.language.delete')}}"
var item_id = $('#item_id');
$(document).ready(function(){
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  $('#form-item').on('submit', function(e) {
      e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
      type: "POST",
    url: "{{route('admin.language.submit')}}",
    data: data,
    })
      .done(function(data) {
        toastr.success(data.message);
      //window.location.reload();
        listTable();
        $('#addModal').modal('hide');
      })
      .fail(function(error) {
      console.log(data)
      error = error.responseJSON.errors;
      for (var key in error) {
        toastr.error(error[key][0]);
      }
      });
  });
});
var deleteItem = function(item_id){
  if(confirm('Are you sure want to delete this item?'))
  {
    $.ajax({
      url: deleteUrl,
      type : 'DELETE',
      data : {item_id:item_id, _token:window.Laravel.csrfToken},
      dataType: 'json'
    })
    .done(function(response) {
      toastr.success(response.message);
        listTable();
        editItem(0,'');
        form_box.addClass('hidden');
    })
    .fail(function(response, code) {
        toastr.error(response.message);
    });
  }
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

function openModal(action) {
  if(action == 'add'){
    $('#item_id').val('');
    $('#name').val('');
    $('#position').val(0);
  }
  $('#addModal').modal('show');
}
function editForm(id, name, position){
  $('#item_id').val(id);
  $('#name').val(name);
  $('#position').val(position);
  openModal('edit');
}
</script>
@endpush
