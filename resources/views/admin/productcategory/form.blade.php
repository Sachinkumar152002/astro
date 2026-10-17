@extends('layouts.admin')

@section('content')
<div class="clearfix"></div>
{!! Form::model($category, ['route' => ['admin.productcategory.submit',$lang, $productTypeName], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($category)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Category Details</h1>
  </div>
  <div class="col-md-6 text-right">
  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
  </div>
  </div>
</div>
<div class="clearfix"></div>
<ul class="nav nav-tabs">
    <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
    <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#products_tab">Products</a></li>
  </ul>
  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
      <div class="row">
    <div class="form-group col-md-4">
        {!! Form::label('name','Name', ['class'=>'required']) !!}
        {!! Form::text('name', old('name'),['class'=>'form-control', 'id'=>'name']) !!}
    </div>
    @if(false)
      <div class="form-group col-md-3">
        {!! Form::label('parent_id','Parent') !!}
        {!! Form::select('parent_id',$categories,  old('parent_id'),['class'=>'form-control']) !!}
      </div>
    @endif
    <div class="form-group col-md-4">
      <div class="">
        <div class="">
          {{ Form::label('image_id', 'Category Image', ['class' => 'control-label required']) }}
        </div>
        <div class="d-flex align-items-center">
           <button type="button" class="btn float-left" id="select-image" onclick="openMediaManager()">Upload Image</button>
          {{ Form::hidden('image_id', old('image_id')) }}
          {{ Form::hidden('image_src', old('image_src',  $category && $category->image ? $category->image->image(200,200) : ''),['id'=>'image_src']) }}
          @if(old('image_id', $category ? $category->image_id : ''))
          <div class="profile_image float-right ml-2" data-mediaid="{{old('image', $category ? $category->image_id : '')}}">
            <img src="{{old('image_src', $category && $category->image ? $category->image->image(70,70) : '')}}" alt="image" class="img-responsive">
            <i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>
          </div>
          @endif
        </div>
      </div>
    </div>
    <div class="col-md-4">
       <div class="form-group">
        {!! Form::label('c_order', 'Order', ['class'=>'required']) !!}
        {!! Form::number('c_order', $category?$category->order:"", ['class'=>'form-control']) !!}
      </div>
    </div>
    <div class="col-12">
      <div class="form-group">
        {!! Form::label('description', 'Description', ['class'=>'']) !!}
        {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
      </div>
    </div>
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-2 d-none">
          <div class="form-group">
            <span class="ml-2 mt-2"><strong>List in Footer</strong></span>
            <input type="checkbox" class="toggle-check" id="show_in_navigation" {{$category && $category->show_in_navigation ? 'checked' : ''}} value="1" name="show_in_navigation">
            <label class="mx-2 t-lable mt-2" for="show_in_navigation"></label>
          </div>
        </div>
        <div class="col-md-2 d-none">
          <div class="form-group">
            <span class="ml-2 mt-2"><strong>Featured</strong></span>
            <input type="checkbox" class="toggle-check" id="is_featured" {{$category && $category->is_featured ? 'checked' : ''}} value="1" name="is_featured">
            <label class="mx-2 t-lable mt-2" for="is_featured"></label>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <span class="ml-2 mt-2"><strong>Refund and Cancellation</strong></span>
            <input type="checkbox" class="toggle-check" id="refund_and_cancellation" {{$category && $category->refund_and_cancellation ? 'checked' : ''}} value="1" name="refund_and_cancellation" onchange="toggleRefundDaysField()">
            <label class="mx-2 t-lable mt-2" for="refund_and_cancellation"></label>
          </div>
        </div>
        <div class="col-md-3" id="refund_days_field" style="display: {{$category && $category->refund_and_cancellation ? 'block' : 'none'}};">
          <div class="form-group">
            {!! Form::label('refund_cancellation_days', 'Days', ['class'=>'']) !!}
            {!! Form::number('refund_cancellation_days', old('refund_cancellation_days', $category ? $category->refund_cancellation_days : ''), ['class'=>'form-control', 'min'=>'0', 'step'=>'1']) !!}
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <span class="ml-2 mt-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="status" {{$category && $category->status ? 'checked' : ''}} value="1" name="status">
            <label class="mx-2 t-lable mt-2" for="status"></label>
          </div>
        </div>
      </div>
    </div>
  </div>

    </div>
        <div id="seo_tab" class="tab-pane fade">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
              {!! Form::label('slug','Url Key', ['class'=>'']) !!}
              {!! Form::text('slug', old('slug'),['class'=>'form-control']) !!}
            </div>
            </div>
              <div class="col-md-6">
              <div class="form-group">
                  {!! Form::label('meta_title','Meta Title') !!}
                  {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
              </div>
              </div>
              <div class="col-12">
              <div class="form-group">
                  {!! Form::label('meta_keywords','Meta Keywords') !!}
                  {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
              </div>
              </div>
              <div class="col-12">
              <div class="form-group">
                  {!! Form::label('meta_description','Meta Description') !!}
                  {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
              </div>
              </div>
          </div>
      </div>
      <div id="products_tab" class="tab-pane fade">
        <div class="row" id="filter_row">
          @php
            $categoryId=0;
            if($category){
              $categoryId=$category->id;
            }
          @endphp
          <input type="hidden" name="category_id" id="category_id" value="{{ $categoryId }}">
          <div class="col-10">
            <div class="form-group">
              <input type="text" name="product_title" id="product_title" class="form-control" placeholder="🔍 Search Product">
            </div>
          </div>
          <div class="col-1">
            @php
              $filterResultUrl=route("admin.productcategory.product.list",["en",$productTypeName]);
            @endphp
            <button type="button" class="btn btn-success" id="filter-apply-btn" onclick="parentFilterResults('{{ $filterResultUrl }}')">
              <i class="fas fa-search pointer"></i>
            </button>
          </div>
          <div class="col-1">
            <button type="button" class="btn btn-success" id="filter-reset-btn"  onclick="resetFilterResults()">
              <i class="fas fa-redo-alt pointer"></i>
            </button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table" id="list-table" data-url="{{ $filterResultUrl }}">
            <thead>
              <tr class="bg-primary text-white">
                <th>#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Status</th>
                <th>Order</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="9" class="pagination"></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
</div>



{!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="{{asset('theme-admin/js/listtable.js')}}"></script>
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
@if(!$category)
  $('#name').on('keyup', function(){
    var value = $(this).val();
    $('#slug').val(value.trim().replaceAll(' ', '-'));
  });
@endif

    var media_image_type = 'productCategories';
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
      if (imageset === 'featured') {
           $('#image_id').val(mediaId);
           $('#image_src').val(imgSrc);
           $('.profile_image').remove(); // Remove the old image if it exists
           newImg.find('.action').remove();
           newImg.removeClass('img-box-choice selected').addClass('profile_image float-right');
           newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this)"></i>');
           $('#image_id').after(newImg);
       } else if (imageset === 'editor') {
           tinyMCE.activeEditor.execCommand('mceInsertContent', true, '<img src="' + imgSrcOriginal.replace(window.location.origin, '') + '" alt="editor-insert-image"/>');
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

      function toggleRefundDaysField() {
        var isChecked = $('#refund_and_cancellation').is(':checked');
        if(isChecked) {
          $('#refund_days_field').show();
        } else {
          $('#refund_days_field').hide();
          $('#refund_cancellation_days').val('');
        }
      }
      let filterResultUrl="{{ $filterResultUrl }}";
      let categoryId="{{ $categoryId }}";
      let paginationPage=1;
      function parentFilterResults(url){
        paginationPage=1;
        filterResults(url);
      }
      function resetFilterResults(){
        $("#product_title").val("");
        $("#filter-apply-btn").click();
      }
      function handleSelectProduct(that,action,product_id){
        let orderInput=$(that).closest("tr").find("input[name=order]");
        let category_id=categoryId;
        let order=orderInput.val()?orderInput.val():0;
        tblObj.data("url",filterResultUrl+"?page="+paginationPage);
        $.ajax({
           url: "{{ route('admin.productcategory.product.categoryid.update',['en',$productTypeName]) }}",
           type : 'GET',
           data : {action,product_id,category_id,order},
           success:function(response){
             // console.log(response)
             toastr.success(response.message);
             listTable();
           },
           error:function(error){
             // console.log(error)
             toastr.error("Error occured in updating category");
           }
         });
      }
</script>
@endpush
