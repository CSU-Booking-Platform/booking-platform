<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

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
        'status',
        'room_type',
        'min_days_advance',
        'max_days_advance',
        'attributes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attributes' => 'array',
    ];

    /**
     * The roles restricted from this room.
     */
    public function restrictions(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'room_restrictions');
    }

    /**
     * The roles restricted from this room.
     */
    public function dateRestrictions(): BelongsToMany
    {
      return $this->belongsToMany(Role::class, 'custom_date_restrictions')
        ->withPivot('min_days_advance', 'max_days_advance')->withTimestamps();
    }

    /**
     * Get the rooms that are part of the booking request.
     */
    public function bookingRequests(): BelongsToMany
    {
        return $this->belongsToMany(BookingRequest::class,
            'reservations',
            'room_id',
            'booking_request_id');
    }

    /**
     * The reservations associated with this room
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the blackouts on the room
    */

    public function blackouts(): BelongsToMany
    {
        return $this->belongsToMany(Blackout::class);
    }

    /**
     * Get the availabilities for the room
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Check the availability of the room.
     */
    public function scopeAvailable(Builder $q)
    {
        $q->where('status', 'available');
    }

    /**
     * Hide rooms restricted by the user's roles
     */
    public function scopeHideUserRestrictions(Builder $q, User $u)
    {
        $q->whereNotIn('id', $u->roles()->with('restrictions')->get()
            ->pluck('restrictions')->flatten()->pluck('id')->unique()->toArray());
    }

    /**
     * Check if start date and end date is within room availabilities
     * @param $startDate is a date time string
     * @param $endDate is a date time string
     * @return void
     * @throws ValidationException
     */
    public function verifyDatetimesAreWithinAvailabilities($startDate, $endDate)
    {
        if ($this->notWithinAvailabilities($startDate, $endDate)) {
            throw ValidationException::withMessages([
                'availabilities' => 'Booking request not within availabilities!'
            ]);
        }
    }
    public function verifyDatesAreWithinRoomRestrictions($startDate)
    {
        $startTime = Carbon::parse($startDate)->toDateString();
        $min_days = $this->min_days_advance;
        $max_days = $this->max_days_advance;

        if(Carbon::today()-> diffInDays($startTime) < $min_days) {
            throw ValidationException::withMessages(['booked_too_close' => 'You can not book events closer than ' .$min_days.' days to the event']);
        } elseif(Carbon::today()-> diffInDays($startTime) > $max_days) {
            throw ValidationException::withMessages(['booked_too_far' => 'You cannot book events farther than '.$max_days.' days from the event']);
        }
    }
    public function verifyDatesAreWithinRoomRestrictionsValidation($startDate, $fail, User $user)
    {

        $startTime = Carbon::parse($startDate)->toDateString();

        $roles = $user->roles()->select('id')->get()->pluck('id');
        $specialRules = $this->dateRestrictions()->whereIn('role_id', $roles)->get(['min_days_advance', 'max_days_advance']);
        $special_min = $specialRules->pluck('min_days_advance')->max();
        $special_max = $specialRules->pluck('max_days_advance')->min();
        $min_days = $special_min ?? $this->min_days_advance;
        $max_days = $special_max ?? $this->max_days_advance;

        if (Carbon::today()->diffInDays($startTime) < $min_days) {
            $fail('You can not book this room later than ' . $min_days . ' days in advance');
        } elseif (Carbon::today()->diffInDays($startTime) > $max_days) {
            $fail('You can not book this room sooner than ' . $max_days . ' days in advance');
        }
    }

    public function verifyDatetimesAreWithinAvailabilitiesValidation($startDate, $endDate, $fail)
  {
    if ($this->notWithinAvailabilities($startDate, $endDate)) {
        $fail('These dates and times are not within the room\'s availabilities!');
    }
  }

  private function verifyRoomQuery($startDate, $endDate, $reservation)
  {
    $query = Reservation::where('room_id', $this->id);
    if ($reservation) {
      $query = $query->where('id', '!=', $reservation->id);
    }
    return $query->where(function ($query) use ($startDate, $endDate) {
      $query->whereBetween('start_time', [$startDate, $endDate])
        ->orWhereBetween('end_time', [$startDate, $endDate])
        ->orWhere(function ($query) use ($startDate, $endDate) {
          $query->where('start_time', '<', $startDate)->where('end_time', '>', $endDate);
        });
    });
  }

  public function verifyRoomIsFreeValidation($startDate, $endDate, $fail, $reservation = null)
  {
    $conflict = $this->verifyRoomQuery($startDate, $endDate, $reservation)->first();

    if ($conflict) {
      $fail('The room cannot be booked at this time - Conflicting schedule');
    }
  }

  //TODO:: check - might be a dead function
  public function verifyRoomIsFree($startDate, $endDate, $reservation = null)
  {
    if ($this->verifyRoomQuery($startDate, $endDate, $reservation)->count() > 0) {
      throw ValidationException::withMessages(['time_conflict' => "This is blocked by another reservation."]);
    }
  }

  private function notWithinAvailabilities($startDate, $endDate)
  {
    $startTime = Carbon::parse($startDate)->toTimeString();
    $endTime = Carbon::parse($endDate)->toTimeString();

    $availabilityStart =
      Availability::where('weekday', Carbon::parse($startDate)->format('l'))
        ->where('room_id', '=', $this->id)->first();

    $availabilityEnd =
      Availability::where('weekday', Carbon::parse($endDate)->format('l'))
        ->where('room_id', '=', $this->id)->first();

    return
      empty($availabilityStart) ||
      empty($availabilityEnd) ||
      $startTime < $availabilityStart->opening_hours ||
      $endTime > $availabilityEnd->closing_hours;
  }

 

  public function minimunReservationTime($startDate, $endDate, $fail)
  {
    $startTime = Carbon::parse($startDate);
    $endTime = Carbon::parse($endDate);
    if($endTime->diffInMinutes($startTime) < 30){
       $fail('The room cannot be booked for less than 30 min');
    }
  }


}
