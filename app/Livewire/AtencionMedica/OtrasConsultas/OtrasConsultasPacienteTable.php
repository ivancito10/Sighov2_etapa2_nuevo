<?php

namespace App\Livewire\AtencionMedica\OtrasConsultas;

use App\Models\ConsultaExterna\HistoriaClinica;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
use App\Services\EspecialidadService;
use PowerComponents\LivewirePowerGrid\Detail;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;

final class OtrasConsultasPacienteTable extends PowerGridComponent
{
    public $id_persona;
    public $datos_especialidad    = [];
    public $listEspecialidades, $listEspecialistas;
    protected $especialidadMap    = [];
    public $detalleAbiertoId = null;
    public string $sortDirection = 'desc';

    #[On('detailRow')]
    public function detailRow(int $rowId): void
    {
        $this->toggleDetail($rowId);
        // Si ya está abierto, ciérralo. Si no, ábrelo.
        $this->detalleAbiertoId = ($this->detalleAbiertoId === $rowId) ? null : $rowId;
    }

    public function datasource(): Collection
    {
        $especialidadService = new EspecialidadService();
        $this->listEspecialidades = $especialidadService->obtenerEspecialidades();
        $this->listEspecialistas = collect($especialidadService->obtenerEspecialistas())->pluck('persona_especialista', 'id');
        // dd($this->listEspecialidades,$this->listEspecialistas);
        $listEspecialidades = $this->listEspecialidades;
        $listEspecialistas = $this->listEspecialistas;

        return HistoriaClinica::query()
            ->with('usuarioCreador')
            ->where('id_persona', $this->id_persona['id'])
            ->where('reconsulta', false)
            ->where('estado_consulta', true)
            ->orderBy('id', 'desc')
            ->get()                         // carga la colección
            ->map(function ($model) use ($listEspecialidades, $listEspecialistas) {
                $model->especialidad = $this->listEspecialidades[$model->id_especialidad] ?? '';
                //  $model->especialista =
                //      ($this->listEspecialistas[$model->id_especialista]['nombres'] ?? '') . ' ' .
                //      ($this->listEspecialistas[$model->id_especialista]['p_apellido'] ?? '') . ' ' .
                //      ($this->listEspecialistas[$model->id_especialista]['s_apellido'] ?? '');
                //dd($model);
                return $model;
            });
    }

    public function setUp(): array
    {

        return [
            Detail::make()
                ->view('livewire.atencion-medica.consulta-externa.historia-clinica.historia-clinica-detail-row')
                //->showCollapseIcon(false)
                ->collapseOthers()
                ->params([
                    'listEspecialistas' => $this->listEspecialistas,
                    'listEspecialidades' => $this->listEspecialidades,
                ]),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('detail_button', function (HistoriaClinica $row) {
                $icono = ($this->detalleAbiertoId === $row->id)
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill=" #5dade2" class="bi bi-caret-up-fill" viewBox="0 0 16 16">
                        <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/>
                    </svg>'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill=" #2471a3 " class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                        <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>';

                return "<button 
                    wire:click=\"detailRow({$row->id})\" 
                    class=\"btn btn-sm btn-outline-transparent\">
                    {$icono}
                </button>";
            })

            ->add('especialidad')
            ->add('id_tipo_consulta', function ($row) {
                if ($row->id_tipo_consulta === 1) {
                    return 'Consulta Externa';
                } elseif ($row->id_tipo_consulta === 2) {
                    return 'Emergencias';
                } elseif ($row->id_tipo_consulta === 3) {
                    return 'C.R.R.';
                } elseif ($row->id_tipo_consulta === 4) {
                    return 'Hospitalizacion';
                } elseif ($row->id_tipo_consulta === 5) {
                    return 'Terapia Intensiva';
                } elseif ($row->id_tipo_consulta === 6) {
                    return 'Quirofano';
                } elseif ($row->id_tipo_consulta === 7) {
                    return 'Transcripcion';
                } elseif ($row->id_tipo_consulta === 8) {
                    return 'Atencion Externa';
                } elseif ($row->id_tipo_consulta === 9) {
                    return 'Hemodialisis';
                } else {
                    return 'Sin Definir';
                }
            })
            ->add('especialista', fn(HistoriaClinica $model) => $model->usuarioCreador->name)
            ->add('asistencia')
            ->add('fecha', fn($row) => \Carbon\Carbon::parse($row->fecha_hora)->format('d/m/Y'))
            ->add('hora', fn($row) => \Carbon\Carbon::parse($row->fecha_hora)->format('H:i'))
            //->add('id_especialista')
            ->add('asistencia', function ($row) {
                if ($row->asistencia) {
                    return '<span style="color: #229954" title="ASISTIO"> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-square-fill" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                    </svg>
                    </span>';
                } else {
                    return '<span style="color: #c62919" title="NO ASISTIO"> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
                    </svg>
                    </span>';
                }
            });
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title('')                           // sin encabezado
                ->field('detail_button')              // vincula con tu campo virtual
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALISTA', 'especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA', 'fecha')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('HORA', 'hora')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('TIPO ATENCIÓN', 'id_tipo_consulta')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center tablita-cuerpo-sin-mover')
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

    #[On('pdf_otros_ce_copia')]
    public function pdf_otros_ce_copia($rowId): void
    {
        $this->js('window.open("/consulta_externa/pdf/c_e_copia/' . $rowId . '", "_blank")');
    }

    #[On('recuperar_pdf_hc_otras_consultas')]
    public function recuperar_pdf_hc_otras_consultas($rowId): void
    {
        // $historiaClinica = HistoriaClinica::where('id_agenda', $rowId)->first();
        // $id_historia = $historiaClinica->id;
        //dd($historiaClinica);
        $this->js('window.open("/consulta_externa/pdf/copia_recuperada/' . $rowId . '", "_blank")');
    }

    public function actions(HistoriaClinica $row): array
    {
        $buttons[] = Button::add('ver_medicamento')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER HISTORIA CLINICA')
            ->dispatch('verHistoriaClinica', ['rowId' => $row->id]);

        $buttons[] = Button::add('reconsulta')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>')
            ->class('btn btn-orange')
            ->dispatch('RegistrarReconsulta', ['rowId' => $row->id, 'tipo_consulta' => $row->id_tipo_consulta])
            ->tooltip('REGISTRAR RECONSULTA');
        //->dispatchTo('atencion-medica.consulta-externa.historia-clinica.', 'ver_medicamento', ['data' => $row->id]);
        /* $buttons[] = Button::add('pdf')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
            <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
            <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
            </svg>')
            ->class('btn btn-red')
            ->dispatch('pdf_otros_ce_copia', ['rowId' => $row->id])
            ->tooltip('COPIA HISTORIA CLÍNICA'); */


        $buttons[] = Button::add('pdf')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
            <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
            <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
            </svg>')
            ->class('btn btn-blue')
            ->dispatch('recuperar_pdf_hc_otras_consultas', ['rowId' => $row->id])
            ->tooltip('COPIA HISTORIA CLÍNICA');
        //->can(allowed: auth()->user()->can('pdf.historia_clinica_original'));

        return $buttons;
    }
}
