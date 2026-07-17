<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'user_type',
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'action_url',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ─── Scopes ───

    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('user_type', 'admin')->where('user_id', $adminId);
    }

    public function scopeForParticipant($query, $participantId)
    {
        return $query->where('user_type', 'participant')->where('user_id', $participantId);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // ─── Helpers ───

    /**
     * Send notification to ALL admins.
     */
    public static function notifyAllAdmins(string $type, string $title, string $message, ?string $icon = null, ?string $actionUrl = null): void
    {
        $admins = \App\Models\Admin::all();
        foreach ($admins as $admin) {
            self::create([
                'user_type'  => 'admin',
                'user_id'    => $admin->admin_id,
                'type'       => $type,
                'title'      => $title,
                'message'    => $message,
                'icon'       => $icon,
                'action_url' => $actionUrl,
            ]);
        }
    }

    /**
     * Send notification to a specific participant.
     */
    public static function notifyParticipant(int $participantId, string $type, string $title, string $message, ?string $icon = null, ?string $actionUrl = null): void
    {
        self::create([
            'user_type'  => 'participant',
            'user_id'    => $participantId,
            'type'       => $type,
            'title'      => $title,
            'message'    => $message,
            'icon'       => $icon,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Broadcast notification to ALL participants.
     */
    public static function notifyAllParticipants(string $type, string $title, string $message, ?string $icon = null, ?string $actionUrl = null): void
    {
        $participants = \App\Models\Participant::all();
        foreach ($participants as $participant) {
            self::create([
                'user_type'  => 'participant',
                'user_id'    => $participant->participant_id,
                'type'       => $type,
                'title'      => $title,
                'message'    => $message,
                'icon'       => $icon,
                'action_url' => $actionUrl,
            ]);
        }
    }
}
