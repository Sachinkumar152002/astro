@extends('layouts.admin')

@section('content')
@push('styles')
<style>
  .puja-thumbnail {
    max-width: 120px;
    max-height: 120px;
    width: 100%!important;
    object-fit: cover;
    cursor: pointer;
    border-radius: 4px;
    border: 1px solid #ddd;
  }

  .video-thumbnail {
    position: relative;
    width: 100%;
    height: 180px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    transition: opacity 0.3s ease;
  }

  .video-thumbnail:hover {
    opacity: 0.9;
  }

  .video-play-icon {
    font-size: 4rem;
    color: rgba(255, 255, 255, 0.9);
    z-index: 2;
    transition: transform 0.3s ease;
  }

  .video-thumbnail:hover .video-play-icon {
    transform: scale(1.1);
  }

  .video-thumbnail-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.6);
    padding: 8px;
    z-index: 3;
  }

  .video-thumbnail-link {
    text-decoration: none;
    display: block;
  }

  .video-thumbnail-link:hover {
    text-decoration: none;
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
  }
  .image-zoom-img {
    max-width: 100%;
    max-height: 90vh;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  }

  /* Group Puja Family Listing UI */
  .gp-stat-card {
    border-radius: 10px;
    padding: 14px 16px;
    border: 1px solid rgba(0,0,0,0.06);
  }
  .gp-stat-card .gp-stat-number {
    font-size: 28px;
    font-weight: 700;
    line-height: 1.1;
  }
  .gp-stat-card .gp-stat-label {
    font-size: 13px;
    opacity: 0.9;
  }
  .gp-bulkbar {
    position: fixed;
    left: 50%;
    transform: translateX(-50%);
    bottom: 18px;
    z-index: 9990;
    width: min(820px, calc(100% - 32px));
    background: #0d6efd;
    color: #fff;
    border-radius: 14px;
    padding: 12px 14px;
    display: none;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 8px 24px rgba(13,110,253,0.35);
  }
  .gp-bulkbar .left {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
  }
  .gp-bulkbar .right {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .gp-bulkbar .btn-light {
    font-weight: 600;
  }

  /* Group Puja search + filter pills */
  .gp-search-filter-row {
    row-gap: 10px;
  }
  .gp-search-filter-row .input-group {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    background: #fff;
    overflow: hidden;
  }
  .gp-search-filter-row .input-group-text {
    background-color: #fff;
    border: none;
    padding-left: 12px;
    color: #9e9e9e;
  }
  .gp-search-filter-row .form-control {
    border: none;
    padding-left: 8px;
    padding-right: 12px;
  }
  .gp-search-filter-row .form-control:focus {
    box-shadow: none;
    border: none;
  }
  .gp-filter-buttons {
    display: flex;
    gap: 8px;
  }
  .gp-filter-btn {
    border-radius: 8px !important;
    min-width: 90px;
    font-weight: 500;
    border: none;
    padding: 8px 16px;
  }
  .gp-filter-btn.btn-primary {
    background-color: #0d6efd;
    color: #fff;
  }
  .gp-filter-btn.btn-outline-primary {
    background-color: #f5f5f5;
    color: #343a40;
  }
</style>
@endpush
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex align-items-center justify-content-between">
        <h1 class="text-primary mb-0">Edit Booked Puja - {{ isset($type) && $type == 'group' ? 'Group Puja' : 'One to One Puja' }}</h1>
      </div>
    </div>
  </div>
</div>

<div class="container mt-3">
  <div class="row">
    <!-- Media Section - Full width for Group Puja, 8 columns for One to One -->
    <div class="{{ isset($assignment->is_group_puja) && $assignment->is_group_puja ? 'col-md-12' : 'col-md-8' }}">
      @if(isset($assignment->is_group_puja) && $assignment->is_group_puja)
        {{-- Group Puja: New Family Listing UI --}}
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="row g-3">
              <div class="col-md-4">
                <div class="gp-stat-card" style="background:#eef5ff;">
                  <div class="gp-stat-number" id="gp-total-count">{{ $familyStats['total'] ?? 0 }}</div>
                  <div class="gp-stat-label">Total Families</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="gp-stat-card" style="background:#eafaf0;">
                  <div class="gp-stat-number" id="gp-completed-count">{{ $familyStats['completed'] ?? 0 }}</div>
                  <div class="gp-stat-label">Completed</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="gp-stat-card" style="background:#fff3e6;">
                  <div class="gp-stat-number" id="gp-pending-count">{{ $familyStats['pending'] ?? 0 }}</div>
                  <div class="gp-stat-label">Pending</div>
                </div>
              </div>
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between mt-3 gp-search-filter-row">
              <div class="input-group" style="max-width: 520px;">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-search" style="color: #9e9e9e;"></i></span>
                </div>
                <input type="text" class="form-control" id="gp-search" placeholder="Search by family name, contact, or email...">
              </div>
              <div class="gp-filter-buttons">
                <button type="button" class="btn btn-primary gp-filter-btn" data-filter="all">All</button>
                <button type="button" class="btn btn-outline-primary gp-filter-btn" data-filter="completed">Completed</button>
                <button type="button" class="btn btn-outline-primary gp-filter-btn" data-filter="pending">Pending</button>
              </div>
            </div>

            <div class="mt-3">
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table mb-0" id="gp-family-table">
                    <thead>
                      <tr>
                        <th style="width: 40px;">
                          <input type="checkbox" id="gp-select-all">
                        </th>
                        <th>FAMILY</th>
                        <th>GOTRA</th>
                        <th>PHOTOS</th>
                        <th>VIDEO</th>
                        <th>STATUS</th>
                        <th>LAST UPDATED</th>
                        <th class="text-right">ACTIONS</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($familyRows as $row)
                        @php
                          $ref = $row['reference'] ?? '';
                          $imagesCnt = (int)($row['images_count'] ?? 0);
                          $videosCnt = (int)($row['videos_count'] ?? 0);
                          $required = (int)($row['photos_required'] ?? 5);
                          $status = strtolower(trim($row['status'] ?? 'pending'));
                          $statusClass = $status === 'completed' ? 'badge-success' : 'badge-warning';
                          $lastUpdated = !empty($row['last_updated']) ? \Carbon\Carbon::parse($row['last_updated'])->format('Y-m-d') : '-';
                          // Disable checkbox if status is completed OR if any image/video is uploaded
                          $shouldDisable = ($status === 'completed') || ($imagesCnt > 0) || ($videosCnt > 0);
                        @endphp
                        <tr class="gp-row" data-status="{{ $status }}" data-search="{{ strtolower(($ref).' '.($row['contact_person'] ?? '').' '.($row['email'] ?? '')) }}">
                          <td>
                            <input type="checkbox" class="gp-row-check" value="{{ $ref }}" {{ $shouldDisable ? 'disabled' : '' }}>
                          </td>
                          <td><strong>{{ $ref }}</strong></td>
                          <td>{{ $row['gotra'] ?? 'N/A' }}</td>
                          <td>{{ $imagesCnt }}/{{ $required }}</td>
                          <td>{{ $videosCnt > 0 ? 'Added' : 'Not added' }}</td>
                          <td><span class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span></td>
                          <td>{{ $lastUpdated }}</td>
                          <td class="text-right">
                            <button type="button" class="btn btn-primary btn-sm gp-edit-btn"
                              data-ref="{{ $ref }}"
                              data-contact="{{ $row['contact_person'] ?? 'N/A' }}"
                              data-email="{{ $row['email'] ?? 'N/A' }}"
                              data-images="{{ $imagesCnt }}"
                              data-required="{{ $required }}"
                              data-videos="{{ $videosCnt }}">
                              <i class="fa fa-edit"></i> Edit
                            </button>
                          </td>
                        </tr>
                      @endforeach
                      @if($familyRows->count() == 0)
                        <tr>
                          <td colspan="8" class="text-center text-muted p-4">No families found for this group puja.</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="mt-3">
              @include('admin._partials.pagination', ['results' => $familyRows])
            </div>

          </div>
        </div>

        {{-- Group Puja Media Manager Modal (Edit) --}}
        <div class="modal fade" id="gpFamilyMediaModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <div>
                  <h5 class="modal-title mb-1" id="gpModalTitle">Family</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="alert alert-warning" id="gpModalStatusAlert" style="display:none;">
                  <i class="fa fa-circle text-warning"></i>
                  <span id="gpModalStatusText"></span>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-2">
                  <h5 class="mb-0">Photos (<span id="gpModalPhotosCount">0</span>/<span id="gpModalPhotosRequired">5</span>)</h5>
                  <a href="javascript:void(0)" class="text-primary font-weight-bold" id="gpAddPhotosBtn">
                    <i class="fa fa-upload"></i> Add Photos
                  </a>
                </div>
                <input type="file" id="gp-photo-file-input" accept="image/*" style="display:none;">
                <input type="file" id="gp-photo-multiple-input" accept="image/*" multiple style="display:none;">
                <div class="row g-2" id="gpPhotoSlots">
                  {{-- slots are rendered by JS --}}
                </div>

                <hr>

                <h5 class="mb-2">Video</h5>
                <ul class="nav nav-tabs" id="gpVideoTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="gp-video-link-tab" data-toggle="tab" href="#gp-video-link" role="tab">Video Link</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="gp-video-upload-tab" data-toggle="tab" href="#gp-video-upload" role="tab">Upload Video</a>
                  </li>
                </ul>
                <div class="tab-content border-left border-right border-bottom p-3" id="gpVideoTabContent">
                  <div class="tab-pane fade show active" id="gp-video-link" role="tabpanel">
                    <div class="gp-video-link-list mb-3"></div>
                    <input type="url" class="form-control" id="gp-video-url-input" placeholder="Enter video URL (YouTube, Vimeo, etc.)">
                    <button type="button" class="btn btn-primary mt-2" id="gpAddVideoLinkBtn">Add Video Link</button>
                    <small class="text-muted d-block mt-2">Supports direct video URLs. Added links stay visible above.</small>
                  </div>
                  <div class="tab-pane fade" id="gp-video-upload" role="tabpanel">
                    <div class="gp-video-upload-list mb-3"></div>
                    <input type="file" id="gp-video-file-input" accept="video/*" style="display:none;">
                    <button type="button" class="btn btn-primary" id="gpUploadVideoBtn">
                      <i class="fa fa-upload"></i> Upload Video
                    </button>
                    <small class="text-muted d-block mt-2">Upload 10 MB or less video.</small>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="gpApproveMediaBtn">
                  <i class="fa fa-check"></i> Approve Media
                </button>
              </div>
            </div>
          </div>
        </div>

        {{-- Bulk action bar (shows when 2+ families selected) --}}
        <div class="gp-bulkbar" id="gp-bulkbar">
          <div class="left">
            <i class="fa fa-users"></i>
            <span id="gpBulkCount">0 families selected</span>
          </div>
          <div class="right">
            <button type="button" class="btn btn-light" id="gpBulkUploadBtn">
              <i class="fa fa-photo"></i> Upload Photo and Video
            </button>
            <button type="button" class="btn btn-link text-white" id="gpBulkClearBtn" style="text-decoration:none;">
              <i class="fa fa-times"></i>
            </button>
          </div>
        </div>
      @endif

      @if(!isset($assignment->is_group_puja) || !$assignment->is_group_puja)
      <!-- Images Section (One-to-one only) -->
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="form-group">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Images @if($images->count() > 0)<span class="badge badge-info">({{ $images->count() }}/5)</span>@endif</h4>
            <button type="button" class="btn btn-primary btn-sm" onclick="openMediaManager('image')" {{ $images->count() >= 5 ? 'disabled' : '' }}>
              <i class="fa fa-plus"></i> Add Image
            </button>
          </div>
            <div class="row g-2" id="images-container">
              @foreach($images as $image)
                <div class="col-md-2 mb-3" id="image-{{ $image->id }}">
                  <div class="border text-center">
                    @if($image->media)
                      <a href="javascript:void(0)" onclick="zoomImage('{{ $image->media->originalPath }}')">
                        <img src="{{ $image->media->image(120, 120) }}" class="img-fluid puja-thumbnail mb-2" alt="Image">
                      </a>
                    @endif
                    <div class="text-center mb-2">
                      <span class="badge badge-{{ $image->is_approved == 1 ? 'success' : 'warning' }}">
                        {{ $image->is_approved == 1 ? 'Approved' : 'Not Approved' }}
                      </span>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteMedia({{ $image->id }}, 'image')">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </div>
                </div>
              @endforeach
              @if($images->count() == 0)
                <div class="col-md-12">
                  <p class="text-muted text-center">No images uploaded yet.</p>
                </div>
              @endif
            </div>
            @if($images->count() >= 5)
              <div class="alert alert-warning mt-2">
                <i class="fa fa-info-circle"></i> Maximum 5 images allowed. Please delete an image to upload a new one.
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Videos Section (One-to-one only) -->
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="form-group">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h4>Videos</h4>
              <div class="d-flex gap-2">
                <input type="file" id="video-file-input" name="file" accept="video/*" style="display: none;" onchange="handleVideoFileSelect(this)">
                <button type="butwton" class="btn btn-primary btn-sm mr-2" onclick="document.getElementById('video-file-input').click()">
                  <i class="fa fa-upload"></i> Upload Video
                </button>
                <button type="button" class="btn btn-info btn-sm" onclick="showVideoUrlModal()">
                  <i class="fa fa-link"></i> Add Video URL
                </button>
              </div>
            </div>
            <div class="row g-2" id="videos-container">
              @foreach($videos as $video)
                <div class="col-md-3 mb-3" id="video-{{ $video->id }}">
                  <div class="border text-center">
                    @if($video->video)
                      <a href="{{ $video->video->originalPath }}" target="_blank" class="video-thumbnail-link">
                        <div class="video-thumbnail">
                          <i class="fa fa-play-circle video-play-icon"></i>
                          <div class="video-thumbnail-overlay">
                            <span class="badge badge-light">Click to view</span>
                          </div>
                        </div>
                      </a>
                    @endif
                    <div class="p-2">
                      <div class="text-center mb-2">
                        <span class="badge badge-{{ $video->is_approved == 1 ? 'success' : 'warning' }}">
                          {{ $video->is_approved == 1 ? 'Approved' : 'Not Approved' }}
                        </span>
                      </div>
                      <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteMedia({{ $video->id }}, 'video')">
                        <i class="fa fa-trash"></i> Delete
                      </button>
                    </div>
                  </div>
                </div>
              @endforeach
              @if($videos->count() == 0)
                <div class="col-md-12">
                  <p class="text-muted text-center">No videos uploaded yet.</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      <!-- Video URL Modal (One-to-one only) -->
      <div class="modal fade" id="videoUrlModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Video URL</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
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
      @endif
    </div>

    <!-- Information & Notes Section (One to One Puja) -->
    <div class="col-md-4 mt-4 mt-md-0">
      @if(!isset($assignment->is_group_puja) || !$assignment->is_group_puja)
        <form id="puja-info-form">
          @csrf
          <input type="hidden" name="id" value="{{ $assignment->id }}">

          <div class=" mt-4">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Puja Information</h5>
            </div>
            <div class="card-body">
              <div class="form-group mb-3">
                <label><strong>Puja Booking Date:</strong></label>
                <p class="mb-0">
                  {{ $assignment->created_at ? $assignment->created_at->format('Y-m-d H:i') : 'N/A' }}
                </p>
              </div>

              <div class="form-group mb-3">
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

              <div class="form-group mb-3">
                <label><strong>Astrologer/Pandit:</strong></label>
                <p class="mb-0">
                  @if($assignment->astrologer)
                    {{ $assignment->astrologer->name ?: $assignment->astrologer->display_name }}
                  @else
                    N/A
                  @endif
                </p>
              </div>

              <div class="form-group mb-3">
                <label for="status"><strong>Status:</strong></label>
                <select class="form-control" name="status" id="status">
                  <option value="assigned" {{ ($assignment->status ?? 'assigned') == 'assigned' ? 'selected' : '' }}>Pending</option>
                  <option value="completed" {{ ($assignment->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="cancelled" {{ ($assignment->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </div>

              <div class="form-group mb-3">
                <label for="notes"><strong>Notes:</strong></label>
                <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Enter notes"></textarea>
              </div>

              <div class="form-group mb-3">
                <label><strong>Display Options:</strong></label>
                <div class="mt-2">
                  <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="display_in_user_profile" id="display_in_user_profile" value="1" {{ ($assignment->display_in_user_profile ?? 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="display_in_user_profile">
                      Display in User Profile
                    </label>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="display_in_astrologer_profile" id="display_in_astrologer_profile" value="1" {{ ($assignment->display_in_astrologer_profile ?? 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="display_in_astrologer_profile">
                      Display in Astrologer Profile
                    </label>
                  </div>
                </div>
              </div>

              <button type="button" class="btn btn-success w-100" onclick="savePujaInfo()">
                <i class="fa fa-save"></i> Save Changes
              </button>
            </div>
          </div>

          <div class=" mt-3">
            <div class="card-header bg-secondary text-white">
              <h5 class="mb-0">Notes</h5>
            </div>
            <div class="card-body">
              <div id="notes-list" class="mt-1 mb-0">
                @php
                  $notesList = [];
                  if($assignment->notes) {
                    $decoded = json_decode($assignment->notes, true);
                    if(json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                      $notesList = array_reverse($decoded); // latest first
                    } else {
                      $notesList = [$assignment->notes];
                    }
                  }
                @endphp
                @if(count($notesList) > 0)
                  @foreach($notesList as $index => $note)
                    @php
                      $noteText = is_array($note) ? ($note['text'] ?? '') : $note;
                      $addedBy = is_array($note) ? ($note['added_by'] ?? 'Admin') : 'Admin';
                      $noteDate = null;
                      if(is_array($note) && !empty($note['date'])) {
                        try {
                          $noteDate = \Carbon\Carbon::parse($note['date'])->format('Y-m-d');
                        } catch (\Exception $e) {
                          $noteDate = null;
                        }
                      }
                      $displayFor = [];
                      if(is_array($note)) {
                        if(isset($note['display_in_user_profile']) && $note['display_in_user_profile']) {
                          $displayFor[] = 'user';
                        }
                        if(isset($note['display_in_astrologer_profile']) && $note['display_in_astrologer_profile']) {
                          $displayFor[] = 'astrologer';
                        }
                      }
                    @endphp
                    <div class=" mb-2 note-item" data-index="{{ $index }}">
                      <div class="card-body py-2">
                        <p class="mb-1">{{ $noteText }}</p>
                        <small class="text-muted d-block">
                          Added by {{ $addedBy }}@if($noteDate) on {{ $noteDate }}@endif
                        </small>
                        @if(!empty($displayFor))
                          <small class="text-muted">
                            For {{ implode(', ', $displayFor) }}
                          </small>
                        @endif
                      </div>
                    </div>
                  @endforeach
                @else
                  <p class="text-muted mb-0"><small>No notes added yet.</small></p>
                @endif
              </div>
            </div>
          </div>
        </form>
      @else
        {{-- Group Puja: no right-side family selector, selection handled in left header section --}}
      @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Media Manager Modal -->
@include('admin.media.manager')

<input type="hidden" id="assignment_id" value="{{ isset($assignment->is_group_puja) && $assignment->is_group_puja ? '' : $assignment->id }}">
<input type="hidden" id="group_puja_references" value="{{ isset($assignment->group_puja_references) ? json_encode($assignment->group_puja_references) : (isset($assignment->group_puja_reference) ? json_encode(explode(', ', $assignment->group_puja_reference)) : '[]') }}">
<input type="hidden" id="product_id" value="{{ $assignment->product ? $assignment->product->id : '' }}">
<input type="hidden" id="astrologer_id" value="{{ $assignment->astrologer ? $assignment->astrologer->id : '' }}">
<input type="hidden" id="is_group_puja" value="{{ isset($assignment->is_group_puja) && $assignment->is_group_puja ? '1' : '0' }}">
<input type="hidden" id="current_media_type" value="">

@endsection

@push('scripts')
<script type="text/javascript">
  var media_image_type = 'astrologer';
  var assignmentId = $('#assignment_id').val() ? parseInt($('#assignment_id').val()) : null;
  var isGroupPuja = $('#is_group_puja').val() == '1';
  var groupPujaReferences = JSON.parse($('#group_puja_references').val() || '[]');
  var productId = $('#product_id').val() ? parseInt($('#product_id').val()) : null;
  var astrologerId = $('#astrologer_id').val() ? parseInt($('#astrologer_id').val()) : null;
  var currentMediaType = '';
  var selectedMediaId = null;
  var currentTargetFamilies = []; // used by group modal (single/bulk)
  var gpModalRequired = 5; // photos required for current family in group puja modal (used to reload after delete)

  // Group Puja: family table interactions (new UI)
  if(isGroupPuja) {
    $(document).ready(function() {
      // default filter "all"
      applyGpFilter('all');
      updateGpBulkBar();
    });
  }

  function applyGpFilter(filter) {
    var f = (filter || 'all').toLowerCase();
    $('.gp-filter-btn').removeClass('btn-primary').addClass('btn-outline-primary');
    $('.gp-filter-btn[data-filter="' + f + '"]').removeClass('btn-outline-primary').addClass('btn-primary');
    var term = ($('#gp-search').val() || '').toLowerCase().trim();

    $('#gp-family-table tbody tr.gp-row').each(function() {
      var rowStatus = ($(this).data('status') || '').toString().toLowerCase();
      var hay = ($(this).data('search') || '').toString();
      var matchesStatus = (f === 'all') ? true : (rowStatus === f);
      var matchesTerm = term ? (hay.indexOf(term) !== -1) : true;
      $(this).toggle(matchesStatus && matchesTerm);
    });
  }

  $(document).on('click', '.gp-filter-btn', function() {
    applyGpFilter($(this).data('filter'));
  });
  $(document).on('input', '#gp-search', function() {
    // use currently active filter
    var active = $('.gp-filter-btn.btn-primary').data('filter') || 'all';
    applyGpFilter(active);
  });

  // Selection (checkboxes)
  function updateGpBulkBar() {
    var selected = [];
    $('.gp-row-check:checked:not(:disabled)').each(function() {
      selected.push($(this).val());
    });
    groupPujaReferences = selected;
    $('#group_puja_references').val(JSON.stringify(groupPujaReferences));

    if(groupPujaReferences.length >= 2) {
      $('#gpBulkCount').text(groupPujaReferences.length + ' families selected');
      $('#gp-bulkbar').css('display', 'flex');
    } else {
      $('#gp-bulkbar').hide();
    }
  }
  $(document).on('change', '.gp-row-check', updateGpBulkBar);
  $(document).on('change', '#gp-select-all', function() {
    var checked = $(this).is(':checked');
    // only select visible rows that are not disabled
    // Also exclude rows that have any images or videos uploaded
    $('#gp-family-table tbody tr.gp-row:visible').each(function() {
      var $row = $(this);
      var imagesCnt = parseInt($row.find('td').eq(3).text().split('/')[0]) || 0;
      var videosCnt = $row.find('td').eq(4).text().toLowerCase().includes('added') ? 1 : 0;
      var $checkbox = $row.find('.gp-row-check');
      // Only check if checkbox is not disabled and no images/videos uploaded
      if(!$checkbox.prop('disabled') && imagesCnt === 0 && videosCnt === 0) {
        $checkbox.prop('checked', checked);
      }
    });
    updateGpBulkBar();
  });
  $(document).on('click', '#gpBulkClearBtn', function() {
    $('.gp-row-check').prop('checked', false);
    $('#gp-select-all').prop('checked', false);
    updateGpBulkBar();
  });

  // Open modal (single)
  $(document).on('click', '.gp-edit-btn', function() {
    var ref = $(this).data('ref');
    currentTargetFamilies = [ref];
    $('#gpModalTitle').text(ref);

    var imagesCnt = parseInt($(this).data('images') || 0);
    var required = parseInt($(this).data('required') || 5);
    gpModalRequired = required;
    var videosCnt = parseInt($(this).data('videos') || 0);

    $('#gpModalPhotosCount').text(imagesCnt);
    $('#gpModalPhotosRequired').text(required);

    // status alert (remaining)
    var remainingPhotos = Math.max(0, required - imagesCnt);
    var remainingVideo = videosCnt > 0 ? 0 : 1;
    if(remainingPhotos > 0 || remainingVideo > 0) {
      var msg = 'Media incomplete: ' + remainingPhotos + ' photos' + (remainingVideo ? ' and video' : '') + ' remaining';
      $('#gpModalStatusText').text(msg);
      $('#gpModalStatusAlert').show();
    } else {
      $('#gpModalStatusAlert').hide();
    }

    renderGpPhotoSlots(required);
    // Load existing media for this family into modal
    loadGpFamilyMedia(ref, required);
    $('#gp-video-url-input').val('');
    $('#gpFamilyMediaModal').modal('show');
  });

  function renderGpPhotoSlots(required) {
    var html = '';
    for(var i=1; i<=required; i++) {
      html += '<div class="col-md-2 mb-2"><div class="border rounded text-center py-4 text-muted gp-photo-slot" style="min-height:110px; cursor:pointer;" data-index="' + i + '">' +
        '<i class="fa fa-image" style="font-size:22px;"></i><div style="font-size:12px;margin-top:6px;">' + i + '</div></div></div>';
    }
    $('#gpPhotoSlots').html(html);
  }

  function renderGpPhotoSlotsWithMedia(required, images) {
    var html = '';
    var imgs = Array.isArray(images) ? images : [];
    for(var i=1; i<=required; i++) {
      var img = imgs[i-1];
      if(img && img.thumb) {
        html += '<div class="col-md-2 mb-2" id="gp-img-' + img.id + '">' +
          '<div class="border rounded text-center p-1 gp-photo-slot" style="cursor:pointer;" data-id="' + img.id + '">' +
          '<img src="' + img.thumb + '" class="img-fluid puja-thumbnail" style="max-height:100px;" />' +
          '<button type="button" class="btn btn-danger btn-sm w-100 mt-1" onclick="event.stopPropagation(); deleteMedia(' + img.id + ', \'image\')">' +
          '<i class="fa fa-trash"></i> Delete' +
          '</button>' +
          '</div></div>';
      } else {
        html += '<div class="col-md-2 mb-2"><div class="border rounded text-center py-4 text-muted gp-photo-slot" style="min-height:110px; cursor:pointer;" data-index="' + i + '">' +
          '<i class="fa fa-image" style="font-size:22px;"></i><div style="font-size:12px;margin-top:6px;">' + i + '</div></div></div>';
      }
    }
    $('#gpPhotoSlots').html(html);
  }

  function renderOneVideoLinkBlock(v) {
    if(!v || !v.url) return '';
    var url = v.url;
    var lower = url.toLowerCase();
    var isYouTube = lower.indexOf('youtube.com') !== -1 || lower.indexOf('youtu.be') !== -1;
    var isVimeo = lower.indexOf('vimeo.com') !== -1;
    var html = '<div class="gp-existing-video-preview mb-3">';
    if(isYouTube) {
      var embedUrl = url;
      if(embedUrl.indexOf('youtu.be/') !== -1) {
        var id = embedUrl.split('youtu.be/')[1].split(/[?&]/)[0];
        embedUrl = 'https://www.youtube.com/embed/' + id;
      } else if(embedUrl.indexOf('watch?v=') !== -1) {
        var id2 = embedUrl.split('watch?v=')[1].split(/[&]/)[0];
        embedUrl = 'https://www.youtube.com/embed/' + id2;
      }
      html += '<div class="embed-responsive embed-responsive-16by9 mb-2">' +
              '<iframe class="embed-responsive-item" src="' + embedUrl + '" frameborder="0" allowfullscreen></iframe>' +
              '</div>';
    } else if(isVimeo) {
      var vimeoId = (url.match(/vimeo\.com\/(\d+)/) || [])[1] || '';
      if(vimeoId) {
        html += '<div class="embed-responsive embed-responsive-16by9 mb-2">' +
                '<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/' + vimeoId + '" frameborder="0" allowfullscreen></iframe>' +
                '</div>';
      } else {
        html += '<a href="' + url + '" target="_blank" rel="noopener">' + url + '</a>';
      }
    } else {
      html += '<video controls style="width:100%; max-height:360px;" class="mb-2">' +
              '<source src="' + url + '">' +
              'Your browser does not support the video tag.' +
              '</video>';
    }
    html += '<div class="d-flex justify-content-between align-items-center">' +
            '<small class="text-muted" style="word-break:break-all;">' + url + '</small>' +
            '<button type="button" class="btn btn-danger btn-sm gp-delete-video" data-id="' + v.id + '"><i class="fa fa-trash"></i></button>' +
            '</div></div>';
    return html;
  }

  function renderOneVideoUploadBlock(v) {
    if(!v || !v.url) return '';
    var html = '<div class="gp-existing-video-preview mb-3">' +
        '<video controls style="width:100%; max-height:360px;" class="mb-2">' +
        '<source src="' + v.url + '">' +
        'Your browser does not support the video tag.' +
        '</video>' +
        '<div class="d-flex justify-content-between align-items-center">' +
        '<small class="text-muted">Uploaded video</small>' +
        '<button type="button" class="btn btn-danger btn-sm gp-delete-video" data-id="' + v.id + '"><i class="fa fa-trash"></i></button>' +
        '</div></div>';
    return html;
  }

  function renderGpVideoSection(videoLinks, videoUploads) {
    var linkList = Array.isArray(videoLinks) ? videoLinks : [];
    var uploadList = Array.isArray(videoUploads) ? videoUploads : [];
    var linkHtml = '';
    for(var i = 0; i < linkList.length; i++) {
      linkHtml += renderOneVideoLinkBlock(linkList[i]);
    }
    if(linkHtml === '') linkHtml = '<p class="text-muted small">No video links added yet.</p>';
    $('#gp-video-link .gp-video-link-list').html(linkHtml);

    var uploadHtml = '';
    for(var j = 0; j < uploadList.length; j++) {
      uploadHtml += renderOneVideoUploadBlock(uploadList[j]);
    }
    if(uploadHtml === '') uploadHtml = '<p class="text-muted small">No videos uploaded yet.</p>';
    $('#gp-video-upload .gp-video-upload-list').html(uploadHtml);
  }

  function loadGpFamilyMedia(ref, required) {
    if(!ref) return;
    if(!productId) return;
    // Do not send astrologer_id so admin sees ALL media for this family (any astrologer)
    var data = { product_id: productId, group_puja_reference: ref };
    $.ajax({
      url: '{{ route("admin.booked-puja.group.family-media") }}',
      type: 'GET',
      data: data,
      success: function(resp) {
        if(resp && resp.success) {
          var images = resp.images || [];
          var videos = resp.videos || [];
          var videoLinks = resp.video_links || (videos.filter(function(v) { return v.is_link; }));
          var videoUploads = resp.video_uploads || (videos.filter(function(v) { return !v.is_link; }));
          $('#gpModalPhotosCount').text(images.length);
          $('#gpModalPhotosRequired').text(required);
          renderGpPhotoSlotsWithMedia(required, images);
          renderGpVideoSection(videoLinks, videoUploads);
          // Update modal status alert based on latest counts
          var imagesCnt = images.length;
          var requiredInt = parseInt(required) || 5;
          var videosCnt = videos.length;
          var remainingPhotos = Math.max(0, requiredInt - imagesCnt);
          var remainingVideo = videosCnt > 0 ? 0 : 1;
          if(remainingPhotos > 0 || remainingVideo > 0) {
            var msg = 'Media incomplete: ' + remainingPhotos + ' photos' + (remainingVideo ? ' and video' : '') + ' remaining';
            $('#gpModalStatusText').text(msg);
            $('#gpModalStatusAlert').show();
          } else {
            $('#gpModalStatusAlert').hide();
          }
          // Update table row status and top cards without page refresh
          updateGpFamilyRowStatus(ref, imagesCnt, videosCnt, requiredInt);
        }
      }
    });
  }

  function updateGpFamilyRowStatus(ref, imagesCnt, videosCnt, required) {
    if(!ref) return;
    var $row = $('#gp-family-table tbody tr.gp-row').filter(function() {
      var val = $(this).find('.gp-row-check').val();
      return val === ref;
    });
    if($row.length === 0) return;

    // Update photos and video columns (indexes: 0 checkbox, 1 family, 2 gotra, 3 photos, 4 video, 5 status)
    $row.find('td').eq(3).text(imagesCnt + '/' + required);
    $row.find('td').eq(4).text(videosCnt > 0 ? 'Added' : 'Not added');

    // Determine status
    var status = (imagesCnt >= required && videosCnt > 0) ? 'completed' : 'pending';
    $row.attr('data-status', status);
    var $badge = $row.find('td').eq(5).find('span');
    var $checkbox = $row.find('.gp-row-check');
    $badge.removeClass('badge-success badge-warning');
    if(status === 'completed') {
      $badge.addClass('badge-success').text('Completed');
      // Disable checkbox when completed
      $checkbox.prop('disabled', true).prop('checked', false);
    } else {
      $badge.addClass('badge-warning').text('Pending');
      // Disable checkbox if any image or video is uploaded (even if not completed)
      if(imagesCnt > 0 || videosCnt > 0) {
        $checkbox.prop('disabled', true).prop('checked', false);
      } else {
        // Enable checkbox only when no images/videos are uploaded
        $checkbox.prop('disabled', false);
      }
    }

    // Update bulk bar if checkbox was unchecked due to disable
    updateGpBulkBar();

    // Recalculate top stats
    recalcGpStats();
  }

  function recalcGpStats() {
    var total = 0, completed = 0, pending = 0;
    $('#gp-family-table tbody tr.gp-row').each(function() {
      total++;
      var st = ($(this).data('status') || '').toString().toLowerCase();
      if(st === 'completed') completed++;
      else pending++;
    });
    $('#gp-total-count').text(total);
    $('#gp-completed-count').text(completed);
    $('#gp-pending-count').text(pending);
  }

  // Delete video from preview button
  $(document).on('click', '.gp-delete-video', function() {
    var id = $(this).data('id');
    if(!id) return;
    deleteMedia(id, 'video');
  });

  // Bulk upload button: open same modal but for multiple refs
  $(document).on('click', '#gpBulkUploadBtn', function() {
    if(!Array.isArray(groupPujaReferences) || groupPujaReferences.length < 2) {
      toastr.warning('Please select at least 2 families');
      return;
    }
    currentTargetFamilies = groupPujaReferences.slice();
    $('#gpModalTitle').text(groupPujaReferences.length + ' families selected');
    $('#gpModalStatusAlert').hide();
    $('#gpModalPhotosCount').text('0');
    $('#gpModalPhotosRequired').text('5');
    renderGpPhotoSlots(5);
    $('#gp-video-url-input').val('');
    $('#gpFamilyMediaModal').modal('show');
  });

  // Approve Media button: approve all existing media for current family/families (so astrologer uploads get approved without admin re-uploading)
  $(document).on('click', '#gpApproveMediaBtn', function() {
    if(!Array.isArray(currentTargetFamilies) || currentTargetFamilies.length === 0) {
      toastr.warning('No family selected');
      return;
    }
    if(!productId) {
      toastr.error('Product not found');
      return;
    }
    var required = parseInt($('#gpModalPhotosRequired').text() || '5', 10);
    var $btn = $(this).prop('disabled', true);
    $.ajax({
      url: '{{ route("admin.booked-puja.media.bulk-approve") }}',
      type: 'POST',
      data: {
        is_approved: 1,
        product_id: productId,
        group_puja_references: currentTargetFamilies,
        _token: '{{ csrf_token() }}'
      },
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      success: function(response) {
        $btn.prop('disabled', false);
        if(response.success) {
          toastr.success(response.message || 'Media approved successfully');
          currentTargetFamilies.forEach(function(ref) { loadGpFamilyMedia(ref, required); });
          $('#gpFamilyMediaModal').modal('hide');
        } else {
          toastr.error(response.message || 'Error approving media');
        }
      },
      error: function(xhr) {
        $btn.prop('disabled', false);
        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error approving media';
        toastr.error(msg);
      }
    });
  });

  function openMediaManager(type) {
    currentMediaType = type;
    $('#current_media_type').val(type);
    $('#media-img-manager').modal('show');
  }

  $('#insert-image').on('click', function(){
    if($('.img-box-choice.selected').length==0) {
      toastr.warning('Please select a media');
      return false;
    }
    var selectedMedia = $('.img-box-choice.selected');
    var mediaId = selectedMedia.data('mediaid');
    var mediaType = $('#current_media_type').val();

    // Check image limit before uploading
    if(mediaType === 'image' && !isGroupPuja) {
      var currentImageCount = $('#images-container .col-md-2').length;
      if(currentImageCount >= 5) {
        toastr.error('Maximum 5 images allowed per puja. Please delete an image first.');
        return false;
      }
    }

    uploadMedia(mediaId, mediaType);
    $('#media-img-manager').modal('hide');
  });

  function uploadMedia(mediaId, mediaType) {
    if(!mediaId) {
      toastr.error('Media ID is required');
      return;
    }

    var data = {
      media_id: mediaId,
      media_type: mediaType,
      _token: '{{ csrf_token() }}'
    };

    if(isGroupPuja) {
      // If modal is open, prefer currentTargetFamilies. Otherwise fallback to selected checkboxes.
      var targets = (Array.isArray(currentTargetFamilies) && currentTargetFamilies.length > 0)
        ? currentTargetFamilies
        : groupPujaReferences;
      data.group_puja_references = targets;
      data.product_id = productId;
      data.astrologer_id = astrologerId;
    } else {
      data.assignment_id = assignmentId;
    }

    $.ajax({
      url: '{{ route("admin.booked-puja.media.upload") }}',
      type: 'POST',
      data: data,
      success: function(response) {
        if(response.success) {
          toastr.success(response.message || 'Media saved successfully');

          // For group puja, update UI without page reload
          if(isGroupPuja && Array.isArray(targets) && targets.length > 0) {
            // Update table rows for all selected families
            var required = 5; // default
            targets.forEach(function(ref) {
              // Reload media for each family to update counts (updates table rows)
              loadGpFamilyMedia(ref, required);
            });

            // If modal is open, handle based on number of families
            if($('#gpFamilyMediaModal').hasClass('show') || $('#gpFamilyMediaModal').is(':visible')) {
              var modalTitle = $('#gpModalTitle').text();

              // If single family upload, reload that family's media in modal
              if(targets.length === 1 && !modalTitle.includes('families selected')) {
                loadGpFamilyMedia(targets[0], required);
              } else if(targets.length > 1 || modalTitle.includes('families selected')) {
                // Bulk upload - close modal to avoid confusion (user can click edit to see specific family)
                $('#gpFamilyMediaModal').modal('hide');
              }
            }
          } else {
            // For one-to-one puja or if no targets, reload page
            location.reload();
          }
        } else {
          toastr.error(response.message || 'Error saving media');
        }
      },
      error: function(xhr) {
        var errorMsg = 'Error saving media. Please try again.';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        }
        toastr.error(errorMsg);
        console.error('Media upload error:', xhr);
      }
    });
  }

  function applyBulkApproval() {
    var status = $('#bulk_approval_status').val();

    if(!status) {
      toastr.warning('Please select an approval status');
      return;
    }

    var data = {
      is_approved: status,
      _token: '{{ csrf_token() }}'
    };

    if(isGroupPuja) {
      data.group_puja_references = groupPujaReferences;
      data.product_id = productId;
      data.astrologer_id = astrologerId;
    } else {
      data.assignment_id = assignmentId;
    }

    $.ajax({
      url: '{{ route("admin.booked-puja.media.bulk-approve") }}',
      type: 'POST',
      data: data,
      success: function(response) {
        if(response.success) {
          toastr.success(response.message || 'Successfully approved');
          location.reload();
        } else {
          toastr.error(response.message || 'Error applying bulk approval');
        }
      },
      error: function(xhr) {
        toastr.error('Error applying bulk approval');
      }
    });
  }

  function deleteMedia(mediaId, type) {
    if(!confirm('Are you sure you want to delete this media? This action cannot be undone.')) return;

    $.ajax({
      url: '{{ route("admin.booked-puja.media.delete") }}',
      type: 'DELETE',
      data: {
        media_id: mediaId,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success) {
          toastr.success(response.message);
          // One-to-one puja: remove by id (images-container / videos-container)
          $('#' + type + '-' + mediaId).remove();
          if($('#' + type + 's-container').length && $('#' + type + 's-container').children().length === 0) {
            $('#' + type + 's-container').html('<div class="col-md-12"><p class="text-muted text-center">No ' + type + 's uploaded yet.</p></div>');
          }
          // Group puja modal: reload family media so deleted item disappears without refresh
          if($('#gpFamilyMediaModal').hasClass('show') && $('#gpFamilyMediaModal').is(':visible') && Array.isArray(currentTargetFamilies) && currentTargetFamilies.length > 0) {
            loadGpFamilyMedia(currentTargetFamilies[0], gpModalRequired);
          }
        } else {
          toastr.error(response.message || 'Error deleting media');
        }
      },
      error: function(xhr) {
        toastr.error('Error deleting media');
      }
    });
  }

  @php
    $notesListForJs = [];
    if(isset($assignment->notes) && $assignment->notes) {
      $decoded = json_decode($assignment->notes, true);
      if(json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        foreach($decoded as $note) {
          if(is_array($note)) {
            $notesListForJs[] = [
              'text' => $note['text'] ?? '',
              'date' => $note['date'] ?? ($assignment->updated_at ? $assignment->updated_at->toISOString() : now()->toISOString()),
              'added_by' => $note['added_by'] ?? 'Admin',
            ];
          } else {
            $notesListForJs[] = [
              'text' => $note,
              'date' => $assignment->updated_at ? $assignment->updated_at->toISOString() : now()->toISOString(),
              'added_by' => 'Admin',
            ];
          }
        }
      } else {
        // If it's a single string, convert to array with date
        $notesListForJs = [[
          'text' => $assignment->notes,
          'date' => $assignment->updated_at ? $assignment->updated_at->toISOString() : now()->toISOString(),
          'added_by' => 'Admin',
        ]];
      }
    }
  @endphp
  var notesArray = @json($notesListForJs);
  var currentAdminName = '{{ auth()->guard("admin")->user()->name ?? auth()->guard("admin")->user()->username ?? "Admin" }}';

  function addNote() {
    var noteText = $('#notes').val().trim();

    if(!noteText) {
      toastr.warning('Please enter a note');
      return;
    }

    // Build display flags based on current checkboxes
    var displayInUser = $('#display_in_user_profile').is(':checked') ? 1 : 0;
    var displayInAstrologer = $('#display_in_astrologer_profile').is(':checked') ? 1 : 0;

    // Add note to array (unshift to add on top)
    var noteObj = {
      text: noteText,
      date: new Date().toISOString(),
      added_by: currentAdminName,
      display_in_user_profile: displayInUser,
      display_in_astrologer_profile: displayInAstrologer
    };

    notesArray.unshift(noteObj);

    // Clear input
    $('#notes').val('');

    // Update display
    updateNotesList();
  }

  function removeNote(index) {
    if(!confirm('Are you sure you want to remove this note?')) {
      return;
    }

    notesArray.splice(index, 1);
    updateNotesList();
    saveNotes();
  }

  function updateNotesList() {
    var notesListHtml = '';

    if(notesArray.length > 0) {
      notesArray.forEach(function(note, index) {
        var noteText = typeof note === 'string' ? note : (note.text || note);
        var addedBy = note.added_by || 'Admin';
        var dateStr = note.date ? formatNoteDate(note.date) : '';
        var displayLabels = [];
        if (note.display_in_user_profile) {
          displayLabels.push('user');
        }
        if (note.display_in_astrologer_profile) {
          displayLabels.push('astrologer');
        }
        notesListHtml += '<div class="card mb-2 note-item" data-index="' + index + '">';
        notesListHtml += '<div class="card-body py-2">';
        notesListHtml += '<p class="mb-1">' + escapeHtml(noteText) + '</p>';
        notesListHtml += '<small class="text-muted d-block">Added by ' + escapeHtml(addedBy) + (dateStr ? ' on ' + dateStr : '') + '</small>';
        if (displayLabels.length > 0) {
          notesListHtml += '<small class="text-muted">For ' + escapeHtml(displayLabels.join(', ')) + '</small>';
        }
        notesListHtml += '</div></div>';
      });
    } else {
      notesListHtml = '<p class="text-muted mb-0"><small>No notes added yet.</small></p>';
    }

    $('#notes-list').html(notesListHtml);
  }

  function formatNoteDate(dateStr) {
    var d = new Date(dateStr);
    if(isNaN(d.getTime())) {
      return '';
    }
    var yyyy = d.getFullYear();
    var mm = String(d.getMonth() + 1).padStart(2, '0');
    var dd = String(d.getDate()).padStart(2, '0');
    return yyyy + '-' + mm + '-' + dd;
  }

  function escapeHtml(text) {
    if(text === null || text === undefined) return '';
    return String(text)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function saveNotes() {
    var formData = {
      id: $('#puja-info-form input[name="id"]').val(),
      notes: JSON.stringify(notesArray),
      _token: '{{ csrf_token() }}'
    };

    $.ajax({
      url: '{{ route("admin.booked-puja.update") }}',
      type: 'POST',
      data: formData,
      success: function(response) {
        if(response.success) {
          // Success - notes saved
        } else {
          toastr.error(response.message || 'Error saving notes');
        }
      },
      error: function(xhr) {
        console.error('Error saving notes:', xhr);
      }
    });
  }

  function savePujaInfo() {
    // If there is text in notes box, add it as a new note first
    var pendingNoteText = $('#notes').val().trim();
    if (pendingNoteText) {
      addNote();
    }

    var formData = {
      id: $('#puja-info-form input[name="id"]').val(),
      status: $('#status').val(),
      notes: JSON.stringify(notesArray),
      display_in_user_profile: $('#display_in_user_profile').is(':checked') ? 1 : 0,
      display_in_astrologer_profile: $('#display_in_astrologer_profile').is(':checked') ? 1 : 0,
      _token: '{{ csrf_token() }}'
    };

    $.ajax({
      url: '{{ route("admin.booked-puja.update") }}',
      type: 'POST',
      data: formData,
      success: function(response) {
        if(response.success) {
          toastr.success(response.message || 'Puja information updated successfully');
          setTimeout(function() { location.reload(); }, 500);
        } else {
          toastr.error(response.message || 'Error updating puja information');
        }
      },
      error: function(xhr) {
        var errorMsg = 'Error updating puja information';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        }
        toastr.error(errorMsg);
      }
    });
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

  function handleVideoFileSelect(input) {
    if(!input || !input.files || !input.files[0]) {
      toastr.error('No file selected');
      return;
    }

    var file = input.files[0];
    var fileType = file.type;
    var fileSizeMB = file.size / (1024 * 1024);
    var maxSizeMB = 10;

    // Validate video file type
    if(!fileType.startsWith('video/')) {
      toastr.error('Please select a valid video file');
      input.value = '';
      return;
    }

    // Validate file size (10MB limit)
    if(fileSizeMB > maxSizeMB) {
      toastr.error('Upload 10 MB or less Video');
      input.value = '';
      return;
    }

    // Upload video file
    uploadVideoFile(file);
  }

  function uploadVideoFile(file) {
    if(!file) {
      toastr.error('No file selected');
      return;
    }

    var formData = new FormData();
    formData.append('file', file);
    formData.append('_token', '{{ csrf_token() }}');

    toastr.info('Uploading video...');

    $.ajax({
      url: '{{ route("admin.booked-puja.video.upload") }}',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      xhr: function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener('progress', function(evt) {
          if(evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            console.log('Upload progress: ' + percentComplete + '%');
          }
        }, false);
        return xhr;
      },
      success: function(response) {
        if(response.success && response.media_id) {
          toastr.success(response.message || 'Video uploaded successfully');
          uploadMedia(response.media_id, 'video');
          $('#video-file-input').val('');
        } else {
          toastr.error(response.message || 'Error uploading video');
          $('#video-file-input').val('');
        }
      },
      error: function(xhr) {
        var errorMsg = 'File failed to upload. Please try again.';

        if(xhr.responseJSON) {
          if(xhr.responseJSON.errors) {
            var errors = xhr.responseJSON.errors;
            var errorMessages = [];
            for(var field in errors) {
              if(errors.hasOwnProperty(field)) {
                errorMessages = errorMessages.concat(errors[field]);
              }
            }
            errorMsg = errorMessages.join(', ');
          } else if(xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          }
        } else if(xhr.status === 413) {
          errorMsg = 'File is too large. Maximum size is 10MB.';
        } else if(xhr.status === 0) {
          errorMsg = 'Upload failed. Please check your PHP upload settings (upload_max_filesize and post_max_size).';
        }

        toastr.error(errorMsg);
        console.error('Upload error:', xhr);
        $('#video-file-input').val('');
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

    // Validate URL format
    try {
      new URL(videoUrl);
    } catch(e) {
      toastr.error('Please enter a valid URL');
      return;
    }

    // Validate video file extension in URL
    var videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm', 'mkv'];
    var urlExtension = videoUrl.split('.').pop().toLowerCase().split('?')[0];

    if(!videoExtensions.includes(urlExtension)) {
      toastr.warning('URL may not be a video file. Supported formats: ' + videoExtensions.join(', '));
    }

    toastr.info('Adding video URL...');

    $.ajax({
      url: '{{ route("admin.booked-puja.video.upload") }}',
      type: 'POST',
      data: {
        video_url: videoUrl,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success && response.media_id) {
          toastr.success(response.message || 'Video URL added successfully');
          $('#videoUrlModal').modal('hide');
          uploadMedia(response.media_id, 'video');
        } else {
          toastr.error(response.message || 'Error adding video URL');
        }
      },
      error: function(xhr) {
        var errorMsg = 'Failed to add video URL. Please try again.';

        if(xhr.responseJSON) {
          if(xhr.responseJSON.errors) {
            var errors = xhr.responseJSON.errors;
            var errorMessages = [];
            for(var field in errors) {
              if(errors.hasOwnProperty(field)) {
                errorMessages = errorMessages.concat(errors[field]);
              }
            }
            errorMsg = errorMessages.join(', ');
          } else if(xhr.responseJSON.message) {
            errorMsg = xhr.responseJSON.message;
          }
        }

        toastr.error(errorMsg);
        console.error('Video URL upload error:', xhr);
      }
    });
  }

  // Group modal actions
  $(document).on('click', '#gpAddPhotosBtn', function() {
    if(!astrologerId || !productId) {
      toastr.error('Astrologer/Product missing for upload');
      return;
    }
    currentTargetFamilies = currentTargetFamilies && currentTargetFamilies.length ? currentTargetFamilies : groupPujaReferences;
    // Use multiple file input for "Add Photos" button (max 5 images)
    $('#gp-photo-multiple-input').click();
  });

  // Clicking on any photo slot (empty or filled) should open device file picker for single image
  $(document).on('click', '.gp-photo-slot', function() {
    if(!astrologerId || !productId) {
      toastr.error('Astrologer/Product missing for upload');
      return;
    }
    // ensure currentTargetFamilies is set (single family or selected families)
    currentTargetFamilies = currentTargetFamilies && currentTargetFamilies.length ? currentTargetFamilies : groupPujaReferences;
    // Use single file input for photo slot click
    $('#gp-photo-file-input').click();
  });

  // Handle single image selection (from photo slot click)
  $(document).on('change', '#gp-photo-file-input', function() {
    if(this.files && this.files[0]) {
      uploadGpPhotoFile(this.files[0]);
      $(this).val('');
    }
  });

  // Handle multiple image selection (from "Add Photos" button)
  $(document).on('change', '#gp-photo-multiple-input', function() {
    if(this.files && this.files.length > 0) {
      var files = Array.from(this.files);
      var maxFiles = 5;

      // Limit to 5 files
      if(files.length > maxFiles) {
        toastr.warning('Maximum ' + maxFiles + ' images allowed. Only first ' + maxFiles + ' will be uploaded.');
        files = files.slice(0, maxFiles);
      }

      // Upload files sequentially
      uploadGpPhotoFilesSequentially(files, 0);
      $(this).val('');
    }
  });

  function uploadGpPhotoFile(file) {
    if(!file) {
      toastr.error('No image selected');
      return;
    }
    if(!Array.isArray(currentTargetFamilies) || currentTargetFamilies.length === 0) {
      toastr.error('No families selected');
      return;
    }

    var formData = new FormData();
    formData.append('file', file);
    formData.append('type', media_image_type);
    formData.append('_token', '{{ csrf_token() }}');

    toastr.info('Uploading image...');

    $.ajax({
      url: '{{ route("admin.media.upload") }}',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      success: function(resp) {
        if(resp && resp.media_id) {
          var data = {
            media_id: resp.media_id,
            media_type: 'image',
            group_puja_references: currentTargetFamilies,
            product_id: productId,
            astrologer_id: astrologerId,
            _token: '{{ csrf_token() }}'
          };

          $.ajax({
            url: '{{ route("admin.booked-puja.media.upload") }}',
            type: 'POST',
            data: data,
            success: function(response) {
              if(response.success) {
                toastr.success(response.message || 'Image uploaded successfully');
                // Reload media for all selected families
                var required = parseInt($('#gpModalPhotosRequired').text() || 5);
                if(currentTargetFamilies && currentTargetFamilies.length > 0) {
                  // Update table rows for all families
                  currentTargetFamilies.forEach(function(ref) {
                    loadGpFamilyMedia(ref, required);
                  });

                  // If modal is open, handle based on number of families
                  if($('#gpFamilyMediaModal').hasClass('show') || $('#gpFamilyMediaModal').is(':visible')) {
                    var modalTitle = $('#gpModalTitle').text();

                    // If single family upload, reload that family's media in modal
                    if(currentTargetFamilies.length === 1 && !modalTitle.includes('families selected')) {
                      loadGpFamilyMedia(currentTargetFamilies[0], required);
                    } else if(currentTargetFamilies.length > 1 || modalTitle.includes('families selected')) {
                      // Bulk upload - close modal to avoid confusion
                      $('#gpFamilyMediaModal').modal('hide');
                    }
                  }
                }
              } else {
                toastr.error(response.message || 'Error saving image');
              }
            },
            error: function(xhr) {
              var errorMsg = 'Error saving image. Please try again.';
              if(xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
              }
              toastr.error(errorMsg);
            }
          });
        } else {
          toastr.error('Error uploading image');
        }
      },
      error: function(xhr) {
        var errorMsg = 'File failed to upload. Please try again.';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        }
        toastr.error(errorMsg);
      }
    });
  }

  // Upload multiple images sequentially
  function uploadGpPhotoFilesSequentially(files, index) {
    if(index >= files.length) {
      // All files uploaded, reload media
      var required = parseInt($('#gpModalPhotosRequired').text() || 5);
      if(currentTargetFamilies && currentTargetFamilies.length > 0) {
        // Update table rows for all families
        currentTargetFamilies.forEach(function(ref) {
          loadGpFamilyMedia(ref, required);
        });

        // If modal is open, reload media for the family being viewed (don't close modal)
        if($('#gpFamilyMediaModal').hasClass('show') || $('#gpFamilyMediaModal').is(':visible')) {
          var modalTitle = $('#gpModalTitle').text();

          // If single family upload, reload that family's media in modal
          if(currentTargetFamilies.length === 1 && !modalTitle.includes('families selected')) {
            loadGpFamilyMedia(currentTargetFamilies[0], required);
          } else if(currentTargetFamilies.length > 1 || modalTitle.includes('families selected')) {
            // Bulk upload - reload first family's media in modal (keep modal open)
            loadGpFamilyMedia(currentTargetFamilies[0], required);
          }
        }
      }
      toastr.success('All ' + files.length + ' images uploaded successfully');
      return;
    }

    var file = files[index];
    var formData = new FormData();
    formData.append('file', file);
    formData.append('type', media_image_type);
    formData.append('_token', '{{ csrf_token() }}');

    toastr.info('Uploading image ' + (index + 1) + ' of ' + files.length + '...');

    $.ajax({
      url: '{{ route("admin.media.upload") }}',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      success: function(resp) {
        if(resp && resp.media_id) {
          var data = {
            media_id: resp.media_id,
            media_type: 'image',
            group_puja_references: currentTargetFamilies,
            product_id: productId,
            astrologer_id: astrologerId,
            _token: '{{ csrf_token() }}'
          };

          $.ajax({
            url: '{{ route("admin.booked-puja.media.upload") }}',
            type: 'POST',
            data: data,
            success: function(response) {
              if(response.success) {
                // Upload next file
                uploadGpPhotoFilesSequentially(files, index + 1);
              } else {
                toastr.error('Error saving image ' + (index + 1) + ': ' + (response.message || 'Unknown error'));
                // Continue with next file even if this one failed
                uploadGpPhotoFilesSequentially(files, index + 1);
              }
            },
            error: function(xhr) {
              var errorMsg = 'Error saving image ' + (index + 1);
              if(xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg += ': ' + xhr.responseJSON.message;
              }
              toastr.error(errorMsg);
              // Continue with next file even if this one failed
              uploadGpPhotoFilesSequentially(files, index + 1);
            }
          });
        } else {
          toastr.error('Error uploading image ' + (index + 1));
          // Continue with next file even if this one failed
          uploadGpPhotoFilesSequentially(files, index + 1);
        }
      },
      error: function(xhr) {
        var errorMsg = 'File ' + (index + 1) + ' failed to upload';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg += ': ' + xhr.responseJSON.message;
        }
        toastr.error(errorMsg);
        // Continue with next file even if this one failed
        uploadGpPhotoFilesSequentially(files, index + 1);
      }
    });
  }

  $(document).on('click', '#gpUploadVideoBtn', function() {
    if(!astrologerId || !productId) {
      toastr.error('Astrologer/Product missing for upload');
      return;
    }
    $('#gp-video-file-input').click();
  });

  $(document).on('change', '#gp-video-file-input', function() {
    if(this.files && this.files[0]) {
      uploadVideoFile(this.files[0]); // reuses existing uploader + uploadMedia()
      $(this).val('');
    }
  });

  $(document).on('click', '#gpAddVideoLinkBtn', function() {
    var videoUrl = ($('#gp-video-url-input').val() || '').trim();
    if(!videoUrl) {
      toastr.error('Please enter a video URL');
      return;
    }

    // First, create media record with video URL
    $.ajax({
      url: '{{ route("admin.booked-puja.video.upload") }}',
      type: 'POST',
      data: {
        video_url: videoUrl,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success && response.media_id) {
          // Now upload the media for group puja families without reloading page
          var targets = (Array.isArray(currentTargetFamilies) && currentTargetFamilies.length > 0)
            ? currentTargetFamilies
            : groupPujaReferences;

          if(!targets || targets.length === 0) {
            toastr.error('No families selected');
            return;
          }

          var data = {
            media_id: response.media_id,
            media_type: 'video',
            group_puja_references: targets,
            product_id: productId,
            astrologer_id: astrologerId,
            _token: '{{ csrf_token() }}'
          };

          $.ajax({
            url: '{{ route("admin.booked-puja.media.upload") }}',
            type: 'POST',
            data: data,
            success: function(uploadResponse) {
              if(uploadResponse.success) {
                toastr.success(uploadResponse.message || 'Video URL added successfully');
                // Do not clear input so added link remains visible; new links show in Video Link tab list
                // Reload media for all selected families
                var required = parseInt($('#gpModalPhotosRequired').text() || 5);
                if(currentTargetFamilies && currentTargetFamilies.length > 0) {
                  // Update table rows for all families
                  currentTargetFamilies.forEach(function(ref) {
                    loadGpFamilyMedia(ref, required);
                  });

                  // If modal is open, handle based on number of families
                  if($('#gpFamilyMediaModal').hasClass('show') || $('#gpFamilyMediaModal').is(':visible')) {
                    var modalTitle = $('#gpModalTitle').text();

                    // If single family upload, reload that family's media in modal
                    if(currentTargetFamilies.length === 1 && !modalTitle.includes('families selected')) {
                      loadGpFamilyMedia(currentTargetFamilies[0], required);
                    } else if(currentTargetFamilies.length > 1 || modalTitle.includes('families selected')) {
                      // Bulk upload - close modal to avoid confusion
                      $('#gpFamilyMediaModal').modal('hide');
                    }
                  }
                } else {
                  // Fallback: update stats
                  recalcGpStats();
                }
              } else {
                toastr.error(uploadResponse.message || 'Error saving video');
              }
            },
            error: function(xhr) {
              var errorMsg = 'Error saving video. Please try again.';
              if(xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
              }
              toastr.error(errorMsg);
            }
          });
        } else {
          toastr.error(response.message || 'Error adding video URL');
        }
      },
      error: function(xhr) {
        var errorMsg = 'Failed to add video URL';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          errorMsg = xhr.responseJSON.message;
        }
        toastr.error(errorMsg);
      }
    });
  });
</script>
@endpush
