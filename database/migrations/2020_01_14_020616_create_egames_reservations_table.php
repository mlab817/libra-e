<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEgamesReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('egames_reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('egames_slot_id');
            $table->dateTime('reserve_date');	
            $table->string('reserve_time_slot');	
            $table->dateTime('time_in')->nullable();	
            $table->dateTime('time_out')->nullable();	
            $table->text('notes')->nullable();
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('egames_reservations');
    }
}
