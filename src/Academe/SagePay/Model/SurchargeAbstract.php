<?php

/**
 * Manages surcharge data, with an end result being an XML string.
 * The surcharges can be set for an account, but can also be set and overridden
 * individually for each payment request. So for example, the surcharges can
 * be set at different rates for different customers or for different basket
 * costs.
 */

namespace Academe\SagePay\Model;

// Helper\Helper is the helper class.
use Academe\SagePay\Helper\Helper;

abstract class SurchargeAbstract extends XmlAbstract
{
    /**
     * The list of surcharges.
     */

    protected $surcharges = array();

    /**
     * Add a percentage surcharge.
     * Set the currency *before* adding a percentage amount.
     * CHECKME: should this percentage be formatted the same as the currency? I suspect not.
     */

    public function addPercentage($payment_type, $percentage)
    {
        $this->surcharges[]['surcharge'] = array(
            'paymentType' => strtoupper($payment_type),
            'percentage' => Helper::formatAmount($percentage, $this->currency),
        );

        return $this;
    }

    /**
     * Add a fixed amount surcharge.
     * Set the currency *before* adding a fixed amount.
     */

    public function addFixed($payment_type, $fixed)
    {
        $this->surcharges[]['surcharge'] = array(
            'paymentType' => strtoupper($payment_type),
            'fixed' => Helper::formatAmount($fixed, $this->currency),
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

