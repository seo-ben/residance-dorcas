<?php

namespace App\Services;

use App\Models\ParametreSysteme;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Récupère un paramètre système par sa clé.
     */
    public function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = ParametreSysteme::where('cle', $key)->first();
            return $setting ? $setting->valeur : $default;
        });
    }

    /**
     * Récupère la monnaie du système.
     */
    public function getCurrency()
    {
        return $this->get('currency', 'FCFA');
    }

    /**
     * Récupère le taux de TVA.
     */
    public function getVatRate()
    {
        return (float) $this->get('tax_rate', 0.18); // 18% par défaut
    }

    /**
     * Heure limite de Check-in.
     */
    public function getCheckInTime()
    {
        return $this->get('check_in_time', '14:00');
    }

    /**
     * Heure limite de Check-out.
     */
    public function getCheckOutTime()
    {
        return $this->get('check_out_time', '12:00');
    }
}
