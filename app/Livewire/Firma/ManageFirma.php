<?php

namespace App\Livewire\Firma;

use App\Livewire\Forms\Firma\CreateFirmaForm;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Firma;
use App\Services\FirmaService;
use Illuminate\Support\Facades\DB;
use Exception;

class ManageFirma extends Component
{
    use WithFileUploads;

    public CreateFirmaForm $form;
    public $firmaActual = null;

    public function mount()
    {
        $this->cargarFirmaActual();
    }

    public function cargarFirmaActual()
    {
        $this->firmaActual = Firma::where('id_usuario', auth()->user()->usu_codigo)
            ->where('estatus', '1')
            ->first();
    }

    public function guardar()
    {
        try {
            $this->form->validate();

            $pngData = FirmaService::maikol_callate($this->form->foto_firma);
            $pngData = FirmaService::optimizarParaFirma($pngData);

            DB::transaction(function () use ($pngData) {
                // Inhabilitar firma anterior si existe
                Firma::where('id_usuario', auth()->user()->usu_codigo)
                    ->where('estatus', '1')
                    ->update(['estatus' => '3']);

                // Crear nueva firma
                Firma::create([
                    'id_usuario' => auth()->user()->usu_codigo,
                    'foto_firma' => $pngData,
                    'estatus' => '1'
                ]);
            });

            $this->reset('form.foto_firma');
            $this->cargarFirmaActual();
            
            $this->showAlert('success', 'Firma guardada correctamente. El fondo ha sido eliminado y convertido a PNG.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al guardar la firma: ' . $e->getMessage());
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function render()
    {
        return view('livewire.pages.firma.manage-firma');
    }
}
