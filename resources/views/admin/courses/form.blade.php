@extends('layouts.admin')
@section('content')
{!! Form::model($course, ['route' => 'admin.course.submit', 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($course)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">Course Details</h1>
  <div class="clearfix"></div>
  </div>
  <div class="col-md-6 text-right mt-2">
     <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>

      <button type="button" name="button" class="btn btn-primary" href="#"><i class="bi bi-escape text-white"></i></button>
  </div>
</div>
<ul class="nav nav-tabs">
  <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information">Information</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#content_tab">Content</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#specification">Specification</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#google_meet">Meet</a></li>
  <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
</ul>

<div class="tab-content">
  <div id="information" class="tab-pane fade in active show">
    <div class="row">
      <div class="col-md-4 col-12">
        <div class="form-group">
            {!! Form::label('name','Name', ['class'=>'required']) !!}
            {!! Form::text('name', old('name'),['class'=>'form-control', 'id'=>'name']) !!}
        </div>
      </div>
        <div class="col-md-4 col-12">
          <div class="form-group">
              {!! Form::label('short_name','Short Name') !!}
              {!! Form::text('short_name', old('short_name'),['class'=>'form-control', ]) !!}
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="form-group">
            <div class="">
              {{ Form::label('image_id', 'Image', ['class' => 'control-label required']) }}
            </div>
              <div class="">
                 <button type="button" class="btn float-left" id="select-image"  data-toggle="modal" onclick="openMediaManager('image')" >Browse Image</button>
                {{ Form::hidden('image_id', old('image_id'), []) }}
                {{ Form::hidden('image_src', old('image_src',  $course && $course->image ? $course->image->image(200,200) : ''),['id'=>'image_src']) }}
                 @if($course)
                <div class="image_image" data-mediaid="{{old('image', $course ? $course->image_id : '')}}">
                  <img src="{{old('image_src', $course && $course->image ? $course->image->image(100,100) : '')}}" short_name="image" class="img-responsive ml-2">
                  <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image')"></i>
                </div>
                @endif
              </div>
          </div>
        </div>

        <div class="form-group col-md-3">
          {!! Form::label('course_category_id', 'Category', ['class'=>'required']) !!}
          {!! Form::select('course_category_id',$_courseCategory, old('_courseCategory'), ['class'=>'form-control', ]) !!}
        </div>

        <div class="form-group col-md-3">
          {!! Form::label('type', 'Type', ['class'=>'required']) !!}
          {!! Form::select('type', courseTypes(), old('type'), ['class' => 'form-control nosearch', 'placeholder' => 'Select']
          ) !!}
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('purchase_type', 'Purchase Type') !!}
          {!! Form::select('purchase_type', purchaseTypes(), old('purchase_type'), ['class' => 'form-control nosearch', 'placeholder' => 'Select']
          ) !!}
        </div>
        <div class="form-group col-md-3">
            {!! Form::label('instructor_id', 'Instructor', ['class'=>'required']) !!}
            {!! Form::select('instructor_id',
                $instructor->pluck('display_name', 'id')->toArray(), $course ? $course->course_type : null, ['class' => 'form-control', 'placeholder' => 'Select Instructor']) !!}
        </div>



     {{-- <div class="form-group">
        {!! Form::label('certified_by', 'Certified By'); !!}
        {!! Form::select('certified_by',$_certified_by, old('certified_by'), ['class'=>'form-control', ]) !!}
      </div> --}}
      <div class="form-group col-md-3 d-none">
        {!! Form::label('access', 'Access in days') !!}
        {!! Form::number('access',old('access'), ['class'=>'form-control', 'min'=>'0']) !!}
      </div>
      {{--
      <div class="form-group col-md-3">
        {!! Form::label('rating', 'Temp Rating'); !!}
        {!! Form::number('rating',old('rating'), ['class'=>'form-control', 'min'=>'0']) !!}
    </div>
    --}}
      <div class="form-group col-md-3">
        {!! Form::label('price', 'Price', ['class'=>'required']) !!}
        {!! Form::number('price',old('price'), ['class'=>'form-control', 'min'=>'0', ]) !!}
      </div>
      <div class="form-group col-md-3">
        {!! Form::label('special_price', 'Special Price') !!}
        {!! Form::number('special_price',old('special_price'), ['class'=>'form-control', 'min'=>'0']) !!}
      </div>
      <div class="form-group col-md-3">
        {!! Form::label('special_from_date', 'Special Price From Date') !!}
        {!! Form::date('special_from_date',old('special_from_date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
      </div>
      <div class="form-group col-md-3">
        {!! Form::label('special_to_date', 'Special Price To Date') !!}
        {!! Form::date('special_to_date',old('special_to_date'), ['class'=>'form-control', 'max'=>'9999-12-31']) !!}
      </div>
      <div class="form-group col-md-3">
        {!! Form::label('duration', 'Duration (Days)', ['class'=>'required']) !!}
        {!! Form::number('duration',old('duration'), ['class'=>'form-control', 'min'=>'0']) !!}
      </div>
      <div class="col-md-3 form-group">
        {!! Form::label('total_class', 'Total Classes', ['class'=>'required']) !!}
        {!! Form::text('total_class',old('total_class'), ['class'=>'form-control']) !!}
      </div>
      <div class="form-group col-md-3">
       {!! Form::label('order', 'Order') !!}
       {!! Form::number('order',old('order'), ['class'=>'form-control', 'min'=>'0']) !!}
     </div>

      <div class="col-12 d-none" id="time-boxes">
        <div class="row">
          <div class="col-md-3 form-group">
            {!! Form::label('start_date', 'Start Date', ['class'=>'required']) !!}
            {!! Form::date('start_date', old('start_date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
          </div>
          <div class="col-md-3 form-group">
            {!! Form::label('start_time', 'Start Time', ['class'=>'required']) !!}
            {!! Form::time('start_time',old('start_time'), ['class'=>'form-control']) !!}
          </div>
          <div class="col-md-3 form-group">
            {!! Form::label('end_time', 'End Time', ['class'=>'required']) !!}
            {!! Form::time('end_time',old('end_time'), ['class'=>'form-control']) !!}
          </div>
          @php
          $courseDayArray = [];
          if($course && count($course->courseDays)){
            $courseDays = $course->courseDays;
            foreach($courseDays as $key => $item){
              array_push($courseDayArray, $item->day);
            }
          }
          @endphp
          <div class="form-group col-md-3">
            {!! Form::label('day', 'Days', ['class'=>'required']) !!}
            <select class="form-control" multiple name="day[]">
              @foreach(days() as $key => $item)
              <option value="{{$key}}" {{$course && in_array($item, $courseDayArray) ? 'selected' : '' }}>{{$item}}</option>
              @endforeach
            </select>
          </div>
          {{--
            <div class="col-md-3 form-group">
              {!! Form::label('total_month', 'Duration (Months)', ['class'=>'required']) !!}
              {!! Form::text('total_month',old('total_month'), ['class'=>'form-control']) !!}
            </div>
          --}}
        </div>
      </div>

      <div class="form-group col-md-6">
          {!! Form::label('youtube','Youtube Video') !!}
          {!! Form::url('youtube', old('youtube'),['class'=>'form-control']) !!}
      </div>

        <div class="form-group col-md-2 mt-4">
          <span class="ml-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="status" {{$course && $course->status ? 'checked' : ''}} value="1" name="status">
            <label class="mx-2 t-lable" for="status"></label>
        </div>
        <div class="form-group col-md-2 mt-4">
          <span class="ml-2"><strong>Publish</strong> </span>
          <input type="checkbox" class="toggle-check" id="publish" {{$course && $course->publish ? 'checked' : ''}} value="1" name="publish">
          <label class="mx-2 t-lable" for="publish"></label>
        </div>
        <div class="form-group col-md-2 mt-4">
          <span class="ml-2"><strong>Apply GST</strong> </span>
          <input type="checkbox" class="toggle-check" onchange="handleApplyGst(this, 'apply_gst_div')" id="apply_gst" {{$course && $course->apply_gst ? 'checked' : ''}} value="1" name="apply_gst">
          <label class="mx-2 t-lable" for="apply_gst"></label>
        </div>
        <div class="form-group col-4 mt-3 d-none apply_gst_div">
            {!! Form::label('hsn_id', 'HSN Code', ['class' => 'required']) !!}
            <select class="form-control nosearch" name="hsn_id" id="hsn_id">
              <option value="">Select</option>
              @foreach($hsnCodes as $key => $hsn)
                <option value="{{$hsn->id}}" {{$course && $course->hsn_id==$hsn->id?'selected':''}}>{{$hsn->name}} - {{$hsn->hsn_code}} - {{$hsn->gst_percentage}}%</option>
              @endforeach
            </select>
        </div>
        <div class="form-group col-3 mt-4 d-none apply_gst_div">
          <span class="ml-2"><strong>Price Include Gst</strong> </span>
          <input type="checkbox" class="toggle-check" id="price_type" {{$course && $course->price_type ? 'checked' : ''}} value="1" name="price_type">
          <label class="mx-2 t-lable" for="price_type"></label>
        </div>
      </div>
    </div>
    <div id="content_tab" class="tab-pane fade">
      <div class="row">
        @if($course && $course->brochure)
          <div class="col-4">
            <div class="form-group">
              {!! Form::label('brochure_file', 'Uploaded Brochure', ['class'=>'']) !!}
              <input type="text" class="form-control" name="brochure_file" value="{{asset($course->brochure)}}">
            </div>
          </div>
        @endif
        <div class="col-4">
          <div class="form-group">
            {!! Form::label('brochure', 'Broucher', ['class'=>'']) !!}
            <input type="file" class="form-control" name="brochure" accept=".pdf">
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            {!! Form::label('short_description', 'Short Description', ['class'=>'required']) !!}
            {!! Form::textarea('short_description', old('short_description'), ['class'=>'form-control','maxlength'=>"159"]) !!}
          </div>
        </div>
          <div class="col-12">
             <div class="form-group">
              {!! Form::label('description', 'Description', ['class'=>'required']) !!}
              {!! Form::textarea('description', old('description'), ['class'=>'form-control']) !!}
            </div>
          </div>
            <div class="col-12">
               <div class="form-group">
                {!! Form::label('ilearn', 'What You Will Learn?') !!}
                {!! Form::textarea('ilearn', old('ilearn'), ['class'=>'form-control']) !!}
              </div>
            </div>
      </div>
    </div>
    <div id="specification" class="tab-pane fade">
      <div class="d-flex justify-content-between mb-2">
        <div>
          {{--
          <a href="javascript:void(0)"  onclick="addSpecification(this)" class="btn btn-primary float-right">
            Add More</i>
         </a>
         --}}
       </div>
      </div>

      {{--
      <table class="table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Heading</th>
            <th>Text</th>
          </tr>
        </thead>
        <tbody id="specification-div">
          @if($course && count($course->specifications))
            @foreach($course->specifications as $specificationKey => $specificationItem)
            <tr>
              <td>
                <div class="form-group col-md-3">
                    <div class="">
                       <button type="button" class="btn btn-sm"  data-toggle="modal" onclick="openMediaManager('icon_id{{$specificationKey}}')">Upload Image</button>
                       <input type="hidden" id="icon_id{{$specificationKey}}_id" name="icon_id[]" value="{{$specificationItem->icon_id}}">
                       @if($course && $specificationItem->image)
                       <input type="hidden" id="icon_id{{$specificationKey}}_src" name="icon_src[]" value="{{$specificationItem->image->image(200,200)}}">
                      <div class="icon_id{{$specificationKey}}_image">
                        <img src="{{$specificationItem->image->image(100,100)}}" short_name="icon" class="img-responsive">
                        <i class="fa fa-trash text-danger" onclick="removeImage(this, 'icon_id{{$specificationKey}}')"></i>
                      </div>
                      @endif
                    </div>
                </div>
              </td>
              <td>
                 <div class="form-group">
                   <input type="text" class="form-control" name="heading[]" value="{{$specificationItem->heading}}">
                </div>
              </td>
              <td>
                 <div class="form-group">
                   <input type="text" class="form-control" name="text[]" value="{{$specificationItem->text}}">
                </div>
              </td>
            </tr>
            @if($specificationKey)
            <td style="vertical-align:middle">
             <div class="">
               <a href="javascript:void(0)"  onclick="removeSpecification(this)">
                 <i class="fa fa-trash text-danger"></i>
              </a>
            </div>
            </td>
            @else
            <td></td>
            @endif
            @endforeach
          @else
          <tr>
            <td>
              <div class="form-group">
                  <div class="">
                     <button type="button" class="btn btn-sm" data-toggle="modal" onclick="openMediaManager('icon_id0')">Upload Image</button>
                     <input type="hidden" id="icon_id0_id" name="icon_id[]">
                     <input type="hidden" id="icon_id0_src" name="icon_src[]">
                  </div>
              </div>
            </td>
            <td>
             <div class="form-group">
               <input type="text" class="form-control" name="heading[]">
            </div>
            </td>
            <td>
             <div class="form-group">
               <input type="text" class="form-control" name="text[]">
            </div>
            </td>
            <td></td>
          </tr>
          @endif
        </tbody>
      </table>
      --}}


      <table class="table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Heading</th>
            <th>Text</th>
          </tr>
        </thead>
        <tbody id="specification-div">
          @php
          $i = 0;
          @endphp
          @if($course && count($course->specifications))
            @foreach(courseSpecifications() as $key => $specification)
            <tr>
              <td>
                <div class="form-group col-md-4">
                  <input type="hidden" name="icon_id[]" value="{{$key}}">
                  <div class="">
                    <img src="{{asset($key)}}" short_name="icon" class="img-responsive">
                  </div>
                </div>
              </td>
              <td>
                 <div class="form-group">
                   <input type="text" class="form-control" name="heading[]" value="{{$specification}}">
                </div>
              </td>
              <td>
                 <div class="form-group">
                   <input type="text" class="form-control" name="text[]" value="{{$course->specifications[$i]->text}}">
                </div>
              </td>
            </tr>
            @php
            $i++;
            @endphp
          @endforeach
          @else
            @foreach(courseSpecifications() as $key => $specification)
              <tr>
                <td>
                  <div class="form-group col-md-4">
                      <div class="">
                         <input type="hidden" name="icon_id[]" value="{{$key}}">
                        <div class="">
                          <img src="{{asset($key)}}" short_name="icon_id" class="img-responsive">
                        </div>
                      </div>
                  </div>
                </td>
                <td>
                   <div class="form-group">
                     <input type="text" class="form-control" name="heading[]" value="{{$specification}}">
                  </div>
                </td>
                <td>
                   <div class="form-group">
                     <input type="text" class="form-control" name="text[]">
                  </div>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
  </div>
  <div id="google_meet" class="tab-pane fade">
    <div class="row">
      <div class="col-md-4 col-12">
        <div class="form-group">
          {!! Form::label('meet_date', 'Date') !!}
          {!! Form::date('meet_date', old('meet_date'), ['class'=>'form-control', 'max'=> '9999-12-31']) !!}
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="form-group">
          {!! Form::label('meet_start_time', 'Start Time') !!}
          {!! Form::time('meet_start_time',old('meet_start_time'), ['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="form-group">
          {!! Form::label('meet_end_time', 'End Time') !!}
          {!! Form::time('meet_end_time',old('meet_end_time'), ['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="form-group">
            {!! Form::label('meet_link','Link') !!}
            {!! Form::text('meet_link', old('meet_link'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="form-group">
            {!! Form::label('meet_platform','Platform') !!}
            {!! Form::text('meet_platform', old('meet_platform'),['class'=>'form-control']) !!}
        </div>
      </div>
      <div class="col-md-4 col-12">
        <div class="form-group">
            {!! Form::label('meet_type','Type') !!}
            {!! Form::select('meet_type',['temporary'=>'Temporary', 'permanent'=>'Permanent'], old('meet_type'), ['class'=>'form-control', ]) !!}
        </div>
      </div>
      <div class="form-group col-md-4 col-12">
          <span class="ml-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="meet_status" {{$course && $course->meet_status ? 'checked' : ''}} value="1" name="meet_status">
            <label class="mx-2 t-lable" for="meet_status"></label>
      </div>
    </div>
  </div>
  <div id="seo_tab" class="tab-pane fade">
    <div class="row">
      <div class="col-md-6 col-12">
        <div class="form-group">
          {!! Form::label('slug', 'Url Key') !!}
          {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'id'=>'slug']) !!}
        </div>
      </div>
      <div class="col-md-6 col-12">
         <div class="form-group">
            {!! Form::label('meta_title','Meta Title') !!}
            {!! Form::text('meta_title', old('meta_title'),['class'=>'form-control']) !!}
        </div>
      </div>
        <div class="col-md-12 col-12">
          <div class="form-group">
              {!! Form::label('meta_keywords','Meta Keywords') !!}
              {!! Form::text('meta_keywords', old('meta_keywords'),['class'=>'form-control']) !!}
          </div>
        </div>
          <div class="col-md-12 col-12">
            <div class="form-group">
                {!! Form::label('meta_description','Meta Description') !!}
                {!! Form::textarea('meta_description', old('meta_description'),['class'=>'form-control mceNoEditor']) !!}
            </div>
          </div>
    </div>
  </div>


  {!! Form::close() !!}

@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script>
  @if(!$course)
    $('#name').on('keyup', function(){
      var value = $(this).val();
      $('#slug').val(value.trim().replaceAll(' ', '-'));
    });
  @endif
var count;
var specificationDiv;
$('doucment').ready(function (){
  specificationDiv = document.getElementById('specification-div');
  specificationDiv = specificationDiv.children;
  count = specificationDiv.length;
  let courseType = '{{$course ? $course->type : 0}}';
  if(courseType == 1){
    $('#time-boxes').removeClass('d-none');
  }
  handleApplyGst($('#apply_gst'), 'apply_gst_div');
})

function handleApplyGst(that, clss){
  $('.'+clss).addClass('d-none');
  if($(that).prop('checked')){
    $('.'+clss).removeClass('d-none');
  }
}

  function addSpecification(elem){
    let div = document.createElement('tr');
    let appendData = `
    <td>
                        <div class="form-group">
                            <div class="">
                               <button type="button" class="btn btn-sm" data-toggle="modal" onclick="openMediaManager('icon_id${count}')">Upload Image</button>
                               <input type="hidden" id="icon_id${count}_id" name="icon_id[]">
                               <input type="hidden" id="icon_id${count}_src" name="icon_src[]">
                            </div>
                        </div>
                        </td>
                        <td>
                         <div class="form-group">
                           <label>Heading</label>
                           <input type="text" class="form-control" name="heading[]">
                        </div>
                        </td>
                        <td>
                         <div class="form-group">
                           <label>Text</label>
                           <input type="text" class="form-control" name="text[]">
                        </div>
                        </td>
                        <td style="vertical-align:middle">
                         <div class="">
                           <a href="javascript:void(0)"  onclick="removeSpecification(this)">
                             <i class="fa fa-trash text-danger"></i>
                          </a>
                      </div>
                      </td>`
    div.innerHTML = appendData;
    specificationDiv = document.getElementById('specification-div');
    specificationDiv.appendChild(div)
    console.log(div)
    count++;
  }
  function removeSpecification(elem){
    $(elem).parent().parent().parent().remove();
  }
</script>
<script type="text/javascript">
  var media_image_type = 'course';
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
    newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \'image_id\')"></i>');
    $('#image_id').after(newImg)
  }
  else if(imageset=='editor') {
    tinyMCE.activeEditor.execCommand( 'mceInsertContent', true , '<img src="'+imgSrcOriginal.replace(window.location.origin,'')+'" alt="editor-insert-image"/>' );
    imageset = 'featured';
  }else{
    $('#'+imageset+'_id').val(mediaId);
    $('#'+imageset+'_src').val(imgSrc);
    $('.'+imageset+'_image').remove();
    newImg.find('.action').remove();
    newImg.removeClass('img-box-choice selected').addClass(imageset+'_image float-right');
    newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
    $('#'+imageset+'_id').after(newImg)
  }
  $('#media-img-manager').modal('hide');
});
 var removeImage = function(elm, image){
      $(elm).parents('.'+image+'_image').remove();
      $('#'+image+'_id').val('');
      $('#'+image+'_src').val('');
   }
var button = $('input[type="submit"]');
var validateAndSubmit = function(form){
  tinyMCE.triggerSave();
  var _f = $(form);
  let formData = new FormData(form);

  button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
  _f.find('.btn-outline-danger').removeClass('btn-outline-danger');
  $.ajax({
    method: "POST",
    url: _f.attr('action'),
    data: formData,
    processData: false,
    cache : false,
    contentType: false,
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
    if(errors.responseJSON.errors){
      var err = errors.responseJSON.errors;
      for(i in err){
          $('#'+i).addClass('btn-outline-danger');
          toastr.error(err[i][0]);
      }
    }else if(errors.responseJSON.message){
      toastr.error(errors.responseJSON.message);
    }
      console.log(errors)
      button.removeClass('disabled').removeAttr('disabled').val('Submit');
  });

  return false;
}

var openMediaManager = function(image = null) {
  imageset = image ? image : 'featured';
  $('#media-img-manager').modal('show');
}
   $('#type').on('change', function(){
     if(this.value == 1) {
       $('#time-boxes').removeClass('d-none');
     } else {
       $('#time-boxes').addClass('d-none');
     }
   })

   $(function(){
       $('select').not('.nosearch').selectpicker({
           liveSearch: true
       });
   });
</script>

@endpush
