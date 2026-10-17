<!-- Mangal Dosha Card -->
<div class="card dosha-card mb-4" id="mangal-card">
  <div class="card-header fw-semibold">
    Mangal Dosha
  </div>
  <div class="card-body">
    <div class="description">
      <h6 class="fw-bold">Mangal Dosh</h6>
      <div class="p-3 border rounded ">
        <div class="d-flex align-items-center mb-2">
          <div class="rounded-circle text-dark d-flex align-items-center justify-content-center me-2" >{{$score>50?'Yes':'No'}}</div>
        </div>
        @foreach($factors as $p=>$st)
        <p class="mb-1"><strong>{{ucfirst($p)}} : </strong> {{$st}}</p>
        @endforeach
        <small class="text-danger d-block mt-2">[This is a computer-generated result. Please consult an Astrologer to confirm &amp; understand this in detail.]</small>
      </div>
    </div>
  </div>
</div>
