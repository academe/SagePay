<?php

/**
 * The transport class used to communicate with SagePay.
 * This is likely to be a simple curl wrapper, but something more
 * complex such as Guzzle could be used if there are specific network
 * requirements.
 * This needs to be done for testing, if nothing else, so the remote
 * communications can be mocked.
 */

namespace Academe\SagePay;

use Academe\SagePay\Exception as Exception;

abstract class TransportAbstract
{
    /**
     * The communications timeout, in seconds.
     * SagePay will be given this long to 
     */

    protected $timeout = 30;

    /**
     * Send a POST message to SagePay.
     *
     * @param $url string The URL to connect to SagePay
     * @param $post arrat The key/value array of data to send.
     * @returns TBC
     */

    abstract public function postSagePay($url, $query) {}

    /**
     * Set the timeout period, in seconds.
     * Not all concrete classes will use this, but the majority will.
     * Override it if you want some validation.
     */

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}
