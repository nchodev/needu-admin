<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGender extends Model
{
    use HasFactory;
    protected $with = 'preferenceAddon';

    protected $fillable = [
        'user_id',
        'preference_addon_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preferenceAddon(): BelongsTo
    {
        return $this->belongsTo(PreferenceAddon::class);
    }
    protected $hidden = [

        'created_at',
        'updated_at'
    ];
}
