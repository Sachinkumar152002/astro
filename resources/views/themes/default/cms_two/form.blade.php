
<div class="gth-form p-5 pt-3">
    <div class="form-title">
        <h2 class="fs-2 text">
            Get in Touch
        </h2>
        <p class="fs-8 text">
            To get more information fill the form below, our team will contact you shortly
        </p>
        <form action="{{ route('cms_two.enquiry') }}" onsubmit="return validateAndSubmit(this)" class="cms_enquiry_form">
            <div class="row">
              <div class="col-6">
                <input autofocus class="form-control" name="name" type="text" placeholder="Name *">
                <div class="text-danger fs-8 d-none validation name-validate">This is required</div>
              </div>
              <div class="col-6">
                <input  class="form-control" name="email" type="email" placeholder="Email *">
                <div class="text-danger fs-8 d-none validation email-validate">This is required</div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-12">
                <input  class="form-control" name="phone" type="number" placeholder="Phone *" oninput="javascript: if (this.value.length > 10) this.value = this.value.slice(0, 10);">
                <div class="text-danger fs-8 d-none validation phone-validate">This is required</div>
              </div>
              <div class="col-6 mb-2">
                <select name="language" class="form-select" placeholder="Choose Language">
                   @foreach(languages() as $key => $item)
                   <option value="{{$key}}">{{$item}}</option>
                   @endforeach
                </select>
                <div class="text-danger fs-8 d-none validation language-validate">This is required</div>
              </div>
              <div class="col-6 mb-2">
                <select onchange="fetchStatess('c_states', this)" id="n_country_id" name="country" class="form-select" placeholder="Choose country">
                    <option value="">Select country</option>
                     @foreach(countries() as $key => $item)
                     <option value="{{$item->id}}" {{$item->id == 101 ? 'selected' : ''}}>{{$item->name}}</option>
                     @endforeach
                </select>
                <div class="text-danger fs-8 d-none validation country-validate">This is required</div>
              </div>
              <div class="col-6 mb-2">
                <select id="c_states" onchange="fetchStatess('c_cities', this)" name="states" class="form-select" placeholder="Choose State">
                  <option value="">Select State</option>
                </select>
                <div class="text-danger fs-8 d-none validation states-validate">This is required</div>
              </div>
              <div class="col-6 mb-2">
                <select id="c_cities" class="form-select" name="cities" placeholder="Choose City">
                  <option value="">Select City</option>
                </select>
                <div class="text-danger fs-8 d-none validation cities-validate">This is required</div>
              </div>
              <div class="col-12">
                <textarea class="form-control" name="message" rows="3" placeholder="Message (optional)"></textarea>
                <div class="text-danger fs-8 d-none validation message-validate">This is required</div>
              </div>
            </div>
            {{--
            <div class="row">
                <div class="col-md-6 col-12 mb-2">
                  <input class="form-control" name="pincode" type="number" placeholder="Pincode" oninput="javascript: if (this.value.length > 6) this.value = this.value.slice(0, 6);">
                  <div class="text-danger fs-8 d-none validation pincode-validate">This is required</div>
                </div>
                <div class="col-md-6 col-12 mb-2">
                  <select name="service_type" class="form-select" placeholder="Select Service">
                     @foreach(serviceType() as $key => $item)
                      <option value="{{$key}}">{{$item}}</option>
                     @endforeach
                  </select>
                  <div class="text-danger fs-8 d-none validation service_type-validate">This is required</div>
                </div>
            </div>
            --}}
            <div class="row my-3">
                <div class="text-center">
                    <button class="main_button w-md-100 enquiry_submit_btn">Send Enquiry</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
setTimeout(function (){
  fetchStatess('c_states', $('#n_country_id'));
}, 1000);
  function fetchStatess (options, elm){
  let url = '{{route("locations")}}';
    let data = '';
    if (options == 'c_states') {
      data += `<option value="">Select State</option>`
      url += '/master_states/?country_id='+ $(elm).val()
    } else if (options == 'c_cities') {
      data += `<option value="">Select City</option>`
      url += '/master_cities/?state_id='+ $(elm).val()
    }
    $.ajax({
        type: "GET",
        url: url,
        success: function(response) {
          let i = 0;
          for(let item in response){
            if(i){
              data += `<option value="${item}">${response[item]}</option>`
            }
            i++;
          }
          $('#'+options).html('');
          $('#'+options).append(data);
        },
        error: function(error) {
            console.log("fetch states by country:", error);
        }
    });
  }
  function validateAndSubmit(form){
    var _f = $(form);
    _f.find('.enquiry_submit_btn').attr('disabled', 'disabled').text('sending...');
    _f.find('.validation').addClass('d-none');
    $.ajax({
        url: "{{ route('cms_two.enquiry') }}",
        method: "POST",
        data: _f.serialize(),
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
          toastr.success(response.message);
          setTimeout(function(){
            window.location.href = "{{route('thank.you')}}";
          }, 3000);
          // _f.reset();
          // _f.find('.enquiry_submit_btn').text('Enquiry Has Been Sent');
          // $('#success_message').html(response.message);
        },
        error: function(xhr) {
          _f.find('.enquiry_submit_btn').text('Send Enquiry').removeAttr('disabled');
          for (var key in xhr.responseJSON.errors) {
            toastr.error(xhr.responseJSON.errors[key][0]);
            // _f.find('.'+key+'-validate').removeClass('d-none');
          }
          console.log(xhr)
      }
    });
    return false;
  }
</script>
