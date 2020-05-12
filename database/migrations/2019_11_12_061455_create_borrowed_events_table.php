<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowedEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowed_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('borrowed_book_id');
            $table->dateTime('date_borrowed');	
            $table->dateTime('due_date');	
            $table->text('notes')->nullable();
            $table->integer('status');
            $table->timestamps();
        });
    }

    /*
        * borrowed_events (Table)
        - ID (Primary Key)
        - borrowed_book_id
        - date_borrowed (nullable) if not yet borrowed
        - due_date (nullable) if not yet approved : if approved due_date to be borrowed
        - status
            - 1 = pending request to be borrowed (To be Approved)
            - 2 = approved to be borrowed (Approved: Reserved) 2 days 
            - 3 = borrowed
            - 4 = returned
            - 5 = damage/lost
        - timestamp
    */
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrowed_events');
    }
}
