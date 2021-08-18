<?php

use Dompdf\Css\Style;
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Database\Repository;
use Livro\Database\Criteria;

use Livro\Database\Transaction;

use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;
use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;

use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

class MedicosList extends Page{
    private $form;
    private $datagrid;
    private $loaded;
    private $connection;
    private $activeRecord;
    private $filters;

    
    use EditTrait;
    use DeleteTrait;
    use ReloadTrait {
        onReload as onReloadTrait;
    }
    use SaveTrait {
        onSave as onSaveTrait;
    }

    public function __construct()
    {
        parent::__construct();
        
        $this->form = new FormWrapper(new Form('form_busca_medicos'));
        $this->form->setTitle('Medicos');

        //busca
        $descricao = new Entry('descricao');
        $this->form->addField('Descrição',$descricao,'100%');
        $this->form->addAction('Buscar', new Action(array($this,'onReload')));
        $this->form->addAction('Novo', new Action(array(new MedicosForm,'onEdit')));



        $this->connection   = 'clinicaphp';
        $this->activeRecord = 'Medicos';

        $this->datagrid = new DatagridWrapper(new Datagrid);

        // instancia as colunas da Datagrid
        $id   = new DatagridColumn('id',     'ID', 'center', '10%');
        $descricao     = new DatagridColumn('descricao',   'Descricao',   'left', '15%');
        $endereco     = new DatagridColumn('endereco',   'Endereço',   'left', '25%');
        $cidade   = new DatagridColumn('nome_cidade', 'Cidade', 'left', '15%');
        $contatos     = new DatagridColumn('contatos',   'Contatos',   'left', '15%');
        $formacao   = new DatagridColumn('formacao', 'Formacao', 'left', '15%');

        // adiciona as colunas à Datagrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($descricao);
        
        $this->datagrid->addColumn($endereco);
        $this->datagrid->addColumn($cidade);
        $this->datagrid->addColumn($contatos);
        $this->datagrid->addColumn($formacao);


        $this->datagrid->addAction( 'Editar',  new Action([new MedicosForm, 'onEdit']),   'id', 'fa fa-edit fa-lg blue');
        $this->datagrid->addAction( 'Excluir', new Action([$this, 'onDelete']), 'id', 'fa fa-trash fa-lg red');
        

        $box = new VBox;
        $box->style = 'display>block';
        $box->add($this->form);
        $box->add($this->datagrid);

        parent::add($box);
    }

    public function onReload()
    {
        // obtém os dados do formulário de buscas
        $dados = $this->form->getData();
        
        // verifica se o usuário preencheu o formulário
        if ($dados->descricao)
        {
            // filtra pela descrição do produto
            $this->filters[] = ['descricao', 'like', "%{$dados->descricao}%", 'and'];
        }
        
        $this->onReloadTrait();   
        $this->loaded = true;
    }
    public function show()
    {
         // se a listagem ainda não foi carregada
         if (!$this->loaded)
         {
	        $this->onReload();
         }
         parent::show();
    }

  

   

    
}