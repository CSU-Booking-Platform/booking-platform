<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Blackout extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time'
    ];

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
     * Get the room that owns the blackout.
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }
}
