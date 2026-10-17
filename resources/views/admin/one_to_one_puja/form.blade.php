@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
  /* Astrologer select2 layout fix */
  .select2-container {
      width: 100% !important;
  }
  .select2-container--default .select2-selection--multiple {
      min-height: 38px;
      padding: 4px 6px;
      border: 1px solid #ced4da;
      border-radius: .25rem;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__rendered {
      display: flex;
      flex-wrap: wrap;
      gap: 4px;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
      margin-top: 2px;
      margin-bottom: 2px;
  }
</style>
@endpush

@section('content')
<h1 class="text-primary float-left">{{$moduleName}} Detail</h1>
<div class="clearfix"></div>
{!! Form::model($product, ['route' => ['admin.'.$nav.'.submit'], 'method'=>'post', 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($product && $product->id)
{{ Form::hidden('id', old('id', $product->id)) }}
@endif
<div class="clearfix"></div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#astrologer_tab">Astrologer</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#gallery_tab">Gallery</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
</ul>
<div class="tab-content">
  <div id="information_tab" class="tab-pane fade in active show">
    {{-- Row 1: Puja Name, Price, Puja Category --}}
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('title', 'Puja Name', ['class' => 'required']) !!}
          {!! Form::text('title', old('title', $product->title ?? ''), ['class' => 'form-control', 'required' => 'required', 'id' => 'title']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('base_price', 'Price', ['class' => 'required']) !!}
          {!! Form::number('base_price', old('base_price', $product->base_price ?? ''), ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'required' => 'required']) !!}
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('category_id', 'Puja Category', ['class' => 'required']) !!}
          <select class="form-control select2" name="category_id[]" id="category_id" multiple="multiple" required>
            @foreach($categories as $categoryId => $categoryName)
              <option value="{{$categoryId}}" {{in_array($categoryId, $selectedCategories) ? 'selected' : ''}}>
                {{$categoryName}}
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    {{-- Row 2: Commission Type, Commission Value, Status --}}
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('commission_type', 'Commission Type', ['class' => 'required']) !!}
          @php
            $commissionType = old('commission_type', $product->commission_type ?? 'percentage');
            $commissionValue = old('commission_value', $product->astrologer_commission_percentage ?? '');
          @endphp
          <select class="form-control" name="commission_type" id="commission_type" onchange="toggleCommissionField(true)" required>
            <option value="percentage" {{ $commissionType == 'percentage' ? 'selected' : '' }}>Percentage</option>
            <option value="fixed" {{ $commissionType == 'fixed' ? 'selected' : '' }}>Fixed</option>
          </select>
        </div>
      </div>
      <div class="col-md-4" id="commission_value_field">
        <div class="form-group">
          {!! Form::label('commission_value', 'Commission Value', ['id' => 'commission_label', 'class' => 'required']) !!}
          {!! Form::number('commission_value', $commissionValue, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'id' => 'commission_value', 'required' => true]) !!}
        </div>
      </div>
      {{ Form::hidden('astrologer_commission_percentage', $commissionValue, ['id' => 'astrologer_commission_percentage']) }}
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('status', 'Status') !!}
          {!! Form::select('status', statusArray(), old('status', $product->status ?? 1), ['class' => 'form-control']) !!}
        </div>
      </div>
      {{-- Hidden field: is_one_to_one_puja is always 1 for one-to-one puja --}}
      {!! Form::hidden('is_one_to_one_puja', 1) !!}
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('video_call_url', 'Video Call URL') !!}
          {!! Form::url('video_call_url', old('video_call_url', $product->video_call_url ?? ''), ['class' => 'form-control', 'placeholder' => 'https://...']) !!}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('short_description', 'Short Description') !!}
          {!! Form::textarea('short_description', old('short_description', $productSpec && $productSpec->short_description ? $productSpec->short_description : ''), ['class' => 'form-control', 'rows' => 3]) !!}
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          {!! Form::label('description', 'Description', ['class' => 'required']) !!}
          {!! Form::textarea('description', old('description', $productSpec && $productSpec->description ? $productSpec->description : ''), ['class' => 'form-control', 'id' => 'editor', 'required' => 'required']) !!}
        </div>
      </div>
    </div>
  </div>

  @php
    $astrologerData = [];
    foreach($astrologers as $astrologer) {
      $label = trim($astrologer->firstname . ' ' . $astrologer->lastname);
      $astrologerData[] = [
        'id' => $astrologer->id,
        'label' => $label,
      ];
    }
  @endphp
  <div id="astrologer_tab" class="tab-pane fade">
    <div class="row">
      {{-- Left column: Search astrologers and add --}}
      <div class="col-md-6">
        <div class="form-group">
          {!! Form::label('astrologer_search', 'Search Astrologer') !!}
          <input type="text" id="astrologer_search" class="form-control" placeholder="Search pandit astrologers by name">
        </div>
        <div id="astrologer-search-results" class="border p-3" style="min-height: 200px; max-height: 400px; overflow-y: auto;">
          {{-- JS will render all astrologers here, then filter on search --}}
        </div>
      </div>
      {{-- Right column: Selected astrologers --}}
      <div class="col-md-6">
        <div class="form-group">
          {!! Form::label('selected_astrologers', 'Selected Astrologers') !!}
          <div id="selected-astrologers-list" class="border p-3" style="min-height: 200px; max-height: 400px; overflow-y: auto;">
            @if(count($selectedAstrologers) > 0)
              @foreach($astrologers as $astrologer)
                @if(in_array($astrologer->id, $selectedAstrologers))
                  @php
                    $label = trim($astrologer->firstname . ' ' . $astrologer->lastname);
                  @endphp
                  <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom astrologer-item" data-id="{{$astrologer->id}}">
                    <span>{{ $label }}</span>
                    <div class="btn-group btn-group-sm">
                      <a href="{{ url('astrovyraadmin/Astrologer/'.$astrologer->id.'/edit') }}" target="_blank" class="btn btn-link p-0" title="View Astrologer">
                        <i class="bi bi-eye text-primary"></i>
                      </a>
                      <button type="button" class="btn btn-link p-0 ml-2 text-danger astrologer-remove-btn" title="Remove astrologer">
                        <i class="bi bi-x-circle"></i>
                      </button>
                    </div>
                  </div>
                @endif
              @endforeach
            @else
              <p class="text-muted mb-0"><small>No astrologers selected yet.</small></p>
            @endif
          </div>
        </div>
        {{-- Hidden select to keep backend validation/submit logic --}}
        <select name="astrologer_id[]" id="astrologer_id" multiple="multiple" class="d-none">
          @foreach($astrologers as $astrologer)
            <option value="{{$astrologer->id}}" {{in_array($astrologer->id, $selectedAstrologers) ? 'selected' : ''}}>
              {{$astrologer->firstname}} {{$astrologer->lastname}}
            </option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  
  <div id="gallery_tab" class="tab-pane fade">
    <div class="form-group">
      {{ Form::label('image_id', 'Main Image', ['class' => 'required']) }}
      <div class="col-sm-12">
        <button type="button" class="btn" id="select-image" onclick="openMediaManager('featured')">Upload Image</button>
        <div class="clearfix"></div>
        <hr>
        {{ Form::hidden('image_id[]', old('image_id', $product && $product->image_id ? $product->image_id : ''), ['required' => 'required', 'id' => 'image_id', 'value' => old('image_id', $product && $product->image_id ? $product->image_id : '')]) }}
        {{ Form::hidden('image_src', old('image_src', $product && $product->image ? $product->image->image(400,400) : ''), ['id' => 'image_src']) }}
        @if(old('image_id', $product && $product->image_id ? $product->image_id : ''))
        <div class="profile_image" data-mediaid="{{old('image_id', $product && $product->image_id ? $product->image_id : '')}}">
          <img src="{{old('image_src', $product && $product->image ? $product->image->image(400,400) : '')}}" alt="image" class="img-responsive">
          <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
        </div>
        @endif
      </div>
    </div>
    <div class="d-flex justify-content-between mb-2">
      <div>
        <a href="javascript:void(0)" onclick="addGallery(this)" class="btn btn-primary float-right">
          <i class="bi bi-plus text-white fs-5"></i> Add Image
        </a>
      </div>
    </div>
    <div class="row" id="image-gallery">
      @if($galleries && $galleries->count() > 0)
        @foreach($galleries as $g => $gallery)
          <div class="form-group col-4">
            <div class="d-flex align-items-center">
              <button type="button" class="btn btn-sm mx-2" data-toggle="modal" onclick="openMediaManager('gallery_image_id{{$g}}')">Upload Image</button>
              <input type="hidden" id="gallery_image_id{{$g}}_id" name="image_id[]" value="{{$gallery->image_id}}">
              <input type="hidden" id="gallery_image_id{{$g}}_src" name="image_src[]" value="{{$gallery->image ? $gallery->image->image(200,200) : ''}}">
              <div class="gallery_image_id{{$g}}_image float-right">
                <img src="{{$gallery->image ? $gallery->image->image(100,100) : ''}}" alt="gallery" class="img-responsive">
                <i class="fa fa-trash text-danger" onclick="removeGalleryImage(this, 'gallery_image_id{{$g}}')"></i>
              </div>
            </div>
          </div>
          <div class="form-group col-8">
            <input type="text" name="alt_text[]" value="{{$gallery->alt_text}}" class="form-control" id="gallery_alt_text{{$g}}_id" placeholder="Alt Text">
          </div>
        @endforeach
      @endif
    </div>
  </div>

  <div id="seo_tab" class="tab-pane fade">
    <div class="form-group">
      {!! Form::label('slug', 'Slug') !!}
      {!! Form::text('slug', old('slug', $product->slug ?? ''), ['class' => 'form-control', 'id' => 'slug']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('meta_title', 'Meta Title') !!}
      {!! Form::text('meta_title', old('meta_title', $product && $product->meta_title ? $product->meta_title : ''), ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('meta_keywords', 'Meta Keywords') !!}
      {!! Form::text('meta_keywords', old('meta_keywords', $product && $product->meta_keywords ? $product->meta_keywords : ''), ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('meta_description', 'Meta Description') !!}
      {!! Form::textarea('meta_description', old('meta_description', $product && $product->meta_description ? $product->meta_description : ''), ['class' => 'form-control mceNoEditor']) !!}
    </div>
  </div>
</div>
{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.select2').select2({
      allowClear: true,
      width: '100%'
    });

    // Init astrologer assign UI
    initAstrologerAssignment();
  });
  
  function initAstrologerAssignment() {
    var allAstrologers = @json($astrologerData);
    var selectedAstrologerIds = @json($selectedAstrologers);
    var baseUrl = '{{ url("astrovyraadmin/Astrologer") }}';

    function renderSelectedAstrologers() {
      var container = $('#selected-astrologers-list');
      if(selectedAstrologerIds.length === 0) {
        container.html('<p class="text-muted mb-0"><small>No astrologers selected yet.</small></p>');
      } else {
        var html = '';
        selectedAstrologerIds.forEach(function(id) {
          var astro = allAstrologers.find(function(a) { return a.id == id; });
          if(!astro) return;
          var editUrl = baseUrl + '/' + astro.id + '/edit';
          html += '<div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom astrologer-item" data-id="'+astro.id+'">';
          html +=   '<span>'+astro.label+'</span>';
          html +=   '<div class="btn-group btn-group-sm">';
          html +=     '<a href="'+editUrl+'" target="_blank" class="btn btn-link p-0" title="View Astrologer"><i class="bi bi-eye text-primary"></i></a>';
          html +=     '<button type="button" class="btn btn-link p-0 ml-2 text-danger astrologer-remove-btn" title="Remove astrologer"><i class="bi bi-x-circle"></i></button>';
          html +=   '</div>';
          html += '</div>';
        });
        container.html(html);
      }

      // Sync hidden select for backend submit
      $('#astrologer_id').val(selectedAstrologerIds.map(String));
    }

    function renderAstrologerSearchResults(query) {
      var container = $('#astrologer-search-results');
      query = query.trim().toLowerCase();
      var filtered = allAstrologers.filter(function(a) {
        if(!query) return true;
        return a.label.toLowerCase().indexOf(query) !== -1;
      });

      if(filtered.length === 0) {
        container.html('<p class="text-muted mb-0"><small>No astrologers found.</small></p>');
        return;
      }

      var html = '';
      filtered.forEach(function(astro) {
        var isSelected = selectedAstrologerIds.indexOf(astro.id) !== -1;
        html += '<div class="d-flex justify-content-between align-items-center mb-1 py-1 border-bottom astrologer-search-item" data-id="'+astro.id+'" style="cursor:pointer;">';
        html +=   '<span>'+astro.label+'</span>';
        html +=   '<span class="badge badge-'+(isSelected ? 'success' : 'primary')+'">'+(isSelected ? 'Added' : 'Add')+'</span>';
        html += '</div>';
      });
      container.html(html);
    }

    $('#astrologer_search').on('keyup', function() {
      renderAstrologerSearchResults($(this).val());
    });

    $('#astrologer-search-results').on('click', '.astrologer-search-item', function() {
      var id = parseInt($(this).data('id'));
      if(selectedAstrologerIds.indexOf(id) === -1) {
        selectedAstrologerIds.push(id);
        renderSelectedAstrologers();
        renderAstrologerSearchResults($('#astrologer_search').val());
      }
    });

    $('#selected-astrologers-list').on('click', '.astrologer-remove-btn', function(e) {
      e.preventDefault();
      var parent = $(this).closest('.astrologer-item');
      var id = parseInt(parent.data('id'));
      selectedAstrologerIds = selectedAstrologerIds.filter(function(aid) { return aid !== id; });
      renderSelectedAstrologers();
      renderAstrologerSearchResults($('#astrologer_search').val());
    });

    // Initial render: all astrologers in search & current selected list
    renderSelectedAstrologers();
    renderAstrologerSearchResults('');
  }

  @if(!$product || !$product->id)
    $('#title').on('keyup', function(){
      var value = $(this).val();
      $('#slug').val(value.trim().replaceAll(' ', '-').toLowerCase());
    });
  @endif

  function toggleCommissionField(clearValue) {
    var commissionType = $('#commission_type').val();
    var label = $('#commission_label');
    var valueField = $('#commission_value');
    
    if(commissionType === 'percentage') {
      label.text('Commission Value (%)');
      valueField.attr('max', '100');
      valueField.attr('placeholder', 'Enter percentage (0-100)');
    } else if(commissionType === 'fixed') {
      label.text('Commission Value (Fixed Amount)');
      valueField.removeAttr('max');
      valueField.attr('placeholder', 'Enter fixed amount');
    }
    
    // Clear value only when explicitly requested (on type change, not on initial load)
    if(clearValue) {
      valueField.val('');
      // Update hidden field
      $('#astrologer_commission_percentage').val('');
    }
  }

  // Update hidden field when commission value changes
  $('#commission_value').on('keyup change', function(){
    $('#astrologer_commission_percentage').val($(this).val());
  });

  // Initialize on page load (set labels/placeholders but keep existing value)
  $(document).ready(function() {
    toggleCommissionField(false);
  });

  var media_image_type = 'product';
  var imageset = 'featured';
  var galleryCount = {{$galleries ? $galleries->count() : 0}};

  $('#insert-image').on('click', function(){
    if($('.img-box-choice.selected').length==0) {
      toastr.warning('Please select an image');
      return false;
    }
    var selectedImg = $('.img-box-choice.selected');
    var mediaId = selectedImg.data('mediaid');
    var imgSrc = $('.img-box-choice.selected img').attr('src');
    var imgSrcOriginal = $('.img-box-choice.selected img').data('original');
    var newImg = selectedImg.clone();
    
    if(imageset === 'featured'){
      $('#image_id').val(mediaId);
      $('#image_src').val(imgSrc);
      $('.profile_image').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
      $('#image_id').after(newImg);
    } else if(imageset.startsWith('gallery_image_id')) {
      var imageId = imageset.replace('gallery_image_id', '');
      $('#' + imageset + '_id').val(mediaId);
      $('#' + imageset + '_src').val(imgSrc);
      $('.' + imageset + '_image').remove();
      newImg.find('.action').remove();
      newImg.removeClass('img-box-choice selected').addClass(imageset + '_image float-right');
      newImg.append('<i class="fa fa-trash text-danger" onclick="removeGalleryImage(this, \'' + imageset + '\')"></i>');
      $('#' + imageset + '_id').after(newImg);
    }
    $('#media-img-manager').modal('hide');
  });

  var removeImage = function(elm){
    $(elm).parents('.profile_image').remove();
    $('#image_id').val('');
    $('#image_src').val('');
  }

  var removeGalleryImage = function(elm, imageId){
    $(elm).parents('.' + imageId + '_image').parent().parent().remove();
    $('#' + imageId + '_id').val('');
    $('#' + imageId + '_src').val('');
  }

  $("#media-img-manager").on('hidden.bs.modal', function (e) {
    imageset = 'featured';
  });

  var openMediaManager = function(option) {
    imageset = option;
    $('#media-img-manager').modal('show');
  }

  function addGallery() {
    let imageDiv = document.getElementById('image-gallery');
    
    let newSection = document.createElement('div');
    newSection.classList.add("form-group", "col-4");
    newSection.innerHTML = `
      <div class="d-flex align-items-center">
        <button type="button" class="btn btn-sm mx-2" data-toggle="modal" onclick="openMediaManager('gallery_image_id${galleryCount}')">Upload Image</button>
        <input type="hidden" id="gallery_image_id${galleryCount}_id" name="image_id[]">
        <input type="hidden" id="gallery_image_id${galleryCount}_src" name="image_src[]">
      </div>
    `;
    imageDiv.appendChild(newSection);

    newSection = document.createElement('div');
    newSection.classList.add("form-group", "col-8");
    newSection.innerHTML = `
      <input type="text" name="alt_text[]" class="form-control" id="gallery_alt_text${galleryCount}_id" placeholder="Alt Text">
    `;
    imageDiv.appendChild(newSection);
    galleryCount++;
  }
</script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
@endpush
