<?php

namespace Academe\SagePay\Helper;

/**
 * Base model abstract for models that generate XML as their main output.
 * TODO: split the currency stuff out to a separate abstract. Currency details
 * are needed by the main service classes, but it makes no sense having
 * ServieceAbstract inheriting the XML functions here.
 */

use Academe\SagePay\Exception as Exception;

class Helper
{
    /**
     * Check a currency code is valid ISO4217.
     */
    public static function checkCurrency($currency)
    {
        // Validate against the ISO4217 metadata and throw an exception if needed.
        // The card handler still may not accept this currency, but at least it will
        // be a valid currency code.

        $currency = strtoupper($currency);

        if ( ! \Academe\SagePay\Metadata\Iso4217::checkCurrency($currency)) {
            throw new Exception\InvalidArgumentException("Invalid currency code '{$currency}'");
        }

        return $currency;
    }

    /**
     * Format a monetory amount to the relavant number of decimal places as required by SagePay.
     */

    public static function formatAmount($amount, $currency)
    {
        // We need a numeric value for the amount, so make sure it is, even if it is a number in a string.
        //if ( ! is_numeric($amount)) $amount = 0;

        // Get the minor unit of the currency - the number of digits after the decimal point.
        // SagePay requires the amount to be padded out to the exact number of decimal places,
        // and that will vary from one currency to another.
        // At least the decimal point (dot) has been agreed to as a standard whole/decimal separator.

        // If the minor unit is null, then the currency code is not valid.
        $minor_unit = \Academe\SagePay\Metadata\Iso4217::minorUnit($currency);

        if ( ! isset($minor_unit)) {
            throw new Exception\InvalidArgumentException("Invalid currency code '{$currency}'");
        }

        // Do a regex check, if a string.
        if (is_string($amount)) {
            if ( ! preg_match('/^[0-9][0-9,]*(\.[0-9]{0,' . $minor_unit . '})?$/', $amount)) {
                throw new Exception\InvalidArgumentException("Invalid amount format '{$amount}'");
            }

            // Remove any comma thousands separators.
            $amount = str_replace(',', '', $amount);
        }

        return (isset($minor_unit) ? number_format((float)$amount, $minor_unit, '.', '') : null);
    }
}

