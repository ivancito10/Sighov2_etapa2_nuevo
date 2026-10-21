<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class CierreGestionExport implements FromCollection, WithHeadings, WithEvents
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
            'CÓDIGO MEDICAMENTO',
            'MEDICAMENTO',
            'FECHA DE VENCIMIENTO',
            'FORMA FARMACUETICA',
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
                /*$sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);*/

                // Cabecera: Negrita y color
                $sheet->getStyle('A1:J1')->getFont()->setBold(true);
                $sheet->getStyle('A1:J1')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('9B9592');

                $sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('FFFFFF');

                // Ajustar ancho de columnas
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Insertar filas de información del cierre de gestión arriba de la tabla
                $sheet->insertNewRowBefore(1, 6);
                $sheet->setCellValue('C2', 'CIERRE DE GESTIÓN');
                $sheet->setCellValue('C3', ($this->infoCierreGestion['unidad'] === 'FARMACIA') ? 'FARMACIA' : 'ALMACEN DE MEDICAMENTOS');

                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);


                $sheet->setCellValue('D2', 'USUARIO: ');
                $sheet->setCellValue('E2', $this->infoCierreGestion['nombreUsuario']);


                $sheet->setCellValue('A6', 'REPORTE DE CIERRE DE GESTIÓN');
                $sheet->mergeCells('A6:J6');
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);

                // Añadir rango de fechas
                $sheet->setCellValue('D3', 'CÉDULA: ');
                $sheet->setCellValue('E3', $this->infoCierreGestion['ci']);

                $sheet->setCellValue('D4', 'FECHA: ');
                $sheet->setCellValue('E4', $this->infoCierreGestion['fechaActual']);

                // Negrita para información relevante
                $sheet->getStyle('D2:D3:D4')->getFont()->setBold(true);
                $sheet->getStyle('G:I')->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER);

                //Insertar imagen
                $drawing = new Drawing();
                $drawing->setName('Logo SSU');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('logo/ssu.png'));
                $drawing->setHeight(80); // altura en píxeles
                $drawing->setCoordinates('B2');
                $drawing->setWorksheet($sheet);
            }
        ];
    }

    public function title(): string
    {
        return 'CIERRE GESTION';
    }
}
