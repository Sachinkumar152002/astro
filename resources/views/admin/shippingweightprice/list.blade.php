@extends('layouts.admin')

@section('content')
<!-- filter-design-new start-->
<div class="" style="position:relative;">
   <div class="">
   <div class="row search-filters" id="filters">
      <div class="" id="filter_row">
      <div class="close-search flaticon-letter-x pointer"><i class="bi bi-x-circle fs-20"></i></div>
       <div class="mt-2">
         <label for="" class="fs-13">Weight from</label>
       <input type="number" class="form-control" name="weight_from">
     </div>
       <div class="mt-2">
         <label for="" class="fs-13">Weight to</label>
       <input type="number" class="form-control" name="weight_to">
     </div>
     <div class="mt-2">
       <label for="" class="fs-13">Local Price</label>
     <input type="number" class="form-control" name="local_price">
   </div>
     <div class="mt-2">
       <label for="" class="fs-13">Domestic Price</label>
     <input type="number" class="form-control" name="domestic_price">
   </div>
     <div class="mt-2">
       <label for="" class="fs-13">National Price</label>
     <input type="number" class="form-control" name="national_price">
   </div>
     <button class="btn btn-success mt-3" onclick="filterResults('{{route('admin.'.$nav.'.list')}}')">Apply</button>
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
<h1 class="text-primary">{{$modulename}}</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    @if(adminCan($nav, 'add'))
    <button class="btn btn-primary text-black m-1" data-toggle="modal" onclick="openModal('add')">
    <i class="bi bi-plus text-white"></i>
    </button>
    <!-- <button class="btn btn-seconday text-black m-1">
      <i class="bi bi-copy"></i>
    </button> -->
    @endif
    @if(adminCan($nav, 'delete'))
    <!-- <button class="btn btn-danger text-white m-1">
      <i class="bi bi-trash"></i>
    </button> -->
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
    <table class="table no-margin" data-url="{{route('admin.'.$nav.'.list')}}" id="list-table">
    <thead>
    <tr class="bg-primary text-white">
      <th>All</th>
              <th class="sortable " data-sort="id"># <i class="icon"></i></th>
    <th class="sortable" data-sort="weight_from">Weight From <i class="icon"></i></th>
    <th class="sortable" data-sort="weight_to">Weight To <i class="icon"></i></th>
    <th class="sortable" data-sort="local_price">Local Price <i class="icon"></i></th>
    <th class="sortable" data-sort="domestic_price">Domestic Price <i class="icon"></i></th>
    <th class="sortable" data-sort="national_price">National Price <i class="icon"></i></th>
    <th class="sortable selected" data-sort="updated_at">Updated At <i class="icon"></i></th>
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
    <div class="form-group row">
    <label for="item_name" class="col-sm-6 control-label required">Weight From</label>
    <div class="col-sm-6">
    <input type="number" class="form-control" id="weight_from"  name="weight_from"><div class="form-control-line"></div>
    </div>
    </div>
                <div class="form-group row">
                  <label for="item_name" class="col-sm-6 control-label required">Weight to</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="weight_to"  name="weight_to"><div class="form-control-line"></div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="item_name" class="col-sm-6 control-label required">Local Price</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="local_price"  name="local_price"><div class="form-control-line"></div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="item_name" class="col-sm-6 control-label required">Domestic Price</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="domestic_price"  name="domestic_price"><div class="form-control-line"></div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="item_name" class="col-sm-6 control-label required">National Price</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="national_price"  name="national_price"><div class="form-control-line"></div>
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
var deleteUrl = "{{route('admin.'.$nav.'.delete')}}"
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
    url: "{{route('admin.'.$nav.'.submit')}}",
    data: data,
    })
      .done(function(data) {
        toastr.success(data.message);
        listTable();
        $('#addModal').modal('hide');
      })
      .fail(function(error) {

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
    $('#weight_from').val(0);
    $('#weight_to').val(0);
    $('#local_price').val(0);
    $('#domestic_price').val(0);
    $('#national_price').val(0);
  }
  $('#addModal').modal('show');
}
function editForm(id, weight_from, weight_to, local_price, domestic_price, national_price){
  $('#item_id').val(id);
  $('#weight_from').val(weight_from);
  $('#weight_to').val(weight_to);
  $('#local_price').val(local_price);
  $('#domestic_price').val(domestic_price);
  $('#national_price').val(national_price);
  openModal('edit');
}
</script>
@endpush
