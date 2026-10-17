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
     <button class="btn btn-success mt-3" onclick="filterResults('{{route('admin.service.list')}}')">Apply</button>
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
<h1 class="text-primary">{{ $modulename }}</h1>
</div>
<div class="col-md-6">
  <div class="d-flex text-align-end justify-content-end">
    <button class="btn btn-primary text-white m-1" data-toggle="modal" onclick="openModal('add')">
    <i class="bi bi-plus"></i>
    </button>
    <button class="btn btn-seconday text-black m-1 d-none">
      <i class="bi bi-copy"></i>
    </button>
    <button class="btn btn-danger text-white m-1 d-none">
      <i class="bi bi-trash"></i>
    </button>
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
    <table class="table no-margin" data-url="{{route('admin.service.list')}}" id="list-table">
    <thead>
    <tr class="bg-primary text-white">
      <th>All</th>
              <th class="sortable " data-sort="id"># <i class="icon"></i></th>
    <th class="sortable selected" data-sort="name">Name <i class="icon"></i></th>
    <th class="sortable" data-sort="short_name">Short Name<i class="icon"></i></th>
    <th class="sortable" data-sort="short_name">Updated At<i class="icon"></i></th>
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
    <div class="card-body sh-0">
    <h3 class="change-state"></h3>
    <form class="form-horizontal" role="form" id="form-item" >
    @csrf
    <input type="hidden" name="item_id" value="" id="item_id">
                <div class="form-group">
                  <label for="item_name" class="col-12 control-label required px-0">Name</label>
                  <div class="">
                    <input type="text" class="form-control" id="name"  name="name"><div class="form-control-line"></div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="item_name" class="col-12 control-label required px-0">Short Name</label>
                  <div class="">
                    <input type="text" class="form-control" id="short_name"  name="short_name"><div class="form-control-line"></div>
                  </div>
                </div>
                <div class="col-12 form-group px-0">
                {{ Form::label('icon_id', 'Icon', ['class' => 'col-sm-12 control-label required px-0']) }}
                <div class="d-flex align-items-center gap-2">
                  <button type="button" class="btn btn-primary" id="select-image" onclick="openMediaManager('icon')">
                    Upload Icon
                  </button>

                  {{ Form::hidden('icon_id', '0', ['id'=>'icon_id']) }}

                  <div class="profile_image text-center icon icon_div mx-2" data-mediaid="0">
                    <img src="{{ no_image() }}" alt="image" class="img-responsive" style="height:100px; width: 100px;">
                    <i class="fa fa-trash text-danger d-none" onclick="removeImage(this, 'icon')"></i>
                  </div>

                  {{ Form::hidden('icon_src', no_image(), ['id'=>'icon_src']) }}
                </div>
              </div>


    <div class="col-12 px-0">
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
  @include('admin.media.manager')

@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('theme-admin/js/listtable.js')}}"> </script>

<script type="text/javascript">
var media_image_type = 'services';
var imageset = 'editor';
$('#insert-image').on('click', function(){
    if($('.img-box-choice.selected').length==0) {
        toastr.warning('Please select an image');
      return false;
    }
    var selectedImg = $('.img-box-choice.selected');
    var mediaId = selectedImg.data('mediaid');
    var imgSrc = $('.img-box-choice.selected img').attr('src')
    var imgSrcOriginal = $('.img-box-choice.selected img').data('original')
    var newImg  = selectedImg.clone();
    if(imageset=='editor') {
      tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
      imageset = 'editor';
    }else{
      $('#'+imageset+'_id').val(mediaId);
      $('#'+imageset+'_src').val(imgSrc);
      $('.profile_image.'+imageset+'_div').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image  text-center '+imageset+'_div');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
      $('#'+imageset+'_id').after(newImg);
    }
    $('#media-img-manager').modal('hide');
  });

var openMediaManager = function(imgOp) {
    imageset = imgOp;
    $('#media-img-manager').modal('show');
}
var removeImage = function(elm, imageset){
      $(elm).parents('.profile_image.'+imageset+'_div').remove();
      $('#'+imageset+'_id').val('');
      $('#'+imageset+'_src').val('');
}
</script>

<script type="text/javascript">
var deleteUrl = "{{route('admin.service.delete')}}"
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
    url: "{{route('admin.service.submit')}}",
    data: data,
    })
      .done(function(data) {
        toastr.success(data.message);
      window.location.reload();

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
    .fail(function(xhr, code) {
      if(xhr.message){
        toastr.error(xhr.message);
      }else if(xhr.responseJSON){
        toastr.error(xhr.responseJSON.message);
      }
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
    $('#short_name').val('');
    $('#icon_id').val('');
    $('#icon_src').val('');
    $('.icon_div').find('i').addClass('d-none');
    $('.icon_div').find('img').attr('src', '{{no_image()}}');
  }
  $('#addModal').modal('show');
}
function editForm(id, name, shortName, icon_id, icon_src){
  $('#item_id').val(id);
  $('#name').val(name);
  $('#short_name').val(shortName);
  $('#icon_id').val(icon_id);
  $('#icon_src').val(icon_src);
  $('.icon_div').find('i').removeClass('d-none');
  $('.icon_div').find('img').attr('src', icon_src);
  openModal('edit');
}
</script>
@endpush
