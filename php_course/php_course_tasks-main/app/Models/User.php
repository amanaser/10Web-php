<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property integer $id
 * @property integer $type
 * @property string $first_name
 * @property string $last_name
'email',
'type',
'gender',
'username',
'password'
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;


    public const TYPE_SELLER = 1;
    public const TYPE_BUYER = 0;
    public const SLUG_SELLER = 'seller';
    public const SLUG_BUYER = 'buyer';
    public const TYPE_SLUGS = [
        self::TYPE_BUYER => self::SLUG_BUYER,
        self::TYPE_SELLER => self::SLUG_SELLER
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'type',
        'gender',
        'username',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isSeller(): bool
    {
       return  $this->type === self::TYPE_SELLER;
    }

    public function isBuyer(): bool
    {
        return  $this->type === self::TYPE_BUYER;
    }
}
