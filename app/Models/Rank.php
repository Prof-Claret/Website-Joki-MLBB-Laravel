<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rank extends Model
{
    protected $fillable = [
        'game_id',
        'name',
        'slug',
        'icon_path',
        'star_system',
        'min_star',
        'max_star',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'min_star' => 'integer',
        'max_star' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function ordersFrom(): HasMany
    {
        return $this->hasMany(Order::class, 'rank_from_id');
    }

    public function ordersTo(): HasMany
    {
        return $this->hasMany(Order::class, 'rank_to_id');
    }
}
