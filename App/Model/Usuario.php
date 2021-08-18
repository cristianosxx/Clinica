<?php
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Record;
use Livro\Database\Transaction;

class Usuario extends Record
{
    const TABLENAME = 'usuario';
    private $validar;

    public function getUsername($user,$senha)
    {
        $criteria = new Criteria;
        $criteria->add('login', '=', $user);
        $criteria->add('senha', '=', $senha);
        $repo = new Repository('usuario');
        $vinculos = $repo->load($criteria);
        return $vinculos;
        
    }
}
