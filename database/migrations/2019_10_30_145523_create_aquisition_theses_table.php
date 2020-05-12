<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAquisitionThesesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aquisition_theses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('thesis_book_id');
            $table->integer('quantity');
            $table->integer('aquisition_type');
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
        Schema::dropIfExists('aquisition_theses');
    }
}
