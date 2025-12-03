<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- ✅ Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/stylish.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Vite for Tailwind/JS if needed -->

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
    </nav>
</aside>

    <!-- Fixed Top Header -->
   <!-- ✅ Fixed Top Header -->
<div class="mdrrmo-header" style="border: 2px solid #031273;">
    <h2 class="header-title">SILANG MDRRMO</h2>

</div>

    <!-- Main content (with padding top to not hide under fixed header) -->
    <main class="main-content pt-24">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Add New Ambulance --}}
        <div class="grid gap-2 p-2 rounded shadow mb-8" style="max-width: 500px; width: 100%; margin: 0 auto; margin-top: 50px;">
            <div class="border p-0 rounded">
                <h3 class="section-title"><i class="fas fa-ambulance"></i> Add New Ambulance</h3>
                <form action="{{ route('admin.ambulances.store') }}" method="POST">
                    @csrf
                    <div class="mb-1">
                        <label class="block">Name</label>
                        <input type="text" name="name" class="border p-1 w-full" required>
                    </div>
                    <div class="mb-1">
                        <label class="block">Status</label>
                        <select name="status" class="border p-1 w-full" required>
                            <option value="Available">Available</option>
                            <option value="Out">Out</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="text-white px-4 py-2 rounded" style="width: 180px; background-color: #4338ca;">
                            <i class="fas fa-plus mr-2"></i> Add Ambulance
                        </button>
                    </div>
                </form>
            </div>
        </div>
<hr class="my-divider" style="width:750px;"> 
        {{-- Existing Ambulances --}}
        <div class="grid gap-6 p-6 rounded shadow mb-8" style="margin-bottom:100px;">
            <div class="border p-4 rounded">
                <h3 class="section-title"><i class="fas fa-ambulance"></i> Existing Ambulances</h3>
                <div class="centered-table">
                    <table class="text-lg border bg-white shadow rounded" style="width:100%;">
                        <thead class="bg-gray-100 text-base uppercase text-gray-600 border-b">
                            <tr>
                                <th class="px-4 py-2" style="font-size: 32px;">Name</th>
                                <th class="px-4 py-2" style="font-size: 32px; ">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ambulances as $amb)
                                <tr class="border-t" style="text-align:center">
                                    
                                    <td class="px-4 py-2" style="font-size: 16px;">{{ $amb->name }}</td>
                                    <td class="px-4 py-2" style="font-size: 16px;">
                                        <span class="px-2 py-1 rounded text-white 
                                            @if($amb->status === 'Available') bg-green-500 
                                            @elseif($amb->status === 'Out') bg-yellow-500 
                                            @else bg-red-500 
                                            @endif">
                                            {{ $amb->status }}
                                        </span>
                                    </td>
                                
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-4" style="font-size: 16px;">No ambulances yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<hr class="my-divider"> 
    </main>
<script>
    function toggleSidebar() {
        const sidenav = document.querySelector('.sidenav');
        sidenav.classList.toggle('active');
    }
</script>

</body>
</html>
