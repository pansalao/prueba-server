<?php

namespace App\Livewire\Planificacion;

use Livewire\Component;
use App\Repositories\Planificacion\PlanificacionIndexRepo;
use App\Repositories\Planificacion\PlanificacionViewRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Planificacion;
use Illuminate\Support\Facades\DB;

class ShowPlanificacion extends Component
{
    public $planificacionId;
    public $planificacion;
    public $motivosRechazoCortes = [];
    public $mostrarMotivoRechazoCorte = [];
    public $contratoEstudiantes;
    public $contratoPath;

    // Propiedades para aprobación de vocero
    public $esVoceroDePlanificacion = false;
    public $motivoRechazoVocero = '';
    public $mostrarFormularioRechazoVocero = false;

    use WithFileUploads;

    protected $planificacionIndexRepo;
    protected $planificacionViewRepo;

    public function __construct()
    {
        $this->planificacionIndexRepo = new PlanificacionIndexRepo();
        $this->planificacionViewRepo = new PlanificacionViewRepo();
    }

    public function mount($planificacionId)
    {
        $this->planificacionId = $planificacionId;
        $this->loadPlanificacionDetails();
    }

    private function loadPlanificacionDetails()
    {
        $details = $this->planificacionViewRepo->getDetallesPlanificacion($this->planificacionId);

        if (!$details) {
            session()->flash('error', 'La planificación solicitada no fue encontrada.');
            return redirect()->route('planificacion/listar');
        }

        $this->planificacion = (object) $details;

        $this->planificacion->unidades = collect($this->planificacion->unidades)->map(function ($unidad) {
            $unidad = (object) $unidad;
            $this->motivosRechazoCortes[$unidad->detalle_id] = $unidad->ultimo_motivo_rechazo ?? '';
            return $unidad;
        })->toArray();

        // Cargar el path del contrato si existe
        $this->contratoPath = $this->planificacion->archivo_contrato ?? null;

        // Verificar si el usuario actual es vocero de esta planificación
        $this->esVoceroDePlanificacion = $this->planificacionIndexRepo->usuarioEsVoceroDePlanificacion($this->planificacionId);

        // Registrar visualización en la bitácora
        $planificacionModel = Planificacion::find($this->planificacionId);
        if ($planificacionModel) {
            Planificacion::logMostrar($planificacionModel);
        }
    }

    public function saveContrato()
    {
        $this->validate([
            'contratoEstudiantes' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        try {
            $path = $this->contratoEstudiantes->store('contratos', 'public');
            
            // Guardar en la base de datos a través de Eloquent
            $planificacionModel = Planificacion::find($this->planificacionId);
            if ($planificacionModel) {
                $planificacionModel->update(['archivo_contrato' => $path]);
            }

            $this->contratoPath = $path;
            $this->contratoEstudiantes = null;

            session()->flash('message', 'El contrato de los estudiantes se ha subido correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al subir contrato: ' . $e->getMessage());
            session()->flash('error', 'Hubo un error al subir el archivo.');
        }
    }

    /**
     * Aprobación final por parte del vocero.
     */
    public function voceroAprobarPlanificacion()
    {
        if (!Gate::allows('aprobacion-vocero-planificacion') && !$this->esVoceroDePlanificacion) {
            session()->flash('error', 'No tienes permisos para aprobar esta planificación como vocero.');
            return;
        }

        if (($this->planificacion->estatus ?? 0) != 5) {
            session()->flash('error', 'La planificación no está en estado pendiente de aprobación del vocero.');
            return;
        }

        $user = Auth::user();
        $firma = DB::table('firma')
            ->where('id_usuario', $user->usu_codigo)
            ->where('estatus', '1')
            ->first();

        if (!$firma) {
            session()->flash('error', 'No puedes aprobar la planificación porque no has subido tu firma al sistema. Por favor, ve al módulo de firmas para registrarla antes de aprobar.');
            return;
        }

        $success = $this->planificacionIndexRepo->aprobarPlanificacionVocero($this->planificacionId, $firma->id_firma);

        if ($success) {
            session()->flash('message', 'Has aprobado la planificación como vocero. La planificación ha sido aprobada completamente.');
            $this->mount($this->planificacion->planificacion_id);
            $this->dispatch('planificacionAprobada');
        } else {
            session()->flash('error', 'No se pudo completar la aprobación. Intente de nuevo.');
        }
    }

    /**
     * Muestra el formulario de rechazo del vocero.
     */
    public function voceroMostrarFormularioRechazo()
    {
        $this->mostrarFormularioRechazoVocero = true;
        $this->motivoRechazoVocero = '';
    }

    /**
     * Cancela el formulario de rechazo del vocero.
     */
    public function voceroOcultarFormularioRechazo()
    {
        $this->mostrarFormularioRechazoVocero = false;
        $this->motivoRechazoVocero = '';
    }

    /**
     * Rechazo por parte del vocero.
     */
    public function voceroRechazarPlanificacion()
    {
        if (!Gate::allows('aprobacion-vocero-planificacion') && !$this->esVoceroDePlanificacion) {
            session()->flash('error', 'No tienes permisos para rechazar esta planificación como vocero.');
            return;
        }

        if (($this->planificacion->estatus ?? 0) != 5) {
            session()->flash('error', 'La planificación no está en estado pendiente de aprobación del vocero.');
            return;
        }

        $motivo = trim($this->motivoRechazoVocero);

        if (mb_strlen($motivo) < 10) {
            $this->addError('motivoRechazoVocero', 'El motivo de rechazo debe tener al menos 10 caracteres.');
            return;
        }

        $success = $this->planificacionIndexRepo->rechazarPlanificacionVocero($this->planificacionId, $motivo);

        if ($success) {
            session()->flash('message', 'Has rechazado la planificación como vocero.');
            $this->mostrarFormularioRechazoVocero = false;
            $this->motivoRechazoVocero = '';
            $this->mount($this->planificacion->planificacion_id);
            $this->dispatch('planificacionRechazada');
        } else {
            session()->flash('error', 'No se pudo completar el rechazo. Intente de nuevo.');
        }
    }





    public function eliminarMotivoRechazo($detalleId)
    {
        if (!Gate::allows('cambiar-estatus-planificacion')) {
            session()->flash('error', 'No tienes permisos para eliminar motivos de rechazo.');
            return;
        }
        if (method_exists($this->planificacionIndexRepo, 'eliminarMotivoRechazoPorCorte')) {
            $this->planificacionIndexRepo->eliminarMotivoRechazoPorCorte($detalleId);
        }
        $this->motivosRechazoCortes[$detalleId] = '';
        $this->mount($this->planificacion->planificacion_id);
    }

    public function mostrarTextareaMotivo($detalleId)
    {
        if (!Gate::allows('cambiar-estatus-planificacion')) {
            session()->flash('error', 'No tienes permisos para rechazar planificaciones.');
            return;
        }
        $this->mostrarMotivoRechazoCorte[$detalleId] = true;
    }

    public function ocultarTextareaMotivo($detalleId)
    {
        if (isset($this->mostrarMotivoRechazoCorte[$detalleId])) {
            $this->mostrarMotivoRechazoCorte[$detalleId] = false;
        }
    }

    public function confirmarRechazoCorte($detalleId)
    {
        if (!Gate::allows('cambiar-estatus-planificacion')) {
            session()->flash('error', 'No tienes permisos para rechazar planificaciones.');
            return;
        }

        $motivo = trim($this->motivosRechazoCortes[$detalleId] ?? '');

        if (mb_strlen($motivo) < 10) {
            // Se usa addError para que Livewire lo detecte en la vista
            $this->addError("motivosRechazoCortes.{$detalleId}", "El motivo de rechazo debe tener al menos 10 caracteres.");
            return;
        }

        $corteARechazar = [
            [
                'detalle_id' => $detalleId,
                'motivo' => $motivo,
            ]
        ];

        // Reutilizamos el método del repo que ya maneja la transacción y actualización de estados
        $success = $this->planificacionIndexRepo->rechazarPlanificacionConCortes(
            $this->planificacion->planificacion_id,
            $corteARechazar
        );

        if ($success) {
            session()->flash('message', 'Corte rechazado correctamente (Planificación pasada a estado Rechazado).');
            // Limpiamos el estado de mostrar textarea para ese corte
            $this->mostrarMotivoRechazoCorte[$detalleId] = false;
            $this->mount($this->planificacion->planificacion_id);
        } else {
            session()->flash('error', 'El corte no pudo ser rechazado.');
        }
    }

    public function aprobarCorte($detalleId)
    {
        if (!Gate::allows('cambiar-estatus-planificacion')) {
            session()->flash('error', 'No tienes permisos para aprobar planificaciones.');
            return;
        }

        $user = Auth::user();
        $firma = DB::table('firma')
            ->where('id_usuario', $user->usu_codigo)
            ->where('estatus', '1')
            ->first();

        if (!$firma) {
            session()->flash('error', 'No puedes aprobar cortes porque no tienes una firma activa registrada en el sistema.');
            return;
        }

        $success = $this->planificacionIndexRepo->aprobarCorte($detalleId, $firma->id_firma);

        if ($success) {
            session()->flash('message', 'Corte aprobado correctamente.');
            $this->mount($this->planificacion->planificacion_id);
        } else {
            session()->flash('error', 'El corte no pudo ser aprobado.');
        }
    }

    public function render()
    {
        $mostrarBotonRechazarPlanificacion = ($this->planificacion->estatus ?? null) != 1 && ($this->planificacion->estatus ?? null) != 5;
        $estatusTexto = $this->mapEstatusToText($this->planificacion->estatus ?? null);
        foreach ($this->planificacion->unidades as $key => $unidad) {
            $this->planificacion->unidades[$key]->estatus_texto = $this->mapEstatusToText($unidad->estatus);
        }

        return view('livewire.pages.planificacion.show-planificacion', [
            'mostrarBotonRechazarPlanificacion' => $mostrarBotonRechazarPlanificacion,
            'estatusTexto' => $estatusTexto,
        ]);
    }

    private function mapEstatusToText(?int $estatus): string
    {
        switch ($estatus) {
            case 1:
                return 'Aprobado';
            case 2:
                return 'Pendiente';
            case 3:
                return 'Rechazado';
            case 4:
                return 'Incompleta';
            case 5:
                return 'Aprobado por Coordinador';
            default:
                return 'Desconocido';
        }
    }

    public function cerrar()
    {
        return redirect()->route('planificacion/listar');
    }
}
