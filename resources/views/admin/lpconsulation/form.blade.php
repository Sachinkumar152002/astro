@extends('layouts.admin')
@section('content')
<h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
<div class="clearfix"></div>
{!! Form::model($item, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#items_tab">Items</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#section_one_tab">Section One</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#section_two_tab">Section two</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#section_three_tab">Section three</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#section_four_tab">Section Four</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#section_five_tab">Section Five</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#testimonials_tab">Testimonials</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#video_testimonials_tab">Video Testimonials</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#faq_tab">FAQ's</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
      <div class="form-group">
          {!! Form::label('title','Title') !!}
          {!! Form::text('title', old('title'),['class'=>'form-control', 'required'=>'required']) !!}
      </div>
<div class="row">

    <div class="col-md-6">
      <div class="form-group px-1">
          <span class="ml-2 mt-2"><strong>Status</strong></span>
          <input type="checkbox" class="toggle-check" id="status" value="1" {{ old('status', $item && $item->status)==1 ? 'checked' : '' }} name="status">
            <label class="mx-2 t-lable mt-2" for="status">Status</label>
      </div>
      <div class="form-group px-1">
          <span class="ml-2 mt-2"><strong>Phone Number</strong></span>
          <input type="checkbox" class="toggle-check" id="display_number" value="1" {{ old('display_number', $item && $item->display_number)==1 ? 'checked' : '' }} name="display_number">
            <label class="mx-2 t-lable mt-2" for="status">Number</label>
      </div>
      <div class="form-group px-1">
          <span class="ml-2 mt-2"><strong>Email</strong></span>
          <input type="checkbox" class="toggle-check" id="display_email" value="1" {{ old('display_email', $item && $item->display_email)==1 ? 'checked' : '' }} name="display_email">
            <label class="mx-2 t-lable mt-2" for="status">Email</label>
      </div>
      <div class="form-group px-1">
          <span class="ml-2 mt-2"><strong>Social Links</strong></span>
          <input type="checkbox" class="toggle-check" id="display_social" value="1" {{ old('display_social', $item && $item->display_social)==1 ? 'checked' : '' }} name="display_social">
            <label class="mx-2 t-lable mt-2" for="status">Social Links</label>
      </div>
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('header_script','Header Script') !!}
        {!! Form::textarea('header_script', old('header_script'),['class'=>'form-control mceNoEditor']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('footer_script','Header Script') !!}
        {!! Form::textarea('footer_script', old('footer_script'),['class'=>'form-control mceNoEditor']) !!}
    </div>
  </div>

  </div>
  <div id="items_tab" class="tab-pane fade"> items </div>
  <div id="section_one_tab" class="tab-pane fade">
    <div class="row mt-3 px-3">
      <div class="form-group col-md-4 d-flex align-items-center">
         <button type="button" class="btn float-left" onclick="openMediaManager('image')">Background Image</button>
         <input type="hidden" name="section_one[image_id]" value="{{$item?$item->image_id:0}}" id="section_one_image_id">
        <div class="image_img_div d-flex align-items-center ml-2" data-mediaid="{{$item ? $item->image_id : 0}}">
          @if($item && $item->image)
            <img src="{{$item->image->image(50,50)}}" id="image_src" alt="admin image" class="img-responsive">
            <i class="fa fa-trash text-danger ml-2" onclick="removeImage(this, 'image')"></i>
          @else
            <img src="{{no_image()}}" width="100" id="image_src" alt="admin image" class="img-responsive ml-2">
          @endif
        </div>
      </div>
    </div>
    <div class="form-group">
        {!! Form::label('section_one[heading]','Heading') !!}
        {!! Form::text('section_one', old('section_one[heading]'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('section_one[sub_heading]','Sub Heading') !!}
        {!! Form::text('section_one', old('section_one[sub_heading]'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('section_one[text]','Text') !!}
        {!! Form::text('section_one', old('section_one[text]'),['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('section_one[video_url]','Video URL') !!}
      <div class="row">
        <div class="col-md-10">
          {!! Form::text('section_one[video_url]', old('section_one[video_url]'),['class'=>'form-control', 'required']) !!}
          <small>Paste any youtube id or youtube url</small>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-primary" onclick="fetchVideoDetails()">Fetch Details</button>
        </div>
      </div>

    </div>

    <div class="form-group">
      {!! Form::label('section_one[video_id]','Video URL') !!}
      {!! Form::text('section_one[video_id]', old('section_one[video_id]'),['class'=>'form-control', 'readonly', 'required']) !!}
    </div>

  <div class="form-group">
      {!! Form::label('section_one[video_title]','Title') !!}
      {!! Form::text('section_one[video_title]', old('section_one[video_title]'),['class'=>'form-control', 'required'=>'required']) !!}
  </div>
  <div class="form-group">
      {!! Form::label('section_one[thumbnail]','Thumbnail') !!}
      <img src="{{old('section_one[thumbnail]', $item?$item->section_one->thumbnail:'')}}" alt="admin image" id="section_one_thumbnail_src" class="float-right img-fluid" style="max-width:200px;">
      {!! Form::text('section_one[thumbnail]', old('section_one[thumbnail]'),['class'=>'form-control', 'required'=>'required']) !!}
  </div>
  </div>
  <div id="section_two_tab" class="tab-pane fade">
    <div class="form-group">
        {!! Form::label('section_two[heading]','Heading') !!}
        {!! Form::text('section_two', old('section_two[heading]'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('section_two[heading_text]','Heading Text') !!}
        {!! Form::text('section_two', old('section_two[heading_text]'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
    <h3 class="h3">Cards</h3>
    <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Sr.</th>
              <th>Heading</th>
              <th>Text</th>
              <th>Image</th>
            </tr>
          </thead>
          <tbody>
            @for($i=0; $i<6; $i++)
            <tr>
              <td>1.</td>
              <td>{!! Form::text('section_two[cards]['.$i.'][title]', old('section_two[cards]['.$i.'][title]'),['class'=>'form-control']) !!}</td>
              <td>{!! Form::textarea('section_two[cards]['.$i.'][text]', old('section_two[cards]['.$i.'][text]'),['class'=>'form-control mceNoEditor']) !!}</td>
              <td>
                <div class="form-group col-md-4 d-flex align-items-center">
                 <button type="button" class="btn float-left" onclick="openMediaManager('image')">Background Image</button>
                 <input type="hidden" name="section_two[cards]['.$i.'][image_id]" value="{{$item?$item->image_id:$i}}" id="section_two_image_id_$i">
                <div class="image_img_div d-flex align-items-center ml-2" data-mediaid="{{$item ? $item->image_id : 0}}">
                  @if($item && $item->image)
                    <img src="{{$item->image->image(50,50)}}" id="image_src" alt="admin image" class="img-responsive">
                    <i class="fa fa-trash text-danger ml-2" onclick="removeImage(this, 'image')"></i>
                  @else
                    <img src="{{no_image()}}" width="100" id="image_src" alt="admin image" class="img-responsive ml-2">
                  @endif
                </div>
              </div></td>
            </tr>
            @endfor
          </tbody>
        </table>
    </div>

  </div>
  <div id="section_three_tab" class="tab-pane fade">
    <div class="form-group">
        {!! Form::label('section_three[heading]','Heading') !!}
        {!! Form::text('section_three', old('section_three[heading]'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
    <h3 class="h3">Circle Images</h3>
    <div class="form-group col-md-4 d-flex align-items-center">
     <button type="button" class="btn float-left" onclick="openMediaManager('image')">Circle Image</button>
     <input type="hidden" name="section_three[image_id]" value="{{$item?$item->image_id:0}}" id="section_three_image_id">
    <div class="image_img_div d-flex align-items-center ml-2" data-mediaid="{{$item ? $item->image_id : 0}}">
      @if($item && $item->image)
        <img src="{{$item->image->image(50,50)}}" id="image_src" alt="admin image" class="img-responsive">
        <i class="fa fa-trash text-danger ml-2" onclick="removeImage(this, 'image')"></i>
      @else
        <img src="{{no_image()}}" width="100" id="image_src" alt="admin image" class="img-responsive ml-2">
      @endif
    </div>
  </div>
    <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Sr.</th>
              <th>Image</th>
            </tr>
          </thead>
          <tbody>
            @for($i=0; $i<4; $i++)
            <tr>
              <td>{{$i++}}</td>
              <td>
                <div class="form-group col-md-4 d-flex align-items-center">
                 <button type="button" class="btn float-left" onclick="openMediaManager('image')">Image</button>
                 <input type="hidden" name="section_three[circleimg][$i][image_id]" value="{{$item?$item->image_id:0}}" id="section_three_circle_image_{{$i}}_image_id">
                <div class="image_img_div d-flex align-items-center ml-2" data-mediaid="{{$item ? $item->image_id : 0}}">
                  @if($item && $item->image)
                    <img src="{{$item->image->image(50,50)}}" id="image_src" alt="admin image" class="img-responsive">
                    <i class="fa fa-trash text-danger ml-2" onclick="removeImage(this, 'image')"></i>
                  @else
                    <img src="{{no_image()}}" width="100" id="image_src" alt="admin image" class="img-responsive ml-2">
                  @endif
                </div>
              </div></td>
            </tr>
            @endfor
          </tbody>
        </table>
    </div>
    <h3 class="h3">Cards</h3>
    <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Sr.</th>
              <th>Heading</th>
              <th>Text</th>
              <th><a href="https://fontawesome.com/v4/icons/" target="_blank">Icon</a></th>
              <th>Icon Color</th>
              <th>BG Color</th>
            </tr>
          </thead>
          <tbody>
            @for($i=0; $i<4; $i++)
            <tr>
              <td>{{$i++}}</td>
              <td>{!! Form::text('section_three[cards]['.$i.'][title]', old('section_three[cards]['.$i.'][title]'),['class'=>'form-control']) !!}</td>
              <td>{!! Form::text('section_three[cards]['.$i.'][text]', old('section_three[cards]['.$i.'][text]'),['class'=>'form-control']) !!}</td>
              <td>{!! Form::text('section_three[cards]['.$i.'][icon]', old('section_three[cards]['.$i.'][icon]'),['class'=>'form-control']) !!}</td>
              <td>{!! Form::text('section_three[cards]['.$i.'][icon_color]', old('section_three[cards]['.$i.'][icon_color]'),['class'=>'form-control']) !!}</td>
              <td>{!! Form::text('section_three[cards]['.$i.'][bg_color]', old('section_three[cards]['.$i.'][bg_color]'),['class'=>'form-control']) !!}</td>
            </tr>
            @endfor
          </tbody>
        </table>
    </div>

  </div>
  <div id="section_four_tab" class="tab-pane fade">
    <div class="form-group">
        {!! Form::label('section_four[heading]','Heading') !!}
        {!! Form::text('section_four', old('section_four[heading]'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('section_four[sub_heading]','Sub Heading') !!}
        {!! Form::text('section_four', old('section_four[sub_heading]'),['class'=>'form-control', 'required'=>'required']) !!}
    </div>
   </div>
  <div id="section_five_tab" class="tab-pane fade">
    <h3 class="h3">Cards</h3>
    <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Sr.</th>
              <th><a href="https://fontawesome.com/v4/icons/" target="_blank">Icon</a></th>
              <th>Heading</th>
              <th>Text</th>

            </tr>
          </thead>
          <tbody>
            @for($i=0; $i<4; $i++)
            <tr>
              <td>{{$i++}}</td>
              <td>{!! Form::text('section_five[cards]['.$i.'][icon]', old('section_five[cards]['.$i.'][icon]'),['class'=>'form-control']) !!}</td>
              <td>{!! Form::text('section_five[cards]['.$i.'][title]', old('section_five[cards]['.$i.'][title]'),['class'=>'form-control']) !!}</td>
              <td>{!! Form::text('section_five[cards]['.$i.'][text]', old('section_five[cards]['.$i.'][text]'),['class'=>'form-control']) !!}</td>
            </tr>
            @endfor
          </tbody>
        </table>
    </div>
  </div>
  <div id="testimonials_tab" class="tab-pane fade"> testimonials
    testimonials drop down
   </div>
  <div id="video_testimonials_tab" class="tab-pane fade"> video_testimonials
    video testimonials drop down
   </div>
  <div id="faq_tab" class="tab-pane fade">
    faqs
  </div>

  <div id="seo_tab" class="tab-pane fade">
    <div class="form-group">
        {!! Form::label('slug','URL Key') !!}
        {!! Form::text('slug', old('slug'),['class'=>'form-control']) !!}
    </div>
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
  var media_image_type = 'lp';
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
</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script>
  var fetchVideoDetails = function(){
      var video_url = $.trim($('#video_url').val());
      $.get('{{route('admin.'.$nav.'.fetch')}}', {url: video_url}, function(response){
          $('#title').val(response.title);
          $('#thumbnail').val(response.image);
          $('#thumbnail_src').attr('src', response.image);
          $('#description').val(response.description);
          $('#video_id').val(response.video_id);
      });
  }
</script>
@endpush
