<?php

namespace App\Livewire\Administracion\Dashboard\Administracion;

use App\Models\User;
use Livewire\Component;

class UsuariosDashboard extends Component
{
    protected array $idsBeneficiarios = [2, 3, 4, 5, 6, 7, 12, 13];
    protected array $idsConvenios    = [8, 9, 10];


    public int $usuarios_titulares_activos      = 0;
    public int $usuarios_titulares_inactivos    = 0;
    public int $usuarios_beneficiarios_activos  = 0;
    public int $usuarios_beneficiarios_inactivos = 0;
    public int $usuarios_estudaintes_activos    = 0;
    public int $usuarios_estudaintes_inactivos  = 0;
    public int $usuarios_convenios_activos      = 0;
    public int $usuarios_convenios_inactivos    = 0;
    public int $usuarios_internos               = 0;
    public int $usuarios_externos               = 0;
    public function mount()
    {
        // TITULARES
        $this->usuarios_titulares_activos = User::query()
            ->whereNotNull('primer_inicio_sesion')
            ->where('email', 'NOT ILIKE', '%@ssulapaz.org%') 
            ->whereHas(
                'personaUser',
                fn($q) =>
                $q->where('id_tipo_asegurado', 1)
            )
            ->count();
        $this->usuarios_titulares_inactivos = User::query()
            ->whereNull('primer_inicio_sesion')
            ->where('email', 'NOT ILIKE', '%@ssulapaz.org%') 
            ->whereHas(
                'personaUser.tipoAseguradoPersona',
                fn($q) =>
                $q->where('tipo_asegurado', 'TITULAR')
            )->count();

        // BENEFICIARIOS (por IDs en persona.id_tipo_asegurado)
        $this->usuarios_beneficiarios_activos = User::query()
            ->whereNotNull('primer_inicio_sesion')
            ->whereHas(
                'personaUser',
                fn($q) =>
                $q->whereIn('id_tipo_asegurado', $this->idsBeneficiarios)
            )->count();

        $this->usuarios_beneficiarios_inactivos = User::query()
            ->whereNull('primer_inicio_sesion')
            ->whereHas(
                'personaUser',
                fn($q) =>
                $q->whereIn('id_tipo_asegurado', $this->idsBeneficiarios)
            )->count();

        // ESTUDIANTES (por nombre en tipo_asegurado)
        $this->usuarios_estudaintes_activos = User::query()
            ->whereNotNull('primer_inicio_sesion')
            ->whereHas(
                'personaUser.tipoAseguradoPersona',
                fn($q) =>
                $q->where('tipo_asegurado', 'ESTUDIANTE')
            )->count();

        $this->usuarios_estudaintes_inactivos = User::query()
            ->whereNull('primer_inicio_sesion')
            ->whereHas(
                'personaUser.tipoAseguradoPersona',
                fn($q) =>
                $q->where('tipo_asegurado', 'ESTUDIANTE')
            )->count();

        // CONVENIOS (por IDs en persona.id_tipo_asegurado)
        $this->usuarios_convenios_activos = User::query()
            ->whereNotNull('primer_inicio_sesion')
            ->whereHas(
                'personaUser',
                fn($q) =>
                $q->whereIn('id_tipo_asegurado', $this->idsConvenios)
            )->count();

        $this->usuarios_convenios_inactivos = User::query()
            ->whereNull('primer_inicio_sesion')
            ->whereHas(
                'personaUser',
                fn($q) =>
                $q->whereIn('id_tipo_asegurado', $this->idsConvenios)
            )->count();

        // INTERNOS / EXTERNOS (por email)
        $this->usuarios_internos = User::query()
            ->where('email', 'like', '%ssulapaz%')
            ->count();

        $this->usuarios_externos = User::query()
            ->where('email', 'like', '%ext%')
            ->count();
    }
    public function render()
    {
        return view('livewire.Administracion.dashboard.administracion.usuarios-dashboard');
    }
}
