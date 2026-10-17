@extends(themeView().'layout')

@section('title', 'Assigned Puja')
@section('keywords', '')
@section('description','')

@push('style')
<style>
  .assigned-puja-table img.puja-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
  }
  .assigned-puja-table th,
  .assigned-puja-table td {
    vertical-align: middle;
    font-size: 14px;
  }
  .assigned-puja-table .puja-name {
    font-weight: 600;
    color: #333;
  }
  .assigned-puja-table .status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    background-color: #28a745;
    color: #fff;
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
          <ul class="nav nav-tabs mb-3" id="assignedPujaTabs" role="tablist">
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
          <div class="tab-content" id="assignedPujaTabContent">
            <!-- One to One Puja Tab -->
            <div class="tab-pane fade show active" id="one-to-one" role="tabpanel" aria-labelledby="one-to-one-tab">
              <div class="card border-n">
                <div class="card-header bg-white border-bottom">
                  <h4 class="mb-0">One to One Puja</h4>
                </div>
                <div class="card-body">
                  @if($oneToOnePujas->count() > 0)
                    <div class="table-responsive">
                      <table class="table table-striped assigned-puja-table mb-0">
                        <thead>
                          <tr>
                            <th>Image</th>
                            <th>Puja Name</th>
                            <th>Commission</th>
                            <th>Assigned On</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($oneToOnePujas as $puja)
                            @php
                              $commissionType = $puja->commission_type ?? 'percentage';
                              $commissionValue = $puja->astrologer_commission_percentage;
                              $commissionDisplay = '-';
                              if($commissionValue !== null && $commissionValue !== ''){
                                if($commissionType === 'fixed'){
                                  $commissionDisplay = '₹' . rtrim(rtrim(number_format($commissionValue, 2, '.', ''), '0'), '.');
                                } else {
                                  $commissionDisplay = rtrim(rtrim(number_format($commissionValue, 2, '.', ''), '0'), '.') . '%';
                                }
                              }
                              $pivot = $puja->astrologers->first()->pivot ?? null;
                              $assignedOn = $pivot && $pivot->created_at
                                ? \Carbon\Carbon::parse($pivot->created_at)->format('d M Y')
                                : ($puja->created_at ? \Carbon\Carbon::parse($puja->created_at)->format('d M Y') : null);
                            @endphp
                            <tr>
                              <td>
                                @if($puja->image)
                                  <img src="{{ $puja->image->image(120, 120) }}" alt="{{ $puja->title ?? 'Puja' }}" class="puja-thumb">
                                @else
                                  <img src="{{ no_image() }}" alt="Puja" class="puja-thumb">
                                @endif
                              </td>
                              <td class="puja-name">
                                {{ $puja->title ?? 'Puja #' . $puja->id }}
                              </td>
                              <td>{{ $commissionDisplay }}</td>
                              <td>{{ $assignedOn ?? 'N/A' }}</td>
                              <td><span class="status-badge">Assigned</span></td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @else
                    <div class="text-center py-5">
                      <p class="text-muted">No one-to-one pujas assigned to you yet.</p>
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <!-- Family Puja Tab -->
            <div class="tab-pane fade" id="group" role="tabpanel" aria-labelledby="group-tab">
              <div class="card border-n">
                <div class="card-header bg-white border-bottom">
                  <h4 class="mb-0">Family Puja</h4>
                </div>
                <div class="card-body">
                  @if($groupPujas->count() > 0)
                    <div class="table-responsive">
                      <table class="table table-striped assigned-puja-table mb-0">
                        <thead>
                          <tr>
                            <th>Image</th>
                            <th>Puja Name</th>
                            <th>Assigned On</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($groupPujas as $puja)
                            @php
                              $pivot = $puja->astrologers->first()->pivot ?? null;
                              $assignedOn = $pivot && $pivot->created_at
                                ? \Carbon\Carbon::parse($pivot->created_at)->format('d M Y')
                                : ($puja->created_at ? \Carbon\Carbon::parse($puja->created_at)->format('d M Y') : null);
                            @endphp
                            <tr>
                              <td>
                                @if($puja->image)
                                  <img src="{{ $puja->image->image(120, 120) }}" alt="{{ $puja->title ?? 'Puja' }}" class="puja-thumb">
                                @else
                                  <img src="{{ no_image() }}" alt="Puja" class="puja-thumb">
                                @endif
                              </td>
                              <td class="puja-name">
                                {{ $puja->title ?? 'Puja #' . $puja->id }}
                              </td>
                              <td>{{ $assignedOn ?? 'N/A' }}</td>
                              <td><span class="status-badge">Assigned</span></td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @else
                    <div class="text-center py-5">
                      <p class="text-muted">No family pujas assigned to you yet.</p>
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

@endsection
