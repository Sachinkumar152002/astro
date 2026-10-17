@extends('layouts.admin')

@section('content')<!-- BEGIN CONTENT-->

<section>

<div class="section-body contain-lg">

<!-- BEGIN INTRO -->
<div class="row">
<div class="col-lg-12">
<button class="btn btn-primary float-right" onclick="editItem(0,'','')">Add New</button>
<h1 class="text-primary">Modules</h1>
</div><!--end .col -->
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

    <div class="col-lg-6">
    <div class="card">
    <div class="card-body">
    <div class="table-responsive">
    <table class="table no-margin" data-url="{{route('admin.module.list')}}" id="list-table">

    <thead>
    <tr class="bg-primary text-white">
          <th class="sortable selected" data-sort="id"># <i class="icon"></i></th>

          <th class="sortable" data-sort="name">Name <i class="icon"></i></th>
          <th class="sortable" data-sort="key">Key <i class="icon"></i></th>

          <th><strong><i class="icon icon-reload float-right" onclick="resetFilter()"></i></strong></th>
        </tr>


    <tr id="filter_row">
          <th></th>
          <th><input type="text" class="form-control" name="name"></th>
    <th></th>
          <!-- <th><input type="text" class="form-control" name="key"></th> -->
          <th>
            <button class="btn btn-success" onclick="filterResults('{{route('admin.module.list')}}')">Filter</button>
          </th>
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

    <div class="col-lg-6 hidden" id="form-box">
    <div class="card">
    <div class="card-body">
    <h3 class="change-state"></h3>
    <form class="form-horizontal" role="form" method="post" id="module-form" action="{{route('admin.module.submit')}}">
    {{csrf_field()}}
    <input type="hidden" name="module_id" value="1" id="module_id">
    <div class="form-group">
    <label for="module_name" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="module_name" placeholder="Module Name" name="name"><div class="form-control-line"></div>
    </div>
    </div>
    <div class="form-group">
    <label for="module_key" class="col-sm-2 control-label">key</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="module_key" placeholder="Module key" name="key"><div class="form-control-line"></div>
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
</div><!--end .row -->
<!-- END RESPONSIVE TABLE 1 -->


<!-- END RESPONSIVE TABLE 1 -->

</div>
</section>

@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('theme-admin/js/listtable.js')}}"> </script>
<script type="text/javascript">
var deleteUrl = '{{route('admin.module.delete')}}'
var module_name = $('#module_name');
var module_key = $('#module_key');
var module_id = $('#module_id');
var form_box = $('#form-box');

$('#module-form').on('submit', function(){
    toastr.remove();

    module_name.parents('.form-group').removeClass('has-error');

    var isErr = false;
    if(module_name.val()=='') {
    toastr.error('Module name cannot be blank!')
    module_name.parents('.form-group').addClass('has-error');
     isErr = true;
    }
    if(module_key.val()=='') {
    toastr.error('Module key cannot be blank!')
    module_key.parents('.form-group').addClass('has-error');
     isErr = true;
    }
    if(isErr) return false;

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
        module_name.val('');
        module_key.val('');
        module_id.val(0);
        listTable();
        form_box.addClass('hidden');
      })
      .fail(function(data) {
    $.each($.parseJSON(data.responseText), function( index, value ) {
    toastr.error(value );
    $('#module_'+index).parents('.form-group').addClass('has-error');
    });
      })
      ;
    return false;
});







var editItem = function(id, name, key) {
    module_id.val(id);
    module_name.val(name);
    module_key.val(key);
    if(id==0) {
    $('.change-state').text('New module');
    } else {
    $('.change-state').text('Edit module ' + name);
    }
    form_box.removeClass('hidden');
}

var deleteItem = function(item_id){
  if(confirm('Are you sure want to delete this item?'))
  {
    $.ajax({
      url: deleteUrl,
      type : 'DELETE',
      data : {module_id:item_id, _token:window.Laravel.csrfToken},
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

/*  on load */
// list();

</script>
@endpush
