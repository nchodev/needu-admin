<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $with = [
        'sexOrientation',
        'interests',
        'lookingFor',
        'media',
        'spokenLanguages',
        'religion',
        'maritalStatus',
        'moreAbouts',
        'lifeStyles',
        'stories',
        'activeStories',
        'gender',
        'preferences'
        ];

    protected $fillable = [
        'email',
        'password',
        'full_name',
        'nick_name',
        'login_medium',
        'social_id',
        'ref_code',
        'current_lang',
        'email_verified_at',
        'status',
        'online',
        'media_count',
        'verified',
        'slug',
        'timezone',
        'dob'

    ];

    public function lookingFor(): HasOne
    {
        return $this->hasOne(LookingFor::class);
    }
    public function sexOrientation(): HasOne
    {
        return $this->hasOne(SexOrientation::class);
    }
    public function gender(): HasOne
    {
        return $this->hasOne(UserGender::class);
    }
    public function interests(): HasMany
    {
        return $this->hasMany(Interest::class);
    }
    public function spokenLanguages(): HasMany
    {
        return $this->hasMany(SpokenLanguage::class);
    }
    public function religion(): HasOne
    {
        return $this->hasOne(Religion::class);
    }
    public function maritalStatus(): HasOne
    {
        return $this->hasOne(MaritalStatus::class);
    }
    public function moreAbouts(): HasMany
    {
        return $this->hasMany(MoreAbout::class);
    }
    public function lifeStyles(): HasMany
    {
        return $this->hasMany(LifeStyle::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
    public function getAgeAttribute()
    {
        return Carbon::parse($this->dob)->age;
    }
    public function getFormattedCreatedAtAttribute()
    {
        // Définir la langue en français
        Carbon::setLocale(App::getLocale());

        return Carbon::parse($this->created_at)->translatedFormat('d F Y');
    }
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

     // Définir la portée notLikedUsers
     public function scopeNotLikedUsers(Builder $query, $userId)
     {
         // Récupérer les IDs des utilisateurs déjà aimés
         $likedUserIds = Like::where('liker_id', $userId)->pluck('liked_id')->toArray();

         // Ajouter l'ID de l'utilisateur courant pour l'exclure de la liste
         $likedUserIds[] = $userId;

         // Retourner les utilisateurs qui ne sont pas dans la liste des utilisateurs aimés
         return $query->whereNotIn('id', $likedUserIds);
     }

    // Utilisateur a liké
    public function likesGiven()
    {
        return $this->hasMany(Like::class, 'liker_id');
    }

    // Utilisateur a été liké
    public function likesReceived()
    {
        return $this->hasMany(Like::class, 'liked_id');
    }


    public function matches()
    {
        return $this->hasMany(UserMatch::class, 'user1_id');
    }
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function activeStories()

    {
        return $this->stories()->active();
    }
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes', 'liker_id', 'liked_id');
    }
    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->slug = self::generateSlug($user->nick_name);
        });

        static::updating(function ($user) {
            if ($user->isDirty('nick_name')) {
                $user->slug = self::generateSlug($user->nick_name);
            }
        });
    }

    // Générer un slug à partir du nick_name
    public static function generateSlug($nick_name)
    {
        // Convertir en slug et mettre en minuscule
        $slug = Str::slug(Str::lower($nick_name));

        // Assurer l'unicité du slug
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected  $casts= [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone'=>'string',
            'height'=>'string'
        ];

}
