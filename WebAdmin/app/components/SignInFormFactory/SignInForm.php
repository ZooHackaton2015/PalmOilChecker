<?php

namespace Components\Forms;

use Components\FormFactory;
use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;

class SignInForm extends UI\Control
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
		$this->template->setFile(__DIR__ . '/signInForm.latte');
		$this->template->render();
	}

	public function setDefaults($defaults)
	{
		$this['form']->setDefaults($defaults);
	}

	protected function createComponentForm()
	{
		$form = $this->factory->create();

		$form->addText('email', 'Email:')
			->setRequired('Pole email je požadované.')
			->addCondition(Form::EMAIL  );

		$form->addPassword('password', 'Heslo:')
			->setRequired('Nezapomínejte na heslo.');

		$form->addCheckbox('remember', 'Pamatovat si mě (déle)');

		$form->addSubmit('send', 'Přihlásit se');

		$form->onSuccess[] = $this->processForm;

		return $form;
	}

	public function processForm(Form $form, $values)
	{
		$this->onSave($form, $values);
	}

}

interface ISignInFormFactory
{
	/** @return SignInForm */
	function create();
}

