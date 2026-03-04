<?php

namespace App\Livewire\Planificacion;

use Livewire\Component;
use App\Repositories\Planificacion\PlanificacionIndexRepo;
use App\Repositories\Planificacion\PlanificacionViewRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ShowPlanificacion extends Component
{
    public $planificacionId;
    public $planificacion;
    public $motivosRechazoCortes = [];
    public $mostrarMotivoRechazoCorte = [];

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

        $this->planificacion->cortes = collect($this->planificacion->cortes)->map(function ($corte) {
            $corte = (object) $corte;
            $this->motivosRechazoCortes[$corte->detalle_id] = $corte->ultimo_motivo_rechazo ?? '';
            return $corte;
        })->toArray();
    }





    public function eliminarMotivoRechazo($detalleId)
    {
        if (!Gate::allows('editar-planificacion')) {
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
        if (!Gate::allows('editar-planificacion')) {
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
        if (!Gate::allows('editar-planificacion')) {
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
        if (!Gate::allows('editar-planificacion')) {
            session()->flash('error', 'No tienes permisos para aprobar planificaciones.');
            return;
        }

        $success = $this->planificacionIndexRepo->aprobarCorte($detalleId);

        if ($success) {
            session()->flash('message', 'Corte aprobado correctamente.');
            $this->mount($this->planificacion->planificacion_id);
        } else {
            session()->flash('error', 'El corte no pudo ser aprobado.');
        }
    }

    public function render()
    {
        $mostrarBotonRechazarPlanificacion = ($this->planificacion->estatus ?? null) != 1;
        $estatusTexto = $this->mapEstatusToText($this->planificacion->estatus ?? null);
        foreach ($this->planificacion->cortes as $key => $corte) {
            $this->planificacion->cortes[$key]->estatus_texto = $this->mapEstatusToText($corte->estatus);
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
            default:
                return 'Desconocido';
        }
    }

    public function cerrar()
    {
        return redirect()->route('planificacion/listar');
    }
}
