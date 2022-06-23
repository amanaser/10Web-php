<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @method static findOrFail
 * @mixin Eloquent
 */
class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'phone_number',
        'email',
        'rating',
        'manager_name'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            if (!$model->user_id){
                $model->user_id = auth()->id();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeForUser($query): Builder
    {
        return $query->where('user_id', auth()->id());
    }
}
