<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'full_name',
        'nick_name',
        "user_id",
        "current_lang",
        'height',
        'phone',
        'education',
        'dob',
        'bio',
        'company',
        'profession'
    ];
}
