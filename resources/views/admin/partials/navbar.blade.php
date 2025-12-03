<nav class="bg-blue-700 text-white px-6 py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center">
        {{-- Logo / Title --}}
        <div class="text-xl font-bold">
            MDRRMO Admin Panel
        </div>

        {{-- Navigation Links --}}
        <ul class="flex flex-wrap gap-x-4 gap-y-2 text-sm font-medium mt-2 sm:mt-0">
            <li><a href="{{ url('/') }}" class="hover:underline">Dashboard</a></li>
            <li><a href="{{ url('/admin/posting') }}" class="hover:underline">Posting</a></li>
            <li><a href="{{ url('/admin/services') }}" class="hover:underline">Create Service</a></li>
            <li><a href="{{ url('/admin/services/reviews') }}" class="hover:underline">Reviews</a></li>
            <li><a href="{{ url('/admin/services/bookings') }}" class="hover:underline">Bookings</a></li>
            <li><a href="{{ url('/admin/gps') }}" class="hover:underline">GPS Tracker</a></li>
            <li><a href="{{ url('/admin/ambulances') }}" class="hover:underline">Ambulances</a></li>
            <li><a href="{{ url('/admin/drivers') }}" class="hover:underline">Drivers</a></li>
        </ul>
    </div>
</nav>
