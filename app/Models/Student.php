<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['openid','account','ssfw_password','lib_password'];

    protected $hidden = [
        'id','created_at', 'updated_at'
    ];

}
