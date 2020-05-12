<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AquisitionBook extends Model
{
    protected $fillable = [
        'acc_id',
        'quantity',
        'aquisition_type'
    ];
}
