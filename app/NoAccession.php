<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoAccession extends Model
{
    protected $fillable = [
        'accession_no',
        'accession_id',
        'status'
    ];
}
