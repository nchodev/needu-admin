<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserMatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'user1_id',
        'user2_id',
        'matched_at'
    ];
    protected $with =['user2','user1'];

    // Relation avec le premier utilisateur
    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    // Relation avec le deuxième utilisateur
    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }
    public function messages():HasMany
    {
        return $this->hasMany(Message::class);
    }

    protected $hidden =[
        'created_at',
        'updated_at'
    ];
}
