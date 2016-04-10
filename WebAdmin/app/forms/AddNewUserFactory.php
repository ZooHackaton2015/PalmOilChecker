<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;


class AddNewUserFactory extends Nette\Object
{
	/** @var FormFactory */
	private $factory;


	public function __construct(FormFactory $factory = null)
	{
        if($factory == null){
            $factory = new FormFactory;
        }
		$this->factory = $factory;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();

		$field = $form->addText('email', 'Email:')
            ->setAttribute('placeholder', 'Email')
			->setRequired('Pole email je požadované.');

        $field->addCondition(Form::EMAIL);
        $field->getControlPrototype()->class[] = 'form-control';


		$prototype = $form->addPassword('password', 'Heslo:')
            ->setAttribute('placehoder', 'Heslo')
			->setRequired('Pole heslo je povinné.')
            ->getControlPrototype();
        $prototype->class[] = 'form-control';

		$prototype = $form->addSubmit('save', 'Vytvořit uživatele')
            ->getControlPrototype();
        $prototype->class[] = 'btn';
        $prototype->class[] = 'btn-success';

		return $form;
	}




}
