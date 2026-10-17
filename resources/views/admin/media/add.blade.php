<div id="media-img-add" tabindex="-1" role="dialog" aria-labelledby="MediaImageAdd" aria-hidden="true" class="modal fade text-center">
  <div role="document" class="modal-dialog modal-media">
    <div class="modal-content">
      <div class="modal-header bg-primary border-0">
        <h4 id="MediaImageAdd" class="modal-title text-white">Media</h4>
        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="col-md-12 media-manager-box" id="new-file-upload" >
          <div class="dropzone" id="file-upload"></div>
          <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
          </div>
        </div>
        <div class="col-md-12 media-manager-box" id="new-file-form" style="display: none;">
          {!! Form::open(['route' => 'admin.media.submit', 'method'=>'post', 'id'=>'media-form', 'class'=>'form-horizontal']) !!}
          {{ Form::hidden('media_id', '',['id'=>'media_id']) }}
          <div class="form-group row">
              {{ Form::label('title', 'Title', ['class' => 'col-sm-3 form-control-label']) }}
              <div class="col-sm-9">
                {{ Form::text('title', '',[ 'placeholder'=>"Title", 'class'=>'form-control form-control-success','id'=>'title']) }}
              </div>
          </div>
          <div class="form-group row">
              {{ Form::label('caption', 'Caption', ['class' => 'col-sm-3 form-control-label']) }}
              <div class="col-sm-9">
                {{ Form::text('caption', '',[ 'placeholder'=>"Caption", 'class'=>'form-control form-control-success','id'=>'caption']) }}
              </div>
          </div>
           <div class="form-group row">
              {{ Form::label('type', 'Type', ['class' => 'col-sm-3 form-control-label']) }}
              <div class="col-sm-9">
                {{ Form::select('type', $types ,'',[ 'placeholder'=>"type", 'class'=>'form-control form-control-success','id'=>'type']) }}
              </div>
          </div>

          <div class="form-group row">
              {{ Form::label('image', 'Image', ['class' => 'col-sm-3 form-control-label']) }}
              <div class="col-sm-9">
                  <img src="" alt="choose-image" id="new-image-src">
              </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="return addMedia('#media-form')">Save</button>
      </div>
    </div>
  </div>
</div>
