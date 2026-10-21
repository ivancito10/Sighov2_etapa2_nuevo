<?php

namespace App\Livewire\Bioestadistica\AdministracionCie;

use App\Models\ConsultaExterna\Cie10;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;
use Throwable;
use App\Services\NotificacioSighoV2nService;
use Illuminate\Support\Facades\DB;

class AdministracionCieMain extends Component
{
    public $descripcion;
    public $codigo;
    public $id_cie;
    public $cie;
    public function render()
    {
        return view('livewire.bioestadistica.administracion-cie.administracion-cie-main');
    }

    public function modalCieRegister()
    {
        // dd('hola');
        $this->resetCampos();
        $this->dispatch('cieRegister');
    }

    #[On('crearNuevaCie')]
    public function crearNuevaCie()
    {
        // dd('hola');
        try {

            DB::beginTransaction();
            if ($this->descripcion == '' || $this->descripcion == null || $this->codigo == '' || $this->codigo == null) {
                $this->dispatch('CieVacia');
                return;
            }
            // validacin de repetido
            //  dd(FuncionesMDC::where('descripcion', $this->descripcion)->exists());
            if (Cie10::where('codigo', $this->codigo)->exists()) {
                $this->dispatch('CieCodigoRepetida');
                return;
            }
            if (Cie10::where('descripcion', $this->descripcion)->exists()) {
                $this->dispatch('CieDescripcionRepetida');
                return;
            }
            $data = [
                'codigo' => $this->codigo,
                'descripcion' => $this->descripcion,
                'estado' => true,
                'id_user_created' => auth()->user()->id
            ];
            // dd($data);
            $funcion = Cie10::create($data);
            if ($funcion) {
                // dd($funcion);
                $noticiacion = new NotificacioSighoV2nService();
                $noticiacion->enviar_notificacion(
                    'Super Admin',
                    'Bio estadistica',
                    'Creacion de nueva CIE',
                    'Nueva CIE creada: '
                );
                DB::commit();
                $this->dispatch('CieCreada');
                $this->dispatch('pg:eventRefresh-CieRegistro');
                $this->resetCampos();
            }
        } catch (Throwable $th) {
            //throw $th;
            dd($th->getMessage());
            DB::rollBack();
            Log::error('Error en crearNuevaFuncionSsu: ' . $th->getMessage()); // Para depuración
            $this->resetCampos();
        }
    }

    #[On('modalCieEdit')]
    public function modalCieEdit($data)
    {
        // dd('hola');
        // dd($data);
        $this->resetCampos();
        $this->id_cie = $data;
        $this->mostrarCie($data);
        $this->dispatch('cieEditar');
    }

    #[On('editarCie')]
    public function editarCie()
    {
        // dd('hola');
        try {
            DB::beginTransaction();
            if ($this->descripcion == '' || $this->descripcion == null || $this->codigo == '' || $this->codigo == null) {
                $this->dispatch('CieVacia');
                return;
            }
            // if ($this->id_cie == Cie10::where('codigo', $this->codigo)->first()->id) {
            //         $this->dispatch('CieCodigoRepetida');
            //         return;
            // }
            // if (Cie10::where('descripcion', $this->descripcion)->exists()) {
            //     $this->dispatch('CieDescripcionRepetida');
            //     return;
            // }
            $data = [
                'codigo' => $this->codigo,
                'descripcion' => $this->descripcion,
                'estado' => true,
                'id_user_updated' => auth()->user()->id
            ];
            // dd($data);
            $funcion = Cie10::where('id', $this->id_cie)->update($data);
            if ($funcion) {
                $noticiacion = new NotificacioSighoV2nService();
                $noticiacion->enviar_notificacion(
                    'Super Admin',
                    'Bio estadistica',
                    'Creacion de nueva CIE',
                    'Nueva CIE creada: '
                );
            }
            DB::commit();
            $this->dispatch('cieEditada');
            $this->dispatch('pg:eventRefresh-CieRegistro');
            $this->resetCampos();
        } catch (Throwable $th) {
            //throw $th;
            DB::rollBack();
            dd($th->getMessage());
            Log::error('Error en editarFuncionSsu: ' . $th->getMessage()); // Para depuración
            $this->resetCampos();
        }
    }


    public function resetCampos()
    {
        $this->reset([
            'codigo',
            'descripcion',
        ]);
    }
    private function mostrarCie($data)
    {
        // dd($data);
        $cie = Cie10::find($data);
        $this->id_cie = $cie->id;
        $this->codigo = $cie->codigo;
        $this->descripcion = $cie->descripcion;
    }

    // opciones para modificar el estado
    #[On('confirmarDeshabilitar')]
    public function confirmarDeshabilitar($data)
    {
        $this->id_cie = $data;
        $this->dispatch('confirmarEstadoDato');
    }
    #[On('deshabilitarCie')]
    public function deshabilitarCie()
    {
        try {
            $datos = Cie10::find($this->id_cie);
            if ($datos) {
                $datos->estado = !$datos->estado;
                if ($datos->estado) {
                    $mensaje = 'habilitado';
                    $this->dispatch('Ciehabilitado');
                } else {
                    $mensaje = 'deshabilitado';
                    $this->dispatch('CieDeshabilitado');
                }
                $datos->save();
                // $this->dispatch('FuncionDeshabilitado');
                $noticiacion = new NotificacioSighoV2nService();
                $noticiacion->enviar_notificacion(
                    'Super Admin',
                    'Bioestadistica',
                    'CIE',
                    'CIE: ' . $mensaje,
                );
                $this->dispatch('pg:eventRefresh-CieRegistro');
            } else {
                $this->dispatch('pg:eventRefresh-CieRegistro');
            }
        } catch (Throwable $th) {
            dd($th->getMessage());
            Log::error('Error en CIE habilitar deshabilitar: ' . $th->getMessage()); // Para depuración
            $this->dispatch('error');
        }
    }
}
