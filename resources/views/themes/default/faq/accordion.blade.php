<div class="accordion-item">
  <h2 class="accordion-header">
    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse{{$key}}" aria-expanded="false" aria-controls="panelsStayOpen-collapse{{$key}}">
      {{$faq->question}}
    </button>
  </h2>
  <div id="panelsStayOpen-collapse{{$key}}" class="accordion-collapse collapse">
    <div class="accordion-body">
      {!! $faq->answer !!}
    </div>
  </div>
</div>
