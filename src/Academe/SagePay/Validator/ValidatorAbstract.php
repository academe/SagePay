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

	public function clearErrors()
	{
		$this->errors = array();
	}

	public function validate($item){
		throw new \Exception("You must over-ride the Validate function in " . get_class($this));
	}
}
