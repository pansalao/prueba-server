<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;
use Livewire\Attributes\Locked;

class UpdateEventoForm extends Form
{
    #[Locked]
    public $id_evento = '';

    public $id_color = '';
    public $descripcion_evento = '';
    public $tipo_evento = '1';
    public $especial_evento = '';
    public $is_especial = false;
    public $is_laborable = false;
    public $is_repetible = false;
    public $is_rango_dias = false;
    public $rango_dias = '';

    public function setEvento($evento)
    {
        $this->id_evento = $evento->id_evento;
        $this->descripcion_evento = $evento->nombre_evento;
        $this->tipo_evento = $evento->tipo_evento;
        $this->especial_evento = $evento->especial_evento ?? '';
        $this->is_especial = !empty($evento->especial_evento);
        $this->id_color = $evento->id_color;
        $this->is_laborable = (bool) $evento->is_laborable_evento;
        $this->is_repetible = (bool) $evento->is_repetible_evento;
        $this->is_rango_dias = (bool) $evento->is_rango_dias_evento;
        $this->rango_dias = $evento->rango_dias_evento;
    }

    protected function rules()
    {
        return [
            'descripcion_evento' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $repo = new \App\Repositories\Evento\EventoUpdateRepo();
                    if ($repo->existeEventoConDescripcion($value, $this->id_evento)) {
                        $fail('Ya existe otro evento con esta descripción.');
                    }
                },
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
            ],
            'tipo_evento' => ['required', 'in:1,2,3,4,5'],
            'is_especial' => ['required', 'boolean'],
            'especial_evento' => ['required_if:is_especial,true', 'nullable', 'in:1,2,3'],
            'is_laborable' => ['required', 'boolean'],
            'is_repetible' => ['required', 'boolean'],
            'id_color' => [
                'required',
                'exists:color,id_color',
                function ($attribute, $value, $fail) {
                    $repo = new \App\Repositories\Evento\EventoUpdateRepo();
                    if ($repo->existeColor($value, $this->id_evento)) {
                        $fail('Este color ya está asignado a otro evento activo.');
                    }
                }
            ],
            'is_rango_dias' => ['required', 'boolean'],
            'rango_dias' => ['required_if:is_rango_dias,true', 'nullable', 'integer', 'min:1', 'max:90'],
        ];
    }

    protected function messages()
    {
        return [
            'descripcion_evento.required' => 'La descripción es obligatoria.',
            'descripcion_evento.string' => 'La descripción debe ser texto.',
            'descripcion_evento.max' => 'La descripción no debe exceder 100 caracteres.',
            'descripcion_evento.regex' => 'Formato inválido en la descripción.',
            'tipo_evento.required' => 'El tipo de evento es obligatorio.',
            'tipo_evento.in' => 'El tipo de evento no es válido.',
            'especial_evento.required_if' => 'Debe seleccionar qué tipo de evento especial es.',
            'especial_evento.in' => 'El evento especial seleccionado no es válido.',
            'id_color.required' => 'El color es obligatorio.',
            'id_color.exists' => 'El color seleccionado no es válido.',
            'is_laborable.boolean' => 'El valor de laborable debe ser booleano.',
            'is_repetible.boolean' => 'El valor de repetible debe ser booleano.',
            'is_rango_dias.boolean' => 'El valor de rango de días debe ser booleano.',
            'rango_dias.required_if' => 'La cantidad de días es obligatoria.',
            'rango_dias.integer' => 'La cantidad de días debe ser un número entero.',
            'rango_dias.min' => 'La cantidad de días debe ser al menos 1.',
            'rango_dias.max' => 'La cantidad de días no debe superar los 90 días.',
        ];
    }
}
