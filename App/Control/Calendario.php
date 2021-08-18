<?php
use Livro\Control\Page;
use Livro\Database\Transaction;
use Livro\Session\Session;

/**
 * Página de produtos
 */
class Calendario extends Page
{

    
    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();
        Transaction::open('clinicaphp');
        $consultas = Consultas::all();
        $items = array();
        $list = '';
        $tipouser = 1;
        
        foreach($consultas as $obj_consultas)
        {
            if($obj_consultas->id_medico == Session::getValue('id') ||$tipouser == Session::getValue('tipo')  )
            {
                $items['id'] = $obj_consultas->id;
                $items['title'] = $obj_consultas->title;
                $items['color'] = $obj_consultas->color;
                $items['start'] = $obj_consultas->inicio;
                $items['end'] = $obj_consultas->fim;
                $list .= json_encode($items).',';
            }
        }
        Transaction::close();
        
        $template =file_get_contents('App/Templates/Calendar.html');
        $output = str_replace('{items}',$list, $template);

        parent::add($output);

    }
}