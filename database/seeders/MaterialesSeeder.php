<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/datos/Materiales.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("El archivo materiales.csv no existe en: {$csvPath}");
            return;
        }

        // Obtener ID de usuario seguro para CLI
        $userId = auth()->id() ?? User::first()?->id ?? 1;

        // 1. Cargar las cuentas desde BD2 / Contabilidad
        $cuentasBD = DB::connection('bd2_contabilidad')
            ->table('contabilidad.cuenta_contable')
            ->pluck('id', 'nro_cuenta');

        // 2. Definir las cabeceras MANUALMENTE (porque el CSV no las incluye)
        $headers = [
            'codigo',
            'nombre_material',
            'descripcion',
            'estado',
            'nro_cuenta',
            'unidad'
        ];

        $file = fopen($csvPath, 'r');
        $dataToInsert = [];
        $codigosProcesados = []; // Control de duplicados
        $rowNum = 0;
        $duplicadosOmitidos = 0;
        $now = now();

        // Leer filas usando punto y coma (;) como delimitador
        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $rowNum++;

            // Omitir líneas vacías
            if ($row === [null] || empty(array_filter($row))) {
                continue;
            }

            // Si la fila tiene menos columnas de las esperadas, rellenamos o notificamos
            if (count($headers) !== count($row)) {
                $this->command->warn("Fila {$rowNum} ignorada: tiene " . count($row) . " columnas, se esperaban " . count($headers));
                continue;
            }

            // Asignar nombres de columnas a la fila
            $item = array_combine($headers, $row);
            $codigo = isset($item['codigo']) ? trim((string) $item['codigo']) : null;

            // --- VALIDACIÓN DE UNICIDAD (Evitar repetir códigos) ---
            if (empty($codigo) || isset($codigosProcesados[$codigo])) {
                $duplicadosOmitidos++;
                continue;
            }

            $codigosProcesados[$codigo] = true;

            // --- Formateo del número de cuenta ---
            $idCuenta = null;
            if (!empty($item['nro_cuenta'])) {
                $raw = trim((string) $item['nro_cuenta']);

                if (strlen($raw) >= 11) {
                    $cuentaFormateada = sprintf(
                        '%s %s %s %02d %02d',
                        $raw[0],
                        substr($raw, 0, 3),
                        substr($raw, 3, 2),
                        (int) substr($raw, 5, 3),
                        (int) substr($raw, 8)
                    );

                    $idCuenta = $cuentasBD->get($cuentaFormateada);

                    if (!$idCuenta) {
                        $this->command->warn("Fila {$rowNum}: No se encontró la cuenta '{$cuentaFormateada}' en BD2.");
                    }
                }
            }

            // Construir registro
            $dataToInsert[] = [
                'codigo'          => $codigo,
                'nombre_material' => $item['nombre_material'] ?? null,
                'descripcion'     => $item['descripcion'] ?? null,
                'estado'          => filter_var(trim($item['estado'] ?? 'true'), FILTER_VALIDATE_BOOLEAN),
                'unidad'          => $item['unidad'] ?? null,
                'id_nro_cuenta'   => $idCuenta,
                'id_user_created' => $userId,
                'id_user_updated' => $userId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        fclose($file);

        // 3. Inserción masiva en la tabla del esquema almacen_general
        if (!empty($dataToInsert)) {
            foreach (array_chunk($dataToInsert, 500) as $chunk) {
                DB::table('almacen_general.material')->insert($chunk);
            }

            $this->command->info('¡' . count($dataToInsert) . ' materiales ÚNICOS importados exitosamente!');
            if ($duplicadosOmitidos > 0) {
                $this->command->comment("Se ignoraron {$duplicadosOmitidos} registros duplicados (ej. código 630015 repetido).");
            }
        } else {
            $this->command->warn('No se encontraron datos válidos para insertar.');
        }
    }
}
