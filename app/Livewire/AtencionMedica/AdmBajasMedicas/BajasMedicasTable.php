<?php

namespace App\Livewire\AtencionMedica\AdmBajasMedicas;

use App\Models\ConsultaExterna\BajaMedica;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class BajasMedicasTable extends PowerGridComponent
{
    use WithExport;

    // IMPORTANTE: al ser Collection, usa campos SIN prefijo de tabla
    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public string $tableName = 'AdmMedBajaMedicaTable';

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

    /**
     * ✅ AHORA ES COLLECTION (búsqueda/sort/paginado en PHP)
     */
    public function datasource(): Collection
    {
        $rows = BajaMedica::with([
            'tipoBajaMedicaBajaMedica:id,nombre',
            'bajaMedicaPersona:id,ci,complemento,matricula_seguro,p_apellido,s_apellido,nombres',
        ])
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        return $rows->map(function (BajaMedica $dato) {
            $gestion = $dato->created_at ? Carbon::parse($dato->created_at)->format('Y') : '';

            $fechaIni = $dato->fecha_ini_baja
                ? Carbon::parse($dato->fecha_ini_baja)->format('d/m/Y')
                : '';

            $fechaFin = $dato->fecha_fin_baja
                ? Carbon::parse($dato->fecha_fin_baja)->format('d/m/Y')
                : '';

            $tipoNombre = optional($dato->tipoBajaMedicaBajaMedica)->nombre ?? '';

            $ci = optional($dato->bajaMedicaPersona)->ci ?? '';
            $comp = optional($dato->bajaMedicaPersona)->complemento ?? '';
            $matricula = optional($dato->bajaMedicaPersona)->matricula_seguro ?? '';

            $p = optional($dato->bajaMedicaPersona)->p_apellido ?? '';
            $s = optional($dato->bajaMedicaPersona)->s_apellido ?? '';
            $n = optional($dato->bajaMedicaPersona)->nombres ?? '';
            // $nombreAsegurado = trim("$p $s $n");
            $nombreAsegurado = collect([$p, $s, $n])
                ->filter()
                ->implode(' ');
            // Estado (HTML)
            // $estadoHtml = '';
            // if ($dato->estado === true) {
            //     $estadoHtml = '<p class="p-2" style="display:inline-block;background-color:#1E8449;color:white;font-weight:bold;border-radius:20px;font-size:0.8rem;">VALIDADO</p>';
            // } elseif ($dato->estado === false) {
            //     $estadoHtml = '<p class="p-2" style="display:inline-block;background-color:#E67E22;color:white;font-weight:bold;border-radius:20px;font-size:0.8rem;">PENDIENTE</p>';
            // } elseif ($dato->estado === null) {
            //     $estadoHtml = '<p class="p-2" style="display:inline-block;background-color:#A93226;color:white;font-weight:bold;border-radius:20px;font-size:0.8rem;">ANULADO</p>';
            // }
            $estadoTexto = $dato->estado === true
                ? 'VALIDADO'
                : ($dato->estado === false ? 'PENDIENTE' : 'ANULADO');

            return (object)  [
                'id' => $dato->id,
                'id_tipo_baja' => $dato->id_tipo_baja,
                'id_persona' => $dato->id_persona,

                'nro_baja_medica' => $dato->nro_baja_medica,
                'nro_baja_medica_gestion' => "{$dato->nro_baja_medica} / {$gestion}",

                'fecha_ini_baja' => $fechaIni,
                'fecha_fin_baja' => $dato->fecha_fin_baja,
                'fecha_fin_baja_format' => $fechaFin,

                'carnet_completo' => trim("{$ci} {$comp}"),
                'matricula' => $matricula,
                'nombre_asegurado' => $nombreAsegurado,

                'dias_baja' => $dato->dias_baja,

                'id_tipo_baja_format' => $tipoNombre,

                // HTML para mostrar
                'estado_formated' => $estadoTexto,

                // (Opcional) texto plano para búsqueda si luego quieres usarlo
                'estado_text' => $dato->estado === true ? 'VALIDADO' : ($dato->estado === false ? 'PENDIENTE' : 'ANULADO'),

                'descripcion' => $dato->descripcion,
                'id_user_created' => $dato->id_user_created,
                'id_user_updated' => $dato->id_user_updated,
                'created_at' => $dato->created_at,
                'estado' => $dato->estado, // para reglas/acciones

                'ci' => (string) $ci,
                'complemento' => (string) $comp,
                'carnet_busqueda' => trim($ci . $comp),
            ];
        });
    }


    public function relationSearch(): array
    {
        return [
            // 'bajaMedicaPersona' => ['ci']
        ];
    }

    /**
     * ✅ fields() sin closures, todo ya viene armado desde datasource()
     */
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id_tipo_baja')
            ->add('id_tipo_baja_format')
            ->add('nro_baja_medica')
            ->add('nro_baja_medica_gestion')
            ->add('fecha_ini_baja')
            ->add('fecha_fin_baja')
            ->add('fecha_fin_baja_format')
            ->add('id_persona')
            ->add('carnet_completo')
            ->add('matricula')
            ->add('nombre_asegurado')
            ->add('ci')
            ->add('dias_baja')
            // ->add('estado_formated')
            ->add('estado_formated', function ($dato) {
                if ($dato->estado_text === 'VALIDADO')
                    return '<p class="p-2" style="display: inline-block; background-color: #1E8449; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     VALIDADO </p>';
                elseif ($dato->estado_text === 'PENDIENTE') {
                    return '<p class="p-2" style="display: inline-block; background-color: #E67E22; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     PENDIENTE </p>';
                } elseif ($dato->estado_text === 'ANULADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #A93226; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     ANULADO </p>';
                }
            })
            /*  ->add('estado_formated', function ($dato) {
                if ($dato->estado === true) {
                    return '<p class="p-2" style="display: inline-block; background-color: #1E8449; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     VALIDADO </p>';
                } elseif ($dato->estado === false) {
                    return '<p class="p-2" style="display: inline-block; background-color: #E67E22; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     PENDIENTE </p>';
                } elseif ($dato->estado === null) {
                    return '<p class="p-2" style="display: inline-block; background-color: #A93226; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     ANULADO </p>';
                }
            }) */
            ->add('estado_text')
            ->add('descripcion')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('estado');
    }

    public function columns(): array
    {
        return [
            Column::make('# bb', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('NRO. BAJA MÉDICA', 'nro_baja_medica_gestion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('CARNET', 'carnet_completo')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('MATRÍCULA', 'matricula')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('PACIENTE', 'nombre_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->searchable(),

            Column::make('FECHA INICIO', 'fecha_ini_baja')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('FECHA FIN', 'fecha_fin_baja_format')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('DÍAS BAJA', 'dias_baja')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('TIPO DE BAJA', 'id_tipo_baja_format')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->searchable(),

            Column::make('ESTADO', 'estado_formated')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                // Si tu PowerGrid permite campo alterno:
                // ->searchable('estado_text')
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left'),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('pdf_baja')]
    public function pdf_baja($rowId): void
    {
        //dd('hola eblalbl');
        // $this->js('alert(' . $rowId . ')');
        $this->js('window.open("/consulta_externa/pdf/baja_medica/' . $rowId . '", "_blank")');
    }

    /**
     * ✅ Ya no tipamos BajaMedica $row porque viene desde Collection (array)
     */
    public function actions($row): array
    {
        // $row es array
        $id = $row->id ?? null;
        $estado = $row->estado ?? null;

        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER BAJA MÉDICA')
            ->dispatchTo('atencion-medica.adm-bajas-medicas.adm-bajas-medicas-main', 'ver_baja', ['data' => $id])
            ->can(allowed: auth()->user()->can('ver.bajas_medicas'));


        if ($estado !== null) {
            $buttons[] = Button::add('anular_baja')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-journal-x" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6.146 6.146a.5.5 0 0 1 .708 0L8 7.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 8l1.147 1.146a.5.5 0 0 1-.708.708L8 8.707 6.854 9.854a.5.5 0 0 1-.708-.708L7.293 8 6.146 6.854a.5.5 0 0 1 0-.708"/>
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                </svg>')
                ->class('btn btn-red')
                ->tooltip('ANULAR BAJA')
                ->dispatchTo('atencion-medica.adm-bajas-medicas.adm-bajas-medicas-main', 'anular_baja', ['data' => $id])
                ->can(allowed: auth()->user()->can('anular.bajas_medicas'));

            $buttons[] = Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>')
                ->class('btn btn-orange')
                ->tooltip('EDITAR BAJA')
                // ->dispatchTo('atencion-medica.adm-bajas-medicas.adm-bajas-medicas-main', 'editar_baja', ['data' => $id])  ;             
                ->dispatchTo('atencion-medica.adm-bajas-medicas.baja-medica-edit', 'editar_baja', ['data' => $id])
                ->can(allowed: auth()->user()->can('super-admin-only'));
            //->can(allowed: auth()->user()->can('anular.bajas_medicas'));
        }


        $buttons[] = Button::add('pdf')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                    </svg>')
            ->class('btn btn-red')
            ->tooltip('VER BAJA MÉDICA')
            ->dispatch('pdf_baja', ['rowId' => $id])
            //->can(allowed: auth()->user()->can('ver.bajas_medicas'));
            ->can(allowed: auth()->user()->can('pdf.baja_medica'));


        return $buttons;
    }
}
