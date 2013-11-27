<?php

namespace Academe\SagePay\Validator;

class ValidatorAbstract
{
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

	public function validate($item){}
}
