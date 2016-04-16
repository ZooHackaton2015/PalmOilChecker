<?php

namespace Components;

use Nette;
use Nette\Application\UI\Form;


class SignFormFactory extends Nette\Object
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

		$form->addText('email', 'Email:')
			->setRequired('Pole email je požadované.')
            ->addCondition(Form::EMAIL  );

		$form->addPassword('password', 'Heslo:')
			->setRequired('Nezapomínejte na heslo.');

		$form->addCheckbox('remember', 'Pamatovat si mě (déle)');

		$form->addSubmit('send', 'Přihlásit se');
		return $form;
	}




}
