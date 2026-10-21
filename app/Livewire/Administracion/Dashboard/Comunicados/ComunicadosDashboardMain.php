<?php

namespace App\Livewire\Administracion\Dashboard\Comunicados;

use App\Models\Administracion\Videos;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ComunicadosDashboardMain extends Component
{
    public $videos = [];
    public function mount()
    {
        // $hoy = Carbon::today()->toDateString();

        // $this->videos = Videos::where('estado', true)
        //     ->get()
        //     ->filter(function ($t) use ($hoy) {
        //         if ($t->permanente) {
        //             return true;
        //         }
        //         if ($t->fecha_inicio && $t->fecha_fin >= $hoy) {
        //             return $t->fecha_inicio <= $hoy && $t->fecha_fin >= $hoy;
        //         }
        //         return false;
        //     })->map(function ($t) {
        //         return [
        //             'src' => $t->ruta,
        //             'title' => $t->titulo
        //         ];
        //     })->values()->toArray();

        // dd($this->videos);
       // dd($this->videos);

       $this->videos = [];
    }


    public function render()
    {
        return view('livewire.Administracion.dashboard.comunicados.comunicados-dashboard-main');
    }
}
