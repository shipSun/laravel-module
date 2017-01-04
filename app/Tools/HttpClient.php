<?php
/**
 * @author ship
 */
namespace App\Tools;

use Core\Exceptions\SystemException;
use Log;

class HttpClient{
	public $appID='';
	public $encrypt=false;
	public $signType='MD5';
	public $ssl = false;
	public $cerPath;
	public $timeout=10;
	public $data;
	
	public function __construct($appID='', $signType='MD5', $ssl=false, $cerPath='', $timeout=10){
		$this->appID = $appID;
		$this->signType = $signType;
		$this->ssl = $ssl;
		$this->cerPath = $cerPath;
		$this->timeout = $timeout;
		$this->encrypt = config('app.sign', false);
	}
	public function get($url, $data){
		if($this->encrypt){
			$data = array_merge($data, $this->sign($this->appID, $this->signType, $data));
		}
		$url.= '?'.http_build_query($data);
		return $this->excute($url, [], 'get');
	}
	public function post($url, $data){
		if($this->encrypt){
			$data = array_merge($data, $this->sign($this->appID, $this->signType, $data));
		}
		return $this->excute($url, $data, 'post');
	}
	protected function sign($appID, $signType, $signData){
		$signData['product_app_id'] = $appID;
		$signData['product_sign_type'] = $signType;
		unset($signData['interface']);
		
		$url = config('module.common').'?interface=encrypt.sign&'.http_build_query($signData);
		$response = $this->excute($url, [], 'get');
		if($response['code'] != 'success'){
			throw new SystemException('签名失败');
		}
		
		$signData['product_sign'] = $response['sign'];
		return $signData;
	}
	protected function excute($url, $data=[], $method='get'){
		Log::debug('请求模块:'.$url, $data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($this->ssl){
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->ssl);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 2);
			curl_setopt($ch, CURLOPT_CAINFO , $this->cerPath);
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , $this->timeout);
		
		if($method=='post'){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			throw new SystemException('curl错误信息:'.curl_errno($ch), 500);
		}
		
		$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		Log::debug('响应状态:'.$httpStatusCode, [$response]);
		
		$totalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		if($totalTime > 10){
			Log::warning('运行时间:'.$totalTime.',运行时间过长！',['url'=>$url]);
		}
		curl_close($ch);
		$data = json_decode($response, true);
		if(!is_array($data)){
			Log::debug('返回非json格式', [$response]);
			$data = $response;
		}
		return $data;
	}
}