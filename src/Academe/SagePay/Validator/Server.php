<?php

namespace Academe\SagePay\Validator;
use Respect\Validation\Validator as v;
use Academe\SagePay\Exception as Exception;
use Academe\SagePay\Metadata as Metadata;

class Server extends ValidatorAbstract
{
	public $TX_TYPE_EMPTY = "TxType must not be empty";
	public $VENDOR_EMPTY = "Vendor must not be empty";
	public $DESCRIPTION_EMPTY = "Description must not be empty";
	public $NOTIFICATION_URL_EMPTY = "Notification URL must not be empty";
	public $AMOUNT_EMPTY = "Amount must not be empty";
	public $CURRENCY_EMPTY = "Currency must not be empty";
	public $CURRENCY_INVALID = "Currency is not valid";
	public $AMOUNT_BAD_FORMAT = "Amount must be in the UK currency format XXXX.XX";
	public $AMOUNT_BAD_RANGE = "Amount must be between 0.01 and 100,000";
	public $DESCRIPTION_TOO_LONG = "Description can only be 100 characters long";

	public function validate($server)
	{
		$this->clearErrors();
		if (!v::notEmpty()->validate($server->getField('TxType'))) {
			$this->addError('TxType', $this->TX_TYPE_EMPTY);
		}
		if (!v::notEmpty()->validate($server->getField('Vendor'))) {
			$this->addError('Vendor', $this->VENDOR_EMPTY);
		}
		if (!v::notEmpty()->validate($server->getField('Description'))) {
			$this->addError('Description', $this->DESCRIPTION_EMPTY);
		}
		if (!v::notEmpty()->validate($server->getField('NotificationURL'))) {
			$this->addError('NotificationURL', $this->NOTIFICATION_URL_EMPTY);
		}
		// NotEmpty will consider a string containing 0 to be empty. That's not what we want with Amount
		if ($server->getField('Amount') != '0' && !v::string()->notEmpty()->validate($server->getField('Amount'))) {
			$this->addError('Amount', $this->AMOUNT_EMPTY);
		}
		if (!v::notEmpty()->validate($server->getField('Currency'))) {
			$this->addError('Currency', $this->CURRENCY_EMPTY);
		}

		// Check the currency is a valid one
        if ( ! Metadata\Iso4217::checkCurrency($server->getField('Currency'))) {
            $this->addError('Currency', $this->CURRENCY_INVALID);
        }

		if (!v::length(1, 100)->validate($server->getField('Description'))) {
			$this->addError('Description', $this->DESCRIPTION_TOO_LONG);
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
