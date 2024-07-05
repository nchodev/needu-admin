<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use  HasFactory, Notifiable;
    
    protected $guard = 'admin';



    protected $hidden = [
        'password'
    ];
}
