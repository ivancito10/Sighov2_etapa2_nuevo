<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Residencia;
use App\Models\Plataforma\Especialista;

class Fisioterapeuta extends Authenticatable
{

}