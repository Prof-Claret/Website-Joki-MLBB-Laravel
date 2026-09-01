<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'worker_id',
        'game_id',
        'service_id',
        'rank_from_id',
        'rank_to_id',
        'order_number',
        'status',
        'priority',
        'price',
        'payment_method',
        'payment_status',
        'wa_number',
        'account_credentials',
        'request_hero',
        'notes',
        'tracking_code',
        'started_at',
        'completed_at',
        'delivery_deadline_at',
        'worker_progress',
        'customer_rating',
        'customer_review',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'worker_progress' => 'integer',
        'customer_rating' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'delivery_deadline_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function rankFrom(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'rank_from_id');
    }

    public function rankTo(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'rank_to_id');
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(OrderProof::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
