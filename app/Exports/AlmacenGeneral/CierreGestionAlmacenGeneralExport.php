<?php

namespace App\Exports\AlmacenGeneral;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CierreGestionAlmacenGeneralExport implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    protected $dato;
    protected $infoCierreGestion;

    public function __construct($dato, $infoCierreGestion)
    {
        $this->dato = $dato;
        $this->infoCierreGestion = $infoCierreGestion;
    }

    public function collection()
    {
        return collect($this->dato);
    }

    public function headings(): array
    {
        return [
            'N°',
            'FECHA REGISTRO',
            'CÓDIGO MATERIAL',
            'DESCRIPCIÓN DE MATERIAL',
            'FECHA DE VENCIMIENTO',
            'UNIDAD',
            'CANTIDAD FISICA',
            'CANTIDAD SISTEMA',
            'CANTIDAD BAJA',
            'FECHA VENC. BAJA',
        ];
    }

    // Eventos para formatear el Excel
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Cabecera: Negrita y color gris de fondo con texto blanco
                $sheet->getStyle('A1:J1')->getFont()->setBold(true);
                $sheet->getStyle('A1:J1')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('9B9592');

                $sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('FFFFFF');

                // Insertar filas de información del cierre de gestión arriba de la tabla
                $sheet->insertNewRowBefore(1, 6);

                // Títulos del encabezado del documento
                $sheet->setCellValue('C2', 'CIERRE DE GESTIÓN');
                $sheet->setCellValue('C3', 'ALMACEN GENERAL');

                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                // Información del usuario y reporte
                $sheet->setCellValue('D2', 'USUARIO: ');
                $sheet->setCellValue('E2', $this->infoCierreGestion['nombreUsuario']);

                $sheet->setCellValue('D3', 'CÉDULA: ');
                $sheet->setCellValue('E3', $this->infoCierreGestion['ci']);

                $sheet->setCellValue('D4', 'FECHA: ');
                $sheet->setCellValue('E4', $this->infoCierreGestion['fechaActual']);

                $sheet->setCellValue('A6', 'REPORTE DE CIERRE DE GESTIÓN DE MATERIALES');
                $sheet->mergeCells('A6:J6');
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);

                // Negrita para etiquetas de información
                $sheet->getStyle('D2')->getFont()->setBold(true);
                $sheet->getStyle('D3')->getFont()->setBold(true);
                $sheet->getStyle('D4')->getFont()->setBold(true);

                // Formato numérico para cantidades
                $sheet->getStyle('G7:I' . ($sheet->getHighestRow()))
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER);

                // Ajustar ancho automático de columnas (se aplica al final para calcular bien el contenido)
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Insertar Logo SSU
                if (file_exists(public_path('logo/ssu.png'))) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo SSU');
                    $drawing->setDescription('Logo');
                    $drawing->setPath(public_path('logo/ssu.png'));
                    $drawing->setHeight(80);
                    $drawing->setCoordinates('B2');
                    $drawing->setWorksheet($sheet);
                }
            }
        ];
    }

    public function title(): string
    {
        return 'CIERRE GESTION ALMACEN GENERAL';
    }
}
