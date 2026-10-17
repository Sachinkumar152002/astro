<div id="media-img-manager" tabindex="-1" role="dialog" aria-labelledby="MediaImageManager" aria-hidden="true" class="modal fade text-center">
  <div role="document" class="modal-dialog modal-media modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 id="MediaImageManager" class="modal-title">Media Manager</h4>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="container flex-grow-1 light-style container-p-y">
          <div class="container-m-nx container-m-ny bg-lightest mb-3">
              <ol class="breadcrumb text-big container-p-x py-3 m-0" id="media-breadcrumb">

              </ol>

              <hr class="m-0" />

              <div class="file-manager-actions container-p-x py-2">
                  <div>
                      <button onclick="moveToUpFolder()" type="button" class="btn btn-default  mr-2"><i class="fas fa-level-up-alt "></i></button>
                      <button onclick="displayDropZone()" type="button" class="btn btn-primary mr-2"><i class="fa fa-upload"></i></button>
                      <button onclick="loadMediaFiles()" type="button" class="btn btn-success  mr-2"><i class="fa fa-retweet"></i></button>
                  </div>
                  <div>
                      <div class="btn-group btn-group-toggle mt-3 mt-lg-0" data-toggle="buttons">
                          <input type="text" class="form-control" id="search_media">
                          <label class="ms-2 btn btn-default icon-btn md-btn-flat" onclick="loadMediaFiles()"> <span class="fa fa-search"></span> </label>
                      </div>
                  </div>

              </div>
              <div class="col-md-12 media-manager-box w-100" id="new-file-upload" style="display: none;" >
                <div   class="dropzone" id="file-upload"></div>
                <div id="progress" class="progress">
                  <div class="progress-bar progress-bar-success"></div>
                </div>
              </div>
              <div class="row media-manager-box w-100 bg-light m-3" id="directory-move" style="display: none;" >
                    <h6>Move to directory</h6>
                    <div class="col-md-6">

                        <select name="" id="media-movefolders" class="form-control"></select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success" onclick="MoveToDirectory()">Move</button>
                    </div>

              </div>
              <hr class="m-0" />
          </div>

          <div class="file-manager-container file-manager-col-view" id="media-container">

          </div>
      </div>
      </div>
    </div>
  </div>
  <div class="hidden" id="select-image-insert">
    <button type="button" class="btn btn-success" id="insert-image">Select</button>
    <div class="img-box-choice selected" data-mediaid="" >
        <img src="{{no_image()}}" alt="preview" class="img-responsive" data-original="">
  </div>
    </div>
<input type="text"  readonly="" id="image_path_original" style="opacity:0">
</div>



@push('styles')
<style>
  #media-img-manager {
    font-size: 1rem;
  }
  .file-manager-actions {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -ms-flex-pack: justify;
    justify-content: space-between;
}
.file-manager-actions > * {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.file-manager-container {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    max-height: 80vh;
    overflow-y: auto;
    min-height : 10vh;
}
.file-item {
    position: relative;
    z-index: 1;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    border: 1px solid #303030;
    cursor: pointer;
}
.file-item:hover,
.file-item.focused {
    border-color: rgba(0, 0, 0, 0);
}
.file-item.focused {
    z-index: 2;
}
.file-item * {
    -ms-flex-negative: 0;
    flex-shrink: 0;
    text-decoration:none;
}
.dark-style .file-item:hover,
.dark-style .file-item.focused {
    border-color: rgba(255, 255, 255, 0.2);
}
.file-item-checkbox {
    margin: 0 !important;
}
.file-item-select-bg {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: -1;
    opacity: 0;
}
.file-item-img {
    background-color: transparent;
    background-position: center center;
    background-size: cover;
}
.file-item-name {
    display: block;
    overflow: hidden;
}
.file-manager-col-view .file-item {
    margin: 0 1rem 1rem 0;
    padding: 1.25rem 0 1rem 0;
    width: 10.6317rem;
    text-align: center;
}
[dir="rtl"] .file-manager-col-view .file-item {
    margin-right: 0;
    margin-left: 0.25rem;
}
.file-manager-col-view .file-item-img,
.file-manager-col-view .file-item-icon {
    display: block;
    margin: 0 auto 5px auto;
    width: 6rem;
    height: 6rem;
    font-size: 2.5rem;
    line-height: 4rem;
}
.file-manager-col-view .file-item-level-up {
    font-size: 1.5rem;
}
.file-manager-col-view .file-item-checkbox,
.file-manager-col-view .file-item-actions {
    position: absolute;
    top: 6px;
}
.file-manager-col-view .file-item-checkbox {
    left: 6px;
}
[dir="rtl"] .file-manager-col-view .file-item-checkbox {
    right: 6px;
    left: auto;
}
.file-manager-col-view .file-item-actions {
    right: 6px;
}
[dir="rtl"] .file-manager-col-view .file-item-actions {
    right: auto;
    left: 6px;
}
.file-manager-col-view .file-item-name {
    width: 100%;
}
.file-manager-col-view .file-manager-row-header,
.file-manager-col-view .file-item-changed {
    display: none;
}
.file-manager-row-view .file-manager-row-header,
.file-manager-row-view .file-item {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    margin: 0 0 0.125rem 0;
    padding: 0.25rem 3rem 0.25rem 2.25em;
    width: 100%;
}
[dir="rtl"] .file-manager-row-view .file-manager-row-header,
[dir="rtl"] .file-manager-row-view .file-item {
    padding-right: 2.25em;
    padding-left: 3rem;
}
.file-manager-row-view .file-item-img,
.file-manager-row-view .file-item-icon {
    display: block;
    margin: 0 1rem;
    width: 2rem;
    height: 2rem;
    text-align: center;
    font-size: 1.25rem;
    line-height: 2rem;
}
.file-manager-row-view .file-item-level-up {
    font-size: 1rem;
}
.file-manager-row-view .file-item-checkbox,
.file-manager-row-view .file-item-actions {
    position: absolute;
    top: 50%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
}
.file-manager-row-view .file-item-checkbox {
    left: 10px;
}
[dir="rtl"] .file-manager-row-view .file-item-checkbox {
    right: 10px;
    left: auto;
}
.file-manager-row-view .file-item-actions {
    right: 10px;
}
[dir="rtl"] .file-manager-row-view .file-item-actions {
    right: auto;
    left: 10px;
}
.file-manager-row-view .file-item-changed {
    display: none;
    margin-left: auto;
    width: 10rem;
}
[dir="rtl"] .file-manager-row-view .file-item-changed {
    margin-right: auto;
    margin-left: 0;
}
.file-manager-row-view .file-item-name {
    width: calc(100% - 4rem);
}
.file-manager-row-view .file-manager-row-header {
    border-bottom: 2px solid rgba(0, 0, 0, 0.05);
    font-weight: bold;
}
.file-manager-row-view .file-manager-row-header .file-item-name {
    margin-left: 4rem;
}
[dir="rtl"] .file-manager-row-view .file-manager-row-header .file-item-name {
    margin-right: 4rem;
    margin-left: 0;
}
.light-style .file-item-name {
    color: #4e5155 !important;
}
.light-style .file-item.selected .file-item-select-bg {
    opacity: 0.15;
}
@media (min-width: 768px) {
    .light-style .file-manager-row-view .file-item-changed {
        display: block;
    }
    .light-style .file-manager-row-view .file-item-name {
        width: calc(100% - 15rem);
    }
}
@media (min-width: 992px) {
    .light-style .file-manager-col-view .file-item-checkbox,
    .light-style .file-manager-col-view .file-item-actions {
        opacity: 0;
    }
    .light-style .file-manager-col-view .file-item:hover .file-item-checkbox,
    .light-style .file-manager-col-view .file-item.focused .file-item-checkbox,
    .light-style .file-manager-col-view .file-item.selected .file-item-checkbox,
    .light-style .file-manager-col-view .file-item:hover .file-item-actions,
    .light-style .file-manager-col-view .file-item.focused .file-item-actions,
    .light-style .file-manager-col-view .file-item.selected .file-item-actions {
        opacity: 1;
    }
}
.material-style .file-item-name {
    color: #4e5155 !important;
}
.material-style .file-item.selected .file-item-select-bg {
    opacity: 0.15;
}
@media (min-width: 768px) {
    .material-style .file-manager-row-view .file-item-changed {
        display: block;
    }
    .material-style .file-manager-row-view .file-item-name {
        width: calc(100% - 15rem);
    }
}
@media (min-width: 992px) {
    .material-style .file-manager-col-view .file-item-checkbox,
    .material-style .file-manager-col-view .file-item-actions {
        opacity: 0;
    }
    .material-style .file-manager-col-view .file-item:hover .file-item-checkbox,
    .material-style .file-manager-col-view .file-item.focused .file-item-checkbox,
    .material-style .file-manager-col-view .file-item.selected .file-item-checkbox,
    .material-style .file-manager-col-view .file-item:hover .file-item-actions,
    .material-style .file-manager-col-view .file-item.focused .file-item-actions,
    .material-style .file-manager-col-view .file-item.selected .file-item-actions {
        opacity: 1;
    }
}
.dark-style .file-item-name {
    color: #fff !important;
}
.dark-style .file-item.selected .file-item-select-bg {
    opacity: 0.15;
}
@media (min-width: 768px) {
    .dark-style .file-manager-row-view .file-item-changed {
        display: block;
    }
    .dark-style .file-manager-row-view .file-item-name {
        width: calc(100% - 15rem);
    }
}
@media (min-width: 992px) {
    .dark-style .file-manager-col-view .file-item-checkbox,
    .dark-style .file-manager-col-view .file-item-actions {
        opacity: 0;
    }
    .dark-style .file-manager-col-view .file-item:hover .file-item-checkbox,
    .dark-style .file-manager-col-view .file-item.focused .file-item-checkbox,
    .dark-style .file-manager-col-view .file-item.selected .file-item-checkbox,
    .dark-style .file-manager-col-view .file-item:hover .file-item-actions,
    .dark-style .file-manager-col-view .file-item.focused .file-item-actions,
    .dark-style .file-manager-col-view .file-item.selected .file-item-actions {
        opacity: 1;
    }
}
</style>
@endpush
@push('scripts')
<script src="{{asset('js/dropzone.js')}}"> </script>
<script type="text/javascript">
var media_folder_id = 0;
var media_folder_id_up = 0;
var mediaFetchUrl = '{{route('admin.media.fetch')}}';
var newFolderUrl = '{{route('admin.media.newfolder')}}';
var media_upload_url =  '{{route('admin.media.upload')}}';
var deleteUrl = '{{route('admin.media.delete')}}';
var moveMediaUrl = '{{route('admin.media.move')}}';

$(function(){
    loadMediaFiles();
});
var loadMediaFiles = function(){
    $('#media-container').html(`<svg width="75" height="75" viewBox="0 0 75 75" xmlns="http://www.w3.org/2000/svg"><style>.spinner_zWVm{animation:spinner_5QiW 1.2s linear infinite,spinner_PnZo 1.2s linear infinite}.spinner_gfyD{animation:spinner_5QiW 1.2s linear infinite,spinner_4j7o 1.2s linear infinite;animation-delay:.1s}.spinner_T5JJ{animation:spinner_5QiW 1.2s linear infinite,spinner_fLK4 1.2s linear infinite;animation-delay:.1s}.spinner_E3Wz{animation:spinner_5QiW 1.2s linear infinite,spinner_tDji 1.2s linear infinite;animation-delay:.2s}.spinner_g2vs{animation:spinner_5QiW 1.2s linear infinite,spinner_CMiT 1.2s linear infinite;animation-delay:.2s}.spinner_ctYB{animation:spinner_5QiW 1.2s linear infinite,spinner_cHKR 1.2s linear infinite;animation-delay:.2s}.spinner_BDNj{animation:spinner_5QiW 1.2s linear infinite,spinner_Re6e 1.2s linear infinite;animation-delay:.3s}.spinner_rCw3{animation:spinner_5QiW 1.2s linear infinite,spinner_EJmJ 1.2s linear infinite;animation-delay:.3s}.spinner_Rszm{animation:spinner_5QiW 1.2s linear infinite,spinner_YJOP 1.2s linear infinite;animation-delay:.4s}@keyframes spinner_5QiW{0%,50%{width:7.33px;height:7.33px}25%{width:1.33px;height:1.33px}}@keyframes spinner_PnZo{0%,50%{x:1px;y:1px}25%{x:4px;y:4px}}@keyframes spinner_4j7o{0%,50%{x:8.33px;y:1px}25%{x:11.33px;y:4px}}@keyframes spinner_fLK4{0%,50%{x:1px;y:8.33px}25%{x:4px;y:11.33px}}@keyframes spinner_tDji{0%,50%{x:15.66px;y:1px}25%{x:18.66px;y:4px}}@keyframes spinner_CMiT{0%,50%{x:8.33px;y:8.33px}25%{x:11.33px;y:11.33px}}@keyframes spinner_cHKR{0%,50%{x:1px;y:15.66px}25%{x:4px;y:18.66px}}@keyframes spinner_Re6e{0%,50%{x:15.66px;y:8.33px}25%{x:18.66px;y:11.33px}}@keyframes spinner_EJmJ{0%,50%{x:8.33px;y:15.66px}25%{x:11.33px;y:18.66px}}@keyframes spinner_YJOP{0%,50%{x:15.66px;y:15.66px}25%{x:18.66px;y:18.66px}}</style><rect class="spinner_zWVm" x="1" y="1" width="7.33" height="7.33"/><rect class="spinner_gfyD" x="8.33" y="1" width="7.33" height="7.33"/><rect class="spinner_T5JJ" x="1" y="8.33" width="7.33" height="7.33"/><rect class="spinner_E3Wz" x="15.66" y="1" width="7.33" height="7.33"/><rect class="spinner_g2vs" x="8.33" y="8.33" width="7.33" height="7.33"/><rect class="spinner_ctYB" x="1" y="15.66" width="7.33" height="7.33"/><rect class="spinner_BDNj" x="15.66" y="8.33" width="7.33" height="7.33"/><rect class="spinner_rCw3" x="8.33" y="15.66" width="7.33" height="7.33"/><rect class="spinner_Rszm" x="15.66" y="15.66" width="7.33" height="7.33"/></svg>`);
    $.ajax({
        url: mediaFetchUrl,
        type : 'POST',
        data : {folder_id:media_folder_id, term: $('#search_media').val() , _token:window.Laravel.csrfToken},
        dataType: 'json'
    })
    .done(function(response) {
            $('#media-container').html(response.folderimages);
            $('#media-breadcrumb').html(response.breadcrumb);
            $('#media-movefolders').html(response.movefolders);
            media_folder_id_up = response.up_id;
            $('#directory-move').hide();
            $('#media-movefolders').selectpicker('destroy');

    })
    .fail(function(response, code) {
        toastr.error(response.message);
    });
}
var loadFolderContent = function(folder_id){
    media_folder_id = folder_id;
    loadMediaFiles();
}
var moveToUpFolder = function(){
    media_folder_id = media_folder_id_up;
    loadMediaFiles();
}
var displayDropZone = function(){
    $('#new-file-upload').toggle();
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
            loadMediaFiles();
            $('#new-file-upload').hide();
        },
        uploadprogress : function(file,progress, bytesent) {
            $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
            );
        },
        sending:function(file, xhr, formData) {
            formData.append("_token", window.Laravel.csrfToken);
            formData.append("folder_id", media_folder_id);
        }
    }
  );
  Dropzone.autoDiscover = false;

var createNewFolder = function(old_folder_id){
    var newFolderName = prompt("Enter New Folder Name");
    if(newFolderName!='') {
        $.ajax({
            url: newFolderUrl,
            type : 'POST',
            data : {folder_id:media_folder_id, name:newFolderName,old_folder_id:old_folder_id, _token:window.Laravel.csrfToken},
            dataType: 'json'
        })
        .done(function(response) {
            loadFolderContent(response.folder_id)
        })
        .fail(function(response, code) {
            toastr.error(response.message);
        });
    }
}
function copyToClipboard(string) {
        $('#image_path_original').val(string);
        $('#image_path_original').select();
        document.execCommand("copy");
        toastr.success("Copied!");
}
var selectImageAndInsert = function(media_id, imageprview, image_original_path){
    var elmMedia = $('#select-image-insert');
    elmMedia.find('.img-box-choice').data('mediaid', media_id);
    elmMedia.find('img').attr('src', imageprview);
    elmMedia.find('img').data('original', image_original_path);
    $('#insert-image').click();
    console.log('clickedselected')
}
var deletemedia = function(media_id, fmType){
    if(confirm("Are you sure want to delete!")){
        $.ajax({
            url: deleteUrl,
            type : 'DELETE',
            data : {media_id:media_id,folder_media :fmType, _token:window.Laravel.csrfToken},
            dataType: 'json'
        })
        .done(function(response) {
            loadMediaFiles();
        })
        .fail(function(response, code) {
            toastr.error(response.message);
        });
    }
}
var move_media_id = 0;
var move_type = '';
var moveemedia = function(media_id, type_F_M){
    move_media_id = media_id;
    move_type = type_F_M;
    $('#directory-move').show();
}
var MoveToDirectory = function(){
    $.ajax({
        url: moveMediaUrl,
        type : 'POST',
        data : {media_id:move_media_id,folder_media :move_type, new_folder_id: $('#media-movefolders').val(), _token:window.Laravel.csrfToken},
        dataType: 'json'
    })
    .done(function(response) {
        loadMediaFiles();
        toastr.success(response.message);
    })
    .fail(function(response, code) {
        toastr.error(response.message);
    });
}
</script>
@endpush
