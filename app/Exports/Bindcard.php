<?php

namespace App\Exports;

use App\Models\Movimiento;
use Maatwebsite\Excel\Concerns\FromCollection;

class Bindcard implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Movimiento::all();
    }
}
