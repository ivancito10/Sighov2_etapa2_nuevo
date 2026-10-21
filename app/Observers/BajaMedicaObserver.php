<?php

namespace App\Observers;

use App\Models\ConsultaExterna\BajaMedica;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Services\ServicioPersonaInstitucion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BajaMedicaObserver
{
    public function created($model)
    {
        $this->sync($model);
    }


    public function updated($model)
    {
        $this->sync($model);
    }

    private function sync($model)
    {
        //Preparando datos para almacenar

        $datosBajaMedica = [
            'fecha_recepcion' => $model->created_at,
            'fecha_inicio' => $model->fecha_ini_baja,
            'fecha_fin' => $model->fecha_fin_baja,
            'id_especialidad' => $model->id_especialidad,
            'id_especialista' => $model->id_especialista,
            'id_user_created' => $model->id_user_created,
            'estado_validacion' => 'PENDIENTE',
            'diagnostico' => $model->descripcion,
            'id_institucion' => $model->id_institucion,
            'id_persona' => $model->id_persona,
            'estado_registro' => true,
            'cod_baja_medica' => 'BM/N°/' . $model->nro_baja_medica . '/' . Carbon::parse($model->created_at)->format('Y'),
            'updated_at' => Carbon::now(),
            'id_baja_sighov2_salud' => $model->id,
        ];

        //dd($datosBajaMedica);

        try {
            DB::connection('pgsql_bd1')
                ->table('baja_medica')
                ->updateOrInsert(
                    ['id_baja_sighov2_salud' => $model->id],
                    $datosBajaMedica
                );
        } catch (\Exception $e) {
            dd($e);
            \Log::error('Error sincronizando baja médica', [
                'id' => $model->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
