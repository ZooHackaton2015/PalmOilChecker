<?php

namespace Components\Forms;

use App\Model\Entities\User;
use Components\FormFactory;
use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;

class EditUserForm extends UI\Control
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
		$this->template->setFile(__DIR__ . '/editUserForm.latte');
		$this->template->render();
	}

	public function setDefaults($defaults)
	{
		$this['form']->setDefaults($defaults);
	}

	protected function createComponentForm()
	{
		$form = $this->factory->create();

		$field = $form->addText('email', 'Email:')
			->setAttribute('placeholder', 'Email')
			->addRule(Form::EMAIL, 'Pole email je požadované.');

		$field->addCondition(Form::EMAIL);

		$password = $form->addPassword('password', 'Heslo:')
			->setAttribute('placeholder', 'Heslo');
		$password->addCondition(Form::FILLED)
			->addRule(Form::MIN_LENGTH, 'Heslo musí být alespoň %d znaků dlouhé', 6);

		$form->addSelect('role', null, User::getRoles());

		$prototype = $form->addSubmit('save', 'Uložit změny')
			->getControlPrototype();
		$prototype->class[] = 'btn';
		$prototype->class[] = 'btn-primary';

		$form->addHidden('id_user');


		$form->onSuccess[] = $this->processForm;

		return $form;
	}

	public function processForm(Form $form, $values)
	{
		$this->onSave($this, $values);
	}

}

interface IEditUserFormFactory
{
	/** @return EditUserForm */
	function create();
}

