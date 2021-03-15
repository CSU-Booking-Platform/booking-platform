<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'status',
        'reference',
        'log',
        'event',
        'notes',
        'onsite_contact',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'reference' => 'array',
        'log' => 'array',
        'event' => 'array',
        'onsite_contact' => 'array',
    ];

    /**
     * Constant types of booking request statuses
     */
    public const REVIEW = 'review';
    public const APPROVED = 'approved';
    public const REFUSED = 'refused';
    public const PENDING = 'pending';

    public const STATUS_TYPES = [self::REVIEW, self::APPROVED, self::REFUSED, self::PENDING];


     /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'room',
    ];


    /**
     * Get the rooms that are part of the booking request.
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class,
            'reservations',
            'booking_request_id',
            'room_id')->withTimestamps();
    }


    /**
     * Get the rooms that are part of the booking request.
     */
    public function getRoomAttribute()
    {
        return $this->rooms()->first();
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
    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'booking_reviewers');
    }

    public function scopePending(Builder $query) {
        $query->where('status', 'review');
    }
}
