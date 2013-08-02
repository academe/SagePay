<?php

/**
 * A PDO data store specifoc for use in WordPress.
 */

namespace Academe\SagePay\Model;

class TransactionPdoWordpress extends TransactionPdo
{
    public function __construct()
    {
        // Set the database connection details.
        // WordPress makes them available as defines.
        $this->setDatabase('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

        parent::__construct();
    }
}
