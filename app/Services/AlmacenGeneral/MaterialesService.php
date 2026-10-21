<?php

namespace App\Services\AlmacenGeneral;

use App\Models\AlmacenGeneral\MaterialStock;

class MaterialesService
{
    public function stockActual($id_material, $dato_almacen_general)
    {
        $stock_almacen_general = MaterialStock::where('id_material', $id_material)
            ->where('id_unidad', $dato_almacen_general)
            ->sum('stock_actualizado');
        return $stock_almacen_general;
    }
}
