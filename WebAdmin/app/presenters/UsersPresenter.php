<?php

namespace App\Presenters;

use Components\Forms\IAddNewUserFormFactory;
use Nette;

use App\Model;
use App\Model\Users;


class UsersPresenter extends BasePresenter
{
    /** @var IAddNewUserFormFactory @inject  */
    public $addUserFactory;

    /** @var Users @inject*/
    public $users;

    protected function startup()
    {
        parent::startup();
    }


    public function actionDefault()
	{
        $this->template->users = $this->users->findAll();

        $this->template->canAddNewUser = true;
	}

    public function actionEdit($id)
    {
        $user = $this->users->find($id);


        $this['addUserForm']->setDefaults($user->asArray());

        dump($this['addUserForm']);exit;
    }

    public function actionDelete($id)
    {

    }

    public function createComponentAddUserForm()
    {
        $form = $this->addUserFactory->create();

        return $form;
    }

}
