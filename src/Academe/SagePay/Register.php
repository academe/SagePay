<?php

/**
 * DEPRECATED.
 *
 * The services have been split up into Server, Direct and Shared.
 * This class extends Server for legacy compatibility, but please use Server
 * instead.
 */

namespace Academe\SagePay;

use Academe\SagePay\Metadata as Metadata;
use Academe\SagePay\Exception as Exception;

class Register extends Server
{
}

