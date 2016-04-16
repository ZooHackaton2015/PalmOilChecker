<?php

namespace Components;

use Nette;
use Nette\Application\UI\Form;


class AddUserFormFactory extends Nette\Object
{
	/** @var FormFactory */
	private $factory;


	public function __construct(FormFactory $factory)
	{
		$this->factory = $factory;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();

		$form->addText('email', 'Email')
			->addRule(Form::EMAIL, 'Zadany řetězec nevypadá jako email')
			->setAttribute('placeholder', 'Email');
		$form->addPassword('password')
			->addRule(Form::MIN_LENGTH, 'Heslo musí být alespoň %d znaků dlouhé', 6)
			->setAttribute('placeholder', 'Heslo');
		$form->addSubmit('save', 'Přidat uživatele');

		return $form;
	}




}
