<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SessionTimeout extends Component
{
    public $sessionLifetime; // En minutos
    public $warningTime = 1; // Minutos antes de expirar para mostrar el aviso

    public function mount()
    {
        $this->sessionLifetime = config('session.lifetime');
    }

    public function stayConnected()
    {
        // Al llamar a cualquier método de Livewire, la sesión se refresca automáticamente
        // No necesitamos hacer nada extra, pero podemos registrar la actividad si es necesario.
        $this->dispatch('session-refreshed');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.session-timeout');
    }
}
