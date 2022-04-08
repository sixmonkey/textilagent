<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * Set attributes that can be translated (https://spatie.be/docs/laravel-translatable/v6/introduction)
     *
     * @var string[]
     */
    public array $translatable = [
        'name',
        'official_name'
    ];

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'emoji' => $this->emoji,
        ];
    }
}
