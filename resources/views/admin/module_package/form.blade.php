
@extends('layouts.admin')
@section('content')
{!! Form::model($item, ['route' => ['admin.software.package.submit', $data_id], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Module Package Detail</h1>
  <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

      <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
</ul>
<div class="clearfix"></div>
<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-3">
          <div class="form-group">
              {!! Form::label('heading','Heading', ['class'=>'required']) !!}
              {!! Form::text('heading', $item ? $item->heading : '' ,['class'=>'form-control']) !!}
          </div>
      </div>
      <div class="col-md-3">
          <div class="form-group">
              {!! Form::label('title','Price', ['class'=>'required']) !!}
              {!! Form::text('title', $item ? $item->title : '',['class'=>'form-control']) !!}
          </div>
      </div>
      <div class="col-md-3 mb-2">
          <div id="package_service_div">
            {!! Form::label('services','Services', ['class'=>'required']) !!}
            @if($item && count($item->services))
            @foreach($item->services as $key => $service)
              <div class="row">
                <div class="col-10 form-group d-flex">
                  <span >{{$key+1}}. </span> <input type="text" class="form-control mx-2" name="services[]" value="{{$service->title}}"/>
                </div>
                 <div class="form-group col-2 d-flex align-items-center">
                   @if($key)
                     <a href="javascript:void(0)">
                       <i class="fa fa-trash text-danger border border-danger p-2" onclick="removeService(this)"></i>
                    </a>
                   @endif
                </div>
              </div>
            @endforeach
            @else
            <div class="row">
              <div class="col-10 form-group d-flex">
                <span>1. </span> <input type="text" class="form-control mx-2" name="services[]"/>
              </div>
            </div>
            @endif
          </div>
          <div>
            <a href="javascript:void(0)">
              <i class="fa fa-plus text-primary border border-primary p-2" onclick="addService(this)"> Add</i>
           </a>
         </div>
      </div>
      <div class="col-md-3">
         <div class="form-group">
          {!! Form::label('order', 'Order', ['class'=>'required']) !!}
          {!! Form::number('order',old('order'), ['class'=>'form-control', 'min'=>'0']) !!}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="mb-3">
</div>
  {!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script>
  var count;
  var serviceDiv;
  $('doucment').ready(function (){
    serviceDiv = document.getElementById('package_service_div');
    serviceDiv = serviceDiv.children;
    count = serviceDiv.length;
  })
    function addService(elem){
      serviceDiv = document.getElementById('package_service_div');
      count = serviceDiv.children;
      count = count.length;
      let div = document.createElement('div');
      div.className = "row";
      let appendData = `

          <div class="col-10 form-group d-flex">
            <span>${count}. </span> <input type="text" class="form-control mx-2" name="services[]"/>
          </div>
           <div class="form-group col-2 d-flex align-items-center">
             <a href="javascript:void(0)">
               <i class="fa fa-trash text-danger border border-danger p-2" onclick="removeService(this)"></i>
            </a>
          </div>`
      div.innerHTML = appendData;
      serviceDiv.appendChild(div)
      // count++;
    }
    function removeService(elem){
      $(elem).parent().parent().parent().remove();
    }
</script>
{{--
<script type="text/javascript">
  var media_image_type = 'vastu';
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
--}}
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
