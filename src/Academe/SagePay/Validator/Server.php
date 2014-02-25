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

    public $fieldsToCheck = array('TxType', 'Vendor', 'Description', 'NotificationURL', 'Amount', 'Currency');
    public function validate($server)
    {
        $this->clearErrors();
        $metaData = Transaction::get('array');

        // Check the currency is a valid one
        if ( ! Metadata\Iso4217::checkCurrency($server->getField('Currency'))) {
            $this->addError('Currency', $this->CURRENCY_INVALID);
        }

        $this->validateAmount($server->getField('Amount'));

        // Perform some general validations and return ourself
        return parent::validate($server);
    }

    /**
    * An invalid amount gets automatically turned into 0 when you set it, so we need to validate seperately
    */
    public function validateAmount($amount)
    {
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
