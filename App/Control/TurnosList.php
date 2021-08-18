<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Database\Transaction;

use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;

use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

/**
 * Página de produtos
 */
class TurnosList extends Page
{
    private $form;
    private $datagrid;
    private $loaded;
    private $connection;
    private $activeRecord;
    private $filters;
    
    use DeleteTrait;
    use ReloadTrait {
        onReload as onReloadTrait;
    }
    
    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();
        
        // Define o Active Record
        $this->activeRecord = 'Turnos';
        $this->connection   = 'clinicaphp';
        
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_busca_turnos'));
        $this->form->setTitle('Turnos');
        
        // cria os campos do formulário
        $descricao = new Entry('descricao');
        
        $this->form->addField('Descrição',   $descricao, '100%');
        $this->form->addAction('Buscar', new Action(array($this, 'onReload')));
        $this->form->addAction('Cadastrar', new Action(array(new TurnosForm, 'onEdit')));
        
        // instancia objeto Datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);
        
        // instancia as colunas da Datagrid
        $id   = new DatagridColumn('id',             'Código',    'center',  '10%');
        $descricao= new DatagridColumn('descricao',      'Descrição', 'left',   '90%');

        
        // adiciona as colunas à Datagrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($descricao);
   
        
        $this->datagrid->addAction( 'Editar',  new Action([new TurnosForm, 'onEdit']), 'id', 'fa fa-edit fa-lg blue');
        $this->datagrid->addAction( 'Excluir', new Action([$this, 'onDelete']),          'id', 'fa fa-trash fa-lg red');
        
        // monta a página através de uma caixa
        $box = new VBox;
        $box->style = 'display:block';
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
    
    /**
     * Exibe a página
     */
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