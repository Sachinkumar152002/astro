
@extends(themeView().'layout')

@section('title', "Delivery Addresses | ".config("app.name"))

@section('content')
<section class="account-page-ui dashboad_section">
  <div class="container">
    <div class="row justify-content-between">
    @include(themeview().'account._sidebar')
    <div class="col-lg-9 col-12 mt-md-0 address_page">
      <div class="card bg-white border-n dil_page">
        <div class="card-body">

          <!-- deliver addresses start -->
          <div class="mb-5">
            <div class="">
              <div class="d-flex justify-content-between">
                <h2 class="fs-5 mt-md-0 mt-5 dil_add">My Address Book</h2>
                <button type="button" class="btn  pencil m-2 m-md-0 d-flex align-items-center my-0 pt-5 pt-md-0 dil_add" name="button" onclick="addEditAddressModal('add', null)">+ Add New Address</button>
              </div>
              <div class="row mx-2 my-2">
                @if($addresses->count() > 0)
                  @foreach($addresses as $key => $value)
                    <div class="col-12 border-n my-1" style="border-radius:10px">
                      <div class="card check-card1 address_hi">
                        <div class="cart_text courses_width address_wi">
                          <div class="card-title mb-0">
                            <h6 class="">{{ $value->name }}</h6>
                          </div>
                          <div class="quantity">
                            {{$value->address}}, {{$value->city}}, {{$value->state}} - {{$value->zipcode}}
                          </div>
                        </div>
                        <div class="cross-icon-card1 pointer">
                          <i class="bi bi-pencil text-primary" onclick='addEditAddressModal("edit", @json($value))'></i>
                          @if($addresses->count() > 1)
                            |
                            <a href="{{route('user.addresses.delete', [$value->id])}}">
                              <i class="bi bi-trash text-danger"></i>
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="col-12 border-n my-1" style="border-radius:10px">
                    <div class="card check-card1" style="height:70px">
                      <div class="cart_text courses_width">
                        <div class="card-title mb-0">
                          <h6 class="">No Address Found</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
          <!-- deliver addresses end -->
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="modal fade mt-5" id="addEditAddressModal" tabindex="-1" aria-labelledby="addEditAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog1 address_modal">
      <div class="modal-content">
        <div class="row">
          <div class="col-10">
            <h3 class="p-4" id="address_heading">Add delivery address</h3>
          </div>
          <div class="col-2">
            <button type="button" class="btn-close1 btn-close" onclick="closeAddEditAddressModal()"></button>
          </div>
        </div>
        <div class="modal-body">
          <form id="add_edit_address_form" onsubmit="return handleAddresses(this)">
            <input type="hidden" class="form-control" id="id" name="id" value="0">
              <div class="col checkout_form">
                <div class="row">
                  <div class="col-md-6 col-6">
                    <div class="mb-3">
                      <label for="new_full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="new_full_name" name="new_full_name" placeholder="Enter full name">
                    </div>
                  </div>
                  <div class="col-md-6 col-6">
                    <div class="mb-3">
                      <label for="new_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                      <input type="tel" class="form-control" id="new_phone" name="new_phone" placeholder="Enter phone" oninput="javascript: if (this.value.length > 10) this.value = this.value.slice(0, 10);">
                    </div>
                  </div>
                  <div class="col-md-6 col-6 add_frm">
                    <label for="new_email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="new_email" name="new_email" placeholder="Enter email">
                  </div>
                  <div class="col-lg-6 col-6 d-none">
                    <div class="mb-3">
                      <label for="new_country_id" class="form-label">Country <span class="text-danger">*</span></label>
                      <select id="new_country_id" name="new_country_id" onchange="fetchStates('states', this, 'new_state_id')" class="form-select form-select-lg mb-0">
                        <option value="">Select Country</option>
                        @if($countries)
                          @foreach($countries as $key => $item)
                          <option value="{{$item->id}}" {{$item->id == 101 ? 'selected' : ''}} >{{$item->name}}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class=" col-lg-6 col-6">
                    <div class="mb-3">
                      <label for="new_state_id" class="form-label">State <span class="text-danger">*</span></label>
                      <select id="new_state_id" name="new_state_id" onchange="fetchStates('cities', this, 'new_city_id')" class="form-select form-select-lg mb-0">
                        <option value="">Select State</option>
                        @if($states)
                          @foreach($states as $key => $item)
                          <option value="{{$item->id}}" >{{$item->name}}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6 col-6">
                    <div class="mb-3">
                      <label for="new_city_id" class="form-label">City <span class="text-danger">*</span></label>
                      <select id="new_city_id" class="form-select form-select-lg" name="new_city_id">
                        <option value="">Select City</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6 col-6">
                    <div class="mb-2">
                      <label for="new_zipcode" class="form-label">Zip code <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" placeholder="Zip Code" name="new_zipcode" oninput="javascript: if (this.value.length > 6) this.value = this.value.slice(0, 6);" id="new_zipcode">
                    </div>
                  </div>
                  <div class="col-12 mb-3">
                    <label for="new_address" class="form-label">Address (Building, Street, Landmark) <span class="text-danger">*</span></label>
                    <textarea class="form-control textarea" id="new_address" name="new_address" rows="2" placeholder="Address"></textarea>
                  </div>
                  <div class="col-12 text-center">
                    <button class="main_button" type="submit" id="add_edit_address_btn" >Submit</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script type="text/javascript">
  function fetchStates (options, that, id, value=0){
    let data = '';
    let url = '{{route('locations')}}';
    if(options == 'states'){
      data += `<option value="" selected>Select State</option>`
      url += '/master_states/?country_id='+ $(that).val();
    }else if(options == 'cities'){
      data += `<option value="">Select City</option>`;
      url += '/master_cities/?state_id='+ $(that).val();
    }
    $.ajax({
        type: "GET",
        url: url, // Laravel route for verifying Razorpay payment
        success: function(response) {
          let i = 0;
          for(let item in response){
            if(i){
              data += `<option value="${item}">${response[item]}</option>`
            }
            i++;
          }
          $('#'+id).html('');
          $('#'+id).append(data);
          if(value){
            $('#'+id).find('option[value='+value+']').prop('selected', true);
          }

        },
        error: function(error) {
            console.log("fetch states by country:", error);
        }
    });
  }
  function addEditAddressModal(action, value){
    $('#id').val(value?value.id:0);
    $('#new_full_name').val(value?value.name:'');
    $('#new_email').val(value?value.email:'');
    $('#new_phone').val(value?value.phone:'');
    $('#new_country_id').val(value?value.country_id:101);
    $('#new_state_id').val(value?value.state_id:'');
    $('#new_city_id').val(value?value.city_id:'');
    $('#new_zipcode').val(value?value.zipcode:'');
    $('#new_address').val(value?value.address:'');
    $('#address_heading').text(value ? 'Edit delivery address' : 'Add delivery address');
    fetchStates('cities', $('#new_state_id'), 'new_city_id', value?value.city_id:0);
    $('#addEditAddressModal').modal('show');
  }

  function handleAddresses(that){
    $(that).find('button[type=submit]').prop('disabled', true);
    let formData = new FormData(that);
    $.ajax({
        url: "{{route('user.addresses')}}",
        method: "POST",
        data:formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for security
        },
        processData:false,
        contentType:false,
        dataType:false,
        success: function(response){
          toastr.success(response.message);
          setTimeout(function(){
            window.location.reload();
          }, 1500);
          closeAddEditAddressModal();
        },
        error: function(error){
          $(that).find('button[type=submit]').prop('disabled', false);
          console.log(error);
          if(error.responseJSON.errors){
            error = error.responseJSON.errors;
            for(var key in error){
              toastr.error(error[key][0]);
            }
          }
        }
    });
    return false;
  }

  function closeAddEditAddressModal(){
    $('#addEditAddressModal').modal('hide');
  }
</script>
@endpush
