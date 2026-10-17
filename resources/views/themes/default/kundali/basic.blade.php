<div class="card-header-main"> Your Astro Details</div>
 <div class="row">
   <div class="col-lg-4 col-md-4 kundli-section mt-3 mt-lg-0">
     <div class="card">
       <div class="card-header fw-semibold">Basic Details</div>
       <div class="card-body">
         <div class="row mb-2"><div class="col-6 kundli-label">Name</div><div class="col-6 kundli-value">{{$name}}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Date</div><div class="col-6 kundli-value">{{$date}}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Time</div><div class="col-6 kundli-value">{{$time}}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Place</div><div class="col-6 kundli-value">{{$place}}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Latitude</div><div class="col-6 kundli-value">{{$latitude}}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Longitude</div><div class="col-6 kundli-value">{{$longitude}}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Timezone</div><div class="col-6 kundli-value">{{$timezone}}</div></div>
         @php
          $panchang = null;
             if($response["panchang"]){
               $panchang=$response["panchang"];
             }

         @endphp
         @if($panchang)
         <div class="row mb-2"><div class="col-6 kundli-label">Sun Rise</div><div class="col-6 kundli-value">{{ $panchang["sunrise_at_birth"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Sun Set</div><div class="col-6 kundli-value">{{ $panchang["sunset_at_birth"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Ayanamsha</div><div class="col-6 kundli-value">{{ $panchang["ayanamsa_name"] }}</div></div>
         @endif
     </div>
     </div>
   </div>

     @if($response)
     @php
       $rasi=$response["rasi"];
     @endphp
     @if(isset($response["extended"]))
       @php
         $extended=$response["extended"];
       @endphp
     @endif
   <div class="col-lg-4 col-md-4 kundli-section mt-3 mt-lg-0">
     <div class="card">
       <div class="card-header fw-semibold">Astrological Details</div>
       <div class="card-body">
         <div class="row mb-2"><div class="col-6 kundli-label">Varna</div><div class="col-6 kundli-value">{{ $extended["varna"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Vashya</div><div class="col-6 kundli-value">{{ $extended["vasya"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Yoni</div><div class="col-6 kundli-value">{{ $extended["yoni"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Gan</div><div class="col-6 kundli-value">{{ $extended["gana"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Nadi</div><div class="col-6 kundli-value">{{ $extended["nadi"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Zodiac</div><div class="col-6 kundli-value">{{ $extended["rasi"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Zodiac Lord</div><div class="col-6 kundli-value">{{ $extended["rasi_lord"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Nakshatra</div><div class="col-6 kundli-value">{{ $extended["nakshatra"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Yog</div><div class="col-6 kundli-value">{{ $extended["yoga"]??'' }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Karan</div><div class="col-6 kundli-value">{{ $extended["karana"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Tithi</div><div class="col-6 kundli-value">{{ $extended["tithi"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Tatva</div><div class="col-6 kundli-value">{{ $extended["tatva"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Name alphabet</div><div class="col-6 kundli-value">{{ $extended["name_start"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Paya</div><div class="col-6 kundli-value">{{ $extended["paya"] }}</div></div>
       </div>
     </div>
   </div>
   @endif
     @if(isset($panchang))
     <div class="col-lg-4 col-md-4 kundli-section mt-3 mt-lg-0">
     <div class="card">
       <div class="card-header fw-semibold">Panchang Details</div>
       <div class="card-body">
         <div class="row mb-2"><div class="col-6 kundli-label">Tithi</div><div class="col-6 kundli-value">{{ $panchang["tithi"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Karana</div><div class="col-6 kundli-value">{{ $panchang["karana"] }}</div></div>
         <div class="row mb-2"><div class="col-6 kundli-label">Yog</div><div class="col-6 kundli-value">{{ $panchang["yoga"]??'' }}</div></div>
       </div>
     </div>
   </div>
   @endif

 </div>
