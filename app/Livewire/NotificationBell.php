<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationBell extends Component
{
    public $planificacionesAceptadas = [];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $userId = Auth::id(); // usu_codigo
            
            $dbSogc = config('database.connections.emulacion_sogac_2.database');
            
            $this->planificacionesAceptadas = DB::table('planificacion as p')
                ->join("$dbSogc.seccion_unidad_docente as sud", 'p.id_profesor_asignado', '=', 'sud.sud_codigo')
                ->join("$dbSogc.usuario as u", 'sud.sud_ced_docente', '=', 'u.usu_cedula')
                ->join("$dbSogc.unidad_curricular as uc", 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
                ->join("$dbSogc.seccion as s", 'sud.sud_cod_seccion', '=', 's.sec_codigo')
                ->where('u.usu_codigo', $userId)
                ->whereIn('p.estatus', [1, 3]) // 1 = Aceptada, 3 = Rechazada
                ->where(function($q) {
                    $q->whereNull('p.notificado')->orWhere('p.notificado', false)->orWhere('p.notificado', 0);
                })
                ->select('uc.ucu_nombre', 's.sec_nombre', 'p.estatus', 'p.id_planificacion')
                ->orderBy('p.id_planificacion', 'desc')
                ->limit(5)
                ->get()
                ->toArray();
        }
    }

    public function markAsRead($id_planificacion)
    {
        DB::table('planificacion')
            ->where('id_planificacion', $id_planificacion)
            ->update(['notificado' => 1]);
            
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
