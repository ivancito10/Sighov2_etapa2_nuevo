<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\BajaMedica;

use App\Models\ConsultaExterna\BajaMedica;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class BajaMedicaTable extends PowerGridComponent
{
    use WithExport;
    public  $id_persona;
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {

        return BajaMedica::query()
            ->with(['especialista.persona', 'tipoBajaMedicaBajaMedica'])
            ->where('id_persona', $this->id_persona);
    }

    public function relationSearch(): array
    {
        return [];
        /*
        return [
            'especialista.persona' => [
                'nombres',
                'p_apellido',
                's_apellido',
            ],
            'tipoBajaMedicaBajaMedica' => [
                'nombre',
            ],
        ];*/
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nro_baja_medica')
            ->add('nro_baja_con_anio', function (BajaMedica $model) {
                if (!$model->nro_baja_medica) {
                    return '-';
                }

                $fechaBase = $model->fecha_ini_baja ?? $model->created_at;
                $anio = $fechaBase ? \Carbon\Carbon::parse($fechaBase)->format('Y') : date('Y');

                return "{$model->nro_baja_medica}/{$anio}";
            })

            ->add('nombre_especialista', function (BajaMedica $model) {
                $persona = $model->especialista?->persona;
                if (!$persona) {
                    return '-';
                }
                return trim("{$persona->nombres} {$persona->p_apellido} {$persona->s_apellido}");
            })
            ->add('id_tipo_baja')
            ->add('nombre_tipo_baja', function (BajaMedica $model) {
                return $model->tipoBajaMedicaBajaMedica?->nombre ?? 'Sin Tipo';
            })

            ->add('fecha_ini_baja')
            ->add('fecha_fin_baja')
            ->add('fecha_ini_baja_formatted', function (BajaMedica $model) {
                return $model->fecha_ini_baja
                    ? \Carbon\Carbon::parse($model->fecha_ini_baja)->format('d/m/Y')
                    : '-';
            })
            ->add('fecha_fin_baja_formatted', function (BajaMedica $model) {
                return $model->fecha_fin_baja
                    ? \Carbon\Carbon::parse($model->fecha_fin_baja)->format('d/m/Y')
                    : '-';
            })

            ->add('id_persona')
            ->add('dias_baja')
            ->add('estado')
            ->add('estado_formated', function ($dato) {
                if ($dato->estado)
                    return '<p class="p-2" style="display: inline-block; background-color: #1E8449; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     VALIDADO </p>';
                else {
                    return '<p class="p-2" style="display: inline-block; background-color: #A93226; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     ANULADO </p>';
                }
            })

            ->add('descripcion')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('motivo_anulacion')
            ->add('nro_resolucion_retroactivo')
            ->add('fecha_anulacion')
            ->add('id_usuario_anula')
            ->add('id_especialidad')
            ->add('id_especialista')
            ->add('id_institucion')
            ->add('created_at');
    }

    public function columns(): array
    {
        //dd($this->id_persona);
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('NRO BAJA MÉDICA', 'nro_baja_con_anio', 'nro_baja_medica')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('ESPECIALISTA', 'nombre_especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('FECHA INICIO', 'fecha_ini_baja_formatted', 'fecha_ini_baja')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),


            Column::make('FECHA FIN', 'fecha_fin_baja_formatted', 'fecha_fin_baja')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('TIPO BAJA', 'nombre_tipo_baja')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('DIAS BAJA', 'dias_baja')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('ESTADO', 'estado_formated')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::action('OPCIONES')->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    #[\Livewire\Attributes\On('pdf_baja')]
    public function pdf_baja($rowId): void
    {
        //dd('hola eblalbl');
        // $this->js('alert(' . $rowId . ')');
        $this->js('window.open("/consulta_externa/pdf/baja_medica/' . $rowId . '", "_blank")');
    }

    public function actions(BajaMedica $row): array
    {
        $id = $row->id ?? null;
        return [
            Button::add('pdf_baja_medica')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
            <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
            <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
            </svg>')
                ->class('btn btn-red')
                //->dispatch('ver_pdf_laboratorio', ['rowId' => $row->id])
                ->dispatch('pdf_baja', ['rowId' => $id])
                ->tooltip('VER PDF')
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
