<?php

/**
 * Models a basket of products, with an XML output.
 * A basket can have quite a complex structure of elements. Much of the options
 * will not be supported in the first phase.
 */

namespace Academe\SagePay\Model;

abstract class BasketAbstract extends XmlAbstract
{
    /**
     * Optional agent ID if a phone payment.
     */

    protected $agentId = null;

    /**
     * Lines in the basket.
     */

    protected $lines = array();

    /**
     * Delivery details (optional).
     */

    protected $delivery = null;

    /**
     * Shipping details (optional).
     */

    protected $shipping = null;

    /**
     * Set delivery details.
     * All numbes will be formatted to 2dp.
     */

    public function setDelivery($net, $tax = 0, $gross = null)
    {
        if ( ! isset($gross)) {
            $gross = $net + $tax;
        }

        $this->delivery = array(
            'deliveryNetAmount' => $this->formatAmount($net),
            'deliveryTaxAmount' => $this->formatAmount($tax),
            'deliveryGrossAmount' => $this->formatAmount($gross),
        );

        return $this;
    }

    /**
     * Set shipping details.
     */

    public function setShipping($ship_id, $shipping_method = '', $shipping_fax_no = '')
    {
        $this->shipping = array(
            'shipId' => $ship_id,
            'shippingMethod' => $shipping_method,
            'shippingFaxNo' => $shipping_fax_no,
        );

        return $this;
    }

    /**
     * Add a simple product line, with values similar to the old (non-XML) basket.
     * This adds a array-based product line. The more complex lines will involve adding
     * an object.
     */

    public function addSimpleLine($description, $quantity, $unit_net, $unit_tax = 0, $unit_gross = null, $line_gross = NULL)
    {
        // Fill in some gaps, if required.
        if ( ! isset($unit_gross)) {
            $unit_gross = $unit_net + $unit_tax;
        }

        if ( ! isset($line_gross)) {
            $line_gross = $unit_gross * $quantity;
        }

        $this->lines[] =  array(
            'description' => $description,
            'quantity' => $quantity,
            'unitNetAmount' => $this->formatAmount($unit_net),
            'unitTaxAmount' => $this->formatAmount($unit_tax),
            'unitGrossAmount' => $this->formatAmount($unit_gross),
            'totalGrossAmount' => $this->formatAmount($line_gross),
        );
    }

    /**
     * Return the basket as an XML string.
     * There are no attributes in this XML, which makes things a little simpler.
     */

    public function toXml()
    {
        $structure = array();

        // Optional agent ID
        if (isset($this->agentId)) $structure['agentId'] = $this->agentId;

        // Add each line.
        foreach($this->lines as $line) {
            if (is_array($line)) {
                $structure[]['item'] = $line;
            }
        }

        // Optional delivery costs.
        if (isset($this->delivery)) {
            $structure = array_merge($structure, $this->delivery);
        }

        // Shipping details.
        if (isset($this->shipping)) {
            $structure = array_merge($structure, $this->shipping);
        }

        return $this->xmlFragment(array('basket' => $structure));
    }
}

