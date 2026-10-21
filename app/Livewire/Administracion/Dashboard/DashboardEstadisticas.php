<?php

namespace App\Livewire\Administracion\Dashboard;

use Livewire\Component;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\Institucion;
use Illuminate\Support\Facades\DB;
use App\Models\Aportes\Comprobante;
use App\Services\ObtenerToken;
use Carbon\Carbon;

class DashboardEstadisticas extends Component
{

    public function mount()
    {
        // DESCOMENTAR PARA COMUNICADOS JJII
        $token = new ObtenerToken();
        $token->obtenerToken();
    }

    public function render()
    {
        return view('livewire.Administracion.dashboard.dashboard-estadisticas');
    }
    public function cambiosLiz()
    {
        // dd('cadsadfas');
    }
}
