<?php
use Livro\Database\Record;


class Clientes extends Record
{
    const TABLENAME = 'clientes';
    private $cidade;
    
   
    public function get_cidade()
    {
        if (empty($this->cidade))
            $this->cidade = new Cidades($this->id_cidade);
        
        return $this->cidade;
    }
    
    
    public function get_nome_cidade()
    {
        if (empty($this->cidade))
            $this->cidade = new Cidades($this->id_cidade);
        
        return $this->cidade->descricao;
    }
}