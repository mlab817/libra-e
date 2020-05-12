<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms_reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('purpose');
            $table->string('topic_description');
            $table->integer('user_id');
            $table->dateTime('reserve_date');	
            $table->string('reserve_time_start');	
            $table->string('reserve_time_end');	
            $table->integer('no_users');
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
        Schema::dropIfExists('rooms_reservations');
    }
}
