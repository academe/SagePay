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
     * Add a percentage surcharge.
     */

    public function addPercentage($payment_type, $percentage)
    {
        $this->surcharges[]['surcharge'] = array(
            'paymentType' => strtoupper($payment_type),
            'percentage' => $this->formatAmount($percentage),
        );
    }

    /**
     * Add a fixed amount surcharge.
     */

    public function addFixed($payment_type, $fixed)
    {
        $this->surcharges[]['surcharge'] = array(
            'paymentType' => strtoupper($payment_type),
            'fixed' => $this->formatAmount($fixed),
        );
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

