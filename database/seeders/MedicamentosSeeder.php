<?php

namespace Database\Seeders;

use App\Models\Farmacia\Medicamento;
use Illuminate\Database\Seeder;

class MedicamentosSeeder extends Seeder
{
    public function run(): void
    {
        $base = database_path('seeders/datos');

        $csvs = [
            "{$base}/MEDICAMENTOS RESTRIGIDOS 2025 VALIDADOS.csv" => true,
            "{$base}/MEDICAMENTOS NO RESTRIGIDOS 2025 VALIDADO.csv" => false,
        ];

        $total = 0;

        foreach ($csvs as $csvPath => $isRestringido) {

            if (! file_exists($csvPath)) {
                $this->command->warn("⚠️ Archivo no encontrado: {$csvPath}");

                continue;
            }

            $insertados = $this->importarCsv($csvPath, $isRestringido);
            $estado = $isRestringido ? 'RESTRINGIDOS' : 'NO RESTRINGIDOS';

            $this->command->info("✅ Insertados {$insertados} medicamentos {$estado}");
            $total += $insertados;
        }

        $this->command->info("🎯 TOTAL GENERAL INSERTADO: {$total}");
    }

    private function importarCsv(string $csvPath, bool $restringido): int
    {
        $delimiter = $this->detectarDelimitador($csvPath);
        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            return 0;
        }

        // ====== CABECERAS ======
        $headers = fgetcsv($handle, 0, $delimiter);
        if (! $headers) {
            fclose($handle);
            $this->command->error('❌ CSV vacío o sin encabezados');

            return 0;
        }

        $normHeaders = array_map([$this, 'normalizarHeader'], $headers);
        $map = $this->mapearHeaders($normHeaders);

        $required = [
            'codigo_medicamento',
            'nombre',
            'forma_farmaceutica',
            'numero_cuenta_contable',
            'id_cuenta_contable',
        ];

        $faltan = array_diff($required, array_keys($map));

        if (! empty($faltan)) {
            $this->command->error('❌ Faltan columnas: '.implode(', ', $faltan));
            $this->command->line('Detectadas: '.implode(' | ', $normHeaders));
            fclose($handle);

            return 0;
        }

        $insertados = 0;

        // ====== FILAS ======
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {

            if (count($row) < count($headers)) {
                $row = array_pad($row, count($headers), null);
            }

            $codigo = $this->val($row[$map['codigo_medicamento']] ?? null);
            $nombre = $this->val($row[$map['nombre']] ?? null);
            $forma = $this->val($row[$map['forma_farmaceutica']] ?? null);
            $conc = $this->val($row[$map['concentracion']] ?? null);
            $cuenta = $row[$map['numero_cuenta_contable']] ?? null;
            $idCuenta = $this->limpiarCuenta($row[$map['id_cuenta_contable']] ?? null);

            if (! $codigo && ! $nombre) {
                continue;
            }

            Medicamento::create([
                'codigo_medicamento' => $codigo,
                'nombre' => $nombre,
                'forma_farmaceutica' => $forma,
                'concentracion' => $conc,
                'restringido' => $restringido,
                'estado' => true,
                'cuenta_contable' => $cuenta,
                'id_cuenta_contable' => $idCuenta,
                'id_user_created' => 1,
                'id_user_updated' => null,
            ]);

            $insertados++;
        }

        fclose($handle);

        return $insertados;
    }

    // ================== UTILIDADES ==================

    private function detectarDelimitador(string $path): string
    {
        $line = '';
        $fh = fopen($path, 'r');
        if ($fh) {
            $line = fgets($fh, 4096) ?: '';
            fclose($fh);
        }

        return (substr_count($line, ';') > substr_count($line, ',')) ? ';' : ',';
    }

    private function normalizarHeader(?string $h): string
    {
        $h = trim(mb_strtolower($h ?? '', 'UTF-8'));

        $h = strtr($h, [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
            'ü' => 'u',
        ]);

        $h = preg_replace('/[^\p{L}\p{N}_]+/u', '_', $h);

        return trim($h, '_');
    }

    private function mapearHeaders(array $headers): array
    {
        $syn = [
            'codigo_medicamento' => ['codigo'],
            'nombre' => ['medicamento'],
            'forma_farmaceutica' => ['forma'],
            'concentracion' => ['concentracion'],
            'numero_cuenta_contable' => ['cuenta'],
            'id_cuenta_contable' => ['id_cuenta'],
        ];

        $map = [];
        foreach ($syn as $key => $aliases) {
            foreach ($aliases as $alias) {
                $idx = array_search($alias, $headers, true);
                if ($idx !== false) {
                    $map[$key] = $idx;
                    break;
                }
            }
        }

        return $map;
    }

    private function val($v): ?string
    {
        if ($v === null) {
            return null;
        }
        $v = trim((string) $v);

        return $v === '' ? null : $v;
    }

    private function limpiarCuenta($v): ?string
    {
        if ($v === null) {
            return null;
        }

        if (preg_match('/e\+/i', $v)) {
            return (string) number_format((float) $v, 0, '', '');
        }

        $digits = preg_replace('/\D+/', '', (string) $v);

        return $digits ?: null;
    }
}
