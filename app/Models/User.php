<?php

namespace App\Models;

use App\Models\Traits\HasCountry;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends AuthUser
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasCountry;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
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
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!$this->exists && empty($this->getAttribute('password'))) {
            $this->password = bcrypt(Str::random(16));
        }
        return parent::save($options);
    }

    /**
     * related orders
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'agent_id');
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

    public static function definedRelations(): array
    {
        $reflector = new \ReflectionClass(get_called_class());

        return collect($reflector->getMethods())
            ->filter(
                fn ($method) => !empty($method->getReturnType()) &&
                    str_contains(
                        $method->getReturnType(),
                        'Illuminate\Database\Eloquent\Relations'
                    )
            )
            ->mapWithKeys(function ($relationship) {
                return [$relationship->name => class_basename($relationship->getReturnType()->getName())];
            })
            ->
            all();
    }
}
