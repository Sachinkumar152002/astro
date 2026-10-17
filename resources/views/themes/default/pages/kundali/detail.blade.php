
@extends(themeView().'layout')

@section('title', "Kundli | ASTROVYRA")
@section('keywords', "Kundli | ASTROVYRA")
@section('description', "Kundli | ASTROVYRA")

@push('style')

@endpush

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"Kundali Deatil",
    "description":"View your complete Kundali details with planetary positions, houses, and horoscope insights.",
    "inLanguage":"en-IN",
    "isPartOf":{
      "@id":"{{ url('/') }}/#website"
    },
    "about":{
      "@id":"{{ url('/') }}/#org"
    },
    "datePublished":"{{ date('Y-m-d',strtotime($kundaliEnquiry->created_at)) }}",
    "dateModified":"{{ date('Y-m-d',strtotime($kundaliEnquiry->updated_at)) }}"
  }
  </script>
@endpush

@push('header_script')
@endpush


<!-- banner-section-start -->
@section('content')
<section class="page-header about-page" style="background-image: url('{{$page->bannerImage ? $page->bannerImage->image() : ''}}'); background-repeat: no-repeat; width: 100%; background-size: cover;">
  <div class="container">

    <div class="head-sec">
      <h1 class="text-center text-white">Kundali</h1>
      @include(themeView().'common.breadcrumb')
      <p class="text-center text-white">Discover the secrets of the stars with our personalized astrology readings. Explore your zodiac sign, daily horoscopes, and celestial insights. Unlock the mysteries of the universe and find your true path.</p>
    </div>
  </div>
</section>
<div class="">
  @php
    $svg=''
  @endphp
</div>
<section class="pb-4 pb-md-3 pb-lg-4">
  <div class="container py-4">

    <div class="row">
      <div class="col-lg-3 mb-3">
        <ul class="nav flex-column sidebar-tabs astrology-sidebar" id="kundliTabs" role="tablist">
          <li class="nav-item">
            <button class="nav-link active" onclick="handleTab(this,'basic')" id="basic-tab">Astro Profile</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="kundli-tab" onclick="handleTab(this,'kundli')">Kundli </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="kp-tab" onclick="handleTab(this,'kp')">KP</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="ashtak-tab" onclick="handleTab(this,'ashtak')">Ashtakvarga</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="charts-tab" onclick="handleTab(this,'charts')">Kundli Charts</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="dasha-tab" onclick="handleTab(this,'dasha')">Dasha</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="freeReport-tab" onclick="handleTab(this,'freeReport')">Free Report</button>
          </li>
        </ul>
      </div>
      <div class="col-lg-9">
        <div class="tab-content" id="kundliTabContent">
          <div class="tab-pane fade show active" id="basic" role="tabpanel">
            <div class="card-header-main"> Your Astro Details</div>

            <div class="row">
              <div class="col-lg-6 kundli-section">
                <div class="card">
                  <div class="card-header fw-semibold">Basic Details</div>
                  <div class="card-body">
                    <div class="row mb-2"><div class="col-6 kundli-label">Name</div><div class="col-6 kundli-value">{{ $data["name"] }}</div></div>
                    <div class="row mb-2"><div class="col-6 kundli-label">Date</div><div class="col-6 kundli-value">{{ $data["date"] }}</div></div>
                    <div class="row mb-2"><div class="col-6 kundli-label">Time</div><div class="col-6 kundli-value">{{ $data["time"] }}</div></div>
                    <div class="row mb-2"><div class="col-6 kundli-label">Place</div><div class="col-6 kundli-value">{{ $data["place"] }}</div></div>
                    <div class="row mb-2"><div class="col-6 kundli-label">Latitude</div><div class="col-6 kundli-value">{{ $data["latitude"] }}</div></div>
                    <div class="row mb-2"><div class="col-6 kundli-label">Longitude</div><div class="col-6 kundli-value">{{ $data["longitude"] }}</div></div>
                    <div class="row mb-2"><div class="col-6 kundli-label">Timezone</div><div class="col-6 kundli-value">{{ $data["timezone"] }}</div></div>

                    @php
                      if($response=$data["response"]){
                        if($response["panchang"]){
                          $panchang=$response["panchang"];
                        }
                      }
                    @endphp

                    @if(isset($panchang))
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

    <div class="col-lg-6 kundli-section">
      <div class="card">
        <div class="card-header fw-semibold">Avakhada Details</div>
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
    <div class="col-lg-6 kundli-section">
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
  </div>
  <div class="tab-pane fade" id="kundli" role="tabpanel">
    <div class="card-header-main">
      Kundli
    </div>
       <div class="row text-center gy-4 description">
         <!-- planets chart -->
       </div>
       <!-- Planet Table (example static content) -->
       <h5 class="mt-4 card-header-main">Planet Positions</h5>
       <table class="table table-bordered table-sm">
         <thead class="table-light">
           <tr>
             <th>Planet</th>
             <th>Zodiac</th>
             <th>Zodiac Lord</th>
             <th>Nakshatra</th>
             <th>Nakshatra Lord</th>
             <th>Degree</th>
             <th>Retro(R)</th>
             <th>Combust</th>
             <th>Avastha</th>
             <th>House</th>
             <th>Status</th>
           </tr>
         </thead>
         @php
          $rullingPlanet=[];
         @endphp
         <tbody>
           @for($i=0;$i<10;$i++)
            @if(isset($response["$i"]))
              @php
                $planet=$response["$i"];
                if($planet["house"]==1){
                  $rullingPlanet[]=$planet;
                }
              @endphp
             <tr>
               <td>{{ $planet["full_name"] }}</td>
               <td>{{ $planet["zodiac"] }}</td>
               <td>{{ $planet["zodiac_lord"] }}</td>
               <td>{{ $planet["nakshatra"] }}</td>
               <td>{{ $planet["nakshatra_lord"] }}</td>
               <td>{{ $planet["local_degree"] }}</td>
               <td>{{ isset($planet["retro"])?"Direct":"Retro" }}</td>
               <td>{{ isset($planet["is_combust"]) && $planet["is_combust"]?"Yes":"No" }}</td>
               <td>{{ $planet ["basic_avastha"] }}</td>
               <td>{{ $planet["house"] }}</td>
               <td>{{ $planet["lord_status"] }}</td>
             </tr>
            @endif
          @endfor
           <!-- Add more rows as needed -->
         </tbody>
       </table>
       <h5 class="mt-4 card-header-main">Dasha</h5>
       <ul class="nav nav-tabs mb-4" id="kundliTabTabs" role="tablist">
         <li class="nav-item">
           <button class="nav-link active" onclick="handleKundliTab(this,'mahadasha')" id="mahadasha-tab">Mahadasha</button>
         </li>
         <li class="nav-item">
           <button class="nav-link" onclick="handleKundliTab(this,'antardasha')" id="antardasha-tab">Antardasha</button>
         </li>
         <li class="nav-item">
           <button class="nav-link" onclick="handleKundliTab(this,'pratyantardasha')" id="pratyantardasha-tab">Pratyantardasha</button>
         </li>
         <li class="nav-item d-none">
           <button class="nav-link" onclick="handleKundliTab(this,'sookshmadasha')" id="sookshmadasha-tab">Sookshmadasha</button>
         </li>
       </ul>
       <!-- Tab Content -->
       <div class="tab-content" id="kundliTabTabContent">
         <div class="tab-pane fade show active" id="mahadasha" role="tabpanel">
         </div>
         <div class="tab-pane fade" id="antardasha" role="tabpanel">
         </div>
         <div class="tab-pane fade" id="pratyantardasha" role="tabpanel">
         </div>
         <div class="tab-pane fade" id="sookshmadasha" role="tabpanel">
         </div>
       </div>
     </div>
     <div class="tab-pane fade" id="kp" role="tabpanel">
         <div class="row text-center gy-4 description">
           <!-- planets chart -->
         </div>
         <div class="row align-items-start gy-4 mt-4">
           <!-- Ruling Planets Table -->
           <div class="col-12">
             <h5 class="card-header-main">Ruling Planets</h5>
             <div class="table-responsive">
               <table class="table table-bordered table-sm">
                 <thead style="background-color: #fffcd9;">
                   <tr>
                     <th>--</th>
                     <th>Sign Lord</th>
                     <th>Star Lord</th>
                     <th>Sub Lord</th>
                   </tr>
                 </thead>
                 <tbody>
                   @foreach($rullingPlanet as $key => $item)
                     <tr>
                       <td>{{ $item["name"] }}</td>
                       <td>{{ $item["zodiac_lord"] }}</td>
                       <td>{{ $item["nakshatra_lord"] }}</td>
                       <td>{{ $item["zodiac"] }}</td>
                     </tr>
                   @endforeach
                   @if(isset($panchang))
                     <tr>
                       <td colspan="3" class="text-end fw-bold">Day Lord</td>
                       <td>{{ $panchang["day_lord"] }}</td>
                     </tr>
                   @endif
                 </tbody>
               </table>
             </div>
           </div>
           <div class="col-12 mt-4 kp-p" id="kp_planet">
             <!-- planets table -->
           </div>
         </div>
       </div>
       <div class="tab-pane fade" id="ashtak" role="tabpanel">
       <h5 class="card-header-main">Ashtakvarga Chart</h5>
       <p class="text-muted">
         Ashtakvarga is used to assess the strength and patterns that are present in a birth chart. The Ashtakavarga or Ashtakavarga is a numerical quantification or score of each planet placed in the chart with reference to the other 7 planets and the Lagna. In Sarva Ashtaka Varga the total scores of all the BAVs are overlaid and then totalled. This makes the SAV of the chart. The total of all the scores should be 337.
       </p>
       <div class="row text-center gy-4 description">
         <!-- planets chart -->
       </div>
     </div>
  <div class="tab-pane fade" id="charts" role="tabpanel">
    <div class="row mt-3">
      <div class="col-12">
        <div class="card">
          <div class="card-header-main">Charts / Divisions</div>
          <div class="card-body">
            <div class="row text-center gy-4 description">
              @if(isset($response["divisions"]))
                @foreach($response["divisions"] as $div)
                  <div class="col-md-6 mb-3">
                    <div class="card">
                      <div class="card-header fw-semibold">{{ $div["title"] }}</div>
                      <div class="card-body">
                        <img src="{{ $div['image'] }}" class="img-fluid" alt="{{ $div['title'] }}">
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane fade" id="dasha" role="tabpanel">
    <ul class="nav nav-tabs mb-3" id="dashaTabTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" onclick="handleDashaTab(this,'vimshottari')" id="vimshottari-tab">Vimshottari</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" onclick="handleDashaTab(this,'yogini')" id="yogini-tab">Yogini</button>
      </li>
    </ul>
    <div class="tab-content" id="dashaTabTabsContent">
      <div class="tab-pane fade show active" id="vimshottari">
        <h5 class="mb-3">Understanding your dasha</h5>
        <div class="description">
          @if(isset($response["dasha"]["vimshottari"]))
            {!! $response["dasha"]["vimshottari"] !!}
          @else
            <p class="text-muted">Details not available.</p>
          @endif
        </div>
      </div>
      <div class="tab-pane fade" id="yogini">
        <h5 class="mb-3">Yogini Dasha - Dhanya</h5>
        <p class="text-muted">
          The Dhanya Yogini period brings prosperity, growth in finances, and successful communication. It's often associated with gaining respect, recognition, and a more fulfilled domestic life.
        </p>
        <div class="description">
          @if(isset($response["dasha"]["yogini"]))
            {!! $response["dasha"]["yogini"] !!}
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane fade" id="freeReport" role="tabpanel">
    <ul class="nav nav-tabs mb-3" id="freeReportMainTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" onclick="handleFreeReportMainTab(this,'general','subGeneral-tab','planetary-tab')" id="general-tab">General</button>
      </li>
      {{-- Remedies commented in original --}}
      <li class="nav-item">
        <button class="nav-link" onclick="handleFreeReportMainTab(this,'dosha','manglik-tab','kalsarpa-tab')" id="dosha-tab">Dosha</button>
      </li>
    </ul>
    <div class="tab-content" id="freeReportMainTabsContent">
      <div class="tab-pane fade show active" id="general" role="tabpanel">
        <ul class="nav nav-pills mb-3" id="generalSubTabs" role="tablist">
          <li class="nav-item">
            <button class="nav-link active" id="subGeneral-tab" data-bs-toggle="tab" data-bs-target="#subGeneral" type="button" role="tab">General</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="planetary-tab" data-bs-toggle="tab" data-bs-target="#planetary" type="button" role="tab">Planetary</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="yoga-tab" data-bs-toggle="tab" data-bs-target="#yoga" type="button" role="tab">Yoga</button>
          </li>
        </ul>
        <div class="tab-content" id="generalSubTabsContent">
          <div class="tab-pane fade show active" id="subGeneral" role="tabpanel">
            <div class="description">
              @if(isset($response["free_report"]["general"]))
                {!! $response["free_report"]["general"] !!}
              @endif
            </div>
          </div>
          <div class="tab-pane fade" id="planetary" role="tabpanel">
            <div class="description">
              @if(isset($response["free_report"]["planetary"]))
                {!! $response["free_report"]["planetary"] !!}
              @endif
            </div>
          </div>
          <div class="tab-pane fade" id="yoga" role="tabpanel">
            <div class="description">
              @if(isset($response["free_report"]["yoga"]))
                {!! $response["free_report"]["yoga"] !!}
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="dosha" role="tabpanel">
                <ul class="nav nav-pills mb-3" id="doshaSubTabs" role="tablist">
                  <li class="nav-item">
                    <button class="nav-link active" id="manglik-tab" data-bs-toggle="tab" data-bs-target="#manglik" type="button" role="tab">Manglik</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" id="kalsarpa-tab" data-bs-toggle="tab" data-bs-target="#kalsarpa" type="button" role="tab">Kalsarpa</button>
                  </li>
                  <li class="nav-item">
                    <button class="nav-link" id="sadesati-tab" data-bs-toggle="tab" data-bs-target="#sadesati" type="button" role="tab">Sadesati</button>
                  </li>
                </ul>

                <div class="tab-content" id="doshaSubTabsContent">
                <div class="tab-pane fade show active" id="manglik" role="tabpanel">
                  <div class="description">
                    @if(isset($response["free_report"]["dosha"]["manglik"]))
                      {!! $response["free_report"]["dosha"]["manglik"] !!}
                    @endif
                  </div>
                </div>
                  <div class="tab-pane fade" id="kalsarpa" role="tabpanel">
                    <div class="description">
                      @if(isset($response["free_report"]["dosha"]["kalsarpa"]))
                        {!! $response["free_report"]["dosha"]["kalsarpa"] !!}
                      @endif
                    </div>
                  </div>
                    <div class="tab-pane fade" id="sadesati" role="tabpanel">
                      <div class="description">
                        @if(isset($response["free_report"]["dosha"]["sadesati"]))
                          {!! $response["free_report"]["dosha"]["sadesati"] !!}
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
    @endsection

@push('scripts')
<script type="text/javascript">
  let lang=@json($kundaliEnquiry->lang);
  function handleTab(that,targetId){
    $("#kundliTabs").find(".nav-link").removeClass("active");
    $(that).addClass("active");
    let tabContainer=$(`#${targetId}`);
    $("#kundliTabContent").find(".tab-pane").removeClass("show active");
    tabContainer.addClass("show active");
    const functionName=`handle${targetId.charAt(0).toUpperCase()+targetId.slice(1)}Container`;
    if(typeof window[functionName] === 'function' && tabContainer.find(".description").children().length==0){
      window[functionName](targetId);
    }
    handleKundliTab('#mahadasha-tab','mahadasha');
    handleDashaTab("#vimshottari-tab","vimshottari");
  }
  let planetDetailId=@json($planetDetail?$planetDetail->id:0);
  function handleKundliContainer(id){
    $('#loader-web').show();
    let divArray=[{"div":"D1","name":"Lagna/Ascendant/Basic Birth Chart"},
    {"div":"D9","name":"Navamsa"}];
    for(let i=0;i<divArray.length;i++){
      divArray[i].planet_detail_id=planetDetailId;
      $.ajax({
          url: "{{ route('kundali.chart') }}",
          method: "POST",
          data: divArray[i],
          headers:{
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            if(response.svg){
              var div = $(`<div class="col-12 col-md-6 mt-5"><h6>${divArray[i].name}</h6><div>${response.svg}</div></div>`);
              $(`#${id}`).find(".description").append(div);
            }
            $('#loader-web').hide();
          },
          error: function(xhr){
            console.log(xhr)
          }
      });
    }
    handleKundliTabContainer();
  }

  function handleKundliTab(that,targetId){
    $("#kundliTabTabs").find(".nav-link").removeClass("active");
    $(that).addClass("active");
    let tabContainer=$(`#${targetId}`);
    $("#kundliTabTabContent").find(".tab-pane").removeClass("show active");
    tabContainer.addClass("show active");
  }

  function handleKundliTabContainer(){
    let dashaArray=[{"dasha":"mahadasha","url":"{{ route('kundali.mahadasha') }}"},
      {"dasha":"antardasha","url":"{{ route('kundali.antardasha') }}"},
      {"dasha":"pratyantardasha","url":"{{ route('kundali.pratyantardasha') }}"},
      // {"dasha":"sookshmadasha","url":"{{ route('kundali.sookshmadasha') }}"}
    ];
    for(let i=0;i<dashaArray.length;i++){
      dashaArray[i].planet_detail_id=planetDetailId;
      $.ajax({
          url: dashaArray[i].url,
          method: "POST",
          data: dashaArray[i],
          headers:{
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            if(response.html){
              $(`#${dashaArray[i].dasha}`).html(response.html);
            }
          },
          error: function(xhr){
            console.log(xhr)
          }
      });
    }
  }

  function handleKpContainer(id){
  $('#loader-web').show();
    let divArray=[{"div":"D1","name":"Lagna/Ascendant/Basic Birth Chart","url":"{{ route('kundali.chart') }}"},
    {"div":"chalit","name":"Bhav-chalit","url":"{{ route('kundali.chart') }}"},
    {"id":"kp_planet","name":"Planet Positions","url":"{{ route('kundali.kp.planet') }}"}];
    for(let i=0;i<divArray.length;i++){
      divArray[i].planet_detail_id=planetDetailId;
      $.ajax({
          url: divArray[i].url,
          method: "POST",
          data: divArray[i],
          headers:{
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            if(response.svg){
              var div = $(`<div class="col-12 col-md-6"><h6>${divArray[i].name}</h6><div>${response.svg}</div></div>`);
              $(`#${id}`).find(".description").append(div);
            }else if(response.html){
              $(`#${divArray[i].id}`).html(response.html);
            }
            $('#loader-web').hide();
          },
          error: function(xhr){
            console.log(xhr)
          }
      });
    }
  }

  function handleChartsContainer(id, divArray=[]){
    $('#loader-web').show();
    if(divArray.length==0){
      divArray=[{"div":"D1","name":"Lagna/Ascendant/Basic Birth Chart"},
      {"div":"D3","name":"Dreshkana"},
      {"div":"D3-s","name":"D3-Somanatha"},
      {"div":"D7","name":"Saptamsa"},
      {"div":"D9","name":"Navamsa"},
      {"div":"D10","name":"Dasamsa"},
      {"div":"D10-R","name":"Dasamsa-EvenReverse"},
      {"div":"D12","name":"Dwadasamsa"},
      {"div":"D16","name":"Shodashamsa"},
      {"div":"D20","name":"Vimsamsa"},
      {"div":"D24","name":"ChaturVimshamsha"},
      {"div":"D24-R","name":"D24-R"},
      {"div":"D30","name":"Trimshamsha"},
      {"div":"D40","name":"KhaVedamsa"},
      {"div":"D45","name":"AkshaVedamsa"},
      {"div":"D60","name":"Shastiamsha"},
      {"div":"chalit","name":"Bhav-chalit"},
      {"div":"moon","name":"Moon chart"},
      {"div":"sun","name":"Sun chart"}];
    }
    for(let i=0;i<divArray.length;i++){
      divArray[i].planet_detail_id=planetDetailId;
      $.ajax({
          url: "{{ route('kundali.chart') }}",
          method: "POST",
          data: divArray[i],
          headers:{
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            if(response.svg){
              var div = $(`<div class="col-12 col-md-6"><h6>${divArray[i].name}</h6><div>${response.svg}</div></div>`);
              $(`#${id}`).find(".description").append(div);
            }
            $('#loader-web').hide();
          },
          error: function(xhr){
            console.log(xhr)
          }
      });
    }
  }

  function handleAshtakContainer(id){
    $('#loader-web').show();
    let planetArray=[{"planet":"Sun","name":"Sun"},
    {"planet":"Moon","name":"Moon"},
    {"planet":"Mercury","name":"Mercury"},
    {"planet":"Venus","name":"Venus"},
    {"planet":"Mars","name":"Mars"},
    {"planet":"Jupiter","name":"Jupiter"},
    {"planet":"Saturn","name":"Saturn"},
    {"planet":"Rahu","name":"Rahu"},
    {"planet":"Ketu","name":"Ketu"}]
    for(let i=0;i<planetArray.length;i++){
      planetArray[i].planet_detail_id=planetDetailId;
      $.ajax({
          url: "{{ route('kundali.akvarga.chart') }}",
          method: "POST",
          data: planetArray[i],
          headers:{
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            if(response.svg){
              var div = $(`<div class="col-12 col-md-6"><h6>${planetArray[i].name}</h6><div>${response.svg}</div></div>`);
              $(`#${id}`).find(".description").append(div);
            }
            $('#loader-web').hide();
          },
          error: function(xhr){
            console.log(xhr)
          }
      });
    }
  }

  function handleDashaTab(that,targetId){
    $("#dashaTabTabs").find(".nav-link").removeClass("active");
    $(that).addClass("active");
    let tabContainer=$(`#${targetId}`);
    $("#dashaTabTabsContent").find(".tab-pane").removeClass("show active");
    tabContainer.addClass("show active");
  }

  function handleDashaContainer(){
    $('#loader-web').show();
    let dashaArray=[{"dasha":"vimshottari","url":"{{ route('kundali.dasha.vimshottari') }}"},
    {"dasha":"yogini","url":"{{ route('kundali.dasha.yogini') }}"}];
    for(let i=0;i<dashaArray.length;i++){
      dashaArray[i].planet_detail_id=planetDetailId;
      $.ajax({
          url: dashaArray[i].url,
          method: "POST",
          data: dashaArray[i],
          headers:{
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            if(response.html){
              $(`#${dashaArray[i].dasha}`).find('.description').html(response.html);
            }
            $('#loader-web').hide();
          },
          error: function(xhr){
            console.log(xhr)
          }
      });
    }
  }

  function handleFreeReportContainer(){
    handleFreeReportMainTab('#general-tab','general','subGeneral-tab','planetary-tab');
  }

  function handleFreeReportMainTab(that,targetId,subTargetId,subTargetTempId){
    $("#freeReportMainTabs").find(".nav-link").removeClass("active");
    $(that).addClass("active");
    let tabContainer=$(`#${targetId}`);
    $("#freeReportMainTabsContent").find(".tab-pane").removeClass("show active");
    tabContainer.addClass("show active");
    let setTimeOutTimer=100;
    const functionName=`handle${targetId.charAt(0).toUpperCase()+targetId.slice(1)}Container`;
    if(typeof window[functionName] === 'function' && tabContainer.find(".description").children().length==0){
      window[functionName](targetId);
      let setTimeOutTimer=3000;
    }
    setTimeout(function(){
      $(`#${subTargetTempId}`).click();
      $(`#${subTargetId}`).click();
    }, setTimeOutTimer);
  }

  function handleGeneralContainer(){
    $('#loader-web').show();
    let dashaArray=[{"id":"subGeneral","url":"{{ route('kundali.report.general') }}","zodiac":@json($rasi),"timeFrame":"yearly",'lang':lang},
    {"id":"planetary","url":"{{ route('kundali.report.planetary') }}",'lang':lang},
    {"id":"yoga","url":"{{ route('kundali.report.yoga') }}",'lang':lang}];
    for(let i=0;i<dashaArray.length;i++){
      dashaArray[i].planet_detail_id=planetDetailId;
      $.ajax({
          url: dashaArray[i].url,
          method: "POST",
          data: dashaArray[i],
          headers:{
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            if(response.html){
              $(`#${dashaArray[i].id}`).find('.description').html(response.html);
            }
            $('#loader-web').hide();
          },
          error: function(xhr){
            console.log(xhr)
          }
      });
    }
  }

  function handleDoshaContainer(){
    $('#loader-web').show();
    let dashaArray=[{"id":"manglik","url":"{{ route('kundali.report.manglik') }}"},
    {"id":"kalsarpa","url":"{{ route('kundali.report.kalsarpa') }}"},
    {"id":"sadesati","url":"{{ route('kundali.report.sadesati') }}"}];
    for(let i=0;i<dashaArray.length;i++){
      dashaArray[i].planet_detail_id=planetDetailId;
      $.ajax({
          url: dashaArray[i].url,
          method: "POST",
          data: dashaArray[i],
          headers:{
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            if(response.html){
              $(`#${dashaArray[i].id}`).find('.description').html(response.html);
            }
            $('#loader-web').hide();
          },
          error: function(xhr){
            console.log(xhr)
          }
      });
    }
  }
</script>
<script>
/**
 * handleTab: toggles top-level tabs (basic, kundli, kp, ashtak, charts, dasha, freeReport, etc.)
 * NOTE: This preserves all your original IDs and simply toggles the .show/.active classes.
 */
function handleTab(btn, paneId) {
  try {
    // 1) sidebar buttons: deactivate all in kundliTabs
    const tabList = document.querySelectorAll('#kundliTabs .nav-link');
    tabList.forEach(b => b.classList.remove('active'));

    // 2) activate clicked button
    if (btn && btn.classList) btn.classList.add('active');

    // 3) hide all top-level panes (inside #kundliTabContent)
    const panes = document.querySelectorAll('#kundliTabContent > .tab-pane');
    panes.forEach(p => p.classList.remove('show','active'));

    // 4) show the target pane
    const target = document.getElementById(paneId);
    if (target) target.classList.add('show','active');

    // Accessibility: focus target
    if (target && typeof target.focus === 'function') {
      target.setAttribute('tabindex','-1');
      target.focus();
    }
  } catch (e) {
    console.error('handleTab error', e);
  }
}
@endpush
