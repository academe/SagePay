<?php

require 'vendor/autoload.php';

use Academe\SagePay;

class TransactionPdoSqliteTest extends PHPUnit_Framework_TestCase{

    // For some reason SQLite fails with an in-memory database.

    protected $dbFileName;
    protected $server;
    protected $storage;

    protected function setUp(){
        $this->dbFileName = ':memory:';
        
        $this->storage = new Academe\SagePay\Model\TransactionPdo();
        $this->storage->setDatabase('sqlite:'.$this->dbFileName, null, null);

        $this->server = new Academe\SagePay\Server();
        $this->server->setTransactionModel($this->storage);
    }

    public function testCreateTable(){
        $success = $this->storage->createTable();
        $this->assertTrue($success);
    }

    public function testCreateTransaction(){
        $this->storage->createTable();
        
        $this->server->setMain('PAYMENT', 'vendorx', '99.99', 'GBP', 'Store purchase', 'http://example.com/mycallback.php');
        $success = $this->server->save();
        $this->assertTrue($success);
    }

    public function testUpdateTransaction(){
        $this->storage->createTable();
        // Create a model
        $this->server->setMain('PAYMENT', 'vendorx', '99.99', 'GBP', 'Store purchase', 'http://example.com/mycallback.php');
        $this->server->save();

        // Now change the model, and check we can update it
        $this->server->setField('Amount', '10');
        $success = $this->server->save();
        $this->assertTrue($success);
    }
}