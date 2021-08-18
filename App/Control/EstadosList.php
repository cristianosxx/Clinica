<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

/**
 * Listagem de Pessoas
 */
class EstadosList extends Page
{
    private $form;     // formulário de buscas
    private $datagrid; // listagem
    private $loaded;

    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();

        $this->form = new FormWrapper(new Form('form_busca_estado'));
        $this->form->setTitle('Estado');

        //busca
        $descricao = new Entry('descricao');
        $this->form->addField('Descrição',$descricao,'100%');
        $this->form->addAction('Buscar', new Action(array($this,'onReload')));
        $this->form->addAction('Novo', new Action(array(new EstadosForm,'onEdit')));

        $this->datagrid = new DatagridWrapper(new Datagrid);

        $id =     new DatagridColumn('id', 'ID','center','30%');
        $descricao = new DatagridColumn('descricao','Descrição','left','70%');

        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addAction( 'Editar',  new Action([new EstadosForm, 'onEdit']), 'id', 'fa fa-edit fa-lg blue');
        $this->datagrid->addAction('Excluir',  new Action([$this,'onDelete']), 'id','fa fa-trash fa-lg red');

        $box =new VBox;
        $box->style = 'display:block';
        $box->add($this->form);
        $box->add($this->datagrid);

        parent::add($box);

    }

    public function onReload()
    {
        Transaction::open('clinicaphp');
        $repository = new Repository('Estados');

        $criteria = new Criteria;
        $criteria->setProperty('order','id');

        $dados = $this->form->getData();
        if($dados->descricao)
        {
            $criteria->add('descricao','like',"%{$dados->descricao}%");
        }

        $estados = $repository->load($criteria);
        $this->datagrid->clear();
        if($estados)
        {
            foreach($estados as $estado)
            {
                $this->datagrid->addItem($estado);
            }
        }
        Transaction::close();
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

    public function onDelete($param)
    {
        $id = $param['id']; // obtém o parâmetro $id
        $action1 = new Action(array($this, 'Delete'));
        $action1->setParameter('id', $id);
        
        new Question('Deseja realmente excluir o registro?', $action1);
    }

    public function Delete($param)
    {
        try
        {
            $id = $param['id']; // obtém a chave
            Transaction::open('clinicaphp'); // inicia transação com o banco 'livro'
            $estados = Estados::find($id);
            $estados->delete(); // deleta objeto do banco de dados
            Transaction::close(); // finaliza a transação
            $this->onReload(); // recarrega a datagrid
            new Message('info', "Registro excluído com sucesso");
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
        }
    }
}