<?php
namespace Core;

use Illuminate\Routing\Router as BaseRouter;

class Router extends BaseRouter{
	public function prepareResponse($request, $response){
		if(is_array($response)){
			$response['code'] = 'success';
			$response['msg'] = 'success';
		}
		return parent::prepareResponse($request, $response);
	}
}