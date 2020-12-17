<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    /**
     * Get the room that owns the booking request.
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room');
    }

    /**
     * Get the user that created the booking request.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
