<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Mail\BookingStatusMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        return view('admin.services.bookings', [
            'pendingBookings' => Booking::where('status', 'pending')->get(),
            'approvedBookings' => Booking::where('status', 'approved')->get(),
            'rejectedBookings' => Booking::where('status', 'rejected')->get(),
        ]);
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'approved';
        $booking->save();

        // Send approval email
        Mail::to($booking->contact_info)->send(new BookingStatusMail($booking));

        return redirect()->route('admin.bookings.index')->with('success', 'Booking approved and email sent!');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';
        $booking->save();

        // Send rejection email
        Mail::to($booking->contact_info)->send(new BookingStatusMail($booking));

        return redirect()->route('admin.bookings.index')->with('success', 'Booking rejected and email sent!');
    }
}
