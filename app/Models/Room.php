<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
    public function bookingRequests()
    {
        return $this->hasMany('App\Models\BookingRequest');
    }
    
     /**
     * Check the availability of the room.
     */
    public function scopeAvailable(Builder $q)
    {
        $q->where('status', 'available');
    }
}
