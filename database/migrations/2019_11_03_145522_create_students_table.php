<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('lib_card_no');
            $table->string('stud_id_no');
            $table->string('f_name');
            $table->string('m_name');
            $table->string('l_name');
            $table->integer('gender');
            $table->string('address');
            $table->string('email_add')->unique();
            $table->string('contact_no');
            $table->string('pic_url');
            $table->integer('program_id');
            $table->integer('grade_year');
            $table->integer('section_id');
            $table->integer('school_year');
            $table->integer('sem');
            $table->integer('status');
            $table->rememberToken();
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
        Schema::dropIfExists('students');
    }
}
