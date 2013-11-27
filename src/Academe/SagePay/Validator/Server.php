<?php

namespace Academe\SagePay\Validator;
use Respect\Validation\Validator as v;

class Server extends ValidatorAbstract
{
	public $COUNTRY_VALID_CODE = "Country must be a valid country code";

	public function validate($addr)
	{

	}
}
