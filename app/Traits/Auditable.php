<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait Auditable
{
    // Dans logAction
    protected function logAction($action, $model, array $details)
    {
        try {
            $audit = AuditLog::create([
                'user_id' => Auth::id() ?? null,
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'details' => json_encode(array_merge($details, [
                    'effectue_par' => Auth::user()->name ?? 'Système',
                    'effectue_le' => now()->toDateTimeString(),
                ])),
            ]);

            if (in_array($action, ['payment_processed', 'reservation_cancelled'])) {
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $this->notificationService->sendNotification(
                        $admin,
                        null,
                        'email',
                        'Action financière enregistrée',
                        "Une action de type {$action} a été effectuée sur {$audit->model_type} #{$audit->model_id}. Détails : " . json_encode($details)
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la journalisation : ' . $e->getMessage());
        }
    }
}