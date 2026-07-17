<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FundraisingCampaign extends Model
{
    protected $table = 'fundraising_campaign';
    protected $primaryKey = 'fundraising_campaign_id';

    protected $fillable = [
        'event_id',
        'target_amount',
        'collected_amount',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'fundraising_campaign_id', 'fundraising_campaign_id');
    }

    /**
     * Campaign is active if end_date >= today
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->end_date && $this->end_date->gte(now()->startOfDay());
    }

    public function getPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->collected_amount / $this->target_amount) * 100, 1));
    }
}
