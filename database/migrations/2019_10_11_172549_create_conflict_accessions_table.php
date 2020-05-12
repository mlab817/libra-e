<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConflictAccessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conflict_accessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('accession_no');
            $table->string('author');
            $table->string('title');
            $table->string('publisher');
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
        Schema::dropIfExists('conflict_accessions');
    }
}
