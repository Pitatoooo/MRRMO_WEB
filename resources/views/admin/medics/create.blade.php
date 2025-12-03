  <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Medic</title>
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
  <section class="containers-grid" style="max-width:800px;">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-2xl font-bold text-green-700 flex items-center gap-2">
        <i class="fas fa-user-md text-blue-500"></i>
        Add New Medic
      </h3>
      <a href="{{ route('admin.medics.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <i class="fas fa-arrow-left"></i>
        Back to Medics
      </a>
    </div>

    <div class="border" style="background:#fff; padding:1.5rem; border-radius:12px;">
      <form action="{{ route('admin.medics.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('phone')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">License Number</label>
            <input type="text" name="license_number" id="license_number" value="{{ old('license_number') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('license_number')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
            <select name="specialization" id="specialization"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Select Specialization</option>
              <option value="Emergency Medicine" {{ old('specialization') == 'Emergency Medicine' ? 'selected' : '' }}>Emergency Medicine</option>
              <option value="Paramedic" {{ old('specialization') == 'Paramedic' ? 'selected' : '' }}>Paramedic</option>
              <option value="Nurse" {{ old('specialization') == 'Nurse' ? 'selected' : '' }}>Nurse</option>
              <option value="EMT" {{ old('specialization') == 'EMT' ? 'selected' : '' }}>EMT</option>
              <option value="Other" {{ old('specialization') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('specialization')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
            <select name="status" id="status" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
          <a href="{{ route('admin.medics.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
            Cancel
          </a>
          <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            Create Medic
          </button>
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
</script>
</body>
</html>
