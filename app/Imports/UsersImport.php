<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToArray;

class UsersImport implements ToArray
{
    public function array(array $rows)
    {
        foreach ($rows as $row) {
            // Crea un nuevo modelo User con los datos de la fila
            $user = new User([
                'name' => $row[0],
                'email' => $row[1],
                'password' => Hash::make($row[2]),
            ]);

            // Guarda el modelo User en la base de datos
            $user->save();
        }
    }
}
