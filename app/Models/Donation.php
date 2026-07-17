<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $table = 'donation';
    protected $primaryKey = 'donation_id';

    protected $fillable = [
        'fundraising_campaign_id',
        'participant_id',
        'amount',
        'payment_intent_id',
        'status',
        'payment_method',
        'donation_date',
        'bill_code',
        'transaction_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donation_date' => 'datetime',
    ];

    public function fundraisingCampaign(): BelongsTo
    {
        return $this->belongsTo(FundraisingCampaign::class, 'fundraising_campaign_id', 'fundraising_campaign_id');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class, 'participant_id', 'participant_id');
    }
}
