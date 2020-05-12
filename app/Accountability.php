<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accountability extends Model
{
    protected $fillable = [
        'payment_pirce',
        'invoice_id',
        'status'
    ];
    
}
