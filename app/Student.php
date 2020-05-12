<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lib_card_no',
        'stud_id_no',
        'f_name',
        'm_name',
        'l_name',
        'gender',
        'password',
        'address',
        'email_add',
        'pic_url',
        'program_id',
        'grade_year',
        'section_id',
        'school_year',
        'sem',
        'status',
    ];  

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
}
