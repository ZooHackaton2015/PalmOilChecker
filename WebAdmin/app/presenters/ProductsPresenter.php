<?php

namespace App\Presenters;

use App\Model\Entities\Product;
use App\Model\Products;
use Components\Forms\IAddProductFormFactory;
use Nette;
use App\Model;


class ProductsPresenter extends BasePresenter
{

	const PRODUCTS_PER_PAGE = 10;

	/** @var Products @inject */
	public $products;

	/** @var IAddProductFormFactory @inject */
	public $addProductFormFactory;

	protected function startup()
	{
		parent::startup();
	}

	/*
	public function handleFindProducts($barcode = 20)
	{
		$this->renderDefault($barcode);
		$this->redrawControl('products');
	}
	*/

	public function handleSetProductSafe($productCode, $safe, $page = 1)
	{

		$this->products->setProductSafe($productCode, !!$safe, $this->user->id);

		$this->renderDefault($page);
		$this->redrawControl('products');
	}


	public function renderDefault($page = 1, $barcodeSearch = null)
	{
		$counts = $this->products->count();
		$totalProducts = $counts['total'];
		$products = $this->products->findMany(self::PRODUCTS_PER_PAGE, $page - 1);

		$this->template->products = $products;
		$this->template->productCounts = $counts;
		$this->template->currentPage = $page;

		$this['paginator']->setPagesInfo($totalProducts, self::PRODUCTS_PER_PAGE, $page);;
	}

	public function actionDelete($barcode, $page = 1)
	{
		$this->products->delete($barcode);
		$this->flashMessage('Záznam s kódem '. $barcode .' byl odebrán.');
		$this->redirect('default', $page);
	}


	public function createComponentAddProductForm()
	{
		$form = $this->addProductFormFactory->create();

		$form->onSave[] = function ($form, $values) {
			$this->processAddProductForm($form, $values);
			$this->flashMessage('Informace o produktu byla přidána');
			$this->redirect('this');
		};

		return $form;
	}

	public function processAddProductForm($form, $values)
	{
		$barcode = $values->barcode;
		$safe = $values->safe;
		$this->products->setProductSafe($barcode, $safe, $this->user->id);
	}

}
