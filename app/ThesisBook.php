<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThesisBook extends Model
{
    public function thesis_authors()
    {
        return $this->hasMany('App\ThesisAuthor', 'thesis_id');
    }
}
