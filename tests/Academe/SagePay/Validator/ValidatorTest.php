<?php

require 'vendor/autoload.php';

use Academe\SagePay;
use Academe\SagePay\Metadata\Transaction as Transaction;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    public function getBasicServer()
    {
        $server = new Academe\SagePay\Server;
        $storage = new Academe\SagePay\Model\TransactionPdo("sqlite:memory");

        $server->setPlatform('test');
        $server->setTransactionModel($storage);

        // Start with valid details
        $server->setMain('PAYMENT', 'vendorx', '99.99', 'GBP', 'Store purchase', 'http://example.com/mycallback.php');

        return $server;
    }

    public function testCustomErrorMessages()
    {
        $validator = new Academe\SagePay\Validator\Server;
        $server = $this->getBasicServer();

        // Set the custom error message
        $customErrorMessage = "This is not a real error message";
        $validator->CANNOT_BE_EMPTY = $customErrorMessage;

        // Empty a field which should not be empty
        $server->setField('Vendor', '');

        // Call the errors, and check the error message matches
        $errors = $validator->validate($server)->getErrors();
        $this->assertEquals($errors['Vendor'], $customErrorMessage);
    }
}