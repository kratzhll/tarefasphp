<?php

class Tarefa
{
    public $id;
    public $titulo;
    public $concluida;

    public function __construct(
        $id = null,
        $titulo = "",
        $concluida = false
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->concluida = $concluida;
    }
}