<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{

    public $search = '';
    public function render()
    {
        return view('livewire.counter');
    }
}
