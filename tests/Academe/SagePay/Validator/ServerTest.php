<?php

require 'vendor/autoload.php';

use Academe\SagePay;

class ServerValidatorTest extends PHPUnit_Framework_TestCase{

	public function testRequiredFields(){
		$server = new Academe\SagePay\Server;
		$validator = new Academe\SagePay\Validator\Server;
		$validator->validate($server);
		$errors = $validator->getErrors();
		$this->assertArrayHasKey('Firstnames', $errors);
		$this->assertArrayHasKey('Surname', $errors);
		$this->assertArrayHasKey('Address1', $errors);
		$this->assertArrayHasKey('City', $errors);
		$this->assertArrayHasKey('Postcode', $errors);
		$this->assertArrayHasKey('Country', $errors);
	}

}