<?php

/**
 * SagePay Direct services.
 * Work in progress as functionality shared by Server, Direct and Shared
 * are moved from Register to Common.
 */

namespace Academe\SagePay;

use Academe\SagePay\Metadata as Metadata;
use Academe\SagePay\Exception as Exception;

class Direct extends Shared
{
    /**
     * The SagePay method to be used.
     */

    protected $method = 'direct';
}

