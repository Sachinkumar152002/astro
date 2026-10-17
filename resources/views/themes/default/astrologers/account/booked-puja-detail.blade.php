@extends(themeView().'layout')

@section('title', 'Booked Puja Details')
@section('keywords', '')
@section('description','')

@push('styles')
<style>
  .puja-thumbnail {
    max-width: 120px;
    max-height: 120px;
    object-fit: cover;
    cursor: pointer;
    border-radius: 4px;
    border: 1px solid #ddd;
  }
  .image-zoom-modal {
    z-index: 9999;
  }
  .image-zoom-content {
    background: transparent;
    border: none;
  }
  .image-zoom-close {
    position: absolute;
    right: 10px;
    top: 10px;
    z-index: 10000;
    font-size: 2rem;
    opacity: 1;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
  }
  .image-zoom-img {
    max-width: 100%;
    max-height: 90vh;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  }
  .video-max-height {
    max-height: 400px;
  }

</style>
@endpush

@section('content')

<section class="dashboad_section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 col-12">
        <div class="row my-3 recent mt-md-0">
          <div class="col-lg-12 mt-5 mt-lg-0">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div>
                <h4>{{ $assignment->product ? $assignment->product->title : 'Puja Details' }}</h4>
              </div>
              <a href="{{ route('astrologer.booked.puja') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to List
              </a>
            </div>

            <div class="row">
              <!-- Left Column - 8 columns for Media -->
              <div class="col-lg-8 col-md-12">
                <!-- Images Section -->
                <div class="card mb-4 border-n">
                  <div class="card-header d-md-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Images @if($images->count() > 0)<span class="badge badge-info">({{ $images->count() }}/5)</span>@endif</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="showImageFileInput()" {{ $images->count() >= 5 ? 'disabled' : '' }} title="You can select multiple images at once (max 5 total, 5MB each)">
                      <i class="bi bi-plus-circle me-1"></i> Add Image(s)
                    </button>
                  </div>
                  <div class="card-body">
                    @if($images->count() > 0)
                      <div class="row" id="images-container">
                        @foreach($images as $image)
                          <div class="col-auto mb-3" id="image-{{ $image->id }}">
                            <div class="text-center">
                              @if($image->media)
                                <a href="javascript:void(0)" onclick="zoomImage('{{ $image->media->originalPath }}')">
                                  <img src="{{ $image->media->image(120, 120) }}" class="img-fluid puja-thumbnail" alt="Puja Image">
                                </a>
                                <div class="mt-2">
                                  <span class="badge badge-{{ $image->is_approved == 1 ? 'success' : 'warning' }}">
                                    {{ $image->is_approved == 1 ? 'Approved' : 'Pending Approval' }}
                                  </span>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteMedia({{ $image->id }}, 'image')">
                                  <i class="bi bi-trash"></i> Delete
                                </button>
                              @endif
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @else
                      <p class="text-muted text-center mb-0">No images uploaded yet. Click "Add Image(s)" to upload (you can select multiple images at once, max 5 total).</p>
                    @endif
                  </div>
                </div>

                <!-- Videos Section -->
                <div class="card mb-4 border-n">
                  <div class="card-header d-md-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Videos ({{ $videos->count() }})</h5>
                    <div class="mt-3 mt-lg-0">
                      <button type="button" class="btn btn-primary btn-sm me-2" onclick="showVideoFileInput()">
                        <i class="bi bi-upload"></i> Upload Video
                      </button>
                      <button type="button" class="btn btn-primary btn-sm" onclick="showVideoUrlModal()">
                        <i class="bi bi-link-45deg me-1"></i> Add Video URL
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    @if($videos->count() > 0)
                      <div class="row" id="videos-container">
                        @foreach($videos as $video)
                          <div class="col-md-6 mb-3" id="video-{{ $video->id }}">
                            <div class="card">
                              @if($video->video)
                                @php
                                  $videoUrl = $video->video->originalPath ?? '';
                                  $lower = strtolower($videoUrl);
                                  $isYouTube = str_contains($lower, 'youtube.com') || str_contains($lower, 'youtu.be');
                                  $isVimeo = str_contains($lower, 'vimeo.com');
                                  $embedUrl = $videoUrl;
                                  if ($isYouTube) {
                                    if (str_contains($lower, 'youtu.be/')) {
                                      $id = preg_replace('#^.*youtu\.be/([^?&]+).*#', '$1', $videoUrl);
                                      $embedUrl = 'https://www.youtube.com/embed/' . $id;
                                    } elseif (preg_match('#(?:youtube\.com/watch\?v=|youtube\.com/embed/)([^&]+)#', $videoUrl, $m)) {
                                      $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                                    }
                                  } elseif ($isVimeo) {
                                    if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $videoUrl, $m)) {
                                      $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                                    }
                                  }
                                @endphp
                                @if($isYouTube || $isVimeo)
                                  <div class="embed-responsive embed-responsive-16by9 card-img-top video-max-height" style="max-height: 400px;">
                                    <iframe class="embed-responsive-item" src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;height:100%;min-height:300px;"></iframe>
                                  </div>
                                @else
                                  <video controls class="card-img-top video-max-height">
                                    <source src="{{ $videoUrl }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                  </video>
                                @endif
                                <div class="card-body p-2">
                                  <span class="badge badge-{{ $video->is_approved == 1 ? 'success' : 'warning' }}">
                                    {{ $video->is_approved == 1 ? 'Approved' : 'Pending Approval' }}
                                  </span>
                                  <button type="button" class="btn btn-danger btn-sm float-end" onclick="deleteMedia({{ $video->id }}, 'video')">
                                    <i class="bi bi-trash"></i> Delete
                                  </button>
                                </div>
                              @endif
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @else
                      <p class="text-muted text-center mb-0">No videos uploaded yet.</p>
                    @endif
                  </div>
                </div>

                <!-- Hidden file inputs for uploads -->
                <input type="file" id="image-file-input" accept="image/*" multiple style="display: none;" onchange="uploadImageFiles(this.files)">
                <input type="file" id="video-file-input" accept="video/*" style="display: none;" onchange="uploadVideoFile(this.files[0])">
              </div>

              <!-- Right Column - 4 columns for Information -->
              <div class="col-lg-4 col-md-12">
                <div class="card border-n">
                  <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Puja Information</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <label><strong>Puja Booking Date:</strong></label>
                      <p class="mb-0">
                        {{ $assignment->created_at ? $assignment->created_at->format('Y-m-d H:i') : 'N/A' }}
                      </p>
                    </div>

                    <div class="mb-3">
                      <label><strong>Puja Schedule Date:</strong></label>
                      @if($assignment->mahurat && $assignment->mahurat->date)
                        <p class="mb-2">
                          <strong>Date:</strong> {{ $assignment->mahurat->date->format('d M Y') }}
                          @if($assignment->mahurat->time)
                            <br><strong>Time:</strong> {{ date('h:i A', strtotime($assignment->mahurat->time)) }}
                          @endif
                        </p>
                        @if(strtolower($assignment->status ?? '') !== 'completed')
                          <button type="button" class="btn btn-sm btn-primary" onclick="openScheduleDateModal()">
                            <i class="bi bi-calendar"></i> Update Schedule
                          </button>
                        @endif
                      @else
                        <p class="mb-2 text-muted">Not Scheduled</p>
                        @if(strtolower($assignment->status ?? '') !== 'completed')
                          <button type="button" class="btn btn-sm btn-primary" onclick="openScheduleDateModal()">
                            <i class="bi bi-calendar-plus"></i> Set Schedule Date
                          </button>
                        @endif
                      @endif
                    </div>

                    <div class="mb-3">
                      <label><strong>Customer:</strong></label>
                      <p class="mb-0">
                        @if($assignment->order && $assignment->order->user)
                          {{ $assignment->order->user->name }}
                        @else
                          N/A
                        @endif
                      </p>
                    </div>

                    <div class="mb-3">
                      <label><strong>Gotra:</strong></label>
                      <p class="mb-0">
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
                      </p>
                    </div>

                    <div class="mb-3">
                      <label><strong>Status:</strong></label>
                      <p class="mb-0">
                        @php
                          $status = strtolower(trim((string)($assignment->status ?? 'assigned')));
                          $statusBadge = match($status) {
                            'completed' => 'badge-success',
                            'cancelled' => 'badge-danger',
                            'pending' => 'badge-warning',
                            'assigned' => 'badge-warning',
                            default => 'badge-warning',
                          };
                          $statusLabel = ucfirst($status === 'assigned' ? 'Pending' : $status);
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                      </p>
                    </div>

                    @if($assignment->notes && $assignment->display_in_astrologer_profile)
                      <div class="mb-3">
                        <label><strong>Notes:</strong></label>
                        @php
                          $notesList = [];
                          if($assignment->notes) {
                            // Check if notes is JSON array
                            $decoded = json_decode($assignment->notes, true);
                            if(json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                              $notesList = $decoded;
                            } else {
                              // If it's a single string, convert to array
                              $notesList = [$assignment->notes];
                            }
                          }
                        @endphp
                        @if(count($notesList) > 0)
                          <ul class="mt-1 mb-0">
                            @foreach($notesList as $index => $note)
                              <li class="mb-2 note-item" data-index="{{ $index }}">
                                {{ isset($note['text']) ? $note['text'] : $note }}
                              </li>
                            @endforeach
                          </ul>
                        @else
                          <p class="mb-0 text-muted"><small>No notes added yet.</small></p>
                        @endif
                      </div>
                    @endif

                    @if($assignment->order)
                      <div class="mb-3">
                        <label><strong>Order Number:</strong></label>
                        <p class="mb-0">{{ $assignment->order->order_number ?? 'N/A' }}</p>
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
  </div>
</section>

<!-- Schedule Date Modal -->
<div class="modal fade" id="scheduleDateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog mt-5  pt-5" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Set Puja Schedule Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="scheduleDateForm">
          <div class="form-group">
            <label for="schedule_date">Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="schedule_date" name="date" required>
          </div>
          <div class="form-group">
            <label for="schedule_time">Time <span class="text-danger">*</span></label>
            <input type="time" class="form-control" id="schedule_time" name="time" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveScheduleDate()">Save Schedule</button>
      </div>
    </div>
  </div>
</div>

<!-- Video URL Modal -->
<div class="modal fade" id="videoUrlModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Video URL</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group mb-3">
          <label for="video-url-input">Video URL</label>
          <input type="url" class="form-control" id="video-url-input" placeholder="https://example.com/video.mp4">
          <small class="form-text text-muted">Enter a direct link to a video file (MP4, MOV, AVI, etc.)</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="uploadVideoUrl()">Add Video</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  var assignmentId = {{ $assignment->id }};

  function showImageFileInput() {
    $('#image-file-input').click();
  }

  function uploadImageFiles(files) {
    if(!files || files.length === 0) return;
    var currentCount = {{ $images->count() }};
    var maxAllowed = 5;
    var maxSizeMB = 5;
    var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    var toUpload = [];
    for(var i = 0; i < files.length && (currentCount + toUpload.length) < maxAllowed; i++) {
      var f = files[i];
      if(f.size > maxSizeMB * 1024 * 1024) {
        toastr.warning('Skipped "' + f.name + '" – max 5MB per image.');
        continue;
      }
      if(allowedTypes.indexOf(f.type) === -1) {
        toastr.warning('Skipped "' + f.name + '" – use JPEG, PNG, GIF or WEBP.');
        continue;
      }
      toUpload.push(f);
    }
    if(toUpload.length === 0) {
      toastr.error('Maximum 5 images allowed. Please delete an image first.');
      $('#image-file-input').val('');
      return;
    }
    if(toUpload.length < files.length) {
      toastr.warning('Only ' + toUpload.length + ' image(s) will be uploaded (max 5 total).');
    }
    var uploaded = 0;
    function uploadNext(index) {
      if(index >= toUpload.length) {
        if(uploaded > 0) {
          toastr.success('Image(s) uploaded successfully');
          setTimeout(function() { location.reload(); }, 800);
        }
        $('#image-file-input').val('');
        return;
      }
      var formData = new FormData();
      formData.append('file', toUpload[index]);
      formData.append('assignment_id', assignmentId);
      formData.append('_token', '{{ csrf_token() }}');
      $.ajax({
        url: '{{ route("astrologer.booked.puja.image.upload") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function() {
          uploaded++;
          uploadNext(index + 1);
        },
        error: function(xhr) {
          var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error uploading image';
          toastr.error(msg);
          uploadNext(index + 1);
        }
      });
    }
    uploadNext(0);
  }

  function zoomImage(imageUrl) {
    // Create modal for image zoom
    var modal = '<div class="modal fade image-zoom-modal" id="imageZoomModal" tabindex="-1" role="dialog">' +
      '<div class="modal-dialog modal-lg modal-dialog-centered" role="document">' +
        '<div class="modal-content image-zoom-content">' +
          '<div class="modal-body p-0 text-center">' +
            '<button type="button" class="close text-white image-zoom-close" onclick="$(\'#imageZoomModal\').modal(\'hide\')">&times;</button>' +
            '<img src="' + imageUrl + '" class="img-fluid image-zoom-img">' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>';

    $('body').append(modal);
    $('#imageZoomModal').modal('show');
    $('#imageZoomModal').on('hidden.bs.modal', function() {
      $(this).remove();
    });
  }

  function openScheduleDateModal() {
    // Load existing mahurat data if available
    $.ajax({
      url: '{{ route("astrologer.booked.puja.mahurat.get") }}',
      type: 'GET',
      data: {
        assignment_id: assignmentId
      },
      success: function(response) {
        if(response.success && response.mahurat) {
          if(response.mahurat.date) {
            $('#schedule_date').val(response.mahurat.date);
          }
          if(response.mahurat.time) {
            $('#schedule_time').val(response.mahurat.time);
          }
        }
        $('#scheduleDateModal').modal('show');
      },
      error: function() {
        $('#scheduleDateModal').modal('show');
      }
    });
  }

  function saveScheduleDate() {
    var date = $('#schedule_date').val();
    var time = $('#schedule_time').val();

    if(!date || !time) {
      toastr.error('Please select both date and time');
      return;
    }

    $.ajax({
      url: '{{ route("astrologer.booked.puja.mahurat.save") }}',
      type: 'POST',
      data: {
        assignment_id: assignmentId,
        date: date,
        time: time,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success) {
          toastr.success(response.message || 'Schedule date saved successfully');
          $('#scheduleDateModal').modal('hide');
          setTimeout(function() {
            location.reload();
          }, 1000);
        } else {
          toastr.error(response.message || 'Error saving schedule date');
        }
      },
      error: function(xhr) {
        var errorMsg = 'Error saving schedule date. Please try again.';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        }
        toastr.error(errorMsg);
      }
    });
  }

  function showVideoFileInput() {
    $('#video-file-input').click();
  }

  function uploadVideoFile(file) {
    if(!file) {
      return;
    }

    // Check file size (10MB = 10240 KB)
    var maxSizeKB = 10240;
    var fileSizeKB = file.size / 1024;

    if(fileSizeKB > maxSizeKB) {
      toastr.error('Upload 10 MB or less Video');
      $('#video-file-input').val('');
      return;
    }

    // Validate file extension
    var allowedExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
    var fileExtension = file.name.split('.').pop().toLowerCase();

    if(!allowedExtensions.includes(fileExtension)) {
      toastr.error('Invalid file type. Allowed formats: ' + allowedExtensions.join(', '));
      $('#video-file-input').val('');
      return;
    }

    var formData = new FormData();
    formData.append('file', file);
    formData.append('assignment_id', assignmentId);
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
      url: '{{ route("astrologer.booked.puja.video.upload") }}',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if(response.success) {
          toastr.success(response.message || 'Video uploaded successfully');
          $('#video-file-input').val('');
          setTimeout(function() {
            location.reload();
          }, 1000);
        } else {
          toastr.error(response.message || 'Error uploading video');
          $('#video-file-input').val('');
        }
      },
      error: function(xhr) {
        var errorMsg = 'Error uploading video. Please try again.';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        }
        toastr.error(errorMsg);
        $('#video-file-input').val('');
        console.error('Video upload error:', xhr);
      }
    });
  }

  function showVideoUrlModal() {
    $('#video-url-input').val('');
    $('#videoUrlModal').modal('show');
  }

  function uploadVideoUrl() {
    var videoUrl = $('#video-url-input').val().trim();

    if(!videoUrl) {
      toastr.error('Please enter a video URL');
      return;
    }

    $.ajax({
      url: '{{ route("astrologer.booked.puja.video.upload") }}',
      type: 'POST',
      data: {
        video_url: videoUrl,
        assignment_id: assignmentId,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success) {
          toastr.success(response.message || 'Video URL added successfully');
          $('#videoUrlModal').modal('hide');
          setTimeout(function() {
            location.reload();
          }, 1000);
        } else {
          toastr.error(response.message || 'Error adding video URL');
        }
      },
      error: function(xhr) {
        var errorMsg = 'Error adding video URL. Please try again.';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        }
        toastr.error(errorMsg);
        console.error('Video URL upload error:', xhr);
      }
    });
  }

  function deleteMedia(mediaId, type) {
    if(!confirm('Are you sure you want to delete this media? This action cannot be undone.')) {
      return;
    }

    $.ajax({
      url: '{{ route("astrologer.booked.puja.media.delete") }}',
      type: 'DELETE',
      data: {
        media_id: mediaId,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success) {
          toastr.success(response.message || 'Media deleted successfully');
          $('#' + type + '-' + mediaId).remove();
          if($('#' + type + 's-container').children().length == 0) {
            var emptyMsg = type === 'image' ? '<p class="text-muted text-center mb-0">No images uploaded yet.</p>' : '<p class="text-muted text-center mb-0">No videos uploaded yet.</p>';
            $('#' + type + 's-container').parent().find('.card-body').html(emptyMsg);
          }
        } else {
          toastr.error(response.message || 'Error deleting media');
        }
      },
      error: function(xhr) {
        toastr.error('Error deleting media');
        console.error('Delete media error:', xhr);
      }
    });
  }
</script>
@endpush

@endsection
