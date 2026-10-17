
@extends('layouts.admin')
@section('content')
{!! Form::model($article, ['route' => ['admin.article.submit', $lang], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($article)
{{ Form::hidden('id', old('id')) }}
@endif
{{ Form::hidden('type', old('type', 0)) }}
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
     <!-- <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button> -->
  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
    <div class="row">
      <div class="form-group col-md-4">
          {!! Form::label('title','Title', ['class'=>'required']) !!}
          {!! Form::text('title', old('title'),['class'=>'form-control', 'required'=>'required', 'id'=>'title']) !!}
      </div>
      {{--<div class="form-group col-md-4">
          {!! Form::label('sub_title','Sub Title') !!}
          {!! Form::text('sub_title', old('sub_title'),['class'=>'form-control']) !!}
      </div>--}}
      <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('posted_on', 'Posted On', ['class'=>'required']) !!}
            {!! Form::date('posted_on', old('posted_on'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
        </div>
          </div>
      <div class="col-md-4">
          {{ Form::label('image_id', 'Image', ['class' => 'control-label required']) }}
          <div class="form-group ">
              <div class="d-flex align-items-center">
                 <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager()">Upload Image</button>
                 <div class="clearfix"></div>
                {{ Form::hidden('image_id', old('image_id'), ['required'=>'required']) }}
                {{ Form::hidden('image_src', old('image_src',  $article && $article->image ? $article->image->image(200,200) : ''),['id'=>'image_src']) }}
                @if(old('image', $article ? $article->image_id : ''))
                <div class="profile_image ml-2" data-mediaid="{{old('image', $article ? $article->image_id : '')}}">
                  <img src="{{old('image_src', $article && $article->image ? $article->image->image(100,100) : '')}}" alt="image" class="img-responsive">
                  <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
                </div>
                @endif
              </div>
          </div>
      </div>

  </div>

     <div class="row">
        <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('category_id', 'Category', ['class'=>'required']) !!}
            {!! Form::select('category_id',$categories, old('category_id'), ['class'=>'form-control', 'required']) !!}
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('status', 'Status') !!}
            {!! Form::select('status',articleStatus(), old('status'), ['class'=>'form-control']) !!}
          </div>
      </div>
      <div class="col-md-3">
          <div class="form-group">
              {!! Form::label('trending', 'Is trending?') !!}
              {!! Form::select('trending',yesNoArray(), old('trending'), ['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
              {!! Form::label('order','Order') !!}
              {!! Form::number('order', old('order'),['class'=>'form-control']) !!}
           </div>
        </div>
        </div>
      <div class="form-group">
        {!! Form::label('short_description', 'Short Description', ['class'=>'required']) !!}
        {!! Form::textarea('short_description', old('short_description'), ['class'=>'form-control',"id"=>"short_description"]) !!}
        <small id="short_description_count">0/100 characters</small>
      </div>
      <div class="form-group">
        {!! Form::label('description', 'Description', ['class'=>'required']) !!}
        {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'id'=>'editor']) !!}
      </div>
  </div>
  <div id="seo_tab" class="tab-pane fade">
    <div class="row">
      <div class="form-group col-md-6">
          {!! Form::label('slug','URL key', ['class'=>'required']) !!}
          {!! Form::text('slug', old('slug'),['class'=>'form-control', 'required'=>'required', 'id'=>'slug']) !!}
      </div>
    <div class="form-group col-md-6">
        {!! Form::label('meta_title','Meta Title') !!}
        {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
    </div>
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



{{--
  @if($article->type==2)
    <div class="form-group">
        {!! Form::label('youtube','Youtube Link') !!}
        {!! Form::text('youtube', old('youtube'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
  @endif
  @if($article->type==1)
    <div class="form-group">
        {!! Form::label('slides','Slides') !!}
        <div id="demo">
          <h4>
            Slide @{{imgPosition+1}} of  @{{imgList.length}}
          </h4>
          <div class="row text-center">
            <div class="col-md-4">
                <a class="btn btn-success" @click="newSlide"><i class="fa fa-plus"></i></a>
                <a class="btn btn-danger" @click="removeSlide"><i class="fa fa-trash"></i></a>
            </div>
            <div class="col-md-4">
                <a class="btn btn-info" @click="prevSlide"><i class="fa fa-arrow-left"></i></a>
                <a class="btn btn-info" @click="nextSlide"><i class="fa fa-arrow-right"></i></a>
            </div>
            <div class="col-md-4">
            <a class="btn btn-warning" @click="moveToPrev"><i class="fa fa-angle-double-left"></i></a>
            <a class="btn btn-warning" @click="moveToNext"><i class="fa fa-angle-double-right"></i></a>
          </div>

          </div>
          <div class="clearfix" style="margin-bottom:20px;"></div>
          <div class="img-slider text-center" name="slide">
          <div v-if="imgList.length" v-for="number in [currentImg]" v-bind:key="number" :class="leftRight" style="position:relative">
            <img :src="imgList[imgPosition].image" class="img-fluid"/>
            <a class="btn btn-primary" @click="addImageToSlide" style="position: absolute;left: 10%;top: 10%;"><i class="fa fa-edit"></i></a>
            <div class="clearfix" style="margin-bottom:20px;"></div>
            <input type="text" class="form-control" placeholder="Slide Title" v-model="imgList[imgPosition].title">
            <textarea class="form-control mceNoEditor" placeholder="Image Text" v-model="imgList[imgPosition].description">@{{imgList[imgPosition].text}}</textarea>
          </div>
        </div>
        <textarea class="hidden d-none mceNoEditor" name="slides">@{{imgList}}</textarea>
         </div>
    </div>
  @endif --}}
  {!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
{{-- <script src="https://unpkg.com/vue/dist/vue.min.js"></script> --}}
@include('admin._partials.editor')

<script type="text/javascript">
  @if(!$article)
    $('#title').on('keyup', function(){
      var value = $(this).val();
      $('#slug').val(value.trim().replaceAll(' ', '-'));
    });
  @endif
  var media_image_type = 'article';
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
<script src="{{asset('theme-admin/js/form.js')}}"></script>

<script type="text/javascript">
function applyCharLimitToEditor(editorId, maxChars, counterId) {

  let editor = tinymce.get(editorId);

  const counterEl = document.getElementById(counterId);

  function getText() {
      return editor.getContent({ format: 'text' }).trim();
  }

  function updateCounter() {
      counterEl.textContent = getText().length + "/" + maxChars + " characters";
  }

  // Key typing limit
  editor.on('keydown', function (e) {
      const txt = getText();
      const allowed = [8, 46, 37, 38, 39, 40];
      if (txt.length >= maxChars &&
          !allowed.includes(e.keyCode) &&
          !(e.ctrlKey || e.metaKey)) {
          e.preventDefault();
      }
  });

  // PASTE limit
  editor.on('paste', function () {
      setTimeout(function () {
          let txt = getText();
          if (txt.length > maxChars) {
              editor.setContent(txt.substring(0, maxChars));
          }
          updateCounter();
      }, 0);
  });

  editor.on('keyup change', updateCounter);

  updateCounter();
}
  let interval = setInterval(function () {
        let ed = tinymce.get('short_description');
        if (ed) {
            clearInterval(interval);
            applyCharLimitToEditor('short_description', 100, 'short_description_count');
        }
    }, 300);

</script>
@endpush
