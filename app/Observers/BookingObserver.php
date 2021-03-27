<?php
namespace App\Observers;

use App\Models\BookingRequest;
use App\Notifications\BookingUpdateNotification;
use Illuminate\Support\Facades\Auth;

class BookingObserver
{
    public function saved(BookingRequest $model)
    {
        if($model->isDirty("status") && Auth::check())
        {
            Auth::user()->notify(new BookingUpdateNotification($model));
        }
    }


}
