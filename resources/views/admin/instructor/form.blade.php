@extends('layouts.admin')

@section('content')
<div id="profile-app">
  <h1 class="text-primary float-left">{{ $moduleName }} Detail </h1>
  <div class="clearfix"></div>

  {!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post', 'onsubmit'=>"return validateAndSubmit(this)"]) !!}

  @if($item)
    {{ Form::hidden('id', old('id')) }}
  @endif

<div class="clearfix"></div>



  <div class="form-group col-md-12">
    <div class="form-group ">
        {{ Form::label('image_id', 'Image', ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-12 float-right">
           <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager()">Upload Image</button>
           <div class="clearfix"></div>
           <hr>
          {{ Form::hidden('image_id', old('image_id')) }}
          {{ Form::hidden('image_src', old('image_src',  $item && $item->image ? $item->image->image(200,200) : ''),['id'=>'image_src']) }}
          @if(old('image', $item ? $item->image_id : ''))
          <div class="profile_image" data-mediaid="{{old('image', $item ? $item->image_id : '')}}">
            <img src="{{old('image_src', $item && $item->image ? $item->image->image(200,200) : '')}}" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
          </div>
          @endif
        </div>
    </div>


          </div>
          <div class="clearfix"></div>

          <div class="form-group">
              {!! Form::label('name', 'Name') !!}
              {!! Form::text('name', old('name'), ['class' => 'form-control', 'required' => 'required']) !!}
              {{-- {!! Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) !!} --}}
          </div>

          <div class="form-group">
            {!! Form::label('service', 'Service') !!}
            {!! Form::select('service', $services->pluck('name', 'name'), old('service'), ['class' => 'form-control']) !!}
        </div>

  {!! Form::submit('Submit',['class'=>'btn btn-primary']) !!}
  {!! Form::close() !!}
    @include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script type="text/javascript">
  var media_image_type = 'instructor';
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
    }
    $('#media-img-manager').modal('hide');
  });
 var removeImage = function(elm){
      $(elm).parents('.profile_image').remove();
      $('#image_id').val('');
      $('#image_src').val('');
  }
  $('#title').on('keyup blur', function(){
      var value = $(this).val();
      $('#slug').val(value);
  });
  {{--
  var myVue = new Vue({
      el: '#demo',
      data: {
        message: 'Click for slide',
        show: true,
        imgList: {!! $article->slides->toJson() !!},
        currentImg: 0,
        oldImg : 0
      },
      computed:{
          imgPosition(){
              if(this.currentImg<0) { this.currentImg =  this.imgList.length-1; }
              else if(this.currentImg>=this.imgList.length) { this.currentImg =  0; }
            return Math.abs(this.currentImg) % this.imgList.length;
          },
          leftRight(){
              return this.oldImg <= this.currentImg ? 'move-right slide-animate' : 'move-left slide-animate';
          }
      },
      mounted(){
          if(this.imgList.length==0) {
            this.newSlide();
          }
      },
      methods : {
          nextSlide(){
            this.oldImg = this.currentImg;
            this.currentImg = this.currentImg + 1;
          },
          prevSlide(){
            this.oldImg = this.currentImg;
            // this.imgList.reverse();
            this.currentImg = this.currentImg - 1;

          },
          moveToNext(){
            let c = this.imgList[this.currentImg];
            let next = this.currentImg+1 == this.imgList.length ? 0 : this.currentImg+1;
            let n = this.imgList[next];
            this.imgList[this.currentImg] = n;
            this.imgList[next] = c;
            this.nextSlide();
          },
          moveToPrev(){
            let c = this.imgList[this.currentImg];
            let next = this.currentImg-1 == 0 ? this.imgList.length-1 : this.currentImg-1;
            let n = this.imgList[next];
            this.imgList[this.currentImg] = n;
            this.imgList[next] = c;
            this.prevSlide();
          },
          newSlide() {
            let newItem = {'image' : 'http://via.placeholder.com/1200x500', 'description':'', 'media_id': 0, "title" :""};
             this.imgList.push(newItem);
             this.currentImg = this.imgList.length - 1;
             toastr.success('new slide added');
          },
          removeSlide(){
            if(confirm('Are you sure want to remove current slide?')) {
              this.imgList.splice(this.currentImg,1);
              this.currentImg -= 1;
              toastr.success('Slided removed');
              if(this.imgList.length==0) {
                this.newSlide();
              }
            }
          },
          addImageToSlide(){
            addImageToSlide();
          },
          updateSideImage(media_id, media_src){
            this.imgList[this.currentImg]['media_id'] = media_id;
            this.imgList[this.currentImg]['image'] = media_src;
          }
      }
    })
    --}}
    var addImageToSlide = function() {
      imageset = 'slide';
      $('#media-img-manager').modal('show');
    }
    $("#media-img-manager").on('hidden.bs.modal', function (e) {
      imageset = 'featured';
    });
    var openMediaManager = function() {
      imageset = 'featured';
      $('#media-img-manager').modal('show');
    }
</script>
<script type="text/javascript">
var button = $('input[type="submit"]');
var validateAndSubmit = function(form){
  tinyMCE.triggerSave();
  var _f = $(form);
  var formData = new FormData(_f[0]);

  button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
  _f.find('.btn-outline-danger').removeClass('btn-outline-danger');

  $.ajax({
    method: "POST",
    url: _f.attr('action'),
    data: formData,
    processData: false,
    contentType: false
  })
  .done(function( msg ) {
      toastr.success(msg.message);
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
      toastr.success("Please wait redirecting to listing page");
      setTimeout(function(){
         window.location.href=msg.redirectto;
      }, 3000);
  })
  .fail(function(errors){
      var err = $.parseJSON(errors.responseText);
      for(i in err){
          $('#'+i).addClass('btn-outline-danger');
          toastr.error(err[i][0]);
      }
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
  });
  return false;
}
</script>

@endpush
