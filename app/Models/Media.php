<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected $hidden = [

        'created_at',
        'updated_at'
    ];
}
