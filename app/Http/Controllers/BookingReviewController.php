<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingCollection;
use App\Http\Resources\BookingResource;
use App\Models\BookingRequest;
use Illuminate\Http\Request;

class BookingReviewController extends Controller
{
    public function index() {
        $bookings = BookingRequest::with('requester', 'reservations', 'reservations.room')->pending()->get();

        return inertia('Approval/Index', [
            'bookings' => new BookingCollection($bookings),
        ]);
    }

    public function show(BookingRequest $booking)
    {
        $booking->loadMissing('requester', 'reservations', 'reservations.room');

        return inertia('Approval/ReviewBooking', [
            'booking' => new BookingResource($booking)
        ]);
    }

    public function review(Request $request, BookingRequest $booking) {
        $request->validateWithBag('reviewBooking', [
            'status' => ['required', 'string', 'max:50', 'in:approved,refused,needs-info'],
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        $booking->status = $request->status;
        $booking->save();

        // if action needs-info, set status needs-info
        // if has comment(s), add comment(s) to booking history...

        return redirect(route('bookings.reviews.index'))
            ->with('flash', ['banner' => "This request was successfully $request->status."]);
    }

}
