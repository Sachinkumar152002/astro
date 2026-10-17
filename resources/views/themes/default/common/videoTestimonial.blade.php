@if(isset($videoTestimonials) && count($videoTestimonials)>0)
  <section class="live_section pt-0 mt-0 av-home-standard-section av-home-video-section">
    <div class="container">
      <div class="row align-items-center gx-0 mx-0 mb-0 mb-lg-0">
        <div class="col-lg-10 col-sm-8">

          <div class="d-flex align-items-center mb-3">
            <i class="bi bi-camera-video-fill me-2"></i>
            <h2 class="mb-0 ms-md-1 ms-lg-0 me-2"> Video Testimonials</h2>


          </div>
        </div>

      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="vtestimonials">
    @foreach($videoTestimonials as $v)
      @if(isset($v->image))
        <div class=""> <!-- slick me slide banane ke liye wrapper -->
          <div class="card v-t-card ms-0">
            <a class="video-link video_link2" onclick="videoModal('https://www.youtube.com/embed/{{$v->video_id}}')">
              <img class="position-relative img-fluid" src="{{$v->image}}" alt="{{$v->title}}">
              <div class="w-100 d-flex justify-content-center align-items-center">
                <span><i class="bi bi-youtube"></i></span>
              </div>
            </a>
            <div class="card-footer bg-white">
              <p class="text-black mb-0 v_tt">{{$v->title}}</p>
            </div>
          </div>
        </div>
      @endif
    @endforeach
  </div>

        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-12 col-lg-12 mt-3">
  <img alt="img" class="img-fluid img-fluid2 panchang_line_img1" loading="lazy" src="assets/img/rectangle_panchang.png">
</div>
    </div>
  </section>
  <div class="modal fade border-0 modal-lg" id="vtestimonial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog k_video_test">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="btn-close position-absolute v_btn" data-bs-dismiss="modal" aria-label="Close"></button>
        <iframe id="video-frame" width="100%" height="400"></iframe>
        </div>
      </div>
    </div>
  </div>
@endif
@push('scripts')
<script>
  function videoModal(videoLink){
    $('#video-frame').attr('src' , videoLink);
    $('#vtestimonial').modal('show');
  }
  $('.v_btn').on('click',function(){
    $('#video-frame').attr('src' , '');
  })
</script>
@endpush
