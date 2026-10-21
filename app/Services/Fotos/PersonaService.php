<?php

namespace App\Services\Fotos;

use App\Models\Administracion\Persona;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Services\Fotos\ImagenService;

class PersonaService
{
    protected $imagenService;

    public function __construct(ImagenService $imagenService)
    {
        $this->imagenService = $imagenService;
    }
    public function obtener_imagen_persona($id_persona)
    {
        // $arrayDatos = [];

        $persona = Persona::where('id', $id_persona)
            ->whereNot('id_tipo_asegurado', 0)
            ->where('afiliado', true)
            ->with([
                'tipoAseguradoPersona',
                'PersonaTitular',
                'PersonaBeneficiario.titularBeneficiario',
                'autorizacionInteriorPersonaInterior',
                'convenioPersona',
                'PersonaEstudiante'
            ])
            ->orderBy('id', 'asc')
            ->first();

        // $idPersonaTitular = 0;

        $tipoAsegurado = $persona->tipoAseguradoPersona->tipo_asegurado ?? null;
        $foto = null;
        $liga = null;
        switch ($tipoAsegurado) {
            case 'TITULAR':
                $titular = $persona->PersonaTitular->where('estado', true)->first();
                // $idPersonaTitular = $titular->id_persona ?? 0;
                $liga = 'Afiliacion/FOTOS/';
                $foto = $titular->foto_nombre ?? null;
                break;

            case 'BENEFICIARIO DEL INTERIOR':
                $autorizacionInterior = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                // $idPersonaTitular = $autorizacionInterior->id_persona_titular ?? 0;
                $liga = 'Vigencia/FOTOS/CONVENIO/';
                $foto = $autorizacionInterior->foto_nombre ?? null;
                break;

            case (Str::contains($tipoAsegurado, 'BENEFICIARIO')):
                $beneficiario = $persona->PersonaBeneficiario->where('estado', true)->first();
                // $idPersonaTitular = $beneficiario->titularBeneficiario->id_persona ?? 0;
                $liga = 'Afiliacion/FOTOS/';
                $foto = $beneficiario->foto_nombre ?? null;
                break;

            case 'TITULAR DEL INTERIOR':
                $autorizacionInteriorTitular = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                // $idPersonaTitular = $autorizacionInteriorTitular->id_persona_titular ?? 0;
                $liga = 'Vigencia/FOTOS/CONVENIO/';
                $foto = $autorizacionInteriorTitular->foto_nombre ?? null;
                break;

            case 'TITULAR CONVENIO':
                $convenio = $persona->convenioPersona->where('estado', true)->first();
                // $idPersonaTitular = $convenio->id_persona ?? 0;
                $liga = 'Vigencia/FOTOS/CONVENIO/';
                $foto = $convenio->foto_nombre ?? null;
                break;

            case 'ESTUDIANTE':
                $estudiante = $persona->PersonaEstudiante
                    ->where('estado_vigencia', true)
                    ->where('estado', true)
                    ->where('estado_validar', 'AFILIADO')
                    ->first();
                // $idPersonaTitular = $estudiante->id_persona ?? 0;
                $liga = 'Afiliacion/SSUE/FOTOS/';
                $foto = $estudiante->foto_nombre ?? null;
                break;

            default:
                // $idPersonaTitular = 0;
                break;
        }

        // $arrayDatos[] = [
        //     'id_persona' => $persona->id,
        //     // 'nombres' => $persona->nombres,
        //     // 'p_apellido' => $persona->p_apellido,
        //     // 's_apellido' => $persona->s_apellido,
        //     // 'matricula_seguro' => $persona->matricula_seguro,
        //     // 'sexo' => $persona->sexo,
        //     // 'fecha_nacimiento' => $persona->fecha_nacimiento,
        //     // 'ci' => $persona->ci,
        //     // 'complemento' => $persona->complemento,
        //     // 'id_tipo_asegurado' => $persona->id_tipo_asegurado,
        //     // 'tipo_asegurado' => $tipoAsegurado,
        //     // 'excepcion' => $persona->excepcion,
        //     // 'id_persona_titular' => $idPersonaTitular,
        //     'foto' => $foto,
        //     'liga' => $liga,
        // ];
        $nombre_imagen = $foto;
        $direccion_foto = $liga;
        return $this->imagenService->obtener_imagen($nombre_imagen, $direccion_foto);

        // return $arrayDatos;
    }
    public function url_imagen_persona($id_persona)
    {
        $imagenUrl = URL::temporarySignedRoute(
            'obtener_imagen_persona',        // Nombre de la ruta que devuelve la imagen
            now()->addMinutes(90),     // Duración de la validez (10 minutos aquí)
            ['id_persona' => $id_persona]   // Parámetros que necesita la ruta
        );
        return $imagenUrl;
        // return url('/api/administracion/obtener_imagen_usuario/' . $id_user);
    }
}
