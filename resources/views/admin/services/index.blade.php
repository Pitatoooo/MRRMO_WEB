<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Services & Bookings | Admin</title>
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
        <a href="{{ url('/') }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <span class="nav-link-locked" style="display: block; padding: 12px 16px; color: #9ca3af; cursor: not-allowed; opacity: 0.6; position: relative;"><i class="fas fa-pen"></i> Posting <i class="fas fa-lock" style="font-size: 10px; margin-left: 8px; opacity: 0.7;"></i></span>
        <a href="{{ url('/admin/services') }}"><i class="fas fa-concierge-bell"></i> Services</a>
        <a href="{{ url('/admin/contacts') }}"><i class="fas fa-address-book"></i> Contacts</a>
        <a href="{{ url('/admin/drivers') }}"><i class="fas fa-car"></i> Drivers</a>
        <a href="{{ url('/admin/gps') }}" ><i class="fas fa-map-marker-alt mr-1"></i> GPS Tracker</a>
        <a href="{{ url('/admin/ambulances') }}"><i class="fas fa-ambulance mr-1"></i> Ambulances</a>
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
<div class="mdrrmo-header" style="border: 2px solid #031273;">
    <h2 class="header-title">SILANG MDRRMO</h2>
</div>
<!-- Main content -->
<main class="main-content pt-24">
    <div class="grid mb-10">
        <div class="border p-4 rounded">
            <h2 class="section-title"><i class="fas fa-plus-circle"></i> Add New Service</h2>
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded shadow mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded shadow mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded shadow mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-semibold">Title:</label>
                    <input type="text" name="title" class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Description:</label>
                    <textarea name="description" class="border p-2 w-full"></textarea>
                </div>
                <div>
                    <label class="block font-semibold">Category:</label>
                    <input type="text" name="category" class="border p-2 w-full" placeholder="e.g. First Aid, Training, etc.">
                </div>
                <div>
                    <label class="block font-semibold">Status:</label>
                    <input type="text" name="status" class="border p-2 w-full" placeholder="Available, Coming Soon, etc.">
                </div>
                <div>
                    <label class="block font-semibold">Image:</label>
                    <input type="file" name="image" class="border p-2 w-full">
                </div>
                <div class="text-center">
                    <button type="submit" class="text-white px-4 py-2 rounded" style="width: 170px; background-color: #4338ca;">
                        <i class="fas fa-save mr-2"></i> Save Service
                    </button>
                </div>
            </form>
        </div>
    </div>
    <hr class="my-divider" style="border: none; height: 4px; background: linear-gradient(to right, #031273, #4CC9F0); width: 80%; margin-top:175px; border-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.12); ">
    
    @if(isset($pendingBookings) || isset($approvedBookings) || isset($rejectedBookings))
    <div class="grid mb-10">
        <div class="border p-4 rounded">
            <h2 class="section-title"><i class="fas fa-calendar-alt"></i> Service Bookings</h2>
            {{-- ✅ Flash Message --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded shadow mb-4">
                    {{ session('success') }}
                </div>
            @endif
            {{-- 🔶 Pending --}}
            @if(isset($pendingBookings))
            <div class="mb-4">
                <h3 class="text-base font-semibold text-yellow-700 mb-2"><i class="fas fa-clock"></i> Pending Bookings</h3>
                @if($pendingBookings->isEmpty())
                    <p class="text-gray-500 italic text-sm">No pending bookings.</p>
                @else
                    <table class="w-full bg-white border rounded shadow text-xs overflow-hidden">
                        <thead class="bg-yellow-400 text-white">
                            <tr>
                                <th class="p-2 text-left">Name</th>
                                <th class="p-2 text-left">Contact</th>
                                <th class="p-2 text-left">Service</th>
                                <th class="p-2 text-left">Date</th>
                                <th class="p-2 text-left">Time</th>
                                <th class="p-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingBookings as $booking)
                                <tr class="border-t">
                                    <td class="p-2">{{ $booking->name }}</td>
                                    <td class="p-2 text-xs">{{ Str::limit($booking->contact_info, 20) }}</td>
                                    <td class="p-2">{{ Str::limit($booking->service->title, 15) }}</td>
                                    <td class="p-2">{{ $booking->preferred_date }}</td>
                                    <td class="p-2">{{ \Carbon\Carbon::parse($booking->preferred_time)->format('g:i A') }}</td>
                                    <td class="p-2 flex gap-1">
                                        <form method="POST" action="{{ route('admin.bookings.approve', $booking->id) }}">
                                            @csrf
                                            <button type="submit" class="flex items-center justify-center text-white rounded shadow text-xs p-0 m-0" style="width: 36px; height: 36px; background-color: #059669;">
                                                <i class="fas fa-check" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; font-size: 1.2rem; line-height: 1; margin: 0; padding: 0;"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.bookings.reject', $booking->id) }}">
                                            @csrf
                                            <button type="submit" class="flex items-center justify-center text-white rounded shadow text-xs p-0 m-0" style="width: 36px; height: 36px; background-color: #dc2626;">
                                                <i class="fas fa-times" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; font-size: 1.2rem; line-height: 1; margin: 0; padding: 0;"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <hr class="my-divider" style="border: none; height: 4px; background: linear-gradient(to right, #031273, #4CC9F0); width: 100%; margin: 2rem auto; border-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.12);">
            @endif
            {{-- ✅ Approved --}}
            @if(isset($approvedBookings))
            <div class="mb-4">
                <h3 class="text-base font-semibold text-green-700 mb-2"><i class="fas fa-check-circle"></i> Approved Bookings</h3>
                @if($approvedBookings->isEmpty())
                    <p class="text-gray-500 italic text-sm">No approved bookings.</p>
                @else
                    <table class="w-full bg-white border rounded shadow text-xs overflow-hidden">
                        <thead class="bg-green-500 text-white">
                            <tr>
                                <th class="p-2 text-left">Name</th>
                                <th class="p-2 text-left">Contact</th>
                                <th class="p-2 text-left">Service</th>
                                <th class="p-2 text-left">Date</th>
                                <th class="p-2 text-left">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedBookings as $booking)
                                <tr class="border-t">
                                    <td class="p-2">{{ $booking->name }}</td>
                                    <td class="p-2 text-xs">{{ Str::limit($booking->contact_info, 20) }}</td>
                                    <td class="p-2">{{ Str::limit($booking->service->title, 15) }}</td>
                                    <td class="p-2">{{ $booking->preferred_date }}</td>
                                    <td class="p-2">{{ \Carbon\Carbon::parse($booking->preferred_time)->format('g:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <hr class="my-divider" style="border: none; height: 4px; background: linear-gradient(to right, #031273, #4CC9F0); width: 100%; margin: 2rem auto; border-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.12);">
            @endif
            {{-- ❌ Rejected --}}
            @if(isset($rejectedBookings))
            <div class="mb-4">
                <h3 class="text-base font-semibold text-red-700 mb-2"><i class="fas fa-times-circle"></i> Rejected Bookings</h3>
                @if($rejectedBookings->isEmpty())
                    <p class="text-gray-500 italic text-sm">No rejected bookings.</p>
                @else
                    <table class="w-full bg-white border rounded shadow text-xs overflow-hidden">
                        <thead class="bg-red-500 text-white">
                            <tr>
                                <th class="p-2 text-left">Name</th>
                                <th class="p-2 text-left">Contact</th>
                                <th class="p-2 text-left">Service</th>
                                <th class="p-2 text-left">Date</th>
                                <th class="p-2 text-left">Time</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach($rejectedBookings as $booking)
                                <tr class="border-t">
                                    <td class="p-2">{{ $booking->name }}</td>
                                    <td class="p-2 text-xs">{{ Str::limit($booking->contact_info, 20) }}</td>
                                    <td class="p-2">{{ Str::limit($booking->service->title, 15) }}</td>
                                    <td class="p-2">{{ $booking->preferred_date }}</td>
                                    <td class="p-2">{{ \Carbon\Carbon::parse($booking->preferred_time)->format('g:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <hr class="my-divider" style="border: none; height: 4px; background: linear-gradient(to right, #031273, #4CC9F0); width: 100%; margin: 2rem auto; border-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.12);">
            @endif
        </div>
        
    </div>
    @endif
</main>
<hr class="my-divider" style="border: none; height: 4px; background: linear-gradient(to right, #031273, #4CC9F0); width: 100%; margin: 2rem auto; border-radius: 2px; box-shadow: 0 2px 5px rgba(0,0,0,0.12);">
<script>
    function toggleSidebar() {
        const sidenav = document.querySelector('.sidenav');
        sidenav.classList.toggle('active');
    }
    
    // Auto-refresh after adding service if success message is shown
    @if(session('success'))
        setTimeout(function() {
            location.reload();
        }, 1500); // Refresh after 1.5 seconds
    @endif
</script>
</body>
</html>