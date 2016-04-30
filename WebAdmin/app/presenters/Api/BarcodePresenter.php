<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 30.04.2016
 * Time: 16:12
 */

namespace App\Presenters;

use App\Model\Entities\Product;

class BarcodePresenter extends ApiPresenter
{
	public function actionDefault()
	{
		$method = $this->request->getMethod();
		switch ($method) {
			default:
				$this->respond(self::STATUS_ERROR, 'PRODUCT: Nebyla rozpoznána metoda ' . $method, 2);
				break;
			case 'POST':
				$this->productPost();
		}
		exit;
	}

	private function productPost()
	{
		$properties = ['barcode', 'contains-oil'];
		$tmpProduct = [];
		foreach($properties as $property){
			if(!isset($this->requestBody->$property)){
				$this->respond(self::STATUS_ERROR,
					'U přidávaného produktu chybí pole \'' . $property . '\'. Prosím poskytněte tato pole: ' . implode(', ', $properties), 3);
			}
			$tmpProduct[$property] = $this->requestBody->$property;
		}

		$product = new Product;
		$product->setBarcode($tmpProduct['barcode']);
		$product->setSafe(!$tmpProduct['contains-oil']);
		$result = $this->products->add($product);

		$safeString = $product->getSafe() ? 'bezpečný' : 'obsahující olej';
		$status = $result ? self::STATUS_OK : self::STATUS_ERROR;

		$this->respond($status, 'Kód ' . $product->getBarcode() . ' je nyní evidován jako ' . $safeString);
	}
}