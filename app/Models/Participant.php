<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

class Participant extends Authenticatable
{
    use Notifiable;

    protected $table = 'participant';
    protected $primaryKey = 'participant_id';

    protected $fillable = [
        'name', 'ic_number', 'email', 'phone', 'password', 'profile_picture'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'participant_id', 'participant_id');
    }

    public function volunteerApplications()
    {
        return $this->hasMany(VolunteerApplication::class, 'participant_id', 'participant_id');
    }
}