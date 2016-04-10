<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Libs\NavbarBuilder;
use MongoDB\Client;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var Client */
    protected $mongoClient;

    public function __construct(Client $mongoClient)
    {
        parent::__construct();
        $this->mongoClient = $mongoClient;
    }

    protected function startup()
    {
        parent::startup();
        /*
        if(!$this->user->isLoggedIn() && !($this->getPresenter() instanceof SignPresenter)){
            $this->flashMessage('Pro vstup do aplikace musíte být přihlášeni.');
            $this->redirect('Sign:in');
        }
        */
        $this->template->navbar = NavbarBuilder::createNavbar();
    }

}
