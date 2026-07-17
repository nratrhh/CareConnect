<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'admin_id',
        'title',
        'eventImg',
        'eventCategory',
        'eventShortDesc',
        'eventLongDesc',
        'status',
    ];

    protected $casts = [
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }

    public function volunteerEvent(): HasOne
    {
        return $this->hasOne(VolunteerEvent::class, 'event_id', 'event_id');
    }

    public function fundraisingCampaign(): HasOne
    {
        return $this->hasOne(FundraisingCampaign::class, 'event_id', 'event_id');
    }

    public function getFormattedIdAttribute(): string
    {
        return 'EVT' . str_pad($this->event_id, 3, '0', STR_PAD_LEFT);
    }
}
