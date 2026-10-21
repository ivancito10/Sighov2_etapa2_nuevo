<?php

namespace App\Livewire\Administracion\Afiliacion\Zonas;

use Livewire\Component;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Zona;
use App\Services\ActualizacionDatosSegipS1;
use App\Services\ActualizacionesPlataforma;
use App\Services\ObtenerPoblacion;
use App\Services\ReplicaPreafiliacion;
use App\Services\ReplicarAgendaS1;
use App\Services\ServiciosCachecito;
use Livewire\Attributes\On;

class ZonasMain extends Component
{
    public $idZona;
    public $departamentoEdit;
    public $provinciaEdit;
    public $municipioEdit;
    public $zonaEdit;

    protected $listeners = ['actualizarZona'];

    public function render()
    {
        $this->mostrarZona($this->idZona);
        return view('livewire.Administracion.afiliacion.zonas.zonas-main');
    }

    public function mount()
    {
        //$dato = new ObtenerPoblacion();
        //dd($dato->obtenerPoblacionAsegurada());
        //dd($dato->obtenerDatosAfiliacionId(8762));
        //dd($dato->obtenerDatosAfiliacionId(1));    
        /*$esp = new ServiciosCachecito();
        dd($esp->listaEspecialistasHabilitados());*/
        /*$dato = new ReplicarAgendaS1();
        $dato->replicarAgendaWebApk();*/
        /*$dato = new ActualizacionesPlataforma();
        $dato->actualizacionEspecialistaHabilitadoServicio();*/
        /*$idPreafiliacion = 8;
        $dato = new ReplicaPreafiliacion();
        dd($dato->enviarDatosPreafiliacion($idPreafiliacion));*/
        /*$datos = [
            'ciAntiguo' => '9868420',
            'ciNuevo' => '9868421',
            'nombres' => 'BORIS ORLANDO',
            'p_apellido' => 'ROQUE',
            's_apellido' => 'HUANCA',
            'fecha_nacimiento' => '2000-04-29',
            'sexo' => 'M',
            'matricula_seguro' => '000429RHB',
        ];

        $dato = new ActualizacionDatosSegipS1();
        $dato->datosActualizados($datos);*/


        /*$datosContrastadosSegip = [
            'ciAntiguo' => '83643991',
            'ciNuevo' => '836439912',
            'nombres' => 'JAVIER JAVIER',
            'p_apellido' => 'TICONA',
            's_apellido' => 'TARQUI',
            'fecha_nacimiento' => '1990-02-23',
            'sexo' => 'M',
            'matricula_seguro' => '930223TTJ',
        ];
        //dd($datosContrastadosSegip);
        $dato = new ActualizacionDatosSegipS1();
        $dato->enviarDatosActualizados($datosContrastadosSegip);*/
    }

    public function redirectToForm()
    {
        return redirect()->route('administracion.zonas.create');
    }

    public function mostrarZona($zonaId)
    {
        try {
            $zona = Zona::find($zonaId);
            $this->zonaEdit = $zona->nombre;
            $idMunicipio = $zona->id_municipio;
            $municipio = Municipio::find($idMunicipio);
            $this->municipioEdit = $municipio->nombre;
            $idProvincia = $municipio->id_provincia;
            $provincia = Provincia::find($idProvincia);
            $this->provinciaEdit = $provincia->nombre;
            $idDepartamento = $provincia->id_departamento;
            $departamento = Departamento::find($idDepartamento);
            $this->departamentoEdit = $departamento->nombre;
        } catch (\Throwable $th) {
            $this->idZona = '';
            $this->departamentoEdit = '';
            $this->provinciaEdit = '';
            $this->municipioEdit = '';
            $this->zonaEdit = '';
        }
    }

    public function actualizarZona()
    {
        try {
            if ($this->zonaEdit != '') {
                $zonaActualizar = Zona::find($this->idZona);
                $zonaActualizar->nombre = $this->zonaEdit;
                $zonaActualizar->id_user_updated = auth()->user()->id;
                $zonaActualizar->save();
                $this->dispatch('zonaActualizada');
                $this->dispatch('pg:eventRefresh-ZonasRegistro');
            } else {
                $this->dispatch('datoIncompleto');
            }
        } catch (\Throwable $th) {
            $this->dispatch('zonaNoActualizada');
        }
    }

    #[On('editarZonas')]
    public function editarZonas($data)
    {
        $this->idZona = $data;
        $this->dispatch('abrirModalEditar');
    }

    #[On('habilitar')]
    public function habilitar($rowId)
    {
        try {
            $zona = Zona::find($rowId);
            if ($zona) {
                $estadoActual = $zona->estado;

                $zona->estado = !$estadoActual;
                $zona->save();
                $this->dispatch('pg:eventRefresh-ZonasRegistro');
                $this->dispatch('ZonaHabilitada');
            }
        } catch (\Throwable $th) {
            $this->dispatch('ZonaNoActualizada');
        }
    }

    #[On('deshabilitar')]
    public function deshabilitar($rowId)
    {
        try {
            $zona = Zona::find($rowId);
            if ($zona) {
                $estadoActual = $zona->estado;
                $zona->estado = !$estadoActual;
                $zona->save();
                $this->dispatch('pg:eventRefresh-ZonasRegistro');
                $this->dispatch('ZonaDeshabilitada');
            }
        } catch (\Throwable $th) {
            $this->dispatch('ZonaNoActualizada');
        }
    }
}
