<?php
use Livro\Database\Record;


class Medicos extends Record
{
    const TABLENAME = 'medicos';
    private $cidade;
    
    /**
     * Retorna a cidade.
     * Executado sempre se for acessada a propriedade "->cidade"
     */
    public function get_cidade()
    {
        if (empty($this->cidade))
            $this->cidade = new Cidades($this->id_cidade);
        
        return $this->cidade;
    }
    
    /**
     * Retorna o nome da cidade.
     * Executado sempre se for acessada a propriedade "->nome_cidade"
     */
    public function get_nome_cidade()
    {
        if (empty($this->cidade))
            $this->cidade = new Cidades($this->id_cidade);
        
        return $this->cidade->descricao;
    }
}