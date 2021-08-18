<?php
use Livro\Database\Record;

class Cidades extends Record{
    const TABLENAME = 'cidades';

    private $estado;
    
    public function get_estado()
    {
        if (empty($this->estado))
        {
            $this->estado = new Estados($this->id_estado);
        }
        
        return $this->estado;
    }
    
    public function get_nome_estado()
    {
        if (empty($this->estado))
        {
            $this->estado = new Estados($this->id_estado);
        }
        
        return $this->estado->descricao;
    }
}