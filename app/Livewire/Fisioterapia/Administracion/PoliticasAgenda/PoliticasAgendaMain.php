<?php

namespace App\Livewire\Fisioterapia\Administracion\PoliticasAgenda;

use App\Services\Fisioterapia\PoliticasAgendaService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Attributes\On;

class PoliticasAgendaMain extends Component
{

    public string $codigo_version = '';
    public ?int $cupos_normales = null;
    public ?int $cupos_especiales = null;
    public ?int $max_sesiones_normales = null;
    public ?bool $estado = null;
    #[Locked]
    public ?int $idPoliticaAgenda = null;
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
        Gate::authorize('fisioterapia.politicasagenda.ver');
    }

    public function crear(): void
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        $this->cerrar();
        $this->formularioAbierto = true;
        $this->dispatch('fisioterapia:abrir-formulario-politicaagenda');
        // $this->dispatch('fisioterapia:abrir-formulario-politicasagenda');
    }

    #[On('fisioterapia:ver-politicaagenda')]
    public function ver(int $id): void
    {
        $this->cargar($id, true);
    }

    #[On('fisioterapia:editar-politicaagenda')]
    public function editar(int $id): void
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        $this->cargar($id, false);
    }

    private function cargar(int $id, bool $soloLectura): void
    {
        $politicaagenda = app(PoliticasAgendaService::class)->obtener($id);
        $this->cerrar();
        $this->idPoliticaAgenda = $politicaagenda->id;
        $this->codigo_version = $politicaagenda->codigo_version ?? null;
        $this->cupos_normales = $politicaagenda->cupos_normales ?? null;
        $this->cupos_especiales = $politicaagenda->cupos_especiales ?? null;
        $this->max_sesiones_normales = $politicaagenda->max_sesiones_normales ?? null;
        $this->estado = $politicaagenda->estado ?? null;
        $this->soloLectura = $soloLectura;
        $this->formularioAbierto = true;
        $this->dispatch($soloLectura ? 'fisioterapia:abrir-detalle-politicaagenda' : 'fisioterapia:abrir-formulario-politicaagenda');
    }

    public function solicitarGuardar(): void
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        abort_unless($this->formularioAbierto && !$this->soloLectura, 403);
        $this->dispatch('fisioterapia:confirmar-guardar-politicaagenda', editando: $this->idPoliticaAgenda !== null);
    }

    public function guardar(): void
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        abort_unless($this->formularioAbierto && !$this->soloLectura, 403);
        try {
            app(PoliticasAgendaService::class)->guardar([
                'codigo_version' => $this->codigo_version,
                'cupos_normales' => $this->cupos_normales,
                'cupos_especiales' => $this->cupos_especiales,
                'max_sesiones_normales' => $this->max_sesiones_normales,
            ], $this->idPoliticaAgenda);
        } catch (QueryException $e) {
            report($e);
            $this->errorOperacion = $e;
            // $this->errorOperacion = 'No se pudo guardar la Política Agenda. Intente nuevamente; si persiste, contacte a soporte.';
            return;
        }
        $this->mensaje = $this->idPoliticaAgenda ? 'Política Agenda actualizada correctamente.' : 'Política Agenda registrada correctamente.';
        $this->cerrar();
        // $this->dispatch('pg:eventRefresh-fisioterapiaTecnicasTable');
        $this->dispatch('pg:eventRefresh-fisioterapiaPoliticasAgendaTable');
        $this->dispatch('fisioterapia:politicaagenda-guardada', mensaje: $this->mensaje);
    }

    #[On('fisioterapia:estado-politicaagenda')]
    public function confirmarEstado(int $id): void
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        $politicaagenda = app(PoliticasAgendaService::class)->obtener($id);
        $this->cerrar();
        $this->idCambioEstado = $politicaagenda->id;
        $this->nombreCambioEstado = $politicaagenda->codigo_version ?? '';
        $this->nuevoEstado = !$politicaagenda->estado;
        $this->dispatch('fisioterapia:confirmar-estado-politicaagenda', nombre: $this->nombreCambioEstado, activar: $this->nuevoEstado);
    }

    public function cambiarEstado(): void
    {
        Gate::authorize('fisioterapia.politicasagenda.gestionar');
        abort_if($this->idCambioEstado === null, 404);
        try {
            app(PoliticasAgendaService::class)->cambiarEstado($this->idCambioEstado, $this->nuevoEstado);
        } catch (QueryException $e) {
            report($e);
            $this->errorOperacion = 'No se pudo cambiar el estado. Intente nuevamente.';
            $this->dispatch('fisioterapia:error-politicaagenda', mensaje: $this->errorOperacion);
            return;
        }
        $this->mensaje = $this->nuevoEstado ? 'Política Agenda reactivada correctamente.' : 'Política Agenda dada de baja correctamente.';
        $this->cerrar();
        // $this->dispatch('pg:eventRefresh-fisioterapiaTecnicasTable');
        $this->dispatch('pg:eventRefresh-fisioterapiaPoliticasAgendaTable');
        $this->dispatch('fisioterapia:politicaagenda-guardada', mensaje: $this->mensaje);
    }

    public function cerrar(): void
    {
        $this->reset('idPoliticaAgenda', 'codigo_version', 'cupos_normales', 'cupos_especiales', 'max_sesiones_normales', 'formularioAbierto', 'soloLectura', 'idCambioEstado', 'nombreCambioEstado', 'nuevoEstado', 'errorOperacion');
        $this->resetValidation();
    }

    public function render()
    {
        Gate::authorize('fisioterapia.politicasagenda.ver');
        return view('livewire.fisioterapia.administracion.politicas-agenda.politicas-agenda-main');
    }
}