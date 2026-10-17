<h5 class="mb-4 card-header-main">Vimshottari Dasha</h5>
<!-- Current Vimshottari Dasha Cards -->
<div class="current-dasha-cards mb-5" id="current-dasha-cards">
  <div class="row g-4">
    @php
      $oNames  = ['mahadasha', 'antardasha', 'paryantardasha', 'Shookshamadasha', 'Pranadasha'];
    @endphp
    @foreach($oNames as $vimDasha)
    <div class="col-md-6 col-lg-2">
      <div class="card current-dasha-card">
        <div class="card-header fw-semibold">{{ucfirst($vimDasha)}}</div>
        <div class="card-body">
          <div class="dasha-planet-name" id="current-{{$vimDasha}}-name">{{${$vimDasha}['name']}}</div>
          <div class="dasha-date-label">Start Date</div>
          <div class="dasha-date-value" id="current-{{$vimDasha}}-start">{{date('M d, Y', strtotime(substr(${$vimDasha}['start'], 0, 15)))}}</div>
          <div class="dasha-date-label mt-2">End Date</div>
          <div class="dasha-date-value" id="current-{{$vimDasha}}-end">{{date('M d, Y', strtotime(substr(${$vimDasha}['end'], 0, 15)))}}</div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
\
