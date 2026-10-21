<?php

namespace App\Livewire\Administracion\Afiliacion\Zonas;

use Livewire\Component;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Departamento;

class ZonasRegister extends Component
{
    public $idDepartamento;
    public $idProvincia;
    public $idMunicipio;
    public $provincias = [];
    public $municipios = [];
    public $nombreZona;

    protected $listeners = ['crearNuevaZona', 'cancelarZona'];

    public function render()
    {
        $departamentos = Departamento::all();

        // Llamar al método obtenerProvincias para actualizar las provincias según el departamento seleccionado

        return view('livewire.Administracion.afiliacion.zonas.zonas-register', compact('departamentos'));
    }

    public function obtenerProvincias()
    {
        try {
            if ($this->idDepartamento) {
                $this->provincias = Provincia::where('id_departamento', $this->idDepartamento)->get();
                if ($this->provincias == null) {
                    $this->provincias = [];
                }
            }
        } catch (\Throwable $th) {
            $this->provincias = [];
        }
    }

    public function obtenerMunicipio()
    {
        try {
            if ($this->idProvincia) {
                $this->municipios = Municipio::where('id_provincia', $this->idProvincia)->get();
                if ($this->municipios == null) {
                    $this->municipios = [];
                }
            }
        } catch (\Throwable $th) {
            $this->municipios = [];
        }
    }

    public function crearNuevaZona()
    {
        $nuevaZona = Zona::create([
            'nombre' => $this->nombreZona,
            'id_municipio' => $this->idMunicipio,
            'estado' => true,
            'id_user_created' => auth()->user()->id,
        ]);

        if ($nuevaZona) {
            $this->dispatch('zonaCreada');
            
            return redirect()->route('Administracion.zonas.index')->with('evento', 'zonaCreada');
        } else {
            $this->dispatch('zonaCancelada');
        }
    }

    public function cancelarZona()
    {
        $this->dispatch('zonaCancelada');

        return redirect()->route('Administracion.zonas.index')->with('evento', 'zonaCancelada');
    }
}
