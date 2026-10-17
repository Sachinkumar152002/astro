@php
  $index = -1;
  $genderNames = ["gender", "p1_gender", "p2_gender"];
  $placesNames = ["birth_place", "p1_birth_place", "p2_birth_place"];

  // Count fields in "other all form" section (excluding P1 and P2)
  $otherFieldsCount = 0;
  foreach($productFormFields as $field) {
    if($field->field) {
      $fieldLabel = $field->field->form_label;
      if(!str_contains($fieldLabel, "P1") && !str_contains($fieldLabel, "P2")) {
        $otherFieldsCount++;
      }
    }
  }

  // Use a dedicated index for static Gotra field so it participates in formDataArray
  $gotraIndex = count($productFormFields) * $product->max_order_qty;
@endphp
@for($i = 1; $i <= $product->max_order_qty; $i++)
  {{--@if($product->max_order_qty > 1)
    <div class="col-12"><h6 class="fw m-0 mt-2">{{ $i }}. Details</h6></div>
  @endif--}}
  @php
    $p1Heading = false;
    $p2Heading = false;
  @endphp

  @foreach($productFormFields as $indexx => $field)
    @if($formField = $field->field)
      @php
        $index++;
        $type = $formField->form_type;
        $label = $formField->form_label;
        // Skip dynamic Gotra field for group puja; we'll render a single combined Gotra field below
        if(strtolower($label) === 'gotra') {
          continue;
        }
        $name = strtolower(str_replace(' ', '_', $label));
        $required = $formField->isrequired;
        if($i > $product->min_order_qty){
          $required = 0;
        }
        $formFieldArray = [
          "type" => $type,
          "label" => $label,
          "name" => $name,
        ];
      @endphp

      @if(str_contains($formField->form_label, "P1"))
        <!-- boy form -->
        @if(!$p1Heading)
          @php
            $p1Heading = true;
          @endphp
          <div class="col-6">
            <div class="row">
              <div class="col-12 d-flex align-items-center mb-2">
                <img src="{{ asset('assets/images/icons/man.png') }}" width="30px" height="30px" alt="man">
                <h6 class="fw m-0 mt-2 ps-1">Boy Details</h6>
              </div>
        @endif

        @if($type == "select" && $name == "p1_gender")
          <div class="col-12 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], [" ", "Girl"], $label) }}</label>
              <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                @if(in_array($name, $genderNames))
                  <option value="">Select</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                @endif
              </select>
            </div>
          </div>
        @elseif($type == "search")
          <div class="col-12 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
              @if(in_array($name, $placesNames))
                <input type="text" class="form-control form-control-lg" name="{{ $name }}_search" id="{{ $name }}" onkeyup='locationPlaces("#{{ $name }}", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} minlength="3">
              @endif
              <div class="suggestion_birth">
                <ul id="{{ $name }}_suggestions"></ul>
              </div>
            </div>
          </div>
        @else
          <div class="col-12 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
              @if($type == 'time')
                <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
              @endif
              <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control form-control-lg {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1">
            </div>
          </div>
        @endif
      @elseif(str_contains($formField->form_label, "P2"))
        <!-- girl form -->
        @if(!$p2Heading)
          @php
            $p2Heading = true;
            $p2FormEnd = false;
          @endphp
            </div>
          </div>
          <div class="col-6">
            <div class="row">
              <div class="col-12 d-flex align-items-center mb-2">
                <img src="{{ asset('assets/images/icons/woman.png') }}" width="30px" height="30px" alt="woman">
                <h6 class="fw m-0 mt-2 ps-1">Girl Details</h6>
              </div>
        @endif

        @if($type == "select" && $name == "p2_gender")
          <div class="col-12 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
              <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                @if(in_array($name, $genderNames))
                  <option value="">Select</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                @endif
              </select>
            </div>
          </div>
        @elseif($type == "search")
          <div class="col-12 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
              @if(in_array($name, $placesNames))
                <input type="text" class="form-control form-control-lg" name="{{ $name }}_search" id="{{ $name }}" onkeyup='locationPlaces("#{{ $name }}", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} minlength="3">
              @endif
              <div class="suggestion_birth">
                <ul id="{{ $name }}_suggestions"></ul>
              </div>
            </div>
          </div>
        @else
          <div class="col-12 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["", ""], $label) }}</label>
              @if($type == 'time')
                <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
              @endif
              <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control form-control-lg {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1">
            </div>
          </div>
        @endif
        @if($productFormFields->count()-1 == $indexx && isset($p2FormEnd) && $p2FormEnd == false)
          </div>
        </div>
          @php
            $p2FormEnd = true;
          @endphp
        @endif
      @else
        <!-- other all form -->
        @if(isset($p2FormEnd) && $p2FormEnd == false)
          </div>
        </div>
          @php
            $p2FormEnd = true;
          @endphp
        @endif

        @if($type == "select" && $name == "language")
          <div class="col-6 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], [" ", "Girl"], $label) }}</label>
              <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                <option value="">Select</option>
                <option value="english">English</option>
                <option value="hindi">Hindi</option>
              </select>
            </div>
          </div>
        @elseif($type == "select" && $name == "gender")
          <div class="col-md-6 col-6 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
              <select class="form-select" name="{{ $name }}" {{ $required ? "required" : "" }} onchange='addFormData(this, @json($formFieldArray), "{{ $index }}")'>
                @if(in_array($name, $genderNames))
                  <option value="">Select</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                @endif
              </select>
            </div>
          </div>
        @elseif($type == "search")
          <div class="col-md-6 col-6 mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
              @if(in_array($name, $placesNames))
                <input type="text" class="form-control form-control-lg" name="{{ $name }}_search" id="{{ $name }}" onkeyup='locationPlaces("#{{ $name }}", "#{{ $name }}_suggestions", "#{{ $name }}", @json($formFieldArray), "{{ $index }}")' {{ $required ? "required" : "" }} minlength="3">
              @endif
              <div class="suggestion_birth">
                <ul id="{{ $name }}_suggestions"></ul>
              </div>
            </div>
          </div>
        @else
          @php
            // Check if this is name field and if it's the only field
            $isNameField = (stripos($label, 'name') !== false || $name == 'name' || $name == 'full_name' || $name == 'first_name');
            $colClass = 'col-md-6 col-6';
            if($isNameField && $otherFieldsCount == 1) {
              $colClass = 'col-6';
            }
          @endphp
          <div class="{{ $colClass }} mb-2">
            <div class="form-group">
              <label for="{{ $name }}" class="form-label fw-semibold mb-1">{{ str_replace(["P1", "P2"], ["Boy", "Girl"], $label) }}</label>
              @if($type == 'time')
                <i class="bi bi-clock ku_time_icon" onclick="handleTimePickerIcon('#{{ $name }}_id')" style="left:275px"></i>
              @endif
              <input type="{{ $type == 'time' ? 'text' : $type }}" name="{{ $name }}" id="{{ $name }}_id" class="form-control form-control-lg {{ $type == 'time' ? 'timepicker' : '' }}" {{ $required ? "required" : "" }} oninput='addFormData(this, @json($formFieldArray), "{{ $index }}")' step="1">
            </div>
          </div>
        @endif
      @endif

    @endif
  @endforeach
@endfor

<!-- Single Gotra Field (for entire family) -->
<div class="col-12 mb-3">
  <div class="form-group">
    <label for="gotra" class="form-label fw-semibold mb-1">Gotra</label>
    <div class="row">
      <div class="col-6">
        <input
          type="text"
          name="gotra"
          id="gotra"
          class="form-control form-control-lg"
          placeholder="Enter Gotra"
          oninput='addFormData(this, {"type":"text","label":"Gotra","name":"gotra"}, "{{ $gotraIndex }}")'
        >
      </div>
      <div class="col-6">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="gotra_unknown" name="gotra_unknown" value="I do not know my gotra" onchange="handleGotraCheckbox(this)">
          <label class="form-check-label" for="gotra_unknown" style="font-size: 14px;">
            I do not know my gotra
          </label>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Ashirwad Box Section -->
<div class="col-12 mb-3">
  <div class="border rounded p-3" style="background-color:#fff;">
    <h6 class="fw-semibold mb-2">Would you like to receive the Ashirwad box?</h6>
    <p class="mb-3" style="font-size: 14px;">The Ashirwad Box will contain divine blessing elements such as Ganga Jal, and more, sourced from sacred Tirth locations.</p>

    <div class="d-flex gap-2 mb-3">
      <button type="button" class="btn ashirwad-yes-btn" id="ashirwad_yes" onclick="handleAshirwadOption('yes')" style="background-color: #fff; color: #6c757d; border: 2px solid #6c757d;">Yes</button>
      <button type="button" class="btn ashirwad-no-btn active" id="ashirwad_no" onclick="handleAshirwadOption('no')" style="background-color: #0e4677; color: #fff; border-color: #0e4677;">No</button>
    </div>

    <!-- Address Selection (shown when Yes is selected) -->
    <div id="ashirwad_address_section" style="display: none;">
      <div class="mb-3">
        <h6 class="fw-semibold mb-3 my-md-2">Select Delivery Address</h6>
        <!-- Addresses List -->
        <div id="ashirwad_addresses_list" class="mb-3">
          <!-- Addresses will be loaded here via JavaScript -->
        </div>

        <!-- Add New Address Option -->
        <div class="mb-3">
          <div class="d-flex align-items-center text-blue" style="cursor: pointer;" onclick="toggleNewAddressForm()">
            <i class="bi bi-plus-circle me-2 fs-5"></i>
            <span class="fw-semibold">Add a new delivery address</span>
          </div>
        </div>

        <!-- New Address Form -->
        <div id="ashirwad_new_address_form" style="display: none;" class="border rounded p-3 mt-3">
          <h6 class="fw-semibold mb-3">New delivery address</h6>
          <div class="row">
            <div class="col-12 mb-2">
              <label for="ashirwad_address" class="form-label fw-semibold mb-1">Street/House Address <span class="text-danger">*</span></label>
              <textarea class="form-control form-control-lg" id="ashirwad_address" name="ashirwad_address" rows="2" placeholder="Enter your address"></textarea>
            </div>
            <div class="col-md-4 mb-2">
              <label for="ashirwad_state_id" class="form-label fw-semibold mb-1">State <span class="text-danger">*</span></label>
              <select class="form-select form-select-lg" id="ashirwad_state_id" name="ashirwad_state_id" onchange="fetchCitiesForAshirwad(this)">
                <option value="">Select State</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label for="ashirwad_city_id" class="form-label fw-semibold mb-1">City <span class="text-danger">*</span></label>
              <select class="form-select form-select-lg" id="ashirwad_city_id" name="ashirwad_city_id">
                <option value="">Select City</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label for="ashirwad_pincode" class="form-label fw-semibold mb-1">Pincode <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-lg" id="ashirwad_pincode" name="ashirwad_pincode" placeholder="Enter pincode" maxlength="6">
            </div>
            <div class="col-12 text-end">
              <button type="button" class="btn btn-secondary btn-sm" onclick="saveNewAshirwadAddress()">Save Address</button>
              <!-- <button type="button" class="btn btn-outline btn-sm" onclick="toggleNewAddressForm()">Cancel</button> -->

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
