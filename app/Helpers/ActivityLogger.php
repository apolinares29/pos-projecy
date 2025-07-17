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

    /**
     * Generate a human-readable diff of changed fields between two arrays or models.
     *
     * @param array $old Original attributes
     * @param array $new Updated attributes
     * @param array|null $fields Optional: restrict to these fields
     * @return string
     */
    public static function diff(array $old, array $new, ?array $fields = null): string
    {
        $diffs = [];
        $fieldsToCheck = $fields ?? array_unique(array_merge(array_keys($old), array_keys($new)));
        foreach ($fieldsToCheck as $field) {
            $oldVal = $old[$field] ?? null;
            $newVal = $new[$field] ?? null;
            if ($oldVal != $newVal) {
                // Hide passwords and sensitive fields
                if (in_array($field, ['password', 'remember_token'])) {
                    $diffs[] = "Changed $field";
                } else {
                    $diffs[] = "Changed $field: '" . (is_null($oldVal) ? 'null' : $oldVal) . "' → '" . (is_null($newVal) ? 'null' : $newVal) . "'";
                }
            }
        }
        return $diffs ? implode('; ', $diffs) : 'No changes detected.';
    }
} 