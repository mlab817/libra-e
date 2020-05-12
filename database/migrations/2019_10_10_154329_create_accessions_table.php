<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('author_id');
            $table->string('book_title');
            $table->integer('publisher_id');
            $table->bigInteger('isbn')->nullable();
            $table->string('pic_url')->nullable();
            $table->integer('classification_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('category_id_2')->nullable();
            $table->integer('illustration_id')->nullable();
            $table->integer('edition')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('pages')->nullable();
            $table->integer('cost_price')->nullable();
            $table->integer('copyright');
            $table->integer('filipiniana')->nullable();
            $table->integer('source_of_fund')->nullable();
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
        Schema::dropIfExists('accessions');
    }
}
