<?php

namespace Database\Seeders;

use App\Models\Administracion\Residencia;
use App\Models\Farmacia\InstitucionExterna;
use Illuminate\Database\Seeder;

class InstitucionExternaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // --- Leer residencias desde CSV ---
        $residenciasCsv = array_map(function ($line) {
            return str_getcsv($line, ';');
        }, file(database_path('seeders/datos/Residencias.csv')));

        array_shift($residenciasCsv); // quitar encabezado

        $residenciasRegistradas = []; // array para guardar los IDs generados

        foreach ($residenciasCsv as $index => $row) {
            $residencia = Residencia::create([
                'direccion' => $row[0],
                'nro_vivienda' => $row[1],
                'id_zona' => $row[2],
                'id_user_created' => $row[3],
                'id_user_updated' => $row[4],
            ]);

            // Guardar el ID generado en el mismo orden que el CSV
            $residenciasRegistradas[$index] = $residencia->id;
        }

        // --- Leer instituciones externas desde CSV ---
        $institucionesCsv = array_map(function ($line) {
            return str_getcsv($line, ';'); // <-- usar ; como separador
        }, file(database_path('seeders/datos/InstitucionExterna.csv')));
        array_shift($institucionesCsv); // quitar encabezado

        foreach ($institucionesCsv as $index => $row) {
            // Obtenemos el ID de residencia generado
            $idResidencia = $residenciasRegistradas[$index] ?? null;

            if ($idResidencia) {
                InstitucionExterna::create([
                    'razon_social' => $row[0],
                    'nit' => $row[1],
                    'registro_sanitario' => $row[2],
                    'telefono' => $row[3],
                    'correo' => $row[4],
                    'estado' => $row[5],
                    'id_residencia' => $idResidencia, // asignación automática
                    'id_user_created' => $row[6],
                    'id_user_updated' => $row[7],

                ]);
            } else {
                echo "No se encontró residencia para institución: {$row[0]}\n";
            }
        }
    }
}
