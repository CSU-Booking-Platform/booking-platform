<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'status',
        'reference',
        'log',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'reference' => 'array',
        'log' => 'array',
    ];

    /**
     * Get the rooms that are part of the booking request.
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class,
            'reservations',
            'booking_request_id',
            'room_id');
    }

    /**
     * Get the rooms that are part of the booking request.
     */
    public function reservations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the user that created the booking request.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
