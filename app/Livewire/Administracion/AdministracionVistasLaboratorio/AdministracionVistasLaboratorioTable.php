<?php

namespace App\Livewire\Administracion\AdministracionVistasLaboratorio;

use App\Models\Laboratorio\AdministracionVistaLaboratorio;
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

final class AdministracionVistasLaboratorioTable extends PowerGridComponent
{
    use WithExport;
    
    public string $sortField ='administracion_vista_laboratorio.id';
    public string $sortDirection = 'desc';
    public string $tableName = 'AdmVistaLabTable';

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
        $searchTerm = trim($this->search);



        return AdministracionVistaLaboratorio::query()
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                
                $userIds = \App\Models\User::on('pgsql_bd1')
                    ->where('name', 'ILIKE', "%{$searchTerm}%")
                    ->pluck('id');

                return $query->whereIn('id_user_especialista', $userIds);
            });
    }

    public function relationSearch(): array
    {
        return [
            //'userAdministracionVistaLaboratorio' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('id_user_especialista')
            ->add('id_user_especialista_formatted', function ($row) {
                return $row->userAdministracionVistaLaboratorio->name;
            })
            ->add('casillas_checks')
            ->add('casillas_checks_formatted', function ($row) {
                if ($row->casillas_checks) {
                    return '<span style="display: inline-flex; align-items: center; gap: 10px; 
                                background-color: #0056b3;
                                color: white; 
                                padding: 8px 20px; 
                                border-radius: 50px;
                            " title="ESTA EN MODO CHECKBOX">

                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" class="bi bi-check-square" viewBox="0 0 16 16">
                                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                    <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                                </svg>

                                <span style="font-size: 18px;">Checkbox</span>
                            </span>';
                } else {
                    return '<span style="display: inline-flex; align-items: center; gap: 10px; 
                        background-color: black;
                        color: white;
                        padding: 8px 20px; 
                        border-radius: 50px;
                    " title="ESTA EN MODO BUSQUEDA MULTISELECT">

                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>

                        <span style="font-size: 18px;">Multiselect</span>
                    </span>';
                }
            })
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::make('NRO', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('ESPECIALISTA', 'id_user_especialista_formatted')
                ->searchable()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('CHECKS / BUSQUEDA', 'casillas_checks_formatted', 'casillas_checks')
                ->sortable()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            /*Column::make('Id user created', 'id_user_created')
                ->sortable()
                ->searchable(),

            Column::make('Id user updated', 'id_user_updated')
                ->sortable()
                ->searchable(),*/

            /*Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::make('Updated at', 'updated_at_formatted', 'updated_at')
                ->sortable(),

            Column::make('Updated at', 'updated_at')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),*/

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function actions(AdministracionVistaLaboratorio $row): array
    {
        $buttons = [];
        if ($row->casillas_checks) {
            $buttons[] = Button::add('cambiar_a_busqueda')
                ->slot('<span style="display: inline-flex;" title="MULTISELECT A CHECKBOX">
                
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>

                </span>')
                ->class('btn btn-red')
                ->tooltip('CAMBIAR A CHECKS')
                ->dispatchTo('administracion.administracion-vistas-laboratorio.administracion-vistas-laboratorio-main', 'cambiarVista', ['data' => $row->id, 'especialista' => $row->id_user_especialista]);
        } else {
            $buttons[] = Button::add('cambiar_a_casillas')
                ->slot('<span style="display: inline-flex;" title="MULTISELECT A CHECKBOX">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                        <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                    </svg>
                </span>')
                ->class('btn btn-green')
                ->tooltip('CAMBIAR A MULTISELECT')
                ->dispatchTo('administracion.administracion-vistas-laboratorio.administracion-vistas-laboratorio-main', 'cambiarVista', ['data' => $row->id, 'especialista' => $row->id_user_especialista]);
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
