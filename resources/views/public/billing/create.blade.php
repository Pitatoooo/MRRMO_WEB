<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-blue-600">📝 Ambulance Billing Form</h2>
    </x-slot>

    <div class="mt-6 bg-white p-6 rounded shadow-md">
<form action="{{ url('/billing') }}" method="POST">
    @csrf

    <label for="name">Name:</label>
    <input type="text" name="name" required>

    <label for="address">Address:</label>
    <input type="text" name="address" required>

    <label for="service_type">Service Type:</label>
    <input type="text" name="service_type" required>

    <button type="submit">Submit</button>
</form>

    </div>
</x-app-layout>
