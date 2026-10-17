@extends(themeView().'layout')

@section('title', 'Profile')
@section('keywords', '')
@section('description','')

@section('content')
{{--
@include(themeView().'astrologers._partials.top')
--}}
<section class="dashboad_section" style="background: #f2f2f2;">
<div class="container">
    <div class="row">
        @include(themeView().'astrologers._partials.sidebar')
        <div class="col-lg-9 col-12 mt-5 mt-md-0">
          <div class="row my-2 dashbord">
                <div class="col-lg-3 col-6 mb-0 mb-md-0 pe-1 pe-md-3 mb-0">
                  <a href="javascript:void(0)">
                    <div class="card bg-primary1  pt-3 mb-1 d-flex justify-content-center align-items-center ">
                      <i class="bi bi-envelope-paper text-white fs-1 text"></i> <p class="text-white fw-bold mt-3 min-hhh">{{count($active_ads)}} Active</p>
                    </div>
                  </a>
                </div>
                <div class="col-lg-3 col-6 mb-0 mb-md-0 ps-1 ps-md-3">
                  <a href="javascript:void(0)">
                    <div class="card bg-primary1  pt-3 mb-1 d-flex justify-content-center align-items-center">
                      <i class="bi bi bi-envelope fs-1 text-white"></i> <p class="text-white fw-bold mt-3 text-center min-hhh">{{count($inactive_ads)}} Inactive</p>
                    </div>
                  </a>
                </div>
                <div class="col-lg-3 col-6 mb-0 mb-md-0 pe-1 pe-md-3 mt-1">
                  <a href="javascript:void(0)">
                    <div class="card bg-primary1  pt-3 mb-1 d-flex justify-content-center align-items-center">
                      <i class="bi bi-envelope-check fs-1 text-white"></i> <p class="fw-bold mt-3 text-white min-hhh">{{count($active_ads)+count($inactive_ads)}} Total</p>
                    </div>
                  </a>
                </div>
              </div>
              <div class="pt-2 mt-3">
                <div class="card-body">
                  <ul class="nav dashboard_tab nav-tabs dash-tab astro-account-tab" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link text-dark active" id="live-ads-tab" data-bs-toggle="tab" href="#live-ads" role="tab" aria-controls="live-ads" aria-selected="false" tabindex="-1">Active</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link text-dark" id="complete-ads-tab" data-bs-toggle="tab" href="#complete-ads" role="tab" aria-controls="complete-ads" aria-selected="false" tabindex="-1">Inactive</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link text-dark" id="form-ads-tab" data-bs-toggle="tab" href="#form-ads" role="tab" aria-controls="form-ads" aria-selected="true">Request</a>
                    </li>
                  </ul>
                  <div class="tab-content mt-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="live-ads" role="tabpanel" aria-labelledby="live-ads-tab">
                      <div class="card bg-light p-3 pt-4 my-4 table-responsive">
                        <div class="row">
                      @forelse($active_ads as $key => $item)
                        <div class="col-md-4 col-sm-6 mb-4">
                          <div class="card h-100 shadow-sm radius">
                            <img src="{{ $item->image ? $item->image->image(400, 250) : no_image() }}"
                                 class="card-img-top"
                                 alt="{{ $item->title }}">

                            <div class="card-body text-center">
                              <h5 class="card-title">{{ $item->title }}</h5>
                              <p class="text-muted">Ad #{{ $key+1 }}</p>
                            </div>
                          </div>
                        </div>
                      @empty
                        <div class="col-12">
                          <div class="no-data text-center p-4">
                            <img src="/assets/images/add.webp" alt="No ad" class="nodata mb-3">
                            <h5 class="mt-2 text-dark">No Ad Found!</h5>
                            <p class="text-secondary">Ads will be displayed here.</p>
                          </div>
                        </div>
                      @endforelse
                    </div>

                      </div>
                    </div>
                    <div class="tab-pane fade" id="complete-ads" role="tabpanel" aria-labelledby="complete-ads-tab">
                      <div class="card bg-light p-3 pt-4 my-4 table-responsive">
                        <div class="row" id="tickets-list">
 @forelse($inactive_ads as $key => $item)
     <div class="col-md-4 mb-4">
         <div class="card table-bordered radius h-100">
             <div class="card-body text-center">
                 <h6 class="card-title mb-2">#{{ $key+1 }}</h6>
                 <p class="fw-bold text-dark">{{ $item->title }}</p>
                 <img src="{{ $item->image ? $item->image->image(200, 200) : no_image() }}"
                      alt="{{ $item->title }}" class="img-fluid rounded">
             </div>
         </div>
     </div>
 @empty
     <div class="col-12">
         <div class="no-data text-center">
             <img src="/assets/images/add.webp" alt="no ad" class="nodata">
             <h5 class="mt-3 text-dark"> No Ad Found!</h5>
             <p class="text-secondary"> Ads will be displayed here.</p>
         </div>
     </div>
 @endforelse
</div>

                      </div>
                    </div>
                    <div class="tab-pane fade" id="form-ads" role="tabpanel" aria-labelledby="form-ads-tab">
                      <form method="POST" action="{{route('astrologer.ads.submit')}}" id="submit-ads">
                        <div class="card p-3 bg-light">
                          <div class="form-group">
                            <label>Title<em class="text-danger">*</em></label>
                            <input type="text" class="form-control" placeholder="Title" id="title" name="title">
                            <div class="text-danger fs-6 d-none validation" id="title-validate">This is required</div>
                          </div>
                          <div class="form-group mt-3">
                            <label for="exampleFormControlTextarea1" class="fs-7">Description <em class="text-danger">*</em></label>
                            <textarea class="form-control" id="description" name="description" placeholder="Description" rows="3"></textarea>
                            <div class="text-danger fs-6 d-none validation" id="description-validate">This is required</div>
                          </div>
                          <div class="form-group my-3">
                            <label for="exampleFormControlFile1" class="mb-2">Image</label>
                            <div class="row">
                            <div class="col-11">
                            <input onchange="showTempProfileImage('image_id', 'image_id_src')" type="file" class="form-control-file form-control" id="image_id" accept=".png, .jpg, .jpeg, .webp" name="image_id">
                            </div>
                            <div class="col-1">
                            <img src="{{no_image()}}" id="image_id_src" alt="no image" width="45" height="45">
                            </div>
                            </div>


                            <div class="text-danger fs-6 d-none validation" id="image_id-validate">This is required</div>
                          </div>
                          <div class="form-group">
                            <label>Link<em class="text-danger">*</em></label>
                            <input type="text" class="form-control" placeholder="Link" id="link" name="link">
                            <div class="text-danger fs-6 d-none validation" id="link-validate">This is required</div>
                          </div>
                          <div class="">
                            <button type="submit" id="ads_submit_btn" name="button" class="main_button2 mb-2 mt-3">Submit</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
    </div>
  </div>
</section>
@endsection
{{--
@include(themeView().'common.editor')
--}}

@push('scripts')
<script type="text/javascript">
  $('#submit-ads').on('submit', function(e){
      e.preventDefault();
      let _f = $(this);
      let url = _f.attr('action');
      var formData = new FormData(this);
        $('#ads_submit_btn').attr('disabled', 'disabled');
        $('.validation').addClass('d-none')
      $.ajax({
          url: url,
          enctype: 'multipart/form-data',
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response){
            document.getElementById('submit-ads').reset();
            $('#submit_support_btn').removeAttr('disabled');
            Swal.fire({
                icon: response.success?'success':'error',
                title: response.message,
                confirmButtonText: 'Okay'
            });
            window.location.reload();
          },
          error: function(error){
            $('#ads_submit_btn').removeAttr('disabled');
            for (var key in error.responseJSON.errors){
              $('#'+key+'-validate').removeClass('d-none').text(error.responseJSON.errors[key][0])
            }
            console.log(error)
          }
      });
  });
  function showTempProfileImage(file, src) {
    var fileInput = document.getElementById(file);
    var file = fileInput.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var imageElement = document.getElementById(src);
            imageElement.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
  }
</script>
@endpush
