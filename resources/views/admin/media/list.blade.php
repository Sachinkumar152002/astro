@extends('layouts.admin')

@push('styles')
<link href="{{asset('css/dropzone.min.css')}}" type="text/css" rel="stylesheet"/>
@endpush

@section('content')

<div class="row">
<div class="col-lg-12">
<button class="btn btn-primary float-right" type="button" data-toggle="modal" data-target="#media-img-add">Add New</button>
<h1 class="text-primary float-left">Media</h1>
</div>
</div>
    <div class="table-responsive">
      <table class="table" id="media-list" data-url="{{route('admin.media.all')}}">
      <thead>
        <tr class="bg-primary text-white">
          <th># <i class="icon"></i></th>
          <th>Images </th>
          <th>URL </th>
          <th class="sortable " data-sort="title">Title <i class="icon"></i></th>
          <th class="sortable" data-sort="type">Type <i class="icon"></i></th>
          {{-- <th class="sortable selected" data-sort="created_at">Created At <i class="icon"></i></th> --}}
          <th class="sortable selected" data-sort="updated_at">Last Updated<i class="icon"></i></th>
          <th><strong><i class="icon icon-reload" onclick="resetFilter()"></i></strong></th>
        </tr>

        <tr id="filter_row">
          <th></th>
          <th></th>
          <th></th>
          <th><input type="text" class="form-control" name="title"></th>
          <th><select class="form-control" name="type">
              <option value=""></option>
              @forelse (App\Model\Media::types() as $k=>$v)
                <option value="{{$k}}">{{$v}}</option>
              @empty
              <option value="0">None</option>
              @endforelse
          </select></th>
          {{-- <th><input type="date" class="form-control" name="created_at"></th> --}}
          <th><input type="date" class="form-control" name="updated_at"></th>

          <th>
            <button class="btn btn-success" onclick="mediaList()">Filter</button>
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
@include('admin.media.view')
@include('admin.media.add')
@endsection

@push('scripts')
<script type="text/javascript" src="{{asset('js/dropzone.js')}}"> </script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript">
     axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
     var deleteUrl = '{{route('admin.media.delete')}}';
     var meida_upload_url = '{{route('admin.media.upload')}}';

     var mediaModelView = $('#media-img-view');
     var confirmDeleteModal = $('#modal-delete');
     var sort_by = $('.sortable.selected').data('sort');
var sort_dir = 'desc';
     var mediaList = function(){
       var tblObj = $("#media-list");
       var filterData = serializeToJson($('#filter_row :input').serializeArray());
    filterData.sync = 1;
    filterData.sort_by = sort_by;
    filterData.sort_dir = sort_dir;
       $.ajax({
         url: tblObj.data('url'),
         type : 'GET',
         data : filterData,
         dataType: 'json'
       })
       .done(function(response) {
           tblObj.find('tbody').html(response.rows);
           tblObj.find('tfoot tr td').html(response.pagination);
           reinitPagination();
       })
       .fail(function(response, code) {
           toastr.error('Error in listing rows');
       });
     };

     mediaList();

     var reinitPagination = function(){
       $('.pagination a').on('click', function(e){
         e.preventDefault();
         $("#media-list").data('url', $(this).attr('href'));
           mediaList();
       });
       $('.media-img-view').on('click', function(){
          var img =  $(this).data('img');
          mediaModelView.find('img').attr('src', img);
          mediaModelView.modal('show');
       });
       resetSort();

     }

     var editCategory = function(mediaStr) {
          var Obj = JSON.parse(mediaStr);
          $('#media_id').val(Obj.media_id);
          $('#title').val(Obj.title);
          $('#caption').val(Obj.caption);
          $('#media_type').val(Obj.type);
          $("#new-image-src").attr('src',Obj.image);
          $('#media-img-add').modal('show');
          $('#new-file-upload').hide();
          $('#new-file-form').show();
     }
      var mediaId = 0;
     var deleteCategory = function(media_id){
          mediaId  = media_id;
          confirmDeleteModal.modal('show');
     }

     var confirmDelete = function(){
       $.ajax({
           url: deleteUrl,
           type : 'DELETE',
           data : {media_id:mediaId, _token:window.Laravel.csrfToken},
           dataType: 'json'
         })
         .done(function(response) {
           toastr.success(response.message);
             mediaList();
             mediaId= 0;
             confirmDeleteModal.modal('hide');
         })
         .fail(function(response, code) {
             toastr.error(response.message);
         });
     }

     var myDropzone = new Dropzone("div#file-upload",
       {
           url: meida_upload_url,
           paramName: "file",
           maxFilesize: 128,
           uploadMultiple:false,
           acceptedFiles: 'image/*',
           previewTemplate:'<i class="hidden preview-image"></i>',
           parallelUploads:1,
            success: function(file,data) {
                $('#new-file-upload').hide();
                $("#media_id").val(data.media_id);
                $("#new-image-src").attr('src',data.img);
                $('#new-file-form').show();
                 $('#progress .progress-bar').css(
                         'width',
                          '0%'
                 );
           },
           uploadprogress : function(file,progress, bytesent) {
               $('#progress .progress-bar').css(
                       'width',
                       progress + '%'
               );
           },
           sending:function(file, xhr, formData) {
               formData.append("_token", window.Laravel.csrfToken);
           }
       }
     );
     Dropzone.autoDiscover = false;

      /* add Media */
      var addMedia = function(ele){
            var frm = $(ele);
            axios({
                method: 'post',
                url: frm.attr('action'),
                data:frm.serialize()
              }).then(function (response) {
                 toastr.success(response.data.message);
                 mediaList();
                 $('#media-img-add').modal('hide');
                 window.location.reload();
              }).catch(function (error) {
                 var errors = error.response.data;
                  $.each(errors,function(i,val){
                      toastr.error(val[0]);
                 })
              });

      }

      function copyToClipboard(text) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(text).select();
        document.execCommand("copy");
        $temp.remove();
        toastr.success("Copied!");
      }

function serializeToJson(serializer){
    var _string = '{';
    for(var ix in serializer)
    {
        var row = serializer[ix];
        _string += '"' + row.name + '":"' + row.value + '",';
    }
    var end =_string.length - 1;
    _string = _string.substr(0, end);
    _string += '}';

    return JSON.parse(_string);
}

$('.sortable').on('click', function(){
    if($('.sortable').hasClass('selected')) {
        sort_dir = sort_dir==='desc' ? 'asc' : 'desc';
    }
    sort_by = $(this).data('sort');
    mediaList();
});

var resetSort = function() {
    $('.sortable').removeClass('selected');
    $('.sortable i').removeClass('icon-arrow-down');
    $('.sortable i').removeClass('icon-arrow-up');
    var sortelm = $('.sortable[data-sort="'+sort_by+'"]');
    sortelm.addClass('selected');
    sortelm.find('i').addClass(sort_dir==='desc'?'icon-arrow-down':'icon-arrow-up');
}

var resetFilter = function() {
    $('#filter_row :input').val('');
    mediaList();
}
</script>
@endpush
