
<div class="gth-form p-2 pt-0">
  <div id="success_message" class="text-center">
  </div>
    <div class="form-title">
        <h2 class="fs-2 text">
            Get in Touch
        </h2>
        <p class="fs-8 text">
            To get more information fill the form below, our team will contact you shortly
        </p>
        <form action="" id="send_enquiry_form">
            <div class="row">
              <div class="col-6">
                <input id="name" autofocus class="form-control" name="name" type="text" placeholder="Name *">
                <div class="text-danger fs-8 d-none validation" id="name-validate">This is required</div>
              </div>
              <div class="col-6">
                <input id="email" class="form-control" name="email" type="email" placeholder="Email *">
                <div class="text-danger fs-8 d-none validation" id="email-validate">This is required</div>
              </div>
              <div class="col-md-6 col-12">
                <input id="phone" class="form-control" name="phone" type="number" placeholder="Phone *" oninput="javascript: if (this.value.length > 10) this.value = this.value.slice(0, 10);">
                <div class="text-danger fs-8 d-none validation" id="phone-validate">This is required</div>
              </div>
              <div class="col-6 mb-2">
                <select id="country" onchange="fetchStates('states', this)" name="country" class="form-select" placeholder="Choose country">
                    <option value="">Select country</option>
                     @foreach(countries() as $key => $item)
                     <option value="{{$item->id}}" {{$item->id == 101 ? 'selected' : ''}}>{{$item->name}}</option>
                     @endforeach
                </select>
                <div class="text-danger fs-8 d-none validation" id="country-validate">This is required</div>
              </div>
              <div class="col-6 mb-3">
                <select id="states" onchange="fetchStates('cities', this)" name="states" class="form-select" placeholder="Choose State">
                  <option value="">Select State</option>
                </select>
                <div class="text-danger fs-8 d-none validation" id="states-validate">This is required</div>
              </div>
              <div class="col-6 mb-3">
                <select id="cities" class="form-select" name="cities" placeholder="Choose City">
                  <option value="">Select City</option>
                </select>
                <div class="text-danger fs-8 d-none validation" id="cities-validate">This is required</div>
              </div>
              <div class="col-6 mb-3">
                <select id="services" name="services" class="form-select" placeholder="Choose Service">
                  <option value="">Select Service</option>
                   @foreach(vastuServies() as $key => $item)
                   <option value="{{$item->id}}">{{$item->name}}</option>
                   @endforeach
                </select>
                <div class="text-danger fs-8 d-none validation" id="services-validate">This is required</div>
              </div>
              <div class="col-12">
                <input id="file" type="file" name="file" class="form-control" placeholder="Upload File">
                <div class="text-danger fs-8 d-none validation" id="file-validate">This is required</div>
              </div>
              <div class="col-12">
                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Message (optional)"></textarea>
                <div class="text-danger fs-8 d-none validation" id="message-validate">This is required</div>
              </div>
            </div>
            {{--
            <div class="row">
              <div class="col-md-6 col-12">
                <input class="form-control" id="pincode" name="pincode" type="number" placeholder="Pincode" oninput="javascript: if (this.value.length > 6) this.value = this.value.slice(0, 6);">
                <div class="text-danger fs-8 d-none validation" id="pincode-validate">This is required</div>
              </div>
            </div>
            --}}
            {{--
            <div class="row">
              <div class="col-md-6 col-12">
                <select id="service_type" name="service_type" class="form-select" placeholder="Choose Service">
                   @foreach(serviceType() as $key => $item)
                   <option value="{{$key}}">{{$item}}</option>
                   @endforeach
                </select>
                <div class="text-danger fs-8 d-none validation" id="service_type-validate">This is required</div>
              </div>
            </div>
            --}}
            {{--
            <div class="row">
              <div class="col-md-6 col-12">
                <input class="form-control" id="length" name="length" type="number" placeholder="Length (in meter)" oninput="javascript: if (this.value.length > 6) this.value = this.value.slice(0, 6);">
                <div class="text-danger fs-8 d-none validation" id="length-validate">This is required</div>
              </div>
              <div class="col-md-6 col-12">
                <input class="form-control" id="breadth" name="breadth" type="number" placeholder="Breadth (in meter)" oninput="javascript: if (this.value.length > 6) this.value = this.value.slice(0, 6);">
                <div class="text-danger fs-8 d-none validation" id="breadth-validate">This is required</div>
              </div>
            </div>
            --}}
            <div class="row my-3">
                <div class="mb-2 text-center">
                    <button class="main_button w-md-100" id="send_enquiry_btn">Send Enquiry</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
setTimeout(function (){
  fetchStates('states', $('#country'));
}, 1000);

  function fetchStates (options, elm){
  let url = '{{route("locations")}}';
    let data = '';
    if (options == 'states') {
      data += `<option value="">Select State</option>`
      url += '/master_states/?country_id='+ $(elm).val()
    } else if (options == 'cities') {
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
window.addEventListener('load', function (){
  $('#send_enquiry_form').on('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);
        $('#send_enquiry_btn').attr('disabled', 'disabled');
          $('#send_enquiry_btn').text('sending...');
        $('.validation').addClass('d-none')
      $.ajax({
          url: "{{ route('vastu.enquiry') }}",
          enctype: 'multipart/form-data',
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(response) {
            document.getElementById('send_enquiry_form').reset();
            toastr.success(response.message);
            // $('#success_message').html(response.message);
            setTimeout(function(){
              window.location.href = "{{route('thank.you')}}";
            }, 3000);
            $('#send_enquiry_btn').text('Enquiry Has Been Sent');
          },
          error: function(xhr) {
            $('#send_enquiry_btn').text('Send Enquiry');
            $('#send_enquiry_btn').removeAttr('disabled');
            for (var key in xhr.responseJSON.errors) {
              toastr.error(xhr.responseJSON.errors[key][0]);
              // $('#'+key+'-validate').removeClass('d-none')
            }
            console.log(xhr)
          }
      });
  });
})
</script>
