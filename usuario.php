<?php 
class Usuario {
    private $usuario;
    private $senha;

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setNome($nome) {
        $this->usuario = $nome;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getCodigo() {
        return $this->codigo;
    }
    
    public function getNome() {
        return $this->usuario;
    }
    public function getSenha() {
        return $this->senha;
    }
    public function imprime() {
        echo $this->codigo." - ".$this->nome;
    }
}