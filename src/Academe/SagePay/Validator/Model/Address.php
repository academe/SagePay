<?php

namespace Academe\SagePay\Validator\Model;
use Respect\Validation\Validator as v;
use Academe\SagePay\Metadata\Iso3166 as Iso3166;
use Academe\SagePay\Metadata\Transaction as Transaction;

class Address extends \Academe\SagePay\Validator\ValidatorAbstract
{

	public $STATE_ONLY_FOR_US = "State is only valid for the US";
	public $COUNTRY_VALID_CODE = "Country must be a valid country code";

	private $countriesWhichDontHavePostcodes = array('IE');
	
	public function validate($addr)
	{
		$this->clearErrors();
		$metaData = Transaction::get('array');
		$fieldsToCheck = array('Firstnames', 'Surname', 'Address1', 'Address2', 'City', 'PostCode', 'Country', 'State', 'Phone');
		foreach ($fieldsToCheck as $field) {
			// I'm assuming/hoping that Billing and Delivery validation rules are identical
			$data = $metaData['Billing'.$field];
			if ($data['required'] && !v::notEmpty()->validate($addr->getField($field))) {
				// Add an exception for Postcodes when the country is one which does not have postcodes
				if (!($field =='PostCode' && in_array($addr->getField('Country'), $this->countriesWhichDontHavePostcodes))) {
					$this->addError($field, sprintf($this->CANNOT_BE_EMPTY, $field));
				}
			}

			if (isset($data['min'], $data['max'])) {
				// Check the length of the field
				if (!v::length($data['min'], $data['max'])->validate($value)) {
					$this->addError($field, sprintf($this->BAD_RANGE, $field, $data['min'], $data['max']));
				}
			}

			// Check the contents of the field
			if(isset($data['chars'])) {
				// We build two regexes, one for testing whether it matches and the other for
				// filtering out the bad characters to show the user which are not valid.
				$regex = $this->buildRegex($data['chars']);
				if (!v::regex($regex)->validate($value)){
					$cleanupRegex = $this->buildRegex($data['chars'], false);
					$badChars = preg_replace($cleanupRegex, '', $value);
					$this->addError($field, sprintf($this->BAD_CHARACTERS, $field, $badChars));
				}
			}
		}

		// State should only be set for the US
		if ($addr->getField('Country') !== 'US' && v::notEmpty()->validate($addr->getField('State'))) {
			$this->addError('State', $this->STATE_ONLY_FOR_US);
		}

		// Country must be an ISO3166-1 Country Code
		if (!in_array($addr->getField('Country'), Iso3166::get())) {
			$this->addError('Country', $this->COUNTRY_VALID_CODE);
		}
		return $this;
	}
}
