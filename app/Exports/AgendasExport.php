<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class AgendasExport implements FromCollection, WithEvents, WithHeadings
{
    protected $agendas, $nombre_institucion, $tipo_asegurado, $fecha_inicio, $fecha_fin;

    public function __construct($agendas, $nombre_institucion, $tipo_asegurado, $fecha_inicio, $fecha_fin)
    {
        $this->agendas = $agendas;
        $this->nombre_institucion = $nombre_institucion;
        $this->tipo_asegurado = $tipo_asegurado;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
    }
    public function collection()
    {
        return collect($this->agendas);
    }

    public function headings(): array
    {
        return [
            'Nº',
            'NOMBRE ASEGURADO',
            'MATRICULA',
            'TIPO DE ASEGURADO',
            'FECHA AGENDA',
            'HORA AGENDA',
            'ESPECIALISTA',
            'ESPECIALIDAD',
            'TIPO DE AGENDA',
            'ESTADO DE AGENDA',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

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
                // Insertar filas de información del medicamento arriba de la tabla
                $sheet->insertNewRowBefore(1, 7);
                // insertamos titulo 
                $sheet->setCellValue('C2', 'DETALLE DE AGENDAS');
                $sheet->mergeCells('C2:D2');
                $sheet->setCellValue('C3', $this->tipo_asegurado);
                $sheet->mergeCells('C3:D3');

                // CAMBIA COLORES
                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                // --- Insertar imagen ---
                $drawing = new Drawing();
                $drawing->setName('Logo SSU');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('logo/ssu.png'));
                $drawing->setHeight(80); // altura en pixeles
                $drawing->setCoordinates('B2');
                $drawing->setWorksheet($sheet);

                // INSTITUCION -------------
                $sheet->setCellValue('F2', 'INSTITUCIÓN: ');
                $sheet->getStyle('F2')->getFont()->setBold(true);
                // Combinar H2 hasta J2
                $sheet->mergeCells('G2:J2');
                // Escribir el nombre en la primera celda del rango
                $sheet->setCellValue('G2', $this->nombre_institucion);
                // MES  --------------------
                $sheet->setCellValue('F3', 'DESDE: ');
                $sheet->getStyle('F3')->getFont()->setBold(true);
                //  $sheet->mergeCells('H3:J3');
                $sheet->setCellValue('G3', $this->fecha_inicio);
                $sheet->getStyle('G3:J3')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // GESTION  --------------------
                $sheet->setCellValue('F4', 'HASTA: ');
                $sheet->getStyle('F4')->getFont()->setBold(true);
                $sheet->setCellValue('G4', $this->fecha_fin);
                $sheet->getStyle('G4:J4')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // GENERADO POR 
                $sheet->setCellValue('F6', 'Generado por Sistema Sigho V2 ');
                $sheet->getStyle('F6')->getFont()->setBold(true);
                $sheet->mergeCells('F6:G6');
            },
        ];
    }
}
