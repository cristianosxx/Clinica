<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\CheckGroup;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Wrapper\FormWrapper;

class EstadosForm extends Page{
    private $form;

    public function __construct()
    {
        parent::__construct();
        $this->form = new FormWrapper(new Form('form_estados'));
        $this->form->setTitle('Estados');

        $id = new Entry('id');
        $descricao = new Entry('descricao');


        $this->form->addField('ID',$id,'40%');
        $this->form->addField('Descrição',$descricao,'70%');

        $id->setEditable(FALSE);

        $this->form->addAction('Salvar', new Action ([$this, 'onSave']));

        parent::add($this->form);
    }

    public function onSave()
    {
        try
        {
            // inicia transação com o BD
            Transaction::open('clinicaphp');

            $dados = $this->form->getData();
            $this->form->setData($dados);
            $esp = new Estados(); // instancia objeto
            $esp->fromArray( (array) $dados); // carrega os dados
            $esp->store(); // armazena o objeto no banco de dados
            
            Transaction::close(); // finaliza a transação
            new Message('info', 'Dados armazenados com sucesso');
        }
        catch (Exception $e)
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', $e->getMessage());

            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }

    public function onEdit($param)
    {
        try
        {
            if (isset($param['id']))
            {
                $id = $param['id']; // obtém a chave
                Transaction::open('clinicaphp'); // inicia transação com o BD
                $estados = Estados::find($id);
                if ($estados)
                {
                    $this->form->setData($estados); // lança os dados da pessoa no formulário
                }
                Transaction::close(); // finaliza a transação
            }
        }
        catch (Exception $e)		    // em caso de exceção
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', $e->getMessage());
            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
}