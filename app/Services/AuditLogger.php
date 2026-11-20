<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Service class for centralized event logging.
 *
 * Demonstrates OOP Encapsulation by shielding callers from database schema specifics.
 */
class AuditLogger
{
    public static function log(string $action, ?string $description = null, ?int $userId = null): AuditLog
    {
        $payload = [
            'user_id'     => $userId ?? Auth::id(),
            'action'      => strtoupper($action),
            'description' => $description,
        ];

        // Safe defensive check: only insert if columns exist
        if (Schema::hasColumn('audit_logs', 'ip_address')) {
            $payload['ip_address'] = Request::ip();
        }

        if (Schema::hasColumn('audit_logs', 'user_agent')) {
            $payload['user_agent'] = Request::userAgent();
        }

        return AuditLog::create($payload);
    }
}