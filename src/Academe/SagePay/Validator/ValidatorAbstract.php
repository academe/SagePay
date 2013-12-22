<?php

namespace Academe\SagePay\Validator;

use Respect\Validation\Validator as v;
use Academe\SagePay\Metadata\Iso3166 as Iso3166;
use Academe\SagePay\Metadata\Transaction as Transaction;

class ValidatorAbstract
{
	public $CANNOT_BE_EMPTY = "%s cannot be empty";
	public $BAD_RANGE = "%s must be between %d and %d characters";
	public $BAD_LENGTH = "%s must be exactly %d characters";
	public $BAD_CHARACTERS = "%s cannot contain the following characters %s";
	// Use this one for debugging the Regexs
	// public $BAD_CHARACTERS = "%s cannot contain the following characters %s regex %s";

	public $fieldsToCheck = array();
	public $countriesWhichDontHavePostcodes = array('IE');

	protected $errors = array();

	public function getErrors(){
		return $this->errors;
	}

	public function hasError($field){
		return (bool) isset($this->errors[$field]);
	}

	public function addError($field, $message)
	{
		// Only keep the first error per field, this is because latter
		// errors may well be fixed by fixing earlier ones.
		if (!isset($this->errors[$field])) {
			$this->errors[$field] = $message;
		}
	}

	public function clearErrors()
	{
		$this->errors = array();
	}

	public function validate($item){
		$metaData = Transaction::get('array');

		foreach ($this->fieldsToCheck as $field) {
			if (is_a($this, 'Academe\SagePay\Validator\Model\Address')) {
				// I'm assuming/hoping that Billing and Delivery validation rules are identical
				$data = $metaData['Billing'.$field];
			} else {
				$data = $metaData[$field];
			}
			$value = $item->getField($field);

			if ($this->hasError($field)) {
				// We only store one error per field, so if we have already got an error for this
				// field then don't waste time checking others. This also means that we can have
				// more specific fields in the child objects which over-ride these completly.
				continue;
			}
			// State is only used when the country is US, the validation rules stores assume country is US.
			// so here we tweak them.
			if ($field == 'State' && $item->getField('Country') != 'US') {
				$data['required'] = false;
				$data['min'] = 0;
				$data['max'] = 0;
			}

			// If the item is required, check it's not empty
			if ($data['required'] && !v::notEmpty()->validate($value)) {
				if ($field =='PostCode'){
					// Add an exception for Postcodes when the country is one which does not have postcodes
					if(!in_array($item->getField('Country'), $this->countriesWhichDontHavePostcodes)) {
						$this->addError($field, sprintf($this->CANNOT_BE_EMPTY, $field));
					}
				} else if ($field == 'Amount') {
					// 0 equates to empty, so do a special check
					if ($item->getField('Amount') != '0' && !v::string()->notEmpty()->validate($item->getField('Amount'))) {
						$this->addError($field, sprintf($this->CANNOT_BE_EMPTY, $field));
					}
				} else {
					$this->addError($field, sprintf($this->CANNOT_BE_EMPTY, $field));
				}
			}

			// If there is a minimum or maximum check the length.
			// TODO: Check whether this code works well when only one or the other is set
			$min = isset($data['min']) ? $data['min'] : null;
			$max = isset($data['max']) ? $data['max'] : null;
			if ($min != null && $max != null) {
				// Check the length of the field
				if ($field == 'State') {
					print_r("\n\nMin: $field : $min, \n\n");
					die;
				}
				if (!v::length($min, $max, true)->validate($value)) {
					if ($min == $max) {
						$this->addError($field, sprintf($this->BAD_LENGTH, $field, $min));
					} else {
						$this->addError($field, sprintf($this->BAD_RANGE, $field, $min, $max));
					}
				}
			}

			// Check the contents of the field
			if(isset($data['chars'])) {
				// We build two regexes, one for testing whether it matches and the other for
				// filtering out the bad characters to show the user which are not valid.
				$regex = $this->buildRegex($data['chars']);
				try{
					if (!v::regex($regex)->validate($value)){
						$cleanupRegex = $this->buildRegex($data['chars'], false);
						$badChars = preg_replace($cleanupRegex, '', $value);
						$this->addError($field, sprintf($this->BAD_CHARACTERS, $field, $badChars, $regex));
					}
				} catch (\Exception $e) {
					throw new \Exception("preg_match has a problem with this regex '{$regex}'");
				}
			}
		}
		return $this;
	}

	public function buildRegex($chars, $matchAll = true){
		// chr(93) = \
		$regexParts = array();
		$regexSpecialChars = array('.',chr(92),'+','*','?','[','^',']','$','(',')','{','}','=','!','<','>','|',':','-');
		foreach ($chars as $char){
			if ($char == 'A') {			// Upper case ASCII letters a-z
				$regexParts[] = 'A-Z';
			} else if ($char == 'a') {	// Lower case ASCII letters a-z
				$regexParts[] = 'a-z';
			} else if ($char == '9') {	// Digits 0-9
				$regexParts[] = '0-9';
			} else if ($char == '^') {	// Extended ASCII letters
				$regexParts[] = '[:print:]';
			} else if ($char == '/') {	// Forward or backward slashes
				$regexParts[] = '/'.chr(92).chr(92);
			} else if ($char == '(') {	// Open or close brackets
				$regexParts[] = '\(\)';
			} else if ($char == '{') {	// Open or close curly brackets
				$regexParts[] = '\{\}';
			} else if ($char == 'n') {	// Carriage return and line feed
				$regexParts[] = '\n\r';
			} else if ($char == '*') {	// No restrictions
				$regexParts[] = ".";
			} else {
				if (in_array($char, $regexSpecialChars)) {
					$regexParts[] = chr(92).$char;
				} else {
					$regexParts[] = $char;
				}
			}
		}
		$regex = join('', $regexParts);

		// Find a delimterer which isn't in the string. Hopefully Sage will never use all of these.
		$possibleDelimeters = array('/', '_', '@', '#', '%', '£', '!');
		foreach ($possibleDelimeters as $pd) {
			if (strpos($regex, $pd) === false) {
				break;
			}
		}

		$regex = '['.$regex.']';
		if ($matchAll) {
			$regex = '^'.$regex.'*$';
		}
		$regex = $pd.$regex.$pd;
		return $regex;
	}
}
