@extends('layouts.admin')
@section('content')
{!! Form::model($article, ['route' => ['admin.testimonial.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($article)
{{ Form::hidden('id', old('id')) }}
@endif
{{ Form::hidden('type', old('type', 0)) }}
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Testimonial Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  <!-- <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">Video Testimonial</a></li> -->
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-3">
              {!! Form::label('title','Title', ['class'=>'required']) !!}
              <div class="form-group ">
              {!! Form::text('title', old('title'),['class'=>'form-control']) !!}
          </div>
      </div>
      <div class="form-group col-md-3">
          {!! Form::label('pageType', 'Page', ['class'=>'required']) !!}
          <select class="form-control" multiple name="pageType[]" id="pageType">
            @foreach(videoTstimonialPage() as $key => $f)
            <option value="{{$key}}">{{$f}}</option>
            @endforeach
          </select>
      </div>
      <div class="form-group col-3 d-none">
          {!! Form::label('city','City', ['class'=>'required']) !!}
          {!! Form::text('city', old('city'),['class'=>'form-control']) !!}
      </div>

      <div class="col-md-3 d-none">
          {{ Form::label('image_id', 'Image', ['class' => 'control-label required']) }}
          <div class="form-group ">
            <div class="d-flex align-items-center">
              <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager()" style="margin-top:-10px;">Upload Image</button>
                 <div class="clearfix"></div>
                {{ Form::hidden('image_id', old('image_id')) }}
                {{ Form::hidden('image_src', old('image_src',  $article && $article->image ? $article->image->image(50,50) : ''),['id'=>'image_src']) }}
                @if(old('image', $article ? $article->image_id : ''))
                <div class="profile_image ml-2" data-mediaid="{{old('image', $article ? $article->image_id : '')}}">
                  <img src="{{old('image_src', $article && $article->image ? $article->image->image(50,50) : '')}}" alt="image" class="img-responsive">
                  <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
                </div>
                @endif
              </div>
          </div>
      </div>
      <div class="form-group col-12">
        {!! Form::label('description', 'Description', ['class'=>'required']) !!}
        {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'id'=>'editor']) !!}
      </div>
      <div class="form-group col-3">
          {!! Form::label('order','Order', ['class'=>'required']) !!}
          {!! Form::number('order', old('order'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
      <span class="ml-2"><strong>Status</strong></span>
        <input type="checkbox" class="toggle-check" id="status" {{$article && $article->status ? 'checked' : ''}} value="1" name="status">
        <label class="mx-2 t-lable mt-3" for="status"></label>
    </div>
    </div>
    {{--
      <div class="row">
      <div class="col-md-6">
          <div class="form-group">
              {!! Form::label('status', 'Status') !!}
              {!! Form::select('status',articleStatus(), old('status'), ['class'=>'form-control']) !!}
            </div>
      </div>
        <div class="form-group col-md-6">
            {!! Form::label('type', 'Type') !!}
            {!! Form::select('type',testimonialType(), old('type'), ['class'=>'form-control nosearch']) !!}
          </div>
          <div class="form-group col-md-6">
              {!! Form::label('pageType', 'pageType') !!}
              {!! Form::select('pageType',textTstimonialPage(), old('pageType'), ['class'=>'form-control']) !!}
            </div>
      </div>
      --}}
  </div>
  {{--
  <div id="seo_tab" class="tab-pane fade">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('video_link','Please enter embeded Video Link') !!}
            {!! Form::text('video_link', old('video_link'),['class'=>'form-control', 'placeholder'=>"e.g //www.youtube.com/embed/RjWu4nP6SRs?si=HiQ2OgqSi6sQq4VF"]) !!}
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('name','Name') !!}
            {!! Form::text('name', old('name'),['class'=>'form-control']) !!}
        </div>
      </div>
    </div>
<div class="row">
  <div class="col-md-6">
      <div class="form-group ">
          {{ Form::label('image_testimonail_id', 'Image', ['class' => 'col-sm-2 control-label']) }}
          <div class="col-sm-12 float-right">
             <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager('image_testimonail')">Upload Thumbnail Image</button>
             <div class="clearfix"></div>
             <hr>
            {{ Form::hidden('image_testimonail_id', old('image_testimonail_id')) }}
            {{ Form::hidden('image_testimonail_src', old('image_testimonail_src',  $article && $article->imageTestimonail ? $article->imageTestimonail->image(200,200) : ''),['id'=>'image_testimonail_src']) }}
            @if(old('image', $article ? $article->image_testimonail_id : ''))
            <div class="image_testimonail_image" data-mediaid="{{old('image_testimonail_id', $article ? $article->image_testimonail_id : '')}}">
              <img src="{{old('image_testimonail_src', $article && $article->imageTestimonail ? $article->imageTestimonail->image(200,200) : '')}}" alt="image" class="img-responsive">
              <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
            </div>
            @endif
          </div>
      </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
        {!! Form::label('status', 'Status') !!}
        {!! Form::select('vStatus',videoStatus(), old('vStatus'), ['class'=>'form-control']) !!}
      </div>
  </div>
</div>
<div class="row">
  <div class="form-group col-md-6">
      {!! Form::label('type', 'Type') !!}
      {!! Form::select('type',testimonialType(), old('type'), ['class'=>'form-control']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('vPageType', 'Page') !!}
        {!! Form::select('vPageType',videoTstimonialPage(), old('vPageType'), ['class'=>'form-control']) !!}
      </div>
</div>
    </div>
    --}}
</div>

  {!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
{{-- <script src="https://unpkg.com/vue/dist/vue.min.js"></script> --}}
@include('admin._partials.editor')

<script type="text/javascript">
  var media_image_type = 'testimonial';
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
    // else if(imageset==='slide'){
    //     myVue.updateSideImage(mediaId, imgSrc.replace('200-200/', ''));
    // }
    else if(imageset=='editor') {
      tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
      imageset = 'featured';
    }else{
      $('#'+imageset+'_id').val(mediaId);
      $('#'+imageset+'_src').val(imgSrc);
      $('.'+imageset+'_image').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass(imageset+'_image float-right');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
      $('#'+imageset+'_id').after(newImg)
    }
    $('#media-img-manager').modal('hide');
  });
 var removeImage = function(elm){
      $(elm).parents('.profile_image').remove();
      $('#image_id').val('');
      $('#image_src').val('');
      $(elm).parents('.image_testimonail_image').remove();
      $('#image_testimonail_id').val('');
      $('#image_testimonail_src').val('');
  }
  $('#title').on('keyup blur', function(){
      var value = $(this).val();
      $('#slug').val(value);
  });

    var addImageToSlide = function() {
      imageset = 'slide';
      $('#media-img-manager').modal('show');
    }
    $("#media-img-manager").on('hidden.bs.modal', function (e) {
      imageset = 'featured';
    });
    var openMediaManager = function(image = null) {
      imageset = image ? image : 'featured';
      $('#media-img-manager').modal('show');
    }
</script>
<script>
    $(document).ready(function() {
      $('#pageType').on('change',function(){
        let values = $('#pageType').val();
        handlePage(values);
      });

      function handlePage(values){
        $('#pageType').val(values);
        $('#pageType').selectpicker('refresh');
      }
      let pageValues = [];
      @if($article)
        pageValues = @json($article->pages->pluck('page'));
        handlePage(pageValues);
      @endif
    });
</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
