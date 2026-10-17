@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
{{--
@include(themeView().'astrologers._partials.top')
--}}
<div class="container mt-3 mb-3">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
          <div class="col-lg-9">
            <!-- new-card-design-start -->

            <ul id="myTab" role="tablist" class="nav nav-tabs">
              <li role="presentation" class="nav-item">
                <a id="live_courses-tab" data-bs-toggle="tab" href="#live_courses" role="tab" aria-controls="live_courses" aria-selected="true" class="nav-link text-dark active">Live Courses</a>
              </li>
              <li role="presentation" class="nav-item">
                <a id="online_courses-tab" data-bs-toggle="tab" href="#online_courses" role="tab" aria-controls="online_courses" aria-selected="false" class="nav-link text-dark" tabindex="-1">Online Courses</a>
              </li>
              <li role="presentation" class="nav-item">
                <a id="products-tab" data-bs-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="false" class="nav-link text-dark" tabindex="-1">Products</a>
              </li>
              <li role="presentation" class="nav-item">
                <a id="services-tab" data-bs-toggle="tab" href="#services" role="tab" aria-controls="services" aria-selected="false" class="nav-link text-dark" tabindex="-1">Services</a>
              </li>
            </ul>

     <!-- tab-start -->
           <div id="myTabContent" class="tab-content">
               <div id="live_courses" role="tabpanel" aria-labelledby="live_courses-tab" class="tab-pane fade active show">
               @php
               $hasOnlineCourse = false;
               @endphp
                 <div class="row mt-3">
                  @forelse($courses as $key => $item)
                   @if($item->course && ($item->course->type==1))
                     @php
                     $hasOnlineCourse = true;
                     @endphp
                      <div class="col-md-3 primium-n ps-0">
                        <div class="card border-0 p-card">
                            <a href="{{route('course.handle', [$item->course->slug])}}" tabindex="0">
                            <img src="{{$item->course && $item->course->image ? $item->course->image->image(200, 200) : asset('assets/image/default.png')}}" class="card-img-top" alt="Basic Astrology Course">
                          </a>
                          <div class="card-body">
                            <button  data-wishlist_id="{{$item->id}}" class="remove_from_wishlist_btn tag cart-tag">
                                <i class="bi bi-trash pointer"></i>
                            </button>
                            <div class="name">
                              <img src="{{$item->course && $item->course->image ? $item->course->image->image(50, 50) : asset('assets/image/default.png')}}" class="ins-img" alt="Instructor Image">
                              <h6 class="fw-bold c-h  4">{{$item->course->name}}</h6>
                            </div>
                            <div class="category">
                              <div class="card-icon">
                                <i class="bi bi-list-check text-white"></i>
                              </div>
                              <span class="c-h-f">{{ $item->course->category ? $item->course->category->name : 'category' }}</span>
                            </div>
                            <div class="footer">
                              <div class="">
                                <a href="javascript:void(0)" class="sale_price text-left fs-7 text fw-semibold" tabindex="0">₹ {{$item->course->special_price}}</a>
                              </div>
                              <div class="mt-2">
                                <a href="{{route('course.handle', [$item->course->slug])}}" tabindex="0">
                                  <button class="btn btn-secondary btn-sm wishlist-btn" tabindex="0"> Add to cart </button>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  @endif
                @endforeach
                @if(!$hasOnlineCourse)
                <span>No items</span>
                @endif
              </div>
              </div>
              <div id="online_courses" role="tabpanel" aria-labelledby="online_courses-tab" class="tab-pane fade">
                @php
                $hasLiveCourse = false;
                @endphp
                <div class="row mt-3">
                 @forelse($courses as $key => $item)
                  @if($item->course && ($item->course->type==3))
                    @php
                    $hasLiveCourse = true;
                    @endphp
                     <div class="col-md-3 primium-n ps-0">
                       <div class="card border-0 p-card">
                           <a href="{{route('course.handle', [$item->course->slug])}}" tabindex="0">
                           <img src="{{$item->course && $item->course->image ? $item->course->image->image(200, 200) : asset('assets/image/default.png')}}" class="card-img-top" alt="Basic Astrology Course">
                         </a>
                         <div class="card-body">
                           <button  data-wishlist_id="{{$item->id}}" class="remove_from_wishlist_btn tag cart-tag">
                               <i class="bi bi-trash pointer"></i>
                           </button>
                           <div class="name">
                             <img src="{{$item->course && $item->course->image ? $item->course->image->image(50, 50) : asset('assets/image/default.png')}}" class="ins-img" alt="Instructor Image">
                             <h6 class="fw-bold c-h  4">{{$item->course->name}}</h6>
                           </div>
                           <div class="category">
                             <div class="card-icon">
                               <i class="bi bi-list-check text-white"></i>
                             </div>
                             <span class="c-h-f">{{ $item->course->category ? $item->course->category->name : 'category' }}</span>
                           </div>
                           <div class="footer">
                             <div class="">
                               <a href="javascript:void(0)" class="sale_price text-left fs-7 text fw-semibold" tabindex="0">₹ {{$item->course->special_price}}</a>
                             </div>
                             <div class="mt-2">
                               <a href="{{route('course.handle', [$item->course->slug])}}" tabindex="0">
                                 <button class="btn btn-secondary btn-sm wishlist-btn" tabindex="0"> Add to cart </button>
                               </a>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>
                 @endif
               @endforeach
               @if(!$hasLiveCourse)
               <span>No items</span>
               @endif
             </div>
              </div>
              <div id="products" role="tabpanel" aria-labelledby="products-tab" class="tab-pane fade">
                @php
                $hasProduct = false;
                @endphp
                <div class="row mt-3">
                 @forelse($products as $key => $item)
                  @if($item->product && ($item->product->type==1))
                    @php
                    $hasProduct = true;
                    @endphp
                     <div class="col-md-3 primium-n ps-0">
                       <div class="card border-0 p-card">
                           <a href="{{route('product.handle', [$item->product->slug])}}" tabindex="0">
                           <img src="{{ $item->product && $item->product->image ? $item->product->image->image(200, 200) :no_image() }}" class="card-img-top" alt="{{$item->product->title}}">
                         </a>
                         <div class="card-body">
                           <button  data-wishlist_id="{{$item->id}}" class="remove_from_wishlist_btn tag cart-tag">
                               <i class="bi bi-trash pointer"></i>
                           </button>
                           <div class="name">
                             <img src="{{ $item->product && $item->product->image ? $item->product->image->image(50, 50) :no_image() }}" class="ins-img" alt="{{$item->product->title}}">
                             <h6 class="fw-bold c-h  4">{{$item->product->title}}</h6>
                           </div>
                           <div class="category">
                             <div class="card-icon">
                               <i class="bi bi-list-check text-white"></i>
                             </div>
                             <span class="c-h-f">{{ $item->product->category ? $item->product->category->name : 'category' }}</span>
                           </div>
                           <div class="footer">
                             <div class="">
                               <a href="javascript:void(0)" class="sale_price text-left fs-7 text fw-semibold" tabindex="0">₹ {{$item->product->sale_price}}</a>
                             </div>
                             <div class="mt-2">
                               <a href="{{route('product.handle', [$item->product->slug])}}" tabindex="0">
                                 <button class="btn btn-secondary btn-sm wishlist-btn" tabindex="0"> Add to cart </button>
                               </a>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>
                 @endif
               @endforeach
               @if(!$hasProduct)
               <span>No items</span>
               @endif
             </div>
              </div>
              <div id="services" role="tabpanel" aria-labelledby="services-tab" class="tab-pane fade">
                @php
                $hasService = false;
                @endphp
                <div class="row mt-3">
                 @forelse($products as $key => $item)
                  @if($item->product && ($item->product->type==3))
                    @php
                    $hasService = true;
                    @endphp
                     <div class="col-md-3 primium-n ps-0">
                       <div class="card border-0 p-card">
                           <a href="{{route('product.handle', [$item->product->slug])}}" tabindex="0">
                           <img src="{{ $item->product && $item->product->image ? $item->product->image->image(200, 200) :no_image() }}" class="card-img-top" alt="{{$item->product->title}}">
                         </a>
                         <div class="card-body">
                           <button  data-wishlist_id="{{$item->id}}" class="remove_from_wishlist_btn tag cart-tag">
                               <i class="bi bi-trash pointer"></i>
                           </button>
                           <div class="name">
                             <img src="{{ $item->product && $item->product->image ? $item->product->image->image(50, 50) :no_image() }}" class="ins-img" alt="{{$item->product->title}}">
                             <h6 class="fw-bold c-h  4">{{$item->product->title}}</h6>
                           </div>
                           <div class="category">
                             <div class="card-icon">
                               <i class="bi bi-list-check text-white"></i>
                             </div>
                             <span class="c-h-f">{{ $item->product->category ? $item->product->category->name : 'category' }}</span>
                           </div>
                           <div class="footer">
                             <div class="">
                               <a href="javascript:void(0)" class="sale_price text-left fs-7 text fw-semibold" tabindex="0">₹ {{$item->product->sale_price}}</a>
                             </div>
                             <div class="mt-2">
                               <a href="{{route('product.handle', [$item->product->slug])}}" tabindex="0">
                                 <button class="btn btn-secondary btn-sm wishlist-btn" tabindex="0"> Add to cart </button>
                               </a>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>
                 @endif
               @endforeach
               @if(!$hasService)
               <span>No items</span>
               @endif
             </div>
            </div>
          </div>
        </div>
    </div>
  </div>

@endsection
