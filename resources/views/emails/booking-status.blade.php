<!DOCTYPE html>
<html>
<body>
    <h2>Hello {{ $booking->name }},</h2>

    <p>Your booking for <strong>{{ $booking->service->title }}</strong> has been <strong>{{ $status }}</strong>.</p>

    <p>📅 Date: {{ $booking->preferred_date }}<br>
       ⏰ Time: {{ \Carbon\Carbon::parse($booking->preferred_time)->format('g:i A') }}</p>

    <p>If you have any questions, feel free to reply to this email.</p>

    <br>
    <p>— MDRRMO Silang</p>
</body>
</html>
