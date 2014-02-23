<?php

require 'vendor/autoload.php';

use Academe\SagePay;
use Academe\SagePay\Metadata\Transaction as Transaction;

class ServerValidatorTest extends PHPUnit_Framework_TestCase{
	private $transactionData;
	public function setUp(){
		$this->transactionData = Transaction::get('array');
	}
	public function getBasicServer(){
		$server = new Academe\SagePay\Server;
		$storage = new Academe\SagePay\Model\TransactionPdo("sqlite:memory");

		$server->setPlatform('test');
		$server->setTransactionModel($storage);

		// Start with valid details
		$server->setMain('PAYMENT', 'vendorx', '99.99', 'GBP', 'Store purchase', 'http://example.com/mycallback.php');

		return $server;
	}
	
	public function testRequiredFields(){
		$validator = new Academe\SagePay\Validator\Server;
		$server = $this->getBasicServer();
		$server->setField('Vendor', '');
		$server->setField('Description', '');
		$server->setField('NotificationURL', '');
		$server->setField('Amount', '');

		// Check they return errors when we haven't filled them in
		$errors = $validator->validate($server)->getErrors();
		// TxType and Currency have defaults so we can't check whether it's not been set
		$this->assertArrayHasKey('Vendor', $errors);
		$this->assertArrayHasKey('Description', $errors);
		$this->assertArrayHasKey('NotificationURL', $errors);
		$this->assertArrayHasKey('Amount', $errors);
	}

	public function testRequiredFieldsAreSetInSetMain(){
		$validator = new Academe\SagePay\Validator\Server;
		$server = $this->getBasicServer();
		
		$server->setMain('PAYMENT', 'vendorx', '99.99', 'GBP', 'Store purchase', 'http://example.com/mycallback.php');
		$errors = $validator->validate($server)->getErrors();
		$this->assertArrayNotHasKey('TxType', $errors);
		$this->assertArrayNotHasKey('Vendor', $errors);
		$this->assertArrayNotHasKey('Description', $errors);
		$this->assertArrayNotHasKey('NotificationURL', $errors);
		$this->assertArrayNotHasKey('Amount', $errors);
		$this->assertArrayNotHasKey('Currency', $errors);
	}

	public function testCurrencyFormatValidatorWorks(){
		$validator = new Academe\SagePay\Validator\Server;
		$server = $this->getBasicServer();

		try {
			$server->setCurrency('nothing');
		} catch (Exception $e){
			$this->assertStringStartsWith('Invalid currency code', $e->getMessage());
		}
		
		// using setMain bypasses the check that setCurrency does, so check the validator does it too
		$server->setField('Currency', 'AAA');
		$errors = $validator->validate($server)->getErrors();
		$this->assertArrayHasKey('Currency', $errors);
		$this->assertEquals($errors['Currency'], $validator->CURRENCY_INVALID);
	}

	public function testAmountIsValidFormat(){
		$validator = new Academe\SagePay\Validator\Server;
		$server = $this->getBasicServer();

		$server->setField('Amount', '1.1.1.1');
		$errors = $validator->validate($server)->getErrors();
		$this->assertArrayHasKey('Amount', $errors);
		$this->assertEquals($errors['Amount'], $validator->AMOUNT_BAD_FORMAT);
	}

	public function testAmountIsValidRange(){
		$validator = new Academe\SagePay\Validator\Server;
		$server = $this->getBasicServer();

		$server->setField('Amount', '0');
		$errors = $validator->validate($server)->getErrors();
		$this->assertArrayHasKey('Amount', $errors);
		$this->assertEquals($errors['Amount'], $validator->AMOUNT_BAD_RANGE);

		$server->setField('Amount', '200000');
		$errors = $validator->validate($server)->getErrors();
		$this->assertArrayHasKey('Amount', $errors);
		$this->assertEquals($errors['Amount'], $validator->AMOUNT_BAD_RANGE);

		$server->setField('Amount', '100,000');
		$errors = $validator->validate($server)->getErrors();
		$this->assertArrayNotHasKey('Amount', $errors);
	}

	public function testDescriptionLength(){
		$validator = new Academe\SagePay\Validator\Server;
		$server = $this->getBasicServer();

		$server->setField('Description', str_pad('', 101, '_'));
		$errors = $validator->validate($server)->getErrors();
		$this->assertArrayHasKey('Description', $errors);
		$correctError = sprintf($validator->BAD_RANGE, 'Description', $this->transactionData['Description']['min'], $this->transactionData['Description']['max']);
		$this->assertStringStartsWith($errors['Description'], $correctError);

		$server->setField('Description', str_pad('', 10, '_'));
		$errors = $validator->validate($server)->getErrors();
		$this->assertArrayNotHasKey('Description', $errors);
	}
}