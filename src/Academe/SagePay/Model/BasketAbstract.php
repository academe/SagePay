<?php

/**
 * Models a basket of products, with an XML output.
 * A basket can have quite a complex structure of elements. Much of the options
 * will not be supported in the first phase.
 * Note: all gross amounts (including the shipping cost) in the basket MUST add up
 * to the total amount of the transaction. SagePay will reject the basket if they
 * do not.
 * Note also that most optional basket fields have a minimum length of one character.
 * The basket will be rejected if any of these fields are zero length. They must instead
 * be left out competely.
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
     * The hotel array, containing mandatory and optional fields, but being
     * optional as a whole.
     */

    protected $hotel = null;

    /**
     * Set delivery details.
     * All amounts will be formatted to 2dp.
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
     * SagePay will reject empty fields as invalid. Only include fields that have data.
     * Thos rule will apply to the man additional fields this XML basket introduces but
     * afre not supported in this library yet.
     */

    public function setShipping($shipId, $shippingMethod = null, $shippingFaxNo = null)
    {
        $shipping = array();

        foreach(array('shipId', 'shippingMethod', 'shippingFaxNo') as $param) {
            if (isset($$param) && $$param != '') {
                $shipping[$param] = $$param;
            }
        }

        if ( !empty($shipping)) {
            $this->shipping = $shipping;
        }

        return $this;
    }

    /**
     * Set the main (mandatory) hotel fields.
     */

    public function setHotel($checkIn, $checkout, $numberInParty, $guestName)
    {
        $this->setHotelField('checkIn', $checkIn);
        $this->setHotelField('checkOut', $checkout);
        $this->setHotelField('numberInParty', $numberInParty);
        $this->setHotelField('guestName', $guestName);

        return $this;
    }

    /**
     * Set optional hotel fields.
     */

    public function setHotelField($name, $value)
    {
        // If we don't have a hotel record yet, then create one.
        if ( ! isset($this->hotel)) {
            $this->hotel = array();
        }

        $this->hotel[$name] = $value;
    }

    /**
     * Add a simple product line, with values similar to the old (non-XML) basket.
     * This adds a product line array to the lines array. The more complex lines with other details
     * will involve adding a line as an object. Simple lines and complex lines can be freely mixed.
     */

    public function addSimpleLine($description, $quantity, $unit_net, $unit_tax = 0, $unit_gross = null, $line_gross = null)
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
     * Add a line object.
     */

    public function addLine(ProductLineAbstract $line)
    {
        $this->lines[] = $line;
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

            if (is_object($line)) {
                // Get the array format rather than the XML format, so don't
                // double XML-encode the product line.
                $structure[] = $line->toArray();
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

        if (isset($this->hotel)) {
            $structure = array_merge($structure, array('hotel' => $this->hotel));
        }

        return $this->xmlFragment(array('basket' => $structure));
    }
}

