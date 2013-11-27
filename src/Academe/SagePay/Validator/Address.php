<?php

namespace Academe\SagePay\Validator;
use Respect\Validation\Validator as v;
use Academe\SagePay\Metadata\Iso3166 as Iso3166;

class Address extends ValidatorAbstract
{
	public $COUNTRY_VALID_CODE = "Country must be a valid country code";
	public $FIRSTNAMES_EMPTY = "Firstnames cannot be empty";
	public $SURNAME_EMPTY = "Surname cannot be empty";
	public $ADDRESS1_EMPTY = "Address1 cannot be empty";
	public $CITY_EMPTY = "City cannot be empty";
	public $POSTCODE_EMPTY = "Postcode cannot be empty";
	public $COUNTRY_EMPTY = "Country cannot be empty";
	public $STATE_ONLY_FOR_US = "State is only valid for the US";

	private $countriesWhichDontHavePostcodes = array('IE');
	
	public function validate($addr)
	{
		if (!v::notEmpty()->validate($addr->getField('Firstnames'))) {
			$this->addError('Firstnames', $this->FIRSTNAMES_EMPTY);
		}
		if (!v::notEmpty()->validate($addr->getField('Surname'))) {
			$this->addError('Surname', $this->SURNAME_EMPTY);
		}
		if (!v::notEmpty()->validate($addr->getField('Address1'))) {
			$this->addError('Address1', $this->ADDRESS1_EMPTY);
		}
		if (!v::notEmpty()->validate($addr->getField('City'))) {
			$this->addError('City', $this->CITY_EMPTY);
		}
		if (!v::notEmpty()->validate($addr->getField('PostCode'))) {
			// Postcode is required for all countries excpet those which don't have a postcode
			if (!in_array($addr->getField('Country'), $this->countriesWhichDontHavePostcodes)) {
				$this->addError('PostCode', $this->POSTCODE_EMPTY);
			}
		}
		if (!v::notEmpty()->validate($addr->getField('Country'))) {
			$this->addError('Country', $this->COUNTRY_EMPTY);
		}

		// State should only be set for the US
		if ($addr->getField('Country') !== 'US' && v::notEmpty()->validate($addr->getField('State'))) {
			$this->addError('State', $this->STATE_ONLY_FOR_US);
		}

		// Country must be an ISO3166-1 Country Code
		if (!in_array($addr->getField('Country'), Iso3166::get())) {
			$this->addError('Country', $this->COUNTRY_VALID_CODE);
		}

	}
}
