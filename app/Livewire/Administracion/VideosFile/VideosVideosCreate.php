<?php

namespace App\Livewire\Administracion\VideosFile;

use App\Models\Administracion\Videos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;

class VideosVideosCreate extends Component
{
    use WithFileUploads;
    public $nombre, $titulo, $descripcion, $video_file, $permanente = false, $externo = false, $fecha_inicio, $fecha_fin;

    public function render()
    {
        return view('livewire.Administracion.videos-file.videos-videos-create');
    }

    public function rules()
    {
        return [
            'nombre' => 'required|min:2',
            'titulo' => 'required|min:2',
            'descripcion'  => 'required|min:2',
            //'video_file' => 'required|mimetypes:video/mp4|max:102400',
            'video_file' => 'required|mimes:mp4,mkv|max:102400',

            'permanente' => 'required',
            'externo' => 'required',
            'fecha_inicio' => $this->permanente ? 'nullable|date' : 'required|date|before:fecha_fin',
            'fecha_fin' => $this->permanente ? 'nullable|date' : 'required|date',
        ];
    }

    public function atras()
    {
        return redirect()->route('administracion.videos.index');
    }

    #[On('crearVideitos')]
    public function crearVideitos()
    {
        $this->validate();
        try {
            $existeVideo = Videos::where('titulo', $this->titulo)
                ->where('nombre', $this->nombre)->first();
            if ($existeVideo) {
                dd('ya esxite');
            } else {
                $videoNuevo = Videos::create([
                    'nombre' => strtoupper($this->nombre),
                    'titulo' => strtoupper($this->titulo),
                    'descripcion' => strtoupper($this->descripcion),
                    'permanente' => $this->permanente,
                    'externo' => $this->externo,
                    'fecha_inicio' => $this->fecha_inicio,
                    'fecha_fin' => $this->fecha_fin,
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);

                $nombreVideoLimpio = Str::slug($this->nombre);
                // $nombreVideoFinal = $videoNuevo->id . '_' . $nombreVideoLimpio . '.mp4';
                $nombreVideoFinal = $videoNuevo->id . '.mp4';
                $this->video_file->storeAs('', $nombreVideoFinal, 'videos');
                $ruta = 'videos/' . $nombreVideoFinal;
                $videoNuevo->update(['ruta' => $ruta]);

                $this->dispatch('VideoRegistradoExito');
                return redirect()->route('administracion.videos.index');
            }
        } catch (\Exception $e) {
            Log::error('Error al guardar el video: ' . $e->getMessage());
        }
    }
}
