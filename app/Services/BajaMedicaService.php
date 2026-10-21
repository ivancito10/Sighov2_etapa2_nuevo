<?php

namespace App\Services;

use App\Models\ConsultaExterna\BajaMedica;
use Illuminate\Support\Carbon;

class BajaMedicaService
{
    public function expirarVencidas(string $timezone = 'America/La_Paz'): int
    {
        $now = Carbon::now($timezone);

        return BajaMedica::where('estado', true)
            ->where('fecha_fin_baja', '<', $now)  // usa '<=' si quieres incluir mismas 00:00
            ->update([
                'estado'     => false,
                'updated_at' => now(),
            ]);
    }
}
