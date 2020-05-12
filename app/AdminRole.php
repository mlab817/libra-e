<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    public function admin()
    {
        return $this->belongsTo('App\Admin');
    }
}
