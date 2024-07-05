<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'expires_at',
    ];

    // Une story appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une story peut avoir plusieurs likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Accéder aux utilisateurs qui ont liké cette story
    public function likers()
    {
        return $this->hasManyThrough(User::class, Like::class, 'story_id', 'id', 'id', 'liker_id');
    }

}
