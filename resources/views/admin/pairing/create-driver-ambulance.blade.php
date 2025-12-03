<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Driver-Ambulance Pairing</title>
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
      <a href="{{ route('admin.pairing.index') }}" class="pairing-nav-item">Pairing</a>
      <a href="{{ route('admin.pairing.log') }}" class="pairing-nav-item">View Log</a>
      <a href="{{ route('admin.pairing.bulk') }}" class="pairing-nav-item">Bulk Pairing</a>
      <a href="{{ route('admin.pairing.driver-medic.create') }}" class="pairing-nav-item">Pair Driver-Medic</a>
      <a href="{{ route('admin.pairing.driver-ambulance.create') }}" class="pairing-nav-item" style="background: var(--brand-orange); color: var(--brand-navy);">Pair Driver-Ambulance</a>
    </div>
  </nav>

  <style>
    /* Compact ambulance layout derived from Bulk */
    #ambCompact { --navy:#0c2d5a; --orange:#f28c28; --surface:#ffffff; --muted:#f6f7fb; --border:#e5e7eb; --text:#1f2937; --accent:#3b82f6; }
    #ambCompact.containers-grid { height: 560px; padding: 0.75rem !important; }
    #ambCompact .header-card { background: linear-gradient(135deg, var(--navy) 0%, #1e3a8a 65%); color:#fff; border-radius:12px; padding:.8rem 1rem; display:flex; align-items:center; gap:.75rem; box-shadow:0 8px 20px rgba(3,18,115,.25); }
    #ambCompact .title-icon { width:42px; height:42px; border-radius:10px; background:linear-gradient(135deg, var(--orange), #ff8c42); color:#0b0b0b; display:flex; align-items:center; justify-content:center; box-shadow:0 6px 16px rgba(242,140,40,.35); }
    #ambCompact .title-text h3 { margin:0; font-size:1.1rem; }
    #ambCompact .title-text p { margin:.15rem 0 0 0; font-size:.8rem; opacity:.9; }

    #ambCompact .card { background:#fff; border:1px solid var(--border); border-radius:14px; box-shadow:0 8px 22px rgba(3,18,115,.12); height: calc(560px - 84px); padding:.9rem; display:flex; flex-direction:column; position:relative; }
    #ambCompact .accent-bar { position:absolute; left:0; top:0; height:6px; width:100%; background: linear-gradient(90deg, var(--orange), #4CC9F0, var(--accent)); border-top-left-radius:14px; border-top-right-radius:14px; }

    #ambCompact .form-body { display:grid; grid-template-rows:auto auto 1fr auto; gap:.6rem; flex:1 1 auto; overflow:hidden; }
    #ambCompact .row-duo { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:.6rem; }
    #ambCompact label { font-size:.85rem; font-weight:800; color:var(--text); margin-bottom:.25rem; }
    #ambCompact select, #ambCompact input, #ambCompact textarea { padding:.55rem .65rem !important; border-radius:10px !important; border:1.6px solid var(--border) !important; font-size:.93rem !important; background:#fff; }
    #ambCompact select:focus, #ambCompact input:focus, #ambCompact textarea:focus { outline:none; box-shadow:0 0 0 3px rgba(59,130,246,.18); border-color:var(--accent) !important; }

    #ambCompact .actions-bottom { margin-top:auto; padding-top:.6rem; display:flex; justify-content:flex-end; gap:.5rem; }
    #ambCompact .btn-primary { background:linear-gradient(135deg, var(--accent) 0%, #1d4ed8 100%); color:#fff; border:none; border-radius:10px; padding:.6rem 1.1rem; font-weight:800; }
    #ambCompact .btn-secondary { background:#6b7280; color:#fff; border:none; border-radius:10px; padding:.6rem 1rem; font-weight:800; }
  </style>

  <section id="ambCompact" class="containers-grid" style="max-width:800px;">
    <div class="header-card">
      <div class="title-icon"><i class="fas fa-ambulance"></i></div>
      <div class="title-text">
        <h3>Create Driver‑Ambulance Pairing</h3>
        <p>Select a driver and an ambulance</p>
      </div>
    </div>

    <div class="card">
      <div class="accent-bar"></div>
      <div class="bg-white p-6 rounded-lg shadow form-body" style="background:#fff; padding:0; box-shadow:none;">
        <form action="{{ route('admin.pairing.driver-ambulance.store') }}" method="POST" class="flex flex-col gap-2">
          @csrf
          <div class="row-duo">
            <div>
              <label class="block text-sm font-medium mb-2">Driver *</label>
              <select name="driver_id" id="driver_id" required class="w-full">
                <option value="">Select Driver</option>
                @foreach($drivers as $driver)
                  @php
                    $isBlocked = isset($blockedDrivers) ? in_array($driver->id, $blockedDrivers) : in_array($driver->id, $pairedDrivers);
                  @endphp
                  @if($isBlocked)
                    <option value="{{ $driver->id }}" disabled class="text-gray-400 bg-gray-100">{{ $driver->name }} (Already paired on {{ $selectedDate }})</option>
                  @else
                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                  @endif
                @endforeach
              </select>
              @error('driver_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Ambulance *</label>
              <select name="ambulance_id" id="ambulance_id" required class="w-full">
                <option value="">Select Ambulance</option>
                @foreach($ambulances as $ambulance)
                  @if(in_array($ambulance->id, $pairedAmbulances))
                    <option value="{{ $ambulance->id }}" disabled class="text-gray-400 bg-gray-100">{{ $ambulance->name }} - Already paired on {{ $selectedDate }}</option>
                  @else
                    <option value="{{ $ambulance->id }}" {{ old('ambulance_id') == $ambulance->id ? 'selected' : '' }}>{{ $ambulance->name }}</option>
                  @endif
                @endforeach
              </select>
              @error('ambulance_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Pairing Date *</label>
            <input type="date" name="pairing_date" id="pairing_date" value="{{ old('pairing_date', $selectedDate) }}" required class="w-full" onchange="refreshPairingOptions()">
            @error('pairing_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="2" class="w-full" placeholder="Any additional notes about this pairing..."></textarea>
            @error('notes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="actions-bottom">
            <a href="{{ route('admin.pairing.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Pairing</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>

<script>
  function toggleSidebar() {
    const sidenav = document.getElementById('sidenav');
    if (!sidenav) return;
    sidenav.classList.toggle('active');
  }

  function refreshPairingOptions() {
    const selectedDate = document.getElementById('pairing_date').value;
    if (selectedDate) {
      // Redirect to the same page with the new date parameter
      window.location.href = '{{ route("admin.pairing.driver-ambulance.create") }}?pairing_date=' + selectedDate;
    }
  }
</script>
</body>
</html>
