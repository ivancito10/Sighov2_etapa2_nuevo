<?php

namespace App\Livewire\Fisioterapia\Administracion\Tecnicas;

use App\Services\Fisioterapia\TecnicasService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Attributes\On;

class TecnicasMain extends Component
{

    public string $codigo = '';
    public string $nombre = '';
    public string $grupo = '';
    public ?bool $estado = null;
    #[Locked]
    public ?int $idTecnica = null;
    #[Locked]
    public bool $formularioAbierto = false;
    #[Locked]
    public bool $soloLectura = false;
    #[Locked]
    public ?int $idCambioEstado = null;
    #[Locked]
    public bool $nuevoEstado = false;
    #[Locked]
    public string $nombreCambioEstado = '';
    public string $mensaje = '';
    public string $errorOperacion = '';

    public function mount(): void
    {
        Gate::authorize('fisioterapia.tecnicas.ver');
    }

    public function crear(): void
    {
        Gate::authorize('fisioterapia.tecnicas.gestionar');
        $this->cerrar();
        $this->formularioAbierto = true;
        $this->dispatch('fisioterapia:abrir-formulario-tecnica');
    }

    #[On('fisioterapia:ver-tecnica')]
    public function ver(int $id): void
    {
        $this->cargar($id, true);
    }

    #[On('fisioterapia:editar-tecnica')]
    public function editar(int $id): void
    {
        Gate::authorize('fisioterapia.tecnicas.gestionar');
        $this->cargar($id, false);
    }

    private function cargar(int $id, bool $soloLectura): void
    {
        $tecnica = app(TecnicasService::class)->obtener($id);
        $this->cerrar();
        $this->idTecnica = $tecnica->id;
        $this->codigo = $tecnica->codigo ?? '';
        $this->nombre = $tecnica->nombre ?? '';
        $this->grupo = $tecnica->grupo ?? '';
        $this->estado = $tecnica->estado ?? '';
        $this->soloLectura = $soloLectura;
        $this->formularioAbierto = true;
        $this->dispatch($soloLectura ? 'fisioterapia:abrir-detalle-tecnica' : 'fisioterapia:abrir-formulario-tecnica');
    }

    public function solicitarGuardar(): void
    {
        Gate::authorize('fisioterapia.tecnicas.gestionar');
        abort_unless($this->formularioAbierto && !$this->soloLectura, 403);
        $this->dispatch('fisioterapia:confirmar-guardar-tecnica', editando: $this->idTecnica !== null);
    }

    public function guardar(): void
    {
        Gate::authorize('fisioterapia.tecnicas.gestionar');
        abort_unless($this->formularioAbierto && !$this->soloLectura, 403);
        try {
            app(TecnicasService::class)->guardar([
                'codigo' => $this->codigo,
                'nombre' => $this->nombre,
                'grupo' => $this->grupo,
            ], $this->idTecnica);
        } catch (QueryException $e) {
            report($e);
            $this->errorOperacion = 'No se pudo guardar la técnica. Intente nuevamente; si persiste, contacte a soporte.';
            // $this->errorOperacion = $e;
            return;
        }
        $this->mensaje = $this->idTecnica ? 'Técnica actualizada correctamente.' : 'Técnica registrada correctamente.';
        $this->cerrar();
        $this->dispatch('pg:eventRefresh-fisioterapiaTecnicasTable');
        $this->dispatch('fisioterapia:tecnica-guardada', mensaje: $this->mensaje);
    }

    #[On('fisioterapia:estado-tecnica')]
    public function confirmarEstado(int $id): void
    {
        Gate::authorize('fisioterapia.tecnicas.gestionar');
        $tecnica = app(TecnicasService::class)->obtener($id);
        $this->cerrar();
        $this->idCambioEstado = $tecnica->id;
        $this->nombreCambioEstado = $tecnica->nombre ?? $tecnica->codigo ?? '';
        $this->nuevoEstado = !$tecnica->estado;
        $this->dispatch('fisioterapia:confirmar-estado-tecnica', nombre: $this->nombreCambioEstado, activar: $this->nuevoEstado);
    }

    public function cambiarEstado(): void
    {
        Gate::authorize('fisioterapia.tecnicas.gestionar');
        abort_if($this->idCambioEstado === null, 404);
        try {
            app(TecnicasService::class)->cambiarEstado($this->idCambioEstado, $this->nuevoEstado);
        } catch (QueryException $e) {
            report($e);
            $this->errorOperacion = 'No se pudo cambiar el estado. Intente nuevamente.';
            $this->dispatch('fisioterapia:error-tecnica', mensaje: $this->errorOperacion);
            return;
        }
        $this->mensaje = $this->nuevoEstado ? 'Técnica reactivada correctamente.' : 'Técnica dada de baja correctamente.';
        $this->cerrar();
        $this->dispatch('pg:eventRefresh-fisioterapiaTecnicasTable');
        $this->dispatch('fisioterapia:tecnica-guardada', mensaje: $this->mensaje);
    }

    public function cerrar(): void
    {
        $this->reset('idTecnica', 'codigo', 'nombre', 'grupo', 'formularioAbierto', 'soloLectura', 'idCambioEstado', 'nombreCambioEstado', 'nuevoEstado', 'errorOperacion');
        $this->resetValidation();
    }

    public function render()
    {
        Gate::authorize('fisioterapia.tecnicas.ver');
        return view('livewire.fisioterapia.administracion.tecnicas.tecnicas-main');
    }
}