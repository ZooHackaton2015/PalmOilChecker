<?php

namespace App\Presenters;

use App\Forms\FormFactory;
use Nette;
use App\Forms\AddNewUserFactory;

use App\Model;
use App\Model\Users;


class UsersPresenter extends BasePresenter
{
    /** @var AddNewUserFactory @inject  */
    private $addUserFactory;

    /** @var Users */
    private $users;

    protected function startup()
    {
        parent::startup();
        $this->users = new Users($this->mongoClient);
        $this->addUserFactory = new AddNewUserFactory();
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
