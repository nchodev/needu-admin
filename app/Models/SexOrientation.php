<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SexOrientation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'preference_addon_id'
    ];
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
