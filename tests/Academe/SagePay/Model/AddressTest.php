<?php

require 'vendor/autoload.php';

use Academe\SagePay;

class AddressTest extends PHPUnit_Framework_TestCase{
	public function testSetField(){
		$addr = new Academe\SagePay\Model\Address;
		$addr->setField('Surname', 'Test Surname');
		$this->assertEquals($addr->getField('Surname'), 'Test Surname');
	}
}