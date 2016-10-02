<?php

namespace Components;

use Nette\Application\UI;

class Paginator extends UI\Control
{

	private $pageCount;
	private $currentPage;
	private $link;

	public function __construct($link = 'default')
	{
		parent::__construct();
		$this->link = $link;
	}

	public function setPagesInfo($totalItems, $itemsPerPage, $currentPage = 1){

		$pageCount = max(1, (int)ceil($totalItems / $itemsPerPage));
		$this->pageCount = $pageCount;
		$this->currentPage = $currentPage;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/paginator.latte');

		$this->template->link = $this->link;
		$this->template->pageCount = $this->pageCount;
		$this->template->currentPage = $this->currentPage;

		$this->template->render();
	}
}
