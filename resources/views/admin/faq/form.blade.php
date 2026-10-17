@extends('layouts.admin')
@section('content')
{!! Form::model($templateFaq, ['route' => ['admin.faq.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($templateFaq)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">FAQ Template</h1>
  </div>
  <div class="col-md-6 text-right mt-2">
    <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
</ul>
  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
  <div class="row">
    <div class="col-md-12">
      <input type="hidden" name="overwrite" value="default">
      {{--
      <div class="form-group">
        {!! Form::label('overwrite', 'Overwrite') !!}
        {!! Form::select('overwrite[]',pageTemplates(), old('overwrite', $templateFaq ? json_decode($templateFaq->overwrite) : []), ['class'=>'form-control', 'multiple']) !!}
      </div>
    --}}
    <div class="row">
      <div class="col-12">
        <div class="form-group">
        {!! Form::label('question','Question',['class' => 'control-label required']) !!}
        {!! Form::text('question', old('question'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-12">
        <div class="form-group">
            {!! Form::label('answer','Answer',['class' => 'control-label required']) !!}
            {!! Form::textarea('answer', old('answer'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-3 col-12">
        <div class="form-group mb-3">
          <label for="type" class="control-label required">Page</label>
          <select class="form-control nosearch" name="type[]" id="typeSelect" onchange="handlePage(this)">
            @foreach(faqTypes() as $key => $f)
            <option value="{{$key}}" {{ $templateFaq && $templateFaq->pages->first()->page == $key ? "selected" : "" }}>{{$f}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group col-md-3 col-12 d-none mb-3" id="category_div">
        <label for="select-category" class="control-label required">Category</label>
        <select class="form-control nosearch" name="category_id" id="categorySelect">
            <option value="">Select</option>
        </select>
      </div>
      <div class="col-md-3 col-12">
        <div class="form-group">
          {!! Form::label('order','Order',['class' => 'control-label']) !!}
          {!! Form::number('order', old('order'),['class'=>'form-control', 'placeholder'=>'Example: 1']) !!}
        </div>
      </div>
      <div class="col-md-3 col-12">
        <div class="form-group">
          <span class="ml-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="status" {{$templateFaq && $templateFaq->status ? 'checked' : ''}} value="1" name="status">
            <label class="mx-2 t-lable mt-3" for="status"></label>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
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

<script>
    $(document).ready(function(){
        @if($templateFaq)
          handlePage("#typeSelect");
        @endif
    });

    function handlePage(that){
      let value = $(that).val();
      if(value > 0){
        populateCategories(value);
        $('#category_div').removeClass('d-none');
      }else{
        $('#category_div').addClass('d-none');
      }
    }

    function populateCategories(type) {
        const typeSelect = $('#typeSelect');
        const categorySelect = $('#categorySelect');
        categorySelect.empty().append('<option value="">Select Category</option>');
        if (type!='') {
            $.ajax({
                url: "{{route('admin.faq.categories')}}",
                type: 'GET',
                data: { type: type },
                success: function(data) {
                  data.forEach(function(category) {
                    categorySelect.append(`<option value="${category.id}" ${category.id == '{{$templateFaq ? $templateFaq->category_id : '' }}' ? 'selected' : ''}>${category.name}</option>`);
                  });
                  // $('#categorySelect').selectpicker('refresh');
                },
                error: function() {
                    alert('Error fetching categories');
                }
            });
        }
    }
</script>
@endpush
