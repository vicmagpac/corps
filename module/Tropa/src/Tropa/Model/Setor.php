<?php

namespace Tropa\Model;

class Setor
{
    public $codigo;
    public $nome;

    public function exchangeArray($data)
    {
        $this->codigo   = (isset($data['codigo'])) ? $data['codigo'] : null;
        $this->nome     = (isset($data['nome'])) ? $data['nome'] : null;
    }
}