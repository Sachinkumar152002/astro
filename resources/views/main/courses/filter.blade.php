   <fieldset class="pb-2 border-bottom">
     <div class="f-heading-main p-2 p-md-3 float-shadow-2">
       <h5 class="fs-5 text fw-semibold p-0 m-0 ms-2">Filter by price</h5>
     </div>
  <div class="p-3 pb-1">

    <div class="price-field mt-0 mt-md-3">
       <input type="range" class="form-range"  min="{{$minPrice}}" max="{{$maxPrice}}" value="{{$priceFrom}}" id="lower">
       <input type="range" class="form-range"  min="{{$minPrice}}" max="{{$maxPrice}}" value="{{$priceTo}}" id="upper">
     </div>
     <div class="price-wrap mt-2 label-size">
       <div class="price-wrap-1">
           <label for="one">₹</label>&nbsp;
          <input id="one">
       </div>
       <div class="price-wrap_line"><i class="bi bi-dash-lg"></i></div>
       <div class="price-wrap-2">
           <label for="two" class="text-end">₹</label>
          <input id="two">
       </div>
     </div>
 </div>
</fieldset>
   <!-- filters -->
   @if(isset($categories) && count($categories))
   <div class="p-3">
   <div class="course-off-2 side-scrollbar">
    <fieldset class="py-3 pt-1 pb-1 border-bottom ">
        <h5 class="fs-6 text fw-semibold">Categories</h5>
      <div class="">
        <div class="form-check">
           <label class="form-check-label" for="type-p-all">
             <input class="form-check-input" {{$slug == 'all' ? 'checked' : ''}}  onclick="filterCategory('all')" type="radio" name="category_slug" value="all" id="all-category" >
               All
           </label>
         </div>
        @foreach($categories as $key => $item)
         <div class="form-check">
            <input class="form-check-input" {{$slug == $item->slug ? 'checked' : ''}} onclick="filterCategory('{{$item->slug}}')" type="radio" name="category_slug" value="{{$item->slug}}" id="{{$item->slug}}" >
            <label class="form-check-label" onclick="filterCategory('{{$item->slug}}')" for="{{$item->slug}}">
                {{$item->name}}
            </label>
          </div>
          @endforeach
      </div>
    </fieldset>
    @endif
    <fieldset class="py-3  pb-1 border-bottom">
        <h5 class="fs-6 text fw-semibold">Mode of Course</h5>
        @php
        $notIn = [0, 2]
        @endphp

        @foreach(courseTypes() as $key => $item)
        @if(!in_array($key, $notIn))
         <div class="form-check">
            <input class="form-check-input" {{$type == $key ? 'checked' : ''}} onclick="filterType('{{$key}}')" type="radio" name="type" id="type-{{$item}}" >
            <label class="form-check-label" for="type-{{$item}}">
              @if($item=='Live')
              {{$item}} (Online & Offline)
              @elseif($item=='Online')
              E-Learning
              @else
              {{$item}}
              @endif
            </label>
          </div>
       @endif
       @endforeach
    </fieldset>
    <fieldset class="py-3">
        <h6 class="fs-6 text fw-semibold">Package Type</h6>
         <div class="form-check">
            <input class="form-check-input" checked onclick="filterPurchaseType(0)" type="radio" name="purchase_type" id="type-p-all">
            <label class="form-check-label" for="type-p-all">All</label>
          </div>
        @foreach(purchaseTypes() as $key => $item)
         <div class="form-check">
            <input class="form-check-input" data-key="{{$key}}" {{$purchaseType == $key ? 'checked' : ''}} onclick="filterPurchaseType('{{$key}}')" type="radio" name="purchase_type" id="type-p-{{$item}}" >
            <label class="form-check-label" for="type-p-{{$item}}">
                {{$item}}
            </label>
          </div>
       @endforeach
    </fieldset>
  </div>
  </div>

  @foreach(ads('course') as $ad)
    @if($ad->image)
      <a href="{{$ad->link}}" target="_blank">
        <img src="{{$ad->image->image(300)}}" alt="ad-image" class="w-100 mt-3">
      </a>
    @else
      {!! $ad->content !!}
    @endif
  @endforeach
  @include(themeView().'common.adpopup', ['page'=>'course'])
