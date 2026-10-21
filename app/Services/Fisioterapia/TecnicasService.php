<?php

namespace App\Services\Fisioterapia;

use App\Models\Fisioterapia\Tecnica;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TecnicasService
{
    public function listar(string $buscar = '', string $estado = 'todos', int $porPagina = 10): LengthAwarePaginator
    {
        Gate::authorize('fisioterapia.tecnicas.ver');
        Validator::make(compact('buscar', 'estado', 'porPagina'), [
            'buscar' => ['string', 'max:100'],
            'estado' => [Rule::in(['todos', 'activos', 'inactivos'])],
            'porPagina' => [Rule::in([10, 25, 50])],
        ])->validate();

        return Tecnica::query()
            ->when(trim($buscar) !== '', function ($query) use ($buscar) {
                // Parámetros enlazados: el texto de búsqueda nunca se concatena al SQL.
                $texto = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], mb_strtolower(trim($buscar))).'%';
                $query->where(function ($query) use ($texto) {
                    $query->whereRaw("LOWER(codigo) LIKE ? ESCAPE '!'", [$texto])
                        ->orWhereRaw("LOWER(nombre) LIKE ? ESCAPE '!'", [$texto])
                        ->orWhereRaw("LOWER(grupo) LIKE ? ESCAPE '!'", [$texto]);
                });
            })
            ->when($estado === 'activos', fn ($query) => $query->where('estado', true))
            ->when($estado === 'inactivos', fn ($query) => $query->where(fn ($q) => $q->where('estado', false)->orWhereNull('estado')))
            ->orderBy('codigo')->orderBy('id')->paginate($porPagina);
    }

    public function obtener(int $id): Tecnica
    {
        Gate::authorize('fisioterapia.tecnicas.ver');
        return Tecnica::findOrFail($id);
    }

    public function guardar(array $entrada, ?int $id = null): Tecnica
    {
        Gate::authorize('fisioterapia.tecnicas.gestionar');
        $datos = Validator::make($entrada, [
            'codigo' => ['required', 'string', 'max:50'],
            'nombre' => ['required', 'string', 'max:255'],
            'grupo' => ['nullable', 'string', 'max:100'],
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no debe superar :max caracteres.',
        ])->validate();
        $datos = array_map(fn ($valor) => is_string($valor) ? trim($valor) : $valor, $datos);
        foreach (['codigo', 'nombre'] as $campo) {
            if ($datos[$campo] === '') {
                throw ValidationException::withMessages([$campo => 'Este campo es obligatorio.']);
            }
        }
        $datos['codigo'] = mb_strtoupper($datos['codigo']);
        $datos['grupo'] = ($datos['grupo'] ?? '') === '' ? null : $datos['grupo'];

        return DB::connection('pgsql')->transaction(function () use ($datos, $id) {
            $tecnica = $id === null ? new Tecnica() : Tecnica::lockForUpdate()->findOrFail($id);
            $duplicada = Tecnica::whereRaw('LOWER(codigo) = ?', [mb_strtolower($datos['codigo'])])
                ->when($id !== null, fn ($q) => $q->where('id', '<>', $id))->exists();
            if ($duplicada) {
                throw ValidationException::withMessages(['codigo' => 'Ya existe una técnica con este código, incluso si está inactiva.']);
            }
            $tecnica->fill($datos);
            if ($id === null) {
                $tecnica->estado = true;
                $tecnica->id_user_created = auth()->id();
            } else {
                $tecnica->id_user_updated = auth()->id();
            }
            // Eloquent asigna created_at/updated_at; no se reciben desde el formulario.
            $tecnica->save();
            return $tecnica;
        }, 3);
    }

    public function cambiarEstado(int $id, bool $estado): Tecnica
    {
        Gate::authorize('fisioterapia.tecnicas.gestionar');
        return DB::connection('pgsql')->transaction(function () use ($id, $estado) {
            $tecnica = Tecnica::lockForUpdate()->findOrFail($id);
            $tecnica->estado = $estado;
            $tecnica->id_user_updated = auth()->id();
            $tecnica->save();
            return $tecnica;
        }, 3);
    }
}
