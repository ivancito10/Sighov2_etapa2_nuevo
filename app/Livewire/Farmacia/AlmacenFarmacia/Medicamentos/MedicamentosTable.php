<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\Medicamentos;

use App\Models\Farmacia\Medicamento;
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
use Illuminate\Database\Eloquent\Collection;
use App\Services\ObtenerCuentaContablePorId;

final class MedicamentosTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'MedicamentosTable';
    public string $sortField = 'codigo_medicamento';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        //$this->showCheckBox();

        return [
            /*Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),*/
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*public function obtenerCuentaContable($id_cuenta)
    {
        $obtenerDatosUnidad = new ObtenerCuentaContablePorId();
        return $obtenerDatosUnidad->obtenerCuentaContablePorId($id_cuenta);
    }*/

    public function datasource(): Collection
    {
        $medicamentos = Medicamento::all(); // Obtiene todos los medicamentos
        /*$medicamentos = $medicamentos->map(function ($medicamento) {
            $personaData = $this->obtenerCuentaContable($medicamento->id_cuenta_contable);
            $medicamento->cuenta_contable = $personaData['codigo'] ?? 'SIN DATO';
            return $medicamento;
        });*/
        return $medicamentos; // Retorna la colección transformada
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('codigo_medicamento')
            ->add('nombre')
            ->add('forma_farmaceutica')
            ->add('concentracion')
            ->add('restringido', function ($date) {
                if ($date->restringido) {
                    return '<span style="color:#1e8449 ;"><b>SI </b></span>';
                } else {
                    return '<span style="color:#b71c1c ;"><b> NO</b></span>';
                }
            })
            ->add('estado')
            ->add('estado_validado', function ($estado) {
                if ($estado->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg></span>';
                }
            })
            ->add('id_cuenta_contable')
            /*->add('cuenta_contable', function ($medicamento) {
                return $medicamento->cuenta_contable;
            })*/
            ->add('habilitado_farmacia')
            ->add('estado_farmacia', function ($estado_habilitado) {
                if ($estado_habilitado->habilitado_farmacia) {
                    return '<span style="color:#1e8449 ;"><b>ACTIVO </b></span>';
                } else {
                    return '<span style="color:#b71c1c ;"><b> CERRADO</b></span>';
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
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),


            Column::make('COD. MEDICAMENTO', 'codigo_medicamento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MEDICAMENTO', 'nombre')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('style', 'max-width: 500px; white-space: normal; word-wrap: break-word;')
                ->sortable()
                ->searchable(),

            Column::make('FORMA FARMACÉUTICA', 'forma_farmaceutica')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CONCENTRACIÓN', 'concentracion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('style', 'max-width: 200px; white-space: normal; word-wrap: break-word;')
                ->sortable()
                ->searchable(),

            Column::make('RESTRINGIDO', 'restringido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            /*Column::make('CUENTA CONTABLE', 'cuenta_contable')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-right')
                ->sortable()
                ->searchable(),*/

            Column::make('ESTADO', 'estado_validado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ACTIVO', 'estado_farmacia')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function actions(Medicamento $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver_medicamento')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER MEDICAMENTO')
            ->dispatchTo('farmacia.almacen-farmacia.medicamentos.medicamentos-main', 'ver_medicamento', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.medicamentos'));


        if ($row->estado == true) {
            $buttons[] = Button::add('editar_medicamento')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>')
                ->class('btn btn-orange')
                ->tooltip('MODIFICAR MEDICAMENTO')
                ->dispatchTo('farmacia.almacen-farmacia.medicamentos.medicamentos-edit', 'editarMedicamento', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('editar.medicamentos'));


            $buttons[] = Button::add('max_min_med')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-data" viewBox="0 0 16 16">
                    <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0z"/>
                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                    </svg>')
                ->class('btn btn-yellow')
                ->tooltip('MAXIMO MINIMO POR SERVICIO')
                ->dispatchTo('farmacia.almacen-farmacia.medicamentos.medicamentos-max-min', 'maxMinMedicamento', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.medicamentos'));

            if ($row->habilitado_farmacia == true) {
                $buttons[] = Button::add('habilitado_farmacia')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-capsule" viewBox="0 0 16 16">
                    <path d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z"/>
                    </svg>')
                    ->class('btn btn-green')
                    ->tooltip('DESHABILITAR')
                    ->dispatchTo('farmacia.almacen-farmacia.medicamentos.medicamentos-main', 'habilitadoFarmacia', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('ver.medicamentos'));
            } else {
                $buttons[] = Button::add('deshabilitado_farmacia')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-capsule-pill" viewBox="0 0 16 16">
                    <path d="M11.02 5.364a3 3 0 0 0-4.242-4.243L1.121 6.778a3 3 0 1 0 4.243 4.243l5.657-5.657Zm-6.413-.657 2.878-2.879a2 2 0 1 1 2.829 2.829L7.435 7.536zM12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8m-.5 1.042a3 3 0 0 0 0 5.917zm1 5.917a3 3 0 0 0 0-5.917z"/>
                    </svg>')
                    ->class('btn btn-red')
                    ->tooltip('HABILITAR')
                    ->dispatchTo('farmacia.almacen-farmacia.medicamentos.medicamentos-main', 'habilitadoFarmacia', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('ver.medicamentos'));
            }
        }
        if ($row->estado == false) {
            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                            </svg>')
                ->id()
                ->class('btn btn-green')
                ->tooltip('HABILITAR')
                ->dispatchTo('farmacia.almacen-farmacia.medicamentos.medicamentos-main', 'cambiar_estado', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('deshabilitar.medicamentos'));
        } else {

            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip('DESHABILITAR')
                ->dispatchTo('farmacia.almacen-farmacia.medicamentos.medicamentos-main', 'cambiar_estado', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('habilitar.medicamentos'));
        }

        return $buttons;
    }
}
