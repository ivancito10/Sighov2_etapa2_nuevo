<?php

namespace App\Livewire\Administracion\VideosFile;

use App\Models\Administracion\Videos;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class VideosVideosMain extends Component
{
    public $id_video;
    public $nombre_v, $titulo_v, $descripcion_v, $video_file_v, $permanente_v, $permanente2, $externo_v,
        $fecha_inicio_v, $fecha_fin_v;
    public  $ruta_v;

    public function render()
    {
        return view('livewire.Administracion.videos-file.videos-videos-main');
    }
    public function mount()
    {
        // $this->ruta_v = 'videos/externos/17.mp4';
    }
    public function registrarVideo()
    {
        return redirect()->route('administracion.videos.create');
    }

    #[On('verModalVideo')]
    public function verModalVideo($data)
    {
        $this->dispatch('abrirModalVideos');
        $this->mostrarVideo($data);
    }
    public function mostrarVideo($id)
    {
        try {
            $video = Videos::where('id', $id)->first();
            if ($video) {
                $this->nombre_v = $video->nombre;
                $this->titulo_v = $video->titulo;
                $this->descripcion_v = $video->descripcion;
                $this->externo_v = $video->externo ? 'EXTERNO' : 'INTERNO';
                $this->permanente_v = $video->permanente ? 'PERMANENTE' : 'TEMPORAL';
                $this->permanente2 = $video->permanente;
                $this->fecha_inicio_v = $video->fecha_inicio;
                $this->fecha_fin_v = $video->fecha_fin;
                $this->ruta_v = $video->ruta;
                //dd($this->ruta_v);
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
        // dd($this->ruta_v);
    }
    #[On('cambiarEstadoVideo')]
    public function cambiarEstadoVideo($data)
    {
        $this->id_video = $data;
        $video = Videos::find($data);
        if ($video) {
            $this->dispatch($video->estado ? 'preguntaInhabilitarEstadoVideo' : 'preguntaHabilitarEstadoVideo');
        }
    }

    #[On('guardarCambioEstadoVideo')]
    public function guardarCambioEstadoVideo()
    {
        try {
            $videos = Videos::find($this->id_video);
            if ($videos) {
                $videos->estado = !$videos->estado;
                $videos->id_user_updated = auth()->user()->id;
                $videos->save();
                $this->dispatch('estadoCambiadoVideo');
                $this->dispatch('pg:eventRefresh-videosTable');
            }
        } catch (\Throwable $e) {
            $this->dispatch('estadoNoCambiadoVideo');
            $trace = collect($e->getTrace())
                ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));
            Log::error("Error al obtener imagen", [
                'USUARIO_ID' => auth()->user()->id ?? null,
                'ERROR'      => $e->getMessage() ?? '',
                'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
                'LINEA'      => $trace['line'] ?? $e->getLine(),
                'FECHA_HORA' => now(),
            ]);
        }
    }
}
