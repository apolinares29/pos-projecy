<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity to the database.
     *
     * @param string $actionType
     * @param string|null $targetType
     * @param int|null $targetId
     * @param string $message
     * @param string $level
     * @return void
     */
    public static function log(
        string $actionType,
        ?string $targetType,
        ?int $targetId,
        string $message,
        string $level = 'INFO'
    ) {
        $user = Auth::user();
        ActivityLog::create([
            'level' => $level,
            'action_type' => $actionType,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'user_id' => $user ? $user->id : null,
            'username' => $user ? $user->username : (session('username') ?? 'system'),
            'ip_address' => request()->ip(),
            'message' => $message,
        ]);
    }
} 