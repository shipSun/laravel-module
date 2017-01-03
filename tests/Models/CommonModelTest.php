<?php
namespace Tests\Models;

use Tests\TestCase;
use App\Model\CommonModel;

class CommonModelTest extends TestCase{
	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testClient(){
		$client = new CommonModel();
		$url = str_replace('gateway', '', CommonModel::$gateway);
		$this->visit($url)
		->see('common');
	}
}