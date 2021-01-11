<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;


    protected $fillable = [
        'room_id',
        'user_id',
        'start_time',
        'end_time',
        'status',
        'reference',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'reference' => 'array'
    ];

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
