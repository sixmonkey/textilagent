<?php

namespace App\Models;

use App\Models\Traits\HasCountry;
use App\Models\Traits\HasRelationships;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    use HasCountry;
    use Searchable;
    use HasRelationships;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    /**
     * the purchases of this company.
     *
     * @return HasMany
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Order::class, 'purchaser_id');
    }

    /**
     * the supplies of this company.
     *
     * @return HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * representation of this model in a search
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    /**
     * get the users title
     *
     * @return string
     */
    public function getTitleAttribute(): string
    {
        return $this->name;
    }
}
