<?php

namespace App\Services;

use App\Models\ConsultaExterna\Receta;
use App\Services\Farmacia\AnularRecetasService;
use Carbon\Carbon;

class VerificarCaducidadRecetas
{
    public function verificarCaducidadRecetas()
    {
        $revertirRecetas = new AnularRecetasService();

        $fechaLimite = Carbon::now()->subDays(2);
        $recetasCaducadas = Receta::where('created_at', '<=', $fechaLimite)
            ->whereNotIn('id_estado_validacion', [2, 3, 4])
            ->get();

        //dd($recetasCaducadas);

        foreach ($recetasCaducadas as $key => $recetaCaducada) {

            $revertirRecetas->revertirRecetasAnuladas($recetaCaducada->id, false);

            $recetaCaducada->id_estado_validacion = 4;
            $recetaCaducada->save();
        }
    }


}
