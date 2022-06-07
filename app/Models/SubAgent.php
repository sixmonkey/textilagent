<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubAgent extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
        'order_id',
        'created_at',
        'updated_at',
    ];

    /**
     * mass assignable
     *
     * @var string[]
     */
    protected $fillable = [
        'cut'
    ];

    /**
     * appended accessors
     *
     * @var string[]
     */
    protected $appends = [
        'name',
    ];

    /**
     * the related user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * the related order
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * get the name for this subagent from related user
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->user()->first()->name ?? 'unknown or deleted agent';
    }
}
