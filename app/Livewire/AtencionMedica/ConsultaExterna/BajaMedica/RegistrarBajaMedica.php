<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\BajaMedica;

use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Livewire\AtencionMedica\OtrasConsultas\RegistroSoap;
use App\Models\Administracion\LinkServicio;
use App\Models\ConsultaExterna\BajaMedica;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\TipoBajaMedica;
use App\Services\DatosEspecialista;
use App\Services\EspecialidadService;
use App\Services\ObtenerToken;
use App\Services\ServicioPersona;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistrarBajaMedica extends Component
{
    public $datoHistoriaClinica;

    public $plan = 4; //4 = Baja Medica

    public $fecha_ini;

    public $fecha_fin;

    public $dias_baja;

    public $descripcion;

    public $nro_resolucion;

    public $tipos_baja = [];

    public $id_tipo_baja;

    public $beneficiarioAportes;

    public $tipo_consulta = null;

    public $listEspecialidades;

    public $fecha_min;

    public $idInstitucionAsegurado;

    public $datosBajaEncontrada;

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.baja-medica.registrar-baja-medica');
    }

    #[On('recibirHCBajaMedica')]
    public function recibirHCBajaMedica($data, $instituciones)
    {
        $this->datoHistoriaClinica = $data;
        $this->idInstitucionAsegurado = $instituciones !== [] ? $instituciones['id_institucion'] : null;
        //$this->idInstitucionAsegurado = $instituciones;
        $this->beneficiarioAportes = $this->beneficiarioConAportes($data['id_persona']);
        //$this->tipos_baja = TipoBajaMedica::all();
        //dd($this->datoHistoriaClinica, $this->idInstitucionAsegurado);
    }

    public function mount()
    {
        $especialidadService = new EspecialidadService();
        $this->listEspecialidades = $especialidadService->obtenerEspecialidades();
        $this->tipo_consulta = request()->query('tipo_at');
        //$this->tipos_baja = TipoBajaMedica::all();
        $this->GenerarTiposBaja();
        $this->fecha_ini = now()->format('Y-m-d');
        $this->fecha_max = null;
        $this->fecha_min = null;
    }

    protected function messages()
    {
        return [
            'fecha_ini.after_or_equal' => 'La fecha debe ser hoy o una fecha posterior.',
            'fecha_ini.required' => 'La fecha de inicio es obligatoria.',
            'dias_baja.required' => 'EL CAMPO ES OBLIGATORIO',
            'dias_baja.min' => 'Debe ingresar al menos 1 día',
        ];
    }

    protected function rules(): array
    {
        $tipo = (int) $this->id_tipo_baja;
        //dd($tipo);
        $maxDias = 7;
        if (in_array($tipo, [2, 3])) {
            $maxDias = 7;
        } elseif ($tipo === 1) {
            $maxDias = 90;
        }

        return [
            'fecha_ini' => [
                $tipo !== 4 ? 'required' : '',
                'date',
                //$tipo !== 3 ? 'after_or_equal:today' : '', // solo si tipo 1
            ],
            'dias_baja' => [
                $tipo !== 4 ? 'required' : '',
                'min:0',
                'max:' . $maxDias,
            ],
            'nro_resolucion' => [
                $tipo == 7 ? 'required' : '',
            ],
            'id_tipo_baja' => 'required',
        ];
    }

    public function updated($property, $value)
    {
        if (in_array($property, ['dias_baja', 'fecha_ini'])) {

            // Valida solo cuando cambia dias_baja (para no fastidiar al cambiar fecha)
            if ($property === 'dias_baja') {
                $this->validateOnly('dias_baja', [
                    'dias_baja' => [
                        'required',
                        'integer',
                        'min:1',
                        'max:' . (int) $this->max_dias,
                    ],
                ], [
                    'dias_baja.required' => 'El campo es obligatorio.',
                    'dias_baja.max' => 'Cantidad ingresada excedida. (' . (int) $this->max_dias . ' dias)',
                    'dias_baja.min' => 'Debe ser al menos 1 día',
                    'dias_baja.integer' => 'Ingrese un valor válido',
                ]);
            }

            if ($this->fecha_ini && $this->dias_baja) {
                $this->calcularFechaFin();
            }
        }
    }

    protected function calcularFechaFin(): void
    {
        if ($this->fecha_ini && $this->dias_baja !== null && $this->dias_baja >= 0) {
            $this->fecha_fin = Carbon::parse($this->fecha_ini)
                ->addDays($this->dias_baja - 1)
                ->format('Y-m-d');
        } else {
            $this->fecha_fin = null;
        }
    }

    //   aqui raquel
    #[On('registrarBajaMedica')]
    public function registrarBajaMedica()
    {
        $this->validate();
        try {
            if ($this->beneficiarioAportes) {
                if (! $this->beneficiarioAportes['tranferencia_aportes']) {
                    return $this->dispatch('SinTransfAportes');
                }
            }
            if ($this->consultarBajasVigentes($this->datoHistoriaClinica['id_persona'])) {
                // dd($this->consultarBajasVigentes($this->datoHistoriaClinica['id_persona']),$this->datosBajaEncontrada);
                $id_persona = $this->datoHistoriaClinica['id_persona'];
                $datos_baja = BajaMedica::where('id_persona', $id_persona)->latest()->first();
                $id_persona = $datos_baja->id_persona;
                $id_baja = $this->datosBajaEncontrada->id;

                // Serivicio Especialidad
                $hc_planes = HCPlanes::where('id_plan', 4)
                    ->where('id_corresponde', $id_baja)
                    ->first();

                $id_historia_clinica = $hc_planes->id_historia_clinica;

                $historia_clinica = HistoriaClinica::find($id_historia_clinica);
                $id_persona = $historia_clinica->id_persona;
                $id_especialista = $historia_clinica->id_especialista;

                // Servicio para mostrar datos del especialista
                $dato_esp = new DatosEspecialista();
                $datos = $dato_esp->datosEspecialista($id_especialista);
                $nombre_doctor = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
                $especialidad = $datos['especialidad_especialista']['especialidad'];

                // Servicio para mostrar datos tipo asegurado e institucion del asegurado
                $dato_per = new ServicioPersona();
                $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);

                $persona = $datos_persona['p_apellido'] . ' ' . $datos_persona['s_apellido'] . ' ' . $datos_persona['nombres'];
                $fecha_inicio = date('d/m/Y', strtotime($this->datosBajaEncontrada->fecha_ini_baja));
                $fecha_fin = date('d/m/Y', strtotime($this->datosBajaEncontrada->fecha_fin_baja));

                $fecha_inicio_d = Carbon::parse($this->datosBajaEncontrada->fecha_ini_baja);
                $fecha_fin_d = Carbon::parse($this->datosBajaEncontrada->fecha_fin_baja);

                $dias = $fecha_inicio_d->diffInDays($fecha_fin_d) + 1;

                // dd($datos_baja);
                return $this->dispatch('HayBajasVigentes', [
                    'id_persona' => $persona,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'dias' => $dias,
                    'especialidad' => $especialidad,
                    'nombre_doctor' => $nombre_doctor,
                ]);
            }
            DB::beginTransaction();
            $inicioAnio = Carbon::now()->startOfYear();
            $finAnio = Carbon::now()->endOfYear();
            // 2) Consulta entre esas dos fechas
            $ultimo = BajaMedica::whereBetween('created_at', [$inicioAnio, $finAnio])
                ->orderBy('nro_baja_medica', 'desc')
                ->lockForUpdate()
                ->value('nro_baja_medica');

            $siguiente = $ultimo ? $ultimo + 1 : 1;
            //dd($this->id_tipo_baja);

            if ($this->dias_baja === null) {
                $fechaFin = Carbon::parse($this->fecha_fin);
                $fechaIni = Carbon::parse($this->fecha_ini);
                $this->dias_baja = ($fechaFin->diffInDays($fechaIni)) + 1;
                // dd($this->dias_baja);
            }
            // dd($this->dias_baja);

            $datos = [
                'id_tipo_baja' => $this->id_tipo_baja,  //REVISAR
                'nro_baja_medica' => $siguiente,
                'fecha_ini_baja' => $this->fecha_ini,
                'fecha_fin_baja' => $this->fecha_fin,
                'dias_baja' => $this->dias_baja ?? null,
                'estado' => true,
                'descripcion' => $this->descripcion,
                'nro_resolucion_retroactivo' => $this->nro_resolucion,
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
                'id_persona' => $this->datoHistoriaClinica['id_persona'],
                'id_especialidad' => $this->datoHistoriaClinica['id_especialidad'],
                'id_especialista' => $this->datoHistoriaClinica['id_especialista'],
                'id_institucion' => $this->idInstitucionAsegurado,
            ];
            $datoCreado = BajaMedica::create($datos);
            if ($datoCreado) {
                $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], $this->plan, $datoCreado->id);
                DB::commit();
                $this->dispatch('bajaCreada');
                $this->reset('id_tipo_baja', 'fecha_ini', 'dias_baja', 'fecha_fin', 'descripcion');
                // para que se descargue el pdf baja medica
                $this->js('window.open("/consulta_externa/pdf/baja_medica/' . $datoCreado->id . '", "_blank")');
            } else {
                $this->dispatch('error');
            }
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error('Error guardando baja medica en historia clinica: ' . $e->getMessage());
        }
    }

    public function registrarTablaHCPlanes($id_hc, $id_plan, $id_corresponde)
    {
        try {
            $attributes = [
                'id_historia_clinica' => $id_hc,
                'id_plan' => $id_plan,
                'id_corresponde' => $id_corresponde,
            ];

            $values = [
                'id_user_created' => auth()->id(),
                'created_at' => now(),
            ];

            // Busca o crea; si existe, no hace nada, si no existe crea
            $HCplanCreado = HCPlanes::firstOrCreate($attributes, $values);
            if ($HCplanCreado) {
                if ($this->tipo_consulta != 1) {
                    $this->dispatch('actualizarPlanes')->to(RegistroSoap::class);
                } else {
                    $this->dispatch('actualizarPlanes')->to(ConsultaExternaRegister::class);
                }
            } else {
                $this->dispatch('error');
            }
        } catch (\Throwable $e) {
            Log::error('Error al registrar en HC_planes', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);
            $this->dispatch('error');
        }
    }

    public function consultarBajasVigentes($id_persona): bool
    {
        $ultimo_id = BajaMedica::where('id_persona', $id_persona)
            ->latest()
            ->value('id');
        if (is_null($ultimo_id)) {
            return false;
        }

        // dd($ultimo_id,$id_persona);
        $this->datosBajaEncontrada = BajaMedica::where('id_persona', $id_persona)
            ->whereDate('fecha_ini_baja', '<=', $this->fecha_fin)
            ->whereDate('fecha_fin_baja', '>=', $this->fecha_ini)
            ->where('estado', true)
            ->first();

        return BajaMedica::where('id_persona', $id_persona)
            ->whereDate('fecha_ini_baja', '<=', $this->fecha_fin)
            ->whereDate('fecha_fin_baja', '>=', $this->fecha_ini)
            ->where('estado', true)
            ->exists();
    }

    public function beneficiarioConAportes($id_persona)
    {
        $link = LinkServicio::where('nombre_servicio', 'BENEFICIARIO CON APORTES')->pluck('link_servicio')->first();
        $token = session('access_token');
        $response = Http::withToken($token)->get($link, [
            'id_persona' => $id_persona,
        ]);

        //dd($response,$response->json());
        return $response->json('data');
    }

    public $fecha_max = null;

    public $max_dias = 7;

    public function actualizarFecha()
    {
        //dd($this->id_tipo_baja);
        $this->reset('dias_baja', 'fecha_fin');
        $this->fecha_ini = now()->format('Y-m-d');

        if ($this->id_tipo_baja == 1 || $this->id_tipo_baja == 2) {
            $this->fecha_min = now()->toDateString();
            $this->fecha_max = now()->toDateString();
            $this->max_dias = 7;
        } elseif ($this->id_tipo_baja == 3) {
            $this->fecha_min = null;
            $this->fecha_max = null;
            $this->max_dias = 90;
        } elseif ($this->id_tipo_baja == 7) {
            $this->fecha_min = null;
            $this->fecha_max = null;
            $this->max_dias = 365;
        } else {
            $this->fecha_min = null;
            $this->fecha_max = null;
            $this->max_dias = 7;
        }

        // Resetear días cuando cambia tipo
        $this->dias_baja = null;
    }

    public function GenerarTiposBaja()
    {
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();

        $link = LinkServicio::where('nombre_servicio', 'OBTENER ESPECIALISTA POR ID USER')->pluck('link_servicio')->first();
        $token = session('access_token', '');
        $datos = [
            'id_usuario' => auth()->id(),
        ];
        $response = Http::withToken($token)->post($link, $datos);

        $datoEspecialista = $response->json('data');
        $id_esp_gineco = $this->listEspecialidades->search('GINECOLOGÍA Y OBSTETRICIA');
        //dd($link, $token, $datoEspecialista['id_especialidad'],$id_esp_gineco);
        //dd($this->tipo_consulta);
        if ((int) $this->tipo_consulta == 4) {
            $this->tipos_baja = TipoBajaMedica::all();
        } else {
            if ($datoEspecialista['id_especialidad'] == $id_esp_gineco) {
                //dd('es gineco', $datoEspecialista['id_especialidad'], $id_esp_gineco);
                $this->tipos_baja = TipoBajaMedica::orderBy('id', 'asc')
                    ->whereNot('nombre', 'INTERNACIÓN')
                    ->whereNot('nombre', 'POST-INTERNACIÓN')
                    ->get();
            } else {
                //dd('no es gineco', $datoEspecialista['id_especialidad'], $id_esp_gineco);
                $this->tipos_baja = TipoBajaMedica::whereNot('nombre', 'MATERNIDAD')
                    ->whereNot('nombre', 'INTERNACIÓN')
                    ->whereNot('nombre', 'POST-INTERNACIÓN')
                    ->get();
            }
        }
    }
}
