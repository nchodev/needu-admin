<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PreferenceAddon extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'position',
        'parent_id'
    ];
    protected $casts = [

        'parent_id' => 'integer',
        'position' => 'integer',
        'priority' => 'integer',
        'status' => 'integer',
        'childes_count' => 'integer',
    ];
    protected $with = ['parent'];



    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    public function children(): HasMany
    {
        return $this->hasMany(PreferenceAddon::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PreferenceAddon::class, 'parent_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
  
    public function getNameAttribute($value)
{
    // Check translations for the current model
    if ($this->translations->isNotEmpty()) {
        foreach ($this->translations as $translation) {
            if ($translation->key == 'name') {
                return $translation->value;
            }
        }
    }

    return $value;
}


    protected static function booted()
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                return $query->where('locale', App::getLocale());
            }]);
        });
    }


    protected $hidden = [
        "created_at",
        "parent_id",
        "position",
        "priority",
        "status",
        "updated_at",
    ];
}
