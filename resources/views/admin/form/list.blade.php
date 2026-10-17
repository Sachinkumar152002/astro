@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label for="" class="fs-13">Label</label>
       <input type="text" class="form-control" name="form_label">
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Type</label>
  <input type="text" class="form-control" name="form_type">
   </div>

        <button class="btn btn-success mt-3" onclick="listTable()">Apply</button>
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
<h1 class="text-primary">Form </h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    @if(adminCan('form-field', 'add'))
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
    <table class="table no-margin" data-url="{{route('admin.form.list')}}" id="list-table">
    <thead>
    <tr class="bg-primary text-white">
       <th>All</th>
              <th class="sortable " data-sort="id"># <i class="icon"></i></th>
    <th class="sortable selected" data-sort="form_label">Label <i class="icon"></i></th>
    <th class="sortable" data-sort="form_type">Type <i class="icon"></i></th>
                      <th class="sortable" data-sort="isrequired">Required <i class="icon"></i></th>
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
    <label for="item_name" class="col-sm-2 control-label required">Label</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="label"  name="form_label" required><div class="form-control-line"></div>
    </div>
    </div>
    <div class="form-group">
    <label for="type" class="col-sm-2 control-label required">Type</label>
    <div class="col-sm-10">
    <select class="form-control" name="form_type" id="type" required>
    @foreach(formFields() as $key => $field)
    <option value="{{$key}}">{{$field}}</option>
    @endforeach
    </select>
    </div>
    </div>
    <div class="form-group d-none" id="file_types">
    <label for="fileType" class="col-sm-4 control-label required">Select File Type</label>
    <div class="col-sm-10">
    <input type="text" id="file_type" class="form-control" name="filetype" value="" placeholder="ex. .jpg, .pdf">
    </div>
    </div>
      <div class="form-group col-md-6">
                  <span class="ml-2"><strong>	Make Field Required</strong></span>
                      <input type="checkbox" class="toggle-check" id="is_required"  value="1" name="isrequired">
                      <label class="mx-2 t-lable mt-2" for="is_required"></label>
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
var deleteUrl = "{{route('admin.form.delete')}}"
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
    url: "{{route('admin.form.submit')}}",
    data: data,
    })
      .done(function(data) {
        toastr.success(data.message);
        // listTable();
      window.location.reload();

      })
      .fail(function(error) {
      console.log(data)
      error = error.responseJSON.errors;
      for (var key in error) {
        toastr.error(error[key][0]);
      }
        // 	$.each($.parseJSON(data.responseJSON.errors), function( index, value ) {
        // 		// $('#module_'+index).parents('.form-group').addClass('has-error');
    // });
      });
  });
});

// $('#add-new').on('click',function(){
//   $('#form-box').removeClass('hidden');
// })

$('#type').on('click',function(){
    if(this.value == 'file'){
    $('#file_types').removeClass('d-none');
    } else{
    $('#file_types').addClass('d-none');
    }
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
    $('#label').val('');
    $('#type').val('');
    $('#file_types').addClass('d-none');
    $('#file_type').val('');
    $('#is_required').prop('checked', false);
  }
  $('#addModal').modal('show');
}
function editForm(id, label, formType, isRequired, fileType){
$('#item_id').val(id);
$('#label').val(label);
$('#type').val(formType);
  if(fileType){
    $('#file_types').removeClass('d-none');
    $('#file_type').val(fileType);
  }
  if(isRequired=='1'){
    $('#is_required').prop('checked', true);
  }
  openModal('edit');
}
</script>
@endpush
