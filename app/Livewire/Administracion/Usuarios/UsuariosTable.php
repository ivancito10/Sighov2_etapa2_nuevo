<?php

namespace App\Livewire\Administracion\Usuarios;

use App\Models\User;
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

final class UsuariosTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'UsuariosTable';
    public string $sortField = 'users.id';
    public string $sortDirection = 'desc';
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

        return User::query()
            ->join('administracion.persona as persona', function ($join) {
                $join->on('users.id_persona', '=', 'persona.id');
            })
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.id_persona',
                'users.estado',
                'users.last_login_at',
                'persona.nombres as nombre_persona',
                'persona.p_apellido as pap_persona',
                'persona.s_apellido as sap_persona',
                'persona.ci as carnet',
                'persona.complemento as persona_complemento',
            ]);
    }

    public function relationSearch(): array
    {
        return [
            'personaUser' => ['nombres', 'p_apellido', 's_apellido', 'ci'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('id_persona')
            ->add('persona_nombre_completo', fn(User $model) => $model->personaUser->p_apellido . ' ' . $model->personaUser->s_apellido . ' ' . $model->personaUser->nombres)
            ->add('carnet_formatted', function ($carnet) {
                if ($carnet->persona_complemento == null) {
                    return $carnet->carnet;
                } else {
                    return $carnet->carnet . ' - ' . $carnet->persona_complemento;
                }
            })
            ->add('roles', fn(User $model) => $model->roles->map(fn($role) => '<span class="badge badge-info" style="font-size:12px">' . $role->name . '</span>')->implode(' <br>'))
            ->add('session_token')
            ->add('created_at')

            ->add('status', function (User $model) {
                $lastLoginAt = $model->last_login_at ? Carbon::parse($model->last_login_at) : null;
                return $lastLoginAt && $lastLoginAt->diffInMinutes() < 15
                    ? '<span class="badge badge-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg></span>'
                    : '<span class="badge badge-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-record-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg></span>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE USUARIO', 'name')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('EMAIL', 'email')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CARNET', 'carnet_formatted', 'carnet')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE PERSONA', 'persona_nombre_completo', 'pap_persona')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ROLES', 'roles')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;'),

            Column::make('ESTADO', 'status')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('Ult. Con.', 'last_login_at')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3  ; color:white;')
                ->bodyAttribute('text-left')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(User $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER USUARIO')
            ->dispatchTo('administracion.usuarios.usuarios-main', 'verUsuario', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('admin.ver_usuario'));



        if ($row->estado === false) {
            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>')
                ->id()
                ->class('btn btn-green')
                ->tooltip('HABILITAR USUARIO')
                ->dispatchTo('administracion.usuarios.usuarios-main', 'cambiarEstado', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('admin.habilitar_usuario'));
        } else {
            if ($row->primer_inicio_sesion == null && $row->last_login_at == null) {
                $buttons[] =   Button::add('pdf')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                    </svg>')

                    ->class('btn btn-red')
                    ->tooltip('VER CREDENCIALES')
                    /* ->dispatch('pdf', ['rowId' => $row->id]) */
                    ->dispatchTo('administracion.usuarios.usuarios-main', 'verCredencialesPdf', ['data' => $row->id])
                    ->can(allowed: auth()->user()->can('admin.pdf_credenciales'));
            }

            $buttons[] = Button::add('editar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
            </svg>')
                ->class('btn btn-orange')
                ->tooltip('EDITAR USUARIO')
                ->dispatchTo('administracion.usuarios.usuarios-main', 'editarUsuario', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('admin.editar_usuario'));

            $buttons[] = Button::add('contraseña')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-incognito" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="m4.736 1.968-.892 3.269-.014.058C2.113 5.568 1 6.006 1 6.5 1 7.328 4.134 8 8 8s7-.672 7-1.5c0-.494-1.113-.932-2.83-1.205l-.014-.058-.892-3.27c-.146-.533-.698-.849-1.239-.734C9.411 1.363 8.62 1.5 8 1.5s-1.411-.136-2.025-.267c-.541-.115-1.093.2-1.239.735m.015 3.867a.25.25 0 0 1 .274-.224c.9.092 1.91.143 2.975.143a30 30 0 0 0 2.975-.143.25.25 0 0 1 .05.498c-.918.093-1.944.145-3.025.145s-2.107-.052-3.025-.145a.25.25 0 0 1-.224-.274M3.5 10h2a.5.5 0 0 1 .5.5v1a1.5 1.5 0 0 1-3 0v-1a.5.5 0 0 1 .5-.5m-1.5.5q.001-.264.085-.5H2a.5.5 0 0 1 0-1h3.5a1.5 1.5 0 0 1 1.488 1.312 3.5 3.5 0 0 1 2.024 0A1.5 1.5 0 0 1 10.5 9H14a.5.5 0 0 1 0 1h-.085q.084.236.085.5v1a2.5 2.5 0 0 1-5 0v-.14l-.21-.07a2.5 2.5 0 0 0-1.58 0l-.21.07v.14a2.5 2.5 0 0 1-5 0zm8.5-.5h2a.5.5 0 0 1 .5.5v1a1.5 1.5 0 0 1-3 0v-1a.5.5 0 0 1 .5-.5"/>
                </svg>')
                ->class('btn btn-black')
                ->tooltip('CAMBIAR CONTRASEÑA')
                ->dispatchTo('administracion.usuarios.usuarios-main', 'cambiarContraseña', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('admin.cambiar_contraseña_usuario'));

            $buttons[] = Button::add('asignar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-fill" viewBox="0 0 16 16">
                <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/>
                </svg>')
                ->class('btn btn-lila')
                ->tooltip('ASIGNAR ROL')
                ->dispatchTo('administracion.usuarios.usuarios-main', 'asignarRol', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('admin.asignar_rol_usuarios'));
            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip('DESHABILITAR USUARIO')
                ->dispatchTo('administracion.usuarios.usuarios-main', 'cambiarEstado', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('admin.deshabilitar_usuario'));
        }
        return $buttons;
    }
}
