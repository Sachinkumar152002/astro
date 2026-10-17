<style media="screen">
.v-t-card {
height: 100%;
display: flex;
flex-direction: column;
}

.v-t-card img {
height:160px;
object-fit: cover;
}

.v-t-card .card-body {
flex-grow: 1;
}
@media (max-width: 768px) {
    .video_link2 {
        height: 186px;
        overflow: hidden;
        position: relative;
    }
    .video_link2 img {
    height: auto !important;
    margin-top: -32px;
}
}


</style>
@if(isset($videos) && count($videos)>0)
<section class="pt-5 p-0">
  <div class="container p-0">
    <div class="row align-items-center gx-0 mx-2">
      <div class="col-lg-3 col-sm-3">
      </div>
      <div class="col-lg-6 col-sm-6">
        <div class="heading_main">
        <img src="{{asset('assets/img/icons/heading_icon.svg')}}">
        <h2>Reference Video</h2>
      </div>
      </div>
      <div class="col-lg-3 col-sm-3 mb-2 mb-md-0">

      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
      </div>
      <div class="col-lg-12">
        <div class="vtestimonials-2">
          @foreach($videos as $v)
          <div class="col-lg-3">
              <div class="card v-t-card1">
                  <a  class="video-link video_link2" onclick="videoModal2('https://www.youtube.com/embed/{{$v->video_id}}')">
                      <img class="position-relative" src="{{$v->thumbnail}}" class="img-fluid" alt="{{$v->title}}">
                        <div class="youtube_icon"><img src="{{asset('assets/img/icons/youtube_icon.png')}}"> </div>


                  </a>
                  <div class="card-body">
                          <h5 class="fw-semibold card-title c-h">{{$v->title}}</h5>
                      </div>
              </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade border-0 modal-lg" id="vtestimonial2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close position-absolute v_btn" data-bs-dismiss="modal" aria-label="Close"></button>
      <iframe id="video-frame2" width="100%" height="400"></iframe>
      </div>
    </div>
  </div>
</div>
@endif
@push('scripts')
<script>
  function videoModal2(videoLink){
    $('#video-frame2').attr('src' , videoLink);
    $('#vtestimonial2').modal('show');
  }
  $('.v_btn').on('click',function(){
    $('#video-frame2').attr('src' , '');
  })
</script>
@endpush
