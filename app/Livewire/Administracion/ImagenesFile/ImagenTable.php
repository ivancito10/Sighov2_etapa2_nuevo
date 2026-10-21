<?php

namespace App\Livewire\Administracion\ImagenesFile;

use App\Models\Administracion\Imagen;
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

final class ImagenTable extends PowerGridComponent
{
    use WithExport;
    public string $sortField ='imagen.id';
    public string $sortDirection = 'desc';

    public string $tableName = 'ImagenesTable';

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

    public function datasource(): Builder
    {
        return Imagen::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id') 
            ->add('nombre_imagen')
            ->add('descripcion')
            ->add('estado')
            ->add('estado_formatted', function ($estado) {
                if ($estado->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill=" #c0392b" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg></span>';
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

            Column::make('DESCRIPCIÓN', 'descripcion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE DE LA IMAGEN', 'nombre_imagen')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(), 
            

            Column::make('ESTADO', 'estado_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(), 

            Column::action('OPCIONES')
            ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
            ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
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

    public function actions(Imagen $row): array
    {
        $buttons = [];
            $buttons[] = Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>')
                ->id()
                ->class('btn btn-blue')
                ->dispatchTo('administracion.imagenes-file.imagenes-main', 'ver_imagen', ['data' => $row->id])
                ->tooltip('VER IMAGEN');
               

            if ($row->estado == false) {
                $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                </svg>')
                ->id()
                ->class('btn btn-green')
                ->dispatchTo('administracion.imagenes-file.imagenes-main', 'cambiar_estado_imagen', ['data' => $row->id])
                ->tooltip('HABILITAR IMAGEN');
            }else{
                $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->dispatchTo('administracion.imagenes-file.imagenes-main', 'cambiar_estado_imagen', ['data' => $row->id])
                ->tooltip('DESHABILITAR IMAGEN');

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
