<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends BaseRole
{
    use HasFactory;

    /**
     * The rooms this role is restricted from.
     */
    public function restrictions()
    {
        return $this->belongsToMany(Room::class, 'room_restrictions');
    }
}
