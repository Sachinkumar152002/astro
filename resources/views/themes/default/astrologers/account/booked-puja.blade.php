@extends(themeView().'layout')

@section('title', 'Booked Puja')
@section('keywords', '')
@section('description','')

@push('style')
<style>
  .puja-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
  }
  .puja-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
  }
  .puja-card-image {
    width: 100%;
    object-fit: cover;
  }
  .puja-card-body {
    padding: 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .puja-card-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    line-height: 1.4;
  }
  .order-info {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
  }
  .order-info-item {
    margin-bottom: 5px;
  }
  .status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }
  .status-assigned {
    background-color: #28a745;
    color: white;
  }
  .puja-card-buttons {
    display: flex;
    gap: 8px;
    width: 100%;
    box-sizing: border-box;
  }

  /* Table Styling for Puja Listing */
  .table thead th {
    font-weight: 600;
    font-size: 14px;
    padding: 12px 15px;
    vertical-align: middle;
  }
  .table-header-border {
    border-right: 1px solid rgba(255,255,255,0.3);
  }
  .table thead th:last-child {
    border-right: none;
  }
  .table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
  }
  .table tbody tr:hover {
    background-color: #f8f9fa !important;
  }
  .badge {
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 4px;
  }
  .icon-size {
    font-size: 1.2rem;
  }
  .puja-card-buttons .btn {
    flex: 1;
    white-space: nowrap;
    padding: 6px 8px;
    font-size: 11px;
    border-radius: 4px;
    border: 1px solid transparent;
    transition: all 0.3s ease;
    line-height: 1.2;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .puja-card-buttons .btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
  }
  .modal .close {
    padding: 0;
    background-color: transparent;
    border: 0;
    opacity: 0.5;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    cursor: pointer;
  }
  .modal .close:hover {
    opacity: 0.75;
  }
  .family-dropdown-item {
    border-bottom: 1px solid #e0e0e0;
    padding: 8px 0;
  }
  .family-dropdown-item:last-child {
    border-bottom: none;
  }
  .family-header {
    display: flex;
    align-items: center;
    padding: 8px;
    cursor: pointer;
    background-color: #f8f9fa;
    border-radius: 4px;
    margin-bottom: 5px;
    transition: background-color 0.2s;
  }
  .family-header:hover {
    background-color: #e9ecef;
  }
  .family-header input[type="checkbox"] {
    margin-right: 10px;
    cursor: pointer;
    width: 18px;
    height: 18px;
  }
  .family-header-label {
    flex: 1;
    font-weight: 600;
    color: #333;
    cursor: pointer;
    user-select: none;
  }
  .family-toggle-icon {
    margin-left: auto;
    font-size: 12px;
    color: #666;
    transition: transform 0.2s;
  }
  .family-toggle-icon.expanded {
    transform: rotate(90deg);
  }
  .family-members {
    padding-left: 30px;
    padding-top: 5px;
    display: none;
  }
  .family-members.show {
    display: block;
  }
  .family-member-item {
    padding: 5px 8px;
    color: #555;
    font-size: 14px;
    border-left: 2px solid #ddd;
    margin-left: 10px;
    margin-bottom: 3px;
  }
  .family-member-item:last-child {
    margin-bottom: 0;
  }
</style>
@endpush

@section('content')
@php
  $astrologer = auth()->guard('astrologer')->user();
@endphp

<section class="dashboad_section" style="background: #f2f2f2;">
  <div class="container">
    <div class="row">
      @include(themeView().'astrologers._partials.sidebar')
      <div class="col-lg-9 col-12 mt-2 mt-md-5 mt-lg-0">
        <div class="tab-scroll-wrapper ps-2">
          <!-- Tabs Navigation -->
          <ul class="nav nav-tabs mb-3" id="bookedPujaTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="one-to-one-tab" data-bs-toggle="tab" data-bs-target="#one-to-one" type="button" role="tab" aria-controls="one-to-one" aria-selected="true">
                One to One Puja
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="group-tab" data-bs-toggle="tab" data-bs-target="#group" type="button" role="tab" aria-controls="group" aria-selected="false">
                Family Puja
              </button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content" id="bookedPujaTabContent">
            <!-- One to One Puja Tab -->
            <div class="tab-pane fade show active" id="one-to-one" role="tabpanel" aria-labelledby="one-to-one-tab">
              <div class="card ">
                <div class="card-body p-0 booked-p border-n radius">
                  @if($oneToOneBookedPujas->count() > 0)
                    <div class="table-responsive">
                      <table class="table table-hover mb-0">
                        <thead>
                          <tr class="bg-primary text-white">
                            <th class="table-header-border">#</th>
                            <th class="table-header-border">Puja Name</th>
                            <th class="table-header-border">Customer Name</th>
                            <th class="table-header-border">Gotra</th>
                            <th class="table-header-border">Booking Date</th>
                            <th class="table-header-border">Puja Scheduled</th>
                            <th class="text-right">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($oneToOneBookedPujas as $index => $assignment)
                            <tr class="{{ $index % 2 == 0 ? 'bg-light' : 'bg-white' }}">
                              <td>{{ $index + 1 }}</td>
                              <td>
                                @if($assignment->product)
                                  {{ $assignment->product->title }}
                                @else
                                  N/A
                                @endif
                              </td>
                              <td>
                                @if($assignment->order && $assignment->order->user)
                                  {{ $assignment->order->user->name ?? 'N/A' }}
                                @else
                                  N/A
                                @endif
                              </td>
                              <td>
                                @php
                                  $gotra = 'N/A';
                                  if($assignment->orderItem && $assignment->orderItem->options) {
                                    $options = json_decode($assignment->orderItem->options, true);
                                    if(isset($options['formdata']) && is_array($options['formdata'])) {
                                      foreach($options['formdata'] as $formField) {
                                        $itemName = isset($formField['name']) ? strtolower(trim($formField['name'])) : '';
                                        $isGotraField = ($itemName == 'gotra' || $itemName == 'one_gotra' ||
                                                       (isset($formField['label']) && stripos(strtolower(trim($formField['label'])), 'gotra') !== false));
                                        if($isGotraField && isset($formField['value']) && !empty(trim($formField['value']))) {
                                          $gotraValue = trim($formField['value']);
                                          if($gotraValue != 'I do not know my gotra' && !empty($gotraValue)) {
                                            $gotra = $gotraValue;
                                            break;
                                          }
                                        }
                                      }
                                    }
                                  }
                                @endphp
                                {{ $gotra }}
                              </td>
                              <td>
                                {{ $assignment->created_at ? (is_string($assignment->created_at) ? date('d M Y', strtotime($assignment->created_at)) : $assignment->created_at->format('d M Y')) : 'N/A' }}
                              </td>
                              <td>
                                @if($assignment->mahurat && $assignment->mahurat->date && $assignment->mahurat->time)
                                  <span class="badge bg-success">{{ $assignment->mahurat->date->format('d M Y') }} at {{ date('h:i A', strtotime($assignment->mahurat->time)) }}</span>
                                  @if($assignment->product && !empty($assignment->product->video_call_url))
                                    <a href="{{ $assignment->product->video_call_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success ms-2" title="Join video call">
                                      <i class="bi bi-camera-video-fill"></i> Join
                                    </a>
                                  @endif
                                @else
                                  <a href="{{ route('astrologer.booked.puja.detail', ['id' => $assignment->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-circle"></i> Add Date
                                  </a>
                                @endif
                              </td>
                              <td class="text-right">
                                <a href="{{ route('astrologer.booked.puja.detail', ['id' => $assignment->id]) }}" class="btn btn-sm btn-info" title="View">
                                  <i class="bi bi-eye"></i>
                                </a>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @else
                    <div class="text-center py-5">
                      <p class="text-muted">No one-to-one pujas booked yet.</p>
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <!-- Family Puja Tab -->
            <div class="tab-pane fade" id="group" role="tabpanel" aria-labelledby="group-tab">
              <div class="card border-n radius">
                <div class="card-body">
                  @if($groupPujaProducts->count() > 0)
                    <div class="row g-4">
                      @foreach($groupPujaProducts as $puja)
                        <div class="col-md-4 col-sm-6">
                          <div class="card">
                            @if($puja->image)
                              <img src="{{ $puja->image->image(400, 400) }}"
                                   alt="{{ $puja->title ?? 'Puja' }}"
                                   class="card-img-top puja-card-image">
                            @else
                              <img src="{{ no_image() }}"
                                   alt="Puja"
                                   class="card-img-top puja-card-image">
                            @endif
                            <div class="card-body puja-card-body booked-p">
                              <h5 class="card-title puja-card-title">{{ $puja->title ?? 'Puja #' . $puja->id }}</h5>
                              @if(!empty($puja->display_valid_till_datetime))
                                <p class="text-muted small mb-2">Join Date: {{ $puja->display_valid_till_datetime->format('d M Y, h:i A') }}</p>
                              @endif
                              @php
                                $familyRefs = $puja->orderItems ? $puja->orderItems->pluck('order.group_puja_reference')->filter()->unique()->values() : collect();
                                $familyCount = $familyRefs->count();
                                $hasFamilies = $familyCount > 0;
                                $showUploadMedia = $hasFamilies && ($puja->show_upload_media_button ?? false);
                                $uploadAllowedAfter = $puja->upload_media_allowed_after ?? null;
                                $uploadAllowedLabel = $uploadAllowedAfter ? $uploadAllowedAfter->format('d M Y, h:i A') : '';
                              @endphp
                              <div class="puja-card-buttons d-flex flex-wrap gap-2">
                                @if($familyCount > 0 && $showUploadMedia)
                                  <button type="button" class="btn btn-secondary btn-sm text-white" onclick="openGroupPujaMediaModal({{ $puja->id }})">
                                    Upload Media
                                  </button>
                                @endif
                                @if(!empty($puja->video_call_url))
                                  @php
                                    $pujaComplete = !empty($puja->display_valid_till_datetime) && $puja->display_valid_till_datetime->copy()->addHours(2)->isPast();
                                    $joinStart = !empty($puja->display_valid_till_datetime) ? $puja->display_valid_till_datetime->copy()->subMinutes(15) : null;
                                    $beforeJoinWindow = $joinStart && $joinStart->isFuture();
                                  @endphp
                                  @if(empty($puja->show_join_button) && !empty($puja->display_valid_till_datetime) && !$pujaComplete && $beforeJoinWindow)
                                    <p class="text-muted small mb-0 w-100">Puja join link available 15 min before</p>
                                  @endif
                                  @if($puja->show_join_button ?? false)
                                    <a href="{{ $puja->video_call_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm text-white text-center d-inline-flex align-items-center justify-content-center" style="min-width: fit-content;">
                                      Join
                                    </a>
                                  @endif
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @else
                    <div class="text-center py-5">
                      <p class="text-muted">No family pujas booked yet.</p>
                    </div>
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

<!-- Puja Media Upload Modal -->
<div class="modal fade" id="pujaMediaModal" tabindex="-1" role="dialog" aria-labelledby="pujaMediaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pujaMediaModalLabel">Upload Puja Media</h5>
        <button type="button" class="close" onclick="closePujaMediaModal()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="pujaMediaForm" enctype="multipart/form-data">
          <input type="hidden" id="assignment_id" name="assignment_id">
          <input type="hidden" id="product_id" name="product_id">
          <input type="hidden" id="order_id" name="order_id">

          <div class="form-group">
            <label><strong>Upload Photos</strong> <span class="badge badge-info" id="photo-count-badge">(0/5)</span></label>
            <div class="d-flex justify-content-between mb-2">
              <div>
                <button type="button" onclick="addPhotoField()" class="btn btn-secondary btn-sm" style="height: auto;" id="add-photo-btn">
                  <i class="bi bi-plus"></i> Add More Photo
                </button>
              </div>
            </div>
            <div class="row" id="photo-gallery">
              <!-- Photo fields will be added here -->
            </div>
          </div>

          <div class="form-group">
            <label><strong>Upload Video</strong></label>
            <div class="mb-2">
              <input type="file" id="video_file" name="video_file" accept="video/*" class="form-control" onchange="previewVideo(this)">
              <small class="text-muted">Only video files allowed (mp4, mov, avi, etc.)</small>
            </div>
            <div id="video-preview" class="mt-2">
              <!-- Video preview will be shown here -->
            </div>
            <div id="video-delete-btn" style="display: none;" class="mt-2">
              <button type="button" class="btn btn-sm btn-danger" onclick="deleteVideo()">
                <i class="fa fa-trash"></i> Delete Video
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closePujaMediaModal()">Close</button>
        <button type="button" class="btn btn-secondary" onclick="savePujaMedia()">Save Media</button>
      </div>
    </div>
  </div>
</div>

<!-- Book Mahurat Modal -->
<div class="modal fade" id="bookMahuratModal" tabindex="-1" role="dialog" aria-labelledby="bookMahuratModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookMahuratModalLabel">Book Mahurat</h5>
        <button type="button" class="close" onclick="closeBookMahuratModal()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="bookMahuratForm">
          <input type="hidden" id="mahurat_assignment_id" name="assignment_id">

          <div class="form-group">
            <label for="mahurat_date"><strong>Date</strong> <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="mahurat_date" name="date" required>
            <small class="text-muted">Select the date for mahurat</small>
          </div>

          <div class="form-group">
            <label for="mahurat_time"><strong>Time</strong> <span class="text-danger">*</span></label>
            <input type="time" class="form-control" id="mahurat_time" name="time" required>
            <small class="text-muted">Select the time for mahurat</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeBookMahuratModal()">Close</button>
        <button type="button" class="btn btn-secondary" onclick="saveMahurat()">Save Mahurat</button>
      </div>
    </div>
  </div>
</div>

<!-- Group Puja Media Upload Modal -->
<div class="modal fade" id="groupPujaMediaModal" tabindex="-1" role="dialog" aria-labelledby="groupPujaMediaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg mt-5 pt-5" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="groupPujaMediaModalLabel">Upload Family Puja Media</h5>
        <button type="button" class="close" onclick="closeGroupPujaMediaModal()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="groupPujaMediaForm" enctype="multipart/form-data">
          <input type="hidden" id="group_product_id" name="product_id">

          <div class="form-group mb-3">
            <label><strong>Select Families</strong> <span class="text-danger">*</span></label>
            <div id="family-dropdown-container" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; padding: 10px;">
              <!-- Families will be loaded here -->
            </div>
            <small class="text-muted">Click on family reference to expand and see members. Select families by checking the boxes.</small>
            <div id="family-loading" class="text-center py-3" style="display: none;">
              <span class="spinner-border spinner-border-sm" role="status"></span> Loading families...
            </div>
          </div>
          <input type="hidden" id="selected_family_references" name="family_references">

          <div class="form-group">
            <label class="mb-3 mb-lg-0"><strong>Upload Photos</strong></label>
            <div class="d-flex justify-content-between mb-2">
              <div>
                <button type="button" onclick="addGroupPhotoField()" class="btn btn-secondary btn-sm" style="height: auto;">
                  <i class="bi bi-plus"></i> Add More Photo
                </button>
              </div>
            </div>
            <div class="row" id="group-photo-gallery">
              <!-- Photo fields will be added here -->
            </div>
          </div>

          <div class="form-group">
            <label><strong>Upload Video</strong></label>
            <div class="mb-2">
              <input type="file" id="group_video_file" name="video_file" accept="video/*" class="form-control" onchange="previewGroupVideo(this)">
              <small class="text-muted">Only video files allowed (mp4, mov, avi, etc.)</small>
            </div>
            <div id="group-video-preview" class="mt-2">
              <!-- Video preview will be shown here -->
            </div>
            <div id="group-video-delete-btn" style="display: none;" class="mt-2">
              <button type="button" class="btn btn-sm btn-danger" onclick="deleteGroupVideo()">
                <i class="fa fa-trash"></i> Delete Video
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeGroupPujaMediaModal()">Close</button>
        <button type="button" class="btn btn-secondary" onclick="saveGroupPujaMedia()">Save Media</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
var photoCount = 0;
var currentAssignmentId = null;
var uploadedPhotoIds = [];
var uploadedVideoId = null;

function openPujaMediaModal(assignmentId, productId, orderId) {
  currentAssignmentId = assignmentId;
  $('#assignment_id').val(assignmentId);
  $('#product_id').val(productId);
  $('#order_id').val(orderId);

  // Clear previous data
  $('#photo-gallery').empty();
  photoCount = 0;
  uploadedPhotoIds = [];
  uploadedVideoId = null;
  $('#video_file').val('');
  $('#video-preview').empty();

  // Add one photo field by default
  addPhotoField();

  // Load existing media if any
  loadExistingMedia(assignmentId);

  // Show modal
  $('#pujaMediaModal').modal('show');
}

function closePujaMediaModal() {
  $('#pujaMediaModal').modal('hide');
}

function addPhotoField() {
  // Check maximum 5 photos limit
  if(photoCount >= 5) {
    toastr.warning('Maximum 5 images allowed per puja');
    return;
  }
  let photoDiv = document.getElementById('photo-gallery');
  let currentPhotoId = 'puja_photo_' + photoCount;

  let newSection = document.createElement('div');
  newSection.classList.add("form-group", "col-md-4", "col-sm-6", "mb-3");
  newSection.innerHTML = `
    <div class="border p-2 rounded">
      <input type="file" id="${currentPhotoId}" name="photo_files[]" accept="image/*" class="form-control mb-2" onchange="previewImage(this, '${currentPhotoId}')">
      <input type="hidden" id="${currentPhotoId}_id" name="photo_ids[]" value="">
      <div id="${currentPhotoId}_preview" class="mb-2" style="min-height: 100px;">
        <!-- Image preview will be shown here -->
      </div>
      <button type="button" class="btn btn-sm btn-secondary btn-block" onclick="removePhotoField(this)" style="height: auto;">
        <i class="fa fa-trash"></i> Remove
      </button>
    </div>
  `;
  photoDiv.appendChild(newSection);
  photoCount++;
  updatePhotoCountBadge();
}

function updatePhotoCountBadge() {
  $('#photo-count-badge').text('(' + photoCount + '/5)');
  if(photoCount >= 5) {
    $('#add-photo-btn').prop('disabled', true);
  } else {
    $('#add-photo-btn').prop('disabled', false);
  }
}

function removePhotoField(button) {
  let container = $(button).closest('.form-group');
  let photoId = container.find('input[type="file"]').attr('id');
  let hiddenPhotoId = $('#' + photoId + '_id').val();

  // Remove from uploadedPhotoIds array if exists
  if(hiddenPhotoId) {
    uploadedPhotoIds = uploadedPhotoIds.filter(id => id != hiddenPhotoId);
  }

  container.remove();
  if(photoCount > 0) {
    photoCount--;
  }
  updatePhotoCountBadge();
}

function previewImage(input, photoId) {
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    reader.onload = function(e) {
      let previewDiv = $('#' + photoId + '_preview');
      previewDiv.html('<img src="' + e.target.result + '" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">');

      // Upload image immediately
      uploadPhoto(input.files[0], photoId);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function uploadPhoto(file, photoId) {
  let formData = new FormData();
  formData.append('file', file);
  formData.append('file_type', 'image_id');
  formData.append('_token', '{{ csrf_token() }}');

  $.ajax({
    url: '{{ route("astrologer.upload") }}',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      if(response.value) {
        $('#' + photoId + '_id').val(response.value);
        uploadedPhotoIds.push(response.value);
        toastr.success('Photo uploaded successfully');
      }
    },
    error: function(xhr) {
      toastr.error('Error uploading photo. Please try again.');
      console.error('Upload error:', xhr);
    }
  });
}

function previewVideo(input) {
  if (input.files && input.files[0]) {
    let file = input.files[0];
    let fileType = file.type;

    if(fileType.startsWith('video/')) {
      let reader = new FileReader();
      reader.onload = function(e) {
        let previewDiv = $('#video-preview');
        previewDiv.html(`
          <video controls class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
            <source src="${e.target.result}" type="${fileType}">
            Your browser does not support the video tag.
          </video>
          <p class="mt-2"><strong>Selected:</strong> ${file.name}</p>
        `);

        // Show delete button
        $('#video-delete-btn').show();

        // Upload video immediately
        uploadVideo(file);
      };
      reader.readAsDataURL(file);
    } else {
      toastr.error('Please select a valid video file');
      input.value = '';
      $('#video-preview').empty();
      $('#video-delete-btn').hide();
    }
  }
}

function deleteVideo() {
  uploadedVideoId = null;
  $('#video_file').val('');
  $('#video-preview').empty();
  $('#video-delete-btn').hide();
  toastr.success('Video removed');
}

function uploadVideo(file) {
  let formData = new FormData();
  formData.append('file', file);
  formData.append('file_type', 'video');
  formData.append('_token', '{{ csrf_token() }}');

  $.ajax({
    url: '{{ route("astrologer.upload") }}',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      if(response.value) {
        uploadedVideoId = response.value;
        toastr.success('Video uploaded successfully');
      }
    },
    error: function(xhr) {
      toastr.error('Error uploading video. Please try again.');
      console.error('Upload error:', xhr);
    }
  });
}


function getVideoPlayerHtml(videoUrl) {
  if (!videoUrl) return '';
  var lower = videoUrl.toLowerCase();
  var isYoutube = lower.indexOf('youtube.com') !== -1 || lower.indexOf('youtu.be') !== -1;
  var isVimeo = lower.indexOf('vimeo.com') !== -1;
  var embedUrl = videoUrl;
  if (isYoutube) {
    var id = '';
    if (lower.indexOf('youtu.be/') !== -1) {
      id = videoUrl.split('youtu.be/')[1].split('?')[0].split('&')[0] || '';
    } else {
      var m = videoUrl.match(/(?:youtube\.com\/watch\?v=|youtube\.com\/embed\/)([^&]+)/);
      id = m ? m[1] : '';
    }
    if (id) embedUrl = 'https://www.youtube.com/embed/' + id;
  } else if (isVimeo) {
    var vm = videoUrl.match(/vimeo\.com\/(?:video\/)?(\d+)/);
    if (vm) embedUrl = 'https://player.vimeo.com/video/' + vm[1];
  }
  if (isYoutube || isVimeo) {
    return '<div class="embed-responsive embed-responsive-16by9" style="max-height: 300px;"><iframe class="embed-responsive-item" src="' + embedUrl + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;height:100%;min-height:250px;"></iframe></div>';
  }
  return '<video controls class="img-thumbnail" style="max-width: 100%; max-height: 300px;"><source src="' + videoUrl + '" type="video/mp4">Your browser does not support the video tag.</video>';
}

function loadExistingMedia(assignmentId) {
  // Load existing uploaded media for this assignment
  var getUrl = '{{ route("astrologer.booked.puja.media.get") }}';

  $.ajax({
    url: getUrl,
    type: 'GET',
    data: { assignment_id: assignmentId },
    success: function(response) {
      if(response.success && response.media) {
        // Clear existing photo fields first
        $('#photo-gallery').empty();
        photoCount = 0;
        uploadedPhotoIds = [];

        // Load photos
        if(response.media.photos && response.media.photos.length > 0) {
          response.media.photos.forEach(function(photo, index) {
            if(photoCount < 5) {
              addPhotoField();
              let currentPhotoId = 'puja_photo_' + (photoCount - 1);
              $('#' + currentPhotoId + '_id').val(photo.media_id);
              $('#' + currentPhotoId + '_preview').html('<img src="' + photo.image_url + '" alt="preview" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">');
              uploadedPhotoIds.push(photo.media_id);
            }
          });
        } else {
          // Add one empty photo field if no existing photos
          if(photoCount < 5) {
            addPhotoField();
          }
        }
        updatePhotoCountBadge();

        // Load video (support direct URL and YouTube/Vimeo embed)
        if(response.media.video && response.media.video.video_id) {
          uploadedVideoId = response.media.video.video_id;
          if(response.media.video.video_url) {
            $('#video-preview').html(getVideoPlayerHtml(response.media.video.video_url));
          }
        }
      } else {
        // Add one empty photo field if no media found
        if(photoCount < 5) {
          addPhotoField();
        }
      }
      updatePhotoCountBadge();
    },
    error: function() {
      // No existing media or error loading - ensure we have at least one photo field
      if(photoCount === 0 && photoCount < 5) {
        addPhotoField();
      }
      updatePhotoCountBadge();
    }
  });
}

function savePujaMedia() {
  // Collect all photo IDs from hidden inputs
  var photoIds = [];
  $('input[name="photo_ids[]"]').each(function() {
    if($(this).val()) {
      photoIds.push($(this).val());
    }
  });

  // Collect photo IDs from uploadedPhotoIds array (for files uploaded via file input)
  uploadedPhotoIds.forEach(function(id) {
    if(id && !photoIds.includes(id)) {
      photoIds.push(id);
    }
  });

  // Collect all data
  var formData = {
    assignment_id: $('#assignment_id').val(),
    product_id: $('#product_id').val(),
    order_id: $('#order_id').val(),
    photo_ids: photoIds.length > 0 ? photoIds : [],
    video_id: uploadedVideoId || null,
    _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
  };

  // Generate URL - use direct URL to avoid route helper issues
  var saveUrl = '{{ url("/astrologer/booked-puja-media") }}';

  // Debug: Log URL and data
  console.log('Saving media to URL:', saveUrl);
  console.log('Form data:', formData);
  console.log('Photo IDs count:', photoIds.length);
  console.log('Video ID:', uploadedVideoId);
  console.log('CSRF Token available:', $('meta[name="csrf-token"]').length > 0);
  console.log('CSRF Token value:', $('meta[name="csrf-token"]').attr('content'));

  $.ajax({
    url: saveUrl,
    type: 'POST',
    data: formData,
    dataType: 'json',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
    success: function(response) {
      if(response.success) {
        toastr.success(response.message || 'Media saved successfully');
        closePujaMediaModal();
      } else {
        toastr.error(response.message || 'Error saving media');
      }
    },
    error: function(xhr) {
      console.error('Save error:', xhr);
      console.error('URL:', saveUrl);
      console.error('Status:', xhr.status);
      console.error('Response:', xhr.responseText);

      if(xhr.status === 404) {
        toastr.error('Route not found. Please check the route configuration.');
      } else if(xhr.status === 403) {
        toastr.error('Permission denied. Please check your assignment.');
      } else if(xhr.status === 419) {
        toastr.error('CSRF token mismatch. Please refresh the page and try again.');
      } else {
        var errors = xhr.responseJSON?.errors || {};
        var message = xhr.responseJSON?.message || 'Error saving media';
        toastr.error(message);

        // Show field errors
        for(var field in errors) {
          toastr.error(errors[field][0]);
        }
      }
    }
  });
}

function openBookMahuratModal(assignmentId) {
  $('#mahurat_assignment_id').val(assignmentId);

  // Load existing mahurat if any
  loadExistingMahurat(assignmentId);

  // Show modal
  $('#bookMahuratModal').modal('show');
}

function closeBookMahuratModal() {
  $('#bookMahuratModal').modal('hide');
}

function loadExistingMahurat(assignmentId) {
  var getUrl = '{{ route("astrologer.booked.puja.mahurat.get") }}';

  $.ajax({
    url: getUrl,
    type: 'GET',
    data: { assignment_id: assignmentId },
    success: function(response) {
      if(response.success && response.mahurat) {
        if(response.mahurat.date) {
          $('#mahurat_date').val(response.mahurat.date);
        }
        if(response.mahurat.time) {
          // Format time to HH:mm format
          var timeValue = response.mahurat.time;
          if(timeValue.length === 5) {
            $('#mahurat_time').val(timeValue);
          } else {
            // Convert to HH:mm if needed
            var timeParts = timeValue.split(':');
            $('#mahurat_time').val(timeParts[0] + ':' + timeParts[1]);
          }
        }
      } else {
        // Clear fields if no mahurat found
        $('#mahurat_date').val('');
        $('#mahurat_time').val('');
      }
    },
    error: function() {
      // Clear fields on error
      $('#mahurat_date').val('');
      $('#mahurat_time').val('');
    }
  });
}

function saveMahurat() {
  var assignmentId = $('#mahurat_assignment_id').val();
  var date = $('#mahurat_date').val();
  var time = $('#mahurat_time').val();

  if(!date || !time) {
    toastr.error('Please fill in both date and time fields');
    return;
  }

  var formData = {
    assignment_id: assignmentId,
    date: date,
    time: time,
    _token: $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
  };

  var saveUrl = '{{ url("/astrologer/booked-puja-mahurat") }}';

  $.ajax({
    url: saveUrl,
    type: 'POST',
    data: formData,
    dataType: 'json',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
    success: function(response) {
      if(response.success) {
        toastr.success(response.message || 'Mahurat saved successfully');
        closeBookMahuratModal();

        // Update button text and style
        var assignmentId = $('#mahurat_assignment_id').val();
        var btnId = '#mahurat-btn-' + assignmentId;
        $(btnId).removeClass('btn-secondary').addClass('btn-success');
        $(btnId).text('Mahurat Booked');

        // Add mahurat info to order-info section if not already present
        var orderInfoSelector = $(btnId).closest('.puja-card-body').find('.order-info');
        if($(orderInfoSelector).find('.mahurat-info').length === 0) {
          var date = $('#mahurat_date').val();
          var time = $('#mahurat_time').val();
          if(date && time) {
            // Format date
            var dateObj = new Date(date);
            var formattedDate = dateObj.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

            // Format time
            var timeParts = time.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = timeParts[1];
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            var formattedTime = hours + ':' + minutes + ' ' + ampm;

            var mahuratHtml = '<div class="order-info-item mahurat-info" style="background: #d4edda; color: #155724; margin-top: 5px;">' +
              '<strong>Mahurat:</strong> ' + formattedDate + ' at ' + formattedTime +
              '</div>';
            $(orderInfoSelector).append(mahuratHtml);
          }
        }
      } else {
        toastr.error(response.message || 'Error saving mahurat');
      }
    },
    error: function(xhr) {
      console.error('Save error:', xhr);

      if(xhr.status === 404) {
        toastr.error('Route not found. Please check the route configuration.');
      } else if(xhr.status === 403) {
        toastr.error('Permission denied. Please check your assignment.');
      } else if(xhr.status === 419) {
        toastr.error('CSRF token mismatch. Please refresh the page and try again.');
      } else {
        var errors = xhr.responseJSON?.errors || {};
        var message = xhr.responseJSON?.message || 'Error saving mahurat';
        toastr.error(message);

        // Show field errors
        for(var field in errors) {
          toastr.error(errors[field][0]);
        }
      }
    }
  });
}

// Group Puja Media Functions
var groupPhotoCount = 0;
var currentGroupProductId = null;
var uploadedGroupPhotoIds = [];
var uploadedGroupVideoId = null;
var groupFamilies = [];

function openGroupPujaMediaModal(productId) {
  currentGroupProductId = productId;
  $('#group_product_id').val(productId);

  // Clear previous data
  $('#group-photo-gallery').empty();
  $('#family-dropdown-container').empty();
  $('#selected_family_references').val('');
  groupPhotoCount = 0;
  uploadedGroupPhotoIds = [];
  uploadedGroupVideoId = null;
  $('#group_video_file').val('');
  $('#group-video-preview').empty();
  $('#group-video-delete-btn').hide();
  groupFamilies = [];

  // Show loading
  $('#family-loading').show();

  // Load families
  loadGroupPujaFamilies(productId);

  // Add one photo field by default
  addGroupPhotoField();

  // Show modal
  $('#groupPujaMediaModal').modal('show');
}

function closeGroupPujaMediaModal() {
  $('#groupPujaMediaModal').modal('hide');
}

function loadGroupPujaFamilies(productId) {
  $.ajax({
    url: '{{ route("astrologer.group.puja.families") }}',
    type: 'GET',
    data: { product_id: productId },
    dataType: 'json',
    success: function(response) {
      $('#family-loading').hide();
      if(response.success && response.families.length > 0) {
        groupFamilies = response.families;
        let dropdownHtml = '';
        response.families.forEach(function(family, index) {
          let familyId = 'family_' + index;
          let membersHtml = '';

          // Build members list
          if(family.persons && family.persons.length > 0) {
            family.persons.forEach(function(person) {
              let personName = typeof person === 'object' ? person.name : person;
              let personLabel = typeof person === 'object' && person.label ? person.label : 'Name';
              membersHtml += '<div class="family-member-item">' +
                            '<strong>' + personLabel + ':</strong> ' + personName +
                            '</div>';
            });
          } else {
            membersHtml = '<div class="family-member-item text-muted">No members found</div>';
          }

          dropdownHtml += '<div class="family-dropdown-item">' +
            '<div class="family-header" onclick="toggleFamily(\'' + familyId + '\')">' +
            '<input type="checkbox" class="family-checkbox" value="' + family.reference + '" id="' + familyId + '_checkbox" onclick="event.stopPropagation(); updateSelectedFamilies();">' +
            '<label class="family-header-label" for="' + familyId + '_checkbox">' + family.reference + '</label>' +
            '<span class="family-toggle-icon" id="' + familyId + '_icon">▶</span>' +
            '</div>' +
            '<div class="family-members" id="' + familyId + '_members">' + membersHtml + '</div>' +
            '</div>';
        });
        $('#family-dropdown-container').html(dropdownHtml);
      } else {
        $('#family-dropdown-container').html('<div class="text-center text-muted py-3">No families available for this puja</div>');
        toastr.warning('No families available for this puja');
      }
    },
    error: function(xhr) {
      $('#family-loading').hide();
      toastr.error('Error loading families. Please try again.');
    }
  });
}

function toggleFamily(familyId) {
  let membersDiv = $('#' + familyId + '_members');
  let icon = $('#' + familyId + '_icon');

  if(membersDiv.hasClass('show')) {
    membersDiv.removeClass('show');
    icon.removeClass('expanded');
  } else {
    membersDiv.addClass('show');
    icon.addClass('expanded');
  }
}

function updateSelectedFamilies() {
  let selectedFamilies = [];
  $('.family-checkbox:checked').each(function() {
    selectedFamilies.push($(this).val());
  });
  $('#selected_family_references').val(selectedFamilies.join(','));
}

function addGroupPhotoField() {
  let photoDiv = document.getElementById('group-photo-gallery');
  let currentPhotoId = 'group_puja_photo_' + groupPhotoCount;

  let newSection = document.createElement('div');
  newSection.classList.add("form-group", "col-md-4", "col-sm-6", "mb-3");
  newSection.innerHTML = `
    <div class="border p-2 rounded">
      <input type="file" id="${currentPhotoId}" name="photo_files[]" accept="image/*" class="form-control mb-2" onchange="previewGroupImage(this, '${currentPhotoId}')">
      <input type="hidden" id="${currentPhotoId}_id" name="photo_ids[]" value="">
      <div id="${currentPhotoId}_preview" class="mb-2" style="min-height: 100px;">
        <!-- Image preview will be shown here -->
      </div>
      <button type="button" class="btn btn-sm btn-secondary btn-block" onclick="removeGroupPhotoField(this)" style="height: auto;">
        <i class="fa fa-trash"></i> Remove
      </button>
    </div>
  `;
  photoDiv.appendChild(newSection);
  groupPhotoCount++;
}

function removeGroupPhotoField(button) {
  let container = $(button).closest('.form-group');
  let photoId = container.find('input[type="file"]').attr('id');
  let hiddenPhotoId = $('#' + photoId + '_id').val();

  if(hiddenPhotoId) {
    uploadedGroupPhotoIds = uploadedGroupPhotoIds.filter(id => id != hiddenPhotoId);
  }

  container.remove();
}

function previewGroupImage(input, photoId) {
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    reader.onload = function(e) {
      let previewDiv = $('#' + photoId + '_preview');
      previewDiv.html('<img src="' + e.target.result + '" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">');

      uploadGroupPhoto(input.files[0], photoId);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function uploadGroupPhoto(file, photoId) {
  let formData = new FormData();
  formData.append('file', file);
  formData.append('file_type', 'image_id');
  formData.append('_token', '{{ csrf_token() }}');

  $.ajax({
    url: '{{ route("astrologer.upload") }}',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      if(response.value) {
        $('#' + photoId + '_id').val(response.value);
        uploadedGroupPhotoIds.push(response.value);
        toastr.success('Photo uploaded successfully');
      }
    },
    error: function(xhr) {
      toastr.error('Error uploading photo. Please try again.');
    }
  });
}

function previewGroupVideo(input) {
  if (input.files && input.files[0]) {
    let file = input.files[0];
    let fileType = file.type;

    // Validate video file type
    if(fileType.startsWith('video/')) {
      let reader = new FileReader();
      reader.onload = function(e) {
        let previewDiv = $('#group-video-preview');
        previewDiv.html(`
          <video controls class="img-thumbnail" style="max-width: 100%; max-height: 300px;">
            <source src="${e.target.result}" type="${fileType}">
            Your browser does not support the video tag.
          </video>
          <p class="mt-2"><strong>Selected:</strong> ${file.name}</p>
        `);

        // Show delete button
        $('#group-video-delete-btn').show();

        // Upload video immediately
        uploadGroupVideo(file);
      };
      reader.readAsDataURL(file);
    } else {
      toastr.error('Please select a valid video file');
      input.value = '';
      $('#group-video-preview').empty();
      $('#group-video-delete-btn').hide();
    }
  }
}

function deleteGroupVideo() {
  uploadedGroupVideoId = null;
  $('#group_video_file').val('');
  $('#group-video-preview').empty();
  $('#group-video-delete-btn').hide();
  toastr.success('Video removed');
}

function uploadGroupVideo(file) {
  let formData = new FormData();
  formData.append('file', file);
  formData.append('file_type', 'video'); // Changed from 'video_id' to 'video'
  formData.append('_token', '{{ csrf_token() }}');

  $.ajax({
    url: '{{ route("astrologer.upload") }}',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      if(response.value) {
        uploadedGroupVideoId = response.value;
        toastr.success('Video uploaded successfully');
      }
    },
    error: function(xhr) {
      let errorMessage = 'Error uploading video. Please try again.';
      if(xhr.responseJSON && xhr.responseJSON.errors) {
        let errors = xhr.responseJSON.errors;
        if(errors.file) {
          errorMessage = errors.file[0];
        }
      }
      toastr.error(errorMessage);
    }
  });
}

function saveGroupPujaMedia() {
  let familyReferencesStr = $('#selected_family_references').val();
  let familyReferences = [];

  if(familyReferencesStr) {
    familyReferences = familyReferencesStr.split(',').filter(function(ref) {
      return ref.trim() !== '';
    });
  }

  if(!familyReferences || familyReferences.length === 0) {
    toastr.error('Please select at least one family');
    return;
  }

  if(uploadedGroupPhotoIds.length === 0 && !uploadedGroupVideoId) {
    toastr.error('Please upload at least one photo or video');
    return;
  }

  let formData = {
    product_id: currentGroupProductId,
    family_references: familyReferences,
    photo_ids: uploadedGroupPhotoIds,
    video_id: uploadedGroupVideoId,
    _token: '{{ csrf_token() }}'
  };

  $.ajax({
    url: '{{ route("astrologer.group.puja.media.save") }}',
    type: 'POST',
    data: formData,
    dataType: 'json',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}',
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
    success: function(response) {
      if(response.success) {
        toastr.success(response.message || 'Media uploaded successfully');
        closeGroupPujaMediaModal();
        // Reload page to show updated data
        setTimeout(function() {
          location.reload();
        }, 1000);
      } else {
        toastr.error(response.message || 'Error saving media');
      }
    },
    error: function(xhr) {
      let errorMessage = 'Error saving media';
      if(xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
      } else if(xhr.responseJSON && xhr.responseJSON.errors) {
        let errors = xhr.responseJSON.errors;
        errorMessage = Object.values(errors).flat().join(', ');
      }
      toastr.error(errorMessage);
    }
  });
}

// Handle modal close on backdrop click and ESC key
$(document).ready(function() {
  // Close modals on backdrop click
  $('#pujaMediaModal, #bookMahuratModal, #groupPujaMediaModal').on('click', function(e) {
    if ($(e.target).hasClass('modal') || $(e.target).hasClass('modal-dialog')) {
      if ($(this).attr('id') === 'pujaMediaModal') {
        closePujaMediaModal();
      } else if ($(this).attr('id') === 'bookMahuratModal') {
        closeBookMahuratModal();
      } else if ($(this).attr('id') === 'groupPujaMediaModal') {
        closeGroupPujaMediaModal();
      }
    }
  });

  // Close modals on ESC key press
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape' || e.keyCode === 27) {
      if ($('#pujaMediaModal').hasClass('show')) {
        closePujaMediaModal();
      }
      if ($('#bookMahuratModal').hasClass('show')) {
        closeBookMahuratModal();
      }
      if ($('#groupPujaMediaModal').hasClass('show')) {
        closeGroupPujaMediaModal();
      }
    }
  });
});
</script>
@endpush
