<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\BookingRequest;
use App\Models\User;

use Exception;


Class DashboardController extends Controller{

    public function index()
    {

        return inertia('Dashboard', [
            'rooms' => Room::selectRaw("count(*) as room_count,room_type")->groupBy("room_type")->get()->pluck("room_count","room_type"),
            'bookings' => BookingRequest::selectRaw("count(*) as booking_count,status")->groupBy("status")->get()->pluck("booking_count","status"),
            'users'=>['Registered'=>User::count()]
        ]);
    }

}