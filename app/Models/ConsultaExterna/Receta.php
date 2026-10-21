<?php

namespace App\Models\ConsultaExterna;

use App\Models\Administracion\Persona;
use App\Models\User;
use App\Models\ConsultaExterna\TipoConsulta;
use App\Models\ConsultaExterna\Consulta;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\Farmacia\Medicamento;
use App\Models\Plataforma\Especialista;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.receta';

    protected $fillable = [
        'id',
        'nro_receta',
        'fecha_entrega',
        'fecha_transcripcion',
        'manual',
        'estado',
        'dias_tratamiento',
        'id_historia_clinica',
        'id_tipo_consulta',
        'id_especialista',
        'id_persona_especialista',
        'id_persona',
        'id_usuario_dispensa',
        'motivo_anulacion',
        'fecha_dispensado',
        'id_usuario_anula',
        'fecha_anulado',
        'clave_unica',
        'id_estado_validacion',
        'id_persona_transcribe',
        'nro_receta_manual',
        'observacion_quirofano',
        'fecha_caducidad',
        'fecha_proxima_consulta',
        'id_user_created',
        'id_user_updated',
        'created_at',
    ];

    //RELACION CON LA TABLA CONSULTA

    public function consultaReceta()
    {
        return $this->belongsTo(Consulta::class, 'id_consulta', 'id');
    }

    //RELACION CON LA TABLA ESTADO VALIDACION
    public function estadoValidacionReceta()
    {
        return $this->belongsTo(EstadoValidacion::class, 'id_estado_validacion', 'id');
    }

    //RELACION CON LA TABLA TIPO CONSULTA

    public function tipoConsultaReceta()
    {
        return $this->belongsTo(TipoConsulta::class, 'id_tipo_consulta', 'id');
    }

    //RELACION CON LA TABLA PERSONA
    public function personaReceta()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    public function personaEspecialistaReceta()
    {
        return $this->belongsTo(Persona::class, 'id_persona_especialista', 'id');
    }

    public function recetaEspecialista()
    {
        return $this->belongsTo(Especialista::class, 'id_especialista', 'id');
    }

    //RELACION N:M CON MEDICAMENTOS
    public function medicamentosReceta()
    {
        return $this->belongsToMany(
            Medicamento::class,
            'consulta_externa.receta_medicamento', // nombre de la tabla pivote
            'id_receta',                           // FK en pivote hacia esta tabla
            'id_medicamento'                       // FK en pivote hacia medicamentos
        )
            ->withPivot([
                'id',
                'cantidad',
                'indicaciones',
                'forma_adm',
                'recomendacion',
                'fecha_ini',
                'fecha_fin',
                'id_user_created',
                'id_user_updated',
                'created_at',
                'updated_at',
                'dias_validez',
            ])
            ->using(RecetaMedicamento::class)  // modelo pivote personalizado
            ->withTimestamps();
    }

    //RELACION CON LA TABLA MEDICAMENTOS REAL
    public function recetaMedicamentoReceta()
    {
        return $this->hasMany(RecetaMedicamento::class, 'id_receta', 'id');
    }

    //RELACION CON LA TABLA RECETA

    public function receta_historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'id_historia_clinica', 'id');
    }

    //RELACION CON LA TABLA USUARIO

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }

    public function usuarioDispensaReceta()
    {
        return $this->belongsTo(User::class, 'id_usuario_dispensa', 'id');
    }
    public function usuarioAnuladorReceta()
    {
        return $this->belongsTo(User::class, 'id_usuario_anula', 'id');
    }
    // PERSONA
    public function personaTranscribeReceta()
    {
        return $this->belongsTo(Persona::class, 'id_persona_transcribe', 'id');
    }
}
