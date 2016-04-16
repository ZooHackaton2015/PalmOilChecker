<?php

namespace App\Presenters;

use Components\Paginator;
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
		$allowedView = $this->isPubliclyOpenView();
		if (!$this->user->isLoggedIn() && !$allowedView) {
			$this->flashMessage('Pro vstup do aplikace musíte být přihlášeni.');
			$this->redirect('Sign:in');
		}

		$this->template->navbar = NavbarBuilder::createNavbar();
	}

	private function isPubliclyOpenView()
	{
		$presenter = $this->getPresenter();
		if ($presenter instanceof SignPresenter || $presenter instanceof HomepagePresenter) {
			return true;
		}
		return false;
	}

	public function createComponentPaginator()
	{
		return new Paginator;
	}

}
