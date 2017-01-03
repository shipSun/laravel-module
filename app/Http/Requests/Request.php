<?php
/**
 * @author ship
 */
namespace App\Http\Requests;

use Illuminate\Http\Request as BaseRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use App\Exceptions\ParamException;

class Request extends BaseRequest implements ValidatesWhenResolved{
	protected $container;
	
	public function validate(){
		$instance = $this->getValidatorInstance();
		if (! $instance->passes()) {
			$this->failedValidation($instance);
		}
	}
	protected function getValidatorInstance(){
		$factory = $this->container->make(ValidationFactory::class);
	
		return $factory->make(
				$this->validationData(), $this->container->call([$this, 'rules']), $this->messages(), $this->attributes()
		);
	}
	protected function validationData()
	{
		return $this->all();
	}
	protected function failedValidation(Validator $validator){
		throw new ParamException($this->response(
				$this->formatErrors($validator)
		));
	}
	protected function formatErrors(Validator $validator){
		return $validator->getMessageBag()->toArray();
	}
	public function response(array $errors){
		$str = '';
		foreach($errors as $key=>$val){
			$str.=$key.':(';
			foreach($val as $v){
				$str.= ' '.$v;
			}
			$str.= ')';
		}
		return $str;
	}
	public function setContainer(Container $container){
		$this->container = $container;
	}
	public function attributes()
	{
		return [];
	}
}