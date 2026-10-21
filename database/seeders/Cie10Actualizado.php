<?php

namespace Database\Seeders;

use App\Models\ConsultaExterna\Cie10;
use Illuminate\Database\Seeder;

class Cie10Actualizado extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/datos/CIE 10 FALTANTES EN BD.csv');

        if (! file_exists($path)) {
            $this->command->error("❌ Archivo no encontrado: {$path}");

            return;
        }

        $insertados = $this->importarCsv($path);

        $this->command->info("✅ CIE10 insertados: {$insertados}");
    }

    private function importarCsv(string $path): int
    {
        $delimiter = ';';
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return 0;
        }

        // ===== CABECERAS =====
        $headers = fgetcsv($handle, 0, $delimiter);
        if (! $headers) {
            fclose($handle);
            $this->command->error('❌ CSV vacío o sin encabezados');

            return 0;
        }

        $headers = array_map([$this, 'normalizarHeader'], $headers);

        $map = [
            'codigo' => array_search('codigo', $headers, true),
            'descripcion' => array_search('descripcion', $headers, true),
        ];

        if ($map['codigo'] === false || $map['descripcion'] === false) {
            fclose($handle);
            $this->command->error('❌ El CSV debe tener las columnas CODIGO y DESCRIPCION');

            return 0;
        }

        $insertados = 0;

        // ===== FILAS =====
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {

            $codigo = $this->val($row[$map['codigo']] ?? null);
            $descripcion = $this->val($row[$map['descripcion']] ?? null);

            if (! $codigo || ! $descripcion) {
                continue;
            }

            // Evita duplicados
            Cie10::create([
                'codigo' => $codigo,
                'descripcion' => $descripcion,
            ]);

            $insertados++;
        }

        fclose($handle);

        return $insertados;
    }

    // ================= UTILIDADES =================

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

    private function normalizarHeader(string $h): string
    {
        $h = trim(mb_strtolower($h, 'UTF-8'));
        $h = strtr($h, [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
        ]);

        return preg_replace('/[^a-z0-9_]/', '', $h);
    }

    private function val($v): ?string
    {
        if ($v === null) {
            return null;
        }
        $v = trim((string) $v);

        return $v === '' ? null : $v;
    }
}
