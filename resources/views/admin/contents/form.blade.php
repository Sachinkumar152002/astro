@extends('layouts.admin')

@section('content')
<h1 class="text-primary float-left">Content Template</h1>
<div class="clearfix"></div>
{!! Form::model($templateContent, ['route' => ['admin.content.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($templateContent)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="clearfix"></div>
<ul class="nav nav-tabs">
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  </ul>
  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
  <div class="row">
    <div class="col-md-12">
      <h3>USE Variable :  </h3>
      <ul>
        <li>%%NAME%% <small>Change  page name  with %%NAME%% </small></li>
        <li>%%PHONE%% <small>Change phone  with %%PHONE%% </small></li>
        <li>%%ADDRESS%% <small>Change address  with %%ADDRESS%% </small></li>
        <li>%%CITY%% <small>Change city name with %%CITY%% </small></li>
        <li>%%STATE%% <small>Change state name with %%STATE%% </small></li>
        <li>%%COUNTRY%% <small>Change country name with %%COUNTRY%% </small></li>
        <li>%%ZIPCODE%% <small>Change zipcode name with %%ZIPCODE%% </small></li>
        <li>%%CURRENT_URL%% <small>Change CURRENT_URL with %%CURRENT_URL%% </small></li>
        <li>%%PARENT_URL%% <small>Change PARENT_URL with %%PARENT_URL%% </small></li>
        <li>%%PARENT_NAME%% <small>Change parent page name with %%PARENT_NAME%% </small></li>
        <li>%%COUNT_DESTINATIONS%% <small>Change total_destinations count with %%COUNT_DESTINATIONS%% </small></li>
        <li>%%WEBSITE%% <small>Change website url of  with %%WEBSITE%% </small></li>
        <li>%%IATA_CODE%% <small>Change iata code with %%IATA_CODE%% </small></li>
        <li>%%TYPE%% <small>Change page type with %%TYPE%% </small></li>
        <li>%%TIME_ZONE%% <small>Change page timezone with %%TIME_ZONE%% </small></li>
        <li>%%LON%% <small>Change page timezone with %%LON%% </small></li>
      </ul>

      <div class="form-group">
        {!! Form::label('overwrite', 'overwrite') !!}
        {!! Form::select('overwrite[]',pageTemplates(), old('overwrite', $templateContent ? json_decode($templateContent->overwrite) : []), ['class'=>'form-control', 'multiple']) !!}
      </div>
    <div class="form-group">
        {!! Form::label('content','Content') !!}
        {!! Form::textarea('content', old('content'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-6">
      {!! Form::label('status', 'Status') !!}
      {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control']) !!}
  </div>
    </div>
  </div>
    </div>
  </div>
{!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
{!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@include('admin._partials.editor')

<script type="text/javascript">
  var media_image_type = 'none';
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
    var newImg  = selectedImg.clone()
    if(imageset=='editor') {
      tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
      imageset = 'featured';
    }
    $('#media-img-manager').modal('hide');
  });
  $("#media-img-manager").on('hidden.bs.modal', function (e) {
    imageset = 'editor';
  });
  var openMediaManager = function() {
    imageset = 'editor';
    $('#media-img-manager').modal('show');
  }
</script>
@endpush
