<?php

namespace App\Services\Fisioterapia;

use App\Models\Fisioterapia\PoliticaAgenda;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PoliticasAgendaService
{
    public function listar(string $buscar = '', string $estado = 'todos', int $porPagina = 10): LengthAwarePaginator
    {
        Gate::authorize('fisioterapia.politicasagenda.ver');
        Validator::make(compact('buscar', 'estado', 'porPagina'), [
            'buscar' => ['string', 'max:100'],
            'estado' => [Rule::in(['todos', 'activos', 'inactivos'])],
            'porPagina' => [Rule::in([10, 25, 50])],
        ])->validate();

        return PoliticaAgenda::query()
            ->when(trim($buscar) !== '', function ($query) use ($buscar) {
                // Parámetros enlazados: el texto de búsqueda nunca se concatena al SQL.
                $texto = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], mb_strtolower(trim($buscar))).'%';
                $query->where(function ($query) use ($texto) {
                    $query->whereRaw("LOWER(codigo_version) LIKE ? ESCAPE '!'", [$texto]);
                        // ->orWhereRaw("LOWER(nombre) LIKE ? ESCAPE '!'", [$texto])
                        // ->orWhereRaw("LOWER(grupo) LIKE ? ESCAPE '!'", [$texto]);
                });
            })
            ->when($estado === 'activos', fn ($query) => $query->where('estado', true))
            ->when($estado === 'inactivos', fn ($query) => $query->where(fn ($q) => $q->where('estado', false)->orWhereNull('estado')))
            ->orderBy('codigo_version')->orderBy('id')->paginate($porPagina);
    }

    public function obtener(int $id): PoliticaAgenda
    {
        Gate::authorize('fisioterapia.politicasagenda.ver');
        return PoliticaAgenda::findOrFail($id);
    }

    public function guardar(array $entrada, ?int $id = null): PoliticaAgenda
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        $datos = Validator::make($entrada, [
            'codigo_version' => ['required', 'string', 'max:50'],
            'cupos_normales' => ['required', 'integer', 'max:100'],
            'cupos_especiales' => ['required', 'integer', 'max:100'],
            'max_sesiones_normales' => ['required', 'integer', 'max:100'],
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no debe superar :max caracteres.',
        ])->validate();
        $datos = array_map(fn ($valor) => is_string($valor) ? trim($valor) : $valor, $datos);
        foreach (['codigo_version', 'cupos_normales', 'cupos_especiales', 'max_sesiones_normales'] as $campo) {
            if ($datos[$campo] === '') {
                throw ValidationException::withMessages([$campo => 'Este campo es obligatorio.']);
            }
        }
        $datos['codigo_version'] = mb_strtoupper($datos['codigo_version']);
        // $datos['grupo'] = ($datos['grupo'] ?? '') === '' ? null : $datos['grupo'];

        return DB::connection('pgsql')->transaction(function () use ($datos, $id) {
            $politicaagenda = $id === null ? new PoliticaAgenda() : PoliticaAgenda::lockForUpdate()->findOrFail($id);
            $duplicada = PoliticaAgenda::whereRaw('LOWER(codigo_version) = ?', [mb_strtolower($datos['codigo_version'])])
                ->when($id !== null, fn ($q) => $q->where('id', '<>', $id))->exists();
            if ($duplicada) {
                throw ValidationException::withMessages(['codigo_version' => 'Ya existe una política agenda con este código version, incluso si está inactiva.']);
            }
            $politicaagenda->fill($datos);
            if ($id === null) {
                $politicaagenda->estado = true;
                $politicaagenda->id_user_created = auth()->id();
            } else {
                $politicaagenda->id_user_updated = auth()->id();
            }
            // Eloquent asigna created_at/updated_at; no se reciben desde el formulario.
            $politicaagenda->save();
            return $politicaagenda;
        }, 3);
    }

    public function cambiarEstado(int $id, bool $estado): PoliticaAgenda
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        return DB::connection('pgsql')->transaction(function () use ($id, $estado) {
            $politicaagenda = PoliticaAgenda::lockForUpdate()->findOrFail($id);
            $politicaagenda->estado = $estado;
            $politicaagenda->id_user_updated = auth()->id();
            $politicaagenda->save();
            return $politicaagenda;
        }, 3);
    }
}
