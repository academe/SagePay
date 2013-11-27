<?php

namespace Academe\SagePay\Validator;

class ValidatorAbstract
{
	public $CANNOT_BE_EMPTY = "%s cannot be empty";
	public $BAD_RANGE = "%s must be between %d and %d characters";
	public $BAD_CHARACTERS = "%s cannot contain the following characters %s";

	protected $errors = array();

	public function getErrors(){
		return $this->errors;
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
		throw new \Exception("You must over-ride the Validate function in " . get_class($this));
	}

	public function buildRegex($chars, $matchAll = true){
		$regexParts = array();
		foreach ($chars as $char){
			if ($char == 'A') {			// Upper case ASCII letters a-z
				$regexParts[] = 'A-Z';
			} else if ($char == 'a') {	// Lower case ASCII letters a-z
				$regexParts[] = 'a-z';
			} else if ($char == '9') {	// Digits 0-9
				$regexParts[] = '0-9';
			} else if ($char == '^') {	// Extended ASCII letters
				$regexParts[] = ':print:';
			} else if ($char == '/\\') {	// Forward or backward slashes
				$regexParts[] = '';
			} else if ($char == '\(\)') {	// Open or close brackets
				$regexParts[] = '';
			} else if ($char == '\{\}') {	// Open or close curly brackets
				$regexParts[] = '';
			} else if ($char == '\+') {	// Plus
				$regexParts[] = '';
			} else if ($char == 'n') {	// Carriage return and line feed
				$regexParts[] = '\n\r';
			} else if ($char == '\&') {	// Ampersand
				$regexParts[] = '';
			} else if ($char == '*') {	// No restrictions
				$regexParts[] = ".";
			} else if ($char == '.') {	// No restrictions
				$regexParts[] = "\.";
			} else {
				$regexParts[] = $char;
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
