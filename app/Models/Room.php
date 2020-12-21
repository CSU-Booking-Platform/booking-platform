<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'number',
        'floor',
        'building',
        'status'
    ];

     /**
     * Get the booking requests for the room.
     */
    public function bookingrequests()
    {
        return $this->hasMany('App\Models\BookingRequest');
    }
}
