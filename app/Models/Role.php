<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends BaseRole
{
    use HasFactory;

    /**
     * The rooms this role is restricted from.
     */
    public function restrictions(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_restrictions');
    }
}
