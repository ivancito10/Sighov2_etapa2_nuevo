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

class BindcardExport implements FromCollection, WithEvents, WithHeadings
{
    protected $fecha_inicio;

    protected $fecha_fin;

    protected $id_medicamento;

    protected $unidad;

    protected $datos;

    protected $info_medicamento;

    public function __construct($datos, $info_medicamento)
    {
        $this->datos = $datos;
        $this->info_medicamento = $info_medicamento;
    }

    public function collection()
    {
        return collect($this->datos);
    }

    // Cabecera de la tabla de movimientos
    public function headings(): array
    {
        return [
            'N°',
            'FECHA',
            'SOLICITANTE',
            'MATRICULA',
            'TIPO',
            'CODIGO',
            'INTERESADO',
            'ESPECIALIDAD',
            'DIAGNOSTICO',
            'INGRESO',
            'SALIDA',
            'SALDO',
            'DATOS TRANSCRIPCIÓN',
            'OBSERVACIONES',
            'ESTADO'

        ];
    }

    // Eventos para formatear el Excel
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // Cabecera: Negrita y color
                $sheet->getStyle('A1:O1')->getFont()->setBold(true);
                $sheet->getStyle('A1:O1')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('9B9592');

                $sheet->getStyle('A1:O1')->getFont()->getColor()->setRGB('FFFFFF');

                // Ajustar ancho de columnas
                foreach (range('A', 'O') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Insertar filas de información del medicamento arriba de la tabla
                $sheet->insertNewRowBefore(1, 7);
                $sheet->setCellValue('C2', 'CONTROL DE MEDICAMENTOS');
                $sheet->setCellValue(
                    'C3',
                    ($this->info_medicamento['unidad'] === 'FARMACIA')
                        ? 'FARMACIA BIND CARD'
                        : 'ALMACEN DE MEDICAMENTOS BIND CARD'
                );

                $sheet->getStyle('C2')->getFont()->getColor()->setRGB('1A5276');
                $sheet->getStyle('C3')->getFont()->getColor()->setRGB('A93226');
                $sheet->getStyle('C2:C3')->getFont()->setBold(true);

                $sheet->setCellValue('G1', 'CÓDIGO: ');
                $sheet->mergeCells('G1:H1');
                $sheet->setCellValue('J1', $this->info_medicamento['cod_medicamento']);
                $sheet->setCellValue('D2', 'MEDICAMENTO: ');
                $sheet->setCellValue('E2', $this->info_medicamento['nombre_medicamento']);
                $sheet->setCellValue('D3', 'FORMA FARMACÉUTICA: ');
                $sheet->setCellValue('E3', $this->info_medicamento['forma_farmaceutica']);
                $sheet->setCellValue('D4', 'NÚMERO DE REGISTROS: ');
                $sheet->setCellValue('E4', $this->info_medicamento['numero_registros']);
                $sheet->setCellValue('A6', 'DETALLE DE MOVIMIENTOS DE MEDICAMENTOS');
                $sheet->mergeCells('A6:J6');
                $sheet->getStyle('A6:A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A6:A7')->getFont()->setBold(true)->setSize(14);

                $sheet->setCellValue('A7', 'DEL: ' . $this->info_medicamento['fecha_inicio'] . ' HASTA: ' . $this->info_medicamento['fecha_fin']);
                $sheet->mergeCells('A7:J7');

                // Negrita para la info del medicamento
                $sheet->getStyle('D2:D3:D4:G1')->getFont()->setBold(true);

                // --- Insertar imagen ---
                $drawing = new Drawing();
                $drawing->setName('Logo SSU');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('logo/ssu.png'));
                $drawing->setHeight(80); // altura en pixeles
                $drawing->setCoordinates('B2');
                $drawing->setWorksheet($sheet);
            },
        ];
    }
}
