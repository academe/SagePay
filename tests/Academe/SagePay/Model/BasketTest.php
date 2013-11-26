<?php

require 'vendor/autoload.php';

use Academe\SagePay;

class BasketTest extends PHPUnit_Framework_TestCase{

	public function testSetCurrency(){
		$addr = new Academe\SagePay\Model\Currency;
		$addr->setCurrency('GBP');
		$this->assertEquals($addr->getField('Surname'), 'Test Surname');
	}
}