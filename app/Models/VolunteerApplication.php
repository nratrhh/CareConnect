<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerApplication extends Model
{
    protected $table = 'volunteer_application';
    protected $primaryKey = 'application_id';

    protected $fillable = [
        'volunteer_event_id',
        'participant_id',
        'skills',
        'notes',
        'status',
        'decline_reason',
        'cancel_reason',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'skills' => 'array',
    ];

    public function volunteerEvent(): BelongsTo
    {
        return $this->belongsTo(VolunteerEvent::class, 'volunteer_event_id', 'volunteer_event_id');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class, 'participant_id', 'participant_id');
    }

    public function getFormattedIdAttribute(): string
    {
        return 'APP' . str_pad($this->application_id, 3, '0', STR_PAD_LEFT);
    }
}
