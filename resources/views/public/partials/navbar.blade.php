<nav class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center">
    <div class="text-lg font-bold">
        MDRRMO Silang
    </div>
    <ul class="flex space-x-4">
        <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
        <li><a href="{{ url('/contact') }}" class="hover:underline">Contact</a></li>
        <li><a href="{{ url('/services') }}" class="hover:underline">Services</a></li>
        <li>    <a href="{{ url('/admin/posting') }}" class="hover:underline">Posting</a>           </li>
        <li>    <a href="{{ url('/admin/gps') }}" class="hover:underline">GPS Tracker</a>           </li>
        <li>    <a href="{{ url('/admin/ambulances') }}" class="hover:underline">Ambulances</a></li>
        <li>    <a href="{{ url('/admin/drivers') }}" class="hover:underline">Drivers</a>           </li>
    </ul>
</nav>
