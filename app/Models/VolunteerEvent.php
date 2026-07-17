<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteerEvent extends Model
{
    protected $table = 'volunteer_event';
    protected $primaryKey = 'volunteer_event_id';

    protected $fillable = [
        'event_id',
        'capacity',
        'eventDate',
        'start_time',
        'end_time',
        'location',
        'location_details',
        'benefits',
    ];

    protected $casts = [
        'eventDate' => 'date:Y-m-d',
        'benefits' => 'array',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(VolunteerApplication::class, 'volunteer_event_id', 'volunteer_event_id');
    }
}
