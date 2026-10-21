<?php

namespace App\Livewire\Administracion\ImagenesFile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\Administracion\Imagen;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;


class ImagenesRegister extends Component
{
    use WithFileUploads;
    #[Validate]
    public $foto, $nombre_imagen, $id_ultimo, $descripcion;
    public function render()
    {
        return view('livewire.Administracion.imagenes-file.imagenes-register');
    }
    public function mount(){
        $this->obtenerUltimoId();

    }
    public function rules()
    {
        return [
            'descripcion' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function obtenerUltimoId(){
        $imagen_table = Imagen::orderBy('id', 'desc')->first();
        $id = $imagen_table->id ?? 0;
        $this->id_ultimo = $id +1;
    }

    #[On('guardar_imagen')]
    public function guardar_imagen(){
        $this->validate();

        DB::beginTransaction();
        try{
             

            // Guardar la imagen en public/images/imagenes_login
            $extension = $this->foto->getClientOriginalExtension();
            $nombreArchivo = $this->id_ultimo . "." . $extension;

            $this->foto->storeAs('', $nombreArchivo, 'images_login');

 
            $imagen = Imagen::create([
                'nombre_imagen' => $nombreArchivo,
                'descripcion' => $this->descripcion,
                'estado' => true,
                'id_user_created' => auth()->user()->id,
            ]);

                
            $imagenId = $imagen->id;
            DB::commit();
            
            $this->dispatch('pg:eventRefresh-ImagenesTable');
            $this->dispatch('guardado_imagen');
           
            $this->reset( 'descripcion', 'foto', 'id_ultimo'); 
            $this->obtenerUltimoId();

        } catch (\Throwable $th) {
        
            DB::rollBack();
            $this->dispatch('error');
            $this->reset( 'descripcion', 'foto', 'id_ultimo'); 
            $this->obtenerUltimoId();
        }


    }

    #[On('cancelar_registro')]
    public function cancelar_registro(){
        $this->reset( 'descripcion', 'foto', 'id_ultimo');
        $this->obtenerUltimoId();
        $this->dispatch('cerrarModal'); 
    }
}
