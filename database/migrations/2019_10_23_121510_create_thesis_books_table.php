<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThesisBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thesis_books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('acc_no');
            $table->integer('call_no');
            $table->string('title');
            $table->integer('month');
            $table->integer('thesis_type_id');
            $table->integer('thesis_category_id');
            $table->integer('thesis_subject_id');
            $table->integer('program_id');
            $table->integer('copyright');
            $table->integer('availability')->default(1);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('thesis_books');
    }
}
