<?php

/**
 * Concrete transport class, built in curl.
 */

namespace Academe\SagePay\Transport;

use Academe\SagePay\Exception as Exception;

abstract class Curl extends Academe\SagePay\TransportAbstract
{
    /**
     * POST a message to SagePay and return the result as TBC.
     */

    public function postSagePay($url, $query)
    {
    }
}

