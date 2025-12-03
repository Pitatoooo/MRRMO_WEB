<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-green-700">🧾 Receipt</h2>
    </x-slot>

    <div class="mt-6 bg-white p-6 rounded shadow-md">
        <h3 class="text-lg font-bold mb-4">Billing Receipt</h3>

        <p><strong>Name:</strong> {{ $billing->name }}</p>
        <p><strong>Address:</strong> {{ $billing->address }}</p>
        <p><strong>Service Type:</strong> {{ $billing->service_type }}</p>
        <p><strong>Date:</strong> {{ $billing->created_at->format('F j, Y g:i A') }}</p>
    </div>
        <!-- Print Button -->
    <div class="mt-4 text-center">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            🖨️ Print Receipt
        </button>
    </div>

</x-app-layout>
