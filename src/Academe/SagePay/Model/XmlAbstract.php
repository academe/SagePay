<?php

/**
 * Base model abstract for models that generate XML as their main output.
 */

namespace Academe\SagePay\Model;

abstract class XmlAbstract
{
    /**
     * Format a monetory amount to 2dp, as required by SagePay.
     */

    protected function formatAmount($amount)
    {
        // We need a numeric value, so make sure it is.
        if ( ! is_numeric($amount)) $amount = 0;

        return money_format('%!.2n', $amount);
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


