<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accession extends Model
{
    protected $fillable = [
        'author_id',
        'book_title',
        'publisher_id',
        'copyright'
    ];
}
