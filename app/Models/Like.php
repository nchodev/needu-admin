<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'liker_id',
        'liked_id',
        'story_id'
    ];

    // Un like appartient à un utilisateur
    public function liker()
    {
        return $this->belongsTo(User::class, 'liker_id');
    }

    // Un like appartient à un utilisateur
    public function liked()
    {
        return $this->belongsTo(User::class, 'liked_id');
    }
}
