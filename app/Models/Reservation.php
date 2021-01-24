<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    /**
     *
     */
    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class);
    }

    /**
     *
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
