<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('Demo/Tables/RoomTableIndex', [
            'rooms' => Room::all(),
        ]);
    }
}
