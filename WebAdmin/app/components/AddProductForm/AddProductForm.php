<?php

namespace Components\Forms;

use Components\FormFactory;
use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;

class AddProductForm extends UI\Control
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
        $this->template->setFile(__DIR__ . '/addProductForm.latte');
        $this->template->render();
    }

    protected function createComponentForm()
    {
        $form = $this->factory->create();

        $field = $form->addText('barcode')
            ->setAttribute('placeholder', 'Produktový kód')
            ->setRequired('Požadované pole');

        $field->getControlPrototype()->class[] = 'form-control';


        $form->addRadioList('safe', '', [
            1 => 'Bez palmového oleje',
            0 => 'Obsahuje palmový olej',
        ]);

        $prototype = $form->addSubmit('save', 'Uložit')
            ->getControlPrototype();
        $prototype->class[] = 'btn';
        $prototype->class[] = 'btn-primary';


        $form->onSuccess[] = $this->processForm;

        return $form;
    }

    public function processForm(Form $form, $values)
    {
        $this->onSave($this, $values);
    }

}

interface IAddProductFormFactory
{
    /** @return AddProductForm */
    function create();
}

