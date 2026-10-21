<?php

namespace App\Livewire\Administracion\VideosFile;

use App\Models\Administracion\Videos;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class VideosVer extends Component
{
    public $id_video;
    public $nombre_v, $titulo_v, $descripcion_v, $video_file_v, $permanente_v, $permanente2, $externo_v,
        $fecha_inicio_v, $fecha_fin_v;
    public  $ruta_v;


    public function render()
    {
        return view('livewire.administracion.videos-file.videos-ver');
    }
    public function mount()
    {
        $this->mostrarVideo($this->id_video);
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

    public function atras()
    {
        return redirect()->route('administracion.videos.index');
    }
}
