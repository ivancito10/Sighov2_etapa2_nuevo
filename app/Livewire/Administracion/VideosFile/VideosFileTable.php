<?php

namespace App\Livewire\Administracion\VideosFile;

use App\Models\Administracion\Videos;
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

final class VideosFileTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'videosTable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        // $this->showCheckBox();

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
        return Videos::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('nombre')
            ->add('titulo')
            ->add('descripcion')
            ->add('ruta')
            ->add('permanente', function ($model) {
                if ($model->permanente) {
                    return '<span style="color:#0342A3" >
                  <b> PERMANENTE </b>
                    </span>';
                } else {
                    return '<span style="color:green" >
                 <b>  TEMPORAL </b>
                    </span>';
                }
            })
            // ->add('fecha_inicio_formatted', fn(Videos $model) => Carbon::parse($model->fecha_inicio)->format('d/m/Y'))
            ->add('fecha_inicio_formatted', function ($model) {
                if ($model->fecha_inicio) {
                    return Carbon::parse($model->fecha_inicio)->format('d/m/Y');
                } else {
                    return 'Sin dato';
                }
            })
            //  ->add('fecha_fin_formatted', fn(Videos $model) => Carbon::parse($model->fecha_fin)->format('d/m/Y'))
            ->add('fecha_fin_formatted', function ($model) {
                if ($model->fecha_fin) {
                    return Carbon::parse($model->fecha_fin)->format('d/m/Y');
                } else {
                    return 'Sin dato';
                }
            })

            ->add('externo', function ($model) {
                if ($model->externo) {
                    return 'EXTERNO';
                } else {
                    return 'INTERNO';
                }
            })
            ->add('estado', function ($model) {
                if ($model->estado) {
                    return '<span style="color:#499932" >
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg>
                    </span>';
                } else {
                    return '<span style="color:red" >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
</svg>
                    </span>';
                }
            })
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->index()
                ->sortable()
                ->searchable(),

            Column::make('TÍTULO', 'titulo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('TIPO', 'externo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('DURACIÓN', 'permanente')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA INICIO', 'fecha_inicio_formatted', 'fecha_inicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('FECHA FIN', 'fecha_fin_formatted', 'fecha_fin')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),


            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
            //      Filter::datepicker('fecha_inicio'),
            //      Filter::datepicker('fecha_fin'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Videos $row): array
    {
        $buttons = [];
        $buttons[] =   Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
            </svg>')
            ->class('btn btn-blue')
            ->route('administracion.videos.ver', ['id' => $row->id])
            ->tooltip('VER DETALLES DE VIDEO');


        $buttons[] =   Button::add('edit')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
</svg>')
            ->class('btn btn-orange')
            ->route('administracion.videos.edit', ['id' => $row->id])
            ->tooltip('EDITAR DETALLES DE VIDEO');

        if ($row->estado) {
            $buttons[] =  Button::add('inhabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->class('btn btn-red')
                ->tooltip('INHABILITAR VIDEO')
                ->dispatchTo('administracion.videos-file.videos-videos-main', 'cambiarEstadoVideo', ['data' => $row->id]);
        } else {
            $buttons[] =  Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>')
                ->class('btn btn-green')
                ->tooltip('HABILITAR VIDEO')
                ->dispatchTo('administracion.videos-file.videos-videos-main', 'cambiarEstadoVideo', ['data' => $row->id]);
        }

        return $buttons;
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
