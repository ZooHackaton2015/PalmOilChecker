<?php

namespace App\Presenters;


use App\Model\Products;
use App\Model\Users;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;
use Nette\Http\IResponse;
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
			$this->sendJsonError('Přihlášení je povinné', IResponse::S401_UNAUTHORIZED);
		}
		$this->request = $this->getRequest();
		$this->requestBody = Json::decode(file_get_contents('php://input'));
	}

	public function actionLogin(){
		$email = filter_input(INPUT_POST, 'email');
		$password = filter_input(INPUT_POST, 'password');
		try{
			$this->user->login($email, $password);
			$this->sendJsonSuccess([], 'Přihlášení úspěšné');
		} catch (AuthenticationException $e){
			$this->sendJsonError($e->getMessage(), IResponse::S401_UNAUTHORIZED);
		}
	}

	public function actionLogout(){
		$this->user->logout();
		$this->sendJsonSuccess([], 'Odhlášení proběhlo úspěšně');
	}

    protected function sendJsonSuccess($data, $message = '', $code = IResponse::S200_OK)
    {
        $this->sendJson($data, self::STATUS_OK, $message, $code);
    }

	protected function sendJsonError($message, $code = IResponse::S500_INTERNAL_SERVER_ERROR, $data = [])
    {
        $this->sendJson($data, self::STATUS_ERROR, $message, $code);
    }

    /**
     * @param $status
     * @param $message
     * @param null|int $code
     */
	public function sendJson($data, $status = self::STATUS_OK, $message = '', $code = IResponse::S200_OK)
	{
	    if (!is_int($code)) {
	        $code = IResponse::S500_INTERNAL_SERVER_ERROR;
        }
        $this->getHttpResponse()->setCode($code);

		$data['status'] = $status;
        if($message){
            $data['message'] = $message;
        }

        parent::sendJson($data);
	}

}
