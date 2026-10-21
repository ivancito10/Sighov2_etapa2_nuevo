<?php

namespace App\Livewire\Administracion\Notificaciones;

use App\Models\Administracion\NotificacionUsuarios;
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
use App\Livewire\Administracion\Notificaciones\NotificacionesListado;

// ...


final class NotificacionesTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'NotificacionesTable';
    public bool $multiSort = true; 

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }
    

    public function datasource(): Builder
    {
        $userId = auth()->check() ? auth()->user()->id : null;

        return NotificacionUsuarios::query()
            ->with('emisor') 
            ->when($userId, function ($query, $userId) {
                $query->where('id_usu_receptor', $userId);
            });
    }

    public function relationSearch(): array
    {
        return [
            'emisor' => [
            'name' 
        ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('estado', function ($estado) {
                if ($estado->estado === 'ABIERTO') {
                    return '<svg viewBox="-4 0 512 512.00051" xmlns="http://www.w3.org/2000/svg" style="width: 25px; height: 25px;">
                            <path d="m488.34375 144.070312h-473.277344c-8.320312 0-15.066406 6.746094-15.066406 15.066407v337.800781c0 8.320312 6.746094 15.0625 15.066406 15.0625h473.277344c8.320312 0 15.0625-6.742188 15.0625-15.0625v-337.800781c0-8.320313-6.742188-15.066407-15.0625-15.066407zm0 0" fill="#ffd561"/>
                            <path d="m488.34375 144.070312h-232.957031v367.929688h232.957031c8.320312 0 15.0625-6.742188 15.0625-15.0625v-337.800781c0-8.320313-6.742188-15.066407-15.0625-15.066407zm0 0" fill="#ffbf31"/>
                            <path d="m502.871094 155.144531c-1.796875-6.539062-7.746094-11.074219-14.527344-11.074219h-473.277344c-6.722656 0-12.632812 4.457032-14.480468 10.917969-1.851563 6.460938.800781 13.367188 6.503906 16.925781l240.460937 145.214844c2.4375 1.519532 5.207031 2.28125 7.976563 2.28125 2.660156 0 5.320312-.703125 7.695312-2.113281l232.816406-145.214844c5.832032-3.460937 8.625-10.398437 6.832032-16.9375zm0 0" fill="#efa91d"/>
                            <path d="m502.871094 155.144531c-1.796875-6.539062-7.746094-11.074219-14.527344-11.074219h-232.957031v175.335938c.046875 0 .09375.007812.140625.007812 2.660156 0 5.320312-.707031 7.695312-2.113281l232.816406-145.21875c5.832032-3.460937 8.625-10.398437 6.832032-16.9375zm0 0" fill="#f79200"/>
                            <path d="m496.320312 146.355469-230.871093-144.074219c-4.777344-2.980469-10.824219-3.046875-15.671875-.167969l-242.410156 144.074219c-5.828126 3.460938-8.625 10.398438-6.828126 16.9375 1.796876 6.539062 7.742188 11.074219 14.527344 11.074219h473.277344c6.722656 0 12.628906-4.457031 14.480469-10.917969 1.851562-6.460938-.800781-13.367188-6.503907-16.925781zm0 0" fill="#fccb4f"/>
                            <path d="m496.320312 146.355469-230.871093-144.074219c-3.050781-1.902344-6.621094-2.609375-10.0625-2.128906v174.046875h232.957031c6.722656 0 12.628906-4.457031 14.480469-10.917969 1.851562-6.460938-.800781-13.367188-6.503907-16.925781zm0 0" fill="#efa91d"/>
                        </svg> Abierto'; 
                } elseif ($estado->estado === 'LEIDO') {
                    return '<svg id="Capa_1" enable-background="new 0 0 512 512" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" style="width: 28px; height: 28px;"><g><g>
                    <path d="m497 181h-241-241c-8.291 0-15 6.709-15 15v270c0 8.291 6.709 15 15 15h241 241c8.291 0 15-6.709 15-15v-270c0-8.291-6.709-15-15-15z" fill="#97de3d" style="fill: rgb(121, 214, 0);"></path>
                    <path d="m512 466v-270c0-8.291-6.709-15-15-15h-241v300h241c8.291 0 15-6.709 15-15z" fill="#59c36a"></path><path d="m412.694 417.646-92.314-75c-6.431-5.229-7.397-14.678-2.183-21.108 5.259-6.401 14.692-7.368 21.108-2.183l92.314 75c6.431 5.229 7.397 14.678 2.183 21.108-5.242 6.431-14.677 7.395-21.108 2.183z" fill="#149084"></path>
                    <path d="m78.197 415.463c-5.215-6.431-4.248-15.879 2.183-21.108l92.314-75c6.46-5.229 15.864-4.248 21.108 2.183 5.215 6.431 4.248 15.879-2.183 21.108l-92.314 75c-6.406 5.192-15.846 4.271-21.108-2.183z" fill="#59c36a"></path><path d="m433.803 246.537c-5.229-6.416-14.663-7.412-21.108-2.183l-156.695 127.325-156.694-127.325c-6.401-5.2-15.85-4.233-21.108 2.183-2.256 2.783-3.354 6.123-3.354 9.434 0 4.365 1.89 8.701 5.537 11.675l166.157 135c.015 0 .029.015.044.029 2.444 1.979 5.889 3.094 9.419 3.115 3.426.02 6.932-.989 9.683-3.334l165.938-134.81c6.429-5.23 7.396-14.678 2.181-21.109z" fill="#59c36a"></path>
                    <path d="m431.62 267.646c6.431-5.229 7.397-14.678 2.183-21.108-5.229-6.416-14.663-7.412-21.108-2.183l-156.695 127.324v34.111c3.426.02 6.932-.989 9.683-3.334z" fill="#149084"></path></g><path d="m388.876 128.31c-2.373-3.97-58.945-97.31-132.876-97.31s-130.503 93.34-132.876 97.31c-2.827 4.731-2.827 10.649 0 15.381 2.373 3.969 58.945 97.309 132.876 97.309s130.503-93.34 132.876-97.31c2.827-4.731 2.827-10.649 0-15.38z" fill="#f0f7ff"></path><path d="m388.876 143.69c2.827-4.731 2.827-10.649 0-15.381-2.373-3.969-58.945-97.309-132.876-97.309v210c73.931 0 130.503-93.34 132.876-97.31z" fill="#dfe7f4"></path>
                    <circle cx="256" cy="136" fill="#3aaaff" r="45"></circle><path d="m301 136c0-24.814-20.186-45-45-45v90c24.814 0 45-20.186 45-45z" fill="#6b77e8"></path><circle cx="256" cy="136" fill="#474f54" r="15"></circle><path d="m271 136c0-8.284-6.716-15-15-15v30c8.284 0 15-6.716 15-15z" fill="#32393f"></path></g></svg> Visto'; 
                } else {
                    return '<span style="color: red;">&#x2709; No leído</span>'; 
                }
            })
            ->add('prioridades', function ($estado) {
                if ($estado->prioridades === 1) {
                    // Círculo verde para Baja prioridad
                    return '<span style="display: inline-block; width: 15px; height: 15px; background-color: green; border-radius: 50%; margin-right: 5px;"></span><span>Baja</span>';
                } elseif ($estado->prioridades === 2) {
                    // Círculo amarillo para Media prioridad
                    return '<span style="display: inline-block; width: 15px; height: 15px; background-color: yellow; border-radius: 50%; margin-right: 5px;"></span><span>Moderada</span>';
                } else {
                    // Círculo rojo para Alta prioridad
                    return '<span style="display: inline-block; width: 15px; height: 15px; background-color: red; border-radius: 50%; margin-right: 5px;"></span><span>Alta</span>';
                }
            })
            
            ->add('id_usu_emisor', function ($row) {
                return $row->emisor->name ?? 'N/A'; // Accede al nombre del emisor
            })
            ->add('id_usu_receptor')
            ->add('fecha', function ($row) {
                return Carbon::parse($row->fecha_hora_enviada)->format('Y-m-d'); // Extrae solo la fecha
            })
            ->add('hora', function ($row) {
                return Carbon::parse($row->fecha_hora_enviada)->format('H:i:s'); // Extrae solo la hora
            })
            ->add('fecha_hora_leida')
            ->add('fecha_hora_abierto')
            ->add('asunto')
            ->add('mensaje')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('N°', 'id')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
            ->bodyAttribute('text-center')
                ->index(),

            Column::make('Asunto', 'asunto')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
            ->bodyAttribute()
            ->sortable()
            ->searchable(),

            Column::make('Emisor', 'id_usu_emisor')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'estado')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Prioridades', 'prioridades')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'fecha') // Nueva columna para la fecha
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
    
            Column::make('Hora', 'hora') // Nueva columna para la hora
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            /*
            Column::make('Fecha hora leida', 'fecha_hora_leida')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::make('Fecha hora abierto', 'fecha_hora_abierto')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            */
            Column::action('Acciones')
            ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3 ; color:white;')
            ->bodyAttribute('text-center'),
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(NotificacionUsuarios $row): array
    {   
        return [
            
            $buttons[] =Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER NOTIFICACION')
            ->dispatchTo('administracion.notificaciones.notificaciones-historial', 'verNotificacion', ['data' => $row->id])
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
