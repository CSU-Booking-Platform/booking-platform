<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BookingRequest;
use App\Models\User;

class CreateBookingReviewersTable extends Migration
{
    public function up()
    {
        Schema::create('booking_reviewers', function (Blueprint $table) {
            $table->foreignIdFor(BookingRequest::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_reviewers');
    }
}
