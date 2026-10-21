<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Agenda;

use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use App\Services\ObtenerToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Button;

final class AgendaExtraSelect extends PowerGridComponent
{   
    //public string $sortField = 'nombre_completo';
    //public string $sortDirection = 'asc';

    // public function datasource(): ?Collection
    // {   
    //     $datos_token = new ObtenerToken();
    //     $datos_token->refresh_token();
    //     //dd($token_nuevo);
    //     $token = session('access_token', '');

    //     // Realiza la llamada a la API y recibe los datos
    //     $link = LinkServicio::where('nombre_servicio','POBLACION ASEGURADA - BASICO')->pluck('link_servicio')->first();
    //     $response = Http::withToken($token)
    //         ->get($link);
    //     //dd($token,$response,$response->json());
    //     $data = $response->json('data');
    //     $mappedData = collect($data)->map(function ($item) {
    //         $item['nombre_completo'] = $item['p_apellido'] . ' ' . $item['s_apellido']. ' ' .$item['nombres'];
    //         return $item;
    //     });

    //     return $mappedData;
    // }

    public function datasource() : Builder  
    {
        return Persona::where('id_tipo_asegurado', '!=', 0)
            ->where('afiliado', true)
            ->orderBy('id', 'desc');
    }


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

     public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            //->add('id_persona')
            ->add('nombre', fn($model) => $model->p_apellido.' '.$model->s_apellido.' '. $model->nombres )
            ->add('matricula_seguro')
            ->add('sexo')
            ->add('fecha_nacimiento', fn($model) => Carbon::parse($model->fecha_nacimiento)->format('d/m/Y'))
            ->add('ci');
    }

    public function columns(): array
    {
        return [
            
            Column::make('C.I.', 'ci')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchableRaw('ci')
                ->sortable(),

            Column::make('MATRÍCULA', 'matricula_seguro')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchableRaw('matricula_seguro')
                ->sortable(),
                
            Column::make('NOMBRE DEL PACIENTE', 'nombre')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchableRaw("CONCAT(p_apellido, ' ', s_apellido, ' ', nombres)")
                ->sortable(),
    

            Column::make('SEXO', 'sexo')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')            
                ->sortable(),

            Column::make('FECHA DE NACIMIENTO', 'fecha_nacimiento')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::action('OPCIONES') // Acción en cada fila, si necesitas agregar alguna
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center tablita-cuerpo-sin-mover')
        ];
    }
    public function actions($row): array
    {
        $buttons = [
            Button::add('agenda anticipada')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-index-thumb-fill" viewBox="0 0 16 16">
                <path d="M8.5 1.75v2.716l.047-.002c.312-.012.742-.016 1.051.046.28.056.543.18.738.288.273.152.456.385.56.642l.132-.012c.312-.024.794-.038 1.158.108.37.148.689.487.88.716q.113.137.195.248h.582a2 2 0 0 1 1.99 2.199l-.272 2.715a3.5 3.5 0 0 1-.444 1.389l-1.395 2.441A1.5 1.5 0 0 1 12.42 16H6.118a1.5 1.5 0 0 1-1.342-.83l-1.215-2.43L1.07 8.589a1.517 1.517 0 0 1 2.373-1.852L5 8.293V1.75a1.75 1.75 0 0 1 3.5 0"/>
                </svg>')
                ->class('btn btn-green')
                ->tooltip('SELECCIONAR')
                ->dispatch('registrarAgendaExtraSelect', ['data' => $row]),
        ];

        return $buttons;
    }
}
