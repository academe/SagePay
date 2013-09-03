<?php

/**
 * SagePay Direct services.
 * Work in progress as functionakity shared by Server, Direct and Shared
 * are moved from Register to Common.
 */

namespace Academe\SagePay;

use Academe\SagePay\Metadata as Metadata;
use Academe\SagePay\Exception as Exception;

class Direct extends Common
{
    /**
     * The SagePay method to be used.
     */

    protected $method = 'direct';
}

