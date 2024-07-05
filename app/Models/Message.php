<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_match_id',
        'sender_id',
        'receiver_id',
        'content',
        'type'
    ];

    public function match():BelongsTo
    {
        return $this->belongsTo(UserMatch::class);
    }
}
