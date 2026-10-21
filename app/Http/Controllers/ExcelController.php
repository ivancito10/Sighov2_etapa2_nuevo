<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Storage;
use App\Imports\UsersImport;
use Carbon\Carbon;

class ExcelController extends Controller
{
    //
    public function archivo(Request $request)
    {
        // Validar que se haya subido un archivo
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
            'institucion' => 'required|string',
            'mes' => 'required|string',
            'gestion' => 'required|string',
            'tipodeaporte' => 'required|string',
        ]);

        // Obtener el archivo subido
        $file = $request->file('archivo');
        // Definir el nombre personalizado para el archivo
        // Obtener el archivo subido y otros datos de la solicitud
        $file = $request->file('archivo');
        $institucion = $request->input('institucion');
        $mes = $request->input('mes');
        $gestion = $request->input('gestion');
        $tipodeaporte = $request->input('tipodeaporte');
        $fecha = Carbon::now()->format('Y-m-d_H-i-s');

        // Construir el nombre personalizado del archivo
        $customFileName = "{$institucion}-{$mes}-{$gestion}-{$tipodeaporte}-{$fecha}." . $file->getClientOriginalExtension();


        // Guardar el archivo en el servidor FTP con el nombre personalizado
        $filePath = 'uploads/' . $customFileName;
        Storage::disk('ftp')->put($filePath, fopen(
            $file,
            'r+'
        ));
        $fileContents = Storage::disk('ftp')->get($filePath);
        // // Leer el contenido del archivo Excel
        // $data = Excel::toArray(new HeadingRowImport, $file);
        // Leer el contenido del archivo Excel y almacenarlo en un array
        $import = new UsersImport;
        $data = Excel::toArray($import, $file);

        // Procesar los datos y guardarlos en la base de datos
        $import->array($data[0]);
        $data_archive = [
            'originalName' => $file->getClientOriginalName(),
            'mimeType' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
        ];

        // Guardar el archivo en el servidor FTP
        $path = Storage::disk('ftp')->put('/', $file);

        // Mostrar la información del archivo para verificar que se recibió correctamente
        return response()->json([
            'Datos del archivo' => $data_archive,
            'data' => $data,
            'file' => $path, // Nombre del archivo subido en el FTP
        ], 200);





        // Mostrar la información del archivo para verificar que se recibió correctamente
        // return response()->json([
        //     'originalName' => $file->getClientOriginalName(),
        //     'mimeType' => $file->getMimeType(),
        //     'size' => $file->getSize(),
        //     'extension' => $file->getClientOriginalExtension(),
        // ], 200);
    }
    public function multiples_archivos(Request $request)
    {
        // Validar que se hayan subido archivos
        $request->validate([
            'archivos.*' => 'required|file',
        ]);

        // Obtener todos los archivos subidos
        $archivos = [];
        foreach ($request->file('archivos') as $archivo) {
            $archivos[] = [
                'originalName' => $archivo->getClientOriginalName(),
                'mimeType' => $archivo->getMimeType(),
                'size' => $archivo->getSize(),
                'extension' => $archivo->getClientOriginalExtension(),
            ];
        }

        // Mostrar la información de los archivos para verificar que se recibieron correctamente
        return response()->json($archivos, 200);
    }
}
