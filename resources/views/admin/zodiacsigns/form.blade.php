
@extends('layouts.admin')
@section('content')
<h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
<div class="clearfix"></div>
{!! Form::model($item, ['route' => ['admin.zodiacsigns.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
{{ Form::hidden('type', old('type', 0)) }}

<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
      <div class="form-group">
          {!! Form::label('name','Name') !!}
          {!! Form::text('name', old('name'),['class'=>'form-control', 'required'=>'required']) !!}
      </div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group ">
            {{ Form::label('image_id', 'Image', ['class' => 'col-sm-12 control-label']) }}
            <div class="col-sm-12 float-right">
               <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager('featured')">Upload Image</button>
               <div class="clearfix"></div>
               <hr>
              {{ Form::hidden('image_id', old('image_id'), ['required'=>'required']) }}
              {{ Form::hidden('image_src', old('image_src',  $item && $item->image ? $item->image->image(400,400) : ''),['id'=>'image_src']) }}
              @if($item->image)
              <div class="profile_image" data-mediaid="{{old('image', $item ? $item->image_id : '')}}">
                <img src="{{old('image_src', $item && $item->image ? $item->image->image(400,400) : '')}}" alt="image" class="img-responsive">
                <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
              </div>
              @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
      <div class="form-group ">
          {{ Form::label('banner_image_id', 'Banner Image', ['class' => 'col-sm-12 control-label']) }}
          <div class="col-sm-12 float-right">
             <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager('banner')">Upload Image</button>
             <div class="clearfix"></div>
             <hr>
            {{ Form::hidden('banner_image_id', old('banner_image_id'), ['required'=>'required']) }}
            {{ Form::hidden('banner_image_src', old('banner_image_src',  $item && $item->bannerImage ? $item->bannerImage->image(400,400) : ''),['id'=>'banner_image_src']) }}
            @if(old('banner_image', $item ? $item->bannerImage : ''))
            <div class="banner profile_image" data-mediaid="{{old('banner_image', $item ? $item->banner_image_id : '')}}">
              <img src="{{old('banner_image_src', $item && $item->bannerImage ? $item->bannerImage->image(400,400) : '')}}" alt="image" class="img-responsive">
              <i class="fa fa-trash text-danger" onclick="removeBannerImage(this)"></i>
            </div>
            @endif
          </div>
      </div>
  </div>

    <div class="col-md-6">
      <div class="form-group ">
        {{ Form::label('dates', 'Sign Date', ['class' => 'col-sm-12 control-label']) }}
          <table class="table">
              <tbody>
                <tr>
                  <td>{!! Form::select('sign_date_sd',monthDays(), old('sign_date_sd'), ['class'=>'form-control']) !!}</td>
                  <td>{!! Form::select('sign_date_sm',astroMonths(), old('sign_date_sm'), ['class'=>'form-control']) !!}</td>
                  <td>-</td>
                  <td>{!! Form::select('sign_date_ed',monthDays(), old('sign_date_ed'), ['class'=>'form-control']) !!}</td>
                  <td>{!! Form::select('sign_date_em',astroMonths(), old('sign_date_em'), ['class'=>'form-control']) !!}</td>
                </tr>
              </tbody>
          </table>
      </div>


    </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          {!! Form::label('lucky_colors','Lucky Color') !!}
          {!! Form::text('lucky_colors', old('lucky_colors'),['class'=>'form-control tagsinput']) !!}
        </div>
        <div class="form-group">
          {!! Form::label('lucky_gemstones','Lucky Gemstones') !!}
          {!! Form::text('lucky_gemstones', old('lucky_gemstones'),['class'=>'form-control tagsinput' ]) !!}
        </div>
        <div class="form-group">
          {!! Form::label('lucky_days','Lucky Days') !!}
          {!! Form::select('lucky_days[]',weekDays(), old('lucky_days', ($item->lucky_days ? json_decode($item->lucky_days) : [])), ['class'=>'form-control', 'multiple']) !!}
        </div>
        <div class="form-group">
          {!! Form::label('compatible_zodiac_signs','Compatible Zodiac Signs') !!}
          {!! Form::select('compatible_zodiac_signs[]',$allSigns, old('compatible_zodiac_signs', ($item->compatible_zodiac_signs ? json_decode($item->compatible_zodiac_signs) : [])), ['class'=>'form-control', 'multiple']) !!}
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          {!! Form::label('symbol','Symbol') !!}
          {!! Form::text('symbol', old('symbol'),['class'=>'form-control tagsinput' ]) !!}
        </div>
        <div class="form-group">
          {!! Form::label('lucky_number','Lucky Number') !!}
          {!! Form::text('lucky_number', old('lucky_number'),['class'=>'form-control tagsinput' ]) !!}
        </div>
        <div class="form-group">
          {!! Form::label('ruiling_planet','Ruiling Planet') !!}
          {!! Form::select('ruiling_planet[]',planets(), old('ruiling_planet', ($item->ruiling_planet ? json_decode($item->ruiling_planet) : [])), ['class'=>'form-control', 'multiple']) !!}
        </div>

      </div>
      <div class="form-group col-md-6">
        {!! Form::label('status', 'Status') !!}
        {!! Form::select('status',statusArray(), old('status'), ['class'=>'form-control']) !!}
      </div>
    </div>
      <div class="form-group">
        {!! Form::label('description', 'Description') !!}
        {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'id'=>'editor']) !!}
      </div>
  </div>
  <div id="seo_tab" class="tab-pane fade">
    <div class="form-group">
        {!! Form::label('meta_title','Meta Title') !!}
        {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('meta_keywords','Meta Keywords') !!}
        {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('meta_description','Meta Description') !!}
        {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
    </div>
    </div>
</div>
  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
  {!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
  var media_image_type = 'zodiacsign';
  var imageset = 'featured';
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
    if(imageset==='featured'){
      $('#image_id').val(mediaId);
      $('#image_src').val(imgSrc);
      $('.profile_image').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
      $('#image_id').after(newImg)
    }
    else if(imageset==='banner'){
    $('#banner_image_id').val(mediaId);
    $('#banner_image_src').val(imgSrc);
    $('.banner.profile_image').remove();
    newImg.find('.action').remove();
    newImg.removeClass('img-box-choice selected').addClass('banner profile_image float-right');
    newImg.append('<i class="fa fa-trash text-danger" onclick="removeBannerImage(this)"></i>');
    $('#banner_image_id').after(newImg)
  }

    else if(imageset=='editor') {
      tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
      imageset = 'featured';
    }
    $('#media-img-manager').modal('hide');
  });
  var removeImage = function(elm){
        $(elm).parents('.profile_image').remove();
        $('#image_id').val('');
        $('#image_src').val('');
    }

  $("#media-img-manager").on('hidden.bs.modal', function (e) {
    imageset = 'featured';
  });

var openMediaManager = function(option) {
    imageset = option;
    $('#media-img-manager').modal('show');
  }
  var removeBannerImage = function(elm){
    $(elm).parents('.profile_image').remove();
    $('#banner_image_id').val('');
    $('#banner_image_src').val('');
}
</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
