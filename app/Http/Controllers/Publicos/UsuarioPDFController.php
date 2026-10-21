<?php

namespace App\Http\Controllers\Publicos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use DomPDF;
use Illuminate\Support\Facades\Storage;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
class UsuarioPDFController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function crear_usuario($id)
    {
            
        $id_usuario = $id;
        // TODO: aqui falta acutalizar el usuario a ssulapaz.openssl_csr_get_public_key
        $this->actualizarUsuarioSSU($id_usuario);
        $datos_usuario_registro = User::with('personaUser')->find($id_usuario);
        
        $datos_usuario_modificador = User::with('personaUser')->find(1);

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

        $pdf = DomPDF::loadView('Publicos.ObtenerUsuario.pdfUsuarioContraseña', compact(
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

    public function actualizarUsuarioSSU($id_usuario)
    {
        $response = false;
        // $id_usuario = 1;
        // titularInstitucionConInstitucion
        $usuario = User::with(['personaUser.PersonaTitular.titularConTitularInstitucion'])->find($id_usuario);
        //dd($usuario);

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
    public function index()
    {
        //
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
