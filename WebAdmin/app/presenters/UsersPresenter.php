<?php

namespace App\Presenters;

use Components\AddUserFormFactory;
use Components\Forms\IEditUserFormFactory;

use App\Model\Entities\User;
use App\Model\UserManager;

use Nette;

use App\Model;
use App\Model\Users;


class UsersPresenter extends BasePresenter
{
	const USERS_PER_PAGE = 10;

	/** @var AddUserFormFactory @inject */
	public $addUserFormFactory;

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
		$this->template->currentPage = $page;


		$this['paginator']->setPagesInfo($this->users->count(), self::USERS_PER_PAGE, $page);;
	}

	public function actionEdit($id)
	{
		$user = $this->users->find($id * 1);

		$this['editUserForm']->setDefaults($user->asArray());


	}

	public function actionDelete($id, $page = 1)
	{
		$user = $this->users->delete($id);
		if(!$user){
			$this->flashMessage('Při mazání uživatele nastaly neočekávané potíže.');
		} else {
			$this->flashMessage('Uživatel '. $user->getEmail() .' byl odebrán ze systému.');
		}
		$this->redirect('default', $page);
	}

	public function createComponentAddUserForm()
	{
		$form = $this->addUserFormFactory->create();

		$form->onSuccess[] = function ($form, $values) {
			$this->userManager->add($values->email, $values->password);
			$this->flashMessage('Uživatel '. $values->email .' byl přidán.');
			$this->redirect('this');
		};
		return $form;
	}

	public function createComponentEditUserForm()
	{
		$form = $this->editUserFormFactory->create();

		$form->onSave[] = function ($form, $values) {
			$this->userManager->update($values);
			$this->flashMessage('Uživatel '. $values->email .' byl upraven.');
			$this->redirect('this');
		};
		return $form;
	}
}
