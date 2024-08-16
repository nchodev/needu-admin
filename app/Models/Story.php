<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'expires_at',
        'mood_id',
        'read_count',
        'thumbnail',
        'type'
    ];
    protected $with = ['userMood'];

    protected $dates = ['expires_at'];

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


    public function userMood(): BelongsTo

    {
        return $this->belongsTo(PreferenceAddon::class, 'mood_id');
    }

    public function scopeActive($query)

    {
        return $query->where('expires_at', '>', now());
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($story) {
            // Définir expires_at à 24 heures après maintenant
            if (is_null($story->expires_at)) {
                $story->expires_at = Carbon::now()->addDays(3);
            }else{
                $story->expires_at =$story->expires_at;
            }
        });
    }


}
