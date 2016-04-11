<?php

namespace App\Presenters;

use App\Model\Entities\Product;
use App\Model\Products;
use Components\Forms\IAddProductFormFactory;
use Nette;
use App\Model;


class ProductsPresenter extends BasePresenter
{

    /** @var Products @inject */
    public $products;

    /** @var IAddProductFormFactory @inject  */
    public $addProductFormFactory;

    protected function startup()
    {
        parent::startup();
    }

    public function handleFindProducts($barcode = 20)
    {
        $this->renderDefault($barcode);
        $this->redrawControl('products');
    }

    public function handleSetProductSafe($productCode, $safe)
    {
        $this->products->setProductSafe($productCode, $safe);
    }


    public function renderDefault($barcode = 0)
	{

//        $products = $this->products->findAll();
        $products = $this->mockProducts($barcode);

        $this->template->products = $products;
	}

    public function actionDelete($barcode)
    {

    }
    
    
    public function createComponentAddProductForm()
    {
        $form = $this->addProductFormFactory->create();

        return $form;
    }

    private function mockProducts($e = 0){
        $e *= 1;

        $products = [];
        for ($i = 0; $i < 9; $i++) {
            $product = new Product();
            srand($e + $i);
            $code = '';
            for ($c = 0; $c < 13; $c++) {
                $code .= rand(0,9);
            }
            $product->setBarcode($code);

            $product->setSafe($i % 5 == 0 || $i % 3 == 2);

            $products[] = $product;
        }
        return $products;
    }

}
