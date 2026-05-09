<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditService
{
    /**
     * Enregistre une action dans les logs d'audit.
     */
    public function logAction($action, $model, array $details = [])
    {
        try {
            return AuditLog::create([
                'user_id' => Auth::id() ?? null,
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'details' => json_encode(array_merge($details, [
                    'effectue_par' => Auth::user()->name ?? 'Système',
                    'effectue_le' => now()->toDateTimeString(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur AuditService : ' . $e->getMessage(), [
                'action' => $action,
                'model' => get_class($model),
                'id' => $model->id
            ]);
            return null;
        }
    }
}
