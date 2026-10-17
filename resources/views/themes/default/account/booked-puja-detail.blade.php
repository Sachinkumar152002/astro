@extends(themeView().'layout')

@section('title', 'Booked Puja Details | '.config("app.name"))

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
</style>
@endpush

@section('content')

<section class="dashboad_section">
  <div class="container">
    <div class="row justify-content-between">
      @include(themeview().'account._sidebar')
      <div class="col-lg-9 col-12 mt-5 mt-lg-0">
        <div class="row my-3 recent mt-md-0">
          <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div>
                <h4>{{ $assignment->product ? $assignment->product->title : 'Puja Details' }}</h4>
                @if(isset($assignment->is_group_puja) && $assignment->is_group_puja && $assignment->group_puja_reference)
                  <p class="text-muted mb-0">
                    <strong>Family Reference:</strong> {{ $assignment->group_puja_reference }}
                  </p>
                @endif
              </div>
              <a href="{{ route('user.booked-puja') }}" class="btn btn-secondary btn-sm" style="text-align: center; display: inline-flex; align-items: center; justify-content: center;">
                <i class="bi bi-arrow-left"></i> Back to List
              </a>
            </div>

            <div class="row">
              <!-- Left Column - 8 columns for Media -->
              <div class="col-lg-8 col-md-12">
                <!-- Images Section -->
                @if($images->count() > 0)
                  <div class="card mb-4">
                    <div class="card-header">
                      <h5 class="mb-0">Images ({{ $images->count() }})</h5>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        @foreach($images as $image)
                          <div class="col-auto mb-3">
                            <div class="text-center">
                              @if($image->media)
                                <a href="javascript:void(0)" onclick="zoomImage('{{ $image->media->originalPath }}')">
                                  <img src="{{ $image->media->image(120, 120) }}" class="img-fluid puja-thumbnail" alt="Puja Image">
                                </a>
                              @endif
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                @endif

                <!-- Videos Section -->
                @if($videos->count() > 0)
                  <div class="card mb-4">
                    <div class="card-header">
                      <h5 class="mb-0">Videos ({{ $videos->count() }})</h5>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        @foreach($videos as $video)
                          <div class="col-md-6 mb-3">
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
                                  <div class="embed-responsive embed-responsive-16by9 card-img-top" style="max-height: 400px;">
                                    <iframe class="embed-responsive-item" src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                  </div>
                                @else
                                  <video controls class="card-img-top" style="max-height: 400px;">
                                    <source src="{{ $videoUrl }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                  </video>
                                @endif
                              @endif
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                @endif

                @if($images->count() == 0 && $videos->count() == 0)
                  <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    @if($assignment->display_in_user_profile)
                      No approved media available for this puja yet.
                    @else
                      Media is not available for viewing in your profile.
                    @endif
                  </div>
                @endif
              </div>

              <!-- Right Column - 4 columns for Information -->
              <div class="col-lg-4 col-md-12">
                <div class="card">
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

                    @if(!isset($assignment->is_group_puja) || !$assignment->is_group_puja)
                    <div class="mb-3">
                      <label><strong>Puja Schedule Date:</strong></label>
                      <p class="mb-0">
                        @if($assignment->mahurat && $assignment->mahurat->date)
                          {{ $assignment->mahurat->date->format('Y-m-d') }}
                          @if($assignment->mahurat->time)
                            {{ $assignment->mahurat->time }}
                          @endif
                        @else
                          Not Scheduled
                        @endif
                      </p>
                    </div>
                    @endif

                    <div class="mb-3">
                      <label><strong>Astrologer/Pandit:</strong></label>
                      <p class="mb-0">
                        @if($assignment->astrologer)
                          {{ $assignment->astrologer->name ?: $assignment->astrologer->display_name }}
                        @else
                          N/A
                        @endif
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
                            default => 'badge-warning',
                          };
                          $statusLabel = ucfirst($status === 'assigned' ? 'Pending' : $status);
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                      </p>
                    </div>

                    @if($assignment->notes && $assignment->display_in_user_profile)
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
                          <ul class="mt-1 mb-0" style="list-style-type: disc; padding-left: 20px;">
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

@push('scripts')
<script>
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
</script>
@endpush

@endsection
