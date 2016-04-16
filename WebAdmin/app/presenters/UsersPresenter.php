<?php

namespace App\Presenters;

use App\Forms\FormFactory;
use App\Model\Entities\User;
use App\Model\UserManager;
use Components\Forms\IEditUserFormFactory;
use Nette;

use App\Model;
use App\Model\Users;
use Nette\Application\UI\Form;


class UsersPresenter extends BasePresenter
{
	const USERS_PER_PAGE = 20;

	/** @var FormFactory @inject */
	public $formFactory;

	/** @var IEditUserFormFactory @inject */
	public $editUserFormFactory;

	/** @var UserManager @inject */
	public $userManager;

	/** @var Users @inject */
	public $users;

	protected function startup()
	{
		parent::startup();
	}


	public function actionDefault($page = 1)
	{
		$this->template->users = $this->users->findMany(self::USERS_PER_PAGE, $page - 1);

		$this->template->canAddNewUser = true;
	}

	public function actionEdit($id)
	{
		$user = $this->users->find($id);

		$this['addUserForm']->setDefaults($user->asArray());


	}

	public function actionDelete($id)
	{

	}

	public function createComponentAddUserForm()
	{
		/** @var Form $form */
		$form = $this->formFactory->create();

		$form->addText('email', 'Email')
			->addRule(Form::EMAIL, 'Zadany řetězec nevypadá jako email')
			->setAttribute('placeholder', 'Email');
		$form->addPassword('password')
			->addRule(Form::MIN_LENGTH, 'Heslo musí být alespoň %d znaků dlouhé', 6)
			->setAttribute('placeholder', 'Heslo');
		$form->addSubmit('save', 'Přidat uživatele');


		$form->onSuccess[] = function ($form, $values) {
			$this->userManager->add($values->email, $values->password);
			$this->redirect('this');
		};
		return $form;
	}

	public function createComponentEditUserForm()
	{
		$form = $this->editUserFormFactory->create();

		$form->onSave[] = function ($form, $values) {
			$id_user = $values->id_user;
			$user = new User;
			$user->setEmail($values->email);
			$user->setPassword($values->password);
			$this->users->update($user);
			dump($values);exit;
		};
		return $form;
	}

}
