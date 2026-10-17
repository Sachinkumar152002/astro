<div id="media-img-manager" tabindex="-1" role="dialog" aria-labelledby="MediaImageManager" aria-hidden="true" class="modal fade text-center new-media">
  <div role="document" class="modal-dialog modal-media modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="MediaImageManager" class="modal-title">Media Manger</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <button type="button" class="btn media-mgnt-choice btn-primary" data-option="#new-file-upload">From computer</button>
        <button type="button" class="btn media-mgnt-choice" data-option="#media-choose">From Media</button>

        <div class="col-md-12 media-manager-box upload-img-box" id="new-file-upload" >
          <div   class="dropzone" id="file-upload"></div>
          <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
          </div>
        </div>
        <div class="col-md-12 media-manager-box hide"  id="media-choose" style="max-height: 300px; overflow-y: auto;">
          <div class="row" id="all-media-files" style="--bs-gutter-x: 0px">
                @if($medias)
                  @foreach($medias as $media)
                      <div class="img-box-choice new-media-box col-md-3" data-mediaid="{{$media->id}}" >
                            <img src="{{$media->image('200', '200')}}" alt="{{$media->title}}" class="img-responsive" data-original="{{$media->originalPath}}">
                            <!-- <div class="action">
                                <a href="javascript:void(0)" class="text-danger" onclick="deletemedia('{{$media->id}}', this)">Delete</a>
                            </div> -->
                      </div>
                  @endforeach
                @endif
              </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="insert-image">Select</button>
        <a href="javascript:void(0)" class ="text-primary" onclick="copyToClipboard()"><i class="fa fa-copy fa-2x"></i></a>
        <input type="text" name="" readonly="" id="image_path_original" class="pull-left form-control">
        <!-- <button type="button" data-dismiss="modal" class="btn btn-secondary" id="close">Close</button> -->
      </div>
    </div>
  </div>
</div>


@push('scripts')

<script src="{{asset('js/dropzone.js')}}"> </script>
<script type="text/javascript">
   var deleteUrl = '{{auth()->check() ? route('media.delete') : route('media.delete')}}';
  var media_upload_url = '{{auth()->check() ? route('media.upload') : route('media.upload')}}';
  var confirmDeleteModal = $('#modal-delete');
  $('.media-mgnt-choice').on('click', function(){
      $('.media-mgnt-choice').removeClass('btn-primary');
      $(this).addClass('btn-primary');
      $('.media-manager-box').addClass('hide');
      $($(this).data('option')).removeClass('hide');

  });

var resetImgChoice = function(){
   $('.img-box-choice').on('click', function(){
      $('.img-box-choice').removeClass('selected');
      $(this).addClass('selected');
      $('#image_path_original').val($(this).find('img').data('original'));
  });
}

  var myDropzone = new Dropzone("div#file-upload",
    {
        url: media_upload_url,
        paramName: "file",
        maxFilesize: 128,
        uploadMultiple:false,
        acceptedFiles: 'image/*',
        previewTemplate:'<i class="hidden preview-image"></i>',
        parallelUploads:1,
         success: function(file,data) {
              $('.img-box-choice').removeClass('selected');
              var newImage = '<div class="img-box-choice selected" data-mediaid="'+data['media_id']+'">\
                            <img src="'+data['img']+'" alt="'+data['file']+'" class="img-responsive" data-original="'+data['original']+'">\
                            </div>';
              $('#all-media-files').prepend(newImage);
              $('.media-mgnt-choice').eq(1).click();
              $('#progress .progress-bar').css(
                      'width',
                       '0%'
              );
              $('#image_path_original').val(data['original']);
              resetImgChoice();
        },
        uploadprogress : function(file,progress, bytesent) {
            $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
            );
        },
        sending:function(file, xhr, formData) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            formData.append("type", media_image_type);
        }
    }
  );
  Dropzone.autoDiscover = false;


  resetImgChoice();
  function copyToClipboard() {
        // var text = $('#image_path_original').val();
        $('#image_path_original').select();
        document.execCommand("copy");
        // var $temp = $("<input>");
        // $("body").append($temp);
        // $temp.val(text).select();
        // document.execCommand("copy");
        // $temp.remove();
        toastr.success("Copied!");
  }
      var mediaId = 0;
      var imgElm = '';
      var deletemedia = function(media_id, elm){
          mediaId  = media_id;
          imgElm = $(elm).parents('.img-box-choice');
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
             mediaId= 0;
             confirmDeleteModal.modal('hide');
             imgElm.remove();
            //  toastr.success('Image deleted successfully');
         })
         .fail(function(response, code) {
             toastr.error(response.message);
         });
     }
     $('.img-box-choice').on('click', function(){
         $('.img-box-choice').removeClass('add-new-class');
         $(this).addClass('add-new-class');
       });

</script>
@endpush
