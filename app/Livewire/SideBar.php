<?php
// Archivo: app/Livewire/SideBar.php

namespace App\Livewire;

use App\Livewire\Actions\Logout;
use Livewire\Component;
use Livewire\Attributes\On;

class SideBar extends Component
{
    public bool $isOpen = false;

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    /**
     * Escucha el evento 'open-sidebar' para abrir el sidebar desde el exterior.
     * Este evento puede ser disparado por Alpine.js u otro componente Livewire.
     */
    #[On('open-sidebar')]
    public function open()
    {
        $this->isOpen = true;
        // Despacha un evento para que Alpine.js (u otros) puedan reaccionar al cambio de estado.
        $this->dispatch('sidebar-state-changed', isOpen: $this->isOpen);
    }

    /**
     * Alterna el estado del sidebar (abierto/cerrado).
     */
    #[On('toggle-sidebar')]
    public function toggle() // Renombrado de toggleSidebar para evitar confusión con el evento
    {
        $this->isOpen = !$this->isOpen;
        // Despacha un evento para que Alpine.js (u otros) puedan reaccionar al cambio de estado.
        $this->dispatch('sidebar-state-changed', isOpen: $this->isOpen);
    }

    public function render()
    {
        $user = auth()->user();
        $roleCount = $user ? $user->obtenerRolesAsociados()->count() : 0;
        
        $hayCalendarioActivo = false;
        $puedoCrearCalendario = false;
        if ($user && $user->can('listar-calendario')) {
            $repo = new \App\Repositories\Calendario\CalendarioCreateRepo();
            $hayCalendarioActivo = $repo->hayCalendarioActivo();
            $puedoCrearCalendario = $repo->contarCalendariosActivos() < 2;
        }

        return view('livewire.layout.side-bar', [
            'isOpen' => $this->isOpen,
            'roleCount' => $roleCount,
            'hayCalendarioActivo' => $hayCalendarioActivo,
            'puedoCrearCalendario' => $puedoCrearCalendario
        ]);
    }
}
