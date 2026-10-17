@extends(themeView() . 'layout')
@section('content')
<!-- Banner Section Start -->
@if(str_contains($_SERVER['HTTP_USER_AGENT'], 'Mobile'))
  @foreach($banners as $index => $banner)
    @if($index==0 && $banner->mobileImage)
      @section('image')
      {{$banner->mobileImage->image()}}
      @endsection
    @endif
    @if($banner->link)
      <a href="{{$banner->link}}">
        <section class="page-header about-page" style="background-image: url('{{$banner->mobileImage->image() ?: ''}}'); background-repeat: no-repeat; background-size: cover; background-position:center;">
          <div class="container">
            <div class="title_breadcrumb">
              <h1 class="text-center">Today Special</h1>
              <div class="text-center">
                @include(themeView().'common.breadcrumb')
              </div>
            </div>
          </div>
        </section>
      </a>
    @else
      <section class="page-header about-page" style="background-image: url('{{$banner->mobileImage->image() ?: ''}}'); background-repeat: no-repeat; background-size: cover; background-position:center;">
        <div class="container">
          <div class="title_breadcrumb">
            <h1 class="text-center">Today Special</h1>
            <div class="text-center">
              @include(themeView().'common.breadcrumb')
            </div>
          </div>
        </div>
      </section>
    @endif
  @endforeach
@else
  @foreach($banners as $index => $banner)
    @if($index==0 && $banner->image)
      @section('image')
      {{$banner->image->image()}}
      @endsection
    @endif
    @if($banner->link)
      <a href="{{$banner->link}}">
        <section class="page-header about-page" style="background-image: url('{{$banner->image->image(1200,   675) ?: ''}}'); background-repeat: no-repeat; background-size: cover; background-position:center;">
          <div class="container">
            <div class="title_breadcrumb">
              <h1 class="text-center">Today Special</h1>
              <div class="text-center">
                @include(themeView().'common.breadcrumb')
              </div>
            </div>
          </div>
        </section>
      </a>
    @else
      <section class="page-header about-page" style="background-image: url('{{$banner->image->image(1200,   675) ?: ''}}'); background-repeat: no-repeat; background-size: cover; background-position:center;">
        <div class="container">
          <div class="title_breadcrumb">
            <h1 class="text-center">Today Special</h1>
            <div class="text-center">
              @include(themeView().'common.breadcrumb')
            </div>
          </div>
        </div>
      </section>
    @endif
  @endforeach
@endif
<!-- Banner Section End -->
<section>
<!-- body-start -->
<div class="container my-4">
  <div class="row">
    {{--
    <div class="col-md-3 col-12 order-md-first order-last mt-3 mt-mb-0">
      <div class="main-filter-box">
        <div class="row">
          @foreach(ads('today') as $ad)
            <div class="col-md-12 col-6">
              <div class="mb-2">
                @if($ad->image)
                  <a href="{{$ad->link}}" target="_blank">
                    <img src="{{$ad->image->image(300)}}" alt="ad-image" class="w-100 mt-3">
                  </a>
                @else
                  {!! $ad->content !!}
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    --}}
    <!-- <div class="col-md-9 col-12 order-md-last order-first"> -->
    <div class="col-12">
      <!-- thought of the day start -->
      @if($thoughtoftheday)
        <div class="">
          <div class="btn-bg p-2 py-3 radius">
            <h5 class="fs-6 fw-bold text-light mb-0 p-0 text-center">Thought of the day</h5>
          </div>
          <div class="border radius px-2">
            <div class="row mb-2 mt-2">
              <div class="col-12">
                {!! $thoughtoftheday->english !!}
              </div>
            </div>
          </div>
        </div>
      @endif
   <!-- thought of the day end -->

      <div class="mt-3 btn-bg p-2 py-3 radius">
        <h5 class="fs-6 fw-bold text-light mb-0 p-0 text-center">Today's Punchang</h5>
      </div>
      <div class="border radius px-2">
        <form action="" method="post">
                 <div class="row py-3">
                    <div class="col-md-3 col-12">
                      <div class="form-group">
                          <label for="startDate">Start</label>
                          <input id="startDate" class="form-control mt-2" type="date" />
                        </div>
                      </div>

                        <div class="col-md-3 col-12">
                        <div class="form-group">
                          <label for="city">City Name</label>
                          <input id="city" class="form-control mt-2" type="text" placeholder="Enter City Name" />
                        </div>
                        </div>

                        <div class="col-md-3 col-12 d-flex align-items-end mt-md-0 mt-3">
                          <button type="" name="button" class="btn btn-secondary px-5 btn-height" id="todayButton">Today</button>
                        </div>

                          <div class="clock col-md-3 col-12 d-flex align-items-end justify-content-center">
                          <p id="clock" class="fw-bold m-0 p-0 fs-4"></p>
                          </div>

                      </div>
                      </form>
              <div class="row">
              <div class="col-md-3 col-6">
                <div class="pm-box border b-h">
                  <h5 class="p-0 m-0 fw-bold fs-5 text-orange text-center">Tithi <span>11</span> </h5>
                  <p  class="p-0 m-0 fs-7 mt-1 text-center">Chaturthi</p>
                  <p  class="p-0 m-0 fs-7 text-center">Start: Yesterday 01:12 PM</p>
                  <p  class="p-0 m-0 fs-7 text-center">END: Today 01:12 PM</p>
                </div>
              </div>
              <div class="col-md-3 col-6">
                <div class="pm-box border b-h">
                  <h5  class="p-0 m-0 fs-5 fw-bold text-orange text-center">Guruvara</h5>
                  <p  class="p-0 m-0 fs-7 mt-1 text-center">Dec. 05,2024</p>
                  <p  class="p-0 m-0 fs-7 text-center">Margashirsha-Sukla</p>
                </div>
             </div>

             <div class="col-md-3 col-6 mt-md-0 mt-2">
               <div class="pm-box  border b-h">
                 <h5 class="text-orange mb-2 fs-5 fw-bold text-center">Paksha</h5>
                 <p class="p-0 m-0 text-center fs-7">Ashtami</p>
               </div>
              </div>
               <div class="col-md-3 col-6 mt-md-0 mt-2">
                 <div class="pm-box border b-h">
                   <h5 class="text-orange mb-2 fs-5 fw-bold text-center">Day</h5>
                   <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                 </div>
             </div>
             </div>
             <div class="row mb-2">
              <div class="col-md-3 col-6">
                <div class="pm-box mt-2 border">
                  <h5 class="text-orange fs-5 fw-bold text-center">Tithi</h5>
                  <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                  <p class="p-0 m-0 fs-7 text-center"> Till - 08:00 PM</p>
                  <p class="p-0 m-0 fs-7 text-center">Next - Navami</p>
                </div>
               </div>
                <div class="col-md-3 col-6">
                  <div class="pm-box mt-2 border">
                    <h5 class="text-orange fs-5 fw-bold text-center">Yoga</h5>
                    <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                    <p class="p-0 m-0 fs-7 text-center"> Till - 08:00 PM</p>
                    <p class="p-0 m-0 fs-7 text-center">Next - Navami</p>
                  </div>
              </div>

              <div class="col-md-3 col-6">
                <div class="pm-box mt-2 border">
                  <h5 class="text-orange mb-2 fs-5 fw-bold text-center">Karana</h5>
                  <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                  <p class="p-0 m-0 fs-7 text-center"> Till - 08:00 PM</p>
                  <p class="p-0 m-0 fs-7 text-center">Next - Navami</p>
                </div>
               </div>
                <div class="col-md-3 col-6">
                  <div class="pm-box mt-2 border">
                    <h5 class="text-orange mb-2 fs-5 fw-bold text-center">Nakshtra</h5>
                    <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                    <p class="p-0 m-0 fs-7 text-center"> Till - 08:00 PM</p>
                    <p class="p-0 m-0 fs-7 text-center">Next - Navami</p>
                  </div>
              </div>
             </div>
             <!-- muhurat-start -->

             </div>


             <!-- muhurat-start -->
              <div class="mt-3">
                <div class="btn-bg p-2 py-3 radius">
                   <h5 class="fs-6 fw-bold text-light mb-0 p-0 text-center">Current Muhurat</h5>
                   </div>
                   <div class="border radius px-2">
                    <div class="row mb-2 mt-2">
                     <div class="col-md-3 col-6">
                       <div class="pm-box mt-2 border">
                         <h5 class="text-orange fs-5 fw-bold text-center">Hora</h5>
                         <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                         <p class="p-0 m-0 fs-7 text-center"> 08:00 AM to 05:24 PM</p>
                         <p class="p-0 m-0 fs-7 text-center">Auspicious for Romance and entertainment</p>
                       </div>
                      </div>
                       <div class="col-md-3 col-6">
                         <div class="pm-box mt-2 border">
                           <h5 class="text-orange fs-5 fw-bold text-center">Current Lagna</h5>
                           <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                           <p class="p-0 m-0 fs-7 text-center"> 08:00 AM to 05:24 PM</p>
                           <p class="p-0 m-0 fs-7 text-center">Auspicious for Romance and entertainment</p>
                         </div>
                     </div>

                     <div class="col-md-3 col-6">
                       <div class="pm-box mt-2 border">
                         <h5 class="text-orange mb-2 fs-5 fw-bold text-center">Do Ghati</h5>
                         <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                         <p class="p-0 m-0 fs-7 text-center"> 08:00 AM to 05:24 PM</p>
                         <p class="p-0 m-0 fs-7 text-center">Auspicious for Romance and entertainment</p>
                       </div>
                      </div>
                       <div class="col-md-3 col-6">
                         <div class="pm-box mt-2 border">
                           <h5 class="text-orange mb-2 fs-5 fw-bold text-center">Nakshtra</h5>
                           <p class="p-0 m-0 text-center fs-7">Ashtami</p>
                           <p class="p-0 m-0 fs-7 text-center"> 08:00 AM to 05:24 PM</p>
                           <p class="p-0 m-0 fs-7 text-center">Auspicious for Romance and entertainment</p>
                         </div>
                     </div>
                    </div>


                    </div>
                 </div>

          <!-- muhurat-end -->

          <!-- moon-start -->
          <div class="row">
            <div class="col-md-4 col-12">
              <div class="mt-3">
                <div class="btn-bg p-2 py-3 radius">
                   <h5 class="fs-6 fw-bold text-light mb-0 p-0 text-center">Sun and Moon Calculations</h5>
                   </div>
                   <div class="border radius px-2 time-box">
                    <div class="row mb-2 mt-2">
                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border">
                         <h5 class="text-orange fs-6 fw-bold text-center">Sun Rise</h5>
                         <p class="p-0 m-0 fs-7 text-center"> 06:00 AM</p>
                       </div>
                      </div>
                       <div class="col-md-6 col-6">
                         <div class="pm-box mt-2 border">
                           <h5 class="text-orange fs-6 fw-bold text-center">Sun Set</h5>
                           <p class="p-0 m-0 fs-7 text-center"> 05:00 PM</p>
                         </div>
                     </div>

                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border">
                         <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Moon Rise</h5>
                         <p class="p-0 m-0 fs-7 text-center">00:00 AM</p>
                       </div>
                      </div>
                       <div class="col-md-6 col-6">
                         <div class="pm-box mt-2 border">
                           <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Moon Set</h5>
                           <p class="p-0 m-0 fs-7 text-center">01:00 PM</p>
                         </div>
                     </div>
                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border">
                         <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Ritu</h5>
                         <p class="p-0 m-0 fs-7 text-center">Hemant</p>
                       </div>
                   </div>
                   <div class="col-md-6 col-6">
                     <div class="pm-box mt-2 border">
                       <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Moon Sign</h5>
                       <p class="p-0 m-0 fs-7 text-center">Simla</p>
                     </div>
                 </div>
                    </div>


                    </div>
                 </div>
            </div>
            <div class="col-md-4 col-12">
              <div class="mt-3">
                <div class="btn-bg p-2 py-3 radius">
                   <h5 class="fs-6 fw-bold text-light mb-0 p-0 text-center">Hindu Month and Year</h5>
                   </div>
                   <div class="border radius px-2 time-box">
                    <div class="row mb-2 mt-2">
                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange fs-6 fw-bold text-center">Shaka Samvat</h5>
                         <p class="p-0 m-0 fs-7 text-center"> 06:00 AM</p>
                       </div>
                      </div>
                       <div class="col-md-6 col-6">
                         <div class="pm-box mt-2 border b-he">
                           <h5 class="text-orange fs-6 fw-bold text-center">Day Duration</h5>
                           <p class="p-0 m-0 fs-7 text-center"> 05:00 PM</p>
                         </div>
                     </div>

                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Kali Samvat</h5>
                         <p class="p-0 m-0 fs-7 text-center">5126</p>
                       </div>
                      </div>
                       <div class="col-md-6 col-6">
                         <div class="pm-box mt-2 border b-he">
                           <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Vikram Samvat</h5>
                           <p class="p-0 m-0 fs-7 text-center">2081</p>
                         </div>
                     </div>
                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Month Amanta</h5>
                         <p class="p-0 m-0 fs-7 text-center">Kartika</p>
                       </div>
                   </div>
                   <div class="col-md-6 col-6">
                     <div class="pm-box mt-2 border b-he">
                       <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Month Purnimanta</h5>
                       <p class="p-0 m-0 fs-7 text-center">Margashirsha</p>
                     </div>
                 </div>
                    </div>


                    </div>
                 </div>
            </div>
            <div class="col-md-4 col-12">
              <div class="mt-3">
                <div class="btn-bg p-2 py-3 radius">
                   <h5 class="fs-6 fw-bold text-light mb-0 p-0 text-center">Auspicious Timings</h5>
                   </div>
                   <div class="border radius px-2 time-box">
                    <div class="row mb-2 mt-2">
                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange fs-6 fw-bold text-center">Shaka Samvat</h5>
                         <p class="p-0 m-0 fs-7 text-center"> 06:00 AM</p>
                       </div>
                      </div>
                       <div class="col-md-6 col-6">
                         <div class="pm-box mt-2 border b-he">
                           <h5 class="text-orange fs-6 fw-bold text-center">Day Duration</h5>
                           <p class="p-0 m-0 fs-7 text-center"> 05:00 PM</p>
                         </div>
                     </div>

                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Kali Samvat</h5>
                         <p class="p-0 m-0 fs-7 text-center">5126</p>
                       </div>
                      </div>
                       <div class="col-md-6 col-6">
                         <div class="pm-box mt-2 border b-he">
                           <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Vikram Samvat</h5>
                           <p class="p-0 m-0 fs-7 text-center">2081</p>
                         </div>
                     </div>
                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Month Amanta</h5>
                         <p class="p-0 m-0 fs-7 text-center">Kartika</p>
                       </div>
                   </div>
                   <div class="col-md-6 col-6">
                     <div class="pm-box mt-2 border b-he">
                       <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Month Purnimanta</h5>
                       <p class="p-0 m-0 fs-7 text-center">Margashirsha</p>
                     </div>
                 </div>
                    </div>


                    </div>
                 </div>
            </div>
            <div class="col-md-4 col-12">
              <div class="mt-3">
                <div class="btn-bg p-2 py-3 radius">
                   <h5 class="fs-6 fw-bold text-light mb-0 p-0 text-center">Inauspicious Timings</h5>
                   </div>
                   <div class="border radius px-2 time-box">
                    <div class="row mb-2 mt-2">
                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange fs-6 fw-bold text-center">Shaka Samvat</h5>
                         <p class="p-0 m-0 fs-7 text-center"> 06:00 AM</p>
                       </div>
                      </div>
                       <div class="col-md-6 col-6">
                         <div class="pm-box mt-2 border b-he">
                           <h5 class="text-orange fs-6 fw-bold text-center">Day Duration</h5>
                           <p class="p-0 m-0 fs-7 text-center"> 05:00 PM</p>
                         </div>
                     </div>

                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Kali Samvat</h5>
                         <p class="p-0 m-0 fs-7 text-center">5126</p>
                       </div>
                      </div>
                       <div class="col-md-6 col-6">
                         <div class="pm-box mt-2 border b-he">
                           <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Vikram Samvat</h5>
                           <p class="p-0 m-0 fs-7 text-center">2081</p>
                         </div>
                     </div>
                     <div class="col-md-6 col-6">
                       <div class="pm-box mt-2 border b-he">
                         <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Month Amanta</h5>
                         <p class="p-0 m-0 fs-7 text-center">Kartika</p>
                       </div>
                   </div>
                   <div class="col-md-6 col-6">
                     <div class="pm-box mt-2 border b-he">
                       <h5 class="text-orange mb-2 fs-6 fw-bold text-center">Month Purnimanta</h5>
                       <p class="p-0 m-0 fs-7 text-center">Margashirsha</p>
                     </div>
                 </div>
                    </div>


                    </div>
                 </div>
            </div>
            <div class="col-md-4 col-12">
              <div class="mt-3">
                <div class="btn-bg p-2 py-3 radius">
                   <h5 class="fs-6 fw-bold text-light mb-0 p-0 text-center">Tarabalam and Chandra Bala</h5>
                   </div>
                   <div class="border radius px-2 time-box">
                     <h5 class="fs-5 mt-2 text-orange">Tarabalam</h5>
                    <p class="fs-7">Ashwini, Bharani, Kritika, Mrigashirsha, Punarvasu, Ashlesha, Magha, Poorva Phalguni, Uttara Phalguni, Chitra, Vishakha, Jyeshta, Moola, Poorva Ashadha, Uttara Ashadha, Dhanishta, Poorva Bhadrapada, Revati</p>

                    <h5 class="fs-5 mt-2 text-orange">Chandra Bala</h5>
                   <p class="fs-7">Mithuna, Simha, Tula, Vrishchika, Kumbha, Meena</p>
                    <h5 class="text-orange">Disha Shoola</h5>
                     <p>East</p>
                    </div>
                 </div>
            </div>
          </div>

          <div class="row">
            @foreach(ads('today') as $ad)
              <div class="col-md-3 col-12">
                <div class="mb-2">
                  @if($ad->image)
                    <a href="{{$ad->link}}" target="_blank">
                      <img src="{{$ad->image->image(300)}}" alt="ad-image" class="w-100 mt-3">
                    </a>
                  @else
                    {!! $ad->content !!}
                  @endif
                </div>
              </div>
            @endforeach
          </div>


             <!-- hindu-month-year -->


</section>
@include(themeView().'common.adpopup', ['page'=>'today'])
@endsection
