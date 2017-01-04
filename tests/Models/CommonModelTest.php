<?php
namespace Tests\Models;

use Tests\TestCase;
use App\Model\CommonModel;

class CommonModelTest extends TestCase{
	
	public function testProductPayType(){
		$data = CommonModel::productPayType('4');
		$this->assertEquals('direct', $data);
	}
	public function testCompany(){
		$data = CommonModel::company(1);
		$this->assertInternalType('int', $data);
		$this->assertEquals(3, $data);
	}
	public function testConfig(){
		$data = CommonModel::config('notice');
		$this->assertInternalType('array', $data);
		$this->assertEquals(false, empty($data));
	}
	public function testVerify(){
		$data['verify_app_id'] = 'cashier';
		$data['verify_sign'] = 'bf14d9c955148e59ce298b543b11db80';
		$data['verify_sign_type'] = 'md5';
		$data['a'] = 11;
		$data = CommonModel::verify($data);
		$this->assertInternalType('array', $data);
		$this->assertArrayHasKey('code', $data);
		$this->assertEquals('success', $data['code']);
	}
	public function testGetPayTypeShowByClient(){
		$data = CommonModel::getPayTypeShowByClient('web');
		$this->assertInternalType('array', $data);
	}
	/**
	 * @expectedException Core\Exceptions\SystemException
	 * @expectedExceptionCode 0
	 * @expectedExceptionMessage 没有对应支付类型
	 */
	public function testGetPayTypeShowByClientThrowException(){
		CommonModel::getPayTypeShowByClient('web1');
	}
}