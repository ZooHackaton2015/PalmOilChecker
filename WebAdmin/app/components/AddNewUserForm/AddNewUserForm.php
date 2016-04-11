<?php

namespace Components\Forms;

use App\Forms\FormFactory;
use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;

class AddNewUserForm extends UI\Control
{
    /** @var FormFactory */
    private $factory;

    public $onSave = [];

    public function __construct(FormFactory $factory)
    {
        parent::__construct();
        $this->factory = $factory;
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/addNewUserForm.latte');
        $this->template->render();
    }

    protected function createComponentForm()
    {
        $form = $this->factory->create();

        $field = $form->addText('email', 'Email:')
            ->setAttribute('placeholder', 'Email')
            ->setRequired('Pole email je požadované.');

        $field->addCondition(Form::EMAIL);
        $field->getControlPrototype()->class[] = 'form-control';


        $prototype = $form->addPassword('password', 'Heslo:')
            ->setAttribute('placeholder', 'Heslo')
            ->setRequired('Pole heslo je povinné.')
            ->getControlPrototype();
        $prototype->class[] = 'form-control';

        $prototype = $form->addSubmit('save', 'Vytvořit uživatele')
            ->getControlPrototype();
        $prototype->class[] = 'btn';
        $prototype->class[] = 'btn-success';


        $form->onSuccess[] = $this->processForm;

        return $form;
    }

    public function processForm(Form $form, $values)
    {
        $this->onSave($this, $values);
    }

}

interface IAddNewUserFormFactory
{
    /** @return AddNewUserForm */
    function create();
}

