<?php

namespace App\Livewire\Calendario;

use Livewire\Component;
use App\Models\CalendarioAcademico;
use Illuminate\Support\Facades\Gate;
use Exception;

class JustificacionesCalendario extends Component
{
    public $calendario;
    public $justificaciones = [];

    public function mount($id)
    {
        if (!Gate::allows('ver-calendario')) {
            abort(403);
        }

        try {
            $this->calendario = CalendarioAcademico::findOrFail($id);

            $justificaciones = $this->calendario->justificativo_calendario_academico;
            $this->justificaciones = is_array($justificaciones) ? $justificaciones : [];

            if (empty($this->justificaciones)) {
                return redirect()->route('calendario.list')->with('error', 'Este calendario no tiene atenuantes registrados.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error al cargar los atenuantes: ' . $e->getMessage());
            return redirect()->route('calendario.list');
        }
    }

    public function render()
    {
        return view('livewire.pages.calendario.justificaciones-calendario');
    }

    public function cerrar()
    {
        return redirect()->route('calendario.list');
    }
}
