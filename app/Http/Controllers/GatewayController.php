<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Tools\ResponseTool;
use Illuminate\Http\Exception\HttpResponseException;

class GatewayController extends Controller{
	public function index(Request $request){
		return $this->run($request);
	}
	protected function run(Request $request){
		$interface = $request->get('interface', null);
		$className = 'App\Interfaces\\'.$this->getInterfaceName($interface);
		$interfaceObject = new $className();
		$actionName = $this->getAction($interface);
		
		$httpRequest = 'App\Http\Requests\\'.$actionName.'Request';
		
		if(class_exists($httpRequest)){
			$request = $httpRequest::createFromGlobals();
			if(method_exists($request, 'validate')){
				$request->setContainer(app());
				$request->validate();
			}
		}
		
		if(!method_exists($interfaceObject, $actionName)){
			throw new \ErrorException('接口不存在');
		}
		
		$interfaceObject->request = $request;
		return $interfaceObject->$actionName();
	}
	protected function getInterfaceName($string){
		$arr = $this->interfaceStrToArr($string);
		if(!isset($arr[0])){
			throw new \ErrorException('接口参数错误');
		}
		return ucwords($arr[0]);
	}
	protected function getAction($string){
		$arr = $this->interfaceStrToArr($string);
		if(!isset($arr[0])){
			throw new \ErrorException('接口参数错误');
		}
		$action = array_pop($arr);
		return ucwords($action);
	}
	protected function interfaceStrToArr($string){
		return explode('.', $string);
	}
}