<?php
use Livro\Database\Record;

class Consultas extends Record{
    const TABLENAME = 'consultas';
    private $medico;
    private $cliente;
    private $status;

    public function get_medico()
    {
        if (empty($this->medico))
            $this->medico = new Medicos($this->id_medico);
        
        return $this->medico;
    }
    
    
    public function get_nome_medico()
    {
        if (empty($this->medico))
            $this->medico = new Medicos($this->id_medico);
        
        return $this->medico->descricao;
    }
    public function get_cliente()
    {
        if (empty($this->cliente))
            $this->cliente = new Clientes($this->id_cliente);
        
        return $this->cliente;
    }
    
    
    public function get_nome_cidade()
    {
        if (empty($this->cliente))
            $this->cliente = new Clientes($this->id_cliente);
        
        return $this->cliente->descricao;
    }
    public function get_status()
    {
        if (empty($this->status))
            $this->status = new Status($this->status);
        
        return $this->status;
    }
    
    
    public function get_nome_status()
    {
        if (empty($this->status))
            $this->status = new Status($this->status);
        
        return $this->status->descricao;
    }
}