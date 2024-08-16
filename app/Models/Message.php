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
        'type',
    ];
    protected $with =[
        'receiver',
        'sender'
    ];


    public function match():BelongsTo
    {
        return $this->belongsTo(UserMatch::class);
    }
      // Relation vers l'utilisateur expéditeur
      public function sender()
      {
          return $this->belongsTo(User::class, 'sender_id');
      }

      // Relation vers l'utilisateur destinataire
      public function receiver()
      {
          return $this->belongsTo(User::class, 'receiver_id');
      }
      public static function getMessagesBetween($userId1, $userId2)
    {
        return self::with('receiver')->where(function($query) use ($userId1, $userId2) {
                    $query->where('sender_id', $userId1)
                        ->where('receiver_id', $userId2);
                })
                ->orWhere(function($query) use ($userId1, $userId2) {
                    $query->where('sender_id', $userId2)
                        ->where('receiver_id', $userId1);
                })
                ->orderBy('created_at', 'asc') // Pour obtenir les messages dans l'ordre chronologique
                ->get();
    }

}
