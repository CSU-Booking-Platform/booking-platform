<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributesColumnsToRooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('capacity_standing')->nullable();
            $table->integer('capacity_sitting')->nullable();
            $table->boolean('food')->nullable();
            $table->boolean('alcohol')->nullable();
            $table->boolean('a_v_permitted')->nullable();
            $table->boolean('projector')->nullable();
            $table->boolean('television')->nullable();
            $table->boolean('computer')->nullable();
            $table->boolean('whiteboard')->nullable();
            $table->integer('sofas')->nullable();
            $table->integer('coffee_tables')->nullable();
            $table->integer('tables')->nullable();
            $table->integer('chairs')->nullable();
            $table->boolean('ambiant_music')->nullable();
            $table->boolean('sale_for_profit')->nullable();
            $table->boolean('fundraiser')->nullable();		
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(
                'capacity_standing', 
                'capacity_sitting',
                'food',
                'alcohol',
                'a_v_permitted',
                'projector',
                'television',
                'computer',
                'whiteboard',
                'sofas',
                'coffee_tables',
                'tables',
                'chairs',
                'ambiant_music',
                'sale_for_profit',
                'fundraiser'
            );
        });
    }
}
