@extends(themeView().'layout')
@section('title', 'Kundali | '.config("app.name"))

@push('schema')
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"WebPage",
    "@id":"{{ url()->current() }}#webpage",
    "url":"{{ url()->current() }}",
    "name":"Kundali",
    "description":"Get your free online Kundali with accurate horoscope, birth chart, and planetary positions.",
    "inLanguage":"en-IN",
    "isPartOf":{
      "@id":"{{ url('/') }}/#website"
    },
    "about":{
      "@id":"{{ url('/') }}/#org"
    },
    "datePublished":"{{ date('Y-m-d') }}",
    "dateModified":"{{ date('Y-m-d') }}"
  }
  </script>
@endpush

@push("style")
<link rel="stylesheet" href="{{asset('assets/css/kundali.css')}}">
@endpush
@section('content')
<section class="page-header about-page mb-md-0 kundlipage" style="background-image: url('{{asset('/media/56/1758358754.jpg')}}'); background-repeat: no-repeat; width: 100%; background-size: cover;">
  <div class="container">
    <div class="head-sec">
      <h1 class="text-center text-white">Kundali</h1>
        @include(themeView().'common.breadcrumb')
      <p class="text-center text-white">Discover the secrets of the stars with our personalized astrology readings. Explore your zodiac sign, daily horoscopes, and celestial insights. Unlock the mysteries of the universe and find your true path.</p>
    </div>
  </div>
</section>
<div class="">
  </div>

<section class="kundali-report-section pb-4 pb-md-3 pb-lg-4">
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <!-- Horizontal Tabs -->
        <ul class="nav nav-tabs kundli-horizontal-tabs mb-4" id="kundliTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="true">
              <i class="fas fa-th-large me-2"></i>Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab" aria-controls="basic" aria-selected="true" onclick="handleTab(this,'basic')">
              <i class="fas fa-user-circle me-2"></i>Basic Details
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="kundali-tab" data-bs-toggle="tab" data-bs-target="#kundali" type="button" role="tab" aria-controls="kundali" aria-selected="true" onclick="handleTab(this,'kundali')">
              <i class="fas fa-star me-2"></i>Kundali
            </a>
          </li>
          <li class="nav-item">
             <a class="nav-link" id="divisional-tab" data-bs-toggle="tab" data-bs-target="#divisional" type="button" role="tab" aria-controls="divisional" aria-selected="true" onclick="handleTab(this,'divisional')">
              <i class="fas fa-star me-2"></i>Divisional Charts
            </a>
          </li>
          <li class="nav-item">
             <a class="nav-link" id="transit-tab" data-bs-toggle="tab" data-bs-target="#transit" type="button" role="tab" aria-controls="transit" aria-selected="true" onclick="handleTab(this,'transit')">
              <i class="fas fa-star me-2"></i>Transit (Gochar) Today</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="ashtakvarga-tab" data-bs-toggle="tab" data-bs-target="#ashtakvarga" type="button" role="tab" aria-controls="ashtakvarga" aria-selected="true" onclick="handleTab(this,'ashtakvarga')">
              <i class="fas fa-star me-2"></i>Ashtakvarga</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="dasha-tab" data-bs-toggle="tab" data-bs-target="#dasha" type="button" role="tab" aria-controls="dasha" aria-selected="true" onclick="handleTab(this,'dasha')">
              <i class="fas fa-star me-2"></i> Dasha</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="dosha-tab" data-bs-toggle="tab" data-bs-target="#dosha" type="button" role="tab" aria-controls="dosha" aria-selected="true"  onclick="handleTab(this,'dosha')">
              <i class="fas fa-star me-2"></i> Dosha</a>

          </li>

          <li class="nav-item">
             <a class="nav-link" id="gem-tab" data-bs-toggle="tab" data-bs-target="#gem" type="button" role="tab" aria-controls="gem" aria-selected="true" onclick="handleTab(this,'gem')">
              <i class="fas fa-star me-2"></i> Gem Suggestion</a>

          </li>
          <li class="nav-item">
            <a class="nav-link" id="rudraksha-tab" data-bs-toggle="tab" data-bs-target="#rudraksha" type="button" role="tab" aria-controls="rudraksha" aria-selected="true" onclick="handleTab(this,'rudraksha')">
              <i class="fas fa-star me-2"></i> Rudraksha Suggestion</a>
          </li>

        </ul>
      </div>
      <div class="col-12">
        <div class="tab-content" id="kundliTabContent">
          <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
            @include(themeView().'kundali.dashboard')
          </div>
          <div class="tab-pane fade" id="basic" role="tabpanel">

          </div>
          <div class="tab-pane fade" id="kundali" role="tabpanel">
            @include(themeView().'kundali.kundali')
          </div>
          <div class="tab-pane fade" id="divisional" role="tabpanel">
            @include(themeView().'kundali.divisional')
          </div>
          <div class="tab-pane fade" id="transit" role="tabpanel">
            @include(themeView().'kundali.transit')
          </div>
          <div class="tab-pane fade" id="ashtakvarga" role="tabpanel"></div>
          <div class="tab-pane fade" id="dasha" role="tabpanel">
            @include(themeView().'kundali.dasha')
          </div>
          <div class="tab-pane fade" id="dosha" role="tabpanel">
              @include(themeView().'kundali.dosha')
          </div>
          <div class="tab-pane fade" id="gem" role="tabpanel"></div>
          <div class="tab-pane fade" id="rudraksha" role="tabpanel" > </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection


@push('scripts')
<script type="text/javascript">
let kundali_id  = @json($kundali?$kundali->id:0);
let lang        = @json($kundali->lang);

function handleTab(that,targetId){

  $("#kundliTabs").find(".nav-link").removeClass("active");
  $(that).addClass("active");
  let tabContainer=$(`#${targetId}`);
  $("#kundliTabContent").find(".tab-pane").removeClass("show active");
  tabContainer.addClass("show active");
  const functionName=`handle${targetId.charAt(0).toUpperCase()+targetId.slice(1)}Container`;

  if(typeof window[functionName] === 'function' ){
    window[functionName](targetId);
  }
}

var loadhtmloftab = function(url, itemData, display_id){
  $.ajax({
      url: url,
      method: "POST",
      data: itemData,
      headers:{
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response){
        if(response.html){
          $(`#${display_id}`).html(response.html);
        }
      },
      error: function(xhr){
      }
  });
}

function handleDashaContainer(){

  let dashaArray=[{
    "dasha":"vimshottari","url":"{{ route('kundali.dasha.vimshottari') }}"},
    {"dasha":"yogini","url":"{{ route('kundali.dasha.yogini') }}"}];
  let mahaDashaArray=[
      {"dasha":"current-mahadasha","url":"{{ route('kundali.dasha.current-mahadasha') }}"},
      {"dasha":"antar-dasha","url":"{{ route('kundali.dasha.antardasha') }}"}
    ];
  for(let i=0;i<dashaArray.length;i++){
    dashaArray[i].kundali_id=kundali_id;
    if(dashaArray[i].dasha==='vimshottari'){
      for(let k=0;k<mahaDashaArray.length;k++){
          loadhtmloftab(mahaDashaArray[k].url, {kundali_id:kundali_id, dasha:mahaDashaArray[k].dasha}, mahaDashaArray[k].dasha);
      }
    }else {
        loadhtmloftab(dashaArray[i].url, {kundali_id:kundali_id, dasha:dashaArray[i].dasha}, dashaArray[i].dasha);
    }
  }
}

function handleDoshaContainer(){

  let dashaArray=[
    {"id":"manglik","url":"{{ route('kundali.dosha.manglik') }}"},
    {"id":"kalsarpa","url":"{{ route('kundali.dosha.kalsarpa') }}"},
    {"id":"sadesati","url":"{{ route('kundali.dosha.sadesati') }}"},
    {"id":"mangal","url":"{{ route('kundali.dosha.mangal') }}"},
  ];
  for(let i=0;i<dashaArray.length;i++){
    loadhtmloftab(dashaArray[i].url, {kundali_id:kundali_id, id:dashaArray[i].id}, dashaArray[i].id);
  }
}

function handleBasicContainer(id){
  loadhtmloftab("{{ route('kundali.basic') }}", {kundali_id:kundali_id}, id);
}
function handleAshtakvargaContainer(id){
  loadhtmloftab("{{ route('kundali.ashtakvarga') }}", {kundali_id:kundali_id}, id);
}

function handleGemContainer(id){
  loadhtmloftab("{{ route('kundali.gem-suggestion') }}", {kundali_id:kundali_id}, id);
}
function handleRudrakshaContainer(id){
  loadhtmloftab("{{ route('kundali.rudraksh-suggestion') }}", {kundali_id:kundali_id}, id);
}

function handleDivisionalContainer(id, chartType = ''){
  let divArray=[];

    divArray=[{"div":"D1","name":"Lagna/Ascendant/Basic Birth Chart"},
    {"div":"D3","name":"D3 Dreshkana"},
    {"div":"D3-s","name":"D3-Somanatha"},
    {"div":"D7","name":"D7 Saptamsa"},
    {"div":"D9","name":"D9 Navamsa"},
    {"div":"D10","name":"D10 Dasamsa"},
    {"div":"D10-R","name":"D10-R Dasamsa-EvenReverse"},
    {"div":"D12","name":"D12 Dwadasamsa"},
    {"div":"D16","name":"D16 Shodashamsa"},
    {"div":"D20","name":"D20 Vimsamsa"},
    {"div":"D24","name":"D24 ChaturVimshamsha"},
    {"div":"D24-R","name":"D24-R ChaturVimshamsha-EvenReverse"},
    {"div":"D30","name":"D30 Trimshamsha"},
    {"div":"D40","name":"D40 KhaVedamsa"},
    {"div":"D45","name":"D45 AkshaVedamsa"},
    {"div":"D60","name":"D60 Shastiamsha"},
    {"div":"chalit","name":"Bhav-chalit"},
    {"div":"moon","name":"Moon chart"},
    {"div":"sun","name":"Sun chart"}];

  for(let i=0;i<divArray.length;i++){
    divArray[i].kundali_id=kundali_id;
    divArray[i].chart_type=chartType;
    loadhtmloftab('{{ route('kundali.chart') }}', divArray[i], divArray[i].div);
  }
}

function handleKundaliContainer(id){
  kundaliChart();
  loadhtmloftab("{{ route('kundali.basic') }}", {kundali_id:kundali_id, tab:'planet-positions'}, 'kundali-positions');
  loadhtmloftab("{{ route('kundali.dasha.mahadasha') }}", {kundali_id:kundali_id}, 'kundali-vimshottari-dasha');
  loadhtmloftab("{{ route('kundali.jaimini-karakas') }}", {kundali_id:kundali_id, id:'jaimini-karakas'}, 'jaimini-karakas');
}

function handleTransitContainer(id){
    transitChart();
  loadhtmloftab("{{ route('kundali.transit-dates') }}", {kundali_id:kundali_id}, 'transit-dates');
}

function kundaliChart(chartType = ''){
  let divArray=[
    {"div":"D1","name":"Lagna/Ascendant/Basic Birth Chart"},
    {"div":"D9","name":"Navamsa"}
  ];

  for(let i=0;i<divArray.length;i++){
    divArray[i].kundali_id=kundali_id;
    divArray[i].chart_type=chartType;
    loadhtmloftab('{{ route('kundali.chart') }}', divArray[i], 'kundali-'+divArray[i].div);
  }
}


function transitChart(chartType = ''){
  let divArray=[
    {"div":"D1","name":"Lagna/Ascendant/Basic Birth Chart", "transit":1},
    {"div":"D9","name":"Navamsa", "transit":1}
  ];

  for(let i=0;i<divArray.length;i++){
    divArray[i].kundali_id=kundali_id;
    divArray[i].chart_type=chartType;
    loadhtmloftab('{{ route('kundali.chart') }}', divArray[i], 'transit-'+divArray[i].div);
  }
}
</script>
@endpush
