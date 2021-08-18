<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Date;
use Livro\Widgets\Form\RadioGroup;
use Livro\Database\Transaction;
use Livro\Session\Session;

use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;

/**
 * Listagem de Pessoas
 */
class ConsultasForm extends Page
{
    private $form; // formulário
    private $connection;
    private $activeRecord;


    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();

        $this->connection = 'clinicaphp';
        $this->activeRecord = 'Consultas';


        $this->form = new FormWrapper(new Form('form_busca_consultas'));
        $this->form->setTitle('Agendar consulta');

        //campos do formulario
        $id = new Entry('id');
        $title = new Entry('title');
        $medico = new Combo('id_medico');
        $cliente = new Combo('id_cliente');
        $color = new Entry('color');
        $inicio = new Date('inicio');
        $fim = new Date('fim');
        $status = new Combo('status');



        // pega medicos
        Transaction::open('clinicaphp');
        $medicos = Medicos::all();
        $items = array();
        foreach ($medicos as $obj_medico) {
            if($obj_medico->id == Session::getValue('id')||Session::getValue('tipo')==1){
            $items[$obj_medico->id] = $obj_medico->descricao;
            }
        }
        Transaction::close();
        $medico->addItems($items);

        //pega clientes
        Transaction::open('clinicaphp');
        $clientes = Clientes::all();
        $items = array();
        foreach ($clientes as $obj_clientes) {
            $items[$obj_clientes->id] = $obj_clientes->descricao;
        }
        Transaction::close();
        $cliente->addItems($items);

        //pega status
        Transaction::open('clinicaphp');
        $statuss = Status::all();
        $items = array();
        foreach ($statuss as $obj_status) {
            $items[$obj_status->id] = $obj_status->descricao;
        }
        Transaction::close();
        $status->addItems($items);


        $status->setValue(1);



        //cria form
        $id->setEditable(FALSE);

        $this->form->addField('ID', $id, '20%');
        $this->form->addField('Descrição', $title, '70%');
        if (Session::getValue('tipo') == 1) {
            $this->form->addField('Médico', $medico, '70%');
        }else if (Session::getValue('tipo') == 2) {
            $medico->setValue(1);
            $this->form->addField('Médico',$medico, '70%');
        }
        

        $this->form->addField('Cliente', $cliente, '70%');
        $this->form->addField('Cor', $color, '70%');
        $this->form->addField('Inicio', $inicio, '70%');
        $this->form->addField('Fim', $fim, '70%');
        $this->form->addField('Situação', $status, '70%');
        

        
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));

        parent::add($this->form);
    }
    function onEdit($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id']; // obtém a chave
                
                Transaction::open($this->connection); // inicia transação com o BD
                
                $class = $this->activeRecord;
                $object = $class::find($id); // instancia o Active Record
                
                $object->inicio = str_replace(' ','T', $object->inicio);
                $object->fim = str_replace(' ','T', $object->fim);
                $this->form->setData($object); // lança os dados no formulário
                
                

                Transaction::close(); // finaliza a transação
            }
        } catch (Exception $e) {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }
    }
    function onSave()
    {
        
        try
        {
            
            Transaction::open( $this->connection );            
            $class = $this->activeRecord;
            $dados = $this->form->getData();
           
            
            $object = new $class; // instancia objeto
            $object->fromArray( (array) $dados); // carrega os dados
            if($object->id_medico == Session::getValue('id')||Session::getValue('tipo')==1){
                    
            }else{
                throw new Exception('O ID do médico não está certo.');
            }
            $object->store(); // armazena o objeto
            
            $dados->id = $object->id;
            $this->form->setData($dados);
            
            Transaction::close(); // finaliza a transação
            new Message('info', 'Dados armazenados com sucesso');
            
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
        }
    }
}
