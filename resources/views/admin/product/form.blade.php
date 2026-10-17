@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .valid-till-calendar-box { border: 1px solid #ddd; border-radius: 6px; padding: 12px; margin-top: 8px; background: #fff; max-width: 320px; }
  .valid-till-calendar-box table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .valid-till-calendar-box th { padding: 6px 4px; text-align: center; font-weight: 600; color: #333; }
  .valid-till-calendar-box td { padding: 4px; text-align: center; }
  .valid-till-calendar-box .day-cell { cursor: pointer; border-radius: 4px; min-width: 32px; height: 28px; line-height: 28px; }
  .valid-till-calendar-box .day-cell.other-month { color: #bbb; cursor: default; }
  .valid-till-calendar-box .day-cell.current-month:hover { background: #e9ecef; }
  .valid-till-calendar-box .day-cell.selected { background: #0d6efd; color: #fff; }
</style>
@endpush

@section('content')
    <div class="clearfix"></div>
    @if($p_id)
    {!! Form::model($product , ['route' => ['admin.product.variation.submit', $p_id, $productTypeName], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
    @else
    {!! Form::model($product , ['route' => ['admin.product.submit', $productTypeName], 'method'=>'post' , 'onsubmit'=>"return validateAndSubmit(this)"]) !!}
    @endif
    @if($product)
      <input type="hidden" name="id" value="{{$product->id}}">
    @endif
    @if($p_id)
      <input type="hidden" name="p_id" value="{{$p_id}}">
    @endif
    <input type="hidden" name="type" value="{{$productTypeValue}}">
    <div class="row">
      <div class="col-md-6">
        <h1 class="text-primary float-left">{{ $moduleName }} Details</h1>
      </div>
      <div class="col-md-6 text-right mt-2">
        @if($product && $product->type == 2)
          <button type="button" name="button" value="submit" class="btn btn-primary" onclick="exportCsv(this, 'all', '{{ $product->id }}')">Export (all)</button>
          <button type="button" name="button" value="submit" class="btn btn-primary" onclick="exportCsv(this, 'current', '{{ $product->id }}')">Export (current)</button>
        @endif
        <button type="submit" name="button" value="submit" class="btn btn-primary"><i class="bi bi-floppy"></i></button>
      </div>
    </div>
    <div class="clearfix"></div>
    <ul class="nav nav-tabs">
        <li class="active nav-item"><a data-toggle="tab" class="active nav-link" href="#information_tab">Information</a></li>
        @if(!$p_id)
        <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#image_tab">Gallery</a></li>
        @endif
        @if(!$p_id)
        <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#description_tab">Description</a></li>
        <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#features_tab">Features</a></li>
        <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#specification_tab">Specification</a></li>
        @endif
        @if($productTypeValue != 3)
          <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#variation_tab">Attributes</a></li>
        @endif
        @if($p_id == 0)
          <li class="nav-item"><a data-toggle="tab" class="nav-link" href="#seo_tab">SEO</a></li>
        @endif
    </ul>
    <div class="tab-content">
        <div id="information_tab" class="tab-pane fade in active show">
            <div class="row">
                <div class="col-md-{{ $productTypeValue == 2 && !$p_id ? '6' : '4' }} px-1">
                    <div class="form-group">
                        {!! Form::label('title', 'Title', ['class' => 'required']) !!}
                        @if($p_id)
                        {!! Form::text('title', old('title'), ['class' => 'form-control bg-light', 'id'=>'title', 'readonly' => 'readonly']) !!}
                        @else
                        {!! Form::text('title', old('title'), ['class' => 'form-control', 'id'=>'title']) !!}
                        @endif
                    </div>
                </div>
                @if($productTypeValue == 1)
                  <div class="col-md-4 px-1">
                      <div class="form-group">
                          {!! Form::label('sku', 'Sku', ['class' => 'required']) !!}
                          {!! Form::text('sku', old('sku'), ['class' => 'form-control']) !!}
                      </div>
                  </div>
                @endif
                @if(!$p_id)
                <div class="col-md-{{ $productTypeValue == 2 ? '6' : '4' }} px-1">
                  <div class="form-group">
                    {!! Form::label('category_id', 'Category', ['class' => 'required']) !!}
                    <select class="form-control" name="category_id[]" multiple>
                      @foreach($categories as $categoryValue => $categoryItem)
                        <option value="{{ $categoryValue }}" {{ in_array($categoryValue,$selectedCategories)?'selected':''  }}>{{ $categoryItem }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                @endif
                @if($productTypeValue == 1)
                  <div class="col-md-4 px-1">
                      <div class="form-group">
                          {!! Form::label('vendor_id', 'Vendor', ['class' => '']) !!}
                          <select class="form-control" name="vendor_id" id="vendor_id">
                            <option value="0">Select</option>
                            @foreach($vendors as $key => $item)
                              <option value="{{ $item->id }}" {{ $product && $product->vendor_id == $item->id ? "selected" : "" }}>{{ $item->name }} - {{ $item->business_name }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                @endif
                {{--<div class="col-md-2 px-1">
                    <div class="form-group">
                        {!! Form::label('type', 'Type', ['class' => 'required']) !!}
                        <select class="form-control nosearch" name="type" id="type">
                          <option value="">Select</option>
                          @foreach(productype() as $key => $item)
                            <option value="{{$key}}" {{$product && $product->type == $key?'selected':''}}>{{productTypeName($item)}}</option>
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 px-1 type-service">
                  <div class="form-group">
                    {!! Form::label('sub_type', 'SubType', ['class' => 'required']) !!}
                    <select class="form-control nosearch" name="sub_type" id="sub_type">
                      @foreach(producSubType() as $key => $item)
                        <option value="{{$key}}" {{$product && $product->sub_type == $key?'selected':''}}>{{$item}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>--}}
                @if(false)
                  <div class="col-md-2 px-1">
                    <div class="form-group">
                      {!! Form::label('model_no', 'Model No.') !!}
                      {!! Form::text('model_no', old('model_no'), ['class' => 'form-control']) !!}
                    </div>
                  </div>
                  <div class="col-md-2 px-1">
                    <div class="form-group">
                      {!! Form::label('isbn', 'ISBN') !!}
                      {!! Form::text('isbn', old('isbn'), ['class' => 'form-control']) !!}
                    </div>
                  </div>
                @endif
                @if($productTypeValue == 1)
                  <div class="col-md-4 px-1">
                    <div class="form-group">
                      {!! Form::label('avaibility', 'Quantity', ['class' => 'required']) !!}
                      {!! Form::number('avaibility', old('avaibility'), ['class' => 'form-control']) !!}
                    </div>
                  </div>
                  <div class="col-md-4 px-1">
                    <div class="form-group">
                      {!! Form::label('minimum_quantity', 'Minimum Quantity', ['class' => 'required']) !!}
                      {!! Form::number('minimum_quantity', old('minimum_quantity'), ['class' => 'form-control']) !!}
                    </div>
                  </div>
                @endif
                    @if($productTypeValue == 1)
                      @php
                        $minOrderQtyLable = "Min Order Qty.";
                        $maxOrderQtyLable = "Max Order Qty.";
                      @endphp
                    @elseif($productTypeValue == 2 && $p_id)
                      @php
                        $minOrderQtyLable = "Min Form Detail";
                        $maxOrderQtyLable = "Max Form Detail";
                      @endphp
                    @endif
                    @if($productTypeValue == 1 || ($productTypeValue == 2 && $p_id))
                      <div class="col-md-4 px-1">
                        <div class="form-group">
                          {!! Form::label('min_order_qty', $minOrderQtyLable, ['class' => 'required']) !!}
                          {!! Form::number('min_order_qty', old('min_order_qty'), ['class' => 'form-control']) !!}
                        </div>
                      </div>
                      <div class="col-md-4 px-1">
                        <div class="form-group">
                          {!! Form::label('max_order_qty', $maxOrderQtyLable, ['class' => 'required']) !!}
                          {!! Form::number('max_order_qty', old('max_order_qty'), ['class' => 'form-control']) !!}
                        </div>
                      </div>
                    @endif
                  @if($productTypeValue != 2)
                  <div class="col-md-4 px-1"></div>
                  @endif
                  @if($productTypeValue == 2)
                    @if(!$p_id)
                    <div class="col-12"></div>
                    <div class="col-md-6 px-1">
                      <div class="form-group">
                        {!! Form::label('pandit_id', 'Pandit Ji', ['class' => 'required']) !!}
                        {!! Form::select('pandit_id', $pandits, old('pandit_id'), ['class' => 'form-control', 'required']) !!}
                      </div>
                    </div>
                    <div class="col-md-6 px-1 subtype-offlinepooja">
                      <div class="form-group">
                          {!! Form::label('place', 'Place', ['class' => 'required']) !!}
                          {!! Form::text('place', $product ? $product->place : '', ['class' => 'form-control']) !!}
                      </div>
                    </div>
                    <div class="col-12"></div>
                    <div class="col-md-6 px-1 subtype-offlinepooja">
                      <div class="form-group">
                          <label class="required">Valid Till Date (select multiple)</label>
                          <div class="row no-gutters align-items-end">
                            <div class="col-auto pr-2">
                              <select name="valid_till_month" id="valid_till_month" class="form-control" style="min-width:120px;">
                                <option value="">Select Month</option>
                                @foreach($validTillMonths ?? [] as $num => $name)
                                  <option value="{{ $num }}" {{ (isset($validTillMonth) && (int)$validTillMonth === (int)$num) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-auto pr-2">
                              <select name="valid_till_year" id="valid_till_year" class="form-control" style="min-width:90px;">
                                <option value="">Select Year</option>
                                @foreach($validTillYears ?? [] as $y)
                                  <option value="{{ $y }}" {{ (isset($validTillYear) && (int)$validTillYear === (int)$y) ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div id="valid_till_dates_calendar_box" class="valid-till-calendar-box" style="display:none;"></div>
                          <div id="valid_till_dates_hidden_container"></div>
                      </div>
                    </div>
                    <div class="col-md-3 px-1 subtype-offlinepooja">
                      <div class="form-group">
                          {!! Form::label('expire_at_time', 'Valid Till Time', ['class' => 'required']) !!}
                          {!! Form::time('expire_at_time', $product ? $product->expire_at_time : '', ['class' => 'form-control']) !!}
                      </div>
                    </div>
                    <div class="col-12"></div>
                    @if($productTypeValue != 2)
                    <div class="col-md-4 px-1 d-flex align-items-end pb-3">
                      <div class="form-group mb-0">
                        <span class="ml-2 mt-2"><strong>Renew</strong></span>
                        <input type="checkbox" class="toggle-check" id="renew" value="1" name="renew">
                        <label class="mx-2 t-lable mt-2" for="renew"></label>
                      </div>
                    </div>
                    @endif
                    @else
                    <div class="col-md-6 px-1 subtype-offlinepooja">
                      <div class="form-group">
                          {!! Form::label('video_call_url', 'Video Call Url', ['class' => 'required']) !!}
                          {!! Form::text('video_call_url', old('video_call_url'), ['class' => 'form-control']) !!}
                      </div>
                    </div>
                    @if($productTypeValue != 2)
                    <div class="col-md-2 px-1">
                      <div class="form-group ">
                        <span class="ml-2 mt-2"><strong>Renew</strong></span>
                        <input type="checkbox" class="toggle-check" id="renew" value="1" name="renew">
                        <label class="mx-2 t-lable mt-2" for="renew"></label>
                      </div>
                    </div>
                    @endif
                    @endif
                    @if($p_id)
                    <div class="col-md-12 px-0 mt-2">
                      <div class="form-group">
                        <strong class="d-block mb-2">Upload Image</strong>
                        <div class="row" id="image-gallery">
                          @if($product && count($product->productGall))
                            @foreach($product->productGall as $p => $pro)
                              <div class="form-group col-12">
                                <div class="d-flex align-items-center">
                                  <button type="button" class="btn btn-sm mx-2" data-toggle="modal" onclick="openMediaManager('image_id{{$p}}')">Upload Image</button>
                                  <input type="hidden" id="image_id{{$p}}_id" name="image_id[]" value="{{$pro->image_id}}">
                                  <input type="hidden" id="image_id{{$p}}_src" name="image_src[]" value="{{$pro->image ? $pro->image->image(200,200) : ''}}">
                                  <div class="image_id{{$p}}_image float-right">
                                    <img src="{{$pro->image ? $pro->image->image(100,100) : ''}}" short_name="icon" class="img-responsive">
                                    <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image_id{{$p}}')"></i>
                                  </div>
                                </div>
                                  <input type="hidden" name="alt_text[]" value="{{ $pro->alt_text }}">
                              </div>
                            @endforeach
                          @else
                              <div class="form-group col-12">
                                <div class="d-flex align-items-center">
                                  <button type="button" class="btn btn-sm mx-2" data-toggle="modal" onclick="openMediaManager('image_id0')">Upload Image</button>
                                  <input type="hidden" id="image_id0_id" name="image_id[]" value="">
                                  <input type="hidden" id="image_id0_src" name="image_src[]" value="">
                                  <div class="image_id0_image float-right"></div>
                                </div>
                                  <input type="hidden" name="alt_text[]" value="">
                              </div>
                          @endif
                        </div>
                      </div>
                    </div>
                    @endif
                    @php
                      $isOneToOnePuja = $product && $product->is_one_to_one_puja == 1;
                    @endphp
                    @if($isOneToOnePuja)
                      <div class="col-md-12 text-center mb-3 py-2 form-text-bg">
                        <div>
                          <h4 class="p-0 m-0 text-white">Commission Settings</h4>
                        </div>
                      </div>
                      <div class="col-md-4 px-1">
                        <div class="form-group">
                          {!! Form::label('commission_type', 'Commission Type', ['class' => 'required']) !!}
                          {!! Form::select('commission_type', [
                            'percentage' => 'Percentage',
                            'fixed' => 'Fixed'
                          ], old('commission_type', $product ? $product->commission_type : 'percentage'), ['class' => 'form-control', 'id' => 'commission_type']) !!}
                        </div>
                      </div>
                      <div class="col-md-4 px-1">
                        <div class="form-group">
                          {!! Form::label('astrologer_commission_percentage', 'Commission Value', ['class' => 'required']) !!}
                          {!! Form::number('astrologer_commission_percentage', old('astrologer_commission_percentage', $product ? $product->astrologer_commission_percentage : ''), [
                            'class' => 'form-control',
                            'id' => 'astrologer_commission_percentage',
                            'step' => '0.01',
                            'min' => '0',
                            'required' => 'required'
                          ]) !!}
                          <small class="form-text text-muted">
                            <span id="commission_hint">
                              @if($product && $product->commission_type == 'fixed')
                                Enter fixed amount (e.g., 200)
                              @else
                                Enter percentage (e.g., 10 for 10%)
                              @endif
                            </span>
                          </small>
                        </div>
                      </div>
                    @endif
                  @endif
                  @if($productTypeValue == 3)
                    <div class="col-md-4 px-1">
                      <div class="form-group">
                        {!! Form::label('api_url_id', 'Api', ['class' => '']) !!}
                        {!! Form::select('api_url_id', $apiUrls, old('api_url_id'), ['class' => 'form-control', 'required']) !!}
                      </div>
                    </div>
                  @endif
                <div class="col-md-12 text-center mb-3 py-2 form-text-bg">
                    <div>
                        <h4 class="p-0 m-0 text-white">Price Details</h4>
                    </div>
                </div>
                <div class="col-md-3 px-1">
                    <div class="form-group">
                        {!! Form::label('base_price', 'Price', ['class' => 'required']) !!}
                        {!! Form::text('base_price', old('base_price'), ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-3 px-1">
                    <div class="form-group">
                        {!! Form::label('sale_price', 'Sale Price', ['class' => '']) !!}
                        {!! Form::text('sale_price', old('sale_price'), ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-3 px-1">
                    <div class="form-group">
                        {!! Form::label('sale_price_start_date', 'Sale Price Start Date', ['class' => '']) !!}
                        {!! Form::date('sale_price_start_date', old('sale_price_start_date'), ['class' => 'form-control', 'max'=> '9999-12-31']) !!}
                    </div>
                </div>
                <div class="col-md-3 px-1">
                    <div class="form-group">
                        {!! Form::label('sale_price_end_date', 'Sale Price End Date', ['class' => '']) !!}
                        {!! Form::date('sale_price_end_date', old('sale_price_end_date'), [
                            'class' => 'form-control', 'max'=> '9999-12-31'
                        ]) !!}
                    </div>
                </div>
                @if($productTypeValue == 1)
                  <div class="col-12 text-center mb-3 py-2 form-text-bg">
                      <div>
                          <h4 class="p-0 m-0 text-white">Tax/Shipping</h4>
                      </div>
                  </div>
                  <div class="col-md-6 px-1">
                      <div class="form-group">
                          {!! Form::label('hsn_id', 'HSN Code', ['class' => 'required']) !!}
                          <select class="form-control nosearch" name="hsn_id">
                            <option value="">Select</option>
                            @foreach($hsnCodes as $key => $hsn)
                            <option value="{{$hsn->id}}" {{$product && $product->hsn_id==$hsn->id?'selected':''}}>{{$hsn->name}} - {{$hsn->hsn_code}} - {{$hsn->gst_percentage}}%</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6 col-12 tax-section-field">
                    <div class="form-group">
                      {!! Form::label('shipping_based_on', 'Shipping based on', ['class'=>'required']) !!}
                      {!! Form::select('shipping_based_on', $shipping_based_on, old('shipping_based_on'), ['class' => 'form-control nosearch', 'id'=>'shipping_based_on']) !!}
                    </div>
                  </div>
                  <div class="col-md-3 col-12 tax-section-field">
                    <div class="form-group">
                      {!! Form::label('weight', 'Weight (gm)', ['class'=>'required']) !!}
                      {!! Form::text('weight', old('weight'), ['class' => 'form-control', 'id'=>'weight']) !!}
                    </div>
                  </div>
                  <div class="col-md-3 col-12 tax-section-field">
                    <div class="form-group">
                      {!! Form::label('length', 'Length (cm)', ['class'=>'required']) !!}
                      {!! Form::text('length', old('length'), ['class' => 'form-control', 'id'=>'length']) !!}
                    </div>
                  </div>
                  <div class="col-md-3 col-12 tax-section-field">
                    <div class="form-group">
                      {!! Form::label('breadth', 'Breadth (cm)', ['class'=>'required']) !!}
                      {!! Form::text('breadth', old('breadth'), ['class' => 'form-control', 'id'=>'breadth']) !!}
                    </div>
                  </div>
                  <div class="col-md-3 col-12 tax-section-field">
                    <div class="form-group">
                      {!! Form::label('height', 'Height (cm)', ['class'=>'required']) !!}
                      {!! Form::text('height', old('height'), ['class' => 'form-control', 'id'=>'height']) !!}
                    </div>
                  </div>
                  <div class="col-md-4 px-1">
                    <div class="form-group">
                      {!! Form::label('delivery_date_id', 'Delivery Date', ['class' => 'required']) !!}
                      {!! Form::select('delivery_date_id', $deliveryDates, old('delivery_date_id'), ['class' => 'form-control', 'required']) !!}
                    </div>
                  </div>
                  <div class="form-group col-md-2 px-1">
                      <span class="ml-2"><strong>Apply Shipping</strong></span>
                        <input type="checkbox" class="toggle-check" id="apply_shipping" {{$product && $product->apply_shipping ? 'checked' : ''}} value="1" name="apply_shipping">
                        <label class="mx-2 t-lable mt-2" for="apply_shipping"></label>
                  </div>
                @endif
                <div class="col-12 text-center mb-3 py-2 form-text-bg">
                    <div>
                        <h4 class="p-0 m-0 text-white">Other</h4>
                    </div>
                </div>
                <div class="form-group col-md-4 px-1">
                   <div class="form-group">
                    {!! Form::label('product_order', 'Order') !!}
                    {!! Form::number('product_order', $product ? $product->order : '', ['class'=>'form-control', 'min'=>'0']) !!}
                  </div>
                </div>

                <div class="form-group col-md-2 px-1 d-none">
                    <span class="ml-2"><strong>Best Seller</strong></span>
                      <input type="checkbox" class="toggle-check" id="is_best_seller" {{$product && $product->is_best_seller ? 'checked' : ''}} value="1" name="is_best_seller">
                      <label class="mx-2 t-lable mt-2" for="is_best_seller"></label>
                </div>
                <div class="form-group col-md-2 px-1 d-none">
                    <span class="ml-2"><strong>Display In footer</strong></span>
                      <input type="checkbox" class="toggle-check" id="is_featured" {{$product && $product->is_featured ? 'checked' : ''}} value="1" name="is_featured">
                      <label class="mx-2 t-lable mt-2" for="is_featured"></label>
                </div>
                @if($productTypeValue != 2)
                @php
                  // Determine refund and cancellation values (not shown for Puja)
                  $productRefundEnabled = 0;
                  $productRefundDays = '';
                  $isFromCategory = false;
                  if($product && $product->refund_and_cancellation !== null) {
                    $productRefundEnabled = $product->refund_and_cancellation;
                    $productRefundDays = $product->refund_cancellation_days ?? '';
                  } elseif(isset($categoryRefundSettings) && $categoryRefundSettings['refund_and_cancellation']) {
                    $productRefundEnabled = 1;
                    $productRefundDays = $categoryRefundSettings['refund_cancellation_days'] ?? '';
                    $isFromCategory = true;
                  }
                @endphp
                <div class="form-group col-md-2 px-1">
                    <span class="ml-2 mt-2"><strong>Refund and Cancellation</strong></span>
                      <input type="checkbox" class="toggle-check" id="refund_and_cancellation" {{$productRefundEnabled ? 'checked' : ''}} value="1" name="refund_and_cancellation" onchange="toggleRefundDaysField()">
                      <label class="mx-2 t-lable mt-2" for="refund_and_cancellation"></label>
                      @if($isFromCategory)
                        <small class="text-muted d-block" style="font-size: 10px;">(From Category)</small>
                      @endif
                </div>
                <div class="form-group col-md-3 px-1" id="refund_days_field" style="display: {{$productRefundEnabled ? 'block' : 'none'}};">
                  {!! Form::label('refund_cancellation_days', 'Days', ['class'=>'']) !!}
                  {!! Form::number('refund_cancellation_days', old('refund_cancellation_days', $productRefundDays), ['class'=>'form-control', 'min'=>'0', 'step'=>'1']) !!}
                  @if($isFromCategory)
                    <small class="text-muted" style="font-size: 10px;">Category default: {{$categoryRefundSettings['refund_cancellation_days'] ?? 'N/A'}} days</small>
                  @endif
                </div>
                @endif
                <div class="form-group col-md-2 px-1">
                    <span class="ml-2 mt-2"><strong>Status</strong></span>
                      <input type="checkbox" class="toggle-check" id="status" {{$product && $product->status ? 'checked' : ''}} value="1" name="status">
                      <label class="mx-2 t-lable mt-2" for="status"></label>
                </div>
                <div class="form-group col-md-3 px-1">
                    <span class="ml-2 mt-2"><strong>Show in Astrologer Profile</strong></span>
                    <input type="checkbox" class="toggle-check" id="show_in_astrologer_profile" {{ $product && $product->show_in_astrologer_profile ? 'checked' : '' }} value="1" name="show_in_astrologer_profile">
                    <label class="mx-2 t-lable mt-2" for="show_in_astrologer_profile"></label>
                    <small class="d-block text-muted" style="font-size: 10px;">Display in "Top Selling" on astrologer profile</small>
                </div>
              @if($productTypeValue == 2)
                {{-- <div class="form-group col-md-3 px-1">
                    <span class="ml-2 mt-2"><strong>One-to-One Puja</strong></span>
                      <input type="checkbox" class="toggle-check" id="is_one_to_one_puja" {{$product && $product->is_one_to_one_puja == 1 ? 'checked' : ''}} value="1" name="is_one_to_one_puja">
                      <label class="mx-2 t-lable mt-2" for="is_one_to_one_puja"></label>
                </div> --}}
                <div class="form-group col-md-12 px-0 d-none" id="form-fields">
                  <div class="card-body px-0">
                    <div class="col-md-12 text-center mb-3 py-2 form-text-bg">
                      <div>
                        <h4 class="p-0 m-0 text-white">Service Form Fields</h4>
                      </div>
                    </div>
                    <div class="row px-2">
                      @foreach($formFields as $key => $item)
                        <div class="col-md-3 mb-3 px-1">
                          <div class="d-flex">
                            <span class="ml-2"><strong>{{$item->form_label}}</strong></span>
                            <input type="checkbox" class="toggle-check" id="{{$item->form_label}}_{{$key}}_field" value="{{$item->id}}" {{$product && in_array($item->id, $productFields) ? 'checked' : '' }} name="form_field[]">
                            <label class="mx-2 t-lable" for="{{$item->form_label}}_{{$key}}_field"></label>
                          </div>
                          <input type="number" class="form-control" name="order[{{$item->id}}]" value="{{$product && isset($productFieldsOrder[$item->id]) ? $productFieldsOrder[$item->id] : ''  }}" placeholder="order">
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endif
              @if($productTypeValue != 2)
              <div class="form-group col-md-12 px-0" id="tags-section">
                <div class="card-body px-0">
                  <div class="col-md-12 text-center mb-3 py-2 form-text-bg">
                      <div>
                          <h4 class="p-0 m-0 text-white">Tags</h4>
                      </div>
                  </div>
                    <div class="row px-2">
                      <div class="col-12">
                          <select data-placeholder="Select Tags ..." multiple="multiple" id="tags" class="w-100 nosearch" name="tags[]">
                            @if($product)
                              @foreach($product->tags as $index => $value)
                                <option value="{{$value->tag?$value->tag->id:''}}" selected>{{$value->tag?$value->tag->name:''}}</option>
                              @endforeach
                            @endif
                          </select>
                      </div>
                    </div>
                </div>
            </div>
              @endif
            </div>
        </div>
        <div id="seo_tab" class="tab-pane fade">
          <div class="row">
            <div class="col-md-6 col-12">
              <div class="form-group">
                {!! Form::label('slug', 'Url Key', ['class'=>'required']) !!}
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
        @if(!$p_id)
        <div id="description_tab" class="tab-pane fade">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        @if(isset($product_spec) && $product_spec->description)
                        {!! Form::textarea('description', $product ? $product->specifications->description : '', ['class' => 'form-control']) !!}
                        @else
                        {!! Form::textarea('description', old('description'), ['class' => 'form-control']) !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('short_description', 'Short Description') !!}
                        @if(isset($product_spec) && $product_spec->short_description)
                        {!! Form::textarea('short_description', $product ? $product->specifications->short_description :'', ['class' => 'form-control']) !!}
                        @else
                        {!! Form::textarea('short_description', old('short_description'), ['class' => 'form-control']) !!}
                        @endif
                    </div>

                </div>
                {{--
                <div class="col-md-12 text-center"
                    style="background: antiquewhite;height: 29px;font-size: 18px;font-weight: 600;">
                    <div>
                        <p>Product Specification</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('label', 'Product Specification') !!}
                        @if(isset($product_spec) && $product_spec->label)
                        {!! Form::textarea('label', old('label', $product_spec->label), ['class' => 'form-control']) !!}
                        @else
                        {!! Form::textarea('label', old('label'), ['class' => 'form-control']) !!}
                        @endif
                    </div>
                </div>
                --}}
            </div>
        </div>
        @endif
        @if(!$p_id)
        <div id="image_tab" class="tab-pane fade">
          <div class="col-12">
            <div class="form-group">
                {!! Form::label('video_url','Video Url (youtube)') !!}
                {!! Form::text('video_url', $product && $product->video ? $product->video->video_url : '',['class'=>'form-control', 'id'=>'video_url']) !!}
            </div>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <div>
              <a href="javascript:void(0)"  onclick="addGallery(this)" class="btn btn-primary float-right">
              <i class="bi bi-plus text-white fs-5"></i>
             </a>
           </div>
          </div>
          <div class="row" id="image-gallery">
          @if($product && count($product->productGall))
            @foreach($product->productGall as $p => $pro)
                <div class="form-group col-4">
                    <div class="d-flex align-items-center">
                       <button type="button" class="btn btn-sm mx-2"  data-toggle="modal" onclick="openMediaManager('image_id{{$p}}')">Upload Image</button>
                       <input type="hidden" id="image_id{{$p}}_id" name="image_id[]" value="{{$pro->image_id}}">
                       <input type="hidden" id="image_id{{$p}}_src" name="image_src[]" value="{{$pro->image ? $pro->image->image(200,200) : ''}}">
                      <div class="image_id{{$p}}_image float-right">
                        <img src="{{$pro->image ? $pro->image->image(100,100) : ''}}" short_name="icon" class="img-responsive">
                        <i class="fa fa-trash text-danger" onclick="removeImage(this, 'image_id{{$p}}')"></i>
                      </div>
                    </div>
                </div>
                <div class="form-group col-8">
                  <input type="text"  name="alt_text[]" value="{{ $pro->alt_text }}" class="form-control" id="alt_text{{ $p }}_id">
                </div>
              @endforeach
            @endif
        </div>
    </div>
        @endif

    {{-- <div class="form-group">
  {!! Form::label('form_type', 'From Type'); !!}
  {!! Form::select('form_type',formTypes(), old('form_type'), ['class'=>'form-control', 'required']) !!}
</div> --}}

@if(!$p_id)
<div id="features_tab" class="tab-pane fade">
  <div class="d-flex justify-content-between mb-2">
    <div class="">
      <h3>Heading</h3>
    </div>
    <div class="">
      <a href="javascript:void(0)"  onclick="addFeature(this)" class="btn btn-primary float-right">
        <i class="bi bi-plus text-white fs-13"></i>
     </a>
   </div>
  </div>

  <table class="table">

    <tbody id="features-div">
      @php
      $i = 0;
      @endphp
        @if($product && count($product->productFeatures))
        @foreach($product->productFeatures as $featureKey => $featureItem)
        <tr>
          <td class="">
            <div class="form-group col-md-4">
                <div class="">
                   <button type="button" class="btn btn-sm"  data-toggle="modal" onclick="openMediaManager('icon_f_id{{$featureKey}}')">Upload Image</button>
                   <input type="hidden" id="icon_f_id{{$featureKey}}_id" name="icon_f_id[]" value="{{$featureItem->icon_id}}">
                   @if($product && $featureItem->image)
                     <input type="hidden" id="icon_f_src{{$featureKey}}_src" name="icon_f_src[]" value="{{$featureItem->image->image(200,200)}}">
                     <div class="icon_f_id{{$featureKey}}_image">
                      <img src="{{$featureItem->image->image(100,100)}}" short_name="icon" class="img-responsive">
                      <i class="fa fa-trash text-danger" onclick="removeImage(this, 'icon_f_id{{$featureKey}}')"></i>
                    </div>
                  @endif
                </div>
            </div>
          </td>
          <td>
             <div class="form-group">
             <input type="text" class="form-control" name="f_heading[]" value="{{$featureItem->f_heading}}">
            </div>
          </td>
          <td>
            @if($featureKey)
             <div class="">
                 <a href="javascript:void(0)" onclick="removeFeature(this)">
                   <i class="fa fa-trash text-danger border border-danger p-2" ></i>
                </a>
            </div>
            @endif
          </td>
        </tr>
        @php
        $i++;
        @endphp
      @endforeach
      @else
        <tr>
          <td class="">
            <div class="form-group col-md-4">
                <div class="">
                   <button type="button" class="btn btn-sm" data-toggle="modal" onclick="openMediaManager('icon_f_id0')">Upload Image</button>
                   <input type="hidden" id="icon_f_id0_id" name="icon_f_id[]">
                   <input type="hidden" id="icon_f_id0_src" name="icon_f_src[]">
                </div>
            </div>
          </td>
          <td>
             <div class="form-group">
             <input type="text" placeholder="enter text" class="form-control" name="f_heading[]">
            </div>
          </td>
          <td>
          </td>
        </tr>
      @endif
    </tbody>
  </table>
</div>
@endif
@if(!$p_id)
<div id="specification_tab" class="tab-pane fade">
    <div class="d-flex justify-content-end mb-2">
      <div>
        <a href="javascript:void(0)"  onclick="addSpecification(this)" class="btn btn-primary float-right">
          <i class="bi bi-plus text-white fs-5"></i>
       </a>
     </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>Heading</th>
          <th>Text</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="specification-div">
        @php
        $i = 0;
        @endphp
          @if($product && count($product->productSpec))
          @foreach($product->productSpec as $specificationKey => $specificationItem)
          <tr>
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
            <td>
              @if($specificationKey)
              <div class="">
                 <a href="javascript:void(0)" onclick="removeSpecification(this)">
                   <i class="fa fa-trash text-danger border border-danger p-2" ></i>
                 </a>
              </div>
              @endif
            </td>
          </tr>
          @php
          $i++;
          @endphp
        @endforeach
        @else
          <tr>
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
            <td>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
@endif

  <div id="variation_tab" class="tab-pane fade">
      @if($p_id)
        <div class="form-group col-4 px-1 d-none">
      @elseif($product && $product->variations->count())
        <div class="form-group col-4 px-1 d-none">
      @else
        <div class="form-group col-4 px-1">
      @endif
        <span class="ml-2 mt-2"><strong>Has Variation</strong></span>
        <input type="checkbox" class="toggle-check" id="has_variation" onchange="handleHasVariation(this)" {{($product && $product->has_variation) || $p_id ? 'checked' : ''}} value="1" name="has_variation">
        <label class="mx-2 t-lable mt-2" for="has_variation"></label>
      </div>
      @php
        $pAttributesArray = [];
        if($product && $product->parent_id==0 && $product->has_variation){
          $pAttributesArray = $product->p_attributes?$product->p_attributes->pluck('attribute_id')->toArray():[];
        }
      @endphp
      @if($p_id)
        <div class="form-group col-4 px-1 d-none" id="variations-div">
          {!! Form::label('atributes', 'Select Attributes', ['class' => 'required']) !!}
          <select class="form-control" onchange="handleAttribute(this)" multiple name="atributes[]" id="atributes">
            <option value="0">Select</option>
            @foreach($attributes as $index => $value)
              <option value="{{$value->id}}" selected >{{$value->name}}</option>
            @endforeach
          </select>
        </div>
      @else
        <div class="form-group col-4 px-1 {{$product && $product->parent_id==0 && $product->has_variation ? '' : 'd-none'}}" id="variations-div">
          {!! Form::label('atributes', 'Select Attributes', ['class' => 'required']) !!}
          <select class="form-control" onchange="handleAttribute(this)" multiple name="atributes[]" id="atributes">
            <option value="0">Select</option>
            @foreach($attributes as $index => $value)
              <option value="{{$value->id}}" {{in_array($value->id, $pAttributesArray)?'selected':''}} >{{$value->name}}</option>
            @endforeach
          </select>
        </div>
      @endif


    <div class="form-group col-md-12 px-0" id="attribute-section">
      <div class="card-body px-0">
        @php
          $product_attributes = [];
          if($product){
            $product_attributes = $product->attributes->pluck('attribute_value', 'attribute_id')->toArray();
          }
        @endphp
        @if($p_id)
          <div class="row px-2">
            @foreach($attributes as $key => $value)
              <div class="col-4 form-group attributeDivs" id="attributeDiv{{$value->id}}">
                  {!! Form::label('attribute['.$value->key.']', $value->name, ['class' => 'required']) !!}
                  {!! Form::text('attribute['.$value->key.']', (isset($product_attributes[$value->id]) ? $product_attributes[$value->id] : ''), ['class' => 'form-control']) !!}
              </div>
            @endforeach
          </div>
        @else
          <div class="row px-2">
            @foreach($attributes as $key => $value)
              <div class="col-4 form-group {{in_array($value->id, $pAttributesArray)?'':'d-none'}} attributeDivs" id="attributeDiv{{$value->id}}">
                  {!! Form::label('attribute['.$value->key.']', $value->name, ['class' => 'required']) !!}
                  {!! Form::text('attribute['.$value->key.']', (isset($product_attributes[$value->id]) ? $product_attributes[$value->id] : ''), ['class' => 'form-control']) !!}
              </div>
            @endforeach
          </div>
        @endif

      </div>
    </div>
  </div>



    {!! Form::close() !!}


    @include('admin.media.manager')
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Update commission hint text based on commission type
    function updateCommissionHint() {
      var commissionType = $('#commission_type').val();
      var hintText = '';

      if(commissionType == 'fixed') {
        hintText = 'Enter fixed amount (e.g., 200)';
      } else {
        hintText = 'Enter percentage (e.g., 10 for 10%)';
      }

      $('#commission_hint').text(hintText);
    }

    // Update hint on page load
    if($('#commission_type').length) {
      updateCommissionHint();

      // Update hint when commission type changes
      $('#commission_type').on('change', function() {
        updateCommissionHint();
      });
    }
  });
</script>
@include('admin._partials.editor')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript">
(function() {
  var monthSel = document.getElementById('valid_till_month');
  var yearSel = document.getElementById('valid_till_year');
  var box = document.getElementById('valid_till_dates_calendar_box');
  var container = document.getElementById('valid_till_dates_hidden_container');
  if (!monthSel || !yearSel || !box) return;

  var selectedDates = @json($validTillDates ?? []);
  var weekDays = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];

  function pad(n) { return n < 10 ? '0' + n : n; }
  function syncHidden() {
    if (!container) return;
    container.innerHTML = '';
    selectedDates.forEach(function(d) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'valid_till_dates[]';
      input.value = d;
      container.appendChild(input);
    });
  }
  function toggleDate(y, m, d) {
    var key = y + '-' + pad(m) + '-' + pad(d);
    var i = selectedDates.indexOf(key);
    if (i === -1) selectedDates.push(key);
    else selectedDates.splice(i, 1);
    selectedDates.sort();
    syncHidden();
    renderCalendar();
  }
  function renderCalendar() {
    var month = parseInt(monthSel.value, 10);
    var year = parseInt(yearSel.value, 10);
    if (!month || !year) { box.style.display = 'none'; return; }
    box.style.display = 'block';

    var first = new Date(year, month - 1, 1);
    var last = new Date(year, month, 0);
    var daysInMonth = last.getDate();
    var startWeekday = first.getDay();
    startWeekday = startWeekday === 0 ? 6 : startWeekday - 1;
    var prevMonthLast = new Date(year, month - 1, 0).getDate();
    var rows = [];
    var row = [];
    for (var i = 0; i < startWeekday; i++) {
      var d = prevMonthLast - startWeekday + i + 1;
      row.push('<td class="day-cell other-month">' + d + '</td>');
    }
    for (var d = 1; d <= daysInMonth; d++) {
      var key = year + '-' + pad(month) + '-' + pad(d);
      var cls = 'day-cell current-month' + (selectedDates.indexOf(key) !== -1 ? ' selected' : '');
      row.push('<td class="' + cls + '" data-year="' + year + '" data-month="' + month + '" data-day="' + d + '">' + d + '</td>');
      if (row.length === 7) { rows.push('<tr>' + row.join('') + '</tr>'); row = []; }
    }
    var nextMonthCount = 7 - row.length;
    if (nextMonthCount < 7) {
      for (var n = 1; n <= nextMonthCount; n++) row.push('<td class="day-cell other-month">' + n + '</td>');
      rows.push('<tr>' + row.join('') + '</tr>');
    }
    var html = '<table><thead><tr>';
    weekDays.forEach(function(w) { html += '<th>' + w + '</th>'; });
    html += '</tr></thead><tbody>' + rows.join('') + '</tbody></table>';
    box.innerHTML = html;
    box.querySelectorAll('.day-cell.current-month').forEach(function(cell) {
      cell.addEventListener('click', function() {
        toggleDate(parseInt(cell.dataset.year), parseInt(cell.dataset.month), parseInt(cell.dataset.day));
      });
    });
  }
  monthSel.addEventListener('change', renderCalendar);
  yearSel.addEventListener('change', renderCalendar);
  syncHidden();
  if (monthSel.value && yearSel.value) renderCalendar();
})();
</script>
<script type="text/javascript">
@if(!$product)
  $('#title').on('keyup', function(){
    var value = $(this).val();
    $('#slug').val(value.trim().replaceAll(' ', '-'));
  });
@endif
@if($product)
  handleProductType(@json($product->type));
  handleProductSubType(@json($product->sub_type));
@else
  handleProductType(1);
  handleProductSubType(0);
@endif
var media_image_type = 'product';
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
  newImg.append('<i class="fa fa-trash text-danger" onclick="removeImage(this, \''+imageset+'\')"></i>');
  $('#'+imageset+'_id').after(newImg)
}
$('#media-img-manager').modal('hide');
});
var removeImage = function(elm, image){
    $(elm).parent('.'+image+'_image').remove();
    // $(elm).parents().remove();
    $('#'+image+'_id').val('');
    $('#'+image+'_src').val('');
 }
var button = $('input[type="submit"]');
var validateAndSubmit = function(form){
tinyMCE.triggerSave();
var _f = $(form);

// button.addClass('disabled').attr('disabled', 'disabled').val('Submitting...');
_f.find('.btn-outline-danger').removeClass('btn-outline-danger');
$.ajax({
  method: "POST",
  url: _f.attr('action'),
  data: _f.serialize()
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
    button.removeClass('disabled').removeAttr('disabled').val('Submit');
});

return false;
}

var openMediaManager = function(image = null) {
imageset = image;
$('#media-img-manager').modal('show');
}

function handleProductType(value){
  // $('#form-fields').addClass('d-none');
  // $('.type-service').addClass('d-none');
  // $('.tax-section-field').addClass('d-none');
  // if(value == 3) {
  //   $('#form-fields').removeClass('d-none');
  //   $('.type-service').removeClass('d-none');
  // }else if(value == 1){
  //   $('.tax-section-field').removeClass('d-none');
  // }
}
function handleProductSubType(value){
  // $('.subtype-offlinepooja').addClass('d-none');
  // if(value == 1) {
  //   $('.subtype-offlinepooja').removeClass('d-none');
  // }
}

$('#type').on('change',function(){
  handleProductType(this.value);
})
$('#sub_type').on('change',function(){
  handleProductSubType(this.value);
})
</script>

<script>
var count;
var productImageDiv;
$('doucment').ready(function (){
  productImageDiv = document.getElementById('image-gallery');
  productImageDiv = productImageDiv.children;
  count = productImageDiv.length;
  console.log(count)
})
function addGallery() {
  let newSection = document.createElement('div');
  newSection.classList.add("form-group", "col-4");
  newSection.innerHTML = `
              <div class="d-flex align-items-center">
                 <button type="button" class="btn btn-sm mx-2" data-toggle="modal" onclick="openMediaManager('image_id${count}')">Upload Image</button>
                 <input type="hidden" id="image_id${count}_id" name="image_id[]">
                 <input type="hidden" id="image_id${count}_src" name="image_src[]">
              </div>
  `;
  imageDiv = document.getElementById('image-gallery');
  imageDiv.appendChild(newSection);

  newSection = document.createElement('div');
  newSection.classList.add("form-group", "col-8");
  newSection.innerHTML = `
              <input type="text"  name="alt_text[]" class="form-control" id="alt_text${count}_id">
  `;
  imageDiv.appendChild(newSection);
  count++;
}
// function removeImage(element) {
//   $(element).parent().parent().parent().remove();
// }
</script>

<script>
var speCount;
var specificationDiv;
$('doucment').ready(function (){
  specificationDiv = document.getElementById('specification-div');
  specificationDiv = specificationDiv.children;
  speCount = specificationDiv.length;
})
  function addSpecification(elem){
    // <div class="form-group col-md-4">
    //     <div class="col-sm-9 float-right">
    //        <button type="button" class="btn float-left" data-toggle="modal" onclick="openMediaManager('icon_id${speCount}')">Upload Image</button>
    //        <input type="hidden" id="icon_id${speCount}_id" name="icon_id[]">
    //        <input type="hidden" id="icon_id${speCount}_src" name="icon_src[]">
    //     </div>
    // </div>
    let div = document.createElement('tr');
    let appendData = `
      <tr>
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
        <td>
        </td>
        <td>
         <div class="">
             <a href="javascript:void(0)" onclick="removeSpecification(this)">
               <i class="fa fa-trash text-danger border border-danger p-2"></i>
            </a>
          </div>
        </td>
      </tr>`
    div.innerHTML = appendData;
    specificationDiv = document.getElementById('specification-div');
    specificationDiv.appendChild(div)
    console.log(div)
    count++;
  }
  function removeSpecification(elem){
    $(elem).parent().parent().parent().remove();
  }
    function addFeature(elem){
    let featureDiv = document.getElementById('features-div');
    let feaCount = featureDiv.children;
    feaCount = feaCount.length;
      let div = document.createElement('tr');
      let appendData = `
                          <tr>
                            <td class="">
                              <div class="form-group col-md-4">
                                  <div class="">
                                     <button type="button" class="btn btn-sm" data-toggle="modal" onclick="openMediaManager('icon_f_id${feaCount}')">Upload Image</button>
                                     <input type="hidden" id="icon_f_id${feaCount}_id" name="icon_f_id[]">
                                     <input type="hidden" id="icon_f_id${feaCount}_src" name="icon_f_src[]">
                                  </div>
                              </div>
                            </td>
                            <td>
                               <div class="form-group">
                               <input type="text" class="form-control" name="f_heading[]">
                              </div>
                            </td>
                            <td>
                              <div class="">
                                <a href="javascript:void(0)" onclick="removeFeature(this)">
                                  <i class="fa fa-trash text-danger border border-danger p-2"> </i>
                               </a>
                               </div>
                            </td>
                          </tr>`
      div.innerHTML = appendData;
      featureDiv = document.getElementById('features-div');
      featureDiv.appendChild(div)
      count++;
    }
    function removeFeature(elem){
      $(elem).parent().parent().parent().remove();
    }
    $(function(){
        $('select').not('.nosearch').selectpicker({
            liveSearch: true
        });
    });
</script>

<script type="text/javascript">

$(document).ready(function (){
  // Tags section is hidden for Puja products (type 2), so init only when present
  if ($('#tags').length) {
    $('#tags').select2({
      placeholder: "Search Tags",
      allowClear: true,
      tags: true,
      minimumInputLength: 1, // Minimum input length before triggering the search
      ajax: {
        url: '{{route('admin.product.search.tag')}}',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            search: params.term
          };
        },
        processResults: function(data) {
          return {
            results: data.items
          };
        },
        cache: true,
        createTag: function(params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true // Mark as a new tag
            };
        }
      }
    });
  }
})


  function addVariation(that, containerId){
    let container = doucment.getElementById(containerId);
    let containerChildrens = container.children;
    let div = document.createElement('tr');
    let appendData = `
    <tr>
      <td>1</td>
      <td>
         <div class="form-group">
           <input type="text" class="form-control" name="variation_name[]">
        </div>
      </td>
      <td>
         <div class="form-group">
           <input type="text" class="form-control" name="variation_slug[]">
         </div>
      </td>
      <td>
         <div class="form-group">
           <input type="text" class="form-control" name="variation_sku[]">
         </div>
      </td>`;
      @foreach($attributes as $key => $value)
        appendData += `<td>
           <div class="form-group">
             <input type="text" class="form-control" name="variation_attribute[${containerChildrens.length}][{{$value->key}}]" >
           </div>
        </td>`;
      @endforeach
      appendData += `<td>
         <div class="form-group">
           <input type="numeric" class="form-control" name="variation_price[]">
         </div>
      </td>
      <td>
         <div class="form-group">
           <input type="numeric" class="form-control" name="variation_special_price[]">
         </div>
      </td>
      <td>
        <i class="fa fa-trash text-danger" onclick="removeVariation(this)" ></i>
      </td>
    </tr>`;
    div.innerHTML = appendData;
    specificationDiv = document.getElementById('specification-div');
    specificationDiv.appendChild(div)
    console.log(div)
    count++;
  }

  function handleAttribute(that){
    let valueArray = $(that).val();
    let indexOf = valueArray.indexOf('0');
    if(indexOf!=-1){
      valueArray.splice(indexOf, 1);
    }
    if(valueArray.length>2){
      toastr.error('Please select any two attributes.');
      valueArray.splice(valueArray.length-1, 1);
    }
    $(that).val(valueArray);
    $('#atributes').selectpicker('refresh');
    $('.attributeDivs').addClass('d-none');
    $(that).val().forEach(function(item){
      $('#attributeDiv'+item).removeClass('d-none');
    });

  }
  function handleHasVariation(that){
    $('#variations-div').addClass('d-none');
    $('.attributeDivs').addClass('d-none');
    if($(that).prop('checked')){
      $('#variations-div').removeClass('d-none');
      $('#atributes').val().forEach(function(item){
        $('#attributeDiv'+item).removeClass('d-none');
      });
    }else{
      $('#atributes').val() = [];
    }
  }

  function exportCsv(that, timeLimit, productId){
    toastr.success(`Please wait, Data is exporting (${timeLimit})....`);
    let toastrInterval = setInterval(function (){
      toastr.success(`Please wait, Data is exporting (${timeLimit})....`);
    }, 5000);
    $(that).prop("disabled", true).text("Exporting..");
    let formData = {
      "time_limit" : timeLimit,
      "product_id" : productId
    }
    $.ajax({
      url: "{{ route('admin.product.exportcsv') }}",
      type : 'GET',
      data : formData,
      dataType: 'json',
      success: function (response){
        clearInterval(toastrInterval);
        toastr.success(response.message);
        let redirectTo = response.redirectTo;
        if(redirectTo){
          window.location.href = redirectTo;
        }
        $(that).prop("disabled", false).text(`Export (${timeLimit})`);
      },
      error: function (error){
        clearInterval(toastrInterval);
        $(that).prop("disabled", false).text(`Export (${timeLimit})`);
        let errors = error.responseJSON.errors;
        if(errors){
          for (var key in errors){
            toastr.error(errors[key][0]);
          }
        }else{
          let message = error.responseJSON.message;
          if(message){
            toastr.error(message);
          }
        }
      }
    });
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
</script>

@endpush
