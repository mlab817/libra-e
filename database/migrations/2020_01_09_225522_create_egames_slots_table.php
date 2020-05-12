<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEgamesSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('egames_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pc_no');
            $table->string('pc_name');
            $table->integer('pc_type');
            $table->text('notes')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('egames_slots');
    }
}
