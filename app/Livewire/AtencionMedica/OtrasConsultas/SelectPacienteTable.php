<?php

namespace App\Livewire\AtencionMedica\OtrasConsultas;

use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use Illuminate\Support\Carbon;
//use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Button;

final class SelectPacienteTable extends PowerGridComponent
{   
    //public string $sortField = 'nombre_completo';
    public string $sortDirection = 'asc';

    // public function datasource(): ?Collection
    // {
    //     $token = session('access_token', '');

    //     // Realiza la llamada a la API y recibe los datos
    //     $link = LinkServicio::where('nombre_servicio','POBLACION ASEGURADA - BASICO')->pluck('link_servicio')->first();
    //     //dd($link);
    //     $response = Http::withToken($token)->get($link);
    //     //dd($response);
    //     $data = $response->json('data');
    //     //dd($data);
    //     $mappedData = collect($data)->map(function ($item) {
    //         // Concatenar nombres y apellidos
    //         $item['nombre_completo'] = $item['nombres'] . ' ' . $item['p_apellido'] . ' ' . $item['s_apellido'];
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
            //->add('nombre_completo')
            ->add('nombres', function($row) {
                return ($row->nombres ? $row->nombres : '').' '.($row->p_apellido ? $row->p_apellido : '').' '. 
                ($row->s_apellido ? $row->s_apellido : '');
            })
            ->add('matricula_seguro')
            ->add('sexo')
            ->add('fecha_nacimiento', function($row) {
                return Carbon::parse($row->fecha_nacimiento)->format('d/m/Y');
            })
            ->add('ci');
    }

    public function columns(): array
    {
        return [
            // Column::make('#', 'id')
            //     ->index()
            //     ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
            //     ->bodyAttribute('text-center')
            //     ->sortable()
            //     ->searchable(),

            Column::make('CARNET', 'ci')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchableRaw('ci')
                ->sortable(),

            Column::make('MATRÍCULA', 'matricula_seguro')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchableRaw('matricula_seguro')
                ->sortable(),
            Column::make('NOMBRE DEL PACIENTE', 'nombres')
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
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>')
                ->class('btn btn-green')
                ->tooltip('SELECCIONAR')
                ->dispatch('registrarPacienteSelect', ['data' => $row]),
        ];

        return $buttons;
    }
}
