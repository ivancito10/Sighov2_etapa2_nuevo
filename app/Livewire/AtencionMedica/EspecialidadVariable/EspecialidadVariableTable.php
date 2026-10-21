<?php

namespace App\Livewire\AtencionMedica\EspecialidadVariable;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Services\EspecialidadService;
use PowerComponents\LivewirePowerGrid\Button;

final class EspecialidadVariableTable extends PowerGridComponent
{
    public function datasource(): ?Collection
    {
        $service = new EspecialidadService();
        $especialidades = $service->obtenerEspecialidades();

        return $especialidades->map(function($nombre, $id) {
            return (object) [
                'id' => $id,
                'especialidad' => $nombre,
            ];
        })->sortBy('especialidad')->values();
    }

    public string $sortField = 'especialidad';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        //$this->showCheckBox();

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
            ->add('especialidad')
            ->add('price');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center') 
                ->index(), 

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
            ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
        ];
    }
    public function actions($row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
            </svg>')
            ->id()
            ->class('btn btn-blue')
            ->dispatchTo('atencion-medica.especialidad-variable.especialidad-variable-main', 'verEspVariables', ['data' => $row->id])
            ->tooltip('VER');

        $buttons[] = Button::add('agregar')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>')
                    ->class('btn btn-orange')
                    ->dispatchTo('atencion-medica.especialidad-variable.especialidad-variable-main', 'registrarEspVariables', ['data' => $row->id])
                    ->tooltip('AGREGAR VARIABLES');
            return $buttons;        
    }
        
        


}
