@extends(themeView().'layout')

@section('content')
<section class="page-header about-page" style="background-image:url('{{ noImage() }}'); background-repeat: no-repeat; width: 100%; background-size: cover;" >
<div class="container">
    <h1 class="text-center">Vastu Services</h1>
    <div class="text-center">
    @include(themeView().'common.breadcrumb')
  </div>
</div>
</section>
@if(isset($services) && count($services ))
<section class="py-md-2 py-2">
  <div class="container">
    <div class="row align-items-center mt-3">
          @foreach($services as $service)
          <div class="col-lg-3 mb-2 vastus vas">
            <a href="{{route('vastu.page', $service->slug)}}">
              <div class="card">
                <img src="{{$service && $service->image ? $service->image->image(400, 400) : ''}}" class="img-fluid vsatu-i" alt="{{$service->name}}">
                <div class="card-body v-text-d">
                  <h5 class="fw-semibold card-title text-truncate">{{$service->name}}</h5>
                {!! $service->short_description !!}
                </div>
              </div>
            </a>
          </div>
          @endforeach
    </div>
  </div>
</section>
@endif
@endsection
