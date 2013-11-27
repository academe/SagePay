<?php

namespace Academe\SagePay\Validator;
use Respect\Validation\Validator as v;
use Academe\SagePay\Exception as Exception;
use Academe\SagePay\Metadata as Metadata;
use Academe\SagePay\Metadata\Transaction as Transaction;

class Server extends ValidatorAbstract
{

	public $CURRENCY_INVALID = "Currency is not valid";
	public $AMOUNT_BAD_FORMAT = "Amount must be in the UK currency format XXXX.XX";
	public $AMOUNT_BAD_RANGE = "Amount must be between 0.01 and 100,000";

	public function validate($server)
	{
		$this->clearErrors();
		$metaData = Transaction::get('array');

		$fieldsToCheck = array('TxType', 'Vendor', 'Description', 'NotificationURL', 'Amount', 'Currency');
		foreach ($fieldsToCheck as $field) {
			$data = $metaData[$field];
			$value = $server->getField($field);
			// If it's required, check it's not empty
			if ($data['required'] && !v::notEmpty()->validate($value)) {

				// NotEmpty will consider a string containing 0 to be empty. That's not what we want with Amount
				if ($field == 'Amount') {
					if ($server->getField('Amount') != '0' && !v::string()->notEmpty()->validate($server->getField('Amount'))) {
						$this->addError('Amount', sprintf($this->CANNOT_BE_EMPTY, 'Amount'));
					}
				} else {
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

		// Check the currency is a valid one
        if ( ! Metadata\Iso4217::checkCurrency($server->getField('Currency'))) {
            $this->addError('Currency', $this->CURRENCY_INVALID);
        }

        $this->validateAmount($server->getField('Amount'));

		return $this;
	}

	/**
	*	An invalid amount gets automatically turned into 0 when you set it, so we need to validate seperately
	**/
	public function validateAmount($amount) {
		// Check the Amount is a valid format
        if ( ! v::regex('/^[0-9,]+(\.[0-9]{1,2})?$/')->validate($amount)) {
            $this->addError('Amount', $this->AMOUNT_BAD_FORMAT);
        } else if (v::regex('/,[0-9]{2}$/')->validate($amount)) {
        	// Detect possible use of comma as decimal delimiter
            $this->addError('Amount', $this->AMOUNT_BAD_FORMAT);
        }

        // Strip the Amount of commas so we can play with it as a number
        $amount = str_replace(',', '', $amount);
        // Lets work in pennies, because then we can't get confused with floats
        $amount = $amount * 100;
        if ( $amount < 1 || $amount > 10000000) {
            $this->addError('Amount', $this->AMOUNT_BAD_RANGE);
        }
        return $this;
	}
}

