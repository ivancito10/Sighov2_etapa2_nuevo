<?php

namespace App\Livewire\Administracion\RolesPermisos;
use App\Livewire\Administracion\RolesPermisos\RolesPermisosMain as MainComponent;
use App\Models\Administracion\Permission;
use App\Models\Administracion\RoleHasPermission;
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

final class PermisosTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

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
        return Permission::query();

       /*  return Permission::query()
        ->leftjoin('administracion.role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
        ->where('role_has_permissions.role_id', '1'); */
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('guard_name')
            ->add('permissions_enabled')
            ->add('permiso_id')
           ->add('asignaciones')
            ->add('estado', function ($dish) {
                if ($dish->permissions_enabled) {
                    return "<span style='color: #116EAB'><b>ASIGNADO</b></span>";
                } else {
                    return "<span style='color:red'><b>SIN ASIGNAR</b></span>";
                }
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('NRO.', 'id')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),

            Column::make('ACCIÓN', 'name')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')    
            ->sortable()
                ->searchable(),

            Column::make('ASIGNACIONES', 'asignaciones')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')    
            ->sortable()
            ->searchable(),

            Column::action('ACTION')
            ->headerAttribute('text-center bg-teal', 'font-size:15px')
            ->bodyAttribute('text-center')
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

    public $mainComponent; 
    public function actions(Permission $row): array
    {
        $buttons = [

            Button::add('agregar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>')
                ->id()
                ->tooltip('AGREGAR PERMISO')
                ->class('btn btn-green')
                ->dispatchTo('administracion.roles-permisos.roles-permisos-main', 'addPermiso', ['rowId' => $row->id]),
        
        
            Button::add('quitar')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                </svg>')
            ->id()
            ->tooltip('REMOVER PERMISO')
            ->class('btn btn-red')
            ->dispatchTo('administracion.roles-permisos.roles-permisos-main', 'quitPermiso', ['rowId' => $row->id]),


            
            ];

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
