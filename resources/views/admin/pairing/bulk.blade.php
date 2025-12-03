<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bulk Pairing</title>
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<!-- Toggle Button for Mobile -->
<button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidenav -->
<aside class="sidenav" id="sidenav">
    <div class="logo-container">
        <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="Logo" class="logo-img">
    </div>
    <nav class="nav-links">
      <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
      <span class="nav-link-locked" style="display: block; padding: 12px 16px; color: #9ca3af; cursor: not-allowed; opacity: 0.6; position: relative;"><i class="fas fa-pen"></i> Posting <i class="fas fa-lock" style="font-size: 10px; margin-left: 8px; opacity: 0.7;"></i></span>
      <a href="{{ url('/admin/pairing') }}" class="{{ request()->is('admin/pairing') ? 'active' : '' }}"><i class="fas fa-link"></i> Pairing</a>
      <a href="{{ url('/admin/drivers') }}" class="{{ request()->is('admin/drivers*') ? 'active' : '' }}"><i class="fas fa-car"></i> Drivers</a>
      <a href="{{ url('/admin/medics') }}" class="{{ request()->is('admin/medics*') ? 'active' : '' }}"><i class="fas fa-user-md"></i> Medics</a>
      <a href="{{ url('/admin/gps') }}" class="{{ request()->is('admin/gps') ? 'active' : '' }}"><i class="fas fa-map-marker-alt mr-1"></i> GPS Tracker</a>
      <a href="{{ url('/admin/ambulances') }}" class="{{ request()->is('admin/ambulances*') ? 'active' : '' }}"><i class="fas fa-ambulance mr-1"></i> Ambulances</a>

      <div class="logout-form">
          <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit">
                  <i class="fas fa-sign-out-alt mr-2"></i> Logout
              </button>
          </form>
      </div>
      <!-- placeholder ng register -->
      <a href="{{ url('/') }}"><i class="fas fa-user-plus mr-1"></i></a>
    </nav>
</aside>

<!-- Fixed Top Header -->
<div class="mdrrmo-header" style="border: 2px solid #031273;">
    <h2 class="header-title">SILANG MDRRMO</h2>
</div>

<main class="main-content pt-8">
  <!-- Pairing Navigation Bar -->
  <nav class="pairing-navbar">
    <div class="pairing-nav-container">
      <a href="{{ route('admin.pairing.index') }}" class="pairing-nav-item">
        Pairing
      </a>
      <a href="{{ route('admin.pairing.log') }}" class="pairing-nav-item">
        View Log
      </a>
      <a href="{{ route('admin.pairing.bulk') }}" class="pairing-nav-item" style="background: var(--brand-orange); color: var(--brand-navy);">
        Bulk Pairing
      </a>
      <a href="{{ route('admin.pairing.driver-medic.create') }}" class="pairing-nav-item">
        Pair Driver-Medic
      </a>
      <a href="{{ route('admin.pairing.driver-ambulance.create') }}" class="pairing-nav-item">
        Pair Driver-Ambulance
      </a>
    </div>
  </nav>
  
  <style>
    /* Local color palette */
    #bulkCompact { --bulk-navy:#0c2d5a; --bulk-orange:#f28c28; --bulk-surface:#ffffff; --bulk-muted:#f6f7fb; --bulk-border:#e5e7eb; --bulk-text:#1f2937; --bulk-accent:#7c3aed; }
    /* Compact, modern fixed layout (no inner scroll) */
    #bulkCompact.containers-grid { height: 560px; padding: 0.75rem !important; background: var(--bulk-surface); }
    /* Header card */
    #bulkCompact .header-card { background: linear-gradient(135deg, var(--bulk-navy) 0%, #1e3a8a 65%); color: #fff; border-radius: 12px; padding: 0.8rem 1rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 8px 20px rgba(3,18,115,0.25); position: relative; overflow: hidden; }
    #bulkCompact .header-card::before { content: ""; position: absolute; inset: 0; opacity: .15; background: radial-gradient(80% 60% at 100% 0%, #4CC9F0 0%, transparent 60%); pointer-events: none; }
    #bulkCompact .title-wrap { display:flex; align-items:center; gap:.75rem; }
    #bulkCompact .title-icon { width:42px; height:42px; border-radius:10px; background:linear-gradient(135deg, var(--bulk-orange), #ff8c42); color:#0b0b0b; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 16px rgba(242,140,40,.35); }
    #bulkCompact .title-text h3 { margin:0; font-size:1.1rem; line-height:1.1; letter-spacing:.3px; }
    #bulkCompact .title-text p { margin:.15rem 0 0 0; font-size:.8rem; opacity:.9; }

    #bulkCompact .card { background: var(--bulk-surface); border: 1px solid var(--bulk-border); border-radius: 14px; box-shadow: 0 8px 22px rgba(3,18,115,0.12); height: calc(560px - 84px); display: flex; flex-direction: column; padding: 0.9rem; position: relative; }
    #bulkCompact .card .accent-bar { position:absolute; left:0; top:0; height:6px; width:100%; background: linear-gradient(90deg, var(--bulk-orange), #4CC9F0, var(--bulk-accent)); border-top-left-radius:14px; border-top-right-radius:14px; }

    #bulkCompact .form-body { display:grid; grid-template-rows:auto auto 1fr auto; gap:.6rem; flex:1 1 auto; overflow:hidden; }
    #bulkCompact .row-inline { display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:.6rem; align-items:end; }
    #bulkCompact .row-duo { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:.6rem; }
    #bulkCompact label { font-size:.85rem; font-weight:800; color:var(--bulk-text); margin-bottom:.25rem; }
    #bulkCompact input[type="date"], #bulkCompact input[type="time"], #bulkCompact textarea, #bulkCompact select { padding:.55rem .65rem !important; border-radius:10px !important; border:1.6px solid var(--bulk-border) !important; font-size:.93rem !important; background:#fff; }
    #bulkCompact input:focus, #bulkCompact textarea:focus, #bulkCompact select:focus { outline:none; box-shadow:0 0 0 3px rgba(76,201,240,.18); border-color:#4CC9F0 !important; }

    /* Chip selectors */
    #bulkCompact .chip-list { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:.4rem; background:var(--bulk-muted); border:1.5px solid var(--bulk-border); border-radius:12px; padding:.55rem; height:160px; overflow:hidden; }
    #bulkCompact .chip-list label { display:grid; grid-template-columns:16px 1fr; align-items:center; gap:.4rem; background:#fff; border:1px solid var(--bulk-border); border-radius:999px; padding:.42rem .7rem; font-size:.86rem; line-height:1.1; min-width:0; }
    #bulkCompact .chip-list label span { white-space:normal; overflow:hidden; text-overflow:ellipsis; min-width:0; word-break:break-word; }
    #bulkCompact .chip-list input[type="checkbox"] { width:14px; height:14px; }
    #bulkCompact .helper-link { font-size:.8rem; color:#2563eb; }
    #bulkCompact .helper-link:hover { color:#1e40af; }

    /* Buttons */
    #bulkCompact .btn-primary { background:linear-gradient(135deg, var(--bulk-accent) 0%, #5b21b6 100%); color:#fff; border:none; border-radius:10px; padding:.6rem 1.1rem; font-weight:800; letter-spacing:.2px; box-shadow:0 6px 16px rgba(124,58,237,.35); }
    #bulkCompact .btn-primary:hover { filter:brightness(1.03); transform:translateY(-1px); }
    #bulkCompact .btn-secondary { background:#6b7280; color:#fff; border:none; border-radius:10px; padding:.6rem 1rem; font-weight:800; letter-spacing:.2px; box-shadow:0 4px 12px rgba(107,114,128,.25); }
    #bulkCompact .btn-secondary:hover { filter:brightness(1.05); transform:translateY(-1px); }

    /* Actions */
    #bulkCompact .actions-bottom { margin-top:auto; padding-top:.6rem; display:flex; justify-content:flex-end; gap:.5rem; }
  </style>

  <section id="bulkCompact" class="containers-grid" style="max-width:800px;">
    <div class="header-card">
      <div class="title-wrap">
        <div class="title-icon"><i class="fas fa-layer-group"></i></div>
        <div class="title-text">
          <h3>Bulk Pairing</h3>
          <p>Configure type, date, and select participants</p>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-2">{{ session('success') }}</div>
    @endif

    <div class="card">
      <div class="accent-bar"></div>
      <form action="{{ route('admin.pairing.bulk.store') }}" method="POST" class="form-body">
        @csrf
        
        <div class="row-inline">
          <div>
            <label>Pairing Type *</label>
            <select name="pairing_type" id="pairing_type" required onchange="togglePairingType()" class="w-full">
              <option value="">Select Pairing Type</option>
              <option value="driver_medic">Driver-Medic Pairing</option>
              <option value="driver_ambulance">Driver-Ambulance Pairing</option>
            </select>
          </div>
          <div>
            <label>Pairing Date *</label>
            <input type="date" name="pairing_date" id="pairing_date" value="{{ old('pairing_date', $selectedDate) }}" required class="w-full" onchange="refreshBulkPairingOptions()">
          </div>
          <div id="time_fields" style="display: none;">
            <div class="row-duo">
              <div>
                <label>Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}" required class="w-full">
              </div>
              <div>
                <label>End Time</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}" required class="w-full">
              </div>
            </div>
          </div>
        </div>

        <div class="row-duo">
          <div>
            <label>Select Drivers *</label>
            <div class="chip-list">
              @foreach($drivers as $driver)
                <label class="{{ in_array($driver->id, $pairedDrivers) ? 'opacity-50' : '' }}">
                  <input type="checkbox" name="drivers[]" value="{{ $driver->id }}" class="driver-checkbox {{ in_array($driver->id, $pairedDrivers) ? 'disabled' : '' }}" {{ in_array($driver->id, $pairedDrivers) ? 'disabled' : '' }}>
                  <span class="{{ in_array($driver->id, $pairedDrivers) ? 'text-gray-500' : '' }}">{{ $driver->name }}@if(in_array($driver->id, $pairedDrivers)) <span class="text-xs text-red-500">(paired)</span>@endif</span>
                </label>
              @endforeach
            </div>
            <button type="button" onclick="selectAllDrivers()" class="helper-link mt-1">Select All (Max 2)</button>
          </div>

          <div id="medic_selection" style="display: none;">
            <label>Select Medics *</label>
            <div class="chip-list">
              @foreach($medics as $medic)
                <label class="{{ in_array($medic->id, $pairedMedics) ? 'opacity-50' : '' }}">
                  <input type="checkbox" name="medics[]" value="{{ $medic->id }}" class="medic-checkbox {{ in_array($medic->id, $pairedMedics) ? 'disabled' : '' }}" {{ in_array($medic->id, $pairedMedics) ? 'disabled' : '' }}>
                  <span class="{{ in_array($medic->id, $pairedMedics) ? 'text-gray-500' : '' }}">{{ $medic->name }} ({{ $medic->specialization ?? 'N/A' }})@if(in_array($medic->id, $pairedMedics)) <span class="text-xs text-red-500">(paired)</span>@endif</span>
                </label>
              @endforeach
            </div>
            <button type="button" onclick="selectAllMedics()" class="helper-link mt-1">Select All Medics</button>
          </div>

          <div id="ambulance_selection" style="display: none;">
            <label>Select Ambulances *</label>
            <div class="chip-list">
              @foreach($ambulances as $ambulance)
                <label class="{{ in_array($ambulance->id, $pairedAmbulances) ? 'opacity-50' : '' }}">
                  <input type="checkbox" name="ambulances[]" value="{{ $ambulance->id }}" class="ambulance-checkbox {{ in_array($ambulance->id, $pairedAmbulances) ? 'disabled' : '' }}" {{ in_array($ambulance->id, $pairedAmbulances) ? 'disabled' : '' }}>
                  <span class="{{ in_array($ambulance->id, $pairedAmbulances) ? 'text-gray-500' : '' }}">{{ $ambulance->name }}@if(in_array($ambulance->id, $pairedAmbulances)) <span class="text-xs text-red-500">(paired)</span>@endif</span>
                </label>
              @endforeach
            </div>
            <button type="button" onclick="selectAllAmbulances()" class="helper-link mt-1">Select All Ambulances</button>
          </div>
        </div>

        <div>
          <label>Notes</label>
          <textarea name="notes" rows="2" class="w-full" placeholder="Any additional notes about these pairings..."></textarea>
        </div>

        <div class="actions-bottom">
          <button type="submit" class="btn-primary">
            <i class="fas fa-check mr-2"></i>Create
          </button>
          <a href="{{ route('admin.pairing.index') }}" class="btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </section>
</main>

<script>
  function toggleSidebar() {
    const sidenav = document.getElementById('sidenav');
    if (!sidenav) return;
    sidenav.classList.toggle('active');
  }

  function togglePairingType() {
    const pairingType = document.getElementById('pairing_type').value;
    const timeFields = document.getElementById('time_fields');
    const medicSelection = document.getElementById('medic_selection');
    const ambulanceSelection = document.getElementById('ambulance_selection');
    
    if (pairingType === 'driver_medic') {
      timeFields.style.display = 'block';
      medicSelection.style.display = 'block';
      ambulanceSelection.style.display = 'none';
    } else if (pairingType === 'driver_ambulance') {
      timeFields.style.display = 'none';
      medicSelection.style.display = 'none';
      ambulanceSelection.style.display = 'block';
    } else {
      timeFields.style.display = 'none';
      medicSelection.style.display = 'none';
      ambulanceSelection.style.display = 'none';
    }
    
    updatePreview();
  }

  function selectAllDrivers() {
    const checkboxes = document.querySelectorAll('.driver-checkbox:not(:disabled)');
    // Limit to maximum 2 drivers
    const maxDrivers = Math.min(2, checkboxes.length);
    checkboxes.forEach((checkbox, index) => {
      checkbox.checked = index < maxDrivers;
    });
    updatePreview();
  }

  function selectAllMedics() {
    const checkboxes = document.querySelectorAll('.medic-checkbox:not(:disabled)');
    checkboxes.forEach(checkbox => checkbox.checked = true);
    updatePreview();
  }

  function selectAllAmbulances() {
    const checkboxes = document.querySelectorAll('.ambulance-checkbox:not(:disabled)');
    checkboxes.forEach(checkbox => checkbox.checked = true);
    updatePreview();
  }

  function refreshBulkPairingOptions() {
    const selectedDate = document.getElementById('pairing_date').value;
    if (selectedDate) {
      // Redirect to the same page with the new date parameter
      window.location.href = '{{ route("admin.pairing.bulk") }}?pairing_date=' + selectedDate;
    }
  }

  function updatePreview() {
    const pairingType = document.getElementById('pairing_type').value;
    const selectedDrivers = document.querySelectorAll('.driver-checkbox:checked:not(:disabled)').length;
    const selectedMedics = document.querySelectorAll('.medic-checkbox:checked:not(:disabled)').length;
    const selectedAmbulances = document.querySelectorAll('.ambulance-checkbox:checked:not(:disabled)').length;
    
    let previewText = '';
    
    if (pairingType === 'driver_medic') {
      const totalPairings = selectedDrivers * selectedMedics;
      previewText = `Will create ${totalPairings} driver-medic pairings (${selectedDrivers} available drivers × ${selectedMedics} available medics)`;
    } else if (pairingType === 'driver_ambulance') {
      const totalPairings = selectedDrivers * selectedAmbulances;
      previewText = `Will create ${totalPairings} driver-ambulance pairings (${selectedDrivers} available drivers × ${selectedAmbulances} available ambulances)`;
    } else {
      previewText = 'Select pairing type and participants to see preview';
    }
    
    document.getElementById('preview_text').textContent = previewText;
  }

  // Add event listeners for checkboxes
  document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        if (this.name === 'drivers[]') {
          limitDriverSelection();
        }
        updatePreview();
      });
    });
  });

  function limitDriverSelection() {
    const driverCheckboxes = document.querySelectorAll('input[name="drivers[]"]:not(:disabled)');
    const checkedDrivers = document.querySelectorAll('input[name="drivers[]"]:checked:not(:disabled)');
    
    if (checkedDrivers.length > 2) {
      // Uncheck the last checked driver
      checkedDrivers[checkedDrivers.length - 1].checked = false;
      alert('Maximum 2 drivers can be selected for bulk pairing.');
    }
  }
</script>
</body>
</html>
