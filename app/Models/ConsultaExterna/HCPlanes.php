<?php

namespace App\Models\ConsultaExterna;

use App\Models\Laboratorio\Solicitudes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HCPlanes extends Model
{
    use HasFactory;
    protected $table = 'consulta_externa.hc_planes';

    protected $fillable = [
        'id',
        'id_historia_clinica',
        'id_plan',
        'id_corresponde',
        'id_user_created',
        'id_user_updated',
        'id_created_at',
        'id_updated_at',
    ];

    public function Planes()
    {
        return $this->belongsTo(Planes::class, 'id_plan', 'id');
    }
    public function HistoriaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'id_historia_clinica', 'id');
    }

    public function PlanReceta()
    {
        return $this->belongsTo(Receta::class, 'id_corresponde', 'id'); // ajusta la FK si es otra
    }

    public function PlanLaboratorio()
    {
        return $this->belongsTo(Solicitudes::class, 'id_corresponde', 'id'); // ajusta la FK si es otra
    }

    public function PlanImagenologia()
    {
        return $this->belongsTo(Imagenologia::class, 'id_corresponde', 'id');
    }
    public function PlanBajaMedica()
    {
        return $this->belongsTo(BajaMedica::class, 'id_corresponde', 'id'); // ajusta la FK si es otra
    }

    public function PlanFisioterapia()
    {
        return $this->belongsTo(Fisioterapia::class, 'id_corresponde', 'id');
    }

    public function PlanInternacion()
    {
        return $this->belongsTo(Internacion::class, 'id_corresponde', 'id');
    }

    public function PlanReferencia()
    {
        return $this->belongsTo(Referencia::class, 'id_corresponde', 'id');
    }

    public function PlanInterconsulta()
    {
        return $this->belongsTo(Interconsulta::class, 'id_corresponde', 'id');
    }

    public function getCodigoAttribute()
    {
        switch ($this->id_plan) {
            case 1:
                $abbr = 'REC/';
                $correlativo = optional($this->PlanReceta)->nro_receta;
                break;
            case 2:
                $abbr = 'LAB/';
                $correlativo = optional($this->PlanLaboratorio)->id_solicitud_s1;
                break;
            case 3:
                $abbr = 'IMG/';
                $correlativo = optional($this->PlanImagenologia)->nro_solicitud;
                break;
            case 4:
                $abbr = 'BMED/';
                $correlativo = optional($this->PlanBajaMedica)->nro_baja_medica;
                break;
            case 5:
                $abbr = 'FISIO/';
                $correlativo = optional($this->PlanFisioterapia)->nro_solicitud;
                break;
            case 6:
                $abbr = 'INT/';
                $correlativo = optional($this->PlanInternacion)->nro_internacion;
                break;
            case 7:
                $abbr = 'REF/';
                $correlativo = optional($this->PlanReferencia)->nro_referencia;
                break;
            case 8:
                $abbr = 'INT-CONSULTA/';
                $correlativo = optional($this->PlanInterconsulta)->nro_interconsulta;
                break;
            default:
                return null;
        }

        return $correlativo ? $abbr . $correlativo : null;
    }
}
