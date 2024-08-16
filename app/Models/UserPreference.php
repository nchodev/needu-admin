<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sex_orientation_id',
        'gender_id',
        'religion_id',
        'marital_status_id',
        'looking_for_id',
        'more_about_ids',
        'life_styles',
        'spoken_languages',
        'min_age',
        'max_age',
        'interests',
        'country',
        'max_distance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lookingFor()
    {
        return $this->belongsTo(LookingFor::class, 'looking_for_id');
    }


}
