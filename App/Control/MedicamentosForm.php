<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Container\VBox;
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
class MedicamentosForm extends Page
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
        $this->activeRecord = 'Medicamentos';


        $this->form = new FormWrapper(new Form('form_busca_medicamentos'));
        $this->form->setTitle('Medicamentos');

        //campos do formulario
        $id = new Entry('id');
        $descricao = new Entry('descricao');
        $fabricante = new Entry('fabricante');
        $composicao = new Entry('composicao');
        $contraindicacoes = new Entry('contraindicacoes');

        //cria form
        $id->setEditable(FALSE);

        $this->form->addField('ID', $id, '20%');
        $this->form->addField('Descrição', $descricao, '70%');
        $this->form->addField('Fabricante', $fabricante, '70%');
        $this->form->addField('Composição', $composicao, '70%');
        $this->form->addField('Contraindicações', $contraindicacoes, '70%');
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));

        parent::add($this->form);

        $box = new VBox;
        $box->style = 'display:block';
        $box->add(file_get_contents('App/Templates/Calendar.html'));
        
        parent::add($box);



    }
}