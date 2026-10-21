<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonaAsus;
use Illuminate\Support\Facades\Validator; 
class personaAsusController extends Controller
{
    //
    public function index(){
        return"hola desde el controlador";
    }
    public function listarPersonaAsus(){
        $personaAsus=PersonaAsus::all();
        if ($personaAsus->isEmpty()) {
            $data=[
                'status'=>200,
                'message'=>'No se econtraron datos de PersonaAsus',
            ];
            return response()->json($data,200);
        }
        $data=[
            'status'=>200,
            'personalAsus'=>$personaAsus,

        ];
        return response()->json($data,200);
        
    }
    public function obtenerPersonaAsus($id){
        $personaAsus=PersonaAsus::find($id);
        if (!$personaAsus) {
            $data=[
                'status'=>404,
                'message'=>'No se econtraron datos de la PersonaAsus',
            ];
            return response()->json($data,404);
        }
        $data=[
            'status'=>200,
            'personalAsus'=>$personaAsus,
        ];
        return response()->json($data,200);
        
    }
    public function crearPersonaAsus(Request $request){
        $validator=Validator::make($request->all(),[
            'nombre'=>'required',
            'apPaterno'=>'required',
            'apMaterno'=>'required',
            'ci'=>'required',
            'complemento'=>'required',
            'tipo_documento'=>'required',
            'matricula'=>'required',
            'fecha_nacimiento'=>'required|date',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $personaAsus = PersonaAsus::create([
            'nombre'=>$request->nombre,
            'apPaterno'=>$request->apPaterno,
            'apMaterno'=>$request->apMaterno,
            'ci'=>$request->ci,
            'complemento'=>$request->complemento,
            'tipo_documento'=>$request->tipo_documento,
            'matricula'=>$request->matricula,
            'fecha_nacimiento'=>$request->fecha_nacimiento,
        ]);
        if (!$personaAsus) {
            $data = [
                'status' => 500,
                'message' => 'Error al crear la personaAsus',
            ];
            return response()->json($data, 500);
        }
        $data = [
            'status' => 201,
            'student' => $personaAsus,
        ];

        return response()->json($data, 201);    

    }
       public function actualizarPersonaAsus(Request $request,$id){
        $personaAsus=PersonaAsus::find($id);
        if (!$personaAsus) {
            $data=[
                'status'=>404,
                'message'=>'No se econtraron datos de la PersonaAsus',
            ];
            return response()->json($data,404);
        }
        $validator=Validator::make($request->all(),[
            'nombre'=>'required',
            'apPaterno'=>'required',
            'apMaterno'=>'required',
            'ci'=>'required',
            'complemento'=>'required',
            'tipo_documento'=>'required',
            'matricula'=>'required',
            'fecha_nacimiento'=>'required|date',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $personaAsus = PersonaAsus::where('id', $id)->update([
            'nombre'=>$request->nombre,
            'apPaterno'=>$request->apPaterno,
            'apMaterno'=>$request->apMaterno,
            'ci'=>$request->ci,
            'complemento'=>$request->complemento,
            'tipo_documento'=>$request->tipo_documento,
            'matricula'=>$request->matricula,
            'fecha_nacimiento'=>$request->fecha_nacimiento,
        ]);
        if (!$personaAsus) {
            $data = [
                'status' => 500,
                'message' => 'Error al actualizar la personaAsus',
            ];
            return response()->json($data, 500);
        }
        $data = [
            'status' => 201,
            'messge'=> "¨PersonaAsus actualizada exitosamente",
            'student' => $personaAsus,

        ];

        return response()->json($data, 201);    
    }
}