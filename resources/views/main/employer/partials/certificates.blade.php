<section class="certification-container wow fadeInDown">
  <div class="container">
    <div class="certification-box">
      <h3 class="text-center"><span>Certification</span> Type</h3>
      <div class='col-md-12 certification-slider'>
        <div class=" slide media-" id="media">
          <div class="-inner col-md-12">
            <div class="item">
              <div class="col-md-12">
                @foreach($certificates as $certificate)
                <div class="col-md-4">
                  <a class="thumbnail" href="javascript:void(0)"><img alt="{{$certificate->name}}" src="{{$certificate->image->image(200,200)}}" class="img-responsive"></a>
                </div>
                @endforeach
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>