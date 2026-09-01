<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'game_id',
        'rank_from_id',
        'rank_to_id',
        'name',
        'slug',
        'description',
        'base_price',
        'price_per_star',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'price_per_star' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function rankFrom(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'rank_from_id');
    }

    public function rankTo(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'rank_to_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
