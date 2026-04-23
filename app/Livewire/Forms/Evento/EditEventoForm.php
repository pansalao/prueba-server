<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;
use Illuminate\Support\Facades\DB;
use App\Repositories\Evento\EventoUpdateRepo;

class EditEventoForm extends Form
{
    public $id_evento = '';
    public $id_color = '';
    public $descripcion_evento = '';
    public $tipo_evento = '1';

    public function setEvento($evento)
    {
        $this->id_evento = $evento->id_evento;
        $this->descripcion_evento = $evento->nombre_evento;
        $this->tipo_evento = $evento->tipo_evento;
        $this->id_color = $evento->id_color;
    }

    protected function rules()
    {
        return [
            'descripcion_evento' => [
                'required', 'string', 'max:100',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('evento')
                        ->where('nombre_evento', $value)
                        ->where('id_evento', '!=', $this->id_evento)
                        ->where('estatus', '!=', '3')
                        ->exists();
                    if ($exists) {
                        $fail('Ya existe otro evento con esta descripción.');
                    }
                },
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
            ],
            'tipo_evento' => ['required', 'in:1,2,3'],
            'id_color' => ['required', 'exists:color,id_color'],
        ];
    }
}
