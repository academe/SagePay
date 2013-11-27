<?php

namespace Academe\SagePay\Validator\Model;

use Respect\Validation\Validator as v;
use Academe\SagePay\Metadata\Iso3166 as Iso3166;
use Academe\SagePay\Metadata\Transaction as Transaction;

class Address extends \Academe\SagePay\Validator\ValidatorAbstract
{

	public $STATE_ONLY_FOR_US = "State is only valid for the US";
	public $COUNTRY_VALID_CODE = "Country must be a valid country code";

	
	public $fieldsToCheck = array('Firstnames', 'Surname', 'Address1', 'Address2', 'City', 'PostCode', 'Country', 'State', 'Phone');

	public function validate($addr)
	{
		$this->clearErrors();
		// Do our special validations first

		// State should only be set for the US
		if ($addr->getField('Country') != 'US' && v::notEmpty()->validate($addr->getField('State'))) {
			$this->addError('State', $this->STATE_ONLY_FOR_US);
		}

		// Country must be an ISO3166-1 Country Code
		if (!array_key_exists($addr->getField('Country'), Iso3166::get())) {
			$this->addError('Country', $this->COUNTRY_VALID_CODE);
		}

		// Perform some general validations and return ourself
		return parent::validate($addr);
	}
}
