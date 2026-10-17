<div class="card dosha-card mb-4" id="kalsarpa-card">
  <div class="card-header fw-semibold">
    Kalsarpa Dosha
  </div>
  <div class="card-body">
    <div class="description">
      <h6 class="fw-bold">Kalsarpa Analysis</h6>
      <div class="p-3 border rounded ">
        <div class="d-flex align-items-center mb-2">
          <div class="rounded-circle text-dark d-flex align-items-center justify-content-center me-2" >{{$is_dosha_present?'Yes':'No'}}</div>
        </div>
        @foreach($remedies as $remedie)
        <p class="mb-1">{{$remedie}}</p>
        @endforeach
      </div>
    </div>
  </div>
</div>
