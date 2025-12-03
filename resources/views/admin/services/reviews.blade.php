@extends('layouts.admin')

@section('title', 'Service Reviews')

@section('content')
@include('public.partials.navbar')

<div class="container p-6">
    <h2 class="text-2xl font-bold mb-4">⭐ Service Reviews</h2>

    @if($reviews->isEmpty())
        <p class="text-gray-600">No reviews submitted yet.</p>
    @else
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="bg-white rounded shadow p-4 border border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold text-lg">{{ $review->name }}</h3>
                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="flex items-center gap-1 mb-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">&#9733;</span>
                        @endfor
                    </div>

                    <p class="text-sm text-gray-700 mb-1"><strong>Service:</strong> {{ $review->service->title }}</p>
                    <p class="text-gray-800">{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
