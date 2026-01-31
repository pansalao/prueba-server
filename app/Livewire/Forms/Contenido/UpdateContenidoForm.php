<?php

namespace App\Livewire\Forms\Contenido;

use Livewire\Form;
use Livewire\Attributes\Validate;

class UpdateContenidoForm extends Form
{
    public $id;
    public $id_tema;
    public $titulo_contenido;
    public $descripcion_contenido;

    protected function rules()
    {
        return [
            'id_tema' => 'required|exists:tema,id_tema',
            'titulo_contenido' => 'required|string|max:255',
            'descripcion_contenido' => 'nullable|string',
        ];
    }

    public function setContenido($contenido)
    {
        $this->id = $contenido->id;
        $this->id_tema = $contenido->id_tema;
        $this->titulo_contenido = $contenido->titulo_contenido;
        $this->descripcion_contenido = $contenido->descripcion_contenido;
    }

    public function values()
    {
        return [
            'id_tema' => $this->id_tema,
            'titulo_contenido' => $this->titulo_contenido,
            'descripcion_contenido' => $this->descripcion_contenido,
        ];
    }
}
