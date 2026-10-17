@extends('layouts.admin')

@section('content')
<div class="clearfix"></div>
{!! Form::model($item, ['route' => ['admin.'.$module.'.submit'], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
@if($item)
{{ Form::hidden('id', old('id')) }}
@endif
<div class="row">
  <div class="col-md-6">
    <h1 class="text-primary float-left">{{ $moduleName }} Detail</h1>
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
    <!-- <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li> -->
  </ul>
  <div class="tab-content">
    <div id="information_tab" class="tab-pane fade in active show">
      <div class="row">
        <div class="form-group col-md-4">
          {!! Form::label('name','Name', ['class'=>'required']) !!}
          {!! Form::text('name', old('name'),['class'=>'form-control', 'id'=>'name']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('email','Email', ['class'=>'required']) !!}
          {!! Form::text('email', old('email'), ['class'=>'form-control', 'id'=>'email']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('phone','Phone', ['class'=>'required']) !!}
          {!! Form::number('phone', old('phone'), ['class'=>'form-control', 'id'=>'phone']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('address','Address') !!}
          {!! Form::text('address', old('address'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-4">
          <label>Country</label>
          <select name="country_id" class="form-control nosearch" id="country_id" onchange="fetchLocations('State', this, 'state_id')">
            <option value="0">Select Country</option>
            @foreach($countries as $country)
              <option value="{{ $country->id }}" {{ $item && $item->country_id == $country->id ? "selected" : "" }}>
                {{$country->name}}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-md-4">
          <label>State</label>
          <select name="state_id" class="form-control nosearch" id="state_id" onchange="fetchLocations('City', this, 'city_id')">
            <option value="0">Select State</option>
            @foreach($states as $state)
              <option value="{{ $state->id }}" {{ $item && $item->state_id == $state->id ? "selected" : "" }}>{{$state->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-md-4">
          <label>City</label>
          <select name="city_id" class="form-control nosearch" id="city_id">
            <option value="0">Select City</option>
            @foreach($cities as $city)
              <option value="{{ $city->id }}" {{ $item && $item->city_id == $city->id ? "selected" : "" }}>{{$city->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('pincode','Pincode') !!}
          {!! Form::text('pincode', old('pincode'),['class'=>'form-control']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('business_name','Business Name', ['class'=>'required']) !!}
          {!! Form::text('business_name', old('business_name'), ['class'=>'form-control', 'id'=>'business_name']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('pan_number','PAN Number', ['class'=>'']) !!}
          {!! Form::text('pan_number', old('pan_number'), ['class'=>'form-control', 'id'=>'pan_number']) !!}
        </div>
        <div class="form-group col-md-4">
          {!! Form::label('gst_number','GST Number', ['class'=>'']) !!}
          {!! Form::text('gst_number', old('gst_number'), ['class'=>'form-control', 'id'=>'gst_number']) !!}
        </div>
        <div class="col-6 col-md-2">
          <div class="form-group">
            <span class="ml-2 mt-2"><strong>Status</strong></span>
            <input type="checkbox" class="toggle-check" id="status" {{$item && $item->status ? 'checked' : ''}} value="1" name="status">
            <label class="mx-2 t-lable mt-2" for="status"></label>
          </div>
        </div>
  </div>
</div>
<div id="seo_tab" class="tab-pane fade">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        {!! Form::label('slug', 'Slug', ['class'=>'']) !!}
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
</div>



{!! Form::close() !!}
@include('admin.media.manager')
@endsection

@push('scripts')
@include('admin._partials.editor')
<script src="{{asset('theme-admin/js/form.js')}}"></script>
<script type="text/javascript">
  function fetchLocations(table, that, elementId){
    let url = "{{ route('location') }}";
    let relationTableId = $(that).val();
    url += `/?table=${table}&relation_table_id=${relationTableId}`;
    let data = `<option value="">Select ${table}</option>`;
    $.ajax({
      type: "GET",
      url: url,
      success: function(response){
        for(let item in response){
          data += `<option value="${item}">${response[item]}</option>`
        }
        $('#'+elementId).html('');
        $('#'+elementId).append(data);
      },
      error: function(error){
        $('#'+elementId).html('');
        $('#'+elementId).append(data);
        console.log("fetch states by country:", error);
      }
    });
  }
</script>
@endpush
