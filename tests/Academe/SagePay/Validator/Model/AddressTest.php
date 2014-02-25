<?php

require 'vendor/autoload.php';

use Academe\SagePay;

class AddressModelValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testRequiredFields()
    {
        $addr = new Academe\SagePay\Model\Address;
        $validator = new Academe\SagePay\Validator\Model\Address;
        $validator->validate($addr);
        $errors = $validator->getErrors();
        $this->assertArrayHasKey('Firstnames', $errors);
        $this->assertArrayHasKey('Surname', $errors);
        $this->assertArrayHasKey('Address1', $errors);
        $this->assertArrayHasKey('City', $errors);
        $this->assertArrayHasKey('PostCode', $errors);
        $this->assertArrayHasKey('Country', $errors);
    }

    public function testPostcodeNotRequiredForIreland()
    {
        $addr = new Academe\SagePay\Model\Address;
        $addr->setField('Country', 'IE');

        $validator = new Academe\SagePay\Validator\Model\Address;
        $validator->validate($addr);

        $errors = $validator->getErrors();

        $this->assertArrayNotHasKey('Postcode', $errors);
    }

    public function testStateOnlyRequiredForUS()
    {
        $addr = new Academe\SagePay\Model\Address;
        $addr->setField('Country', 'GB');
        $addr->setField('State', 'AA');

        $validator = new Academe\SagePay\Validator\Model\Address;
        $validator->validate($addr);

        $errors = $validator->getErrors();
        
        $this->assertArrayHasKey('State', $errors);
        $this->assertEquals($errors['State'], $validator->STATE_ONLY_FOR_US);
    }
}
