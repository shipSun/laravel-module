<?php
/**
 * @author ship
 */
namespace App\Tools;

class ResponseTool {
	public static function success($msg, $data=[]){
		return self::response('success', $msg, $data);
	}
	public static function fail($msg, $data=[]){
		return self::response('fail', $msg, $data);
	}
	public static function response($code, $msg, $data){
		if(!is_array($data)){
			$data = [];
		}
		$data['code'] = $code;
		$data['msg'] = $msg;
		return response()->json($data);
	}
}