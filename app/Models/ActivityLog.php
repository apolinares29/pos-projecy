<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'timestamp',
        'level',
        'action_type',
        'target_type',
        'target_id',
        'user_id',
        'username',
        'ip_address',
        'message',
    ];

    public $timestamps = true;

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 