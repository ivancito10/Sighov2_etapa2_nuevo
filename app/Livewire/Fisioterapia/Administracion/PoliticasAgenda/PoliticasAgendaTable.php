<?php

namespace App\Livewire\Fisioterapia\Administracion\PoliticasAgenda;

use App\Models\Fisioterapia\PoliticaAgenda;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\Filter;

final class PoliticasAgendaTable extends PowerGridComponent
{
    public string $tableName = 'fisioterapiaPoliticasAgendaTable';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        Gate::authorize('fisioterapia.politicasagenda.ver');
    }

    public function setUp(): array
    {
        return [Header::make()->showSearchInput(), Footer::make()->showPerPage(10, [10, 25, 50])->showRecordCount()];
    }

    public function datasource(): Builder
    {
        Gate::authorize('fisioterapia.tecnicas.ver');
        return PoliticaAgenda::query()->select(['id', 'codigo_version', 'cupos_normales', 'cupos_especiales', 'max_sesiones_normales', 'estado']);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()->add('id')
            ->add('codigo_version', fn (PoliticaAgenda $row) => e($row->codigo_version))
            ->add('cupos_normales', fn (PoliticaAgenda $row) => e($row->cupos_normales))
            ->add('cupos_especiales', fn (PoliticaAgenda $row) => e($row->cupos_especiales ))
            ->add('max_sesiones_normales', fn (PoliticaAgenda $row) => e($row->max_sesiones_normales))
            // ->add('max_sesiones_normales', fn (PoliticaAgenda $row) => e($row->grupo ?? '—'))
            ->add('estado')
            // ->add('estado_formatted', fn (PoliticaAgenda $row) => $row->estado
            //     ? '<span class="badge badge-success">Activa</span>'
            //     : '<span class="badge badge-danger">Inactiva</span>');
            ->add('estado_formatted', fn (PoliticaAgenda $row) => $row->estado
                ? '<span ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                </span>'
                : '<span >
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg>
                </span>');
    }

    public function columns(): array
    {
        $style = 'font-size:15px; background-color:#2471a3; color:white;';
        return [
            Column::make('#', 'id')->index()->headerAttribute('text-center', $style)->bodyAttribute('text-center'),
            Column::make('CÓDIGO VERSIÓN', 'codigo_version')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('CUPOS NORMALES', 'cupos_normales')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('CUPOS ESPECIALES', 'cupos_especiales')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('MAXIMAS SESIONES', 'max_sesiones_normales')->sortable()->searchable()->headerAttribute('text-center', $style),
            Column::make('ESTADO', 'estado_formatted', 'estado')->sortable()->headerAttribute('text-center', $style)->bodyAttribute('text-center'),
            Column::action('OPCIONES')->headerAttribute('text-center', $style)->bodyAttribute('text-center text-nowrap'),
        ];
    }

    public function filters(): array
    {
        return [Filter::boolean('estado_formatted', 'estado')->label('Activas', 'Inactivas')];
    }

    public function actions(PoliticaAgenda $row): array
    {
        $target = 'fisioterapia.administracion.politicas-agenda.politicas-agenda-main';
        $icon = fn (string $accion) => view('components.table-action-icon', compact('accion'))->render();
        $buttons = [Button::add('ver')->slot($icon('ver'))
                    ->class('btn btn-blue')
                    ->tooltip('VER POLÍTICA AGENDA')
                    ->dispatchTo($target, 'fisioterapia:ver-politicaagenda', ['id' => $row->id])];
        if (Gate::allows('fisioterapia.politicasagenda.gestionar')) {
            $buttons[] = Button::add('editar')->slot($icon('editar'))
                    ->class('btn btn-orange')
                    ->tooltip('EDITAR POLÍTICA AGENDA')
                    ->dispatchTo($target, 'fisioterapia:editar-politicaagenda', ['id' => $row->id]);
            $buttons[] = Button::add('estado')->slot($icon($row->estado ? 'deshabilitar' : 'habilitar'))
                    ->class($row->estado ? 'btn btn-red' : 'btn btn-green')
                    ->tooltip($row->estado ? 'DAR DE BAJA POLÍTICA AGENDA' : 'REACTIVAR POLÍTICA AGENDA')
                    ->dispatchTo($target, 'fisioterapia:estado-politicaagenda', ['id' => $row->id]);
        }
        return $buttons;
    }
}
