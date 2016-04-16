<?php

namespace App\Presenters;

use Nette;
use App\Forms\SignFormFactory;
use Nette\Application\UI\Form;


class SignPresenter extends BasePresenter
{
	/** @var SignFormFactory @inject */
	public $factory;

	public $onSave = [];

	public function actionOut()
	{
		$this->getUser()->logout();
		$this->redirect('Homepage:');
	}


	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->factory->create();
		$form->onSuccess[] = function ($form, $values) {
			$this->formSucceeded($form, $values);
			if($this->user->isLoggedIn()){
				$this->redirect('Homepage:');
			}
		};
		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{
		if ($values->remember) {
			$this->user->setExpiration('14 days', FALSE);
		} else {
			$this->user->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->user->login($values->email, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('The username or password you entered is incorrect.');
		}
	}

}
