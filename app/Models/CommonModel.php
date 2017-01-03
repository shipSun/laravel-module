<?php
/**
 * @author ship
 */
namespace App\Model;

use App\Tools\HttpClient;
use Core\Exceptions\SystemException;
use Core\Exceptions\NoticeException;

class CommonModel extends HttpClient{
	public static $gateway;
	
	public function __construct($appID='common', $signType='MD5', $ssl=false, $cerPath='', $timeout=10){
		self::$gateway = config('module.common');
		parent::__construct($appID, $signType, $ssl, $cerPath, $timeout);
	}
	public static function productPayType($key){
		$data['product_type'] = $key;
		
		$data['interface'] = 'productPayType.paytype';
		$data = self::getClient($data);
		if($data['code']=='success'){
			return $data['finance_product_type'];
		}
		throw new SystemException($data['msg']);
	}
	public static function company($key){
		$data['product_type'] = $key;
		
		$data['interface'] = 'company.id';
		$data = self::getClient($data);
		
		if($data['code']=='success'){
			return $data['user_id'];
		}
		throw new SystemException($data['msg']);
	}
	public static function config($key){
		$data['key'] = $key;
		
		$data['interface'] = 'config.run';
		$data = self::getClient($data);
		
		if($data['code']=='success'){
			return $data;
		}
		throw new SystemException($data['msg']);
	}
	public static function verify($data){
		if(!isset($data['verify_app_id'])){
			throw new NoticeException('product_app_id不能为空');
		}
		if(!isset($data['verify_sign'])){
			throw new NoticeException('product_sign不能为空');
		}
		if(!isset($data['verify_sign_type'])){
			throw new NoticeException('product_sign_type不能为空');
		}
		
		$data['interface'] = 'encrypt.verify';
		return self::getClient($data);
	}
	public static function getPayTypeShowByClient($key){
		$data['client'] = $key;
		$data['interface'] = 'payType.lists';
		$data = self::getClient($data);
		if($data['code']!='success'){
			throw new SystemException('支付类型为空');
		}
		unset($data['code']);
		unset($data['msg']);
		return $data;
	}
	protected static function getClient($data){
		$client = new Static();
		return $client->get(self::$gateway, $data);
	}
}