<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Residencia;

class ResidenciaSeeder extends Seeder
{

    public function run()
    {
        // Ruta del CSV (puedes ponerlo en database/seeds/)
        $file = database_path('seeders/datos/residencias.csv');

        // Leer todas las líneas del CSV
        $csv = array_map('str_getcsv', file($file));

        // Si tu CSV tiene encabezados en la primera fila, elimínalos:
        // array_shift($csv);

        foreach ($csv as $row) {
            Residencia::create([
                'direccion' => $row[0],
                'nro_vivienda' => $row[1],
                'id_zona' => $row[2],
                'latitud' => $row[3],
                'longitud' => $row[4],
                'id_user_created' => $row[5],
                'id_user_updated' => $row[6],
            ]);
        }
    }
}
