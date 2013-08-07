<?php

/**
 * Manages surcharge data, with an end result being an XML string.
 */

namespace Academe\SagePay\Model;

abstract class SurchargeAbstract extends XmlAbstract
{
    /**
     * The list of surcharges.
     */

    protected $surcharges = array();

    /**
     * The currency used for formatting.
     */

    protected $currency = 'GBP';

    /**
     * Set the currency for amount formatting.
     * Set the currency *before* setting any surcharges, as it does not get reformatted
     * during XML generation (this may be fixed in later versions).
     */

    public function setCurrency($currency)
    {
        $this->currency = strtoupper($currency);

        return $this;
    }

    /**
     * Add a percentage surcharge.
     */

    public function addPercentage($payment_type, $percentage)
    {
        $this->surcharges[]['surcharge'] = array(
            'paymentType' => strtoupper($payment_type),
            'percentage' => $this->formatAmount($percentage, $this->currency),
        );

        return $this;
    }

    /**
     * Add a fixed amount surcharge.
     */

    public function addFixed($payment_type, $fixed)
    {
        $this->surcharges[]['surcharge'] = array(
            'paymentType' => strtoupper($payment_type),
            'fixed' => $this->formatAmount($fixed, $this->currency),
        );

        return $this;
    }

    /**
     * Format the surcharges as XML.
     */

    public function toXml()
    {
        if ( ! empty($this->surcharges)) {
            return $this->xmlFragment(array('surcharges' => $this->surcharges));
        } else {
            return '';
        }
    }
}

