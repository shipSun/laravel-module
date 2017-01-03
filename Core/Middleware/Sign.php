<?php
/**
 * @author ship
 */
namespace Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Model\CommonModel;
use Core\Exceptions\SystemException;
use Core\Exceptions\ParamException;
use Core\Exceptions\NoticeException;
use Log;

class Sign {
	
	public function handle(Request $request, Closure $next){
		if(config('app.sign', false)){
			$appName = $this->getAppName();
			
			$this->diffAppName($appName, $request->get('product_app_id'));
			
			$data['verify_app_id'] = $appName;
			$request->request->remove('product_app_id');
			$request->query->remove('product_app_id');
			
			$data['verify_sign'] = $request->get('product_sign');
			$request->request->remove('product_sign');
			$request->query->remove('product_sign');
			
			$data['verify_sign_type'] = $request->get('product_sign_type');
			$request->request->remove('product_sign_type');
			$request->query->remove('product_sign_type');
			
			Log::debug($appName.'签名验证', $data);
			$cashier = CommonModel::verify($data);
			
			if($cashier['code']=='fail'){
				throw new SystemException($appName.'签名验证失败');
			}
		}
		return $next($request);
	}
	protected function getAppName(){
		$appName = config('app.app_name','');
		if(empty($appName)){
			throw new SystemException('开启签名功能,模块名称不能为空');
		}
		return $appName;
	}
	protected function diffAppName($appName, $diffAppName){
		if($appName!=$diffAppName){
			throw new NoticeException('模块名称不匹配');
		}
		return true;
	}
}