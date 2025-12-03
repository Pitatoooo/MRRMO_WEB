@component('mail::message')
# Booking Status Update

Hello {{ $booking->name }},

Your booking request for the service **{{ $booking->service->title }}** on **{{ $booking->preferred_date }}** at **{{ \Carbon\Carbon::parse($booking->preferred_time)->format('g:i A') }}** has been

@if($booking->status === 'approved')
**approved!** 🎉
@elseif($booking->status === 'rejected')
**rejected.** 😞
@else
updated.
@endif

@if($booking->status === 'approved')
Thank you for choosing our services. We look forward to assisting you!
@else
If you have questions, please contact us for more info.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
