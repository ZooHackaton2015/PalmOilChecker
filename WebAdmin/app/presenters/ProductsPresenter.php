<?php

namespace App\Presenters;

use App\Model\Entities\Product;
use App\Model\Products;
use Nette;
use App\Model;

use App\Model\Users;


class ProductsPresenter extends BasePresenter
{

    /** @var Products  */
    private $products;

    protected function startup()
    {
        parent::startup();
        $this->products = new Products($this->mongoClient);
    }

    public function handleFindProduct($like = 2)
    {
        //$products = $this->products->findLike($like, 20);
        $products = $this->mockProducts($like);
        $this->renderProducts($products);
    }

    public function handleSetProductSafe($productCode, $safe)
    {
        $this->products->setProductSafe($productCode, $safe);
    }


    public function renderDefault()
	{
        $this->renderProducts($this->mockProducts());
	}

    private function renderProducts($products = [])
    {
        $this->template->products = $products;
        if($products) {
            $this->redrawControl('products');
        }
    }

    public function actionDelete($barcode)
    {

    }
    
    
    public function createComponentProductForm()
    {

    }

    private function mockProducts($e = 0){
        $products = [];
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $code = '';
            for ($c = 0; $c < 13; $c++) {
                $code .= ($i ^ $c + $c * 3) % 10;
            }
            $product->setBarcode($code);
            $product->setSafe((($i ^ 6 + $c * 3) % 2) == 0);
            $products[] = $product;
        }
        return $products;
    }

}
