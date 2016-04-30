<?php
/**
 * Created by PhpStorm.
 * User: Stepan
 * Date: 30.04.2016
 * Time: 14:19
 */

namespace App\Presenters;


use App\Model\Products;
use App\Model\Users;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use Nette\Utils\Json;

class ApiPresenter extends Presenter
{
	/** @var Users @inject  */
	public $users;

	/** @var Products @inject  */
	public $products;

	/** @var Request  */
	protected $request;
	protected $requestBody;

	const STATUS_ERROR = 'error';
	const STATUS_OK = 'ok';

	public function startup()
	{
		parent::startup();
		if(!$this->user->isLoggedIn() && $this->getAction() != 'login'){
			$this->respond(self::STATUS_ERROR, 'Přihlášení je povinné', 0);
		}
		$this->request = $this->getRequest();
		$this->requestBody = Json::decode(file_get_contents('php://input'));
	}

	public function actionLogin(){
		$email = filter_input(INPUT_POST, 'email');
		$password = filter_input(INPUT_POST, 'password');
		try{
			$this->user->login($email, $password);
			$this->respond(self::STATUS_OK, 'Přihlášení úspěšné');
		} catch (AuthenticationException $e){
			$this->respond(self::STATUS_ERROR, $e->getMessage(), 1);
		}
	}

	public function actionLogout(){
		$this->user->logout();
		$this->respond(self::STATUS_OK, 'Odhlášení proběhlo úspěšně');
	}


	protected function respond($status, $message, $code = 0)
	{
		$data = [
			'status' => $status,
			'code' => $code,
			'message' => $message
		];
		$this->sendJson($data);
	}

}