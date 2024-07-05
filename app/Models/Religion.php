<?php

namespace App\Models;

use App\Models\User;
use App\Models\PreferenceAddon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Religion extends Model
{
    use HasFactory;
    protected $with = 'preferenceAddon';

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
