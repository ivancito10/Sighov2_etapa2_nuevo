<?php

namespace App\Livewire\Farmacia\Farmacia\CierreGestion;

use App\Models\Farmacia\Medicamento;
use App\Services\ListaUnidades;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class MedicamentosTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'MedicamentosFarmaciaTable';

    public string $sortField = 'codigo_medicamento';

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

    /*public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }*/
    public function datasource(): Builder
    {

        $fechaActual = now()->format('Y-m-d');
        $idFarmacia = 76;

        return Medicamento::query()
            ->where('estado', true)
            ->select('medicamento.*')
            ->selectRaw("
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM farmacia.cierre_gestion AS c 
                WHERE c.id_medicamento = medicamento.id
                AND c.id_unidad = ?
                AND DATE(c.fecha_registro) = ?
            ) 
            THEN 'INVENTARIADO' 
            ELSE 'SIN INVENTARIAR' 
        END AS inventariado
    ", [$idFarmacia, $fechaActual]);
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
            ->add('estado')
            ->add('inventariado')
            ->add('inventariado_modificado', function ($inv) {
                if ($inv->inventariado == 'INVENTARIADO') {
                    return '<span style="color:#878787"> <b>INVENTARIADO</b></span>';
                } else {
                    return '<span style="color:#2360B8"><b>SIN INVENTARIAR</b></span>';
                }
            })
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
            ->add('cuenta_contable', function ($medicamento) {
                return $medicamento->cuenta_contable;
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
                //
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                // ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3; color:white; max-width:250px; white-space:normal; word-break:break-word;')
                ->bodyAttribute('text-center', 'max-width:250px; white-space:normal; word-break:break-word;')
                ->sortable()
                ->searchable(),

            Column::make('FORMA FARMACÉUTICA', 'forma_farmaceutica')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado_validado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center'),

            /*  Column::make('INVENTARIADO', 'inventariado_modificado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(), */

            Column::make('INVENTARIADO', 'inventariado_modificado', 'inventariado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            /*   ->searchableRaw("
                        CASE
                            WHEN EXISTS (
                                SELECT 1 FROM farmacia.cierre_gestion c
                                WHERE c.id_medicamento = medicamento.id
                            )
                            THEN 'INVENTARIADO'
                            ELSE 'SIN INVENTARIAR'
                        END
                    "), */

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions(Medicamento $row): array
    {
        $buttons = [];

        if ($row->estado == true && $row->inventariado == 'SIN INVENTARIAR') {
            $buttons[] = Button::add('registrar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>')
                ->class('btn btn-orange')
                // ->route('farmacia.cierre-gestion.create', ['id' => $row->id])
                ->tooltip('REGISTRAR CIERRE DE GESTIÓN')
                //->dispatchTo('farmacia.farmacia.cierre-gestion.cierre-gestion-register', 'formulario_cierre_gestion', ['data' => $row->id]);
                ->dispatchTo('farmacia.farmacia.cierre-gestion.cierre-gestion-nuevo', 'formulario_cierre_gestion_farmacia', ['data' => $row->id]);
        }

        return $buttons;
    }
}
