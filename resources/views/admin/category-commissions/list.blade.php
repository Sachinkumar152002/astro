@extends('layouts.admin')

@section('content')
<div class="px-3">
  <div class="row">
    <div class="col-md-8">
      <h1 class="text-primary">Category Commissions</h1>
      <p class="text-muted">Configure commission rates for all product categories. Edit values directly in the table and click Save.</p>
    </div>
    <div class="col-md-4 text-end">
      <div class="commission-mode-badge">
        <span class="badge-label">Commission Setup:</span>
        <span class="badge badge-mode">{{$commissionMode == 'PERCENTAGE' ? 'Percentage (%)' : 'Fixed (₹)'}}</span>
      </div>
    </div>
  </div>

  <form id="commissionForm" method="POST" action="{{route('admin.category_commissions.submit')}}">
    @csrf

    <!-- Product Categories Table -->
    <div class="category-section mt-4">
      <h4 class="section-heading">
        <i class="bi bi-box-seam"></i> Product Categories
      </h4>
      <div class="table-responsive">
        <table class="table table-striped table-hover category-table">
          <thead class="table-header">
            <tr>
              <th class="col-sno">Sno.</th>
              <th class="col-name">Category Name</th>
              <th class="col-commission">Commission Value</th>
              <th class="col-maxcap">Max Cap (₹)</th>
              <th class="col-status">Status</th>
            </tr>
          </thead>
          <tbody>
            @php
              $sno = 1;
            @endphp
            @forelse($productCategories as $category)
              @php
                $commission = isset($existingCommissions[$category->id]) ? $existingCommissions[$category->id] : null;
              @endphp
              <tr>
                <td class="text-center">{{$sno++}}</td>
                <td>
                  <input type="hidden" name="commissions[{{$category->id}}][category_id]" value="{{$category->id}}">
                  <strong>{{$category->name}}</strong>
                </td>
                <td class="text-center">
                  <input type="number"
                         name="commissions[{{$category->id}}][commission_value]"
                         class="form-control form-control-sm text-center commission-input"
                         value="{{$commission ? $commission->commission_value : '0'}}"
                         step="0.01"
                         min="0"
                         placeholder="0.00">
                </td>
                <td class="text-center">
                  <input type="number"
                         name="commissions[{{$category->id}}][max_cap]"
                         class="form-control form-control-sm text-center commission-input"
                         value="{{$commission && $commission->max_cap ? $commission->max_cap : ''}}"
                         step="0.01"
                         min="0"
                         placeholder="No limit">
                </td>
                <td class="text-center">
                  <label class="toggle-switch">
                    <input type="checkbox"
                           class="status-toggle"
                           name="commissions[{{$category->id}}][status]"
                           value="1"
                           id="status_{{$category->id}}"
                           {{$commission && $commission->status == 1 ? 'checked' : ''}}>
                    <span class="toggle-slider"></span>
                  </label>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">No Product Categories found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Service Categories Table -->
    <div class="category-section mt-4">
      <h4 class="section-heading">
        <i class="bi bi-file-earmark-text"></i> Services (Report) Categories
      </h4>
      <div class="table-responsive">
        <table class="table table-striped table-hover category-table">
          <thead class="table-header">
            <tr>
              <th class="col-sno">Sno.</th>
              <th class="col-name">Category Name</th>
              <th class="col-commission">Commission Value</th>
              <th class="col-maxcap">Max Cap (₹)</th>
              <th class="col-status">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($serviceCategories as $category)
              @php
                $commission = isset($existingCommissions[$category->id]) ? $existingCommissions[$category->id] : null;
              @endphp
              <tr>
                <td class="text-center">{{$sno++}}</td>
                <td>
                  <input type="hidden" name="commissions[{{$category->id}}][category_id]" value="{{$category->id}}">
                  <strong>{{$category->name}}</strong>
                </td>
                <td class="text-center">
                  <input type="number"
                         name="commissions[{{$category->id}}][commission_value]"
                         class="form-control form-control-sm text-center commission-input"
                         value="{{$commission ? $commission->commission_value : '0'}}"
                         step="0.01"
                         min="0"
                         placeholder="0.00">
                </td>
                <td class="text-center">
                  <input type="number"
                         name="commissions[{{$category->id}}][max_cap]"
                         class="form-control form-control-sm text-center commission-input"
                         value="{{$commission && $commission->max_cap ? $commission->max_cap : ''}}"
                         step="0.01"
                         min="0"
                         placeholder="No limit">
                </td>
                <td class="text-center">
                  <label class="toggle-switch">
                    <input type="checkbox"
                           class="status-toggle"
                           name="commissions[{{$category->id}}][status]"
                           value="1"
                           id="status_{{$category->id}}"
                           {{$commission && $commission->status == 1 ? 'checked' : ''}}>
                    <span class="toggle-slider"></span>
                  </label>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">No Service Categories found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Puja Categories Table -->
    <div class="category-section mt-4">
      <h4 class="section-heading">
        <i class="bi bi-calendar-heart"></i> Puja Categories
      </h4>
      <div class="table-responsive">
        <table class="table table-striped table-hover category-table">
          <thead class="table-header">
            <tr>
              <th class="col-sno">Sno.</th>
              <th class="col-name">Category Name</th>
              <th class="col-commission">Commission Value</th>
              <th class="col-maxcap">Max Cap (₹)</th>
              <th class="col-status">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pujaCategories as $category)
              @php
                $commission = isset($existingCommissions[$category->id]) ? $existingCommissions[$category->id] : null;
              @endphp
              <tr>
                <td class="text-center">{{$sno++}}</td>
                <td>
                  <input type="hidden" name="commissions[{{$category->id}}][category_id]" value="{{$category->id}}">
                  <strong>{{$category->name}}</strong>
                </td>
                <td class="text-center">
                  <input type="number"
                         name="commissions[{{$category->id}}][commission_value]"
                         class="form-control form-control-sm text-center commission-input"
                         value="{{$commission ? $commission->commission_value : '0'}}"
                         step="0.01"
                         min="0"
                         placeholder="0.00">
                </td>
                <td class="text-center">
                  <input type="number"
                         name="commissions[{{$category->id}}][max_cap]"
                         class="form-control form-control-sm text-center commission-input"
                         value="{{$commission && $commission->max_cap ? $commission->max_cap : ''}}"
                         step="0.01"
                         min="0"
                         placeholder="No limit">
                </td>
                <td class="text-center">
                  <label class="toggle-switch">
                    <input type="checkbox"
                           class="status-toggle"
                           name="commissions[{{$category->id}}][status]"
                           value="1"
                           id="status_{{$category->id}}"
                           {{$commission && $commission->status == 1 ? 'checked' : ''}}>
                    <span class="toggle-slider"></span>
                  </label>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">No Puja Categories found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-12 text-end">
        <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
          <i class="bi bi-arrow-clockwise"></i> Reset
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save"></i> Save All Commissions
        </button>
      </div>
    </div>
  </form>
</div>
@endsection

@push('styles')
<style>
  /* Category Section */
  .category-section {
    margin-bottom: 30px;
  }

  .section-heading {
    color: #007bff;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
  }

  /* Table Styling */
  .category-table {
    background-color: #ffffff;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 0;
  }

  .category-table thead th {
    background-color: #007bff;
    color: #ffffff;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
    padding: 10px 12px;
    border: none;
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .category-table tbody td {
    padding: 8px 12px;
    border: none;
    vertical-align: middle;
    background-color: #ffffff;
  }

  .category-table tbody tr:nth-of-type(even) td {
    background-color: #f8f9fa;
  }

  .category-table tbody tr:hover td {
    background-color: #e9ecef !important;
    transition: background-color 0.2s ease;
  }

  /* Column Widths */
  .col-sno {
    width: 60px;
  }

  .col-name {
    min-width: 300px;
  }

  .col-commission {
    min-width: 180px;
  }

  .col-maxcap {
    min-width: 180px;
  }

  .col-status {
    min-width: 150px;
  }

  /* Input Styling */
  .commission-input {
    width: 140px;
    margin: 0 auto;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  .commission-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
  }

  /* Toggle Switch Styles */
  .toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 30px;
    margin: 0;
  }

  .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 30px;
  }

  .toggle-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  }

  .toggle-switch input:checked + .toggle-slider {
    background-color: #28a745;
  }

  .toggle-switch input:focus + .toggle-slider {
    box-shadow: 0 0 1px #28a745;
  }

  .toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(30px);
  }

  /* Table Container */
  .table-responsive {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background-color: #ffffff;
  }

  /* Text Styling */
  .category-table tbody td strong {
    color: #333;
  }

  /* Commission Mode Badge */
  .commission-mode-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    margin-top: 10px;
  }

  .badge-label {
    font-weight: 600;
    color: #495057;
    font-size: 14px;
  }

  .badge-mode {
    font-size: 14px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 4px;
  }

  .badge-mode {
    background-color: #007bff;
    color: #ffffff;
  }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
  // Handle form submission
  $('#commissionForm').on('submit', function(e) {
    e.preventDefault();

    // Collect all form data
    var formData = $(this).serializeArray();
    var commissions = {};

    // Organize data by category_id
    formData.forEach(function(item) {
      if(item.name.startsWith('commissions[')) {
        var match = item.name.match(/commissions\[(\d+)\]\[(\w+)\]/);
        if(match) {
          var catId = match[1];
          var field = match[2];

          if(!commissions[catId]) {
            commissions[catId] = {};
          }

          if(field === 'status') {
            // If toggle is checked, status = 1 (enabled), else 0 (disabled)
            commissions[catId][field] = item.value === '1' ? 1 : 0;
          } else {
            commissions[catId][field] = item.value;
          }
        }
      }
    });

    // Convert to array format
    var commissionsArray = Object.keys(commissions).map(function(key) {
      return commissions[key];
    });

    // Submit via AJAX
    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: {
        _token: $('input[name="_token"]').val(),
        commissions: commissionsArray
      },
      dataType: 'json',
      beforeSend: function() {
        $('button[type="submit"]').prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');
      },
      success: function(response) {
        toastr.success(response.message);
        setTimeout(function() {
          window.location.href = response.redirectto;
        }, 1000);
      },
      error: function(xhr) {
        var errors = xhr.responseJSON?.errors || {};
        var errorMsg = 'Error saving commissions';

        if(errors.general) {
          errorMsg = errors.general[0];
        }

        toastr.error(errorMsg);
        $('button[type="submit"]').prop('disabled', false).html('<i class="bi bi-save"></i> Save All Commissions');
      }
    });
  });

  // Handle toggle switches - if checked, commission is enabled (status = 1), if unchecked, disabled (status = 0)
  $('.status-toggle').on('change', function() {
    if($(this).is(':checked')) {
      $(this).val('1');
    } else {
      $(this).val('0');
    }
  });

  // Initialize toggle values on page load
  $('.status-toggle').each(function() {
    if(!$(this).is(':checked')) {
      $(this).val('0');
    } else {
      $(this).val('1');
    }
  });
});
</script>
@endpush
