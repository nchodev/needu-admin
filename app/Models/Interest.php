<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interest extends Model
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
