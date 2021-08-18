<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\RadioGroup;
use Livro\Database\Transaction;

use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;
/**
 * Listagem de Pessoas
 */
class ClientesForm extends Page
{
    private $form; // formulário
    private $connection;
    private $activeRecord;

    use SaveTrait;
    use EditTrait;

    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();

        $this->connection = 'clinicaphp';
        $this->activeRecord = 'Clientes';


        $this->form = new FormWrapper(new Form('form_busca_clientes'));
        $this->form->setTitle('Clientes');

        //campos do formulario
        $id = new Entry('id');
        $descricao = new Entry('descricao');
        $endereco = new Entry('endereco');
        $cidade = new Combo('id_cidade');
        $contatos = new Entry('contatos');
        
        
        //pega as cidades
        Transaction::open('clinicaphp');
        $cidades = Cidades::all();
        $items = array();
        foreach($cidades as $obj_cidade)
        {
            $items[$obj_cidade->id] = $obj_cidade->descricao;
        }
        Transaction::close();
        $cidade->addItems($items);

        

       

        //cria form
        $id->setEditable(FALSE);

        $this->form->addField('ID',$id,'20%');
        $this->form->addField('Descrição', $descricao, '70%');
        $this->form->addField('Endereço', $endereco, '70%');
        $this->form->addField('Cidade', $cidade, '70%');
        $this->form->addField('Contatos', $contatos, '70%');
        
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));

        parent::add($this->form);

        



    }
}