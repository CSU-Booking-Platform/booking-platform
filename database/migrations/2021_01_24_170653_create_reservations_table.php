<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained();
            $table->foreignId('booking_request_id')->constrained();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->timestamps();
        });

        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('room_id');
        });

        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn('start_time');
        });

        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn('end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');

        Schema::table('booking_requests', function (Blueprint $table) {
            $table->foreignId('room_id')->constrained();
            $table->datetime('start_time');
            $table->datetime('end_time');
        });
    }
}
