<?php

namespace App\Livewire\Forms\Color;

use Livewire\Form;
use App\Repositories\Color\ColorCreateRepo;

class CreateColorForm extends Form
{
    public $nombre_color = '';
    public $codigo_color = '#000000';

    public function rules()
    {
        return [
            'nombre_color' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $repo = new ColorCreateRepo();
                    if ($repo->existeNombre($value)) {
                        $fail('Ya existe un color con este nombre.');
                    }
                },
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s]+$/u'
            ],
            'codigo_color' => [
                'required',
                'string',
                'size:7',
                'regex:/^#[a-fA-F0-9]{6}$/',
                function ($attribute, $value, $fail) {
                    $repo = new ColorCreateRepo();
                    if ($repo->existeCodigo($value)) {
                        $fail('Ya existe un color con este código hexadecimal.');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'nombre_color.required' => 'El nombre del color es obligatorio.',
            'nombre_color.max' => 'El nombre del color no debe exceder 100 caracteres.',
            'nombre_color.regex' => 'El formato del nombre del color no es válido.',
            'codigo_color.required' => 'El código de color es obligatorio.',
            'codigo_color.size' => 'El código de color debe tener 7 caracteres (ej: #FF0000).',
            'codigo_color.regex' => 'El formato del código de color debe ser hexadecimal.',
        ];
    }
}
