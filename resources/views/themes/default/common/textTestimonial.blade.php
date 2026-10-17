@if(isset($textTestimonials) && count($textTestimonials)>0)
  <section class="testimonials_bg mt-md-2 mt-5 av-home-standard-section av-home-testimonial-section">
    <div class="container px-1 px-md-2">
      <div class="row align-items-center gx-0 mx-0 mb-lg-3 mb-0">
        <div class="col-lg-10 col-sm-8">

          <div class="d-flex align-items-center">
            <i class="fa fa-comments-o me-2"></i>
 <h2 class="mb-0 ms-md-1 ms-lg-0 me-2"> Testimonials</h2>


          </div>
        </div>

      </div>

          <!-- Testimonials Section -->

  <div class="testimonial-slider">
    @foreach($textTestimonials as $t)
      <div class="">
        <a href="#">
          <div class="card p-2 me-0 me-md-3 test-box">
            <div class="card-body p-0 position-relative">
              <span class="quote-icon">“</span>
              <p class="card-text testimonials-p px-1 p-12">{{ strip_tags ($t->description) }}</p>
              <h6 class="fw-bold c-h text-end text-black te-title">{{ $t->title }}</h6>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>


    </div>
  </section>
@endif
