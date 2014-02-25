<?php

require 'vendor/autoload.php';

use Academe\SagePay;

class ServerTest extends PHPUnit_Framework_TestCase
{
    private $server;
    private $storage;

    public function setUp()
    {
        $server = new Academe\SagePay\Server();
        $storage = new Academe\SagePay\Model\TransactionPdo();
        $storage->setDatabase('sqlite:memory', '', '');

        $server->setTransactionModel($storage);
        $server->setPlatform('test');

        $this->server = $server;
        $this->storage = $storage;
    }

    public function testStorageIsStorage()
    {
        $this->assertInstanceOf('Academe\SagePay\Model\TransactionPdo', $this->storage);
    }

    public function testServerIsServer()
    {
        $this->assertInstanceOf('Academe\SagePay\Server', $this->server);
    }

    public function testSetMain()
    {
        $this->server->setMain('PAYMENT', 'vendorx', '99.99', 'GBP', 'Store purchase', 'http://example.com/mycallback.php');
        $this->assertEquals($this->server->getField('TxType'), 'PAYMENT');
    }
}
