<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Translation extends Model
{
    use HasFactory;
    public $timestamps =false;
    protected $casts=[
        'translationable_id'=>'integer'
    ];
    protected $fillable =[
        'translationable_id',
        'translationable_type',
        'locale',
        'key',
        'value'
    ];
    public function translationable(): MorphTo
    {
        return $this->morphTo();
    }
}
