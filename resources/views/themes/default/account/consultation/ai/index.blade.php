@extends(themeView().'layout')

 @section('title', 'Ai Consultation Histories | '.config("app.name"))

@section('content')
<section class="account-page-ui dashboad_section">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeview().'account._sidebar')
      <div class="col-lg-9 col-12 mt-md-0 mt-5 all_pages pad-ap3">
        <div class="d-flex justify-content-between">
          <h4 class="mb-0 dash-heading">My AI Chat Consultations</h4>
          <div class="">

            <button class="main_button"> Total Chats {{ $appointments->count() }}</button>
          </div>
        </div>
       <div class="tab-content" id="myTabContent">
         <div class="tab-pane fade {{$tab == 'chat' ? 'active show' : ''}}" id="home" role="tabpanel" aria-labelledby="home-tab">
           <div class="card bg-white border-n mb-3 mt-3">
             <div class="card-body pb-0">
               <div class="row">
                 @php
                   $i = 0;
                 @endphp
                 @foreach($appointments as $key => $item)
                   @php
                     $astrologer = $item->astrologer;
                     $scheduledStartTime = strtotime($item->start_at);
                     $scheduledEndTime = strtotime($item->end_at);
                     $duration = ceil(($scheduledEndTime - $scheduledStartTime)/60);

                     if($duration < 0){
                       $duration = 0;
                     }

                     $i++;
                   @endphp

                   <div class="col-md-4 col-sm-6 mb-4">
                     <div class="earning-card border-n p-3 bg-light rounded">
                       <div class="d-flex justify-content-between align-items-center mb-2">
                         <span class="badge k_bg_badge">#{{$i}}</span>
                         <span class="text-muted small">{{date('d-m-Y h:i A', $scheduledStartTime)}}</span>
                       </div>
                       <div class="d-flex justify-content-between mb-2">
                         <span class=""><i class="bi bi-person"></i> Astrologer</span>
                         <span class="text-dark fw-bold">{{ $astrologer ? $astrologer->display_name : 'Astrologer' }}</span>
                       </div>

                       <div class="d-flex justify-content-between mb-2">
                         <span><i class="bi bi-currency-rupee"></i> /min</span>
                         <span class="text-danger fw-bold">₹ {{$item->per_min_charge}}</span>
                       </div>

                       <div class="d-flex justify-content-between mb-2">
                         <span><i class="bi bi-clock"></i> Duration</span>
                         <span class="text-dark fw-bold">{{ $duration }} min</span>
                       </div>

                       <div class="d-flex justify-content-between border-top pt-2">
                         <span><i class="bi bi-currency-rupee"></i> Amount</span>
                         @if($item->free)
                          @php
                            $totalPrice = $item->charges - $item->discount;
                          @endphp
                          <span class="text-success fw-bold">₹ {{ $totalPrice > 0 ? $totalPrice : 0 }} (free)</span>
                         @else
                          <span class="text-success fw-bold">₹ {{$item->charges}}</span>
                         @endif
                       </div>

                       <div class="d-flex justify-content-between border-top pt-2">
                         <span><i class="bi bi-lightning-charge"></i> Action</span>
                         <span>
                           @if($item->paid)
                            @if($item->charges <= 0)
                              <span class="text-black fw-bold">Cancelled</span>
                            @else
                              <a class="text-black fw-bold text-decoration-underline" href="{{route('user.startaichat', [$item->id])}}">Completed</a>
                            @endif
                           @else
                            <a class="text-black fw-bold text-decoration-underline" href="{{route('user.startaichat', [$item->id])}}">Join</a>
                           @endif
                         </span>
                       </div>
                       @php
                        $review = $reviews->where("appointment_id", $item->id)->where("astrologer_id", $item->astrologer_id)->first();
                        $rating = "";
                        $comment = "";
                        $status = 0;
                        if($review){
                          $rating = $review->rating;
                          $comment = $review->comment;
                          $status = $review->status;
                        }
                       @endphp
                       <div class="d-flex justify-content-between border-top pt-2">
                         @if($item->paid == 1 && $status == 0)
                           <a href="javascript:void(0)" class="text-primary fw-bold" onclick="handleReviewModal('open', '{{ $item->id }}', '{{ $item->astrologer_id }}', '{{ $rating }}', '{{ $comment }}')">Review</a>
                         @endif
                       </div>
                     </div>
                   </div>
                 @endforeach
                 @if($appointments->count() == 0)
                   <div class="col-12">
                     <div class="no-data text-center">
                       <img src="{{asset('assets/images/data-1.jpg')}}" class="nodata">
                       <h5 class="mt-3 text-dark"> No Chat Consultation Found!</h5>
                       <p class="text-secondary"> Consultation bookings and history will be displayed here.</p>
                     </div>
                   </div>
                  @else
                    {!! $appointments->links('vendor.pagination.bootstrap-5') !!}
                  @endif
               </div>

             </div>
           </div>
         </div>
      </div>
    </div>
  </div>
</div>
</section>

<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg astrologer_rev">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">Write Review</h5>
        <button type="button" class="btn-close" onclick="handleReviewModal('close', 0, 0, 0, '')"></button>
      </div>

      <div class="modal-body">
        <form id="review_form">
          <input type="hidden" name="appointment_id" id="appointment_id">
          <input type="hidden" name="astrologer_id" id="astrologer_id">
          <input type="hidden" name="rating" id="rating" value="">
          <div class="col-md-12 stars rati mb-3">
            <i class="bi bi-star" data-value="1" id="star-1"></i>
            <i class="bi bi-star" data-value="2" id="star-2"></i>
            <i class="bi bi-star" data-value="3" id="star-3"></i>
            <i class="bi bi-star" data-value="4" id="star-4"></i>
            <i class="bi bi-star" data-value="5" id="star-5"></i>
          </div>
          <div class="mb-3">
            <textarea name="comment" id="comment" rows="5" class="form-control" placeholder="Write your review..."></textarea>
          </div>
          <div class="text-end">
            <button type="submit" class=" p-add">Submit</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
function handleReviewModal(action, appointment_id, astrologer_id, rating, comment){
  $("#appointment_id").val(appointment_id);
  $("#astrologer_id").val(astrologer_id);
  $("#comment").val(comment);
  rating = Number(rating);
  $("#star-"+rating).click();

  if(action == "open"){
    $("#reviewModal").modal("show");
  }else{
    $("#reviewModal").modal("hide");
  }
}

document.querySelectorAll(".rati i").forEach(star => {
  star.addEventListener("click", function() {
    let value = this.getAttribute("data-value");
    document.querySelectorAll(".rati i").forEach(s => {
      s.classList.remove("bi-star-fill");
      s.classList.add("bi-star");
    });
    for (let i = 0; i < value; i++) {
      let starEl = document.querySelectorAll(".rati i")[i];
      starEl.classList.remove("bi-star");
      starEl.classList.add("bi-star-fill");
    }
    document.getElementById("rating").value = value;
  });
});

$('#review_form').on('submit', function(e){
    e.preventDefault();
    let formData = $(this).serialize();
    $.ajax({
        url: "{{ route('user.astrologer.review.submit') }}",
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response){
          toastr.success(response.message);
          setTimeout(function() {
            window.location.reload();
          }, 1000);
        },
        error: function(error){
          let errors = error.responseJSON.errors;
            if(errors){
              for(let key in errors){
                toastr.error(errors[key][0]);
              }
            }else{
              let message = error.responseJSON.message;
              if(message){
                toastr.error(message);
              }
            }
        }
    });
});

</script>
@endpush
