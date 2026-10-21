<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\Afiliacion\AfiliacionTitularesMail;
use App\Mail\Afiliacion\AfiliacionTitularesReafiliacionMail;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\TitularInstitucion;
use Illuminate\Support\Facades\Log;

class UserPassword extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pdf_reset($id_usuario, $password)
    {

        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario_modificador = User::with('personaUser')->find(auth()->user()->id);
        $mensajeQR = 'Usuario: ' . $datos_usuario_registro->email . '|| Nueva Contraseña: ' . $password . ' || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(80)
            ->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        // dd($datos_usuario_registro);
        $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y H:i:s');
        $nombre_pdf = $datos_usuario_registro->personaUser->nombres . ' - New Password ' . Carbon::now()->format('d-m-Y');
        $pdf = PDF::loadView('Administracion.Usuarios.pdfResetPassword', compact(
            'datos_usuario_registro',
            'password',
            'fecha',
            'datos_usuario_modificador',
            'qrCode'
        ))
            ->setPaper('letter', 'portrait');
        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
    public function pdf_user_new($id_usuario)
    {
        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario_modificador = User::with('personaUser')->find(auth()->user()->id);
        // dd($datos_usuario_registro->personaUser->id_tipo_asegurado);
        if ($datos_usuario_registro->personaUser->id_tipo_asegurado === 1) {
            $this->actualizarUsuarioSSU($id_usuario);
        }
        $mensajeQR = 'Usuario: ' . $datos_usuario_registro->email . '|| Contraseña: ' . $datos_usuario_registro->personaUser->ci . ' || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(80)
            ->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        //dd($datos_usuario_modificador);
        $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y H:i:s');
        $nombre_pdf = $datos_usuario_registro->personaUser->nombres . ' - Documento confidencial ' . Carbon::now()->format('d-m-Y');

        $pdf = PDF::loadView('Administracion.Usuarios.pdfUsuarioContraseña', compact(
            'datos_usuario_registro',
            'fecha',
            'datos_usuario_modificador',
            'qrCode'
        ))
            ->setPaper('letter', 'portrait');

        $pdfContent = $pdf->output();
        $ftpPath = 'Administracion/Usuarios/Credenciales/' . $id_usuario . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);

        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    public function pdf_user_afiliacion($id_titutlar)
    {

        $datos_titular = Titular::with('titularPersona')->where('id', $id_titutlar)->first();

        /*$datos_titular = Titular::join('administracion.persona as per', 'per.id', '=', 'afiliacion.titular.id_persona')
        ->where('afiliacion.titular.id', '=', $id_titutlar)->first();*/
        $usuario = User::where('id_persona', $datos_titular->id_persona)->first();
        $this->actualizarUsuarioSSU($usuario->id);

        $datos_usuario_registro = User::with('personaUser')->find($usuario->id);
        $datos_usuario_modificador = User::with('personaUser')->find(auth()->user()->id);

        $mensajeQR = 'Usuario: ' . $datos_usuario_registro->email . '|| Contraseña: ' . $datos_usuario_registro->personaUser->ci . ' || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(80)
            ->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        //dd($datos_usuario_modificador);
        $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y H:i:s');
        $nombre_pdf = $datos_usuario_registro->personaUser->nombres . ' - Documento confidencial ' . Carbon::now()->format('d-m-Y');

        $pdf = PDF::loadView('Administracion.Usuarios.pdfUsuarioContraseña', compact(
            'datos_usuario_registro',
            'fecha',
            'datos_usuario_modificador',
            'qrCode'
        ))
            ->setPaper('letter', 'portrait');

        $pdfContent = $pdf->output();
        $ftpPath = 'Administracion/Usuarios/Credenciales/' . $usuario->id . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);

        //mail
        $imageBase64 = "asd";
        $asunto = 'Sistema Sighov2 - Unidad de Afiliación';
        $email = $datos_titular->correo;
        $correo = new AfiliacionTitularesMail($asunto, $datos_titular, $imageBase64, $usuario->email, $datos_titular->titularPersona->ci, $usuario->id);

        try {
            Mail::to($email)->send($correo);
        } catch (\Exception $e) {
            // Log del error (opcional)
            Log::error('No se pudo enviar el correo: ' . $e->getMessage());
        }

        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
    public function GenerarUsuario($persona_encontrada)
    {
        // Convertir a mayúsculas para asegurar consistencia
        $nombres = str_replace(' ', '', mb_strtolower($persona_encontrada->nombres));
        $p_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->p_apellido));
        $s_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->s_apellido));

        // Inicializar el email
        $email = '';

        if (!empty($p_apellido) == true && !empty($s_apellido) == true) {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($s_apellido, 0, 1);
        }

        if (!empty($p_apellido) == false && !empty($s_apellido) == true) {
            $email = substr($nombres, 0, 1) . $s_apellido . substr($s_apellido, 0, 1);
        }

        if (!empty($p_apellido) == true && !empty($s_apellido) == false) {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($p_apellido, 0, 1);
        }

        return $email;
    }
    public function pdf_user_reafiliacion($id_titular_tra)
    {
        $id_titutlar = $id_titular_tra;

        $datos_titular = Titular::join('administracion.persona as per', 'per.id', '=', 'afiliacion.titular.id_persona')
            ->where('afiliacion.titular.id', '=', $id_titutlar)->first();
        $usuario = User::where('id_persona', $datos_titular->id_persona)->first();
        $numeroInvertido = (int)strrev((string)$datos_titular->id_persona);
        $resultado = $numeroInvertido + 3;
        $persona_encontrada = Persona::find($datos_titular->id_persona);
        $usuario_generado = $this->GenerarUsuario($persona_encontrada);
        $usuario->update([
            'email' => $usuario_generado . '-' . $resultado,
            'estado' => true
        ]);
        $this->actualizarUsuarioSSU($usuario->id);
        $datos_usuario_registro = User::with('personaUser')->find($usuario->id);
        $datos_usuario_modificador = User::with('personaUser')->find(auth()->user()->id);

        $mensajeQR = 'Usuario: ' . $datos_usuario_registro->email . '|| Contraseña: ************* || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(80)
            ->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        //dd($datos_usuario_modificador);
        $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y H:i:s');
        $nombre_pdf = $datos_usuario_registro->personaUser->nombres . ' - Documento confidencial ' . Carbon::now()->format('d-m-Y');

        $pdf = PDF::loadView('Administracion.Usuarios.pdfUsuarioContraseñaRepetir', compact(
            'datos_usuario_registro',
            'fecha',
            'datos_usuario_modificador',
            'qrCode'
        ))
            ->setPaper('letter', 'portrait');

        $pdfContent = $pdf->output();
        $ftpPath = 'Administracion/Usuarios/Credenciales/' . $usuario->id . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);

        //mail
        $imageBase64 = "asd";
        $asunto = 'Sistema Sighov2 - Unidad de Afiliación';
        $email = $datos_titular->correo;
        //dd($asunto, $datos_titular, $imageBase64,$usuario->email,$datos_titular->ci,$usuario->id);
        $correo = new AfiliacionTitularesReafiliacionMail($asunto, $datos_titular, $imageBase64, $usuario->email, $datos_titular->ci, $usuario->id);
        try {
            Mail::to($email)->send($correo);
        } catch (\Exception $e) {
            // Log del error (opcional)
            Log::error('No se pudo enviar el correo: ' . $e->getMessage());
        }

        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }


    public function pdf_user_reafiliacion_beneficiario($idBeneficiario)
    {
        //

        $datos_beneficiario = Beneficiario::find($idBeneficiario);

        $usuario = User::where('id_persona', $datos_beneficiario->id_persona)->first();
        $datos_usuario_registro = User::with('personaUser')->find($usuario->id);
        $datos_usuario_modificador = User::with('personaUser')->find(auth()->user()->id);

        $numeroInvertido = (int)strrev((string)$datos_beneficiario->id_persona);
        $resultado = $numeroInvertido + 3;
        $persona_encontrada = Persona::find($datos_beneficiario->id_persona);
        $usuario_generado = $this->GenerarUsuario($persona_encontrada);
        $usuario->update([
            'email' => $usuario_generado . '-' . $resultado,
            'estado' => true
        ]);
        // dd($usuario);
        $mensajeQR = 'Usuario: ' . $datos_usuario_registro->email . '|| Contraseña: ************* || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(80)
            ->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        //dd($datos_usuario_modificador);
        $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y H:i:s');
        $nombre_pdf = $datos_usuario_registro->personaUser->nombres . ' - Documento confidencial ' . Carbon::now()->format('d-m-Y');

        $pdf = PDF::loadView('Administracion.Usuarios.pdfUsuarioContraseñaRepetir', compact(
            'datos_usuario_registro',
            'fecha',
            'datos_usuario_modificador',
            'qrCode'
        ))
            ->setPaper('letter', 'portrait');

        $pdfContent = $pdf->output();
        $ftpPath = 'Administracion/Usuarios/Credenciales/' . $usuario->id . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);

        //mail
        $imageBase64 = "asd";
        $asunto = 'Sistema Sighov2 - Unidad de Afiliación';
        $email = $datos_beneficiario->correo;
        //dd($asunto, $datos_beneficiario, $imageBase64,$usuario->email,$datos_beneficiario->ci,$usuario->id);
        $correo = new AfiliacionTitularesReafiliacionMail($asunto, $datos_beneficiario, $imageBase64, $usuario->email, $datos_beneficiario->ci, $usuario->id);
        try {
            Mail::to($email)->send($correo);
        } catch (\Exception $e) {
            // Log del error (opcional)
            Log::error('No se pudo enviar el correo: ' . $e->getMessage());
        }

        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
    public function extraerAntesDelGuion($texto)
    {
        // Si la cadena ya contiene "@ssulapaz.org", la retorna sin cambios
        if (strpos($texto, '@ssulapaz.org') !== false) {
            return $texto;
        }

        // Divide el texto en dos partes usando el primer guion encontrado
        $partes = explode('-', $texto, 2);

        // Retorna la parte antes del guion
        return $partes[0];
    }

    public function cambioUsuarioSSU($usuario)
    {
        $ssu = '@ssulapaz.org';

        // Si el email ya tiene "@ssulapaz.org", no hacer nada
        if (strpos($usuario->email, $ssu) !== false) {
            return;
        }

        $name_user = $this->extraerAntesDelGuion($usuario->email);
        $new_name_user = $name_user . $ssu;

        // Solo actualizar si el email realmente cambia
        if ($usuario->email !== $new_name_user) {
            $usuario->email = $new_name_user;
            $usuario->save();
        }
    }
    public function actualizarUsuarioSSU($id_usuario)
    {
        $response = false;
        // $id_usuario = 1;
        // titularInstitucionConInstitucion
        $usuario = User::with(['personaUser.PersonaTitular.titularConTitularInstitucion'])->find($id_usuario);

        // $id_titular=$usuario->personaUser->PersonaTitular->id;
        // $consulta = TitularInstitucion::where('id_titular', $id_titular)->orderBy('id', 'dec');

        // return $usuario;
        if (count($usuario->personaUser->PersonaTitular) != 0) {

            $titular = Titular::where('id_persona', $usuario->id_persona)
                ->where('estado_vigencia', 'VIGENTE')
                ->where('estado', true)
                ->orderBy('id', 'desc')
                ->first();
            $id_titular = $titular->id;
            $titular_institucion = TitularInstitucion::where('id_institucion', 4)
                ->where('id_titular', $id_titular)->orderBy('id', 'desc')->first();
            if ($titular_institucion) {
                $this->cambioUsuarioSSU($usuario);
                $response = $usuario->email;
            }
        }

        // return $usuario;
        return $response;
    }
    public function credencialesInicio($id)
    {
        $id_usuario = $id;
        // TODO: aqui falta acutalizar el usuario a ssulapaz.openssl_csr_get_public_key
        $this->actualizarUsuarioSSU($id_usuario);
        // return response()->json($this->actualizarUsuarioSSU($id_usuario), 200);

        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        $datos_usuario_modificador = User::with('personaUser')->find(auth()->user()->id);

        $mensajeQR = 'Usuario: ' . $datos_usuario_registro->email . '|| Contraseña: ' . $datos_usuario_registro->personaUser->ci . ' || Verificado y validado por el SSU LP - Sistema SIGHO v2.';
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(80)
            ->color(3, 125, 155)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($mensajeQR));

        //dd($datos_usuario_modificador);
        $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y H:i:s');
        $nombre_pdf = $datos_usuario_registro->personaUser->nombres . ' - Documento confidencial ' . Carbon::now()->format('d-m-Y');

        $pdf = PDF::loadView('Administracion.Usuarios.pdfUsuarioContraseña', compact(
            'datos_usuario_registro',
            'fecha',
            'datos_usuario_modificador',
            'qrCode'
        ))
            ->setPaper('letter', 'portrait');

        $pdfContent = $pdf->output();
        $ftpPath = 'Administracion/Usuarios/Credenciales/' . $id_usuario . '.pdf';
        Storage::disk('ftp')->put($ftpPath, $pdfContent);

        return $pdf->download($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
