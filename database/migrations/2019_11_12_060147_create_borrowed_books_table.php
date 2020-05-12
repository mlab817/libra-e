<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowedBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowed_books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_no');
            $table->integer('user_id');
            $table->integer('accession_no_id');
            $table->integer('book_type');
            $table->dateTime('date_borrowed');	
            $table->dateTime('due_date');	
            $table->dateTime('return_date')->nullable();	
            $table->text('notes')->nullable();
            $table->integer('status');
            $table->timestamps();
        });
    }

    /*
      - transaction_no
      - user_id = User (Table)
      - book_type
        - 1 = AccessionBooks: accession_id (Foreign Key) = accessions_tbl
        - 2 = Journals: journals_id (Foreign Key) = journals_tbl     
        - 3 = Inspirationals: inspirationals_id (Foreign Key) = inspiarationals_tbl      
        - 4 = ThesisBook: thesis_book_id (Foreign Key) = thesis_book_tbl       
      - date_borrowed (nullable) if not yet borrowed
      - due_date (nullable) if not yet approved : if approved due_date to be borrowed
      - status
        - 1 = pending request to be borrowed (To be Approved)
        - 2 = approved to be borrowed (Approved: Reserved) 2 days 
        - 3 = borrowed
        - 4 = returned
        - 5 = damage/lost
    */
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrowed_books');
    }
}
