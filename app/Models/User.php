<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'can',
    ];

    /**
     * Get all booking requests created by this user.
     */
    public function bookingRequests()
    {
        return $this->hasMany('App\Models\BookingRequest');
    }

    public function getCanAttribute()
    {
        return $this->getAllPermissions()->pluck('name');
    }

    public function getUserNumberOfBookingRequestPerPeriod()
    {
        $numberOfBookingRequestPerPeriod = null;

        foreach ($this->roles as $role) {
            if ($role->number_of_bookings_per_period > $numberOfBookingRequestPerPeriod) {
                $numberOfBookingRequestPerPeriod = $role->number_of_bookings_per_period;
            }
        }

        return $numberOfBookingRequestPerPeriod;
    }

    public function getUserNumberOfDaysPerPeriod()
    {
        $numberOfDaysPerPeriod = null;

        foreach ($this->roles as $role) {
            if ($role->number_of_days_per_period > $numberOfDaysPerPeriod) {
                $numberOfDaysPerPeriod = $role->number_of_days_per_period;
            }
        }

        return $numberOfDaysPerPeriod;
    }

  public function canMakeAnotherBookingRequest($startDate): bool
  {
    $numberOfBookingRequestPerPeriod = $this->getUserNumberOfBookingRequestPerPeriod();
    $numberOfDaysPerPeriod = $this->getUserNumberOfDaysPerPeriod();

    if (is_null($numberOfBookingRequestPerPeriod) && is_null($numberOfDaysPerPeriod)) {
      return true;
    }

    if (Carbon::parse($startDate)->diffInDays(Carbon::now()) > $numberOfDaysPerPeriod) {
      return true;
    }

    $nbOfBookingRequest = BookingRequest::query()
      ->whereHas('reservations', function (Builder $query) use ($numberOfDaysPerPeriod) {
        $query->where('start_time', '>', Carbon::now());
        $query->where('start_time', '<', Carbon::now()->addDays($numberOfDaysPerPeriod));
      })
      ->where('user_id', '=', $this->id)
      ->where('status', 'not like', '%declined%')
      ->count();

    return $nbOfBookingRequest < $numberOfBookingRequestPerPeriod;
  }
}
