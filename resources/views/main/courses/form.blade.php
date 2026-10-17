<div class="modal fade modal-lg" id="Bookdemo" tabindex="-1" aria-labelledby="BookdemoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
        <button type="button" class="btn-close" id="allcourselandingpagebtnclose" data-bs-dismiss="modal" aria-label="Close" style="top: 15px;"></button>
      </div>
      <div class="modal-body pt-0">
        <div class="gth-form p-2 pt-0">
          <div id="success_message" class="text-center">
          </div>
            <div class="form-title">
                <h2 class="fs-2 text">Get Course Brochure</h2>
                <p class="fs-8 text">To get more information fill the form below, our team will contact you shortly</p>
                <form action="" class="formValidateAndSubmit">
                  <input type="hidden" name="page_type" value="courses">
                    <div class="row">
                      <div class="col-md-4 col-12">
                        <input id="name" required class="form-control" name="name" type="text" placeholder="Name *">
                      </div>
                      <div class="col-md-4 col-12">
                        <input id="email" required class="form-control" name="email" type="email" placeholder="Email *">
                      </div>
                      <div class="col-md-4 col-12">
                        <input id="phone" required class="form-control" name="phone" type="number" placeholder="Phone *" oninput="javascript: if (this.value.length > 10) this.value = this.value.slice(0, 10);">
                      </div>
                      <div class="col-md-6 col-12 mb-3">
                        <select id="category" name="category" required class="form-select" onchange="handleCourseType(this)">
                            <option value="">Select Category</option>
                            @php
                            $notIn = [0, 2]
                            @endphp
                            @foreach(courseTypes() as $key => $value)
                              @if(!in_array($key, $notIn))
                                @if($value=='Live')
                                <option data-value="{{$key}}" value="{{$value}} (Online & Offline)">{{$value}} (Online & Offline)</option>
                                @elseif($value=='Online')
                                <option data-value="{{$key}}" value="E-Learning">E-Learning</option>
                                @else
                                <option data-value="{{$key}}" value="{{$value}}">{{$value}}</option>
                                @endif
                              @endif
                            @endforeach
                        </select>
                      </div>
                      <div class="col-md-6 col-12 mb-3">
                        <select id="course" name="course" required class="form-select">
                            <option value="">Select Course</option>
                        </select>
                      </div>
                      <div class="col-12 mb-2">
                        <textarea class="form-control" id="description"  name="description" rows="3" placeholder="Message"></textarea>
                      </div>
                    </div>
                    <div class="row mb-2">
                        <div class="mb-2 text-center">
                            <button type="submit" class="main_button w-md-100" id="send_enquiry_btn">Send Enquiry</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let courses = @json([courses(1), courses(2), courses(3)]);
function handleCourseType(that){
  const value = $(that).find('option:selected').attr('data-value');
  document.getElementById('course').innerHTML = '';
  courses[(value-1)].forEach(function(item, index){
    let option = document.createElement('option');
    option.value = item == 'Select Course' ? '' : item;
    option.innerHTML = item;
    document.getElementById('course').appendChild(option);
  })
}

window.addEventListener('load', function (){

  $('.formValidateAndSubmit').on('submit', function(e) {
      e.preventDefault();
      let _f = $(this);
      _f.find('button[type=submit]').attr('disabled', true);
      $(_f).find("input[required], select[required], textarea[required]").each(function() {
          if (!this.checkValidity()) {
              toastr.error($(this).attr('name') + ' is required.');
          }
      });
      let formData = _f.serialize();
      $.ajax({
          url: '{{url("/lp/form/submit")}}',
          type: 'POST',
          data: formData,
          success: function(response) {
              toastr.success(response.message);
              document.getElementById('allcourselandingpagebtnclose').click();
              setTimeout(function() {
                  toastr.success('Thank You & Visit Again.');
                  if(response.redirectTo){
                    window.location.href = response.redirectTo;
                  }
              }, 1500);
          },
          error: function(error) {
              _f.find('button[type=submit]').attr('disabled', false);
              if (error.responseJSON && error.responseJSON.errors) {
                  error = error.responseJSON.errors;
                  for (var key in error) {
                      toastr.error(error[key][0]);
                  }
              }
              console.log(error);
          }
      });
  });
});
</script>
