<?php

namespace App\Presenters;

use App\Model\Entities\Product;
use Nette\Http\IResponse;

class BarcodePresenter extends ApiPresenter
{
	public function actionDefault()
	{
		$method = $this->request->getMethod();
		switch ($method) {
			default:
				$this->sendJsonError('PRODUCT: Nebyla rozpoznána metoda ' . $method, IResponse::S405_METHOD_NOT_ALLOWED);
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
				$this->sendJsonError(
					'U přidávaného produktu chybí pole \'' . $property . '\'. Prosím poskytněte tato pole: ' . implode(', ', $properties), IResponse::S400_BAD_REQUEST);
			}
			$tmpProduct[$property] = $this->requestBody->$property;
		}

		$product = new Product;
		$product->setBarcode($tmpProduct['barcode']);
		$product->setSafe(!$tmpProduct['contains-oil']);
		$result = $this->products->add($product);

		$safeString = $product->getSafe() ? 'bezpečný' : 'obsahující olej';

		$status = $result ? self::STATUS_OK : self::STATUS_ERROR;
		$code = $result ? IResponse::S200_OK : IResponse::S500_INTERNAL_SERVER_ERROR;

		$this->sendJson([], $status, 'Kód ' . $product->getBarcode() . ' je nyní evidován jako ' . $safeString, $code);
	}
}
