<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConflictAccession extends Model
{
    protected $fillable = [
        'accession_no',
        'author',
        'title',
        'publisher',
        'status',
    ];
}
