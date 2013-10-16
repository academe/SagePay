<?php

/**
 * Base model abstract for models that generate XML as their main output.
 * TODO: split the currency stuff out to a separate abstract. Currency details
 * are needed by the main service classes, but it makes no sense having
 * ServieceAbstract inheriting the XML functions here.
 */

namespace Academe\SagePay\Model;

use Academe\SagePay\Exception as Exception;

abstract class XmlAbstract
{
    /**
     * The currency used for formatting.
     */

    protected $currency = 'GBP';

    /**
     * Set the currency for amount formatting.
     * The three-character ISO4217 currency code is required.
     */

    public function setCurrency($currency)
    {
        // Validate against the ISO4217 metadata and throw an exception if needed.
        // The card handler still may not accept this currency, but at least it will
        // be a valid currency code.

        $currency = strtoupper($currency);
        $all_currencies = \Academe\SagePay\Metadata\Iso4217::get('array');

        if ( ! isset($all_currencies[$currency])) {
            throw new Exception\InvalidArgumentException("Invalid currency code '{$currency}'");
        }

        $this->currency = $currency;

        return $this;
    }

    /**
     * Format a monetory amount to the relavant number of decimal places as required by SagePay.
     */

    protected function formatAmount($amount, $currency = null)
    {
        // Use the currency, if passed in.
        // TODO: we should validate the currency passed in, to make sure it is valid.
        // Better still, the currency formatting can be pulled out to a helper class, as it is
        // used in many places (all XML formatting models and the main transaction process.
        $use_currency = (isset($currency) ? strtoupper($currency) : $this->currency);

        // We need a numeric value, so make sure it is, even if it is a number in a string.
        if ( ! is_numeric($amount)) $amount = 0;

        // Get the minor unit of the currency - the number of digits after the decimal point.
        // SagePay requires the amount to be padded out to the exact number of decimal places.

        $minor_unit = \Academe\SagePay\Metadata\Iso4217::get()
            ->{$use_currency}
            ->minor;

        return number_format($amount, $minor_unit, '.', '');
    }

    /**
     * Format an XML fragment - a single array.
     * Recurse for nested arrays.
     */

    protected function xmlFragment($data)
    {
        $fragment = '';

        // If there is an object here, then pull out its public properties.
        // Use the built-in toArrat() if it exists.
        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } else {
                $data = get_object_vars($data);
            }
        }

        if (is_array($data)) {
            foreach($data as $key => $value) {
                // Skip a level for numeric keys. They are just so we can have a list
                // of itentical tags together on the same level.
                if (is_numeric($key)) {
                    $fragment .= $this->xmlFragment($value);
                } else {
                    // If the keys have special characters in, then we are certainly in
                    // trouble, but try and make a best job of it.
                    // Open the element.
                    $fragment .= '<' . htmlspecialchars($key) . '>';

                    if (is_string($value) || is_numeric($value)) {
                        $fragment .= htmlspecialchars($value);
                    } elseif (is_bool($value)) {
                        // Can also be "true" or "false" in some places, but documentation is not
                        // clear if that is a general rule.
                        $fragment .= ($value ? '1' : '0');
                    } elseif (is_array($value) || is_object($value)) {
                        $fragment .= $this->xmlFragment($value);
                    }

                    // Close the element.
                    $fragment .= '</' . htmlspecialchars($key) . '>';
                }
            }
        }

        return $fragment;
    }
}


