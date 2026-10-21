<?php

namespace App\Livewire\Administracion\VideosFile;

use App\Models\Administracion\Videos;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Component;

class VideosEdit extends Component
{
    use WithFileUploads;
    public $id_video;
    public $nombre, $titulo, $descripcion, $video_file, $permanente, $externo, $fecha_inicio, $fecha_fin;
    public  $ruta;
    public function render()
    {
        return view('livewire.Administracion.videos-file.videos-edit');
    }
    public function mount()
    {
        $this->mostrarVideo($this->id_video);
    }

    public function rules()
    {
        return [
            'nombre' => 'required|min:2',
            'titulo' => 'required|min:2',
            'descripcion'  => 'required|min:2',
            // 'video_file' => 'required|mimetypes:video/mp4|max:102400',
            'permanente' => 'required',
            'externo' => 'required',
            'fecha_inicio' => $this->permanente ? 'nullable|date' : 'required|date|before:fecha_fin',
            'fecha_fin' => $this->permanente ? 'nullable|date' : 'required|date',
        ];
    }

    #[On('editarVideitos')]
    public function editarVideitos()
    {
        $this->validate();
        $video = Videos::where('id', $this->id_video)->first();
        if ($video) {
            $video->titulo = $this->titulo;
            $video->nombre = $this->nombre;
            $video->descripcion = $this->descripcion;
            $video->externo = $this->externo;
            $video->permanente = $this->permanente;
            if ($this->permanente === true) {
                $this->reset('fecha_inicio', 'fecha_fin');
            }
            $video->fecha_inicio = $this->fecha_inicio;
            $video->fecha_fin = $this->fecha_fin;
            if ($this->video_file) {
                $nombreVideoLimpio = Str::slug($this->nombre);
                // $nombreVideoFinal = $videoNuevo->id . '_' . $nombreVideoLimpio . '.mp4';
                $nombreVideoFinal = $video->id . '.mp4';
                $this->video_file->storeAs('', $nombreVideoFinal, 'videos');
                $ruta = 'videos/' . $nombreVideoFinal;
            }

            $video->save();
            $this->dispatch('videoEditado');
            return redirect()->route('administracion.videos.index');
        }
    }

    public function mostrarVideo($id)
    {
        try {
            $video = Videos::where('id', $id)->first();
            if ($video) {
                $this->nombre = $video->nombre;
                $this->titulo = $video->titulo;
                $this->descripcion = $video->descripcion;
                $this->externo = $video->externo;
                $this->permanente = $video->permanente;
                //  $this->permanente2 = $video->permanente;
                $this->fecha_inicio = $video->fecha_inicio;
                $this->fecha_fin = $video->fecha_fin;
                $this->ruta = $video->ruta;
            }
        } catch (\Throwable $e) {
            Log::error("Error al ver videos", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }

    public function atras()
    {
        return redirect()->route('administracion.videos.index');
    }
}
