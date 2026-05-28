<?php

namespace App\Livewire\Vocero;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Vocero;
use App\Models\User;

class PanelVocero extends Component
{
    public $isCoordinador = false;
    public $isVocero = false;
    public $secciones = [];
    public $voceroInfo = null;

    // Propiedad para la búsqueda de estudiantes
    public $search = '';
    
    public $trayectoSeleccionado = '';
    public $seccionSeleccionada = '';
    public $trayectosDisponibles = [];
    public $seccionesDisponibles = [];

    public function mount()
    {
        $user = Auth::user();
        
        // Verificar si tiene el permiso (Coordinador u otro rol autorizado)
        if ($user->can('listar-voceros') || $user->can('listar-vocero') || $user->can('modulo-voceros') || $user->can('modulo-vocero') || in_array($user->usu_cod_rol, [1, 5, 11]) || in_array(session('active_role'), [1, 5, 11])) {
            $this->isCoordinador = true;
            $this->cargarSecciones();
        } else {
            // Verificar si es vocero
            $vocero = Vocero::where('id_estudiante', $user->usu_cedula)->first();
            if ($vocero) {
                $this->isVocero = true;
                $this->cargarInfoVocero($vocero);
            } else {
                abort(403, 'No tienes permisos para acceder a este módulo.');
            }
        }
    }

    public function cargarSecciones()
    {
        // En una implementación real, aquí se obtendría el PNF del coordinador actual
        // Para este ejemplo asumimos que podemos listar las secciones relacionadas al PNF
        // El PNF del coordinador de Informática es el 4.
        $pnfCoordinador = 4; // Esto debería ser dinámico dependiendo del coordinador

        $dbSogc = config('database.connections.emulacion_sogac_2.database');

        // Buscar secciones activas y sus estudiantes inscritos
        // El usuario proporcionó: "la tabla inscripcion tiene la relacion entre el estudiante y la seccion mediante la tabla seccion_unidad_docente"
        
        $seccionesQuery = DB::connection('emulacion_sogac_2')
            ->table('seccion as s')
            ->join('seccion_unidad_docente as sud', 's.sec_codigo', '=', 'sud.sud_cod_seccion')
            ->join('inscripcion as i', 'sud.sud_codigo', '=', 'i.ins_cod_seccion_unidad_docente')
            ->join('persona as p', 'i.ins_cedula', '=', 'p.per_cedula')
            ->join('semestre as sem', 's.sec_cod_semestre', '=', 'sem.sem_codigo')
            ->join('trayecto as tr', 'sem.sem_cod_trayecto', '=', 'tr.tra_codigo')
            ->select(
                's.sec_codigo',
                's.sec_nombre',
                'tr.tra_nombre as trayecto_nombre',
                'p.per_cedula',
                'p.per_nombres',
                'p.per_apellidos'
            )
            // Aquí se debería filtrar por el PNF del coordinador si hay una tabla intermedia, 
            // pero como la relación exacta hacia el programa no está especificada en esta query base,
            // asumimos que el coordinador tiene visibilidad de estas secciones.
            ->where('s.sec_estatus', 'A');

        if (!empty($this->search)) {
            $seccionesQuery->where(function ($q) {
                $q->where('p.per_nombres', 'like', '%' . $this->search . '%')
                  ->orWhere('p.per_apellidos', 'like', '%' . $this->search . '%')
                  ->orWhere('p.per_cedula', 'like', '%' . $this->search . '%')
                  ->orWhere('s.sec_nombre', 'like', '%' . $this->search . '%');
            });
        }

        $estudiantes = $seccionesQuery->get();

        // Agrupar estudiantes por sección
        $agrupados = [];
        $vocerosData = Vocero::where('estatus', 'A')->get(['id_seccion', 'id_estudiante', 'tipo_vocero', 'updated_at'])->groupBy('id_seccion');

        foreach ($estudiantes as $est) {
            if (!isset($agrupados[$est->sec_codigo])) {
                $agrupados[$est->sec_codigo] = [
                    'sec_codigo' => $est->sec_codigo,
                    'sec_nombre' => $est->sec_nombre,
                    'trayecto_nombre' => $est->trayecto_nombre,
                    'vocero_actual' => $vocerosAsignados[$est->sec_codigo] ?? null,
                    'estudiantes' => []
                ];
            }

            // Evitar duplicados si hay varias UCs
            $existe = collect($agrupados[$est->sec_codigo]['estudiantes'])->contains('per_cedula', $est->per_cedula);
            if (!$existe) {
                // Verificar si este estudiante es algún tipo de vocero en esta sección
                $voceroRecord = isset($vocerosData[$est->sec_codigo]) ? $vocerosData[$est->sec_codigo]->firstWhere('id_estudiante', $est->per_cedula) : null;
                
                $esVocero = $voceroRecord !== null;
                $tipoVocero = $esVocero ? $voceroRecord->tipo_vocero : null; // 1, 2 o 3
                $fechaAsignacion = $esVocero ? $voceroRecord->updated_at->format('d/m/Y h:i A') : null;

                $agrupados[$est->sec_codigo]['estudiantes'][] = [
                    'per_cedula' => $est->per_cedula,
                    'per_nombres' => $est->per_nombres,
                    'per_apellidos' => $est->per_apellidos,
                    'es_vocero' => $esVocero,
                    'tipo_vocero' => $tipoVocero,
                    'fecha_asignacion' => $fechaAsignacion
                ];
            }
        }

        $this->secciones = array_values($agrupados);
        
        // Extraer trayectos disponibles
        $this->trayectosDisponibles = collect($estudiantes)->pluck('trayecto_nombre')->unique()->values()->toArray();
        
        // Filtrar por trayecto si está seleccionado
        if (!empty($this->trayectoSeleccionado)) {
            $this->secciones = array_filter($this->secciones, function($s) {
                return $s['trayecto_nombre'] === $this->trayectoSeleccionado;
            });
            $this->seccionesDisponibles = collect($this->secciones)->pluck('sec_nombre', 'sec_codigo')->toArray();
        } else {
            $this->seccionesDisponibles = [];
        }

        // Filtrar por seccion si está seleccionada
        if (!empty($this->seccionSeleccionada)) {
            $this->secciones = array_filter($this->secciones, function($s) {
                return $s['sec_codigo'] == $this->seccionSeleccionada;
            });
        }
    }

    public function updatedTrayectoSeleccionado()
    {
        $this->seccionSeleccionada = ''; // Resetear la sección al cambiar de trayecto
        $this->cargarSecciones();
    }

    public function updatedSeccionSeleccionada()
    {
        $this->cargarSecciones();
    }

    public function updatedSearch()
    {
        $this->cargarSecciones();
    }

    public function cargarInfoVocero($vocero)
    {
        // Cargar datos del estudiante y su sección
        $info = DB::connection('emulacion_sogac_2')
            ->table('seccion as s')
            ->join('seccion_unidad_docente as sud', 's.sec_codigo', '=', 'sud.sud_cod_seccion')
            ->join('inscripcion as i', 'sud.sud_codigo', '=', 'i.ins_cod_seccion_unidad_docente')
            ->join('persona as p', 'i.ins_cedula', '=', 'p.per_cedula')
            ->join('semestre as sem', 's.sec_cod_semestre', '=', 'sem.sem_codigo')
            ->join('trayecto as tr', 'sem.sem_cod_trayecto', '=', 'tr.tra_codigo')
            ->where('p.per_cedula', $vocero->id_estudiante)
            ->where('s.sec_codigo', $vocero->id_seccion)
            ->select(
                'p.per_cedula',
                'p.per_nombres',
                'p.per_apellidos',
                's.sec_nombre',
                'tr.tra_nombre as trayecto_nombre'
            )
            ->first();

        $this->voceroInfo = $info;
    }

    public function asignarVocero($cedula, $idSeccion, $tipo)
    {
        $pnfCoordinador = 4; // O obtener el PNF real

        // Revisar si ya hay un registro de este TIPO de vocero para esta sección activo o inactivo
        $vocero = Vocero::where('id_seccion', $idSeccion)->where('tipo_vocero', $tipo)->first();
        if ($vocero) {
            // Actualizar registro existente (reactivar y actualizar fecha por Eloquent automáticamente)
            $vocero->id_estudiante = $cedula;
            $vocero->id_coordinador = Auth::id();
            $vocero->estatus = 'A';
            // Para forzar la actualización de updated_at si el estudiante es el mismo
            $vocero->touch();
            $vocero->save();
        } else {
            // Crear nuevo
            Vocero::create([
                'id_estudiante' => $cedula,
                'id_seccion' => $idSeccion,
                'id_pnf' => $pnfCoordinador,
                'id_coordinador' => Auth::id(),
                'estatus' => 'A',
                'tipo_vocero' => $tipo
            ]);
        }

        $this->cargarSecciones();
        session()->flash('message', 'Vocero asignado exitosamente.');
    }

    public function quitarVocero($idSeccion, $tipo)
    {
        $vocero = Vocero::where('id_seccion', $idSeccion)->where('tipo_vocero', $tipo)->first();
        if ($vocero) {
            $vocero->estatus = 'I';
            $vocero->save();
            $this->cargarSecciones();
            session()->flash('message', 'Rol de vocero revocado y desactivado exitosamente.');
        }
    }

    public function render()
    {
        return view('livewire.vocero.panel-voceros')->layout('layouts.app');
    }
}
